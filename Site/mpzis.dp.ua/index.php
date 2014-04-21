<?php
  date_default_timezone_set("Europe/Kiev");
  include ("include/core.php");
	$core = new Core("admin/conf.ini");
 	$core->include_all();
  $loc = $core->init();
 	//print "Location => $loc";
  $core->parser($loc);
?>