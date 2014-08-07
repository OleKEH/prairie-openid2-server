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


if (isset($webspace['webspace_css'])) {
?>
<style type="text/css">
	<!--
	<?php echo $webspace['webspace_css'];?>
	-->
	</style>
<?php 

}
 

	$emailForm = labelinput_("contact_email", _("Email address"));
	$emailForm.= note_(_("Your email address will be sent to me to allow me to reply. It is not kept.")); 
	$emailForm.= labelinput_("contact_subject", _("Subject")); 
	$emailForm.= labeltextarea_("contact_message", _("Message")); 
	$emailForm.= p_(_("Please solve the following mathematical problem so that we know you are a human.")); 
	$emailForm.= labelinput_("maptcha_text", $maptcha, "", "id_p_captcha", "id_captcha")."<br>"; 
	$emailForm.= note_(_("Example: 2 * 2 = 4 or 0 - 9 = -9")); 
	$emailForm.= p_(input_("submit_contact_form", _("send"), "submit"), "buttons"); 



	if (!empty($webspace['webspace_html'])) {
		$bodyText=theme_article_wrapper($webspace['webspace_html']);
	}
	else {
		$freetext = _("OpenID for {1}.");
		$freetext = str_replace("{1}", htmlspecialchars(WEBSPACE_OPENID), $freetext);
		$bodyText = theme_article_wrapper($freetext);
	}
	
	echo theme_profile_body($emailForm, $bodyText ); 
	echo theme_profile_sidebar ("/get_file.php?avatar=".(int)$webspace['user_id']."&amp;width=200", 
				htmlspecialchars($webspace['user_name']),
				htmlspecialchars($webspace['user_location']) ); 
?>