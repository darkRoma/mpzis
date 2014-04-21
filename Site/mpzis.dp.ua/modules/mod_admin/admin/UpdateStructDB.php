<?php
	
	function adm_adm_EditStructTable($u)
	{
		
		$out.= adm_adm_EchoStructTable($u);
		
		
		
 		return $out;
 	} 
 	
 	/*--------------------------- ÂÛÂÎÄÈÌ ÒÀÁËÈÖÓ ÍÀ ÝÊÐÀÍ -------------------------------------*/
 	function adm_adm_EchoStructTable($u)
 	{
 		$eng = new Engine();
		$c_args = $eng->InitConf("conf.ini");

		$sql = new cMysql($c_args["sql_host"],$c_args["sql_db"],$c_args["sql_login"],$c_args["sql_pass"]);
		$table = $c_args["sql_struct_tab"];
		
		$s = "SELECT * FROM $table WHERE `c_type`='path'";
		$ret = $sql->query($s);
		
		
		$out .= "<div class = struct_tab><table>";
		while($row = mysql_fetch_assoc($ret))
		{
			$link = "'javascript: void(0)' onclick='xajax_adm_adm_EchoPreparedTable(\"".$row['id']."\",
																					\"".$u['result']."\");'";
			if($row['id'] == $u['changed'])
			{
				$chang = " bgcolor = 'pink'";
			}
			$out .= "<tr $chang><td>".$row['c_adm_title']."</td>
						<td>".$row['c_id']."</td>
						<td><a href=$link>".$row['c_value']."</a></td>
						
					</tr>";
			if($chang) $chang = "";		
				
			
		}
		$out .= "</table></div>";
		$out .= "<div id='adm_site_map'></div>";
		
 		return $out;
 	}
 	
 	function adm_adm_EchoSiteMap($d)
 	{
 		$dir = opendir($d);
	
		while ((($file = readdir($dir))!==false)) {
	 	if ((filetype($d.$file) == 'file')) {
	  		list(,$ext) = explode('.',$file);
	  	
	  	if($ext == 'ini')  	$out .= $d.$file . "<br>";
	 	} 
	 	elseif ((filetype($d.$file) == "dir") && ($file !== ".") && ($file !== "..") && (is_dir($d.$file))) 
		 {
	 	
		 $out.= "$d$file<br>";		
	  	 $out .= adm_adm_EchoSiteMap($d.$file."/");
	 	 }
	 
		}
	
		return $out;
 	}
 	
 	function adm_adm_EchoPreparedTable($id,$result)
 	{
 		$out .= adm_adm_EchoSiteMap('../');
		$out = preg_replace("|\.\.\/(admin/)*|","",$out);
		
		$arr = explode("<br>",$out);
		$out = "";
		
		while($str = array_shift($arr))
		{
			$link = "'javascript: void(0)' onclick='xajax_adm_adm_UpdateStructDb(\"$id\",\"$str\",\"$result\");'";
			$out .= "<a href=$link>$str</a><br>\n";
		}
		

		$obj = new xajaxResponse();
		$obj->addassign('adm_site_map','innerHTML',$out);
		return $obj;
 	}
 	
 	function adm_adm_UpdateStructDb($id,$val,$result)
 	{
 		$eng 	= new Engine();
		$c 		= $eng->InitConf("conf.ini");
		
		$sql = new cMysql($c["sql_host"],$c["sql_db"],$c["sql_login"],$c["sql_pass"]);
		$tab = $c["sql_struct_tab"];
		
		$s = "UPDATE $tab SET `c_value`='$val' WHERE `id`=$id";
 		$sql->query($s);
 		
		 //----------------------------- STATISTIC ----------------------------------------------------//

		/*$arg = $eng->PrepareStatArgs('admin','struct_upd',array( "v1" => $_SESSION['login'],
																		"v2" => $id,
																		"v3" => $val,
																		"v4" => "",
																		"v5" => "",
																		"v6" => ""));
		adm_stat_AddToDB($arg);*/
	//----------------------------- END STATISTIC ----------------------------------------------------//
	
	
 		$u['changed'] = $id;
 		$u['result'] = $result;
 		$out = adm_adm_EchoStructTable($u);
 		
		$obj = new xajaxResponse();
		$obj->setCharEncoding('windows-1251');
		$obj->addassign($result,'innerHTML',$out);
		return $obj;
 	}
	
?>