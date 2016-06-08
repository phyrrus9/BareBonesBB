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
 * Description of post
 *
 * @author phyrrus9
 */

require_once 'DBManager.php';
require_once 'user.php';
require_once 'forum.php';
require_once 'sessionManager.php';
require_once 'permissionManager.php';

class post {
	public $pid;
	public $forum;
	public $user;
	public $when;
	public $parent;
	public $title;
	public $message;
	public $replies;
	public $locked;
	private $DB;
	private $SM;
	private $PM;
	public function __construct()
	{
		$this->pid = -1;
		$this->forum = null;
		$this->user = null;
		$this->when = 0;
		$this->parent = null;
		$this->title = null;
		$this->message = null;
		$this->replies = null;
		$this->locked = true;
		$this->DB = new DBManager();
		$this->SM = new sessionManager();
		$this->PM = new permissionManager();
	}
	public function load($pid)
	{
		if ($this->SM->poke() && $this->PM->can("view")) {
			$query = "SELECT pid, fid, uid, whendt, parentpid, msgtitle, message, locked " .
				    "FROM posts WHERE pid = $pid";
			$this->DB->connect();
			$data = $this->DB->query($query)[0];
			$this->pid = $data['pid'];
			$this->forum = new forum();
			$this->forum->load($data['fid'], false);
			$this->user = new user();
			$this->user->load($data['uid']);
			$this->when = $data['whendt'];
			if (($this->parent = $data['parentpid']) != null) {
				$this->parent = new post();
				$this->parent->load($data['parentpid']);
			}
			$this->title = $data['msgtitle'];
			$this->message = $data['message'];
			$this->locked = $data['locked'] == 1;
			$this->DB->disconnect();
			$this->replies = $this->loadReplies($this->pid);
		}
	}
	public function loadReplies($pid)
	{
		$ret = null;
		if ($this->SM->poke() == false || $this->PM->can("view") == false) {
			return null;
		} else if ($pid < 0) {
			return null;
		}
		$query = "SELECT pid FROM posts WHERE parentpid = $pid ORDER BY whendt DESC;";
		$this->DB->connect();
		$data = $this->DB->query($query);
		if (count($data) > 0) {
			$ret = array();
			foreach ($data as $replypid) {
				$tmp = new post();
				$tmp->load($replypid);
				array_push($ret, $tmp);
			}
		}
		$this->DB->disconnect();
		return $ret;
	}
	public function create($forum, $title, $message)
	{
		if ($this->SM->poke() == false || $this->PM->can("post") == false) {
			return null;
		}
		$fid = $forum->fid;
		$uid = $this->SM->uid;
		$msgtitle = $title;
		$query = "INSERT INTO posts(fid, uid, msgtitle, message) VALUES($fid, $uid, '$msgtitle', '$message');";
		$this->DB->connect();
		$this->DB->statement($query);
		$check = "SELECT pid FROM posts WHERE fid = $fid AND uid = $uid AND msgtitle = '$msgtitle' AND message = '$message';";
		$pid = $this->DB->query($check)[0]['uid'];
		$this->DB->disconnect();
		$ret = new post();
		$ret->load($pid);
		return $ret;
	}
	public function reply($message)
	{
		if ($this->SM->poke() == false  || $this->PM->can("reply") == false) {
			return null;
		} else if ($this->locked == true) {
			if (!(($this->user->uid == $this->SM->uid && $this->PM->can("unlock_own") == true) ||
				($this->PM->can("unlock=") == true))) { //I know, it's hideous....
				return null;
			}
		}
		$fid = $this->forum->fid;
		$uid = $this->SM->uid;
		$parent = $this->pid;
		$query = "INSERT INTO posts(fid, uid, parentpid, message) VALUES($fid, $uid, $parent, '$message');";
		$check = "SELECT pid FROM posts WHERE fid = $fid AND uid = $uid AND parentpid = $parent AND message = '$message';";
		$this->DB->connect();
		$this->DB->statement($query);
		$pid = $this->DB->query($check)[0]['pid'];
		$this->DB->disconnect();
		$ret = new post();
		$ret->load($pid);
		return $ret;
	}
	public function lock()
	{
		if ($this->SM->poke() == false  ||
		    ($this->PM->can("lock") == false || $this->user->uid != $this->SM->uid)) {
			return false;
		} else if ($this->PM->can("lock_own") == false) {
			return false;
		}
		$pid = $this->pid;
		$query = "UPDATE posts SET locked = 1 WHERE pid = $pid;";
		$this->DB->connect();
		$this->DB->statement($query);
		$this->DB->disconnect();
		return true;
	}
	public function unlock()
	{
		if ($this->SM->poke() == false  ||
		    ($this->PM->can("unlock") == false || $this->user->uid != $this->SM->uid)) {
			return false;
		} else if ($this->PM->can("unlock_own") == false) {
			return false;
		}
		$pid = $this->pid;
		$query = "UPDATE posts SET locked = 0 WHERE pid = $pid;";
		$this->DB->connect();
		$this->DB->statement($query);
		$this->DB->disconnect();
		return true;
	}
	public function delete()
	{
		if ($this->SM->poke() == false  ||
		    ($this->PM->can("delete") == false || $this->user->uid != $this->SM->uid)) {
			return false;
		} else if ($this->PM->can("delete_own") == false) {
			return false;
		}
		$pid = $this->pid;
		$query = "DELETE FROM posts WHERE pid = $pid OR parentpid = $pid;";
		$this->DB->connect();
		$this->DB->statement($query);
		$this->DB->disconnect();
		return true;
	}
	public function move($forum)
	{
		if ($this->SM->poke() == false  ||
		    ($this->PM->can("move") == false || $this->user->uid != $this->SM->uid)) {
			return false;
		} else if ($this->PM->can("move_own") == false) {
			return false;
		}
		$pid = $this->pid;
		$fid = $forum->fid;
		$query = "UPDATE posts SET fid = $fid WHERE pid = $pid OR parentpid = $pid;";
		$this->DB->connect();
		$this->DB->statement($query);
		$this->DB->disconnect();
		return true;
	}
}

?>