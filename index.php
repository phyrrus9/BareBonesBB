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
	session_start();
	require_once 'config.php';
	include_once 'globals.php';
	include 'Classes/sessionManager.php';
	include 'Functions/navigation.php';
	if ($SM->poke()) {
		redirect_page("viewForum.php");
	}
?>
<html>
	<head>
		<meta charset="UTF-8">
		<title></title>
	</head>
	<body>
	    <h1>Board Entry Page</h1>
	    <hr />
	    <form action="action.php?type=user&action=login" method="POST">
		   <label>Username</label>&emsp;<input type="text" name="username" /><br />
		   <label>Password</label>&emsp;<input type="password" name="password" /><br />
		   <input type="submit" value="Log In" />
	    </form>
	    <hr />
	    <div class="navright">
		   <form action="action.php?type=user&action=register" method="POST">
			  <label>Username</label>&emsp;<input type="text" name="username" /><br />
			  <label>Email</label>&emsp;<input type="text" name="email" /><br />
			  <label>Password</label>&emsp;<input type="password" name="password" /><br />
			  <input type="submit" value="Register" />
		   </form>
	    </div>
	</body>
</html>
