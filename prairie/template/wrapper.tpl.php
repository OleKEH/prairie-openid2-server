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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo STND_LOCALE;?>" lang="<?php echo STND_LOCALE;?>">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="<?php echo STND_LOCALE;?>" />
	
	<?php
	if (defined('SCRIPT_NAME') && SCRIPT_NAME == 'profile') {
	?>
		<link rel="openid2.provider" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/login" />
		<link rel="openid.server" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/login" />
	<?php }?>

	<title><?php if (isset($webspace_title)) { echo $webspace_title; } else { echo _("Prairie");}?></title>

	<script type="text/javascript" src="/<?php echo SCRIPT_TEMPLATE_PATH;?>js/functions.js"></script>
	<script type="text/javascript" src="/tiny_mce/tiny_mce.js"></script>
	<style type="text/css">
	<!--
	@import url(/<?php echo SCRIPT_THEME_PATH;?>css/common.css);
	@import url(/<?php echo SCRIPT_THEME_PATH;?>css/<?php echo SCRIPT_NAME;?>.css);
	-->
	</style>
</head>

<body>
	<div id="content_container">
		<div id="header_container">
			<ul>
				<?php
				if (!empty($_SESSION['user_id'])) {
				?>
				<?php
				$link_css = "";
				if (defined('SCRIPT_NAME') && SCRIPT_NAME == "profile") {
					$link_css = " class=\"current\"";
				}
				?>
				<li><a href="/profile"<?php echo $link_css;?>><?php echo _("Profile");?></a></li>


				<?php
				$link_css = "";
				if (defined('SCRIPT_NAME') && SCRIPT_NAME == "editor") {
					$link_css = " class=\"current\"";
				}
				?>
				<li><a href="/editor"<?php echo $link_css;?>><?php echo _("Edit");?></a></li>
				
				<?php
				$link_css = "";
				if (defined('SCRIPT_NAME') && SCRIPT_NAME == "account") {
					$link_css = " class=\"current\"";
				}
				?>
				<li><a href="/account"<?php echo $link_css;?>><?php echo _("Account");?></a></li>

				<?php
				if (defined('USER_IS_MAINTAINER')) {
				$link_css = "";
				if (defined('SCRIPT_NAME') && SCRIPT_NAME == "maintain") {
					$link_css = " class=\"current\"";
				}
				?>
				<li><a href="/maintain"<?php echo $link_css;?>><?php echo _("Maintain");?></a></li>
				<?php }?>
				
				<li><a href="/disconnect"><?php echo _("Log off");?></a></li>
				<?php }?>
			</ul>
			
			<div id="header_title">
				<a href="/"><img src="/get_file.php?title=<?php if (defined('WEBSPACE_USERID')) { echo WEBSPACE_USERID; } else { echo 0;}?>" border="0" alt="" /></a>
			</div>
		</div>

		<div style="clear:both;"></div>

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
		
		<div id="body_container">
			<?php echo $content;?>
		</div>
		
		<div style="clear:both;"></div>
		
		<div id="footer_container">
			<ul>
				<li><?php echo _("Made with <a href='http://www.barnraiser.org/prairie/'>Prairie</a>");?></li>
				<?php
				if (!isset($_SESSION['user_id']) && defined('WEBSPACE_OPENID')) {
				?>
				<li><a href="/login"><?php echo _("Manage");?></a></li>
				<?php }?>
			</ul>
		</div>

		<div style="clear:both;"></div>
	</div>
</body>
</html>