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
 * Description of forum
 *
 * @author phyrrus9
 */

if (!class_exists('DBManager')) {
	include('Classes/DBManager.php');
}
if (!class_exists('post')) {
	include('Classes/post.php');
}

class forum
{
	public $fid;
	public $order;
	public $name;
	public $description;
	public $parent;
	public $children;
	public $category;
	private $recursionDone;
	private $DB;
	public function __construct()
	{
		$this->fid = -1;
		$this->order = -1;
		$this->name = null;
		$this->description = null;
		$this->parent = null;
		$this->category = false;
		$this->children = null;
		$this->recursionDone = false;
		$this->DB = new DBManager();
	}
	public function load($fid, $recurse = true)
	{
		$query = "SELECT fid, displayorder, name, description, parent, category " .
			    "FROM forums WHERE fid = $fid ORDER BY displayorder;";
		$this->DB->connect();
		$data = $this->DB->query($query);
		if (!is_array($data) && count($data) > 0) { return false; } else { $data = $data[0]; }
		$this->fid = $data['fid'];
		$this->order = $data['displayorder'];
		$this->name = $data['name'];
		$this->description = $data['description'];
		$this->parent = $data['parent'];
		$this->category = $data['category'] == 1;
		$this->DB->disconnect();
		/*if ($this->parent != null) {
			$parentfid = $this->parent;
			$this->parent = new forum();
			$this->parent->load($parentfid, !$this->recursionDone);
		}*/
		if ($recurse == true && !$this->recursionDone) {
			$this->children = $this->loadChildren($fid);
		}
	}
	public function loadChildren($fid, $expand = true)
	{
		$this->DB->connect();
		$ret = null;
		$query = "SELECT fid FROM forums WHERE parent = $fid ORDER BY displayorder;";
		$data = $this->DB->query($query);
		$this->children = null;
		$this->DB->disconnect();
		if (count($data) > 0 && is_array($data)) {
			$ret = array();
			foreach ($data as $tmpfid) {
				$tmpfid = $tmpfid['fid'];
				$tmp = new forum();
				$tmp->load($tmpfid, true);
				if ($expand) {
					array_push($ret, $tmp);
				} else {
					array_push($ret, $tmpfid);
				}
			}
		} else { $this->recursionDone = true; }
		return $ret;
	}
	public function delete() {
		foreach ($this->children as $child) {
			$child->delete();
		}
		$this->DB->connect();
		$this->DB->statement("DELETE FROM forums WHERE fid = $this->fid;");
		$this->DB->disconnect();
	}
	public function create($name, $description = null, $order = 0, $parent = null, $category = false) {
		$fmtdescription = $description == null ? "null" : "'$description'";
		$fmtparent = $parent == null ? "null" : $parent->fid;
		$fmtcategory = $category == true ? 1 : 0;
		$query = "INSERT INTO forums(displayorder, name, description, parent, category) " .
			    "VALUES($order, '$name', $fmtdescription, $fmtparent, $fmtcategory);";
		$this->DB->connect();
		$this->DB->statement($query);
		$this->DB->disconnect();
	}
	public function reorder($order) {
		$this->DB->connect();
		$this->DB->statement("UPDATE forums SET displayorder = $order WHERE fid = $this->fid;");
		$this->DB->disconnect();
	}
	public function threadCount() {
		return count($this->threadList());
	}
	public function postCount($post = null) {
		$count = 0;
		if ($post == null) {
			$threadList = $this->threadList();
			foreach ($threadList as $thread) {
				$count += $this->postCount($thread);
			}
		} else if ($post->replies != null) {
			foreach ($post->replies as $childpid) {
				$child = new post();
				$child->load($childpid, true);
				$count += $this->postCount($child) + 1;
			}
		} else {
			return 1;
		}
		return $count;
	}
	private function threadList() {
		$threads = array();
		$this->DB->connect();
		$pidList = $this->DB->query("SELECT pid FROM posts WHERE fid = $this->fid;");
		$this->DB->disconnect();
		foreach($pidList as $pidtmp) {
			$pid = $pidtmp['pid'];
			$tmp = new post();
			$tmp->load($pid, true);
			array_push($threads, $tmp);
		}
		return $threads;
	}
}

?>