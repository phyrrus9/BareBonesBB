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

if (!class_exists('sessionManager')) {
	include 'Classes/sessionManager.php';
}
$SM->poke();
$username = $SM->username;

?>

<head>
	<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>
    <div class="navright">
	<a href="action.php?type=user&action=logout" class="button">Log Out <?php echo $username; ?></a>&emsp;
    </div>
</body>