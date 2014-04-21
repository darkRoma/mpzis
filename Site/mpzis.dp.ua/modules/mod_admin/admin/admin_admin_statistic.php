<?php

/*--------------------------------------- ВЫБИРАЕТ ДАННЫЕ ИЗ ТАБЛИЦЫ СТАТИСТИКИ ------------------------------------------*/
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
				$out .= $u['v8'] . " Пользователь:" .$u['v1'] . " добавил нового пользователя ".$u['v2'] .
																	" с правами ".$u['v4'];
				break;
			}
			case "user_edit":
			{
				//admin	user	55hhfdfd	Editor
				$out .= $u['v8'] ." Пользователь:".$u['v1']." изменил данные о ".$u['v2'];
				break;
			}
			case "user_delete":
			{
				//admin	13
				$out .= $u['v8'] ." Пользователь:".$u['v1']." удалил пользователя ".$u['v2'];
																	
				break;
			}
		}
		
		
		return $out;
	} 
?>