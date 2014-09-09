<?php
     //include ("../include/stdfx.php");
     include ("../include/core.php");

     $eng = new Engine();
	 $c_args = $eng->InitConf("conf.ini");

 	 $ph = 200;
	 $pw = 200;

		//print_r($_POST);
	
	 $sql = new cMysql($c_args["sql_host"],$c_args["sql_db"],$c_args["sql_login"],$c_args["sql_pass"]);
	 $table = $c_args["sql_struct_tab"];

     $s = "Select `c_value` from ". $table ." where `c_id`='tempdir_path'";

     $ret 		 = $sql->query($s,true);
     $tuploaddir = "../".$ret["c_value"]."/";

	 $s = "Select `c_value` from ". $table ." where `c_id`='atricles_preview_path'";

	 $ret 		= $sql->query($s,true);
     $uploaddir = "../".$ret["c_value"]."/";

     $cmd = $_POST["cmd"];


	//print "$uploaddir";

     switch ( $cmd )
     {
     	case "new":
     	{
     		__new();
     		break;
     	}
     	case "edit":
     	{
     		__edit();
     		break;
     	}

     }
		
     //echo "<script>window.close()</script>";
/*---------------------------------------------------------------------------------------------------*/

     function __new()
     {
     	/*
     	$out .= "<form name=\"\" action=\"adm_article_update.php\" method=\"post\">\n";
        $out .= "<input name=\"page\" type=\"hidden\" value=$page>\n";
		$out .= "<input name=\"cmd\" type=\"hidden\" value=\"new\">\n";


        $out.= "Page Title<br>\n";
		$out.= "<input name=\"n_title\" type=\"text\" value=\"\"><br>\n";
		$out.= "Picture preview path<br>\n";
		$out.= "<input name=\"n_pic_path\" type=\"file\" value=\"\"><br>\n";
		$out.= "Article<br>\n";
		$out.= "<textarea name=\"n_text\" rows=30 cols=50 wrap=\"off\"></textarea><br>\n";
		$out.= "Author<nr>\n";
		$out.= "<input name=\"n_author\" type=\"text\" value=\"\"><br>\n";
		$out.= "Category";
        $out.= "<input name=\"n_category\" type=\"text\" value=\"\"><br>\n";
        $out.= "<input type=\"submit\" value=\"Create\">\n";
     	*/

        global $uploaddir;
     	global $c_args;

     	

     	$page = $_POST["page"];
		$title = $_POST["n_title"];
		$text = $_POST["n_text"];
		$author = $_POST["n_author"];
		$category = $_POST["n_category"];

		//var_dump($_POST);

		if($pic != "") // заменить фото на сайте
        {
			  // загрузить новое

			  UploadPhoto("n_pic_path");

			  // обновить бaзу

     		  $pic_path = $uploaddir.$_FILES["n_pic_path"]["name"];
     		  print "$pic_path";
        }

		$sql = new cMysql($c_args["sql_host"],$c_args["sql_db"],$c_args["sql_login"],$c_args["sql_pass"]);
		$table = $c_args["sql_main_tab"];
								mysql_query("SET NAMES 'utf8'");

        $s = "SELECT Max(LID) FROM $table WHERE `type`='article'";
        $ret = $sql->query($s,true);

        $lid = $ret["Max(LID)"] + 1;
        //var_dump($lid);
        $date = date("Y-d-m");
        
        $s = "INSERT INTO `$table` (`gid`, `type`, `lid`, `v1`, `v2`,`v3`,`v4`,`v5`,`v6`,`v7` )
				VALUES ('', 'article', '$lid', '$title', '$pic_path','$text','$author','$date','$category','$page')";
								mysql_query("SET NAMES 'utf8'");
     	$ret = $sql->query($s);


     }

     function __edit()
     {
     	 global $uploaddir;
     	 global $c_args;

     	 $sql = new cMysql($c_args["sql_host"],$c_args["sql_db"],$c_args["sql_login"],$c_args["sql_pass"]);
		 $table = $c_args["sql_main_tab"];

     	 $pic = $_FILES["n_pic_path"]["name"];

     	$page 	= $_POST["page"];
		$title 	= $_POST["n_title"];
		$text 	= $_POST["n_text"];
		$author = $_POST["n_author"];
		$category = $_POST["n_category"];
		$date	= $_POST["n_date"];
		$lid  	= $_POST["lid"];

		/////////////////////////////////////////

        if($pic != "") // заменить фото на сайте
        {
			//удалить старое фото
			  $s  = "SELECT `v2`,`gid` FROM $table WHERE `lid` = '$lid' AND `type`='article' AND `v7`='$page'";
			  $ret = $sql->query($s,true);
								mysql_query("SET NAMES 'utf8'");

			  //print $uploaddir.$row["photo"] . " <==DEL";
			  if($ret["v2"] != "")	  unlink($ret["v2"]);

			  // загрузить новое
			  UploadPhoto("n_pic_path");

			  // обновить бaзу

              $s = "UPDATE $table SET `v2` = '".$uploaddir.$_FILES["n_pic_path"]["name"]."'
              WHERE `gid` = '".$ret["gid"]."'";
              								mysql_query("SET NAMES 'utf8'");
     		  $ret = $sql->query($s);
        }





     	$s = "UPDATE $table SET `gid` = '', `type` = 'article', `lid` = '$lid',`v1`='$title',
     			`v3`='$text',`v4`='$author',`v5`='$date',`v6`='$category',`v7`='$page'
    		  WHERE `lid` = '$lid' AND `type`='article' AND `v7`='$page'";

		//print "'$s'";
     	$ret = $sql->query($s);

		//print "Запись была изменена.";
        //echo "<script>window.location='admin.php?cmd=edit&page=$page&lid=$lid';</script>";

     }

function UploadPhoto($file)
{
     global $tuploaddir;
	 global $uploaddir ;

	GLOBAL $ph ;
	GLOBAL $pw ;

	  if ($_FILES["$file"]["error"] > 0)
	  {
		echo "Error: " . $_FILES["$file"]["error"] . "<br />";
		exit;
	  }

	   if(move_uploaded_file($_FILES["$file"]["tmp_name"],
	     $tuploaddir.$_FILES["$file"]["name"]))
	   {
	     echo("Файл успешно загружен <br>");
	   } else {
	      echo("Ошибка загрузки файла");
	   }

   /////////////TRANSFORM PIC///////////////////////////////////

		if(create_small($tuploaddir.$_FILES["$file"]["name"],$uploaddir.$_FILES["$file"]["name"],
					$ph,$pw))
		{
			echo ("Это не фото");
			unlink($tuploaddir.$_FILES["$file"]["name"]);
			exit;
		}
		unlink($tuploaddir.$_FILES["$file"]["name"]);



}

function create_small($name_big,$name_small,$max_x, $max_y)
{
  list($x, $y, $t, $attr) = getimagesize($name_big);

  if ($t == IMAGETYPE_GIF)
    $big=imagecreatefromgif($name_big);
  else if ($t == IMAGETYPE_JPEG)
    $big=imagecreatefromjpeg($name_big);
  else if ($t == IMAGETYPE_PNG)
    $big=imagecreatefrompng($name_big);
  else
    return 1;

  if ($x > $y)
  {
    $xs=$max_x;
    $ys=$max_x/($x/$y);
  }
  else
  {
    $ys=$max_y;
    $xs=$max_y/($y/$x);
  }
  $small=imagecreatetruecolor ($xs,$ys);
  $res = imagecopyresampled($small,$big,0,0,0,0,$xs,$ys,$x,$y);
  imagedestroy($big);
  imagejpeg($small,$name_small);
  imagedestroy($small);

  return 0;
}


?>