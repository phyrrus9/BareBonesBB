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
 * Description of user
 *
 * @author phyrrus9
 */
if (!class_exists('user')) {
	class user {
		public $uid;
		public $username;
		public $email;
		private $DB;
		public function __construct() {
			$this->uid = -1;
			$this->username = null;
			$this->email = null;
			$this->DB = new DBManager();
		}
		public function load($uid) {
			$ret = array();
			$query = "SELECT uid, username, email FROM users WHERE uid = $uid;";
			$this->DB->connect();
			$data = $this->DB->query($query)[0];
			$this->uid = $data['uid'];
			$this->username = $data['username'];
			$this->email = $data['email'];
			$ret['uid'] = $data['uid'];
			$ret['username'] = $data['username'];
			$ret['email'] = $data['email'];
			$this->DB->disconnect();
			return $ret;
		}
		public function loadusername($username) {
			$this->DB->connect();
			$query = "SELECT uid FROM users WHERE username = $username;";
			$uid = $this->DB->query($query)[0]['uid'];
			$this->DB->disconnect();
			return $this->load($uid);
		}
		public function register($username, $email, $password) {
			$this->DB->connect();
			$this->DB->statement("INSERT INTO users(username, password, email) " .
							 "VALUES('$username', PASSWORD('$password'), '$email');");
			$uid = $this->DB->query("SELECT uid FROM users WHERE username = '$username' " .
							    "AND password = PASSWORD('$password') AND email = '$email';")[0]['uid'];
			$this->DB->disconnect();
			$this->setup_permissions($uid);
		}
		private function setup_permissions($uid) {
			global $UPGRADE_SETTINGS;
			foreach ($UPGRADE_SETTINGS as $key => $value) {
				$k = "p_$key";
				$$k = $value;
			}
			$query = "INSERT INTO userqueue(p_uid,p_post,p_reply,p_lock_own," .
				    "p_unlock_own,p_delete_own,p_warn,p_manage_flags,p_move," .
				    "p_lock,p_delete,p_ban,p_moderator) ".
				    "VALUES($uid,$p_post,$p_reply,$p_lock_own,$p_unlock_own," .
				    "$p_delete_own,$p_warn,$p_manage_flags,$p_move,$p_lock," .
				    "$p_delete,$p_ban,$p_moderator);";
			$query2 = "INSERT INTO override_userqueue(uid) VALUES($uid);";
			$query3 = "INSERT INTO permissions(uid) VALUES($uid);";
			$this->DB->connect();
			$this->DB->statement($query);
			$this->DB->statement($query2);
			$this->DB->statement($query3);
			$this->DB->disconnect();
		}
		public function postCount() {
			$query = "SELECT pid FROM posts WHERE uid = $this->uid;";
			$this->DB->connect();
			$count = count($this->DB->query($query));
			$this->DB->disconnect();
			return $count;
		}
	}
}	
?>