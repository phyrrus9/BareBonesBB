<?php namespace Actions\forum;

if (!class_exists('forum')) {
	include 'Classes/forum.php';
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

if (!defined("ACTION_forum")) {
	define("ACTION_forum", "INCLUDED");
	
	function action_forum($params) {
		$action = $params['action'];
		if (!strcmp($action, "new")) {
			$parent = null;
			if (isset($params['fid']) && $params['fid'] != -1) {
				$parent = new \forum();
				$parent->load($params['fid']);
			}
			\Actions\forum\action_new($parent);
		} else if (!strcmp($action, "postnew")) {
			$parent = null;
			$category = isset($params['category']) ? !strcmp($params['category'], "on") : false;
			if (isset($params['fid'])) {
				$parent = new \forum();
				$parent->load($params['fid']);
			}
			$description = !strcmp($params['description'], "") ? null : $params['description'];
			\Actions\forum\action_postnew($params['name'], $description, $params['order'], $parent, $category);
		} else if (!strcmp($action, "delete")) {
			$forum = new \forum();
			$forum->load($params['fid']);
			\Actions\forum\action_delete($forum);
		} else if (!strcmp($action, "postdelete")) {
			$forum = new \forum();
			$forum->load($params['fid']);
			$delete = isset($params['delete']) ? !strcmp($params['delete'], "on") : false;
			\Actions\forum\action_postdelete($forum, $delete);
		}
	}
	function action_new($parent = null) {
		?>
<h2>New Forum or Category</h2>
<form action="action.php?type=forum&action=postnew" method="POST">
    <label>Forum Title</label> <input type="text" name="name" value="New Forum" style="width: 300px;" /><br />
    <label>Description</label> <input type="text" name="description" style="width: 500px;" /><br />
		<?php
		$checked = null;
		if (!$parent) {
			$checked = "checked";
		} else {
			echo("<input type=\"hidden\" name=\"fid\" value=\"$parent->fid\" />");
		}
		?>
    <label>Order</label> <input type="number" name="order" value="0" style="width: 40px;">&emsp;&emsp;&emsp;
    <label>Category</label> <input type="checkbox" name="category" <?php echo $checked; ?> />&emsp;&emsp;&emsp;
    <input type="submit" class="button" value="Create" />
</form>
		<?php
	}
	function action_postnew($name, $description = null, $order = 0, $parent = null, $category = false) {
		$parentforum = $category ? null : $parent;
		$parentfid = $parentforum != null ? $parentforum->fid : null;
		$forumhandle = $parentforum == null ? new \forum() : $parentforum;
		$newforum = $forumhandle->create($name, $description, $order, $parentforum, $category);
		\Actions\forum\redirect_forum($newforum);
	}
	function action_delete($forum) {
		$fid = $forum->fid;
		$forumname = $forum->name;
		$haschildren = count($forum->children) > 0;
		$childwarning = $haschildren ? "<h3>This entity has children!</h3>" : "";
		?>
<form action="action.php?type=forum&action=postdelete&fid=<?php echo $fid; ?>" method="POST">
    <?php echo $childwarning; ?>
    <label>Delete <?php echo $forumname; ?>?</label> <input type="checkbox" name="delete" />&emsp;&emsp;&emsp;
    <input type="submit" class="button" value="Go" />
</form>
		<?php
	}
	function action_postdelete($forum, $delete) {
		global $PM;
		if ($delete == true && $PM->can("delete_forum") == true) {
			$forum->delete();
		}
		\Actions\forum\redirect_forum();
	}
	function redirect_forum($forum = null) {
		$fid = $forum == null ? -1 : $forum->fid;
		?><script>window.location.assign("viewForum.php?fid=<?php echo $fid ?>")</script><?php
	}
}

?>
