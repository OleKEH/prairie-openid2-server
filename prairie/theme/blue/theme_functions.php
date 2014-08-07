<?php
// This function returns extra header lines that is needed between the
// <head>...</head> for your theme. 
// Blue script
function theme_headincludes () {
	
	$csscommon = SCRIPT_THEME_PATH."css/common.css";
	$cssScriptName =  SCRIPT_THEME_PATH."css/".SCRIPT_NAME.".css"; 
	$tmppath = "/theme/blue"; 
//    <script type="text/javascript" src="$tmppath/script.js"></script>  
	$html = <<<HTT
<style type="text/css">
	<!--
	@import url($csscommon);
	@import url($cssScriptName);
	-->
	</style>	
	<link rel="stylesheet" href="$tmppath/css/style.css" type="text/css" media="screen" />
    <!--[if IE 6]><link rel="stylesheet" href="$tmppath/css/style.ie6.css" type="text/css" media="screen" /><![endif]-->
    <!--[if IE 7]><link rel="stylesheet" href="$tmppath/css/style.ie7.css" type="text/css" media="screen" /><![endif]-->
 
HTT;
	return $html; 
}

function theme_article_wrapper ($body) {
	$html = <<<BTT
	 <div class="art-layout-cell art-content">
       <div class="art-post">
         <div class="art-post-body">
            <div class="art-post-inner art-article">
          	  <div id="box_freetext">		
	           $body
	           </div>
	        </div>
            <div class="cleared"></div>
         </div>                            
         <div class="cleared"></div>
       </div>
    </div>
    <div class="cleared"></div>
BTT;
	return $html;  
	/*
	 * <div id="box_freetext">
	<p>$body</p>
	</div>
	 */
}

function theme_aboutpic_wrapper ($heading, $imageurl, $imagetext="") {
	return ""; 
}

function theme_profile_body($formemail, $bodytext) {
	// dette er en test. 
	
	$html = <<<TXT
	<!-- col-left -->
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
  <!-- /col-left -->
	
TXT;

	return $html; 
}

function theme_sidebar_block ($body, $header="") {
	
	if ($header) {
		$hdTxt = '<div class="art-blockheader">'; 
		$hdTxt .= '<div class="t">'.$header."</div></div>";
	} else $hdTxt=""; 
	
	$html= <<<TXT
<!-- art-block -->
  <div class="art-block">
    <div class="art-block-tl"></div>
    <div class="art-block-tr"></div>
    <div class="art-block-bl"></div>
    <div class="art-block-br"></div>
    <div class="art-block-tc"></div>
    <div class="art-block-bc"></div>
    <div class="art-block-cl"></div>
    <div class="art-block-cr"></div>
    <div class="art-block-cc"></div>
    <div class="art-block-body">
      $hdTxt
      <div class="art-blockcontent">
        <div class="art-blockcontent-body">
          <!-- block-content -->
          $body <!-- /block-content -->
          <div class="cleared"></div>
        </div>
      </div>
      <div class="cleared"></div>
    </div>
  </div>
  <!-- /art-block -->
TXT;
	return $html; 
}



function theme_profile_sidebar ($userpicURL,$UserName, $UserLocation) {
	// this function puts the content in the sidebar (declaration of the sidebar itself). 
	// when using together with other template systems, cut and paste the code into this function. 
	$contactMe = _("Contact me");
	
	$userPicInner = <<<USE
	<div>
		<p>$UserName, $UserLocation</p>
       <img src="$userpicURL" alt="an image" style="margin: 0 auto;display:block;width:95%" />
       
 		<div class="box_footer">
			<span onclick="javascript:objShowHide('box_contact');" class="span_link">$contactMe</span>
		</div>      
    </div>    
USE;
	
	$picblock = theme_sidebar_block ($userPicInner , _("About me")); 
	$html = <<<TX2
	<!-- Sidebar 1 -->
	<div class="art-layout-cell art-sidebar1">
    <div class="art-block">
    <div class="art-block-tl"></div>
    <div class="art-block-tr"></div>
    <div class="art-block-bl"></div>
    <div class="art-block-br"></div>
    <div class="art-block-tc"></div>
    <div class="art-block-bc"></div>
    <div class="art-block-cl"></div>
    <div class="art-block-cr"></div>
    <div class="art-block-cc"></div>
	$picblock
	</div>
    </div>
    <!-- /Sidebar 1 -->
TX2;
	
	return $html; 
	
}

function theme_startmenublock () {
	$html = <<<SMB
	<!-- menu block -->
  <div class="art-nav">
    <div class="l"></div>
    <div class="r"></div>
    <ul class="art-menu">
SMB;

	return $html; 
}

function theme_endmenublock () {
	return "</ul></div>\n<!-- /menu block -->\n"; 
}

function theme_topMenuItem ($selected, $buttontext, $menuURL) {
	if ($selected) $linkclass = "active";
	else $linkclass = "";
	$html = <<<XXX
	<li>
		<a href="$menuURL" class="$linkclass"><span class="l"></span><span class="r"></span><span class="t">$buttontext</span></a>
	</li>
	
XXX;
	return $html; 
}


function theme_head ($menu) {
	// The following lines are for making an graphic of the username in the header. 
//	if (defined('WEBSPACE_USERID')) $imgUrl = "/get_file.php?title=".WEBSPACE_USERID; 
//	else $imgUrl = "/get_file.php?title=0";
//	$cont = link_('<img src="'.$imgUrl.'" border="0" alt="" />', "/"); 
	// Alternate: Have header as TEXT. 
	$cont = htmlspecialchars(WEBSPACE_USERNAME);
	$html = <<<HDD
	<!-- header -->
  <div class="art-header">
    <div class="art-header-png"></div>
    <div class="art-header-jpeg"></div>
    <div class="art-logo">
      <h1 id="name-text" class="art-logo-name">$cont</h1>
      <div id="slogan-text" class="art-logo-text">
        Profile page
      </div>
    </div>
  </div>
  <!-- /header -->
  $menu
HDD;
		return $html; 
}

// function theme_layoutrow_



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
<body>
  <div id="art-page-background-gradient"></div>
  <div id="art-page-background-glare">
    <div id="art-page-background-glare-image"></div>
  </div>
  
  <!-- art-main -->
  <div id="art-main">
  
  <!-- art-sheet -->
    <div class="art-sheet">
      <div class="art-sheet-tl"></div>
      <div class="art-sheet-tr"></div>
      <div class="art-sheet-bl"></div>
      <div class="art-sheet-br"></div>
      <div class="art-sheet-tc"></div>
      <div class="art-sheet-bc"></div>
      <div class="art-sheet-cl"></div>
      <div class="art-sheet-cr"></div>
      <div class="art-sheet-cc"></div>
      
      <!-- sheet-body -->
      <div class="art-sheet-body">
        $head $errorMsg
        
        <!-- art-content-layout -->
        <div class="art-content-layout">
          <div class="art-content-layout-row">
            $body
          </div>
        </div>
        <!-- /art-content-layout -->
        
        <div class="cleared"></div>
        
        <!-- footer -->
        <div class="art-footer">
          <div class="art-footer-t"></div>
          <div class="art-footer-l"></div>
          <div class="art-footer-b"></div>
          <div class="art-footer-r"></div>
          
          <!-- art-footer-body -->
          <div class="art-footer-body">
            <div class="art-footer-text">
              <ul>
                <li>$footerText1</li>
                <li>$managelink</li>
              </ul>
            </div>
            <div class="cleared"></div>
          </div>
          <!-- /art-footer-body -->
          
        </div>
        <div class="cleared"></div>
        <!-- /footer -->
        
      </div>
      <!-- /sheet-body -->
      
    </div>
      <!-- art-sheet -->
      
    <div class="cleared"></div>
    <p class="art-page-footer"><a href="http://www.nett-tech.com/">Web Template</a> created by NETT-TECH.COM.</p>
  </div>
  <!-- art-main -->
  
</body>
BDY;
	return $html; 
	
}

?>