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
// -----------------------------------------------------------------------


// CHECK INSTALLED
if (is_readable("install/installer.php")) {
	header("Location: install/installer.php");
	exit;
}


include_once ("config/core.config.php");
include_once ("inc/functions.inc.php");


// SESSION HANDLER --------------------------------------------------
session_name($core_config['php']['session_name']);
session_start();


// SETUP URL ROUTING ------------------------------------------------
$uri_routing = routeURL();

if (isset($uri_routing[0]) && $uri_routing[0] == "disconnect") {
	session_unset();
	session_destroy();
	session_write_close();
	header("Location: /");
	exit;
}


// SET LOCALE ----------------------------------------------------------
define ('LOCALE', $core_config['language']['server_locale']);

if (isset($core_config['language']['standard_locale'])) {
	define ('STND_LOCALE', $core_config['language']['standard_locale']);
}
else {
	define ('STND_LOCALE', $core_config['language']['server_locale']);
}

putenv("LANGUAGE=".LOCALE);
setlocale(LC_ALL, LOCALE);

$domain = 'prairie';
bindtextdomain($domain, dirname(__FILE__) . "/language"); 
textdomain($domain);

// Pending - see if we can remove the LC_MESSAGE dir and store all .mp .po files in language dir
//putenv("TEXTDOMAINDIR=./languages");
//echo getenv('TEXTDOMAINDIR'); // we maybe able to alter this to get the required path


// SETUP DATABASE ----------------------------------------------
require_once('class/Db.class.php');
$db = new Database($core_config['db']);


// SETUP TEMPLATES --------------------------------------------------
define("SCRIPT_TEMPLATE_PATH", "template/");

require_once('class/Template.class.php');
$tpl = new Template(); // outer template
$body = new Template(); // inner template


// SETUP WEBSPACE ---------------------------------------------------
if (!empty($core_config['script']['single_webspace'])) { // single comain name
	$user_webspace = $core_config['script']['single_webspace'];
}
elseif (!empty($core_config['script']['multiple_webspace_pattern'])) { // using sub-domains
	
	preg_match ($core_config['script']['multiple_webspace_pattern'], $_SERVER['HTTP_HOST'], $matches);
	
	if (!empty($matches[1])) {
		$user_webspace = $matches[1];
	}
}
if (isset($user_webspace)) {

	// SELECT WEBSPACE -------------------------------------------
	$query = "
		SELECT ws.webspace_css, ws.webspace_html, u.openid_name, 
		u.user_name, u.user_email, u.user_location, u.user_id, 
		ws.webspace_title, ws.webspace_theme, u.user_live 
		FROM " . $db->prefix . "_user u 
		LEFT JOIN " . $db->prefix . "_webspace ws ON u.user_id=ws.user_id 
		WHERE 
		u.openid_name=" . $db->qstr($user_webspace)
	;
	
	$result = $db->Execute($query, 1);
	if (!empty($result[0]) && $result[0]['user_live'] == 1) {

		$webspace = $result[0];

		define("WEBSPACE_OPENID", $webspace['openid_name']);
		define("WEBSPACE_USERID", $webspace['user_id']);
		define("WEBSPACE_USERNAME", $webspace['user_name']); 
		
		if (!empty($webspace['webspace_title'])) {
			$tpl->set('webspace_title', $webspace['webspace_title']);
		}
		
		$body->set('webspace', $webspace);

		$maintainer_openids = array();
		
		if (!empty($core_config['security']['maintainer_openids'])) {
			$maintainer_openids = explode(",", $core_config['security']['maintainer_openids']);
	
			foreach ($maintainer_openids as $key => $i):
				$maintainer_openids[$key] = trim($i);
			endforeach;
		}

		if (in_array(WEBSPACE_OPENID, $maintainer_openids)) {
			define("USER_IS_MAINTAINER", 1);
		}
		
		// check if called from consumer : Do not log in if allready logged on before. 
		if (isset($uri_routing[0]) && ($uri_routing[0]=="login")) {
			$openid_mode = GetFromURL("openid_mode"); 
			
			if ($openid_mode) 
			{
				if (!empty($_SESSION['user_id'])) {
					$uri_routing[0]="trust"; 
				} else {
					
					if ($openid_mode=="checkid_immediate") {
						$openid_return_to = GetFromURL("openid_return_to");
						if (strpos($openid_return_to, '?')) $s = '&'; else $s = '?';
					
						$data_to_send = Array ();
						$data_to_send['openid.ns'] = 'http://specs.openid.net/auth/2.0';
						$data_to_send['openid.mode'] = 'setup_needed';
						$data_to_send['openid.user_setup_url'] = 'http'.(isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == 1) ? 's' : '').'://'.$_SERVER['SERVER_NAME'] . '/login';
					
						$redirurl = $openid_return_to . $s . http_build_query($data_to_send);
						if (strpos($redirurl, '\n') !== FALSE || (strpos($redirurl, 'http://')!==0 && strpos($redirurl, 'https://')!==0)) {
							header("Status: 500");
							echo "Invalid return URL found.";
							exit;
						}
						header('location: ' . $redirurl);
						exit;
					}
						
				}			
					
			}
		} 	
	}
	elseif (!empty($result[0]) && $result[0]['user_live'] != 1 && isset($uri_routing[1])) {
		// We are answering the registration confirmation email
		$uri_routing[0] = "register";
	}
	elseif ((!isset($uri_routing[0])) || ($uri_routing[0] != "register")) {	 
		$uri_routing[0] = "public";
	} 
}

// SETUP THEME ---------------------------------------------------------
if (!empty($webspace['webspace_theme'])) {
	define("SCRIPT_THEME_NAME", $webspace['webspace_theme']);
}
else {
	define("SCRIPT_THEME_NAME", $core_config['script']['default_theme_name']);
}

define("SCRIPT_THEME_PATH", "theme/" . SCRIPT_THEME_NAME . "/");

require_once (SCRIPT_THEME_PATH . "theme_functions.php"); 

// OBTAIN SCRIPT NAME --------------------------------------------------
if (isset($uri_routing[0]) && is_readable(SCRIPT_TEMPLATE_PATH . $uri_routing[0] . '.tpl.php')) {
	define("SCRIPT_NAME", $uri_routing[0]);
}
else {
	define("SCRIPT_NAME", 'profile');
}
// echo SCRIPT_NAME;// debug
if (defined('SCRIPT_NAME') && is_readable(SCRIPT_NAME . '.php')) {
	require_once(SCRIPT_NAME . '.php');
	$inner_template_body = file_get_contents(SCRIPT_TEMPLATE_PATH . SCRIPT_NAME . '.tpl.php');
}
else {
	header('location: /disconnect');
	exit;
}


// SET TEMPLATE VARS -----------------------------------------------------
$body->set('uri_routing', $uri_routing);
$tpl->set('uri_routing', $uri_routing);

$tpl->set('content', $body->parse($inner_template_body));

$outer_tpl = SCRIPT_TEMPLATE_PATH . 'wrapper.tpl.php';

echo $tpl->fetch($outer_tpl);

?>