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

?>

<div id="col_left">
	<div class="box" id="box_contact">
		<div class="box_header">
			<h1>Contact us</h1>
		</div>
		

		<form method="post">
		<div class="box_body">
			<p>
				<label for="id_contact_email"><?php echo _("Email");?></label>
				<input type="text" name="contact_email" id="id_contact_email" />
			</p>

			<p class="note">
				<?php echo _("Your email address will be sent to me to allow me to reply. It is not kept.");?>
			</p>

			<p>
				<label for="id_contact_subject"><?php echo _("Subject");?></label>
				<input type="text" name="contact_subject" id="id_contact_subject" />
			</p>
			
			<p>
				<label for="id_contact_message"><?php echo _("Message");?></label></br />
				<textarea name="contact_message" id="id_contact_message"></textarea>
			</p>

			<p>
				<?php echo _("Please solve the following mathematical problem so that we know you are a human.");?>
			</p>

			<p id="id_p_captcha">
				<label for="id_captcha"><?php echo $maptcha; ?></label></br />
				<input type="text" name="maptcha_text" id="id_captcha" value="" />
			</p>
	
			<p class="note">
				<?php echo _("Example: 2 * 2 = 4 or 0 - 9 = -9");?>
			</p>

			<p class="buttons">
				<input type="submit" name="send_email" value="send" />
			</p>
		</div>
		</form>
	</div>

	<?php
	if (isset($uri_routing[1]) && $uri_routing[1] == "policy") {
	?>
	<div class="box" id="id_content_box">
		<div class="box_header"><h1>Your privacy</h1></div>
		<div class="box_body">
			<p>All information except your email address and memorable date is displayed in your profile page. You can choose to either keep this private or reveal it to members or the public (this can be managed from your "account" page).</p>


			<p>We may use your name and email address to contact you for "service purposes". This means that we may contact you for a number of purposes related to the service that is supplied to you. For example, we may wish to provide you with password reminders or notify you that the particular service might not be available for a time.</p>
		</div>
	</div>
	<?php
	}
	elseif (isset($uri_routing[1]) && $uri_routing[1] == "tos") {
	?>
	<div class="box" id="id_content_box">
		<div class="box_header"><h1>Terms and conditions</h1></div>
		<div class="box_body">
			<p>This agreement takes place immediately when you register with this service. We may need to change these terms by posting changes on-line. We will try to inform you if we do by sending you an email, however your continued use of this service after changes are posted means you agree to be legally bound by these terms as updated and/or amended.</p>
			
			<p>You agree to use this website only for lawful purposes, and in a way that does not infringe the rights of, restrict or inhibit anyone else's use and enjoyment of this website.</p>
			
			<p>You agree that the service is provided "as is" and "is available" basis without any representations or any kind of warranty.</p>
			
			<p>Under no circumstances will we be liable for any of the following losses or damage (whether such losses where foreseen, foreseeable, known or otherwise): (a) loss of data; (b) loss of revenue or anticipated profits; (c) loss of business; (d) loss of opportunity; (e) loss of goodwill or injury to reputation; (f) losses suffered by third parties; or (g) any indirect, consequential, special or exemplary damages arising from the use of this website regardless of the form of action.</p>
			
			<p>A person viewing yoru profile picture will automatically download it to their computers cache via their web browser. Because of this we require your permission to distribute it. You agree that give us a royalty-free distribution license for your work.</p>

			<p>If there is any conflict between these terms and anything else in our service or from what anyone tells you then these terms will over-rule them. These terms shall be governed by and interpreted in accordance with the laws of the country where this service resides.</p>
		</div>
	</div>
	<?php
	}
	else {
	?>
	<div class="box" id="id_content_box">
		<div class="box_header"><h1>Welcome</h1></div>
		<div class="box_body">
			<p>This is an OpenID service. It is being run on free software called 'Prairie' which is developed and released by <a href="http://www.barnraiser.org/">Barnraiser</a>.</p>

			<?php
			if (isset($registration_allowed)) {
			?>
			<p>You can register by filling in our <a href="/register">registration form</a>.
			<?php }?>
</div>
	</div>
	<?php }?>
</div>



<div id="col_right">
	<div class="box">
		<div class="box_header">
			<h1>About us</h1>
		</div>
		
		<div class="box_body">
			<ul>
				<li><a href="/public">Welcome</a></li>
				<li><a href="/public/policy">Privacy</a></li>
				<li><a href="/public/tos">Terms and conditions</a></li>
			</ul>
		</div>

		<div class="box_footer">
			<span onclick="javascript:objShowHide('box_contact');" class="span_link"><?php echo _("Contact us");?></span>
		</div>
	</div>
</div>