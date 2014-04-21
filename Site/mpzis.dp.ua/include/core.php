<?php

	include ("stdfx.php");
	include ("convert.php");
    //include ("func.php");


class Core
{
///////////////////////////////////////////////////////////////////////////////////
////ПЕРЕМЕННЫЕ
	// ПЕРЕМЕННЫЕ ЯДРА
	var $conf_path="admin/conf.ini";


	//Параметры от пользователя через GET
	var $u_args;

	//Параметры из тэмплайта для модуля
	var $tpl_args;

    // Параметры из ядра
    var $c_args;

    // Переменные базы данных SQL
	// Название таблицы со структурой сайта
	var $tab_struct;
	// Название таблицы с наполнением сайта
	var $tab_main ;

	var $host;
	var $db;
	var $login;
	var $pass;


///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////

   // Конструктор
	function Core($path="")
	{
		if($path!=""){
         	$eng = new Engine;
         	$this->c_args = $eng->InitConf($path);
         }
		//print "Core:";
		//print_r($this->c_args);
    }

    //Подключение всех модулей
	function include_all()
	{
       $dir = $this->GetModulesDir();

	   $h   = opendir($dir);

		while ($file = readdir($h))
		{
				if(preg_match("/mod_/",$file))
				{
						//print ($dir."/".$file."/mod_$arr[1].php");
						if(is_file($dir."/".$file."/$file.php"))
							include($dir."/".$file."/$file.php");		
				}				
		}

	}

	// Определяет какую страницу нужну отображать, передает парметры от пользователя
	function init()
	{
		$sql = new cMysql($this->c_args["sql_host"],$this->c_args["sql_db"],$this->c_args["sql_login"],$this->c_args["sql_pass"]);

		unset($this->u_args);
		
		
		$url= $_SERVER['REQUEST_URI'];
		$url=str_replace($this->c_args['root'], '',$url);
		if (strlen($url)<2)$url='/index/';
		$F=false;

		$urla=explode('/',$url);
		for ($i=1;$i<count($urla);$i++)
		{
		 if ($urla[$i]!='')
		 {
		 	if($i==1)
			 {		
		  		$this->u_args["page"] = $urla[$i];
		  		$F = true;
		 	 }else{
		  		$j=$i-1;
		  		$this->u_args["p$j"] = $urla[$i];
		 	 }
		 }
		}
		
		
		//print_r($this->u_args);

		$page = !$F ? "index" : $this->u_args["page"];
//Здесь был ekwo!!
 		$s = "SELECT * from " . $this->c_args["sql_struct_tab"] . " where `c_id`='$page'";
 		
 		//echo $s;
        $ret = $sql->query($s);
      if(mysql_numrows($ret)>0){
   		if(mysql_result($ret,0,'c_type')=='alias')
       {
        $template=mysql_result($ret,0,'c_value');
        $s = "SELECT * from " . $this->c_args["sql_struct_tab"] . " where `c_id`='{$template}'";
        $ret = $sql->query($s);
        if (@mysql_numrows($ret)>0)
        {
       		$template=mysql_result($ret,0,'c_value');
       	  $this->u_args['page']=mysql_result($ret,0,'c_id');
       	}else{
          $ret = $this->GetTemplatesDir() ."/404.tpl";
         }
       }
   		else $template=mysql_result($ret,0,'c_value');
   		}else{
        $ret = $this->GetTemplatesDir() ."/404.tpl";
       }

		if(!isset($template))
		{
			$ret = $this->GetTemplatesDir() ."/404.tpl";
		}else{

   		$ret = $this->GetTemplatesDir() ."/".$template;
      }
        return $ret;
	}



    //Главная ф-ция в ядре . Обрабатывает шаблон
         // if($str = preg_match("/ *\[ *chili [+[a-zA-Z]* *= *[a-zA-Z]*]* * \]/",$file[$i],$matches))



	function parser($tpl_path)
	{
       // print "parset($tpl_path)<br>";
		$i;
				
        $file 	= file($tpl_path);
        $n 		= count($file);

        for($i=0;$i<$n;$i++)
        {
			/*------------------------ ВЫПОЛНЯЕМ ПХП-СЦЕНАРИЙ ИЗ ШАБЛОНА -----------------------------*/
			
			if(preg_match('|\?php|',$file[$i]))
				$i = $this->EvalScript($file,$i);
							
			if($str = strstr($file[$i],"[chili"))
			//if($str = preg_match("/ *\[ *chili +\w *= *(\d)/",$file[$i],$matches))
			{
 				$mod = $this->ParseChiliTag($str);
 				$function = "mod_" . $mod;
				
				/*----------------------- НЕ ПОДКЛЮЧАЕМ МОДУЛИ АДМИНКИ -------------------------------------*/
				if(isset($this->tpl_args['obj']) AND $this->tpl_args['obj'] != '' AND $this->tpl_args['obj'] == 'admin')
					continue; 
 				
				 $ret = $function ($this->u_args,$this->tpl_args,$this->c_args);

			}else{

        		print $file[$i];

            }
        }
	}
	
function EvalScript($arr,$i)
{
	$j = $i+1;
	
				
	while(!preg_match('|\?\>|',$arr[$j]))
	{		
		//print "in $i ";
		$ev .= 	$arr[$j] ."\n";
		$j++;
	}
				
	eval($ev);
	//print "'".$ev."'";		
	return ++$j;
}

	function ParseChiliTag($str)
	{
		unset($this->tpl_args);

 		$str = ereg_replace(" +", " ", $str);
  		$str = ereg_replace(" *= *", "=", $str);
  		$str = ereg_replace(" *] *", "]", $str);


		//$str = ereg_replace("= ", "=", $good);
 		//print "<h1>str = '$str'</h1>";

    	$end = strpos($str,"]");

    	if($str[6] == ' ') 	$n = 7;
    	else				$n = 6;

    	$buff1 = substr($str,$n,$end-$n);

 		//print "<h1>buff1 = '$buff1'</h1><br>";

        if($buff1 != "")
	  	{
	        $args = split(" ",$buff1);

	   		while(list($key,$value) = each($args))
	   		{
	   			list($k1,$v1) = split("=",$value);
	   			//print "k1 = $k1 v1 = $v1<br>";

	   			$this->tpl_args[$k1] = $v1;
	   		}
			////////////////////
        }else{
        	$this->tpl_args["NULL"] = TRUE;
        }


   		$str = ereg_replace(" +","", $str);
   		//print "<h1>str = '$str'</h1>";
   		$start = strpos($str,"]") + 1;
   		$end= strpos($str,"[/");

   		$buff2 = substr($str,$start,$end-$start);

    	//print "<h1>$buff2</h1>";

    	return $buff2;
	}


    ///////////////////////////////////////////////////////////////////////////////////////////////
    function GetModulesDir()
    {
       $sql = new cMysql($this->c_args["sql_host"],$this->c_args["sql_db"],$this->c_args["sql_login"],$this->c_args["sql_pass"]);

	   $s = "Select `c_value` from ". $this->c_args["sql_struct_tab"] ." where `c_id`='modules_path'";

	   $ret = $sql->query($s);

	   $res =  mysql_fetch_row($ret);

	   return $res[0];
    }

    function GetTemplatesDir()
    {
       $sql = new cMysql($this->c_args["sql_host"],$this->c_args["sql_db"],$this->c_args["sql_login"],$this->c_args["sql_pass"]);

	   $s = "Select `c_value` from ". $this->c_args["sql_struct_tab"] ." where `c_id`='templates_path'";

	   $ret = $sql->query($s);

	   $res =  mysql_fetch_row($ret);

	   return $res[0];
    }

}




?>