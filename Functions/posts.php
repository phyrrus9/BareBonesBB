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

if (!defined("FUNC_posts")) {
	define("FUNC_posts", "INCLUDED");
	if (!class_exists('forum')) {
		include 'Classes/forum.php';
	}
	if (!class_exists('postManager')) {
		include 'Classes/postManager.php';
	}
	if (!class_exists('sessionManager')) {
		include 'Classes/sessionManager.php';
	}
	if (!class_exists('post')) {
		include 'Classes/post.php';
	}
	if (!class_exists('permissionManager')) {
		include 'Classes/permissionManager.php';
	}
	if (!defined("FUNC_navigation")) {
		include 'navigation.php';
	}

	function _p_text_indent($text, $indent) {
		echo $text; //I know...
	}
	
	function display_post($post, $indent = 0) {
		$uid = $post->user->uid;
		$username = $post->user->username;
		$when = $post->when;
		$hrwidth = 95 - ($indent * 5);
		$divpad = ($indent * 1);
		echo("<div class=\"post\" style=\"padding-left:".$divpad."%;\">");
		echo("<a href=\"viewUser.php?uid=$uid\">$username</a> | ");
		echo("<a href=\"viewPost.php?pid=$post->pid\">$when</a> &nbsp;&nbsp;&nbsp;");
		display_postNavigation($post->pid);
		echo("<br />" . $post->message . "</div>");
		$replies = $post->loadReplies($post->pid);
		if ($replies != null) {
			foreach ($replies as $replypid) {
				$reply = new post();
				$reply->load($replypid);
				display_post($reply, $indent + 1);
			}
		}
	}
}

?>