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

function display_forumList($fid) {
	$curForum = new forum();
	$curForum->load($fid);
	if ($curForum->parent != null) {
		$parentForum = new forum();
		$parentForum->load($curForum->parent, false);
		?>&nbsp;
		<a href="viewForum.php?fid=<?php echo $parentForum->fid; ?>"><?php echo $parentForum->name; ?></a><?php
	}
	?><br /><b><strong><?php echo($curForum->name); ?>&emsp;&emsp;&emsp;</strong></b><?php
	display_forumNavigation($fid);
	echo("<br />");
	if (count($curForum->children) > 0) { ?>
	  <table class="forumList">
		 <tr>
			<td class="header">Name</td>
		  <?php
		foreach ($curForum->children as $childForum) {
			?><td class="data"><a href="viewForum.php?fid=<?php echo $childForum->fid; ?>"><?php echo $childForum->name; ?></a></td><?php
		}?>
		</tr>
		<tr>
		    <td class="header" >Description</td>
		<?php
		foreach ($curForum->children as $childForum) {
			?><td class="data"><?php echo $childForum->description; ?></td><?php
		}
		?>
		</tr>
		<tr>
		    <td class="header">Threads</td>
		<?php
		foreach ($curForum->children as $childForum) {
			?><td class="data"><?php echo $childForum->threadCount(); ?></td><?php
		}
		?>
		</tr>
		<tr>
		    <td class="header">Posts</td>
		<?php
		foreach ($curForum->children as $childForum) {
			?><td class="data"><?php echo $childForum->postCount(); ?></td><?php
		}
		?>
		</tr>
		<?php
	}?></table><?php
	display_postList($fid);
}

function display_postList($fid) {
	$PTM = new postManager();
	$forum = new forum();
	$forum->load($fid);
	$posts = $PTM->load($forum);
	echo("<pre>");
	//var_dump($posts);
	echo("</pre>");
	foreach ($posts as $post) {
		$pid = $post->pid;
		$username = $post->user->username;
		$title = $post->title;
		?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="viewPost.php?pid=<?php echo($pid); ?>"><?php echo("$username | $title"); ?></a><br /><?php
	}
}