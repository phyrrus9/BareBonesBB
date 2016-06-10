<?php

/*
  DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
  Version 2, December 2004

  Copyright (C) 2004 Sam Hocevar <sam@hocevar.net>

  Everyone is permitted to copy and distribute verbatim or modified
  copies of this license document, and changing it is allowed as long
  as the name is changed.

  DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
  TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION

  0. You just DO WHAT THE FUCK YOU WANT TO.
 */

/**
 * Description of DBManager
 *
 * @author phyrrus9
 */

require_once 'config.php';

if (!class_exists('DBManager')) {
	class DBManager {
		private $server;
		private $port;
		private $username;
		private $password;
		private $database;
		private $db;
		public function __construct()
		{
			global $DB_SETTINGS;
			$this->server = $DB_SETTINGS['server'];
			$this->port = $DB_SETTINGS['port'];
			$this->username = $DB_SETTINGS['username'];
			$this->password = $DB_SETTINGS['password'];
			$this->database = $DB_SETTINGS['database'];
		}
		public function connect()
		{
			$this->db = mysqli_connect($this->server, $this->username, $this->password, $this->database, $this->port);
			return $this->db != null;
		}
		public function disconnect()
		{
			if ($this->db != null)
			{
				mysqli_close($this->db);
			}
			$this->db = null;
		}
		public function statement($query)
		{
			return mysqli_query($this->db, $query);
		}
		public function query($query)
		{
			$ret = array();
			$res = mysqli_query($this->db, $query);
			if (is_bool($res)) {
				return $res;
			}
			//$ret = mysqli_fetch_all($res);
			$cols = mysqli_fetch_fields($res);
			while (($row = mysqli_fetch_row($res)) != null)
			{
				$tmp = array();
				for ($i = 0; $i < count($row); $i++)
				{
					$colName = $cols[$i]->name;
					$tmp[$colName] = $row[$i];
				}
				array_push($ret, $tmp);
			}
			return $ret;
		}
	}
	$DB = new DBManager();
}
?>
