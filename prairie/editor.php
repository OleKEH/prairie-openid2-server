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

if (!isset($_SESSION['user_id'])) {
	header("Location: /");
	exit;
}

if (isset($_POST['save_profile'])) {
	$title = trim($_POST['webspace_title']);
	
	if (in_array($_POST['theme_name'], barnraiser_scandir('theme/')) && is_file('theme/' . $_POST['theme_name'] . '/thumb.png')) {
		$theme_name = $_POST['theme_name'];
	}
	else {
		$theme_name = $core_config['script']['default_theme_name'];
	}

	$query = "
		SELECT user_id
		FROM " . $db->prefix . "_webspace
		WHERE user_id=" . (int)$_SESSION['user_id']
	;
	
	$result = $db->Execute($query);
	
	if (empty($result)) {
		$rec = array();
		
		$rec['user_id'] = $_SESSION['user_id'];
		$rec['webspace_title'] = $title;
		$rec['webspace_theme'] = $theme_name;
		
		$table = $db->prefix . '_webspace';
		
		$db->insertDB($rec, $table);
	}
	else {
		$query = "
			UPDATE " . $db->prefix . "_webspace
			SET 
			webspace_title=" . $db->qstr($title) . ",
			webspace_theme=" . $db->qstr($theme_name) . "
			WHERE 
			user_id=" . (int)$_SESSION['user_id']
		;
	
		$db->Execute($query);
	}

	if (!empty($title)) {
		makeThemeHeader($core_config['file']['dir'], (int)$_SESSION['user_id'], $theme_name, $title);
	}
	else {
		unlink($core_config['file']['dir'] . "/titles/" . (int)$_SESSION['user_id'] . ".png");
	}

	header('location: /editor');
	exit;
}
elseif (isset($_POST['save_markup'])) {
	$html = trim($_POST['html']);
	$html = parseHTML($html, $core_config['security']['allowable_html_tags']);

	//	$css = trim($_POST['css']);
//	$css = parseCSS($css);
	
	$query = "
		SELECT user_id
		FROM " . $db->prefix . "_webspace
		WHERE user_id=" . (int)$_SESSION['user_id']
	;
	
	$result = $db->Execute($query);
	
	if (empty($result)) {
		$rec = array();
		
		$rec['user_id'] = $_SESSION['user_id'];
		$rec['webspace_html'] = $html;
//		$rec['webspace_css'] = $css;
		
		$table = $db->prefix . '_webspace';
		
		$db->insertDB($rec, $table);
	}
	else {
		$query = "
			UPDATE " . $db->prefix . "_webspace
			SET 
			webspace_html=" . $db->qstr($html) . " 
			WHERE 
			user_id=" . (int)$_SESSION['user_id']
		;
		$db->Execute($query);
	}

	header('location: /editor');
	exit;
}

$query = "
	SELECT *
	FROM " . $db->prefix . "_webspace
	WHERE user_id=" . (int)$_SESSION['user_id']
;

$result = $db->Execute($query);

if (!empty($result)) {
	$body->set('webspace', $result[0]);
	
	if (!empty($result[0]['webspace_title'])) {
		$tpl->set('webspace_title', $result[0]['webspace_title']);
	}
	else {
		unset($tpl->vars['webspace_title']);
	}
}

// GET THEMES ------------------------------------
$themes = barnraiser_scandir('theme/');

if (!empty($themes)) {
	$body->set('themes', $themes);
}

$body->set('default_theme', $core_config['script']['default_theme_name']);


// Parse HTML 
function parseHTML($str, $allowable_html_tags) {

	$str = strip_tags($str, $allowable_html_tags);

	$str = link_parse($str); // we need to linkparse after all other links have been parsed
	
	return $str;
}

function link_parse($str) {
	
	$pattern = '#(^|[^"\'=\]>]{1})(http|HTTP|ftp)(s|S)?://([^\s<>\.]+)\.([^\s<>]+)#sm';
	
	if (preg_match_all($pattern, $str, $matches, PREG_PATTERN_ORDER)) { 
		
		foreach ($matches[0] as $key => $val) {

			$val_trimmed = trim($val);

			if (strlen($val_trimmed) > 30) {
				$title = substr($val_trimmed, 0, 30) . '...';
			}
			else {
				$title = $val_trimmed;
			}

			$link = '<a href="' . $val_trimmed . '">' . $title . '</a>';
			$count = 1;
			$str = str_replace ($val, $link, $str, $count);
		}
	}

	return $str;
}


// Parse CSS 
function parseCSS($str) {

	$str = strip_tags($str);
	
	return $str;
}

?>