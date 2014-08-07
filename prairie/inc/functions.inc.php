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


function Link_($name, $url) {
	$html='<a href="'.$url.'">'.$name.'</a>'; 
	return $html; 	
}

function GetFromURL ($param) {
	if ($_SERVER["REQUEST_METHOD"]=="GET") {
		if (isset ($_GET[$param])) return $_GET[$param]; 
		else return ""; 
	} else {
		if (isset ($_POST[$param])) return $_POST[$param];
		else return ""; 
	}
}

function table_($width=0, $style="", $border=0) {
	if ($width==0) $html="</table>\n";
	else {
		$html="<table"; 
		if ($style!="") $html .= " class='". $style . "'"; 
		$html .= ' width="'.$width.'%" border="'. $border. '">'."\n"; 
	}
	return $html; 
}

function tr_($content="/", $style="", $option="") {
	if ($content == "/") $html="</tr>\n"; 
	else {
		$html="<tr"; 
		if ($style!="") $html .= " class ='".$style."'";
		if ($option!="") $html .= $option; 
		if ($content!="") $html .=">".$content."</tr>\n"; 
		else $html .=">";  
	}	
	return $html; 
}

function td_($content="/", $style="", $width=0, $colspan=1, $option="") {
	if ($content == "/") $html="</td>\n"; 
	else {
		$html="<td"; 
		if ($colspan!=1) $html.=' colspan="'.$colspan.'"';
		if ($width!=0) $html.=' width="'.$width.'%"'; 
		if ($style!="") $html .= " class ='".$style."'";
		if ($option!="") $html .= $option; 
		if ($content!="") $html .=">".$content."</td>\n"; 
		else $html .=">";  
	}	
	return $html; 
}

function tdt_($content="/", $style="", $option="") {
	if ($content == "/") $html="</td>\n"; 
	else {
		$html='<td valign="top"'; 
		if ($style!="") $html .= " class ='".$style."'";
		if ($option!="") $html .= $option; 
		if ($content!="") $html .=">".$content."</td>\n"; 
		else $html .=">";  
	}	
	return $html; 
}

function b_($content){
	return "<b>".$content."</b>"; 
}
function c_ ($content){
	return "<center>".$content."</center>"; 
}
function form_($action="", $method="POST", $style="") {
	if ($action=="") $html="</form>\n"; 
	else {
		$html='<form action="'.$action.'" method="'.$method.'"'; 
		if ($style!="") $html .= ' class="'.$style.'"'; 
		$html .=">\n"; 
	}
	return $html; 
}

function p_($body, $id="") {
	if ($id) $idtx=' id="'.$id.'"'; else $idtx=""; 
	return "<p$idtx>$body</p>"; 
}

function note_($body) {
	return '<p class="note">'.$body.'</p>';
}

function checkbox_($name, $defval="") {
	// '<input name="xxid" type="checkbox" id="xxid" value="1" checked>'
	$html='<input name="'.$name.'" type="checkbox" id="'.$name.'" value="1"'; 
	if (($defval) && ($defval!="0") && ($defval!="false")) $html.=" checked"; 
	$html.=">\n"; 
	return $html; 
}

function labelinput_($name, $label, $value="", $pid="", $iid="") {
	if ($iid) $ixid = $iid; else $ixid = "id_".$name; 
	$html=p_("<label for=".'"'.$ixid.'">'.$label."</label>
		<input type=".'"text" name="'.$name.'" id="'.$ixid.'" value="'.$value.'" />', $pid);
	return $html; 
}

function labeltextarea_($name, $label, $value="") {
	$html=p_("<label for=".'id_'.$name.'">'.$label."</label>
		<textarea name=".'"'.$name.'" id="id_'.$name.'">'.$value.'</textarea>');
	return $html; 
}

function input_($name,  $defval="", $type="text", $size=45, $maxlength=0, $style=""){
	$html='<input'; 
	if ($style)$html.=' class="'.$style.'"'; 
	$html .=' name="'.$name.'" type="'.$type.'" value="'.htmlspecialchars($defval).'"'; 
	$html .= ' id="'.$name.'" size="'.$size.'"'; 
	if ($maxlength!=0) $html.=' maxlength="'.$maxlength.'"'; 
	$html.="/>\n"; 
	return $html; 
}
function textarea_($name, $content="", $cols=60, $rows=4, $style="" ) {
	$html='<textarea name="'.$name.'" cols="'.$cols.'" rows="'.$rows.'"';  
	if ($style!="") $html.= ' class="'.$style.'"';
	$html.='>'.$content."</textarea>\n"; 
	return $html; 
}


// URL routing into array
function routeURL ($webspace_name=null) {
	if (isset($_SERVER['ORIG_PATH_INFO'])) {
		$request_uri = substr($_SERVER['ORIG_PATH_INFO'], 1);
	} else {
		$request_uri = substr($_SERVER['PATH_INFO'], 1);
	}
	
	$request_arr = explode('/', $request_uri);
	
	foreach ($request_arr as $key => $i):
		if (empty($i)) {
			unset($request_arr[$key]);
		}
	endforeach;

	return $request_arr;
}



// used in register and account to format the DOB
function formatDate($y, $m, $d) {
	if (empty($y)) {
		$GLOBALS['script_error_log'][] = _("Please provide us with a valid year.");
		return 0;
	}
	
	if (empty($m)) {
		$GLOBALS['script_error_log'][] = _("Please provide us with a valid month.");
		return 0;
	}
	
	if (empty($d)) {
		$GLOBALS['script_error_log'][] = _("Please provide us with a valid day.");
		return 0;
	}
	
	return date('Y-m-d', mktime(0, 0, 0, $m, $d, $y));
}


function gen_maptcha() {
	$numbers = array();
	$numbers['ascii'] = array(0,1,2,3,4,5,6,7,8,9,10);
	$numbers['words'] = array('zero','one','two','three','four','five','six','seven','eight','nine','ten');

	$operators = array();
	$operators['ascii'] = array('+','-','*');
	$operators['words'] = array('plus','minus','times');
	
	$_SESSION['maptcha'] = "";
	
	$m = 'ascii';
	if (rand(0,1)) {
		$m = 'words';
	}
	$n1 = rand(0, count($numbers[$m])-1);
	
	$x = $numbers[$m][$n1];
	
	$m = 'ascii';
	if (rand(0,1)) {
		$m = 'words';
	}
	$n2 = rand(0, count($numbers[$m])-1);
	
	$y = $numbers[$m][$n2];
	
	$m = 'ascii';
	if (rand(0,1)) {
		$m = 'words';
	}
	$n3 = rand(0, count($operators[$m])-1);
	
	$o = $operators[$m][$n3];
	eval('$_SESSION[\'maptcha\']=' . intval($numbers['ascii'][$n1]) . $operators['ascii'][$n3] . intval($numbers['ascii'][$n2]) . ';');
	
	if (rand(0,1)) {
		return 'Calculate this: ' . $x . ' ' . $o . ' ' .$y;
	}
	elseif (rand(0,1)) {
		return 'Solve this equation: ' . $x . ' ' . $o . ' ' .$y;
	}
	elseif (rand(0,1)) {
		return 'Work this out: ' . $x . ' ' . $o . ' ' .$y . " ?";
	}
	else {
		return 'Solve this little puzzle: ' . $x . ' ' . $o . ' ' .$y;
	}
}

function match_maptcha($answer) {
	return intval($answer) == intval($_SESSION['maptcha']);
}


// scan a directory for directory names
function barnraiser_scandir($dir) {

	$dirnames = array();
	
	$entries = @scandir($dir);
	
	if (!empty($entries)) {
		foreach($entries as $i):
			if ($i != '.' && $i != '..' && $i != 'CVS' && $i != '.DS_Store') {
				array_push($dirnames, $i);
			}
		endforeach;
	}

	return $dirnames;
}


// Makes the webspace header title image
function makeThemeHeader($path, $user_id, $theme, $title) {
	// we get require vars needed for this function
	if (is_readable('theme/' . $theme . '/theme_header.inc.php')) {
		require('theme/' . $theme . '/theme_header.inc.php');
	}
	else {
		return false;
	}

	$color = Hex2RGB($color);
	$background_color = Hex2RGB($background_color);

	$font = 'theme/' . $theme . '/font/' . $font;
	
	$img_width = 760;
	$img_height = 40 + $font_size;
	$img_base = 20 + $font_size;
	
	
	if (isset($title, $font, $color, $background_color)) {
		$im = imagecreatetruecolor($img_width, $img_height);
		
		$t_color = imagecolorallocate($im, $color[0], $color[1], $color[2]);
		$b_color = imagecolorallocate($im, $background_color[0], $background_color[1], $background_color[2]);
		imagefilledrectangle($im, 0, 0, $img_width, $img_height, $b_color);
		imagettftext($im, $font_size, 0, 10, $img_base, $t_color, $font, $title);


		// OK TO HERE
		$dir = $path . '/titles/';
		$fname = $user_id . '.png';
		
		@unlink($dir . '/' . $fname);
		
		
		 @imagepng($im, $dir . '/' . $fname);
	}
}


// Used by makeThemeHeader
function Hex2RGB($color){
	$color = str_replace('#', '', $color);
	if (strlen($color) != 6){ return array(0,0,0); }
		$rgb = array();
		for ($x=0;$x<3;$x++){
			$rgb[$x] = hexdec(substr($color,(2*$x),2));
	}
	return $rgb;
}


// used in register and account
function checkEmail($email) {
  $result = TRUE;
  if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email)) {
    $result = FALSE;
  }
  return $result;
}


// used in register and in installer
function formatIdentityName($id) {
	$id = strtolower($id);

	if (!preg_match('/^[a-zA-Z0-9.~]+$/', $id)) {
		$GLOBALS['script_error_log'][] = _("You have used characters in your OpenID name that are not allowed. Only a-z, A-Z and 0-9 are permissable.");
	}

	if (strlen($id) < 2) {
        	$GLOBALS['script_error_log'][] = _("Your OpenID name needs to be at least 2 characters long.");;
	}

	if (strlen($id) > 50) { // link too long
		$GLOBALS['script_error_log'][] = _("Your OpenID is too long. The name should be under characters long.");
	}
	return $id;
}

?>