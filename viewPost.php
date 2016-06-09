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
	include 'Functions/navigation.php';
}
if (!defined("FUNC_posts")) {
	include 'Functions/posts.php';
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
	   <?php
	   $pid = $_GET['pid'];
	   $curPost = new post();
	   $curPost->load($pid);
	   display_post($curPost);
	   ?>
    </body>
</html>
