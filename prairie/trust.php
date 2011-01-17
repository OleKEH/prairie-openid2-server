<?php

// -----------------------------------------------------------------------
// This file is part of Prairie
// 
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
// openid.realm
// -----------------------------------------------------------------------


require_once('class/Openid.class.php');

$server = new OpenidServer($db, $core_config['security']['openid_encryption_level']);

if (isset($_POST['trust'])) {
	
	if (isset($_GET['openid_trust_root'])) {
		$trust_url = $server->normalize($_GET['openid_trust_root']);
	}
	elseif (isset($_GET['openid_realm'])) {
		$trust_url = $server->normalize($_GET['openid_realm']);
	}
	else {
		$trust_url = $server->normalize($_GET['openid_return_to']);
	}

	$query = "
		SELECT trust_id
		FROM " . $db->prefix . "_trust
		WHERE trust_url=" . $db->qstr($trust_url)
	;
	
	$result = $db->Execute($query);
	
	if (empty($result)) {
		$rec = array();

		$rec['user_id'] = $_SESSION['user_id'];
		$rec['trust_url'] = $trust_url;
		$rec['trust_total'] = 1;
		$rec['trust_last_visit'] = time();
		
		$table = $db->prefix . '_trust';
		
		$db->insertDB($rec, $table);

	}
	else {
		$query = "
			UPDATE " . $db->prefix . "_trust
			SET trust_total=trust_total+1, 
			trust_last_visit=NOW()
			WHERE trust_id=" . $result[0]['trust_id']
		;
		
		$db->Execute($query);
	}
}
elseif (isset($_POST['cancel'])) {
	header("Location: " . $_GET['openid_return_to']);
	exit;
}
else {
	if (isset($_GET['openid_trust_root'])) {
		$trust_url = $server->normalize($_GET['openid_trust_root']);
	}
	elseif (isset($_GET['openid_realm'])) {
		$trust_url = $server->normalize($_GET['openid_realm']);
	}
	else {
		$trust_url = $server->normalize($_GET['openid_return_to']);
	}

	$query = "
		SELECT *
		FROM " . $db->prefix . "_trust
		WHERE trust_url=" . $db->qstr($trust_url)
	;
	
	$result = $db->Execute($query);
	
	if (empty($result)) {
		$body->set('trust_url', $trust_url);
	}
	else {
		$body->set('trust', $result[0]);
	}
}

if (isset($_POST['openid_mode'])) {
	$openid_mode = $_POST['openid_mode'];
}
elseif (isset($_GET['openid_mode']) && !isset($_POST['login'])) {
	$openid_mode = $_GET['openid_mode'];
}


if (isset($openid_mode)) {

	switch($openid_mode) {
		case 'associate':
			$server->associate();
		break;
		case 'checkid_setup':
			$server->checkid_setup();
		break;
		case 'check_authentication':
			$server->check_authentication();
		break;
		case 'checkid_immediate':
			$server->checkid_immediate();
		break;
		default:
	}
}

?>