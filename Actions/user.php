<?php namespace Actions\user;

if (!class_exists('user')) {
	include 'Classes/user.php';
}
if (!class_exists('sessionManager')) {
	include 'Classes/sessionManager.php';
}
if (!defined("FUNC_navigation")) {
	include 'Functions/navigation.php';
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

if (!defined("ACTION_user")) {
	define("ACTION_user", "INCLUDED");
	
	function action_user($params) {
		$action = $params['action'];
		if (!strcmp($action, "logout")) {
			\Actions\user\action_logout();
		} else if (!strcmp($action, "login")) {
			\Actions\user\action_login($params['username'], $params['password']);
		}
	}
	
	function action_logout() {
		global $SM;
		$SM->logout();
		redirect_page("index.php");
	}
	function action_login($username, $password) {
		global $SM;
		if ($SM->login($username, $password)) {
			redirect_page("viewForum.php");
		} else {
			redirect_page("index.php");
		}
	}
}

?>