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



// PHP CONFIGURATION -----------------------------------------------------
// PHP keeps data in a session. The session is called "PHPSESSID" as standard. If you
// have more than one instance of this software you should create a unique session name.
// recomended is characters A-Z (uppercase),0-9 with no spaces. DO NOT use a dot (.).
$core_config['php']['session_name'] = "PHPSESSIDPRAIRIE";

error_reporting(E_ALL); // error handling in development environment.
//error_reporting(0);	// error handling in production environment


// RELEASE NOTES ---------------------------------------------------------
$core_config['release']['version'] = 					"Alpha 0.2";
$core_config['release']['release_date'] = 				"09-22-2008"; // MM-DD-YYYY
$core_config['release']['install_date'] = 				"";


//DATABASE CONFIGURATION -------------------------------------------------
$core_config['db']['host'] = "localhost";
$core_config['db']['user'] = "";
$core_config['db']['pass'] = "";
$core_config['db']['db'] = "barnraiser_prairie";
$core_config['db']['prefix'] =	 			"prairie";
$core_config['db']['collate'] =	 			""; // utf8_swedish_ci



// URI, DOMAIN and SUB-DOMAIN CONFIGURATION -----------------------------
$core_config['script']['core_domain'] = "http://prairie.localhost";

// comment this out if you want a multi-user version of the openidserver
$core_config['script']['single_webspace'] = "";
// Include slash at start and end of string. Please a back slash in front of any dot. is (.*?) for subdomain
$core_config['script']['multiple_webspace_pattern'] = "/(.*?)\.prairie.localhost/";



// REGISTRATION RESTRICTION CONFIGURATION -------------------------------
// Valid email domains (leave empty if not for specific email domains. Use commas to separate multiple domains
$core_config['registration']['email_domains'] = 		"";

$core_config['registration']['unauthorized_openid_names'] = 	"www, ftp, http, https, barnraiser, prairie";
// allow registration - 1 = yes, 0 = no - Only valid when Prairie setup for multiple accounts
$core_config['registration']['allow_registration'] = "1";


// SECURITY CONFIGURATION -----------------------------------------------
// 1 = with email, 0 = with subdomain - 1 is more secure
$core_config['security']['login_with_email'] = 		1;
// Either "SHA256" or blank which defaults to SHA1
$core_config['security']['openid_encryption_level'] = 	"";
$core_config['security']['allowable_html_tags'] = 	"<object><param><embed><a><img><h1><h2><h3><h4><h5><h6><hr><code><p><br /><span>";
// we can access the maintainer.php page? Enter the users openid_name
$core_config['security']['maintainer_openids'] = 	"";





// THEME CONFIGURATION --------------------------------------------------
$core_config['script']['default_theme_name'] = 		"silver";


// FILE CONFIGURATION ----------------------------------------------------
$core_config['file']['dir'] =				"asset/";


// LANGUAGE CONFIGURATION --------------------------------------------------
// A locale name usually has the form 'll_CC'. Here 'll' is an ISO 639 two-letter language code, and 'CC' is an ISO 3166 two-letter country code
// Check that these correspond to installed operating system language packs (Linux = locale -a for list)
// If non are included the default language will display as English.
$core_config['language']['server_locale'] = 		""; // sv_SE.utf8
$core_config['language']['standard_locale'] = 		""; // optional if not the same as the server_locale... used in the HTML


// EMAIL CONFIGURATION ---------------------------------------
$core_config['mail']['host'] = "";
$core_config['mail']['port'] = 				25;
$core_config['mail']['email_address'] = "";
$core_config['mail']['from_name'] = 			"";
$core_config['mail']['mailer'] = 			"smtp";
$core_config['mail']['wordwrap'] = 			80;


// END OF CONFIG FILE ----------------------------------------------------

?>