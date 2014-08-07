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


if (!isset($_SESSION['user_id']) || !defined('USER_IS_MAINTAINER')) {
	header("Location: /");
	exit;
}


if (isset($_POST['update_profile'])) {
	$dob_year = (int) $_POST['dob_year'];
	$dob_month = (int) $_POST['dob_month'];
	$dob_day = (int) $_POST['dob_day'];

	$dob = formatDate($dob_year, $dob_month, $dob_day);

	$query = "UPDATE " . $db->prefix . "_user 
		SET 
		user_email=" . $db->qstr($_POST['user_email']) . ",
		user_dob=" . $db->qstr($dob) . " 
		WHERE
		user_id=" . (int)$_POST['user_id']
	;

	$db->Execute($query);
}
elseif (isset($_POST['send_new_password'])) {

	$query = "
		SELECT user_id
		FROM " . $db->prefix . "_user
		WHERE
		user_id=" . (int)$_POST['user_id']
	;

	$result = $db->Execute($query, 1);
		
	if (!empty($result[0]['user_id'])) {
		// we reset the password
		$new_password = substr(md5(time()), 0, 5);
		
		// we send a message
		$query = "
			UPDATE " . $db->prefix . "_user
			SET user_password=" . $db->qstr(md5($new_password)) . "
			WHERE user_id=" . (int)$result[0]['user_id']
		;
		
		$db->Execute($query);
		
		require_once('class/Mail/class.phpmailer.php');

		// email, subject, message
		$email_subject = _("Here is your new OpenID password");
	
		$mail->Subject = $email_subject;
		
		$email_message = _("Your new password is {1}.");
		$email_message = str_replace("{1}", $new_password, $email_message);
	
		// HTML-version of the mail
		$html  = "<HTML><HEAD><TITLE></TITLE></HEAD>";
		$html .= "<BODY>";
		$html .= utf8_decode(nl2br($email_message));
		$html .= "</BODY></HTML>";

		$mail->Body = $html;
		// non - HTML-version of the email
		$mail->AltBody   = utf8_decode(strip_tags($email_message));
		$mail->AddAddress($_POST['user_email']);
	
		if($mail->Send()) {
			// sent
			$body->set('new_password', 1);
		}
		// success message
		$GLOBALS['script_message_log'][] = _("New password was sent.");
	}
}
elseif (isset($_POST['send_new_verification'])) {

	$key = substr(md5 (time()), 0, 5);
	$url_key = 'register/' . $key;

	$query = "
		UPDATE " . $db->prefix . "_user
		SET user_registration_key=" . $db->qstr($key) . "
		WHERE user_id=" . (int)$_POST['user_id']
	;

	$db->Execute($query);

	// Send welcome email with link.
	// setup mail
	require_once('class/Mail/class.phpmailer.php');
	$mail->From = $core_config['mail']['email_address'];
	
	// email, subject, message
	$email_subject = _("Welcome to your new OpenID service!");
	
	$mail->Subject = utf8_decode($email_subject);

	$url = 'http://';
		
	if (isset($_SERVER['HTTPS'])) {
		if (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == 1) {
			$url = 'https://';
		}
	}

	$url .= str_replace("/(.*?)\\", $_POST['openid_name'], $core_config['script']['multiple_webspace_pattern']);
	$url .=  $url_key;
		
	// HTML-version of the mail
	$email_message_html = "<p>To activate your account please <a href=\"" . $url . "\">click here</a>.</p>";
	$html_content = $email_message_html;
	
	$mail->Body = utf8_decode($html_content);
	
	// text version of email
	$email_message_txt = "To activate your account please press the following link: \n\n" . $url;
	$email_message_txt = utf8_decode($email_message_txt);
	
	$mail->AltBody = $email_message_txt;
	$mail->AddAddress($_POST['user_email']);
	
	if($mail->Send()) {
		// sent
		$body->set('email_sent', 1);
	}
}
elseif (isset($_POST['save_config_openid_names'])) {

	backupConfig();

	$core_config['registration']['unauthorized_openid_names'] = $_POST['unauthorized_openid_names'];

	writeToConfig('$core_config[\'registration\'][\'unauthorized_openid_names\']', $core_config['registration']['unauthorized_openid_names']);
}
elseif (isset($_POST['save_config_allow_registration'])) {

	backupConfig();
	
	if (!empty($_POST['allow_registration'])) {
		$core_config['registration']['allow_registration'] = 1;
	}
	else {
		$core_config['registration']['allow_registration'] = 0;
	}
	
//$core_config['registration']['allow_registration']
	writeToConfig('$core_config[\'registration\'][\'allow_registration\']', $core_config['registration']['allow_registration']);
}
elseif (isset($_POST['save_config_maintainer_openids'])) {

	backupConfig();
	
	$core_config['security']['maintainer_openids'] = $_POST['maintainer_openids'];

	writeToConfig('$core_config[\'security\'][\'maintainer_openids\']', $core_config['security']['maintainer_openids']);
}
elseif (isset($_POST['save_config_email_domains'])) {

	backupConfig();
	
	$core_config['registration']['email_domains'] = $_POST['authorized_email_domains'];

	writeToConfig('$core_config[\'registration\'][\'email_domains\']', $core_config['registration']['email_domains']);
}
elseif (isset($_POST['save_config_html_tags'])) {

	backupConfig();
	
	$core_config['security']['allowable_html_tags'] = $_POST['allowable_html_tags'];

	writeToConfig('$core_config[\'security\'][\'allowable_html_tags\']', $core_config['security']['allowable_html_tags']);
}
elseif (isset($_POST['save_config_language'])) {

	backupConfig();
	
	$core_config['language']['server_locale'] = $_POST['server_locale'];
	$core_config['language']['standard_locale'] = $_POST['standard_locale'];

	writeToConfig('$core_config[\'language\'][\'server_locale\']', $core_config['language']['server_locale']);
	writeToConfig('$core_config[\'language\'][\'standard_locale\']', $core_config['language']['standard_locale']);
}


if (isset($uri_routing[1]) && $uri_routing[1] == "account" && isset($uri_routing[2])) {
	
	$query = "
		SELECT user_id, openid_name, user_name, user_email, user_dob, user_live 
		FROM " . $db->prefix . "_user 
		WHERE 
		user_id=".(int)$uri_routing[2]
	;
	
	$result = $db->Execute($query, 1);
	
	if (!empty($result[0])) {
		$body->set('account', $result[0]);
	}
}
elseif (isset($uri_routing[1]) && $uri_routing[1] == "accounts") {
	
	$query = "
		SELECT user_id, openid_name, user_name, user_email, user_dob, user_live 
		FROM " . $db->prefix . "_user 
		ORDER BY openid_name"
	;
	
	$result = $db->Execute($query);
	
	if (!empty($result)) {
		$body->set('accounts', $result);
	}
}
else {

	$query = "
		SELECT count(user_id) as total_accounts 
		FROM " . $db->prefix . "_user 
		WHERE user_live=1"
	;
	
	$result = $db->Execute($query);
	
	if (!empty($result[0])) {
		$body->set('accounts', $result[0]['total_accounts']);
	}
	else {
		$body->set('accounts', 0);
	}

	
	$query = "
		SELECT count(user_id) as total_accounts_pending
		FROM " . $db->prefix . "_user 
		WHERE user_live!=1"
	;
	
	$result = $db->Execute($query);
	
	if (!empty($result[0])) {
		$body->set('accounts_pending', $result[0]['total_accounts_pending']);
	}
	else {
		$body->set('accounts_pending', 0);
	}
}



$body->set('arr_mail', $core_config['mail']);
$body->set('arr_db', $core_config['db']);
$body->set('arr_reg', $core_config['registration']);
$body->set('arr_lang', $core_config['language']);
$body->set('arr_security', $core_config['security']);

if (!empty($core_config['script']['single_webspace'])) {
	$body->set('single_account_instance', 1);
}


function writeToConfig($where, $what) {
	$config = file('config/core.config.php');
	foreach($config as $key => $val) {
		if (strstr($val, $where)) {
			$config[$key] = $where . ' = "' . $what . "\";\n";
			@file_put_contents('config/core.config.php', implode($config));
			break;
		}
	}
}

function backupConfig() {

	$name = "~core.config_" . time() . ".php";

	$config = file_get_contents('config/core.config.php');

	file_put_contents('config/' . $name , $config);
}

?>