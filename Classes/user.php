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

require_once 'DBManager.php';

class user {
	public $uid;
	public $username;
	public $email;
	private $DB;
	public function __construct()
	{
		$this->uid = -1;
		$this->username = null;
		$this->email = null;
		$this->DB = new DBManager();
	}
	public function load($uid)
	{
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
		return ret;
	}
	public function loadusername($username)
	{
		$this->DB->connect();
		$query = "SELECT uid FROM users WHERE username = $username;";
		$uid = $this->DB->query($query)[0]['uid'];
		$this->DB->disconnect();
		return $this->load($uid);
	}
}

?>