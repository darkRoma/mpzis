<?php

/*--------------------------------------- �������� ������ �� ������� ���������� ------------------------------------------*/
	function admin_admin_statistic($u)
	{
		//DEBUG
		/*
		foreach($args as $k => $v)
		{
			$out .= " [$k] => $v ";
		}
		*/
		// END DEBUG
		
		
		switch($u['action'])
		{
			case "user_new":
			{
				//	admin	user_new	admin	test	 	Editor	 
				$out .= $u['v8'] . " ������������:" .$u['v1'] . " ������� ������ ������������ ".$u['v2'] .
																	" � ������� ".$u['v4'];
				break;
			}
			case "user_edit":
			{
				//admin	user	55hhfdfd	Editor
				$out .= $u['v8'] ." ������������:".$u['v1']." ������� ������ � ".$u['v2'];
				break;
			}
			case "user_delete":
			{
				//admin	13
				$out .= $u['v8'] ." ������������:".$u['v1']." ������ ������������ ".$u['v2'];
																	
				break;
			}
		}
		
		
		return $out;
	} 
?>