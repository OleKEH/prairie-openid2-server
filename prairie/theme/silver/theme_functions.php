<?php
// This function returns extra header lines that is needed between the
// <head>...</head> for your theme. 
// Silver theme
function theme_headincludes () {
	
	$csscommon = SCRIPT_THEME_PATH."css/common.css";
	$cssScriptName =  SCRIPT_THEME_PATH."css/".SCRIPT_NAME.".css"; 
	$html = <<<HTT
<style type="text/css">
	<!--
	@import url($csscommon);
	@import url($cssScriptName);
	-->
	</style>	
	
HTT;
	return $html; 
}

function theme_article_wrapper ($body) {
	$html = <<<BTT
	<div id="box_freetext">
	<p>$body</p>
	</div>
BTT;
	return $html;  
}

function theme_aboutpic_wrapper ($heading, $imageurl, $imagetext="") {
	return ""; 
}

function theme_profile_body($formemail, $bodytext) {
	// dette er en test. 
	
	$html = <<<TXT
	<div id="col_left">
	<div class="box" id="box_contact">
		<div class="box_header">
			<h1><?php echo _("Email me");?></h1>
		</div>
		<form method="post">
		<div class="box_body">
			 $formemail
		</div>
		</form>
	</div>
	$bodytext 
	</div>
TXT;
	return $html; 
}

function theme_profile_sidebar ($userpicURL,$UserName, $UserLocation) {
	// this function puts the content in the sidebar (declaration of the sidebar itself). 
	// when using together with other template systems, cut and paste the code into this function. 
	$contactMe = _("Contact me");
	$html = <<<TX2
	<div id="col_right">
	<div class="box" id="box_profile">
		<div class="box_header">
			<h1><?php echo _("About me");?></h1>
		</div>

		<div class="box_body">
			<p>
				$UserName, $UserLocation
			</p>

			<div class="avatar">
				<img src="$userpicURL" />
			</div>
		</div>

		<div class="box_footer">
			<span onclick="javascript:objShowHide('box_contact');" class="span_link">$contactMe</span>
		</div>
	</div>
	</div>
TX2;
	
	return $html; 
	
}

function theme_startmenublock () {
	return "<ul>"; 
}

function theme_endmenublock () {
	return "</ul>"; 
}

function theme_topMenuItem ($selected, $buttontext, $menuURL) {
	if ($selected) $link_css = " class=\"current\"";
	else $link_css = "";
	
	$html = '<li><a href="'.$menuURL.'"'.$link_css.">". $buttontext."</a></li>\n"; 
	return $html; 
}


function theme_head ($menu) {
	
	if (defined('WEBSPACE_USERID')) $imgUrl = "/get_file.php?title=".(int)WEBSPACE_USERID; 
	else $imgUrl = "/get_file.php?title=0";
	$cont = link_('<img src="'.$imgUrl.'" border="0" alt="" />', "/"); 
	$html = <<<HDD
	<body>
	<div id="content_container">
	<div id="header_container">
			$menu
			<div id="header_title">
				$cont
			</div>
		</div>

		<div style="clear:both;"></div>
HDD;
		return $html; 
}



function theme_bodypart ($head, $body) {
	
	$errorMsg = ""; 
	if (!empty($GLOBALS['script_error_log'])) {
		$Msg = '<div id="system_error_container">'; 
		foreach($GLOBALS['script_error_log'] as $key => $val){
				$Msg .= $val . "<br />";
		}
		$errorMsg=$Msg."</div>"; 	
	}
	if (!isset($_SESSION['user_id']) && defined('WEBSPACE_OPENID'))	$managelink = link_( _("Manage"), "/login"); 
	else $managelink=""; 
	
	$footerText1 = _("Made with <a href='http://www.barnraiser.org/prairie/'>Prairie</a>");
	$html = <<<BDY
	
		$head
		$errorMsg
		
		
		<div id="body_container">
			 $body
		</div>
		
		<div style="clear:both;"></div>
		
		<div id="footer_container">
			<ul>
				<li>$footerText1</li>
				<li>$managelink</li>
			</ul>
		</div>

		<div style="clear:both;"></div>
	</div>
</body>	
	
	
BDY;
	return $html; 
	
}

?>