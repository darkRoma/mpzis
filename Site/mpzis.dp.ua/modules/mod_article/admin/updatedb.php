<?php

		$eng = new Engine();
		$c_args = $eng->InitConf("conf.ini");

		$sql = new cMysql($c_args["sql_host"],$c_args["sql_db"],$c_args["sql_login"],$c_args["sql_pass"]);
		$table = $c_args["sql_main_tab"];
		
		/*днаюбкемхе гюохях*/
	function __new($page,$pic,$title,$text,$category,$url,$visibility,$stitle,$sdesc,$stags)
     {
		$eng = new Engine();
		$c_args = $eng->InitConf("conf.ini");

		$sql = new cMysql($c_args["sql_host"],$c_args["sql_db"],$c_args["sql_login"],$c_args["sql_pass"]);
		$table = $c_args["sql_main_tab"];

		
		
		$author = $_SESSION["login"];


        $s = "SELECT Max(LID) FROM $table WHERE `type`='article'";
        $ret = $sql->query($s,true);

        $lid = $ret["Max(LID)"] + 1;
        //var_dump($lid);
        $date = date("Y-d-m");
        $text=strtr($text, "'",'"');
        $s = "INSERT INTO `$table` (`gid`, `type`, `lid`, `v1`, `v2`,`v3`,`v4`,`v5`,`v6`,`v7`,`v8`,`v9`,`title`,`tags`,`description` )
				VALUES ('', 'article', '$lid', '$title', '$pic','$text','$author','$date','$category','$page','$visibility','$url','$stitle','$stags','$sdesc')";

     	$ret = $sql->query($s);


     }
	/*педюйрхпнбюмхе гюохях*/				
     function __edit($page,$pic,$title,$text,$category,$date,$lid,$url,$visibility,$stitle,$sdesc,$stags)
     {
		$eng = new Engine();
		$c_args = $eng->InitConf("conf.ini");

		$sql = new cMysql($c_args["sql_host"],$c_args["sql_db"],$c_args["sql_login"],$c_args["sql_pass"]);
		$table = $c_args["sql_main_tab"];
		$text=strtr($text, "'",'"');

     	$s = "UPDATE $table SET `type` = 'article', `lid` = '$lid',`v1`='$title',`v2`='$pic',
     			`v3`='$text',`v5`='$date',`v6`='$category',`v7`='$page',`v8`='$visibility',`v9`='$url', `title`='$stitle',`description`='$sdesc',`tags`='$stags'
    		  WHERE `lid` = '$lid' AND `type`='article' AND `v7`='$page'";

     	$ret = $sql->query($s);

     }
?>