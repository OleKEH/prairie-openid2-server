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

if (!isset($_SESSION['user_id'])) {
	header("Location: /");
	exit;
}

if (isset($_POST['save_email_notification'])) {
		
	if (!empty($_POST['user_email_notify'])) {
		$email_notify=1;
	}
	else {
		$email_notify="NULL";
	}

	$query = "UPDATE " . $db->prefix . "_user 
		SET 
		user_email_notify=" . $email_notify . " 
		WHERE
		user_id=" . (int)$_SESSION['user_id']
	;
	
	$db->Execute($query);

	header('location: /account');
	exit;

}
elseif (isset($_POST['save_profile_information'])) {

	$dob_year = (int) $_POST['dob_year'];
	$dob_month = (int) $_POST['dob_month'];
	$dob_day = (int) $_POST['dob_day'];

	$dob = formatDate($dob_year, $dob_month, $dob_day);

	if (empty($_POST['user_name'])) {
		$GLOBALS['script_error_log'][] = _("You must provide a name.");
	}
	
	if (empty($GLOBALS['script_error_log'])) {
		
		$query = "UPDATE " . $db->prefix . "_user 
			SET 
			user_name=" . $db->qstr($_POST['user_name']) . ",
			user_dob=" . $db->qstr($dob) . ",
			user_location=" . $db->qstr($_POST['user_location']) .",
			user_nick=" . $db->qstr($_POST['user_nick']) . ",
			user_gender=" . $db->qstr($_POST['user_gender']) . ",
			user_postcode=" . $db->qstr($_POST['user_postcode']) . ",
			user_country=" . $db->qstr($_POST['user_country']) . ",
			user_language=" . $db->qstr($_POST['user_language']) . ",
			user_timezone=" . $db->qstr($_POST['user_timezone']) . ",
			user_birthdate=" . $db->qstr($_POST['user_birthdate']) . "
			WHERE user_id=" . (int)$_SESSION['user_id'].";";

		$db->Execute($query);
		
		if (empty($GLOBALS['script_error_log'])) {
			$_SESSION['user_name'] = $_POST['user_name'];
			$_SESSION['user_dob'] = $dob;
			$_SESSION['user_location'] = $_POST['user_location'];
			$_SESSION['user_nick'] = $_POST['user_nick'];
			$_SESSION['user_gender'] = $_POST['user_gender'];
			$_SESSION['user_postcode'] = $_POST['user_postcode'];
			$_SESSION['user_country'] = $_POST['user_country'];
			$_SESSION['user_language'] = $_POST['user_language'];
			$_SESSION['user_timezone'] = $_POST['user_timezone'];
			$_SESSION['user_birthdate'] = $_POST['user_birthdate'];
			
			// success message
			$GLOBALS['script_message_log'][] = _("Your profile information was updated.");
		}
	}
}
elseif (isset($_POST['change_user_email'])) {
	$_POST['user_email1'] = trim($_POST['user_email1']);
	$_POST['user_email2'] = trim($_POST['user_email2']);

	// We check to see if there is a list of valid email addresses
	if (!empty($core_config['script']['email_domains'])) {
		$email_domain =  substr(strrchr($_POST['user_email1'], "@"), 1 );

		if (!in_array($email_domain, $core_config['script']['email_domains'])) {
			$error_domains = implode(", ", $core_config['script']['email_domains']);
			$error_message = _("You must provide us with a valid email address. This has to be within the domains {1}.");
			$error_message = str_replace("{1}", $error_domains, $error_message);
			$GLOBALS['script_error_log'][] = $error_message;
		}
	}

	if (empty($GLOBALS['script_error_log'])) {
		if (empty($_POST['user_email1'])) {
			$GLOBALS['script_error_log'][] = _("You must provide us with an email address.");;
		}
	
		if ($_POST['user_email1'] != $_POST['user_email2']) {
			$GLOBALS['script_error_log'][] = _("Your email addresses did not match.");
		}
		
		if (empty($GLOBALS['script_error_log'])) {
			if (!checkEmail($_POST['user_email1'])) {
				$GLOBALS['script_error_log'][] = _("Your email address does not like a valid email address.");
			}
		}
	}
	
	if (empty($GLOBALS['script_error_log'])) {

		$query = "
			UPDATE " . $db->prefix . "_user
			SET user_email=" . $db->qstr(trim($_POST['user_email1'])) . " 
			WHERE user_id=" . (int)$_SESSION['user_id']
		;

		$db->Execute($query);

		header('location: /disconnect');
		exit;
	}
}
elseif (isset($_POST['change_user_password'])) {
	if (empty($_POST['user_password_old'])) {
		$GLOBALS['script_error_log'][] = _("You did not give us the correct current password.");
	}
	
	if ($_POST['user_password1'] != $_POST['user_password2']) {
		$GLOBALS['script_error_log'][] = _("Your new passwords did not match.");
	}
	
	if (strlen($_POST['user_password1']) < 2) {
		$GLOBALS['script_error_log'][] = _("Your password must be longer than 2 characters.");
	}
	
	if (empty($GLOBALS['script_error_log'])) {
		$query = "
			SELECT user_id
			FROM " . $db->prefix . "_user
			WHERE user_id=" . (int)$_SESSION['user_id'] . "
			AND user_password=" . $db->qstr(md5($_POST['user_password_old']))
		;
		
		$result = $db->Execute($query);
		
		if (!isset($result[0]['user_id'])) {
			$GLOBALS['script_error_log'][] = _("You did not give us the correct current password.");
		}
	}
	
	if (empty($GLOBALS['script_error_log'])) {
		$query = "
			UPDATE " . $db->prefix . "_user
			SET user_password=" . $db->qstr(md5($_POST['user_password1'])) . "
			WHERE
			user_id=" . (int)$_SESSION['user_id'] . " AND
			user_password=" . $db->qstr(md5($_POST['user_password_old']))
		;
		
		$db->Execute($query);

		$GLOBALS['script_message_log'][] = _("Your password has been changed.");
	}
}



// CHECK TO DISPLAY AVATAR DELETE BUTTON ------
$av = glob($core_config['file']['dir'] . "avatars/" . (int)$_SESSION['user_id'] . "/100*");

if (isset($av[0])) {
	$body->set('display_avatar_delete_button', 1);
}

?>