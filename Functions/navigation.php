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
	function display_forumNavigation($fid) {
		$forum = new forum();
		$forum->load($fid);
		$pm = new permissionManager();
		if (!$pm->can("view")) {
			return;
		} if ($pm->can("post") && !$forum->category) {
			echo("<a href=\"action.php?type=post&fid=$fid&action=new\" class=\"button\">New Post in $forum->name</a> ");
		} if ($pm->can("delete_forum")) {
			echo("<a href=\"action.php?type=forum&fid=$fid&action=delete\" class=\"button\">Delete $forum->name</a> ");
		}
	}
}

?>