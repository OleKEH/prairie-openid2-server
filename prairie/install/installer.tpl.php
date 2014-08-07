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

<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<title>Prairie installer</title>

	<style type="text/css">
	<!--
	body { color: #333; font-size: 1.0em; margin:20px; margin-left: auto; margin-right: auto; padding: 0px; width: 810px; font-family: Helvetica, Arial, Lucida Grande, Lucida Sans Unicode, Bitstream Vera Sans, Geneva, sans-serif; }

	a, .span_link { color: #9a35f5; text-decoration: none; cursor: pointer; }
	a:hover, .span_link:hover { color: #f61646; }

	input[type="submit"], input[type="button"] { font-size: 0.8em; color: #fff; font-weight: bold; background-color: #beb9bf; border-style: solid; border-width: 0px 1px 1px 0px; border-color: #afafaf; text-transform: uppercase; }
	input[type="text"] { padding-left: 4px; }
	input[type="submit"] { cursor:pointer; }
	select { padding-left: 4px; }
	textarea { padding: 2px; font-family: Lucida Grande, Lucida Sans Unicode, Bitstream Vera Sans, Geneva, Helvetica, Arial, sans-serif; font-size: 0.9em; }
	
	ul { list-style-type: circle; margin: 12px; padding: 0px; }
	li { margin: 4px; padding-bottom: 4px; }

	#system_error_container { margin:4px; padding:4px; margin-left:0px; width: 800px; color: #b50000; font-size: 0.9em; border: 2px dotted #b50000; }

	.box { border-color: #e6e6e6; border-style: solid; border-width: 1px 1px 1px 1px; margin-bottom: 10px; }
	.box_header { margin:2px; margin-left: 8px; font-size: 0.8em; }
	.box_header h1 { font-weight: bold; font-size: 1.2em; margin:0px; padding-top: 4px; }
	.box_body { margin-left: 10px; margin-right: 10px; margin-bottom: 10px; }
	.box_footer { font-size: 0.7em; text-align: right; margin-left: 10px; margin-right:10px; padding:4px; border-top: 1px dotted #e6e6e6; }
	.box_footer ul { margin:0px; padding:0px; }
	.box_footer li { display: inline; margin:0px; padding:0px; padding-left: 4px; white-space: nowrap; }

	.buttons { text-align: right; padding-right: 1px; }
	.hint { color: #736257; font-size: 0.9em; border: 2px dotted #736257; padding: 6px; }
	.note { margin-left: 135px; margin-right: 25px; font-size: 0.8em;  font-style:italic; }
	.warning { color: #b50000; font-size: 0.9em; border: 2px dotted #b50000; padding: 6px; }

	#col_left { float:left; display: inline; width: 390px; }
	#col_right { float:left; width: 390px;  margin-left:30px; }
	
	.box_body label { float: left; width: 110px; margin-right: 25px; }
	.box_body p { clear:both; padding-bottom: 10px; }
	
	#id_dob_year { width: 65px; }
	#id_dob_month { width: 45px; }
	#id_dob_day { width: 45px; }
	
	table { margin-left: 20px; margin-right: 20px; margin-bottom: 10px; }
	td { padding: 4px; }
	-->
	</style>
</head>

<body>
	<?php
	if (!empty($GLOBALS['script_error_log'])) {
	?>
	<div id="system_error_container">
		<?php
		foreach($GLOBALS['script_error_log'] as $key => $val):
			echo $val . "<br />";
		endforeach;
		?>
	</div>
	<?php }?>
	
	
	<form method="post">

	<?php
	if (!isset($display)) {
	?>
	<div class="box" style="border-width: 0px;">
		<div class="box_header">
			<h1><?php echo _("System check");?></h1>
		</div>

		<div class="box_body">
			<p>
				<?php echo _("Please ensure that all checks have passed to enable the installer to be activated.");?>
			</p>

			<?php
			if (isset($system_checks)) {
			?>
				<table width="100%" cellspacing="0" cellpadding="0">
					<?php
					foreach($system_checks as $key => $value):
					?>
						<tr>
							<td><?php echo $value['result']; ?></td>
							<td><?php
								if (empty($value['is_valid'])) {
									echo _("FAILED");
								}
								else {
									echo _("PASSED");
								}
								?>
							</td>
						</tr>
					<?php
					endforeach;
					?>
				</table>
			<?php }?>
		</div>
	</div>


	<div class="box" style="border-width: 0px;">
		<div class="box_header">
			<h1><?php echo _("Prairie installer");?></h1>
		</div>

		<div class="box_body">
			<p>
				<?php
				$txt = _("This installer will install version {version} of Prairie. For more information on Prairie please visit the <a href='http://www.barnraiser.org/prairie'>Prairie product page</a>.");
				$txt = str_replace("{version}", $core_config['release']['version'], $txt);
				echo $txt;
				?>
			</p>
			

			<p>
				<label for="id_installation_type" style="width: 160px;"><?php echo ("Select installation type");?></label>
				<select name="installation_type" id="id_installation_type" <?php if ($is_error) { echo " disabled"; }?>>
					<option value="single" checked="checked"><?php echo ("Perform installation for a single user");?></option>
					<option value="multiuser"><?php echo ("Perform installation for a multiple users");?></option>
				</select>
			</p>
			
			<p class="buttons">
				<input type="submit" name="start_install" value="<?php echo ("Start installation");?>" <?php if ($is_error) { echo " disabled"; }?> />
			</p>
		</div>
	</div>
	<?php
	}
	elseif (isset($display) && $display == "no_chmod_allowed") {
	?>
	<div class="box">
		<div class="box_header">
			<h1><?php echo _("Installation complete");?></h1>
		</div>

		<div class="box_body">
			<p>
				<?php echo _("Your installation is complete. Please either change the permissions on the 'installer.php' file in the install directory to not be readable, writeable or executable or delete the file to complete your installation.");?>
			</p>

			<p>
				<?php
				$txt = _("Once completed please visit <a href='{url}'>your account</a> to comtinue.");
				$txt = str_replace("{url}", $post_chmod_url, $txt);
				echo $txt;
				?>

		</div>
	</div>
	<?php
	}
	elseif (isset($display) && $display == "install_form") {
	?>
	<div id="col_left">
		<div class="box">
			<div class="box_header">
				<h1><?php echo _("Setup domain");?></h1>
			</div>

			<div class="box_body">
				<input type="hidden" name="installation_type" value="<?php echo $_POST['installation_type'];?>" />
				<?php
				if (isset($_POST['installation_type']) && $_POST['installation_type'] == "multiuser") {
				?>

				<div id="id_new_subdomain1">
					<p>
						<?php
						$txt = _("The domain you try to install at is {domain}. This implies identity urls will look something like this:");
						$domain_txt = $http . "://" . $domain;
						$txt = str_replace("{domain}", $domain_txt, $txt);
						echo $txt;
						?>
					</p>
						
					<ul>
						<li><?php echo $http;?>://sarah.<?php echo $domain; ?></li>
						<li><?php echo $http;?>://james.<?php echo $domain; ?></li>
						<li><?php echo $http;?>://tom.<?php echo $domain; ?></li>
					</ul>
	
					<p class="buttons">
						<input type="button" onclick="document.getElementById('id_new_subdomain2').style.display='block'; document.getElementById('id_new_subdomain1').style.display='none';" value="<?php echo _("change this");?>" />
					</p>
				</div>
					
				<div id="id_new_subdomain2" style="display: none;">
					<p>
						<?php echo _("Give an example of how an identity url should look like.");?>
					</p>

					<p>
						<?php echo $http;?>:// <input type="text" name="new_subdomain" value="<?php echo $domain; ?>" />
					</p>
				</div>
				<?php
				}
				else {
				?>
				<p>
					Your OpenID will be <b><?php echo $http;?>://<?php echo $domain; ?></b>
				</p>

				<p class="buttons">
					<input type="button" onclick="document.getElementById('id_new_domain').style.display='block';" value="<?php echo _("change this");?>" />
				</p>

				<p id="id_new_domain" style="display: none;">
					Type in your OpenID URL.<br />
					<?php echo $http;?>://<input type="text" name="new_domain" value="<?php echo $domain; ?>" />
				</p>
				<?php }?>
			</div>
		</div>

		<div class="box">
			<div class="box_header">
				<h1><?php echo _("Configure database");?></h1>
			</div>

			<div class="box_body">
				<p>
					<label for="id_database_host"><?php echo _("Host");?></label>
					<input type="text" name="database_host" id="id_database_host" value="<?php if (isset($core_config['db']['host'])) { echo $core_config['db']['host'];}?>" />
				</p>

				<p class="note">
					<?php echo _("Example: localhost");?>
				</p>
	
				<p>
					<label for="id_database_user"><?php echo _("Username");?></label>
					<input type="text" name="database_user" id="id_database_user" value="<?php if (isset($core_config['db']['user'])) { echo $core_config['db']['user'];}?>" />
				</p>
	
				<p>
					<label for="id_database_password"><?php echo _("Password");?></label>
					<input type="text" name="database_password" id="id_database_password" value="<?php if (isset($core_config['db']['pass'])) { echo $core_config['db']['pass'];}?>" />
				</p>
	
				<p>
					<label for="id_database_db"><?php echo _("Database name");?></label>
					<input type="text" name="database_db" id="id_database_db" value="<?php if (isset($core_config['db']['db'])) { echo $core_config['db']['db'];} else { echo "aroundme_identity";}?>" />
				</p>

				<p class="note">
					<?php echo _("Example: prairie");?>
				</p>
			</div>
		</div>

		
		<div class="box">
			<div class="box_header">
				<h1><?php echo _("Configure email");?></h1>
			</div>

			<div class="box_body">
				<p>
					<label for="id_email_address"><?php echo _("Email address");?></label>
					<input type="text" name="email_address" id="id_email_address" value="<?php if (isset($core_config['mail']['email_address'])) { echo $core_config['mail']['email_address'];}?>" />
				</p>

				<p class="note">
					<?php echo _("This is the default address from which emails are sent.");?>
				</p>
			
				<p>
					<label for="id_email_host"><?php echo _("Email host");?></label>
					<input type="text" name="email_host" id="id_email_host" value="<?php if (isset($core_config['mail']['host'])) { echo $core_config['mail']['host'];}?>" />
				</p>

				<p class="note">
					<?php echo _("This is your SMTP server. Look in your email preferences and see what the address of the server used to send your emails is.");?>
				</p>
			
				<p>
					<label for="id_email_from_name"><?php echo _("Email From");?></label>
					<input type="text" name="email_from_name" id="id_email_from_name" value="<?php if (isset($core_config['mail']['from_name'])) { echo $core_config['mail']['from_name'];}?>" />
				</p>

				<p class="note">
					<?php echo _("This is the 'from name' that the email is send from. Typically it will be the name of the company, brand or service.");?>
				</p>
			
				<p>
					<label for="id_email_smtp_user"><?php echo _("Username");?></label>
					<input type="text" name="smtp_user" id="id_email_smtp_user" value="<?php if (isset($core_config['mail']['smtp']['username'])) { echo $core_config['mail']['smtp']['username'];}?>" />
				</p>
			
				<p>
					<label for="id_email_smtp_password"><?php echo _("Password");?></label>
					<input type="text" name="smtp_password" id="id_email_smtp_password" value="<?php if (isset($core_config['mail']['smtp']['password'])) { echo $core_config['mail']['smtp']['password'];}?>" />
				</p>

				<p class="note">
					<?php echo _("If you need a username and password to access SMTP type them above otherwise leave them empty.");?>
				</p>
			</div>
		</div>
	</div>

	<div id="col_right">
		<div class="box">
			<div class="box_header">
				<h1><?php echo _("Your account");?></h1>
			</div>

			<div class="box_body">

				<p>
					<?php echo _("We use a combination of your email and password to log you in.");?>
				</p>
				
				<p>
					<label for="id_user_email"><?php echo _("Email");?></label>
					<input type="text" name="user_email" id="id_user_email" value="<?php if (isset($_POST['user_email'])) { echo $_POST['user_email'];}?>"/>
				</p>
				
				<p>
					<?php echo _("Your password must be over 5 characters long.");?>
				</p>
				
				<p>
					<label for="id_user_password1"><?php echo _("Password");?></label>
					<input type="password" name="user_password1" id="id_user_password1" value="<?php if (isset($_POST['user_password1'])) { echo $_POST['user_password1']; }?>" />
				</p>
		
				<p>
					<label for="id_user_password2"><?php echo _("Repeat password");?></label>
					<input type="password" name="user_password2" id="id_user_password2" value="<?php if (isset($_POST['user_password2'])) { echo $_POST['user_password2']; }?>" />
				</p>
	
				<p>
					<?php echo _("Choose your OpenID name");?>
				</p>
				
				<p>
					<label for="id_openid_name"><?php echo _("OpenID name");?></label>
					<input type="text" name="openid_name" id="id_openid_name" value="<?php if (isset($_POST['openid_name'])) { echo $_POST['openid_name'];}?>" <?php if(isset($user_name)) { echo " readonly";}?> />
				</p>
	
				<p>
					<?php echo _("Add your name, location and a memorable date.");?>
				</p>
				
				<p>
					<label for="id_user_name"><?php echo _("Name");?></label>
					<input type="text" id="id_user_name" name="user_name" value="<?php if (isset($_POST['user_name'])) { echo $_POST['user_name']; }?>"/>
				</p>
		
				<p>
					<label for="id_user_location"><?php echo _("Location");?></label>
					<input type="text" id="id_user_location" name="user_location" value="<?php if (isset($_POST['user_location'])) { echo $_POST['user_location']; }?>"/>
				</p>
				
				<p>
					<label for="id_dob_year"><?php echo _("Memorable date");?></label>
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
			</div>
		</div>


		<div class="box">
			<div class="box_header">
				<h1><?php echo _("Perform installation");?></h1>
			</div>

			<div class="box_body">
				<p>
					<?php echo _("The installer will install Prairie then take you to your OpenID URL. Click the link marked 'manage' to login. Once logged in you will see a 'maintain' link at the top of your account which will provide you with additional setup options.");?>


			<p class="buttons">
				<input type="submit" name="perform_installation" value="<?php echo _("Install now");?>" />
			</p>
			</div>
		</div>
	<?php }?>
	</form>
</body>
</html>