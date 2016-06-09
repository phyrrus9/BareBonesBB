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
 * Description of postManager
 *
 * @author phyrrus9
 */

class postManager {
	public $forum;
	public $posts;
	private $lastRefresh;
	private $DB;
	private $SM;
	public function __construct() {
		$this->forum = null;
		$this->posts = null;
		$this->lastRefresh = 0;
		$this->DB = new DBManager();
		$this->SM = new sessionManager();
	}
	public function load($forum) {
		if ($this->posts != null || $this->forum != null) {
			$this->posts = null;
			$this->forum = null;
		}
		$this->forum = $forum;
		return $this->reload();
	}
	public function reload() {
		$fid = $this->forum->fid;
		$query = "SELECT pid FROM posts WHERE fid = $fid ORDER BY whendt desc LIMIT 512;";
		$this->DB->connect();
		$posts = $this->DB->query($query);
		$this->DB->disconnect();
		$this->posts = array();
		foreach ($posts as $pidarr) {
			$pid = $pidarr['pid'];
			$tmp = new post();
			$tmp->load($pid);
			array_push($this->posts, $tmp);
		}
		$this->lastRefresh = time();
		return $this->posts;
	}
	public function get($n) {
		if ($this->forum == null) {
			return null;
		} else if (time() >= ($this->lastRefresh + (60 * 5))) {
			$this->reload();
		}
		return $this->posts[$n];
	}
}

?>