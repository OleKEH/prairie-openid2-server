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

<?php
if (isset($webspace['webspace_css'])) {
?>
<style type="text/css">
	<!--
	<?php echo $webspace['webspace_css'];?>
	-->
	</style>
<?php }?>


<div id="col_left">
	<div class="box" id="box_contact">
		<div class="box_header">
			<h1><?php echo _("Email me");?></h1>
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
				<input type="submit" name="submit_contact_form" value="<?php echo _("send");?>" />
			</p>
		</div>
		</form>
	</div>

	<div id="box_freetext">
		<?php
		if (!empty($webspace['webspace_html'])) {
		?>
		<p>
			<?php echo $webspace['webspace_html'];?>
		</p>
		<?php
		}
		else {
		?>
		<p>
			<?php 
			$freetext = _("OpenID for {1}.");
			$freetext = str_replace("{1}", WEBSPACE_OPENID, $freetext);
			echo $freetext;
			?>
		</p>
		<?php }?>
	</div>
</div>


<div id="col_right">
	<div class="box" id="box_profile">
		<div class="box_header">
			<h1><?php echo _("About me");?></h1>
		</div>

		<div class="box_body">
			<p>
				<?php echo $webspace['user_name']; ?>,
				<?php echo $webspace['user_location']; ?>
			</p>

			<div class="avatar">
				<img src="/get_file.php?avatar=<?php echo $webspace['user_id']?>&amp;width=200" />
			</div>
		</div>

		<div class="box_footer">
			<span onclick="javascript:objShowHide('box_contact');" class="span_link"><?php echo _("Contact me");?></span>
		</div>
	</div>
</div>