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
	include 'globals.php'
?>
<html>
	<head>
		<meta charset="UTF-8">
		<title></title>
	</head>
	<body>
	<?php
		if ($SM->poke() == false)
		{
			$SM->login("admin", "alpine");
		}
		$forums = $FM->getForums();
		echo("<pre>");
		var_dump($forums);
		echo("</pre>");
		/*foreach ($forums as $category) {
			$catName = $category->name;
			?>
	    <h2><?php echo("$catName"); ?></h2>
			<?php
			$subforums = $category->children;
			foreach ($subforums as $childfid) {
				$forum = new forum();
				$forum->load($childfid, false);
				$forumName = $forum->name;
				?><h3>&nbsp;&nbsp;&nbsp;<?php echo("$forumName $childfid"); ?></h3><br /><?php
			}
		}*/
	?>
	    <br />
	    <hr />
	</body>
</html>
