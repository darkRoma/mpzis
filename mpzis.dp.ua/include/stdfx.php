<?php


class cMysql
{
//////VARS ///////////////////////
var $link=0;

var $db 		= "database";
var $host 		= "localhost";
var $login 		= "root";
var $pass		= "";

//////////////////////////////////////////

var $prev_ret;

//////////FUNCTIONS///////////////////


function cMysql($host_,$db_,$login_,$pass_)
{
    $this->db  		= $db_;
    $this->host  	= $host_;
    $this->login  	= $login_;
    $this->pass  	= $pass_;
}


////////// RAW API//////////////////////////////////////////////////////////////////////
function connect()
{

	//$link = msql_connect($host)
	/*
	print "host=".$this->host;
	print "<br>login = ".$this->login;
	print "<br>Pass = ".$this->pass;
	print "<br>";
    */

	if(!  $this->link=mysql_connect($this->host,$this->login,$this->pass))
	{
		echo(DBERR.mysql_error());
		return FALSE;
	}

	return TRUE;
}


function query($sql,$single = false,$char=false)
{

  if($this->connect()){
  	if($char) mysql_query("SET NAMES '$char'"); else mysql_query("SET NAMES 'cp1251'");
	   if(!@mysql_select_db($this->db,$this->link))
	    {
	    	 die("[ERROR cMysql->Query($sql) <== ".mysql_error() ."]");
	    	 return FALSE;
	    }
//		mysql_query("SET NAMES 'UTF8'");
	   if(! $sql_query = mysql_query($sql))
	   {
	   	 die("[ERROR cMysql->Query($sql) <==".mysql_error() . "]");
	   	 return FALSE;
	   }

	   $this->close();
   }
	if( $single )
	{
		$sql_query  = mysql_fetch_array($sql_query,MYSQL_ASSOC);
	}

	$this->prev_ret = $sql_query;

   return $sql_query;
}
function close()
{
	mysql_close($this->link);
}

function NumRows($ret = 0)
{
	 if(!$ret) return msql_num_rows($this->$prev_ret) ;
}

function ListFields($table)
{
	//$res = mysql_query("SHOW COLUMNS FROM `my_table`");
//while ($row = mysql_fetch_array($res)) $col_names[]=$row[0];

	 $ret = $this->query("SHOW COLUMNS FROM `$table`");
	 while ($row = mysql_fetch_array($ret)) $col_names[]=$row[0];
	 return $col_names;
}

////////// RAW API//////////////////////////////////////////////////////////////////////
}
/*---------------------------------------------------------------------------------------------------*/

class Engine
{
	var $conf_path;
    var $conf_a;

////////////////////////////////////////
	function Engine()
	{

	}

	function InitConf($path = '')
	{
		
		if($path != "")
			$this->conf_path = $path;

			$file = file($this->conf_path);

	    	while(list($key, $value) = each($file))
	    	{
	    		$value = substr($value,0,strlen($value)-2);
	    		$a = split('=',$value);

	    		$arr[$a[0]] = $a[1];
	    	}


            while(list($k,$v) = each($arr))
	    		$this->conf_a[$k] 		= $v;

      

        return $this->conf_a;

	}
	/*
		1) Transform assocc array into string '[$k],[$v]'
		2) Transform $k,$v arrays into assocc array  
		For ajax calls.
	*/
	function TransArgs($a,$b="")
	{
        if(empty($b)) // 1)
        {
			$out .= "[";
	
	        $i=0;
			while(list($k,$v) = each($a))
			{
				$keys[$i] ="\"$k\"";
				$vals[$i++] = "\"$v\"";
	
			}
	
			$out .=implode(",",$keys);
			$out .= "],[";
			$out .=implode(",",$vals);
			$out .="]";
		}else{		// 2
			for($i=0;$i<count($a);$i++)
        		$out[ $a[$i] ] = $b[$i];
			
		}

		//print "'$out'";

        return $out;
	}
	
	function PrepareStatArgs($module,$action,$arg)
	{			
		$out['args'] 			= $arg;
		$out['args']['action'] 	= $action;
		$out['args']['type'] 	= $module;
		return $out;
	}
	
	function GetModTable($mod_name,$tab_name)
	{
		$tab = $_SESSION["MODS_DATA"][$mod_name]["TABLES"][$tab_name]["var"];
		
		return $tab;
	}
	
	function GetModVar($mod_name,$var_name)
	{
		$tab = $_SESSION["MODS_DATA"][$mod_name]["VARS"][$var_name]["var"];
		
		return $tab;
	}
	
	function GetModDir($mod_name,$dir_name)
	{
		$tab = $_SESSION["MODS_DATA"][$mod_name]["DIRS"][$dir_name]["var"];
		
		return $tab;
	}
	
	function GetModTableRaw($mod_name,$tab_name)
	{
		
	    $c_args = $this->InitConf();
			
		$sql = new cMysql($c_args["sql_host"],$c_args["sql_db"],
							$c_args["sql_login"],$c_args["sql_pass"]);
		
		$tab = $c_args["sql_main_tab"];
		
		
		$s = "SELECT * FROM $tab where `type`='_install_' and `v1`='$mod_name'";
		$r = $sql->query($s,true);
		
			
		
		if(!empty( $r )) // если есть поле install для модуля...
		{
			// запишем таблицы 
			$name 	= explode(",",$r['v2']);
			$title 	= explode(",",$r['v3']); 
			$vars 	= explode(",",$r['v4']);

			while($n = array_shift($name))
			{
				$t = array_shift($title);
				$v = array_shift($vars);
						
				$data[$mod_name]['TABLES'][$n] = array("name" => $n,"title" => $t,"var" => $v);
			}
		}
		
		return $data[$mod_name]['TABLES'][$tab_name]['var'];
	}
	
	function GetModDirRaw($mod_name,$dir_name)
	{
		
	    $c_args = $this->InitConf();
			
		$sql = new cMysql($c_args["sql_host"],$c_args["sql_db"],
							$c_args["sql_login"],$c_args["sql_pass"]);
		
		$tab = $c_args["sql_main_tab"];
		
		
		$s = "SELECT * FROM $tab where `type`='_install_' and `v1`='$mod_name'";
		$r = $sql->query($s,true);
		
			
		
		if(!empty( $r )) // если есть поле install для модуля...
		{
			// запишем таблицы 
			$name 	= explode(",",$r['v5']);
			$title 	= explode(",",$r['v6']); 
			$vars 	= explode(",",$r['v7']);

			while($n = array_shift($name))
			{
				$t = array_shift($title);
				$v = array_shift($vars);
						
				$data[$mod_name]['DIRS'][$n] = array("name" => $n,"title" => $t,"var" => $v);
			}
		}
		
		return $data[$mod_name]['DIRS'][$dir_name]['var'];
	}
	
	function GetModVarRaw($mod_name,$var_name)
	{
		
	    $c_args = $this->InitConf();
			
		$sql = new cMysql($c_args["sql_host"],$c_args["sql_db"],
							$c_args["sql_login"],$c_args["sql_pass"]);
		
		$tab = $c_args["sql_main_tab"];
		
		
		$s = "SELECT * FROM $tab where `type`='_value_' and `v1`='$mod_name'";
		$r = $sql->query($s,true);
		
			
		
		if(!empty( $r )) // если есть поле install для модуля...
		{
			// запишем таблицы 
			$name 	= explode(",",$r['v2']);
			$title 	= explode(",",$r['v3']); 
			$vars 	= explode(",",$r['v4']);

			while($n = array_shift($name))
			{
				$t = array_shift($title);
				$v = array_shift($vars);
						
				$data[$mod_name]['VARS'][$n] = array("name" => $n,"title" => $t,"var" => $v);
			}
		}
		
		return $data[$mod_name]['VARS'][$var_name]['var'];
	}
	
	
	 // каленным железом выжигаем всю гадость
	function Burn($msg , $N = 0 )
	{
		$str = $msg;
		
		if( $N )
			$str = substr($str, 0, $N);
		
		$str = preg_replace("/[^\w\x7F-\xFF\s]/", " ", $str);
		//$str = trim(preg_replace("/\s(\S{1,2})\s/", " ", ereg_replace(" +", "  "," $str ")));
		$str = ereg_replace(" +", " ", $str);
		
		return $str;
	}
	
}


?>