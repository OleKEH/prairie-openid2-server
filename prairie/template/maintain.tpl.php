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

<form method="post">
<?php
if (isset($uri_routing[1]) && $uri_routing[1] == "account" && isset($uri_routing[2])) {
?>

<div id="col_single">
	<div class="box" id="box_account">
		<div class="box_header">
			<h1><?php echo _("Account");?></h1>
		</div>

		<div class="box_body">
			<?php
			if (isset($account)) {
			?>
			<input type="hidden" name="user_id" value="<?php echo htmlspecialchars($account['user_id']);?>" />
			<input type="hidden" name="openid_name" value="<?php echo htmlspecialchars($account['openid_name']);?>" />
			
			<p>
				<label for="id_dob_year"><?php echo _("Memorable date");?></label>
				<?php
				if (isset($account['user_dob'])) {
					$dob = explode('-', $account['user_dob']);
				}
				?>
				<select name="dob_year" id="id_dob_year">
					<option value=""><?php echo _("Year");?></option>
					<?php 
						for($i = 2003; $i > 1908; $i--) { 
							$selected = "";
							if (isset($dob[0]) && (int) $dob[0] == $i) {
								$selected = " selected=\"selected\"";
							}
					?>
						<option value="<?php echo $i; ?>"<?php echo $selected; ?>><?php echo $i; ?></option>
					<?php } ?>
				</select> -
				<select name="dob_month" id="id_dob_month">
					<option value=""><?php echo _("Month");?></option>
					<option value="01"<?php if (isset($dob[1]) && (int) $dob[1] == 1) echo " selected=\"selected\""; ?>>01</option>
					<option value="02"<?php if (isset($dob[1]) && (int) $dob[1] == 2) echo " selected=\"selected\""; ?>>02</option>
					<option value="03"<?php if (isset($dob[1]) && (int) $dob[1] == 3) echo " selected=\"selected\""; ?>>03</option>
					<option value="04"<?php if (isset($dob[1]) && (int) $dob[1] == 4) echo " selected=\"selected\""; ?>>04</option>
					<option value="05"<?php if (isset($dob[1]) && (int) $dob[1] == 5) echo " selected=\"selected\""; ?>>05</option>
					<option value="06"<?php if (isset($dob[1]) && (int) $dob[1] == 6) echo " selected=\"selected\""; ?>>06</option>
					<option value="07"<?php if (isset($dob[1]) && (int) $dob[1] == 7) echo " selected=\"selected\""; ?>>07</option>
					<option value="08"<?php if (isset($dob[1]) && (int) $dob[1] == 8) echo " selected=\"selected\""; ?>>08</option>
					<option value="09"<?php if (isset($dob[1]) && (int) $dob[1] == 9) echo " selected=\"selected\""; ?>>09</option>
					<option value="10"<?php if (isset($dob[1]) && (int) $dob[1] == 10) echo " selected=\"selected\""; ?>>10</option>
					<option value="11"<?php if (isset($dob[1]) && (int) $dob[1] == 11) echo " selected=\"selected\""; ?>>11</option>
					<option value="12"<?php if (isset($dob[1]) && (int) $dob[1] == 12) echo " selected=\"selected\""; ?>>12</option>
				</select> - 
				<select name="dob_day" id="id_dob_day">
					<option value=""><?php echo _("Day");?></option>
					<option value="01"<?php if (isset($dob[2]) && (int) $dob[2] == 1) echo " selected=\"selected\""; ?>>01</option>
					<option value="02"<?php if (isset($dob[2]) && (int) $dob[2] == 2) echo " selected=\"selected\""; ?>>02</option>
					<option value="03"<?php if (isset($dob[2]) && (int) $dob[2] == 3) echo " selected=\"selected\""; ?>>03</option>
					<option value="04"<?php if (isset($dob[2]) && (int) $dob[2] == 4) echo " selected=\"selected\""; ?>>04</option>
					<option value="05"<?php if (isset($dob[2]) && (int) $dob[2] == 5) echo " selected=\"selected\""; ?>>05</option>
					<option value="06"<?php if (isset($dob[2]) && (int) $dob[2] == 6) echo " selected=\"selected\""; ?>>06</option>
					<option value="07"<?php if (isset($dob[2]) && (int) $dob[2] == 7) echo " selected=\"selected\""; ?>>07</option>
					<option value="08"<?php if (isset($dob[2]) && (int) $dob[2] == 8) echo " selected=\"selected\""; ?>>08</option>
					<option value="09"<?php if (isset($dob[2]) && (int) $dob[2] == 9) echo " selected=\"selected\""; ?>>09</option>
					<option value="10"<?php if (isset($dob[2]) && (int) $dob[2] == 10) echo " selected=\"selected\""; ?>>10</option>
					<option value="11"<?php if (isset($dob[2]) && (int) $dob[2] == 11) echo " selected=\"selected\""; ?>>11</option>
					<option value="12"<?php if (isset($dob[2]) && (int) $dob[2] == 12) echo " selected=\"selected\""; ?>>12</option>
					<option value="13"<?php if (isset($dob[2]) && (int) $dob[2] == 13) echo " selected=\"selected\""; ?>>13</option>
					<option value="14"<?php if (isset($dob[2]) && (int) $dob[2] == 14) echo " selected=\"selected\""; ?>>14</option>
					<option value="15"<?php if (isset($dob[2]) && (int) $dob[2] == 15) echo " selected=\"selected\""; ?>>15</option>
					<option value="16"<?php if (isset($dob[2]) && (int) $dob[2] == 16) echo " selected=\"selected\""; ?>>16</option>
					<option value="17"<?php if (isset($dob[2]) && (int) $dob[2] == 17) echo " selected=\"selected\""; ?>>17</option>
					<option value="18"<?php if (isset($dob[2]) && (int) $dob[2] == 18) echo " selected=\"selected\""; ?>>18</option>
					<option value="19"<?php if (isset($dob[2]) && (int) $dob[2] == 19) echo " selected=\"selected\""; ?>>19</option>
					<option value="20"<?php if (isset($dob[2]) && (int) $dob[2] == 20) echo " selected=\"selected\""; ?>>20</option>
					<option value="21"<?php if (isset($dob[2]) && (int) $dob[2] == 21) echo " selected=\"selected\""; ?>>21</option>
					<option value="22"<?php if (isset($dob[2]) && (int) $dob[2] == 22) echo " selected=\"selected\""; ?>>22</option>
					<option value="23"<?php if (isset($dob[2]) && (int) $dob[2] == 23) echo " selected=\"selected\""; ?>>23</option>
					<option value="24"<?php if (isset($dob[2]) && (int) $dob[2] == 24) echo " selected=\"selected\""; ?>>24</option>
					<option value="25"<?php if (isset($dob[2]) && (int) $dob[2] == 25) echo " selected=\"selected\""; ?>>25</option>
					<option value="26"<?php if (isset($dob[2]) && (int) $dob[2] == 26) echo " selected=\"selected\""; ?>>26</option>
					<option value="27"<?php if (isset($dob[2]) && (int) $dob[2] == 27) echo " selected=\"selected\""; ?>>27</option>
					<option value="28"<?php if (isset($dob[2]) && (int) $dob[2] == 28) echo " selected=\"selected\""; ?>>28</option>
					<option value="29"<?php if (isset($dob[2]) && (int) $dob[2] == 29) echo " selected=\"selected\""; ?>>29</option>
					<option value="30"<?php if (isset($dob[2]) && (int) $dob[2] == 30) echo " selected=\"selected\""; ?>>30</option>
					<option value="31"<?php if (isset($dob[2]) && (int) $dob[2] == 31) echo " selected=\"selected\""; ?>>31</option>
				</select>
			</p>

			<p>
				<label><?php echo _("email address");?></label>
				<input type="text" name="user_email" value="<?php echo htmlspecialchars($account['user_email']);?>" />
			</p>

			<p class="buttons">
				<input type="submit" name="update_profile" value="<?php echo _("update details");?>" />
				<?php
				if (!empty($account['user_live'])) {
				?>
				<input type="submit" name="send_new_password" value="<?php echo _("send new password");?>" />
				<?php
				}
				else {
				?>
				<input type="submit" name="send_new_verification" value="<?php echo _("send new verification email");?>" />
				<?php }?>
			</p>
			
			<?php
			}
			else {
			?>
			<p>
				<?php echo _("There are no active account.");?>
			</p>
			<?php }?>
		</div>

		<div class="box_footer">
			<a href="/maintain/accounts"><?php echo _("list accounts");?></a>
		</div>
	</div>
</div>

<?php
}
elseif (isset($uri_routing[1]) && $uri_routing[1] == "accounts") {
?>

<div id="col_single">
	<div class="box" id="box_accounts">
		<div class="box_header">
			<h1><?php echo _("Accounts");?></h1>
		</div>

		<div class="box_body">
			<?php
			if (isset($accounts)) {
			?>
			<ul>
			<?php
			foreach ($accounts as $key => $i):
			?>
			<li>
				<span class="openid_name"><a href="/maintain/account/<?php echo htmlspecialchars($i['user_id']);?>"><?php echo htmlspecialchars($i['openid_name']);?></a></span>
				<span class="user_name"><?php echo htmlspecialchars($i['user_name']);?></span>
				<span class="user_email"><a href="mailto:<?php echo htmlspecialchars($i['user_email']);?>"><?php echo htmlspecialchars($i['user_email']);?></a></span>
				<span class="user_dob"><?php echo htmlspecialchars($i['user_dob']);?></span>
				<span class="user_live"><?php if (!empty($i['user_live'])) { echo _("Live");} else { echo _("Pending");}?></span>
			</li>
			<?php
			endforeach;
			?>
			</ul>

			<div style="clear:both;"></div>
			
			<?php
			}
			else {
			?>
			<p>
				<?php echo _("There are no active accounts.");?>
			</p>
			<?php }?>
		</div>
	</div>
</div>

<?php
}
else {
?>

<div id="col_left">
	<?php
	if (!isset($single_account_instance)) {
	?>
	<div class="box">
		<div class="box_header">
			<h1><?php echo _("Accounts");?></h1>
		</div>

		<div class="box_body">
			<p>
				<?php
				$txt = ngettext("You have {1} live account.", "You have {1} live accounts.", $accounts);
				$txt .= " ";
				$txt .= ngettext("You have {2} pending account.", "You have {2} pending accounts.", $accounts_pending);
				$txt = str_replace("{1}", $accounts, $txt);
				$txt = str_replace("{2}", $accounts_pending, $txt);
				echo $txt;
				?>
			</p>
		</div>
		
		<div class="box_footer">
			<ul>
				<li><a href="/maintain/accounts"><?php echo _("view accounts");?></a></li>
			</ul>
		</div>
	</div>
	
	<div class="box">
		<div class="box_header">
			<h1><?php echo _("Unauthorized OpenID names");?></h1>
		</div>

		<div class="box_body">
			<p>
				<?php echo _("Unauthorized OpenID names are OpenID names that you do not allow. Use a comma to separate each name.");?>
			</p>
			
			<p>
				<label for="id_unauthorized_openid_names"><?php echo _("Names");?></label>
				<input type="text" name="unauthorized_openid_names" id="id_unauthorized_openid_names" value="<?php echo htmlspecialchars($arr_reg['unauthorized_openid_names']);?>" />
			</p>
			
			<p class="note">
				<?php echo _("Example 'www, ftp, http, https'");?>
			</p>

			<p class="buttons">
				<input type="submit" name="save_config_openid_names" value="<?php echo _("save");?>" />
			</p>
		</div>
	</div>
	
	<div class="box">
		<div class="box_header">
			<h1><?php echo _("Registration configuration");?></h1>
		</div>

		<div class="box_body">
			<p>
				<label for="id_allow_registration"><?php echo _("Allow registration");?></label>
				<input type="checkbox" value="1" name="allow_registration" id="id_allow_registration" <?php if (isset($arr_reg['allow_registration']) && $arr_reg['allow_registration'] == 1) { echo "checked=\"checked\"";}?> />
			</p>
			
			<p class="buttons">
				<input type="submit" name="save_config_allow_registration" value="<?php echo _("save");?>" />
			</p>
		</div>
	</div>
	<?php }?>

	<div class="box">
		<div class="box_header">
			<h1><?php echo _("Allowable HTML tags");?></h1>
		</div>

		<div class="box_body">
			<p>
				<?php echo _("Allowable HTML tags are the HTML tags that are available for us in the profile editor. Use the tag name in lowercase together with the surrounding < and > characters.");?>
			</p>
			
			<p>
				<label for="id_allowable_html_tags"><?php echo _("Allowable HTML tags");?></label>
				<input type="text" name="allowable_html_tags" id="id_allowable_html_tags" value="<?php echo $arr_security['allowable_html_tags'];?>" />
			</p>
			
			<p class="note">
				<?php echo _("Example '&lt;object>&lt;param>&lt;embed>'");?>
			</p>

			<p class="buttons">
				<input type="submit" name="save_config_html_tags" value="<?php echo _("save");?>" />
			</p>
		</div>
	</div>
</div>

<div id="col_right">
	<?php
	if (!isset($single_account_instance)) {
	?>
	<div class="box">
		<div class="box_header">
			<h1><?php echo _("Maintainer configuration");?></h1>
		</div>

		<div class="box_body">
			<p>
				<?php echo _("A 'Maintainer' is an administrator that has access to this screen. Add a maintainers OpenID account name. Use a comma to separate each name.");?>
			</p>
			
			<p>
				<label for="id_maintainer_openids"><?php echo _("Account names");?></label>
				<input type="text" name="maintainer_openids" id="id_maintainer_openids" value="<?php echo htmlspecialchars($arr_security['maintainer_openids']);?>" />
			</p>
			
			<p class="buttons">
				<input type="submit" name="save_config_maintainer_openids" value="<?php echo _("save");?>" />
			</p>
		</div>
	</div>

	
	<div class="box">
		<div class="box_header">
			<h1><?php echo _("Authorized email domains");?></h1>
		</div>

		<div class="box_body">
			<p>
				<?php echo _("Authorized email domains are the domain names of email accounts that you allow to register. Leave blank to allow any email domain to register. Use a comma to separate each domain name.");?>
			</p>
			
			<p>
				<label for="id_authorized_email_domains"><?php echo _("Email domains");?></label>
				<input type="text" name="authorized_email_domains" id="id_authorized_email_domains" value="<?php echo htmlspecialchars($arr_reg['email_domains']);?>" />
			</p>
			
			<p class="note">
				<?php echo _("Example 'barnraiser.org, barnraiser.net'");?>
			</p>

			<p class="buttons">
				<input type="submit" name="save_config_email_domains" value="<?php echo _("save");?>" />
			</p>
		</div>
	</div>
	<?php }?>

	<div class="box">
		<div class="box_header">
			<h1><?php echo _("Language configuration");?></h1>
		</div>

		<div class="box_body">
			<p>
				<?php echo _("Please follow the installation instructions in the language pack to change the language of this software.");?>
			</p>
			
			<p>
				<label for="id_standard_locale"><?php echo _("Standard locale");?></label>
				<input type="text" name="standard_locale" id="id_standard_locale" value="<?php echo htmlspecialchars($arr_lang['standard_locale']);?>" />
			</p>
			
			<p>
				<label for="id_server_locale"><?php echo _("Server locale");?></label>
				<input type="text" name="server_locale" id="id_server_locale" value="<?php echo htmlspecialchars($arr_lang['server_locale']);?>" />
			</p>

			<p class="buttons">
				<input type="submit" name="save_config_language" value="<?php echo _("save");?>" />
			</p>
		</div>
	</div>
</div>
<?php }?>
</form>