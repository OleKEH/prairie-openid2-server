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


// Menu wrapper

function render_topMenu ($startblock = "", $endblock = "") {
	if (defined('SCRIPT_NAME') ) $currentscript = SCRIPT_NAME; 
		else $currentscript = ""; 
	$topMenu = 	$startblock; 
	$topMenu .= theme_topMenuItem (false, _("Home"), "/" );
	if (!empty($_SESSION['user_id'])) {
		
		$topMenu .= theme_topMenuItem ($currentscript == "profile", _("Profile"), "/profile" ); 
		$topMenu .= theme_topMenuItem ($currentscript == "editor", _("Edit"), "/editor" ); 
		$topMenu .= theme_topMenuItem ($currentscript == "account", _("Account"), "/account" ); 
		if (defined('USER_IS_MAINTAINER')) {
			$topMenu .= theme_topMenuItem ($currentscript == "maintain", _("Maintain"), "/maintain" ); 
		}
		$topMenu .= theme_topMenuItem (false, _("Log off"), "/disconnect" ); 
	} else {
		// no menus 
	}
	$topMenu .= $endblock; 
	return $topMenu; 
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo STND_LOCALE;?>" lang="<?php echo STND_LOCALE;?>">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="<?php echo STND_LOCALE;?>" />
	
	<?php
	if (defined('SCRIPT_NAME') && SCRIPT_NAME == 'profile') {
	?>
		<link rel="openid2.provider" href="http<?php if (isset($_SERVER['HTTPS'])&& (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == 1)) { echo 's';} ?>://<?php echo $_SERVER['HTTP_HOST']; ?>/login" />
		<link rel="openid.server" href="http<?php if (isset($_SERVER['HTTPS'])&& (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == 1)) { echo 's'; } ?>://<?php echo $_SERVER['HTTP_HOST']; ?>/login" />
	<?php }?>

	<title><?php if (isset($webspace_title)) { echo htmlspecialchars($webspace_title); } else { echo _("Prairie");}?></title>

	<script type="text/javascript" src="/<?php echo SCRIPT_TEMPLATE_PATH;?>js/functions.js"></script>
	<script type="text/javascript" src="/tiny_mce/tiny_mce.js"></script>
	<?php 
		echo theme_headincludes(); 
	?>
</head>
<?php 
		$startmenublock = theme_startmenublock(); 
		$endmenublock = theme_endmenublock(); 
		$topMenu = render_topMenu ( $startmenublock, $endmenublock); 
		$header = theme_head ($topMenu); 
		echo theme_bodypart ($header, $content); 
?>
</html>