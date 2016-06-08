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
	include 'globals.php';
	$fid = 0;
	if (isset($_GET['fid'])) {
		$fid = $_GET['fid'];
	}
	$curForum = new forum();
	$curForum->load($fid);
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo("" . $curForum->name); ?></title>
    </head>
    <body>
	<?php
		if ($curForum->parent != null) {
			$parentForum = new forum();
			$parentForum->load($curForum->parent, false);
			?><a href="viewForum.php?fid=<?php echo $parentForum->fid; ?>"><?php echo $parentForum->name; ?></a><?php
		}
		if (count($curForum->children) > 0) { ?>
	   <h3>Forums</h3><h4> <?php
		foreach ($curForum->children as $childForum) {
			?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="viewForum.php?fid=<?php echo $childForum->fid; ?>"><?php echo $childForum->name; ?></a><?php
		}
		}?></h4>
    </body>
</html>
