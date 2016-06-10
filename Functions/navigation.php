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

if (!class_exists('forum')) {
	include 'Classes/forum.php';
}
if (!class_exists('postManager')) {
	include 'Classes/postManager.php';
}
if (!class_exists('post')) {
	include 'Classes/post.php';
}
if (!class_exists('permissionManager')) {
	include 'Classes/permissionManager.php';
}

if (!defined("FUNC_navigation")) {
	define("FUNC_navigation", "INCLUDED");
	function display_forumNavigation($fid = -1) {
		$forum = $fid == -1 ? null : new forum();
		if ($forum != null) {
			$forum->load($fid);
			$type = "Forum";
			$entry = "<td class=\"rightbutton\">";
			$exit = "</td>";
		} else {
			$type = "Category";
			$entry = $exit = "";
		}
		$pm = new permissionManager();
		if (!$pm->can("view")) {
			return;
		}
		?> <table style="display: inline-table; float: right"><tr> <?php
		if ($pm->can("post") && $fid != -1 && !$forum->category) {
			echo("<td class=\"rightbutton\"><a href=\"action.php?type=post&fid=$fid&action=new\" class=\"button\">New Post in $forum->name</a></td>");
		} if ($pm->can("delete_forum") && $fid != -1) {
			echo("<td class=\"rightbutton\"><a href=\"action.php?type=forum&fid=$fid&action=delete\" class=\"button\">Delete $forum->name</a></td>");
		} if ($pm->can("create_forum")) {
			echo("$entry<a href=\"action.php?type=forum&fid=$fid&action=new\" class=\"button\">New $type</a>$exit");
		}?></tr></table><?php
	}
	function display_postNavigation($pid) {
		$curPost = new post();
		$curPost->load($pid, false);
		$fid = $curPost->forum->fid;
		$level = 0;
		if ($fid == null) {
			$fid = -1;
		}
		global $SM;
		$origPost = $curPost;
		while ($origPost->parent != null) {
			$origPost = $origPost->parent;
			$level++;
		}
		$origpid = $origPost->pid;
		$pm = new permissionManager();
		if (!$SM->poke()) { return false; }
		$owns = $curPost->user->uid == $SM->uid;
		$candelete = $pm->can("delete") ? true : $pm->can("delete_own") && $owns ? true : false;
		$canlock = ($pm->can("lock") ? true : $pm->can("lock_own") && $owns ? true : false) && !$curPost->locked;
		$canunlock = ($pm->can("unlock") ? true : $pm->can("unlock_own") && $owns ? true : false) && $curPost->locked;
		?> <table class="forumList" style="display: inline-table; float: right;"><tr> <?php
		if (!$curPost->locked || $canunlock) {
			echo("<td class=\"rightbutton\"><a href=\"action.php?type=post&fid=$fid&parent=$origpid&action=new\" class=\"button\">Reply</a></td>");
			if ($curPost->parent != null) {
				$parentpid = $curPost->parent->pid;
				echo("<td class=\"rightbutton\"><a href=\"action.php?type=post&fid=$fid&parent=$parentpid&action=new\" class=\"button\">Reply Level $level</a></td>");
			}
			echo("<td class=\"rightbutton\"><a href=\"action.php?type=post&fid=$fid&parent=$pid&action=new\" class=\"button\">Comment</a></td><td>&emsp;</td>");
		} if ($candelete) {
			echo("<td class=\"rightbutton\"><a href=\"action.php?type=post&fid=$fid&pid=$pid&action=delete\" class=\"button\">Delete</a></td>");
		} if ($canlock) {
			echo("<td class=\"rightbutton\"><a href=\"action.php?type=post&fid=$fid&pid=$pid&action=lock\" class=\"button\">Lock</a></td>");
		} if ($canunlock) {
			echo("<td class=\"rightbutton\"><a href=\"action.php?type=post&fid=$fid&pid=$pid&action=unlock\" class=\"button\">Unock</a></td>");
		} ?></tr></table><?php
	}
	function redirect_page($page) {
		echo("<head><script>window.location.assign(\"$page\")</script></head>");
	}
}

?>