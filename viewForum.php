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
	}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Viewing forum</title>
    </head>
    <body>
	   <a href="viewForum.php">Forum Index</a>
	   <?php
		if ($fid < 0) {
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
