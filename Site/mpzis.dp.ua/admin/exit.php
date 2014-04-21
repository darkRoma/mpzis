<?php
//ini_set('session.save_path','/www/danmillm/users/danmillm-mpzis/tmp');
	if (session_id() == "") session_start();
	session_destroy();
	
	header('Location: index.php');
?>