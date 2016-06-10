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
?>
<html>
	<head>
		<meta charset="UTF-8">
		<title></title>
	</head>
	<body>
	<?php
		$SM->logout();
		$SM->login($_GET['u'], $_GET['p']);
	?>
	    <br />
	    <hr />
	</body>
</html>
