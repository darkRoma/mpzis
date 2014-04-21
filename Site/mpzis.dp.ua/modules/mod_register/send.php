<?php
require("../../include/core.php");

	$eng 	= new Engine();
	$c 		= $eng->InitConf("../../admin/conf.ini");
	$sql 	= new cMysql($c["sql_host"],$c["sql_db"],$c["sql_login"],$c["sql_pass"]);
	//$tab 	= $eng->GetModTableRaw('register','register');
	
	$tab = "mpzis_register";
	
	//BurnPost();
	
	$file = UploadFiles($tab);
	
	header("location: /result");	
	//print_r($_POST);
	
//( [science] => [surname] => [name] => [f_name] => [kaf] => [place] => [who] => [h_addr] => [city] => [country] => [index] => [h_tel] => [w_tel] => [email] => [r_name] => [lang] => )

/*	$s = "insert into $tab
	 (science,surname,name,f_name,kaf,place,who,h_addr,city,country,index,h_tel,w_tel,email,r_name,lang,file_upl) 
	values( '{$_POST['science']}','{$_POST['surname']}','{$_POST['name']}','{$_POST['f_name']}','{$_POST['kaf']}','{$_POST['place']}','{$_POST['who']}','{$_POST['h_addr']}','{$_POST['city']}','{$_POST['country']}','{$_POST['index']}','{$_POST['h_tel']}','{$_POST['w_tel']}','{$_POST['email']}','{$_POST['r_name']}','{$_POST['lang']}','$file' )";
*/

$s = "insert into $tab
	 (science,surname,name,f_name,kaf,place,who,h_addr,city,country,`index`,nova_poshta_number,h_tel,w_tel,email,r_name,lang,file_upl,section,rep_type) 
	values('{$_POST['science']}','{$_POST['surname']}','{$_POST['name']}','{$_POST['f_name']}','{$_POST['kaf']}','{$_POST['place']}','{$_POST['who']}','{$_POST['h_addr']}','{$_POST['city']}','{$_POST['country']}','{$_POST['index']}','{$_POST['nova_poshta_number']}','{$_POST['h_tel']}','{$_POST['w_tel']}','{$_POST['email']}','{$_POST['r_name']}','{$_POST['lang']}','$file','{$_POST['section']}','{$_POST['rep_type']}' )";
	

	
	$sql->query($s,false,'UTF8');


function UploadFiles($db)
{
			
			if(empty($_FILES['file_upl']['name']))
				return 0;
				
			//print_r($_FILES);	
			
			$eng 	= new Engine();
			$c 		= $eng->InitConf("../../admin/conf.ini");
			$sql 	= new cMysql($c["sql_host"],$c["sql_db"],$c["sql_login"],$c["sql_pass"]);
			
			
			$uploaddir = '../../'. "upload_register" . '/';
			
			$ret = '../'. "upload_register" . '/';
			//$uploaddir =  "upload_register" . '/';
			$fn=makeUniqName(basename(transliterate($_FILES['file_upl']['name'])));
			$uploadfile = $uploaddir .$fn;
			$ret .= $fn;
			
			//$uploadfile = transliterate($uploadfile);
			//print "<b>".$uploadfile."</b>";
			//print "<b>".$_FILES['file_upl']['tmp_name']."</b>";
			
			if (move_uploaded_file($_FILES['file_upl']['tmp_name'], $uploadfile))
			{
				// update db...
				chmod($uploadfile, 0777);
				//$s = "UPDATE $db SET `files` = '$uploadfile' where `id` = $o_id";
				//$sql->query($s);
				//print "upload OK";
				
			}else{
				
				//print "error occured:<h6>$uploadfile</h6>";
				
			}
			
			return $ret;
}

   function makeUniqName($filename)
   {
         $pos=strrpos($filename,'.');
         $str=md5(substr($filename,0,$pos).rand());
         return $str.substr($filename,$pos);

   }
function BurnPost()
	{
		$eng = new Engine;
		
		// client data
		//( [science] => [surname] => [name] => [f_name] => [kaf] => [place] => [who] => [h_addr] => [city] => [country] => [index] => [h_tel] => [w_tel] => [email] => [r_name] => [lang] => )
	
	/*	
	science	varchar(50)	utf8_unicode_ci		���	���		 	 	 	 	 	 
 	surname	varchar(50)	utf8_unicode_ci		���	���		 	 	 	 	 	 	 
 	name	varchar(50)	utf8_unicode_ci		���	���		 	 	 	 	 	 	 
 	f_name	varchar(50)	utf8_unicode_ci		���	���		 	 	 	 	 	 	 
 	kaf	varchar(100)	utf8_unicode_ci		���	���		 	 	 	 	 	 	 
 	place	varchar(100)	utf8_unicode_ci		���	���		 	 	 	 	 	 	 
 	who	varchar(50)	utf8_unicode_ci		���	���		 	 	 	 	 	 	 
 	h_addr	varchar(100)	utf8_unicode_ci		���	���		 	 	 	 	 	 	 
 	city	varchar(20)	utf8_unicode_ci		���	���		 	 	 	 	 	 	 
 	country	varchar(20)	utf8_unicode_ci		���	���		 	 	 	 	 	 	 
 	index	varchar(10)	utf8_unicode_ci		���	���		 	 	 	 	 	 	 
 	h_tel	varchar(20)	utf8_unicode_ci		���	���		 	 	 	 	 	 	 
 	w_tel	varchar(20)	utf8_unicode_ci		���	���		 	 	 	 	 	 	 
 	email	varchar(30)	utf8_unicode_ci		���	���		 	 	 	 	 	 	 
 	r_name	varchar(200)	utf8_unicode_ci		���	���		 	 	 	 	 	 	 
 	lang	varchar(20)	utf8_unicode_ci		���	���		 	 	 	 	 	 	 
 	file_upl	varchar(256)	utf8_unicode_ci		���	���		 
 	*/
		
		$_POST['science'] 	= $eng->Burn($_POST['science'],50);
		
		$_POST['email'] 	= preg_replace('/[\`\'\\\"\<\> ]/','',$_POST['email']);
		
		$_POST['surname'] 	= $eng->Burn($_POST['surname'],50);
		
		$_POST['name']		= $eng->Burn($_POST['name'],50);
		
		$_POST['f_name'] 	= $eng->Burn($_POST['f_name'],50);
		
		$_POST['kaf'] 		= $eng->Burn($_POST['kaf'],100);
		
		$_POST['place'] 	= $eng->Burn($_POST['place'],100);
		
		$_POST['who'] 		= $eng->Burn($_POST['who'],50);
		
		$_POST['h_addr'] 	= $eng->Burn($_POST['h_addr'],100);
		
		$_POST['city'] 		= $eng->Burn($_POST['city'],20);
		
		$_POST['country'] 	= $eng->Burn($_POST['country'],20);
		
		$_POST['index'] 	= $eng->Burn($_POST['index'],10);
		
		$_POST['nova_poshta_number'] 	= $eng->Burn($_POST['nova_poshta_number'],10);
		
		$_POST['h_tel']		= $eng->Burn($_POST['h_tel'],20);
		
		$_POST['w_tel'] 	= $eng->Burn($_POST['w_tel'],20);
	
		$_POST['r_name'] 	= $eng->Burn($_POST['r_name'],200);
		
		$_POST['lang']		= $eng->Burn($_POST['lang'],20);
		
	}
	
		function transliterate($input){
		$gost = array(
   "�"=>"YE","�"=>"I","�"=>"G","�"=>"i","�"=>"-","�"=>"ye","�"=>"g",
   "�"=>"A","�"=>"B","�"=>"V","�"=>"G","�"=>"D",
   "�"=>"E","�"=>"YO","�"=>"ZH",
   "�"=>"Z","�"=>"I","�"=>"J","�"=>"K","�"=>"L",
   "�"=>"M","�"=>"N","�"=>"O","�"=>"P","�"=>"R",
   "�"=>"S","�"=>"T","�"=>"U","�"=>"F","�"=>"X",
   "�"=>"C","�"=>"CH","�"=>"SH","�"=>"SHH","�"=>"'",
   "�"=>"Y","�"=>"","�"=>"E","�"=>"YU","�"=>"YA",
   "�"=>"a","�"=>"b","�"=>"v","�"=>"g","�"=>"d",
   "�"=>"e","�"=>"yo","�"=>"zh",
   "�"=>"z","�"=>"i","�"=>"j","�"=>"k","�"=>"l",
   "�"=>"m","�"=>"n","�"=>"o","�"=>"p","�"=>"r",
   "�"=>"s","�"=>"t","�"=>"u","�"=>"f","�"=>"x",
   "�"=>"c","�"=>"ch","�"=>"sh","�"=>"shh","�"=>"",
   "�"=>"y","�"=>"","�"=>"e","�"=>"yu","�"=>"ya",
   " "=>"_","�"=>"_",","=>"_","!"=>"_","@"=>"_",
   "#"=>"-","$"=>"","%"=>"","^"=>"","&"=>"","*"=>"",
   "("=>"",")"=>"","+"=>"","="=>"",";"=>"",":"=>"",
   "'"=>"","\""=>"","~"=>"","`"=>"","?"=>"","/"=>"",
   "\\"=>"","["=>"","]"=>"","{"=>"","}"=>"","|"=>""
  );
 $input=urldecode($input);
return strtr($input, $gost);
}

?>