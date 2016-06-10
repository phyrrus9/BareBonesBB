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
 * Description of sessionManager
 *
 * @author phyrrus9
 */
if (!class_exists('DBManager')) {
	include 'Classes/DBManager.php';
}
if (!class_exists('sessionManager')) {
	class sessionManager {
		public $uid = -1;
		public $username = "nil";
		public $expires = 0;
		private function session()
		{
			if (session_status() != PHP_SESSION_ACTIVE) {
				session_start();
			}
		}
		public function login($in_username, $in_password)
		{
			$this->session();
			$DB = new DBManager();
			if (!$DB->connect()) {
				die("OOPS!");
			}
			$result = $DB->query("SELECT uid FROM users WHERE username = '" . $in_username . 
							 "' AND password = PASSWORD('" . $in_password . "');");
			if (count($result) > 1) {
				die("INVALID LOGIN");
			}
			$data = $result[0];
			$_SESSION['uid'] = $data['uid'];
			$_SESSION['username'] = $in_username;
			return $this->poke(true);
		}
		public function logout()
		{
			$this->session();
			$_SESSION['uid'] = -1;
			$_SESSION['username'] = "nil";
			$_SESSION['sessionExpires'] = 0;
			echo("logged out");
		}
		public function poke($ignoreTimeout = false)
		{
			$this->session();
			$this->load();
			$curtime = time();
			if ($curtime >= $this->expires && !$ignoreTimeout){
				$this->logout();
				return false;
			} else {
				$this->expires = $curtime + (7 * 24 * 60 * 60);
				$_SESSION['sessionExpires'] = $this->expires;
			}
			if ($this->uid < 0) {
				$this->logout();
				return false;
			} else if (!strcmp($this->username, "nil")) {
				$this->logout();
				return false;
			} else {
				return true;
			}
		}
		public function load()
		{
			$this->session();
			$this->uid = $_SESSION['uid'];
			$this->username = $_SESSION['username'];
			$this->expires = $_SESSION['sessionExpires'];
		}
	}
	$SM = new sessionManager();
}
?>
