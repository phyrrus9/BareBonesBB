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
		$data = $this->DB->query($query)[0];
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
		if (count($data) > 0) {
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
		} else {
			$this->recursionDone = true;
		}
		return $ret;
	}
	public function delete() {
		/*******TODO*******/
	}
	public function create($name, $description = null, $order = 0, $parent = null, $category = null) {
		/*******TODO*******/
	}
	public function reorder($order) {
		/*******TODO*******/
	}
}

?>