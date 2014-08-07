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


if (isset($_POST['login_admin'])) {
	$login_password = trim($_POST['login_password']);
	
	if (empty($login_password)) {
		$GLOBALS['script_error_log'][] = _("Please fill in your password.");;
	}

	if (!empty($core_config['security']['login_with_email'])) {
		$login_email = trim($_POST['login_email']);
	
		if (empty($login_email)) {
			$GLOBALS['script_error_log'][] = _("Please fill in your email address.");;
		}
	}
	
	
	if (empty($GLOBALS['script_error_log'])) {
		$query = "
			SELECT *
			FROM " . $db->prefix . "_user
			WHERE 
			user_password=" . $db->qstr(md5($login_password)) . " AND 
			openid_name=" . $db->qstr(WEBSPACE_OPENID)
		;

		if (!empty($core_config['security']['login_with_email'])) {
			$query .= " AND user_email=" . $db->qstr($login_email);
		}

		$result = $db->Execute($query);
		
		if (!empty($result)) {
			$_SESSION['user_id'] = $result[0]['user_id'];
			$_SESSION['user_email'] = $result[0]['user_email'];
			$_SESSION['user_name'] = $result[0]['user_name'];
			$_SESSION['user_location'] = $result[0]['user_location'];
			$_SESSION['user_dob'] = $result[0]['user_dob'];
			
			$_SESSION['user_nick'] = $result[0]['user_nick'];
			$_SESSION['user_gender'] = $result[0]['user_gender'];
			$_SESSION['user_postcode'] = $result[0]['user_postcode'];
			$_SESSION['user_country'] = $result[0]['user_country'];
			$_SESSION['user_language'] = $result[0]['user_language'];
			$_SESSION['user_timezone'] = $result[0]['user_timezone'];
			$_SESSION['user_birthdate'] = $result[0]['user_birthdate'];
			$openIDMode = GetFromURL("openid_mode"); 
			
			if ($openIDMode) {
				if ($_SERVER["REQUEST_METHOD"]=="GET") {
					header('location: /trust?' . http_build_query($_GET));
				} else {
					unset ($_POST["login_email"]); 
					unset ($_POST["login_password"]); 
					header('location: /trust?' . http_build_query($_POST));
				}
				exit;
			}
			else {
				header('location: /profile');
				exit;
			}
		}
		else {
			if (!empty($core_config['security']['login_with_email'])) {
				$GLOBALS['script_error_log'][] = _("Login failed. Please check your email address and password.");
			}
			else {
				$GLOBALS['script_error_log'][] = _("Login failed. Please check your password.");
			}
		}
	}
}
elseif (isset($_POST['submit_new_password'])) {

	$dob_year = (int) $_POST['dob_year'];
	$dob_month = (int) $_POST['dob_month'];
	$dob_day = (int) $_POST['dob_day'];

	$dob = formatDate($dob_year, $dob_month, $dob_day);

	if (empty($_POST['new_password_email'])) {
		$GLOBALS['script_error_log'][] = _("You must provide a valid email address.");
	}
	
	if (empty($GLOBALS['script_error_log'])) {
		
		$query = "
			SELECT user_id
			FROM " . $db->prefix . "_user
			WHERE
			user_email=" . $db->qstr($_POST['new_password_email']) . " AND 
			user_dob=" . $db->qstr($dob)
		;

		$result = $db->Execute($query, 1);
		
		if (!empty($result[0]['user_id'])) {
			// we reset the password
			$new_password  = $new_password = substr(md5(time()), 0, 5);
			
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
			$mail->AddAddress($_POST['new_password_email']);
		
			if($mail->Send()) {
				// sent
				$body->set('new_password', 1);
			}
			// success message
			$GLOBALS['script_message_log'][] = _("Your profile information was updated.");
		}
		else {
			$GLOBALS['script_error_log'][] = _("We could not find a match to your email and memorable date. Please contact the service owner.");
		}
	}
}


$openIDMode=GetFromURL ("openid_mode"); 
if ($openIDMode) $openid_mode = $openIDMode; 

require_once('class/Openid.class.php');

if (isset($openid_mode) && !isset($_POST['login']) && !isset($_POST['trust'])) {

	$server = new OpenidServer($db, $core_config['security']['openid_encryption_level']);

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

if (!empty($core_config['security']['login_with_email'])) {
	$body->set('login_email_required', 1);
}

?>