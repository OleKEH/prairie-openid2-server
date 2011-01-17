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
	<div class="box" id="box_security">
		<div class="box_header">
			<h1><?php echo _("Security check");?></h1>
		</div>

		<div class="box_body">
			<p>
				<?php echo _("1. Check your OpenID account matches the first part of the URL given in your browser! Never enter your password unless they match.");?>
			</p>

			
			<p>
				<img src="/<?php echo SCRIPT_THEME_PATH;?>img/browser_warning.png" alt="<?php echo _("picture of a browsers url field");?>" />
			</p>
			
			<p>
				<?php echo _("2. Only your password should ever be typed into this screen. NEVER type it into any other screen! If you are asked for your OpenID password anywhere else then you risk compromising your OpenID account.");?>
			</p>
			
			<p>
				<img src="/<?php echo SCRIPT_THEME_PATH;?>img/password_warning.png" alt="<?php echo _("picture of a browsers url field");?>" />
			</p>

			
			<p>
				<?php echo _("3. Never give your password to anyone. Even the makers of this software do not need it when giving you technical support. NEVER write it down or give your password away to anyone.");?>
			</p>
			
		</div>
	</div>
</div>

<div id="col_right">
	<form method="post">
	<div class="box" id="box_lost_password" style="display:none;">
		<div class="box_header">
			<h1><?php echo _("Lost password?");?></h1>
		</div>

		<div class="box_body">
			<?php
			if (isset($new_password)) {
			?>
			<p>
				<?php echo _("Your new password has been emailed to you.");?>
			</p>
			<?php
			}
			else {
			?>
			<p>
				<?php echo _("Fill in the following details and we will email you a new password.");?>
			</p>
			
			<p>
				<label for="id_dob_year"><?php echo _("Memorable date");?></label><br />
				<select name="dob_year" id="id_dob_year">
					<option value=""><?php echo _("Year");?></option>
					<?php 
						for($i = 2003; $i > 1908; $i--) {
					?>
						<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
					<?php } ?>
				</select> -
				<select name="dob_month" id="id_dob_month">
					<option value=""><?php echo _("Month");?></option>
					<option value="01">01</option>
					<option value="02">02</option>
					<option value="03">03</option>
					<option value="04">04</option>
					<option value="05">05</option>
					<option value="06">06</option>
					<option value="07">07</option>
					<option value="08">08</option>
					<option value="09">09</option>
					<option value="10">10</option>
					<option value="11">11</option>
					<option value="12">12</option>
				</select> - 
				<select name="dob_day" id="id_dob_day">
					<option value=""><?php echo _("Day");?></option>
					<option value="01">01</option>
					<option value="02">02</option>
					<option value="03">03</option>
					<option value="04">04</option>
					<option value="05">05</option>
					<option value="06">06</option>
					<option value="07">07</option>
					<option value="08">08</option>
					<option value="09">09</option>
					<option value="10">10</option>
					<option value="11">11</option>
					<option value="12">12</option>
					<option value="13">13</option>
					<option value="14">14</option>
					<option value="15">15</option>
					<option value="16">16</option>
					<option value="17">17</option>
					<option value="18">18</option>
					<option value="19">19</option>
					<option value="20">20</option>
					<option value="21">21</option>
					<option value="22">22</option>
					<option value="23">23</option>
					<option value="24">24</option>
					<option value="25">25</option>
					<option value="26">26</option>
					<option value="27">27</option>
					<option value="28">28</option>
					<option value="29">29</option>
					<option value="30">30</option>
					<option value="31">31</option>
				</select>
			</p>

			<p>
				<label for="id_email"><?php echo _("Email");?></label>
				<input type="text" name="new_password_email" id="id_email" />
			</p>
			
			<p class="buttons">
				<input type="submit" name="submit_new_password" value="<?php echo _("send");?>" />
			</p>
			<?php }?>
		</div>
	</div>
	</form>


	<form method="post">
	<div class="box" id="box_login">
		<div class="box_header">
			<h1><?php echo _("Login");?></h1>
		</div>

		<div class="box_body">
			<?php
			if (isset($login_email_required)) {
			?>
			<p>
				<label for="id_login_email"><?php echo _("Email");?></label>
				<input type="text" id="id_login_email" value="" name="login_email" />
			</p>
			<?php }?>

			<p>
				<label for="id_login_password"><?php echo _("Password");?></label>
				<input type="password" id="id_login_password" value="" name="login_password" />
			</p>

			<p class="buttons">
				<input type="submit" name="login_admin" value="<?php echo _("log in");?>" />
			</p>
		</div>

		<div class="box_footer">
			<ul>
				<li onclick="javascript: objShowHide('box_lost_password');" class="span_link"><?php echo _("Lost password?");?></li>
			</ul>
		</div>
	</div>
	</form>
</div>