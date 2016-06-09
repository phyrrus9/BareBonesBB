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
			\Actions\post\action_newpost($params['fid'], $user, $parent);
		} else if (!strcmp($params['action'], "postnew")) {
			var_dump($params);
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
			\Actions\post\action_submitpost($forum, $title, nl2br($params['message']), $user, $parent);
		}
	}
	
	function action_newpost($fid, $user, $parent = null) {
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
	function action_submitpost($forum, $title, $message, $user, $parent = null) {
		global $DB;
		global $PM;
		$reply = $parent != null;
		$DB->connect();
		if ($PM->can("post") && $reply != true) {
			$query = "INSERT INTO posts(fid, uid, msgtitle, message) " .
				    "VALUES($forum->fid, $user->uid, '$title', '$message');";
			$DB->statement($query);
		} else if ($PM->can("reply") && $reply == true) {
			$query = "INSERT INTO posts(parentpid, uid, message) " .
				    "VALUES($parent->pid, $user->uid, '$message');";
			$DB->statement($query);
		}
		$DB->disconnect();
		?><script>window.location.assign("viewForum.php?fid=<?php echo $forum->fid; ?>")</script><?php
	}
}

?>