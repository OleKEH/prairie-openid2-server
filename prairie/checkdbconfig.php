<?php
// -----------------------------------------------------------------------
// This file is part of Prairie
// checkdbconfig.php
// Copyright (C) 2003-2008 NETT TECH
// http://www.nett-tech.com/
// ole@nett-tech.com
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


// check the users table to see if it has all the fields (upgraded)
// if changes to the other tables, we need to have a similar sequence for those
// tables. To run this manually (after upgrade) - run: http://<yourdomain>/checkdbconfig 
// once to add new fields that needs to be added to the database.  



$userTable = new StructureUpdate ($db, "prairie_user"); 
$userTable->checkCreate("CREATE  TABLE IF NOT EXISTS `prairie_user` (
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
  `user_nick` VARCHAR(45) NULL ,
  `user_gender` VARCHAR(10) NULL ,
  `user_postcode` VARCHAR(45) NULL ,
  `user_country` VARCHAR(45) NULL ,
  `user_language` VARCHAR(45) NULL ,
  `user_timezone` VARCHAR(45) NULL ,
  PRIMARY KEY (`user_id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;"); 

$userTable->addField('user_id', 'INT(11) NOT NULL AUTO_INCREMENT'); 
$userTable->addField('openid_name', 'VARCHAR(100) NOT NULL'); 
$userTable->addField('user_name', 'VARCHAR(255) NOT NULL'); 
$userTable->addField('user_password', 'VARCHAR(255) NOT NULL'); 
$userTable->addField('user_email', 'VARCHAR(255) NOT NULL'); 
$userTable->addField('user_location', 'VARCHAR(255) NOT NULL'); 
$userTable->addField('user_dob', 'DATE NOT NULL'); 
$userTable->addField('user_registration_key', 'VARCHAR(100) NULL DEFAULT NULL'); 
$userTable->addField('user_live', "INT(1) NOT NULL DEFAULT '0'"); 
$userTable->addField('user_create_datetime', 'DATETIME NOT NULL'); 
$userTable->addField('user_nick', 'VARCHAR(45) NULL'); 
$userTable->addField('user_gender', 'VARCHAR(10) NULL'); 
$userTable->addField('user_postcode', 'VARCHAR(45) NULL'); 
$userTable->addField('user_country', 'VARCHAR(45) NULL'); 
$userTable->addField('user_language', 'VARCHAR(45) NULL'); 
$userTable->addField('user_timezone', 'VARCHAR(45) NULL'); 
$userTable->addField('user_bio', 'TEXT NULL'); 
$userTable->addField('user_birthdate', 'DATE NOT NULL');
$userTable->syncTableDef(); 


$articletable = new StructureUpdate($db, "prairie_articles"); 
$articletable->checkCreate("CREATE  TABLE IF NOT EXISTS `prairie_articles` (
  `art_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `user_id` INT(11) NOT NULL ,
  `art_Title` VARCHAR(100) NULL ,
  `art_html` TEXT NOT NULL ,
  `art_menutext` VARCHAR(45) NOT NULL ,
  `art_order` INT NULL ,
  PRIMARY KEY (`art_id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;"); 
$articletable->addField('art_id', 'INT(11) NOT NULL AUTO_INCREMENT'); 
$articletable->addField('user_id', 'INT(11) NOT NULL'); 
$articletable->addField('art_Title', 'VARCHAR(100) NULL'); 
$articletable->addField('art_html', 'TEXT NOT NULL'); 
$articletable->addField('art_menutext', 'VARCHAR(45) NOT NULL'); 
$articletable->addField('art_order', 'INT NULL'); 
$articletable->syncTableDef();



?>