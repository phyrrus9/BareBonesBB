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
 * Description of permissionManager
 *
 * @author phyrrus9
 */

if (!class_exists('DBManager')) {
	include 'DBManager.php';
}

if (!class_exists('permissionManager')) {

	class permissionManager {
		private $DB;
		private $data = null;
		private $uid = -1;
		public function reload()
		{
			global $SM;
			$this->data = null;
			if ($this->uid == -1)
			{
				if ($SM->poke() == false) {
					die("bad uid!");
					return false;
				}
				$this->uid = $SM->uid;
			}
			$query = "SELECT can_view, can_post, can_reply, can_lock, can_unlock, " .
					"can_lock_own, can_unlock_own, can_move, can_delete, can_delete_own, " .
					"can_manage_flags, can_warn, can_ban, can_create_user, can_delete_user, " .
					"can_create_forum, can_delete_forum, is_moderator, is_admin, is_super " .
					"FROM permissions WHERE uid = $this->uid;";
			//die($query);
			$this->DB = new DBManager();
			$this->DB->connect();
			$res = $this->DB->query($query);
			$this->data = $res[0];
			$this->DB->disconnect();
			return true;
		}
		public function can($perm) {
			return $this->get($perm);
		}
		public function get($perm)
		{
			if ($this->data == null) {
				$this->reload();
			}
			$key = "can_" . $perm;
			return $this->data[$key] == 1;
		}
		public function is($perm)
		{
			if ($this->data == null) {
				$this->reload();
			}
			$key = "is_" . $perm;
			return $this->data[$key] == 1;
		}
		public function set($perm, $uid, $value)
		{
			if ($this->data == null) {
				$this->reload();
			}
			$this->DB = new DBManager();
			$this->DB->connect();
			$val = 0;
			if ($value) {
				$val = 1;
			}
			if ($this->is("admin") || $this->is("super")) {
				$this->DB->statement("UPDATE permissions SET can_$perm = $val WHERE uid = $uid;");
			}
			$this->DB->disconnect();
		}
		public function set_is($perm, $uid, $value)
		{
			if ($this->data == null) {
				$this->reload();
			}
			$this->DB = new DBManager();
			$this->DB->connect();
			$val = 0;
			$perform = false;
			if ($value) {
				$val = 1;
			}
			$query = "UPDATE permissions SET is_$perm = $val WHERE uid = $uid;";
			if ($this->is("admin") || $this->is("super")) {
				$perform = true;
				if ($this->is("super") == false && strcmp($perm, "moderator")) {
					$perform = false;
				}
			}
			if ($perform == true) {
				$this->DB->statement($query);
			}
			$this->DB->disconnect();
		}
		public function get_uid($perm, $uid)
		{
			if ($this->data == null) {
				$this->reload();
			}
			if ($this->is("admin") == false) {
				return null;
			}
			$key = "can_$perm";
			$query = "SELECT $key " .
					"FROM permissions WHERE uid = $uid;";
			$this->DB = new DBManager();
			$this->DB->connect();
			$data = $this->DB->query($query)[0];
			$this->DB->disconnect();
			return $data[$key] == 1;
		}
		public function is_uid($perm, $uid)
		{
			if ($this->data == null) {
				$this->reload();
			}
			if ($this->is("admin") == false) {
				return null;
			}
			$key = "is_$perm";
			$query = "SELECT $key " .
					"FROM permissions WHERE uid = $uid;";
			$this->DB = new DBManager();
			$this->DB->connect();
			$data = $this->DB->query($query)[0];
			$this->DB->disconnect();
			return $data[$key] == 1;
		}
		public function avail_can()
		{
			$ret = array();
			$query = "SELECT * FROM permissions WHERE uid = 0;";
			$this->DB = new DBManager();
			$this->DB->connect();
			$data = $this->DB->query($query)[0];
			foreach ($data as $key => $value) {
				if (strstr($key, "can_") != null) {
					array_push($ret, $key);
				}
			}
			$this->DB->disconnect();
			return $ret;
		}
		public function avail_is()
		{
			$ret = array();
			$query = "SELECT * FROM permissions WHERE uid = 0;";
			$this->DB = new DBManager();
			$this->DB->connect();
			$data = $this->DB->query($query)[0];
			foreach ($data as $key => $value) {
				if (strstr($key, "is_") != null) {
					array_push($ret, $key);
				}
			}
			$this->DB->disconnect();
			return $ret;
		}
	}
	$PM = new permissionManager();
}

?>