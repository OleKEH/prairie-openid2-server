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


include "config/core.config.php";


$allowable_mime_types['jpg'] = 				"image/jpeg";
$allowable_mime_types['png'] = 				"image/png";
$allowable_mime_types['gif'] = 				"image/gif";

if (!empty($_REQUEST['reloadsession'])) {
	session_name($core_config['php']['session_name']);
	session_start();
	// the wrapper.tpl.php refreshes the session before timeout...
	$_SESSION['last_session_access_time'] = time();
	session_write_close();

	header("Content-type: image/png");
	readfile($core_config['file']['dir'] . 'session-reload-image.png');
	
}
elseif (isset($_REQUEST['avatar'])) { //?avatar=file = avatar

	if (!isset($_REQUEST['width'])) {
		$_REQUEST['width'] = 100;
	}

	$av = glob($core_config['file']['dir'] . 'avatars/' . (int)$_REQUEST['avatar'] . '/' . (int)$_REQUEST['width'] . '*');

	if (isset($av[0])) {
		$file = $av[0];
	}
	else {
		$file = 'template/silver/img/no_avatar_' . (int)$_REQUEST['width'] . '.png';
	}
}
elseif (isset($_REQUEST['title'])) { // ?title=file = webpage title image
	$file = $core_config['file']['dir'] . 'titles/' . (int)$_REQUEST['title'] . '.png';

	if (!is_file($file)) {
		$file = $core_config['file']['dir'] . 'titles/0.png';
	}
}



if (isset($file)) {
	if (is_file($file)) {
		$suffix = strtolower(substr($file, -3));
	}
	
	if (isset($suffix) && array_key_exists($suffix, $allowable_mime_types)) {
	
		header("Content-type: ".$allowable_mime_types[$suffix]);
		
		readfile($file);
	}
	else {
		header("Content-type: image/png");
		
		readfile('template/' . $core_config['am']['template_name'] . '/img/no_avatar_60.png');
	}
}

?>