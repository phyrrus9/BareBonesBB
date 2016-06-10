<?php namespace Actions\post;
include 'globalHeader.php';
if (!class_exists('forum')) {
	include 'Classes/forum.php';
}
if (!class_exists('post')) {
	include 'Classes/post.php';
}
if (!class_exists('permissionManager')) {
	include 'Classes/permissionManager.php';
}
if (!class_exists('DBManager')) {
	include 'Classes/DBManager.php';
}
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

if (!defined("ACTION_post")) {
	define("ACTION_post", "INCLUDED");
	
	function action_post($params, $user) {
		if (!strcmp($params['action'], "new")) {
			$parent = null;
			if (isset($params['parent'])) {
				$parent = $params['parent'];
			}
			\Actions\post\action_newpost($params['fid'], $parent);
		} else if (!strcmp($params['action'], "postnew")) {
			$parent = null;
			$title = null;
			$forum = new \forum();
			$forum->load($params['fid']);
			if (isset($params['parent'])) {
				$parent = new \post();
				$parent->load($params['parent']);
			}
			if (isset($params['title'])) {
				$title = $params['title'];
			}
			\Actions\post\action_submitpost($forum, $title, nl2br($params['message']), $parent);
		} else if (!strcmp($params['action'], "lock") || !strcmp($params['action'], "unlock")) {
			$pid = $params['pid'];
			$fid = $params['fid'];
			$post = new \post();
			$forum = new \forum();
			$post->load($pid);
			$forum->load($fid);
			\Actions\post\action_toggleLock($forum, $post);
		} else if (!strcmp($params['action'], "delete")) {
			$pid = $params['pid'];
			$fid = $params['fid'];
			$post = new \post();
			$forum = new \forum();
			$post->load($pid);
			$forum->load($fid);
			\Actions\post\action_delete($forum, $post);
		}
	}
	
	function action_newpost($fid, $parent = null) {
		?>
<form action="action.php?type=post&action=postnew&fid=<?php echo $fid; ?>" method="POST">
    <input type="submit" class="button" value="Create Post" /><br />
    <?php if ($parent == null) { ?>
    <textarea name="title" rows="1" cols="80%">Title</textarea><br />
    <?php } else { ?>
    <input type="hidden" name="parent" value="<?php echo $parent; ?>" />
    <?php } ?>
    <textarea name="message" rows="15" cols="80%">Message</textarea><br />
</form>
		<?php
	}
	function action_submitpost($forum, $title, $message, $parent = null) {
		global $PM;
		$reply = $parent != null;
		$cpost = new \post();
		$parentPost = $parent;
		if ($PM->can("post") && $reply != true) {
			$parentPost = $cpost->create($forum, $title, $message);
		} else if ($PM->can("reply") && $reply == true) {
			$parent->reply($message);
		}
		\Actions\post\redirect_post($parentPost);
	}
	function action_toggleLock($forum, $post) {
		global $PM;
		$canlock = ($PM->can("lock") || ($PM->can("lock_own") && $post->owns())) && !$post->locked;
		$canunlock = ($PM->can("unlock") || ($PM->can("unlock_own") && $post->owns())) && $post->locked;
		if ($post->locked && $canunlock) {
			$post->unlock();
		} else if ($canlock) {
			$post->lock();
		}
		\Actions\post\redirect_post($post);
	}
	function action_delete($forum, $post) {
		global $PM;
		if ($PM->can("delete") || ($PM->can("delete_own") && $post->owns)) {
			$post->delete();
		}
		\Actions\post\redirect_forum($forum);
	}
	function redirect_forum($forum) {
		?><script>window.location.assign("viewForum.php?fid=<?php echo $forum->fid; ?>")</script><?php
	}
	function redirect_post($post) {
		?><script>window.location.assign("viewPost.php?pid=<?php echo $post->pid; ?>")</script><?php
	}
}

?>