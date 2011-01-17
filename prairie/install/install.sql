-------------------------------------------------------------------------
-- This file is part of Prairie
-- 
-- Copyright (C) 2003-2008 Barnraiser
-- http:--www.barnraiser.org/
-- info@barnraiser.org
-- 
-- This program is free software: you can redistribute it and/or modify
-- it under the terms of the GNU General Public License as published by
-- the Free Software Foundation, either version 3 of the License, or
-- (at your option) any later version.
-- 
-- This program is distributed in the hope that it will be useful,
-- but WITHOUT ANY WARRANTY; without even the implied warranty of
-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
-- GNU General Public License for more details.
-- 
-- You should have received a copy of the GNU General Public License
-- along with this program; see the file COPYING.txt.  If not, see
-- <http:--www.gnu.org/licenses/>
-------------------------------------------------------------------------


-- Table structure for table `prairie_session`
CREATE TABLE IF NOT EXISTS `prairie_session` (
  `assoc_handle` varchar(200) NOT NULL default '',
  `assoc_type` varchar(200) default NULL,
  `enc_mac_key` varchar(200) default NULL,
  `expires_in` varchar(200) default NULL,
  `session_type` varchar(200) default NULL,
  `mac_key` varchar(200) default NULL,
  `create_datetime` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`assoc_handle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Table structure for table `prairie_trust`
CREATE TABLE IF NOT EXISTS `prairie_trust` (
  `trust_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `trust_url` varchar(255) NOT NULL,
  `trust_total` int(11) NOT NULL default '0',
  `trust_last_visit` datetime NOT NULL,
  PRIMARY KEY  (`trust_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


-- Table structure for table `prairie_user`
CREATE TABLE IF NOT EXISTS `prairie_user` (
  `user_id` int(11) NOT NULL auto_increment,
  `openid_name` varchar(100) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_location` varchar(255) NOT NULL,
  `user_dob` date NOT NULL,
  `user_registration_key` varchar(100) default NULL,
  `user_live` int(1) NOT NULL default '0',
  `user_create_datetime` datetime NOT NULL,
  PRIMARY KEY  (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


-- Table structure for table `prairie_webspace`
CREATE TABLE IF NOT EXISTS `prairie_webspace` (
  `user_id` int(11) NOT NULL,
  `webspace_title` varchar(100) default NULL,
  `webspace_html` text NOT NULL,
  `webspace_css` text NOT NULL,
  `webspace_theme` varchar(50) default NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ENDS