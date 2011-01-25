-------------------------------------------------------------------------
-- This file is part of Prairie
-- 
-- Copyright (C) 2003-2008 Barnraiser
-- http:--www.barnraiser.org/
-- info@barnraiser.org
-- changes to extend the model done by Ole Kristian Ek Hornnes, www.nett-tech.com
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


-- -----------------------------------------------------
-- Table `prairie_session`
-- -----------------------------------------------------
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


-- -----------------------------------------------------
-- Table `prairie_trust`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `prairie_trust` (
  `trust_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `trust_url` varchar(255) NOT NULL,
  `trust_total` int(11) NOT NULL default '0',
  `trust_last_visit` datetime NOT NULL,
  PRIMARY KEY  (`trust_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


-- -----------------------------------------------------
-- Table `prairie_user`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `prairie_user` (
  `user_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `openid_name` VARCHAR(100) NOT NULL ,
  `user_name` VARCHAR(255) NOT NULL ,
  `user_password` VARCHAR(255) NOT NULL ,
  `user_email` VARCHAR(255) NOT NULL ,
  `user_location` VARCHAR(255) NOT NULL ,
  `user_dob` DATE NOT NULL ,
  `user_registration_key` VARCHAR(100) NULL DEFAULT NULL ,
  `user_live` INT(1) NOT NULL DEFAULT '0' ,
  `user_create_datetime` DATETIME NOT NULL ,
  `user_nick` VARCHAR(45) NULL DEFAULT NULL ,
  `user_gender` VARCHAR(10) NULL DEFAULT NULL ,
  `user_postcode` VARCHAR(45) NULL DEFAULT NULL ,
  `user_country` VARCHAR(45) NULL DEFAULT NULL ,
  `user_language` VARCHAR(45) NULL DEFAULT NULL ,
  `user_timezone` VARCHAR(45) NULL DEFAULT NULL ,
  `user_bio` TEXT NULL DEFAULT NULL ,
  `user_birthdate` DATE NOT NULL ,
  PRIMARY KEY (`user_id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;



-- -----------------------------------------------------
-- Table `prairie_webspace`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `prairie_webspace` (
  `user_id` int(11) NOT NULL,
  `webspace_title` varchar(100) default NULL,
  `webspace_html` text NOT NULL,
  `webspace_css` text NOT NULL,
  `webspace_theme` varchar(50) default NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -----------------------------------------------------
-- Table `prairie_articles`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `prairie_articles` (
  `art_id` INT NOT NULL AUTO_INCREMENT ,
  `user_id` INT NOT NULL ,
  `art_Title` VARCHAR(100) NULL ,
  `art_html` TEXT NOT NULL ,
  `art_menutext` VARCHAR(45) NOT NULL ,
  `art_order` INT NULL ,
  PRIMARY KEY (`art_id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- ENDS