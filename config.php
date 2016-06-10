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

$DB_SETTINGS = array(
    "server"		=>	"localhost",
    "port"		=>	3306,
    "username"		=>	"root",
    "password"		=>	"alpine",
    "database"		=>	"BareBonesBB"
);
$UPGRADE_SETTINGS = array( //post count that upgrade occurs, -1 to disable
    "post"		=>	10,
    "reply"		=>	0,
    "lock_own"		=>	50,
    "unlock_own"	=>	75,
    "delete_own"	=>	100,
    "warn"		=>	250,
    "manage_flags"	=>	300,
    "move"		=>	500,
    "lock"		=>	550,
    "delete"		=>	600,
    "ban"			=>	750,
    "moderator"	=>	1000
);

?>

