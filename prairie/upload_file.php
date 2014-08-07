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


include_once ("config/core.config.php");


// SESSION HANDLER --------------------------------------------------
session_name($core_config['php']['session_name']);
session_start();

if (!isset($_SESSION['user_id'])) {
	header("Location: /");
	exit;
}

if (isset($_POST['submit_delete_avatar'])) {
	$destination = $core_config['file']['dir'] . "avatars/" . (int)$_SESSION['user_id'] . "/";

	foreach(glob($destination . '*') as $i) {
		unlink($i);
	}
}
else {

	if (isset($_FILE) && empty($_FILES['frm_file']['tmp_name'])) {
		$GLOBALS['script_error_log'][] = _("The picture is not set. Please browse and select a picture.");
	}
	elseif (!is_uploaded_file($_FILES['frm_file']['tmp_name'])) {
		$error_message = _("The picture you are trying to upload is too big. Please upload a picture less than {1}. in size");
		$error_message = str_replace ("{1}", ini_get('post_max_size'), $error_message);
		$GLOBALS['script_error_log'][] = $error_message;
	}
	elseif (!isset($_SESSION['user_id'])) {
		$GLOBALS['script_error_log'][] = _("You are not logged in so you cannot upload a picture.");
	}
	
	
	if (empty($GLOBALS['script_error_log'])) {
		
		$allowable_mime_types = array("image/jpeg", "image/png", "image/gif");
		$mime_type_suffixes = array('jpg' =>'image/jpeg', 'png' =>'image/png', 'gif' =>'image/gif');
	
		
		if (isset($_POST['submit_upload_avatar'])) {
			
			$thumbnail_width = array(60, 100, 200);
			$destination = $core_config['file']['dir'] . "avatars/" . (int)$_SESSION['user_id'] . "/";
	
			if (!is_dir($destination)) {
				$oldumask = umask(0);
				if(!mkdir ($destination, 0770, 1)) {
					$GLOBALS['script_error_log'][] = _("We could not create a directory for your avatars. Please report this to our support team.");
				}
				umask($oldumask);
			}
	
			
			// CHECK MIME TYPE ----------------------------------------------------------------
			if (function_exists('finfo_open')) {
				$resource = finfo_open(FILEINFO_MIME);
				$mime_type = finfo_file($resource, $_FILES['frm_file']['tmp_name']);
				finfo_close($resource);
			}
			elseif (function_exists('mime_content_type')) {
				$mime_type = mime_content_type($_FILES['frm_file']['tmp_name']);
			}
			else {
				$mime_type = $_FILES['frm_file']['type'];
			}
	
			// We use this to map IE-mimetype to standard mimetype
			$mime_map = array(array("from" => "image/pjpeg", "to" => "image/jpeg"));
	
			foreach($mime_map as $i):
				if ($i['from'] == $mime_type) {
					$mime_type = $i['to'];
				}
			endforeach;
	
			// Is the mime-type allowed?
			if (!validateMimeType($allowable_mime_types, $mime_type)) {
				$GLOBALS['script_error_log'][] = _("The picture is not a valid type. You can upload JPG, PNG and GIF formatted pictures.");
			}
	
			if (empty($GLOBALS['script_error_log'])) {
				// create file name
				foreach($mime_type_suffixes as $key => $mts) {
					if ($mts == $mime_type) {
						$suffix = $key;
					}
				}
				
				// We create thumbnails
				if ($mime_type == "image/gif" || $mime_type == "image/jpeg" || $mime_type == "image/png") {
	
					$image_size = getimagesize($_FILES['frm_file']['tmp_name']);
	
					// we create an avatar
					$type  = explode('/', $mime_type);
					$imagecreatefrom = 'imagecreatefrom' . $type[1];
					$image = 'image' . $type[1];
					$new_image = $imagecreatefrom($_FILES['frm_file']['tmp_name']);
	
					foreach($thumbnail_width as $key => $t):
	
						$filename = $t . "." . $suffix;
	
						$width = $t;
						$height = ($width / $image_size[0]) * $image_size[1];
	
						$blank_image = ImageCreateTrueColor($width, $height);
						$col = imagecolorallocate($blank_image, 255, 255, 255);
						imagefilledrectangle($blank_image, 0, 0, $width, $height, $col);
						$newimage = ImageCopyResampled($blank_image, $new_image, 0, 0, 0, 0, $width, $height, $image_size[0], $image_size[1]);
						$image($blank_image, $destination . $filename);
					endforeach;
				}
			}
		}
	}
}

function validateMimeType($mimes, $mime_type) {
	foreach($mimes as $m) {
		if ($m == $mime_type) {
			return 1;
		}
	}
	return 0;
}

if (!empty($GLOBALS['script_error_log'])) {
	require_once('class/Template.class.php');
	$tpl = new Template(); // outer template
	define("SCRIPT_TEMPLATE_PATH", "template/");

	define("SCRIPT_THEME_PATH", "theme/silver/");
	
	define ('STND_LOCALE', $core_config['script']['server_locale']);

	$tpl->set('content', '');
	$outer_tpl = SCRIPT_TEMPLATE_PATH . 'wrapper.tpl.php';
	echo $tpl->fetch($outer_tpl);
}
else {
	header("Location: /");
	exit;
}
?>