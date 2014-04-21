<?php

/*--------------------------------------- ВЫБИРАЕТ ДАННЫЕ ИЗ ТАБЛИЦЫ СТАТИСТИКИ ------------------------------------------*/
	function admin_article_statistic($u)
	{
		//DEBUG
		/*
		foreach($args as $k => $v)
		{
			$out .= "[$k] => $v";
		}
		*/
		// END DEBUG
		
		switch($u['action'])
		{
			case "new":
			{
				//type		action	v1	v2	v3	v4	v5	v6
				//article	new		admin	page1	656	6888	ссылка на статью	2008-15-10
				$out .= $u['v8'] . " Пользователь:" .$u['v1'] . " добавил статью в раздел ".$u['v2'] .
																	"<a href=".$u['v5'].">".$u['v4'];
				break;
			}
			case "edit":
			{
				//	article	edit	admin	page1	fdsfsdf	445435	ссылка на статью	2008-10-10
				$out .= $u['v8'] ." Пользователь:".$u['v1']." изменил статью в раздел ".$u['v2'] .
																	"<a href=".$u['v5'].">".$u['v4'];;
				break;
			}
			case "delete":
			{
				//	article	delete	admin	page1	3
				$out .= $u['v8'] ." Пользователь:".$u['v1']." удалил статью в раздел ".$u['v2'];
																	
				break;
			}
		}
		
		
		return $out;
	} 
?>