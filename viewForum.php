<!DOCTYPE html>
<!--
        DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE 
                    Version 2, December 2004 

 Copyright (C) 2004 Sam Hocevar <sam@hocevar.net> 

 Everyone is permitted to copy and distribute verbatim or modified 
 copies of this license document, and changing it is allowed as long 
 as the name is changed. 

            DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE 
   TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION 

  0. You just DO WHAT THE FUCK YOU WANT TO.
-->
<?php
	include 'globalHeader.php';
	include_once 'Functions/forums.php';
	if (!class_exists('forumManager')) {
		include 'Classes/forumManager.php';
	}
	$fid = -1;
	if (isset($_GET['fid'])) {
		$fid = $_GET['fid'];
		if (!strcmp($fid, "")) {
			$fid = -1;
		}
	}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Viewing forum</title>
    </head>
    <body>
	   <div class="navleft"> <a href="viewForum.php" class="button">Forum Index</a>
	   <?php
		if ($fid < 0) {
			display_forumNavigation();
			$forums = $FM->getForums();
			foreach ($forums as $forum) {
				display_forumList($forum->fid);
			}
		} else {
			display_forumList($fid);
		}
	   ?>
    </body>
</html>
