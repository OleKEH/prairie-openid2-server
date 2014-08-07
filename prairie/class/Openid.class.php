<?php

// -----------------------------------------------------------------------
// This file is part of Prairie
// Openid.class.php
// Copyright (C) 2003-2008 Barnraiser
// http://www.barnraiser.org/
// info@barnraiser.org
// 
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
// 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with this program; see the file COPYING.txt.  If not, see
// <http://www.gnu.org/licenses/>
// -----------------------------------------------------------------------



// some default values
define("OPENID_DH_MODULUS", '155172898181473697471232257763715539915724801966915404479707795314057629378541917580651227423698188993727816152646631438561595825688188889951272158842675419950341258706556549803580104870537681476726513255747040765857479291291572334510643245094715007229621094194349783925984760375594985848253359305585439638443');
define("OPENID_DH_GEN", 2);
define("OPENID_EXPIRES_IN", 10000000);
define("DEBUG", 0);


class OpenidServer {

	// see section 8.3 of specification
	var $association_type = 'HMAC-SHA1';
	
	// see section 8.4 of specification
	var $association_session_type = 'DH-SHA1';
	
	// nr of bytes in the hmac-function
	var $blocksize = 64;

	// All references to specification numbers refer to version 2.0 of the 
	// OpenID authentication specification unless otherwise stated.
	
	
	// constructor
	function OpenidServer($db, $type=null) {
		$this->storage = $db;

		if (isset($type) && $type == "SHA256") {
			$this->association_type = 'HMAC-SHA256';
			$this->association_session_type = 'DH-SHA256';
		}
	}
	
	// Spec 4.2: Integer representations - Converts $n into a twos
	// compliment of a binary number (encoding it)
	function btwocEncode($long) {
		$cmp = bccomp($long, 0);

		if ($cmp == 0) {
			return "\x00";
		}

		$bytes = array();

		while (bccomp($long, 0) > 0) {
			array_unshift($bytes, bcmod($long, 256));
			$long = bcdiv($long, pow(2, 8));
		}

		if ($bytes && ($bytes[0] > 127)) {
			array_unshift($bytes, 0);
		}

		$string = '';
		foreach ($bytes as $byte) {
			$string .= pack('C', $byte);
		}

		return $string;
	}
	
	// Spec 4.2: Integer representations - Converts $n into a binary
	// from a twos compliment (decoding it)
	function btwocDecode($str) {
		$bytes = array_merge(unpack('C*', $str));
		$n = 0;

		foreach ($bytes as $byte) {
			$n = bcmul($n, pow(2, 8));
			$n = bcadd($n, $byte);
		}
		return $n;
	}
	
	// bitwise exclusive or function - either / or
	// takes 1100 and compares to 1001 to get 1010 
	function _xor($x, $y) {
		$a = '';
		for($i=0; $i < strlen($y); $i++) { 
			$a .= $x[$i] ^ $y[$i];
		}
		return $a;
	}
	
	// encryption-function... for more info read http://en.wikipedia.org/wiki/HMAC
	// is used when creating the signature where $key is assoc_handle/mac-key and $data is key-values (tokens)
	// (see 4.1.1 of specification)
	function hmac($key, $data) {
	
		switch($this->association_type) {
			case 'HMAC-SHA256':
				$hash_function = 'sha256';
			break;
			case 'HMAC-SHA1':
				$hash_function = 'sha1';
			break;
			default:
				$hash_function = 'sha1';
		}
		return hash_hmac($hash_function, $data, $key, true);
	}
	
	// calculates g^x mod p (x=secret number at server) and returns it encoded binary
	function dh_public() {
		$secret_key = '';
		for($i = 0; $i < rand(1, strlen($this->_openid_dh_modulus)-1); $i++) {
			if ($i == 0) {
				$secret_key .= rand(1, 9);
			}
			else {
				$secret_key .= rand(0, 9);
			}
		}
		$_SESSION['openid_secret_key'] = $secret_key;
		
		return base64_encode($this->btwocEncode(bcpowmod($this->_openid_dh_gen, $secret_key, $this->_openid_dh_modulus)));
	}
	
	function server_url() {
		
		$prefix = 'http://';
		if (isset($_SERVER['HTTPS'])) {
			if (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == 1) {
				$prefix = 'https://';
			}
		}

		return $prefix . $_SERVER['SERVER_NAME'] . '/login';
	}
	
	// a simple debug-function.
	// remove this later.
	function _debug($arr=null, $filename="debug_array.txt") {
		if (!empty($arr)) {
			$f = $filename;
		}
		elseif (isset($_POST['openid_mode'])) {
			$f = 'debug_' . $_POST['openid_mode'] . '.txt';
		}
		elseif (isset($_GET['openid_mode'])) {
			$f = 'debug_' . $_GET['openid_mode'] . '.txt';
		}
		
		if (empty($arr)) {
			file_put_contents($f, $this->association_type."\n".microtime() . "\n\nGET\n\n" . implode("\n", explode('&', http_build_query($_GET))) . "\n\n\n\nPOST\n\n" . implode("\n", explode('&', http_build_query($_POST))));
		}
		else {
			$str = "";
			foreach($arr as $key => $v) {
				$str .= $key . ':' . $v . "\n";
			}
			file_put_contents($f, $str);
		}
	}
	
	// normalizes an url
	function normalize($url) {
	
		if (substr($url, 0, strlen('https://')) != 'https://') {
			if (substr($url, 0, strlen('http://')) != 'http://') {
				$url = 'http://' . $url;
			}
			$this->openid_prefix = 'http://';
		}
		else {
			$this->openid_prefix = 'https://';
		}
		
		if (substr($url, -9) == 'index.php') {
			$url = substr($url, 0, -9);
		}

		if (substr($url, -1) == '#') {
			$url = substr($url, 0, strlen($url) - 1);
		}
		
		if (strpos(substr($url, strlen($this->openid_prefix), strlen($url)), '/')) {
			// do nothing
		}
		elseif (strpos(substr($url, strlen($this->openid_prefix), strlen($url)), ':')) {
			// do nothing
		}
		else {
			$url .= '/';
		}

		return $url;
	}
	
	
	// curl-function that senda data to an openid-server
	function _send($data, $method = 'POST', $url=null) {
		
		if (!isset($url)) {
			$url = $this->openid_url_server;
		}
		
		$s = '?';
		if (strpos($url, $s)) {
			$s = '&';
		}
		
		if ($method == 'GET') {
			$url .= $s . http_build_query($data);
		}
	
		$curl = curl_init($url);
		
		if (!ini_get("safe_mode")) {
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		}
		
		if ($method == 'POST') {
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
		}
		else {
			curl_setopt($curl, CURLOPT_HTTPGET, true);
		}
		
		curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0); // this solves the issues with the chunked encoding
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($curl);
		
		if (curl_errno($curl) == 0){
			return $response;
		}
		else {
			return 0;
		}
	}

	// see 8.1 of specification
	function associate() {

		$data_to_send = array();
		
		if (!empty($_POST['openid_ns']) && $_POST['openid_ns'] == 'http://specs.openid.net/auth/2.0') {
			$this->openid_version = 2;
			$data_to_send['ns'] = 'http://specs.openid.net/auth/2.0';
		}
		
		if (empty($_POST['openid_session_type'])) {
			$data_to_send['session_type'] = 'no-encryption';
		}
		else {
			$data_to_send['session_type'] = $_POST['openid_session_type'];
		}
		
		if (!empty($_POST['openid_assoc_type'])) {
			$this->association_type = $_POST['openid_assoc_type'];
			$data_to_send['assoc_type'] = $_POST['openid_assoc_type'];
		}
		else {
			$this->association_type = 'HMAC-SHA1';
			$data_to_send['assoc_type'] = 'HMAC-SHA1';
		}
		
		$data_to_send['assoc_handle'] = $this->assoc_handle();
		$data_to_send['expires_in'] = OPENID_EXPIRES_IN;
		
		if ($data_to_send['session_type'] != 'no-encryption') {
		
			if (!empty($_POST['openid_dh_modulus'])) {
				// we decode openid_dh_modulus to 'a real' integer
				$this->_openid_dh_modulus = $this->btwocDecode(base64_decode($_POST['openid_dh_modulus']));
			}
			else {
				$this->_openid_dh_modulus = OPENID_DH_MODULUS;
			}
			
			if (!empty($_POST['openid_dh_gen'])) {
				$this->_openid_dh_gen = $this->btwocDecode(base64_decode($_POST['openid_dh_gen']));
			}
			else {
				$this->_openid_dh_gen = OPENID_DH_GEN;
			}
		
			$data_to_send['dh_server_public'] = $this->dh_public();
			$data_to_send['enc_mac_key'] = $this->enc_mac_key($_POST['openid_dh_consumer_public']);
		}
		else {
			$data_to_send['mac_key'] = $this->mac_key();
		}

		$this->storage->writeSession($data_to_send);
		$this->direct_response($data_to_send);
		return 1;
	}
	
	function sreg_extention ($datax=array()) {
		$sregflt = false; 
		$fieldswanted = Array (); 
	//	$this->_debug(); 
		$values = GetFromURL("openid_sreg_required"); 
		if ($values) {
			$reqfields = explode (",", $values); 
			foreach ($reqfields as $flt) {
				$fieldswanted[$flt]="REQUIRED"; 
			}
		}
		$values = GetFromURL("openid_sreg_optional"); 
		if ($values) {
			$optfields = explode (",", $values); 
			foreach ($optfields as $flt) {
				$fieldswanted[$flt]="OPTIONAL"; 
			}
		}
//		$this->_debug($fieldswanted);
		if (!empty($fieldswanted)) { 
			reset ($fieldswanted); 
			foreach ($fieldswanted as $flt => $recopt) {
				switch (trim($flt)) {
					case  ("nickname") : 
						$value=$_SESSION['user_nick'];
						break; 
					case  ("email") : 
						$value=$_SESSION['user_email'];
						break; 
					case  ("fullname") : 
						$value=$_SESSION['user_name'];
						break; 
					case  ("dob") : 
						$value=$_SESSION['user_birthdate'];
						break; 
					case  ("gender") : 
						$value=$_SESSION['user_gender'];
						break; 
					case  ("postcode") : 
						$value=$_SESSION['user_postcode'];
						break; 
					case  ("country") : 
						$value=$_SESSION['user_country'];
						break; 
					case  ("language") : 
						$value=$_SESSION['user_language'];
						break; 
					case  ("timezone") : 
						$value=$_SESSION['user_timezone'];
						break; 
				}
				if ($value) {
					$opcode="openid.sreg.".$flt; 
					$datax[$opcode] = $value; 
					$sregflt = true; 
				}
			}	
		}
		
	} 
	
	
	
	// see section 10 of specification
	function checkid_setup($type = null) {
		if (!empty($_SESSION['user_id']) && isset($_POST['trust'])) {
			
			$openid_identity = GetFromURL("openid_identity"); 
			$openid_return_to = GetFromURL("openid_return_to"); 
			
			if ($openid_identity == 'http://specs.openid.net/auth/2.0/identifier_select'){
				$openid_identity='http'.(isset($_SERVER['HTTPS'])&& (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == 1) ? 's' : '').'://'.$_SERVER['SERVER_NAME'].'/'; 
			}
			
			$openIDns=GetFromURL("openid_ns"); 
			if ($openIDns == 'http://specs.openid.net/auth/2.0') {
				$data_to_send['openid.ns'] = 'http://specs.openid.net/auth/2.0';
				$this->openid_version = 2;
			} else $this->openid_version = 1;
			
			$data_to_send['openid.identity'] = $openid_identity;	
						
			$data_to_send['openid.mode'] = 'id_res';
			
			if ($this->openid_version == 2) {
				$data_to_send['openid.op_endpoint'] = $this->server_url();
				$data_to_send['openid.claimed_id'] = $openid_identity;
				$data_to_send['openid.response_nonce'] = gmdate('Y-m-d') . 'T' . gmdate('H:i:s') . 'ZUNIQUE';
			}
			
			$data_to_send['openid.return_to'] = $openid_return_to;
			$assocHandle = GetFromURL("openid_assoc_handle"); 
			if ($assocHandle) {
				$data_to_send['openid.assoc_handle'] = $assocHandle; 
			}
			else {
				// we had to do this for bloggr. is ok?
				$data_to_send['openid.assoc_handle'] = $this->assoc_handle();
			}
			
			$this->sreg_extention (&$data_to_send);
			
			
			$signed = '';
			foreach($data_to_send as $key => $v) {
				$signed .= substr($key, 7) . ',';
			}
			
			$data_to_send['openid.signed'] = $signed . 'signed';
	
			$tokens = '';
			foreach($data_to_send as $key => $value) {
				$tokens .= substr($key, 7) . ':' . $value . "\n";
			}
			
			$query = "
				SELECT *
				FROM " . $this->storage->prefix . "_session
				WHERE assoc_handle=" . $this->storage->qstr($data_to_send['openid.assoc_handle']).";"
			;
				
			$openid_session = $this->storage->Execute($query);
			if (isset($openid_session[0]['assoc_handle'])) {
				$this->association_type = $openid_session[0]['assoc_type'];
			}
			$data_to_send['openid.sig'] = base64_encode($this->hmac($data_to_send['openid.assoc_handle'], $tokens));
		
			$s = '?';
			if (strpos($openid_return_to, $s)) {
				$s = '&';
			}
			
			 // send us back to the consumer
			header('location: ' . $openid_return_to . $s . http_build_query($data_to_send));
			exit;
		}
	}
	
	// this method is not yet implemented.
	function check_authentication() {
		unset($_SESSION);
		session_destroy();
		header('Content-Type: text/plain; charset=UTF-8');
		echo 'is_valid:true'; exit; // this is a hack...
	}
	
	// see section 9.3 of specification
	function checkid_immediate() {
	//	$this->_debug(); 
		$openid_identity = GetFromURL("openid_identity"); 
		$openid_return_to = GetFromURL("openid_return_to"); 
					
		if ($openid_identity == 'http://specs.openid.net/auth/2.0/identifier_select'){
			$openid_identity='http'.(isset($_SERVER['HTTPS'])&& (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == 1) ? 's' : '').'://'.$_SERVER['SERVER_NAME'].'/'; 
		}	
		
		if (!empty($_SESSION['user_id'])) {
								
			$openIDns=GetFromURL("openid_ns"); 
			if ($openIDns == 'http://specs.openid.net/auth/2.0') {
				$data_to_send['openid.ns'] = 'http://specs.openid.net/auth/2.0';
				$this->openid_version = 2;
			} else $this->openid_version = 1;
			
			$data_to_send['openid.identity'] = $openid_identity;	
						
			$data_to_send['openid.mode'] = 'id_res';
			
			if ($this->openid_version == 2) {
				$data_to_send['openid.op_endpoint'] = $this->server_url();
				$data_to_send['openid.claimed_id'] = $openid_identity;
				$data_to_send['openid.response_nonce'] = gmdate('Y-m-d') . 'T' . gmdate('H:i:s') . 'ZUNIQUE';
			}
			
			$data_to_send['openid.return_to'] = $openid_return_to;
			$assocHandle = GetFromURL("openid_assoc_handle"); 
			if ($assocHandle) {
				$data_to_send['openid.assoc_handle'] = $assocHandle; 
			}
			else {
				// we had to do this for bloggr. is ok?
				$data_to_send['openid.assoc_handle'] = $this->assoc_handle();
			}
			
			$this->sreg_extention (&$data_to_send);
			
			$signed = '';
			foreach($data_to_send as $key => $v) {
				$signed .= substr($key, 7) . ',';
			}
			
			$data_to_send['openid.signed'] = $signed . 'signed';
	
			$tokens = '';
			reset ($data_to_send);
			foreach($data_to_send as $key => $value) {
				$tokens .= substr($key, 7) . ':' . $value . "\n";
			}
			
			$query = "
				SELECT *
				FROM " . $this->storage->prefix . "_session
				WHERE assoc_handle=" . $this->storage->qstr($data_to_send['openid.assoc_handle'])
			;
					
			$openid_session = $this->storage->Execute($query);
			
			if (isset($openid_session['assoc_handle'])) {
				$this->association_type = $openid_session['assoc_type'];
			}
			
			$data_to_send['openid.sig'] = base64_encode($this->hmac($data_to_send['openid.assoc_handle'], $tokens));
		
			$s = '?';
			if (strpos($openid_return_to, $s)) {
				$s = '&';
			}
//	$this->_debug($data_to_send);
			
			// send us back to the consumer
			header('location: ' . $openid_return_to . $s . http_build_query($data_to_send));
			exit;
		} else {
			$s = '?';
			if (strpos($openid_return_to, $s)) {
				$s = '&';
			}
			$data_to_send['openid.ns'] = 'http://specs.openid.net/auth/2.0';
			$data_to_send['openid.mode'] = 'setup_needed';
			$data_to_send['openid.user_setup_url'] = $this->server_url();
			$this->debug($data_to_send); 
			header('location: ' . $openid_return_to . $s . http_build_query($data_to_send));
			exit;
			
			
		}
		
	}
	
	// see section 5.1.2 of specification
	function direct_response($data) {
		header('Content-Type: text/plain; charset=UTF-8');
		foreach($data as $key => $value) {
 			echo $key . ':' . $value . "\n";
		}
		if (DEBUG) {
			$this->_debug($data);
		}
 		exit;
	}
	
	// creates a random key
	// see section 8.2.1 of specification
	// this whole thing should be rewritten.
	// instead of using this as the 'secret', we should init a new session poitning to this.
	// the session then owns a 'real secret' of 20 chars.
	// that is, assoc_handle (is a session) -> (decides) secret
	// in that way we can use many connections at the same time.
	function assoc_handle() {
		
		unset($_SESSION['openid_assoc_handle']);
		
		switch($this->association_type) {
			case 'HMAC-SHA256':
				$limit = 32;
			break;
			case 'HMAC-SHA1':
				$limit = 20;
			break;
			default:
				$limit = 20;
		}

		$_SESSION['openid_assoc_handle'] = "";
		for($i = 0; $i < $limit; $i++){
			$_SESSION['openid_assoc_handle'] .= chr(rand(97, 122));
		}

		return $_SESSION['openid_assoc_handle'];
	}
	
	// generates the enc_mac_key.
	// decodes $n to 'real integer' and then calculates g^xy mod p (= (g^y mod p)^x mod p), where
	// x= secret at server and y=secret at consumer.
	// Uses that number and the assoc_handle to calculate the enc_mac_key.
	// method was moced from OpenidCommon to OpenidServer 070815
	function enc_mac_key($n) {
	
		switch($this->association_type) {
			case 'HMAC-SHA256':
				$hash_function = 'sha256';
			break;
			case 'HMAC-SHA1':
				$hash_function = 'sha1';
			break;
			default:
				$hash_function = 'sha1';
		}
		$y = $this->btwocDecode(base64_decode($n));
		$x = bcpowmod($y, $_SESSION['openid_secret_key'], $this->_openid_dh_modulus);

		$enc_mac_key = base64_encode($this->_xor(hash($hash_function, $this->btwocEncode($x), true), $_SESSION['openid_assoc_handle']));
		return $enc_mac_key;
	}
	
	function mac_key() {
		$mac_key = base64_encode($_SESSION['openid_assoc_handle']);
		return $mac_key;
	}
}

?>