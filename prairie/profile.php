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


if (isset($_POST['submit_contact_form'])) {
	if (empty($_POST['contact_subject'])) {
		$GLOBALS['script_error_log'][] = _("Please enter a subject.");
	}

	if (empty($_POST['contact_message'])) {
		$GLOBALS['script_error_log'][] = _("Please enter a message.");
	}
	
	if (!match_maptcha($_POST['maptcha_text'])) {
		$GLOBALS['script_error_log'][] = _("You failed the math test dismally. Please try again.");
	}

	if (empty($GLOBALS['script_error_log'])) {

		$query = "
			SELECT user_email
			FROM " . $db->prefix . "_user
			WHERE openid_name=" . $db->qstr(WEBSPACE_OPENID)
		;
		
		$result = $db->Execute($query);

		$user_email = $result[0]['user_email'];	

		require_once('class/Mail/class.phpmailer.php');

		$email_subject = htmlspecialchars($_POST['contact_subject']);
		
		$mail->Subject = $email_subject;
	
		$email_message = htmlspecialchars($_POST['contact_message']);
	
		if (!empty($_POST['contact_email'])) {
			$email_message .= "\n\n";
			$email_message .= _("The sender included their email") . ": " . $_POST['contact_email'];
		}

		$email_message .= "\n\n";
		$email_message .= _("This mail was sent from Prairie");
	
	
		// HTML-version of the mail
		$html  = "<HTML><HEAD><TITLE></TITLE></HEAD>";
		$html .= "<BODY>";
		$html .= utf8_decode(nl2br($email_message));
		$html .= "</BODY></HTML>";
	
		$mail->Body = $html;
		// non - HTML-version of the email
		$mail->AltBody   = utf8_decode($email_message);
	
		$mail->ClearAddresses();
		$mail->AddAddress($user_email);
	
		if($mail->Send()) {
			// sent
			$GLOBALS['script_message_log'][] = _("Thank you for contacting us. We'll reply as soon as possible.");
		}
	}
}

$maptcha = gen_maptcha();
$body->set('maptcha', $maptcha);

?>