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


class Database {

	// the constructor
	// Tom Calthrop, 26th March 2007
	//
	function Database($db_core_config) {
		$this->db_config = $db_core_config;
		$this->prefix = $db_core_config['prefix'];
	} //EO Database


	function newConnection() {
		//we connect to the database
		$this->connection = @mysql_connect($this->db_config['host'], $this->db_config['user'] , $this->db_config['pass']);

		if (!is_resource($this->connection)) {
			$error_message = _("There was a database error of type {1}.");
			$error_message = str_replace("{1}", mysql_error(), $error_message);
			$GLOBALS['script_error_log'][] = $error_message;
		}
		else {
			//we select the database
			$db_selected = mysql_select_db($this->db_config['db'], $this->connection);
			if (!$db_selected) {
				$error_message = _("There was a database select error of type {1}.");
				$error_message = str_replace("{1}", mysql_error(), $error_message);
				$GLOBALS['script_error_log'][] = $error_message;
			}
			else {
				$db->prefix = $this->db_config['prefix'];

				// set up database collation
				$query = "SET NAMES 'utf8'";
				$this->Execute($query);
				$query = "SET CHARACTER SET 'utf8'";
				$this->Execute($query);
			}
		}
	}

	function Execute($query, $rows=null, $offset=null) {
		
		$query = trim($query);

		if (!isset($this->connection)) {
			$this->newConnection();
		}
		
		if (isset($rows) && is_int($rows) && $rows > 0) { // is_numeric
			
			if (isset($offset) && is_int($offset) && $offset > 0) { // is_numeric
				$query .= " LIMIT " . $offset . ", " . $rows;
			}
			else {
				$query .= " LIMIT " . $rows;
			}
		}
		
		$this->resource = mysql_query($query, $this->connection);
		
		if (!$this->resource) {
			$error_message = _("There was a database query error of type {1} on query {2}.");
			$error_message = str_replace("{1}", mysql_error(), $error_message);
			$error_message = str_replace("{2}", $query, $error_message);
			$GLOBALS['script_error_log'][] = $error_message;
		}
		else {

			if (is_resource($this->resource)) { // SELECT, SHOW, DESCRIBE or EXPLAIN
				
				if (mysql_num_rows($this->resource) > 0) {
					$result = array();
					while($row = mysql_fetch_array($this->resource)) {
						$result[] = $row;
					}
					//mysql_free_result($resource);
					return $result;
				}
				else {
					return array(); // empty result
				}
			}
			return 1; // It's ok if we reach here!
		}
		return 0; // Not OK
	}
	
	// if magic quotes disabled, use stripslashes()
	function qstr($s) {
		
		if (!get_magic_quotes_gpc()) {
 			$s =  mysql_real_escape_string($s);
		}
		return "'" . $s . "'";
	}

	function insertID() {
		if (isset($this->connection)) {
			if (is_resource($this->connection)) {
				return mysql_insert_id ($this->connection);
			}
		}
		return 0;
	}

	function insertDb($data, $table) {
	
		$query = "
			DESCRIBE " . $table
		;
		
		$result = $this->Execute($query);
		
		$query = "INSERT INTO " . $table . "(";
		
		foreach($data as $key => $d):
			$query .= $key . ", ";
		endforeach;
		
		$query = substr($query, 0, strlen($query) - 2);
		$query .= ") VALUES (";
		
		foreach($data as $key => $d):
			
			$data_type = "";
			for ($i = 0; $i < count($result); $i++) {
				if ($key == $result[$i]['Field']) {
					$data_type = $result[$i]['Type'];
				}
			}
			
			if ($data_type == 'datetime') {
				$query .= $this->qstr(date('Y-m-d H:i:s', $d)) . ", ";
			}
			elseif (is_string($d)) {
				$query .= $this->qstr($d, get_magic_quotes_gpc()) . ", ";
			}
			else {
				$query .= $d . ", ";
			}
		endforeach;
		
		$query = substr($query, 0, strlen($query) - 2);
		$query .= ")";

		$insert = $this->Execute($query);

		return $insert;
	}

	function dbTime () {
		$dbtime = date("Y-m-d H:i:s");
		return $this->qstr($dbtime);
	}
	
	function writeSession($data) {
	
		$rec = array();
		
		if (isset($data['assoc_handle'])) {
			$rec['assoc_handle'] = $data['assoc_handle'];
		}
		
		if (isset($data['assoc_type'])) {
			$rec['assoc_type'] = $data['assoc_type'];
		}
		
		if (isset($data['enc_mac_key'])) {
			$rec['enc_mac_key'] = $data['enc_mac_key'];
		}
		
		if (isset($data['expires_in'])) {
			$rec['expires_in'] = $data['expires_in'];
		}
		
		if (isset($data['session_type'])) {
			$rec['session_type'] = $data['session_type'];
		}
		
		if (isset($data['mac_key'])) {
			$rec['mac_key'] = $data['mac_key'];
		}
		
		$table = $this->prefix . '_session';
		$this->insertDb($rec, $table);
	}
}

class StructureUpdate {
	
	var $dbx, $dbtable, $dbfields; 
	
	function __construct($db, $table) {
		$this->dbx = $db; 
		$this->dbtable = $table; 
	}
	
	function addField ($fieldname, $ftype) {
		$this->dbfields[$fieldname] = $ftype; 
	}
	
	function syncTableDef () {
		$missingfields = Array (); 
		$TableFields = Array (); 
		$dropfields = Array ();
		
		$sql = "SHOW FIELDS FROM `$this->dbtable`"; 
		$schema_fields = $this->dbx->Execute($sql); 
		
		reset ($schema_fields); 
		foreach ($schema_fields as $column) {
			
			$fname=$column['Field']; 
			// print_r("Felt: ".$fname."\n"); 
			if (!isset($this->dbfields[$fname])) {
				$dropfields[$fname]=$this->dbfields[$fname]; 
			} 
			$TableFields[$fname]=$column['Field'];
		}
		
		reset ($this->dbfields); 
		foreach ($this->dbfields as $key => $value) {	
			if (!isset($TableFields[$key])) {
				$missingfields[$key]=$value; 
			}
		}
		//print_r($missingfields); 
		$pref=""; 
		if (!empty($missingfields)) {
			$sql = "ALTER TABLE `".$this->dbtable."` "; 
			reset ($missingfields); 
			foreach ($missingfields as $key => $value) {
				$sql.= $pref."ADD COLUMN `".$key."` ".$value; 
				if (!$pref) $pref=", "; 
			}
			$sql.=";";
			$StatusMessage = "\n".$sql;
			$StatusMessage .=  "<br>Status: " . print_r($this->dbx->Execute($sql), true);
		}
	}
}



?>