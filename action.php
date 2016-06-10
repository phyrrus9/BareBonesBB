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

include 'Actions/post.php';
include 'Actions/forum.php';
include 'Actions/user.php';

if (!defined("PAGE_action")) {
	define("PAGE_action", "INCLUDED");

	$params = array();
	foreach ($_GET as $key => $val) {
		$params[$key] = $val;
	}
	foreach ($_POST as $key => $val) {
		$params[$key] = $val;
	}
	$type = $params['type'];
	if (!strcmp($type, "post")) {
		\Actions\post\action_post($params);
	} else if (!strcmp($type, "forum")) {
		\Actions\forum\action_forum($params);
	} else if (!strcmp($type, "user")) {
		\Actions\user\action_user($params);
	}
}
?>