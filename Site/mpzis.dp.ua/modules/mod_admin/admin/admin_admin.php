<?php
	
	include "UpdateUsersDB.php";
	include "UpdateStructDB.php";
	 
	 
	$admin_INFO["name"] 			= "admin";
	$admin_INFO["version"] 			= 1.0;
	$admin_INFO["comment"] 			= "admin for module admin (demo version)";
	$admin_INFO["group"] 			= "top";
	$admin_INFO["function"]			= "admin_admin";
	$admin_INFO["caption"]			= "Управлять";
	
	$admin_INFO["ACCESS"]["r"]["E"] 	= 1;
	$admin_INFO["ACCESS"]["r"]["GE"] 	= 1;
	$admin_INFO["ACCESS"]["w"]["E"] 	= 1;
	$admin_INFO["ACCESS"]["w"]["GE"] 	= 1;
	$admin_INFO["ACCESS"]["a"]["E"] 	= 1;
	$admin_INFO["ACCESS"]["a"]["GE"] 	= 1;
	 
	 
	function admin_admin($u_n,$u_a,$c_n,$c_a)
	{
		$eng 	= new Engine;
     	$u 		= $eng->TransArgs($u_n,$u_a);
     	$c		= $eng->TransArgs($c_n,$c_a);
     	
/*-------------------- DEBUG -------------------------------     	
     	$s = "U(";
		while(list($k,$v) = each($u))
			$s.="[$k]=>`$v` \n";
     	$s .= "<br>";
/*-------------------- DEBUG -------------------------------*/     	
     	
     	
		$cmd = $u['cmd'];
		if(!isset($cmd)) $cmd = 'view';
		
		switch($cmd)
		{
			case "users":
			{
				if($_SESSION['access'] == 'Admin'){
					$l = "'javascript: void(0)' onclick='xajax_adm_adm_AddNewUserForm(\"".$u['result']."\");'";
					$ret .= "<a href=$l)>Add new user</a>";
				}
				
				$ret .= adm_adm_ShowUsers($u);
				break;
			}
			case "struct":
			{
				$ret .= adm_adm_EditStructTable($u);
				break;
			}
			case "view":
			{
				$ret = adm_adm_interface();
				
				break;
			}
		}
		   	
		  	
		   	
		$out .= $s;
		$out .= $ret;   
		  	
		
		//DEBUG
        $_f = fopen("debug_admin.txt","w");
        fwrite($_f,$out);
        fclose($_f);		

     	$obj = new xajaxResponse();
     	$obj->setCharEncoding('windows-1251');
		$obj->addassign($u['result'],'innerHTML',$out);
		return $obj;
	}
	
	/*------------------------------- REGISTER FUNCTIONS ---------------------------*/
	
	$xajax->registerFunction('adm_adm_EditUserForm');
	$xajax->registerFunction('adm_adm_AddNewUserForm');
	$xajax->registerFunction('adm_adm_update__');
	$xajax->registerFunction('adm_adm_DeleteUser');
	
	/*--------------- STRUCT TABLE ---------------------------------------------------*/
	$xajax->registerFunction('adm_adm_EchoPreparedTable');
	$xajax->registerFunction('adm_adm_UpdateStructDb');
?>
<?php
	
	
	function adm_adm_interface()
	{
		$l="'javascript: void(0)' onclick='xajax_parser([\"cmd\",\"obj\"],[\"users\",\"admin\"]);'"; 
		$out .= "<a href=$l>";
		$out .= "Users";
		$out .= "</a><br>";
		
		if($_SESSION['access'] == 'Admin'){
			$l="'javascript: void(0)' onclick='xajax_parser([\"cmd\",\"obj\"],[\"struct\",\"admin\"]);'"; 
			$out .= "<a href=$l>";
			$out .= "Struct table";
			$out .= "</a><br>";
		}
		
		return $out;
	}
	
	

?>