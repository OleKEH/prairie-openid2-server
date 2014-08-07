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


if (isset($uri_routing[1])) { // We are confirming a key
	// This if statement stays at the top because we might still want to reset an account even with registration set to off (from maintain screen)
	$query = "
		SELECT user_id 
		FROM " . $db->prefix . "_user 
		WHERE
		user_registration_key=" . $db->qstr($uri_routing[1]) . "
		AND user_live!=1"
	;
	
	$result = $db->Execute($query);
	
	if (!empty($result)) {
		
		$query = "
			UPDATE
			" . $db->prefix . "_user 
			SET
			user_registration_key=NULL,
			user_live=1 
			WHERE 
			user_id=" . (int)$result[0]['user_id']
		;
		
		$db->Execute($query);
		
		header('location: /');
		exit;
	}
	else {
		// wrong key. What happens?
		header('location: /');
		exit;
	}
}


if (!isset($core_config['script']['multiple_webspace_pattern'])) {
	// In single user mode registration is handled within the installation process.
	header('location: /');
	exit;
}

if (!isset($core_config['registration']['allow_registration']) || $core_config['registration']['allow_registration'] != 1) {
	// Registration is not allowed
	header('location: /');
	exit;
}


if (isset($_POST['register'])) {
	
	$_POST['user_name'] = trim($_POST['user_name']);
	$_POST['user_location'] = trim($_POST['user_location']);
	$_POST['user_email'] = trim($_POST['user_email']);

	if (empty($_POST['user_name'])) {
		$GLOBALS['script_error_log'][] = _("You must provide a name.");
	}

	if (!checkEmail($_POST['user_email'])) {
		$GLOBALS['script_error_log'][] = _("Your email address does not like a valid email address.");
	}

	if (!empty($core_config['script']['email_domains'])) {
		$email_domain =  substr(strrchr($_POST['user_email'], "@"), 1 );

		if (!in_array($email_domain, $core_config['script']['email_domains'])) {
			$error_domains = implode(", ", $core_config['script']['email_domains']);
			$error_message = _("You must provide us with a valid email address. This has to be within the domains {1}.");
			$error_message = str_replace("{1}", $error_domains, $error_message);
			$GLOBALS['script_error_log'][] = $error_message;
		}
	}

	// is the email unique
	$query = "
		SELECT user_id 
		FROM " . $db->prefix . "_user 
		WHERE
		user_email=" . $db->qstr($_POST['user_email']) . " AND
		user_live=1"
	;
	
	$result = $db->Execute($query);

	if (isset($result[0]['user_id'])) {
		$GLOBALS['script_error_log'][] = _("Your email is already in use. You can request a new password from the login page.");
	}
	
	$openid_name = strtolower(trim($_POST['openid_name']));
	
	if (empty($openid_name)) {
		$GLOBALS['script_error_log'][] = _("You must provide an openid name.");
	}
	else {
		$openid_name = formatIdentityName($openid_name);
		
		if (empty($GLOBALS['script_error_log'])) {
			$query = "
				SELECT user_id
				FROM " . $db->prefix . "_user
				WHERE openid_name=" . $db->qstr($openid_name)
			;
			
			$result = $db->Execute($query);
			
			if (!empty($result)) {
				$GLOBALS['script_error_log'][] = _("This OpenID account name is already in use. Please choose another one.");
			}
		}
	}

	if (empty($_POST['user_location'])) {
		$GLOBALS['script_error_log'][] = _("You must provide a location.");
	}

	if ($_POST['user_password1'] != $_POST['user_password2']) {
		$GLOBALS['script_error_log'][] = _("Your new passwords did not match.");
	}
	
	if (strlen($_POST['user_password1']) < 2) {
		$GLOBALS['script_error_log'][] = _("Your password must be longer than 2 characters.");
	}

	$dob_year = (int) $_POST['dob_year'];
	$dob_month = (int) $_POST['dob_month'];
	$dob_day = (int) $_POST['dob_day'];
	
	$dob = formatDate($dob_year, $dob_month, $dob_day);
	
	if (empty($_POST['tos'])) {
		$GLOBALS['script_error_log'][] = _("You must agree to our terms of service.");
	}
	
	if (!match_maptcha($_POST['maptcha_text'])) {
		$GLOBALS['script_error_log'][] = _("You failed the math test dismally. Please try again.");
	}
	
	if (empty($GLOBALS['script_error_log'])) {
		// insert into db here

		$key = substr(md5 (time()), 0, 5);
		$url_key = 'register/' . $key;
		
		$rec = array();
		$rec['user_name'] = $_POST['user_name'];
		$rec['user_location'] = $_POST['user_location'];
		$rec['user_create_datetime'] = time();
		$rec['user_password'] = md5($_POST['user_password1']);
		$rec['user_email'] = $_POST['user_email'];
		$rec['user_dob'] = $dob;
		$rec['user_registration_key'] = $key;
		$rec['openid_name'] = $openid_name;
		
		$table = $db->prefix . '_user';
		
		$db->insertDB($rec, $table);

		$user_id = $db->insertID();

		if (!empty($user_id)) {
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

			$url .= str_replace("/(.*?)\\", $openid_name, $core_config['script']['multiple_webspace_pattern']);
			$url .=  $url_key;
				
			// HTML-version of the mail
			$email_message_html = "<p>To activate your account please <a href=\"" . $url . "\">click here</a>.</p>";
			$html_content = $email_message_html;
			
			$mail->Body = utf8_decode($html_content);
			
			// text version of email
			$email_message_txt = "To activate your account please press the following link: \n\n" . $url;
			$email_message_txt = utf8_decode($email_message_txt);
			
			$mail->AltBody = $email_message_txt;
			$mail->AddAddress($rec['user_email']);
			
			if($mail->Send()) {
				// sent
				$body->set('email_sent', 1);
			}
			else {
				$GLOBALS['script_error_log'][] = _("We could not send you a verification email. Please check that your email address is valid.");
			}
		}
	}
}


$maptcha = gen_maptcha();
$body->set('maptcha', $maptcha);

$body->set('domain_name', $core_config['script']['core_domain']);

if (!empty($core_config['script']['email_domains'])) {
	$body->set('email_domains', $core_config['script']['email_domains']);
}

?>