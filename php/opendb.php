<?php
$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die('Error connecting to mysql! Check ./php/config.php for errors.');
mysql_select_db($dbname);
?> 