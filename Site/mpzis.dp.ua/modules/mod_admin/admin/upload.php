<?php


	
	$ph = 200;
	$pw = 200;

		//print_r($_POST);
	
     $tuploaddir = "../../../users/";
     $uploaddir = "../../users/";
	 
	 $pic = $_FILES["file"]["name"];

	 if ($_FILES["file"]["error"] > 0)
	  {
		echo "Error: " . $_FILES["file"]["error"] . "<br />";
		exit;
	  }
	 
	 //print_r($_FILES) ;
	 
     if($pic != "") // заменить фото на сайте
        {
			//удалить старое фото
			
			  if(is_file($pic))	  unlink($pic);

			  // загрузить новое
			  UploadPhoto("file");

        }

 
?>
<?php
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
	     echo("<div id='status'>220</div>");
	   } else {
	      echo("ERROR");
	   }

   /////////////TRANSFORM PIC///////////////////////////////////
/*
		if(create_small($tuploaddir.$_FILES["$file"]["name"],$uploaddir.$_FILES["$file"]["name"],
					$ph,$pw))
		{
			echo ("ERROR");
			unlink($tuploaddir.$_FILES["$file"]["name"]);
			exit;
		}
		unlink($tuploaddir.$_FILES["$file"]["name"]);
*/
echo("<div id='result'>".$uploaddir.$_FILES["$file"]["name"]."</div>");

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
