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
if (isset($email_sent)) { 
?>
<div id="col_full">
	<div class="box">
		<div class="box_body">
			<h1><?php echo _("Thank you!");?></h1>
			<p><?php echo _("You have been sent an email. Please click the link in it to activate your account.");?></p>
		</div>
	</div>
</div>
<?php
}
else {
?>
<div id="col_full">
	<form method="post">
	<div class="box">
		<div class="box_body">
			<h1><?php echo _("Register to obtain your OpenID!");?></h1>

			<p>
				<?php
				if (isset($email_domains)) {
					$email_domains_str = implode (", ", $email_domains);
					$email_domain_str = _("Registration is limited to people whom have an email account ending with {1}.");
					$email_domain_str = str_replace("{1}", htmlspecialchars($email_domains_str), $email_domain_str);
					echo $email_domain_str;
				}
				else {
					echo _("Joining is free for you. Simply fill in this registration form to come in!");
				}
				?>
			</p>
			
			<h2><?php echo _("Email and password");?></h2>

			<p>
				<?php echo _("We use a combination of your email and password to log you in.");?>
			</p>
			
			<p>
				<label for="id_user_email"><?php echo _("Email");?></label>
				<input type="text" name="user_email" id="id_user_email" value="<?php if (isset($_POST['user_email'])) { echo htmlspecialchars($_POST['user_email']);}?>"/>
			</p>
			
			<p>
				<?php echo _("Your password must be over 5 characters long.");?>
			</p>
			
			<p>
				<label for="id_user_password1"><?php echo _("Password");?></label>
				<input type="password" name="user_password1" id="id_user_password1" value="<?php if (isset($_POST['user_password1'])) { echo htmlspecialchars($_POST['user_password1']); }?>" />
			</p>
	
			<p>
				<label for="id_user_password2"><?php echo _("Repeat password");?></label>
				<input type="password" name="user_password2" id="id_user_password2" value="<?php if (isset($_POST['user_password2'])) { echo htmlspecialchars($_POST['user_password2']); }?>" />
			</p>


			<h2><?php echo _("Your OpenID name");?></h2>
			
			<p>
				<?php echo _("Choose your OpenID name");?>
			</p>
			
			<p>
				<label for="id_openid_name"><?php echo _("OpenID name");?></label>
				<input type="text" name="openid_name" id="id_openid_name" value="<?php if (isset($_POST['openid_name'])) { echo htmlspecialchars($_POST['openid_name']);}?>" />
			</p>
	
			
			<h2><?php echo _("About you");?></h2>
	
			<p>
				<label for="id_user_name"><?php echo _("Name");?></label>
				<input type="text" id="id_user_name" name="user_name" value="<?php if (isset($_POST['user_name'])) { echo htmlspecialchars($_POST['user_name']); }?>"/>
			</p>
	
			<p>
				<label for="id_user_location"><?php echo _("Location");?></label>
				<input type="text" id="id_user_location" name="user_location" value="<?php if (isset($_POST['user_location'])) { echo htmlspecialchars($_POST['user_location']); }?>"/>
			</p>
			
			<p>
				<label for="id_dob"><?php echo _("Memorable date");?></label>
				<select name="dob_year" id="id_dob_year">
					<option value=""><?php echo _("Year");?></option>
					<?php
						for($i = 2003; $i > 1908; $i--) {
							$selected = "";
							if (isset($_POST['dob_year']) && $_POST['dob_year'] == $i) {
								$selected = " selected=\"selected\"";
							}
					?>
						<option value="<?php echo $i; ?>"<?php echo $selected; ?>><?php echo $i; ?></option>
					<?php } ?>
				</select> -
				<select name="dob_month" id="id_dob_month">
					<option value=""><?php echo _("Month");?></option>
					<option value="01"<?php if (isset($_POST['dob_month']) && $_POST['dob_month'] == 1) echo " selected=\"selected\""; ?>>01</option>
					<option value="02"<?php if (isset($_POST['dob_month']) && $_POST['dob_month'] == 2) echo " selected=\"selected\""; ?>>02</option>
					<option value="03"<?php if (isset($_POST['dob_month']) && $_POST['dob_month'] == 3) echo " selected=\"selected\""; ?>>03</option>
					<option value="04"<?php if (isset($_POST['dob_month']) && $_POST['dob_month'] == 4) echo " selected=\"selected\""; ?>>04</option>
					<option value="05"<?php if (isset($_POST['dob_month']) && $_POST['dob_month'] == 5) echo " selected=\"selected\""; ?>>05</option>
					<option value="06"<?php if (isset($_POST['dob_month']) && $_POST['dob_month'] == 6) echo " selected=\"selected\""; ?>>06</option>
					<option value="07"<?php if (isset($_POST['dob_month']) && $_POST['dob_month'] == 7) echo " selected=\"selected\""; ?>>07</option>
					<option value="08"<?php if (isset($_POST['dob_month']) && $_POST['dob_month'] == 8) echo " selected=\"selected\""; ?>>08</option>
					<option value="09"<?php if (isset($_POST['dob_month']) && $_POST['dob_month'] == 9) echo " selected=\"selected\""; ?>>09</option>
					<option value="10"<?php if (isset($_POST['dob_month']) && $_POST['dob_month'] == 10) echo " selected=\"selected\""; ?>>10</option>
					<option value="11"<?php if (isset($_POST['dob_month']) && $_POST['dob_month'] == 11) echo " selected=\"selected\""; ?>>11</option>
					<option value="12"<?php if (isset($_POST['dob_month']) && $_POST['dob_month'] == 12) echo " selected=\"selected\""; ?>>12</option>
				</select> -
				<select name="dob_day" id="id_dob_day">
					<option value=""><?php echo _("Day");?></option>
					<option value="01"<?php if (isset($_POST['dob_day']) && $_POST['dob_day'] == 1) echo " selected=\"selected\""; ?>>01</option>
					<option value="02"<?php if (isset($_POST['dob_day']) && $_POST['dob_day'] == 2) echo " selected=\"selected\""; ?>>02</option>
					<option value="03"<?php if (isset($_POST['dob_day']) && $_POST['dob_day'] == 3) echo " selected=\"selected\""; ?>>03</option>
					<option value="04"<?php if (isset($_POST['dob_day']) && $_POST['dob_day'] == 4) echo " selected=\"selected\""; ?>>04</option>
					<option value="05"<?php if (isset($_POST['dob_day']) && $_POST['dob_day'] == 5) echo " selected=\"selected\""; ?>>05</option>
					<option value="06"<?php if (isset($_POST['dob_day']) && $_POST['dob_day'] == 6) echo " selected=\"selected\""; ?>>06</option>
					<option value="07"<?php if (isset($_POST['dob_day']) && $_POST['dob_day'] == 7) echo " selected=\"selected\""; ?>>07</option>
					<option value="08"<?php if (isset($_POST['dob_day']) && $_POST['dob_day'] == 8) echo " selected=\"selected\""; ?>>08</option>
					<option value="09"<?php if (isset($_POST['dob_day']) && $_POST['dob_day'] == 9) echo " selected=\"selected\""; ?>>09</option>
					<option value="10"<?php if (isset($_POST['dob_day']) && $_POST['dob_day'] == 10) echo " selected=\"selected\""; ?>>10</option>
					<option value="11"<?php if (isset($_POST['dob_day']) && $_POST['dob_day'] == 11) echo " selected=\"selected\""; ?>>11</option>
					<option value="12"<?php if (isset($_POST['dob_day']) && $_POST['dob_day'] == 12) echo " selected=\"selected\""; ?>>12</option>
					<option value="13"<?php if (isset($_POST['dob_day']) && $_POST['dob_day'] == 13) echo " selected=\"selected\""; ?>>13</option>
					<option value="14"<?php if (isset($_POST['dob_day']) && $_POST['dob_day'] == 14) echo " selected=\"selected\""; ?>>14</option>
					<option value="15"<?php if (isset($_POST['dob_day']) && $_POST['dob_day'] == 15) echo " selected=\"selected\""; ?>>15</option>
					<option value="16"<?php if (isset($_POST['dob_day']) && $_POST['dob_day'] == 16) echo " selected=\"selected\""; ?>>16</option>
					<option value="17"<?php if (isset($_POST['dob_day']) && $_POST['dob_day'] == 17) echo " selected=\"selected\""; ?>>17</option>
					<option value="18"<?php if (isset($_POST['dob_day']) && $_POST['dob_day'] == 18) echo " selected=\"selected\""; ?>>18</option>
					<option value="19"<?php if (isset($_POST['dob_day']) && $_POST['dob_day'] == 19) echo " selected=\"selected\""; ?>>19</option>
					<option value="20"<?php if (isset($_POST['dob_day']) && $_POST['dob_day'] == 20) echo " selected=\"selected\""; ?>>20</option>
					<option value="21"<?php if (isset($_POST['dob_day']) && $_POST['dob_day'] == 21) echo " selected=\"selected\""; ?>>21</option>
					<option value="22"<?php if (isset($_POST['dob_day']) && $_POST['dob_day'] == 22) echo " selected=\"selected\""; ?>>22</option>
					<option value="23"<?php if (isset($_POST['dob_day']) && $_POST['dob_day'] == 23) echo " selected=\"selected\""; ?>>23</option>
					<option value="24"<?php if (isset($_POST['dob_day']) && $_POST['dob_day'] == 24) echo " selected=\"selected\""; ?>>24</option>
					<option value="25"<?php if (isset($_POST['dob_day']) && $_POST['dob_day'] == 25) echo " selected=\"selected\""; ?>>25</option>
					<option value="26"<?php if (isset($_POST['dob_day']) && $_POST['dob_day'] == 26) echo " selected=\"selected\""; ?>>26</option>
					<option value="27"<?php if (isset($_POST['dob_day']) && $_POST['dob_day'] == 27) echo " selected=\"selected\""; ?>>27</option>
					<option value="28"<?php if (isset($_POST['dob_day']) && $_POST['dob_day'] == 28) echo " selected=\"selected\""; ?>>28</option>
					<option value="29"<?php if (isset($_POST['dob_day']) && $_POST['dob_day'] == 29) echo " selected=\"selected\""; ?>>29</option>
					<option value="30"<?php if (isset($_POST['dob_day']) && $_POST['dob_day'] == 30) echo " selected=\"selected\""; ?>>30</option>
					<option value="31"<?php if (isset($_POST['dob_day']) && $_POST['dob_day'] == 31) echo " selected=\"selected\""; ?>>31</option>
				</select>
			</p>

			<p class="note">
				<?php echo _("We do not display your memorable date anywhere, but we do use it to verify you should you wish to contact us.");?>
			</p>

			<h2><?php echo _("A little test");?></h2>
			<p>
				<?php echo _("Please solve the following mathematical problem so that we know you are a human.");?>
			</p>

			<p>
				<label for="id_captcha"><?php echo $maptcha; ?></label>
				<input type="text" name="maptcha_text" id="id_captcha" value="" />
			</p>
	
			<p class="note">
				<?php echo _("Example: 2 * 2 = 4 or 0 - 9 = -9");?>
			</p>
			
	
			<p class="toc">
				<label for="id_tos"><?php echo _("I agree to the <a href='/public/tos' target='_new'>terms of service</a>.");?></label>
				<input type="checkbox" name="tos" id="id_tos" value="1" <?php if (isset($_POST['tos']) && !empty($_POST['tos'])) echo "checked=\"checked\"";?>/>
			</p>
	
			<p class="buttons">
				<input type="submit" name="register" value="<?php echo _("REGISTER");?>" />
			</p>
		</div>
	</div>
	</form>
</div>
<?php }?>