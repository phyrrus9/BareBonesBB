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
 * Description of forumManager
 *
 * @author phyrrus9
 */

if (!class_exists('forumManager')) {
	class forumManager {
		private $DB;
		private $categories;
		private $lastRefresh;
		public function __construct()
		{
			$this->DB = new DBManager();
			$this->categories = null;
			$this->lastRefresh = 0;
		}
		public function load()
		{
			if ($this->categories != null) {
				$this->categories = null;
			}
			$this->categories = array();
			$query = "SELECT fid FROM forums WHERE category = 1;";
			$this->DB->connect();
			$data = $this->DB->query($query);
			foreach ($data as $fid) {
				$fid = $fid['fid'];
				$tmp = new forum();
				$tmp->load($fid);
				array_push($this->categories, $tmp);
			}
			$this->lastRefresh = time();
		}
		public function getForums()
		{
			if ((time() - (60 * 5)) > $this->lastRefresh) {
				$this->load();
			}
			return $this->categories;
		}
	}
	$FM = new forumManager();
}
?>