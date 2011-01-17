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
include_once ("config/core.config.php");
include_once ("inc/functions.inc.php");

if (isset($_POST['send_email'])) {
	if (empty($_POST['email'])) {
		$GLOBALS['script_error_log'][] = _("You have not given us your email address.");
	}
	
	if (empty($_POST['contact_message'])) {
		$GLOBALS['script_error_log'][] = _("You have not given us a message.");	
	}
	
	if (empty($GLOBALS['script_message_log'])) {
		require_once('class/Mail/class.phpmailer.php');
	
		// email, subject, message
		$email_subject = _("Email from Prairie");
		
		$mail->Subject = $email_subject;
		
		$email_message = $_POST['contact_message'];
		
		// HTML-version of the mail
		$html  = "<HTML><HEAD><TITLE></TITLE></HEAD>";
		$html .= "<BODY>";
		$html .= utf8_decode(nl2br($email_message));
		$html .= "</BODY></HTML>";
	
		$mail->Body = $html;
		// non - HTML-version of the email
		$mail->AltBody   = utf8_decode(strip_tags($email_message));
		$mail->AddAddress($core_config['mail']['email_address']);
		$mail->From = $_POST['email'];
		
		if($mail->Send()) {
			// sent
			$GLOBALS['script_message_log'][] = _("Thank you for contacting us. We'll reply as soon as possible.");
		}
	}
}

if (isset($core_config['script']['multiple_webspace_pattern'])) {
	$body->set('registration_allowed', 1);
}

$maptcha = gen_maptcha();
$body->set('maptcha', $maptcha);

?>