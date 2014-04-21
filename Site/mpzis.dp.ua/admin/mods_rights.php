<?php
// Этот файл - часть ядра. В нем описаны функции связанные с правами пользователей для модулей ситемы

	function kern_ret($x,$mod,$acc,$p)
	{
		$l = "xajax_kern_UpdateModsAccess('$mod','$acc','$p');";
		if($x == 0) return "<input type='checkbox' onclick=$l>";
		else		return "<input type='checkbox' checked onclick=$l>";
	}
	
	function kern_UpdateModsAccess($mod,$acc,$p)
	{
		$eng = new Engine();
		$c_args = $eng->InitConf("conf.ini");
		
		$v = $_SESSION["MODS_DATA"][$mod]["ACCESS"][$acc][$p];
		
		if( $v )	$_SESSION["MODS_DATA"][$mod]["ACCESS"][$acc][$p] = 0;
		else		$_SESSION["MODS_DATA"][$mod]["ACCESS"][$acc][$p] = 1;
			
		$val = $_SESSION["MODS_DATA"][$mod]["ACCESS"];
		
		// update db....
		
		$sql = new cMysql($c_args["sql_host"],$c_args["sql_db"],$c_args["sql_login"],$c_args["sql_pass"]);
		$tab = $c_args["sql_main_tab"];
				
		$s = "UPDATE $tab SET `v1` = '{$val[r][E]}', `v2` = '{$val[r][GE]}',`v3`='{$val[w][E]}',
											`v4`='{$val[w][GE]}',`v5`='{$val[a][E]}',`v6`='{$val[a][GE]}'
    		  			WHERE `type` = '_access_' and `v7` = '$mod'";					   
				//print $s;
				
				$sql->query($s);
	
		$response = new xajaxResponse();
		
		//$response->addalert($mod);
		
		return $response;
	}
	
	
	// Отображает список модулей в системе 		
	function kern_DispModuleList()
	{
		$MODS = $_SESSION["MODS_DATA"] ;
		
		print_r($MODS['admin']['DIRS']);
		
		
		
		foreach($MODS as $val)
		{
			$INST_FLAG = false;
			
			
			$out .= "<div id = 'mods_li'>";
			
			$out .= "<div id = 'mods_name'>{$val["title"]} ({$val["name"]})</div>";
			$out .= "<div id = 'mods_ver'>".$val["version"]."</div>";
			$out .= "<div id = 'mods_comm'>".$val["comment"]."</div>";
			
			
			
		if($_SESSION['access'] == 'Admin')
			{
/*********************************************************************************************************************/
				$out .= "<div id = 'acc_tab_{$val["name"]}' style='display:none'>";
				
				$out .= "<table>";
				$out .= "<tr><td></td><td>Читать</td><td>Писать</td><td>Доступ</td></tr>";
				
	
				$out .= "<tr><td>Редактор</td> <td>".kern_ret($val["ACCESS"]['r']['E'],$val["name"],'r','E')."</td>
											    <td>".kern_ret($val["ACCESS"]['w']['E'],$val["name"],'w','E')." </td>
												 <td>".kern_ret($val["ACCESS"]['a']['E'],$val["name"],'a','E')."</td></tr>";
				
				
				$out .= "<tr><td>Гл. Редактор</td><td>".kern_ret($val["ACCESS"]['r']['GE'],$val["name"],'r','GE')."</td>
												<td>".kern_ret($val["ACCESS"]['w']['GE'],$val["name"],'w','GE')."</td>
												<td>".kern_ret($val["ACCESS"]['a']['GE'],$val["name"],'a','GE')."</td></tr>";
				$out .= "</table>";
				$out .= "</div>"; // END DIV acc_tab_{$val["name"]}

				$l 		= "'javascript: void(0)' onclick=ToggleDiv('acc_tab_{$val["name"]}');";
				
				$out .= "<a href = $l>Права </a>";
				
/*********************************************************************************************************************/
				// интерфейс для изменения полей в базе....
				$out .= "<div id = 'acc_itl_{$val["name"]}' style='display:none'>";//ВЫЕЗДЖАЮЩИЙ ДИВ ДЛЯ МОДУЛЯ
				
				$tables = $val["TABLES"];
					
				if(!empty($tables)){
					
					$out .= "<fieldset>";						// fieldset TABLES
					$out .= "<legend>ТАБЛИЦЫ</legend>";
					
					$i=0;
					$names = "";
					$vals = "";
					foreach($tables as $tab)
					{
						$out .= "<span id = '{$val["name"]}_name_$i' class='' title='{$tab["title"]};{$tab["name"]}'>
								{$tab["title"]}({$tab["name"]})
						</span><br>";
						
						
						$out .= "<input type='text' value='{$tab["var"]}' id='{$val["name"]}_val_$i'>";
						
						$out .= "<br>";
						
						$names .= "'{$val["name"]}_name_$i',";
						$vals  .= "'{$val["name"]}_val_$i',";
						
						$i++;
						$INST_FLAG = true;
					}
					
						$n = trim($names,',');
						$v = trim($vals,',');
					
					$out .= "<input type='button' value='Go' onclick=SaveModsInstall('{$val["name"]}',[$n],[$v])>";	
					
				$out .= "</fieldset>";						// END fieldset TABLES	
				}
				
				
				$dirs = $val["DIRS"];
				if(!empty($dirs)){
					$out .= "<fieldset>";						// fieldset DIRS
					$out .= "<legend>ПУТИ</legend>";
				
					$i=0;
					$names = "";
					$vals = "";
					foreach($dirs as $dir)
					{
						$out .= "<span id = '{$val["name"]}_dir_$i' class='' title='{$dir["title"]};{$dir["name"]}'>
								{$dir["title"]}({$dir["name"]})
						</span><br>";
						
						
						$out .= "<input type='text' value='{$dir["var"]}' id='{$val["name"]}_dir_val_$i'>";
						
						$out .= "<br>";
						
						$names .= "'{$val["name"]}_dir_$i',";
						$vals  .= "'{$val["name"]}_dir_val_$i',";
						
						$i++;
						$INST_FLAG = true;
					}
					
						$n = trim($names,',');
						$v = trim($vals,',');
					
					$out .= "<input type='button' value='Go' onclick=SaveModsInstallDir('{$val["name"]}',[$n],[$v])>";	
				
					$out .= "</fieldset>";						// END fieldset DIRS
				}

				$vars = $val["VARS"];
				if( !empty($vars) ){
					$out .= "<fieldset>";						// fieldset VARS
					$out .= "<legend>ДАННЫЕ МОДУЛЯ</legend>";
				
					$i=0;
					$names = "";
					$vals = "";
					foreach($vars as $var)
					{
						$out .= "<span id = '{$val["name"]}_var_$i' class='' title='{$var["title"]};{$var["name"]}'>
								{$var["title"]}({$var["name"]})
						</span><br>";
						
						
						$out .= "<input type='text' value='{$var["var"]}' id='{$val["name"]}_var_val_$i'>";
						
						$out .= "<br>";
						
						$names .= "'{$val["name"]}_var_$i',";
						$vals  .= "'{$val["name"]}_var_val_$i',";
						
						$i++;
						$INST_FLAG = true;
					}
					
						$n = trim($names,',');
						$v = trim($vals,',');
					
					$out .= "<input type='button' value='Go' onclick=SaveModsInstallVar('{$val["name"]}',[$n],[$v])>";	
				
					$out .= "</fieldset>";						// END fieldset VARS
				}				
				
				
				$out .= "</div>";// END DIV acc_itl_{$val["name"] ВЫЕЗДЖАЮЩИЙ ДИВ ДЛЯ МОДУЛЯ END
/********************************************************************************************************************/				
				if( $INST_FLAG ){
					$l 		= "'javascript: void(0)' onclick=ToggleDiv('acc_itl_{$val["name"]}');";
					$out .= "<a href = $l>Настройки модуля</a>";
				}
			}
			
			$out .= "</div>";
			
		}
		
		$obj = new xajaxResponse();
		$obj->addassign("result","innerHTML",$out);
		
		//$obj->addalert($out);
		$obj->setCharEncoding('windows-1251');
		return $obj;
	}
	
	function kern_SaveModsInstallSett($mod,$n,$v)
	{
		$eng = new Engine();
		$c_args = $eng->InitConf("conf.ini");
      	
      	$sql = new cMysql($c_args["sql_host"],$c_args["sql_db"],$c_args["sql_login"],$c_args["sql_pass"]);
		$tab = $c_args["sql_main_tab"];
        
		$n = trim($n,',');
		$v = trim($v,',');
		
		$_n = explode(",",$n);
		
		//обновляем бд....
		foreach($_n as $e){
			list($temp,$temp2) = explode(';',$e);
				$name_str 	.= $temp2. ","; 
				$title_str 	.= iconv("UTF-8","windows-1251",$temp).",";
				
				$f_n[] = $temp2;
				$f_t[] = iconv("UTF-8","windows-1251",$temp);
			} 
		
		$name_str = trim ($name_str,',');$title_str = trim ($title_str,',');
	
		$s = "UPDATE $tab SET `v2` = '$name_str', `v3` = '$title_str',`v4`='$v'
											
    		  			WHERE `type` = '_install_' and `v1` = '$mod'";
    	
    	$sql->query($s);
		
		
		//обновляем сессию...
		$f_v =explode(',',$v);
		
		$i=0;
		foreach($f_n as $__f){
			$_SESSION["MODS_DATA"][$mod]["TABLES"][$__f] = array("name"=>$__f,"title" => $f_t[$i],"var"=>$f_v[$i]);
			$i++;	
		}
		
		
		//print_r($_SESSION["MODS_DATA"][$mod]["TABLES"]);

		$obj = new xajaxResponse();
		
		//$obj->addassign("result","innerHTML",$out);
		
		//$obj->addalert($s);
		$obj->addscript("xajax_kern_DispModuleList();");
		//$obj->setCharEncoding('windows-1251');
		
		return $obj;
	}
	
	function kern_SaveModsInstallSettDir($mod,$n,$v)
	{
		$eng = new Engine();
		$c_args = $eng->InitConf("conf.ini");
      	
      	$sql = new cMysql($c_args["sql_host"],$c_args["sql_db"],$c_args["sql_login"],$c_args["sql_pass"]);
		$tab = $c_args["sql_main_tab"];
        
		$n = trim($n,',');
		$v = trim($v,',');
		
		$_n = explode(",",$n);
		
		//обновляем бд....
		foreach($_n as $e){
			list($temp,$temp2) = explode(';',$e);
				$name_str 	.= $temp2. ","; 
				$title_str 	.= iconv("UTF-8","windows-1251",$temp).",";
				
				$f_n[] = $temp2;
				$f_t[] = iconv("UTF-8","windows-1251",$temp);
			} 
		
		$name_str = trim ($name_str,',');$title_str = trim ($title_str,',');
	
		$s = "UPDATE $tab SET `v5` = '$name_str', `v6` = '$title_str',`v7`='$v'
											
    		  			WHERE `type` = '_install_' and `v1` = '$mod'";
    	
    	$sql->query($s);
		
		
		//обновляем сессию...
		$f_v =explode(',',$v);
		
		$i=0;
		foreach($f_n as $__f){
			$_SESSION["MODS_DATA"][$mod]["DIRS"][$__f] = array("name"=>$__f,"title" => $f_t[$i],"var"=>$f_v[$i]);
			$i++;	
		}
		
		
		//print_r($_SESSION["MODS_DATA"][$mod]["TABLES"]);

		$obj = new xajaxResponse();
		
		//$obj->addassign("result","innerHTML",$out);
		
		//$obj->addalert($s);
		$obj->addscript("xajax_kern_DispModuleList();");
		//$obj->setCharEncoding('windows-1251');
		
		return $obj;
	}
	
	function kern_SaveModsInstallSettVar($mod,$n,$v)
	{
		$eng = new Engine();
		$c_args = $eng->InitConf("conf.ini");
      	
      	$sql = new cMysql($c_args["sql_host"],$c_args["sql_db"],$c_args["sql_login"],$c_args["sql_pass"]);
		$tab = $c_args["sql_main_tab"];
        
		$n = trim($n,',');
		$v = trim($v,',');
		
		$_n = explode(",",$n);
		
		//обновляем бд....
		foreach($_n as $e){
			list($temp,$temp2) = explode(';',$e);
				$name_str 	.= $temp2. ","; 
				$title_str 	.= iconv("UTF-8","windows-1251",$temp).",";
				
				$f_n[] = $temp2;
				$f_t[] = iconv("UTF-8","windows-1251",$temp);
			} 
		
		$name_str = trim ($name_str,',');$title_str = trim ($title_str,',');
	
		$s = "UPDATE $tab SET `v2` = '$name_str', `v3` = '$title_str',`v4`='$v'
											
    		  			WHERE `type` = '_value_' and `v1` = '$mod'";
    	
    	$sql->query($s);
		
		
		//обновляем сессию...
		$f_v =explode(',',$v);
		
		$i=0;
		foreach($f_n as $__f){
			$_SESSION["MODS_DATA"][$mod]["VARS"][$__f] = array("name"=>$__f,"title" => $f_t[$i],"var"=>$f_v[$i]);
			$i++;	
		}
		
		
		//print_r($_SESSION["MODS_DATA"][$mod]["TABLES"]);

		$obj = new xajaxResponse();
		
		//$obj->addassign("result","innerHTML",$out);
		
		//$obj->addalert($s);
		$obj->addscript("xajax_kern_DispModuleList();");
		//$obj->setCharEncoding('windows-1251');
		
		return $obj;
	}
	
	
	//$xajax->registerFunction('kern_DisplayTable');
	$xajax->registerFunction("kern_DispModuleList");
	$xajax->registerFunction("kern_UpdateModsAccess");
	
	$xajax->registerFunction("kern_SaveModsInstallSett");
	$xajax->registerFunction("kern_SaveModsInstallSettDir");
	$xajax->registerFunction("kern_SaveModsInstallSettVar");
	
?>