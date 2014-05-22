<?php
	$register_INFO["name"] 				= "register";
	$register_INFO["title"]				= "Модуль Регистрации";
	$register_INFO["version"] 			= 1.0;
	$register_INFO["comment"] 			= "Модуль для управления информацией";
	$register_INFO["group"] 				= "left";
	$register_INFO["function"]			= "admin_register";
	$register_INFO["caption"]			= "&#1059;&#1095;&#1072;&#1089;&#1090;&#1085;&#1080;&#1082;&#1080;";  // button
	
	//$workers_INFO["INSTALL"]["DIRS"][]	= "";
$register_INFO["TABLES"]["workers"]	= array("name"=>"register","title" => "Таблица учасники","var"=>"mpzis_register");

	$register_INFO["ACCESS"]["r"]["E"] 		= 1;
	$register_INFO["ACCESS"]["r"]["GE"] 	= 1;
	$register_INFO["ACCESS"]["w"]["E"] 		= 1;
	$register_INFO["ACCESS"]["w"]["GE"] 	= 1;
	$register_INFO["ACCESS"]["a"]["E"] 		= 1;
	$register_INFO["ACCESS"]["a"]["GE"] 	= 1;

      function admin_register($u_n,$u_a,$c_n,$c_a)
      {
        $eng 	= new Engine;
     	$u 		= $eng->TransArgs($u_n,$u_a);
     	$c		= $eng->TransArgs($c_n,$c_a);
     	$c=parse_ini_file('../admin/conf.ini');
     	
/*-------------------- DEBUG -------------------------------  	
     	$s = "U(";
		while(list($k,$v) = each($u))
			$s.="[$k]=>`$v` \n";
     	$s .= "<br>";
/*-------------------- DEBUG -------------------------------*/  
		
		$out .= $s;
		
		$cmd = $u["cmd"];
		if(empty($cmd)) $cmd = "view";
		
		
		switch( $cmd )
		{
			case "view":
			{
				$out .= reg_DisplayTab($c);
				break;
			}

			case "view_for_print":
			{
				$out .= reg_DisplayTabForPrint($c);
				break;
			}

			case "accept": // принять людей
			{
				$out .= reg_accept($u['id']);
				
				$out .= reg_DisplayTab( $c );
				break;
			}
			
			case "del_quest": //вывод сообщения об удалении
			{
				
				$out .= reg_del_quest($u['id']);
				
				break;
			}
			
			case "del": // удалить из бд
			{
				$out .= reg_delete($u['id']);
				
				$out .= reg_DisplayTab( $c );	
				break;			
			}
			
			case "del_all_quest":
			{
			    $out .= reg_del_all_quest();
				break;
			}

            case "del_all":
            {
				$out .= reg_delete_all();
				
				$out .= reg_DisplayTab( $c );	
				break;
            }

			case "edit_form":
			{
				$out .= reg_EditForm($u['id'],$c);
				break;
			}
			
			case "edit":
			{
				//U([cmd]=>`edit` [obj]=>`register` [science]=>`11111` [surname]=>`gfdgfdgdg` [name]=>`fgdgfdg` [f_name]=>`dfgdfgfg` [kaf]=>`hfhhf` [place]=>`fhgfh` [who]=>`hfghfgh` [h_addr]=>`fghfghgfh` [city]=>`h4htrh` [country]=>`rthrthrt` [index]=>`rthrthtrh` [h_tel]=>`hgfhgf` [w_tel]=>`gfdhdf` [email]=>`fdhfh` [r_name]=>`dfhfhf` [lang]=>`dfhfhfhfd` [section]=>`2222` [reg_type]=>`33333` [result]=>`res_out`
				
				$sql = new cMysql($c["sql_host"],$c["sql_db"],$c["sql_login"],$c["sql_pass"]);
				$tab = "mpzis_register";
				
				$s = "UPDATE $tab SET `science`='{$u["science"]}',`surname`='{$u["surname"]}',`name`='{$u["name"]}',`f_name`='{$u["f_name"]}',`kaf`='{$u["kaf"]}',`place`='{$u["place"]}',`who`='{$u["who"]}',`h_addr`='{$u["h_addr"]}',`city`='{$u["city"]}',`country`='{$u["country"]}',`index`='{$u["index"]}',`nova_poshta_number`='{$u["nova_poshta_number"]}',`h_tel`='{$u["h_tel"]}',`w_tel`='{$u["w_tel"]}',`email`='{$u["email"]}',`r_name`='{$u["r_name"]}',`lang`='{$u["lang"]}',`section`='{$u["section"]}',`rep_type`='{$u["rep_type"]}' 
				WHERE `id` = {$u["id"]}";
//						mysql_query("SET NAMES 'utf8'");
				//$s = iconv("UTF-8","windows-1251",$s);
				$sql->query($s,false,'UTF8');
				
				$out .= reg_DisplayTab( $c );	
				break;
			}
		}
		
		
		$obj = new xajaxResponse();
     	$obj->setCharEncoding('utf-8');
		$obj->addassign($u['result'],'innerHTML',$out);
//$obj->addScript('$(".rounded").corners("5px anti-alias");');
		//$obj->addalert('test');
		return $obj;
		
      }
      
      $xajax->registerFunction('reg_accept');
      $xajax->registerFunction('reg_delete');
      
      
      
      function reg_EditForm( $id,$c )
      {
      	$eng = new Engine();
		$sql = new cMysql($c["sql_host"],$c["sql_db"],$c["sql_login"],$c["sql_pass"]);
		$tab = "mpzis_register";
		
		$s  = "select * from $tab where `id`=$id";
		$ret = $sql->query($s,1,'UTF8');
      	
      	//$out .= "<input id = 'name' type='text' value='{$ret["name"]}'>";
      	
      	/*(science,surname,name,f_name,kaf,place,who,h_addr,city,country,`index`,h_tel,w_tel,email,r_name,lang,file_upl,section,reg_type) 
	values('{$_POST['science']}','{$_POST['surname']}','{$_POST['name']}','{$_POST['f_name']}','{$_POST['kaf']}','{$_POST['place']}','{$_POST['who']}','{$_POST['h_addr']}','{$_POST['city']}','{$_POST['country']}','{$_POST['index']}','{$_POST['h_tel']}','{$_POST['w_tel']}','{$_POST['email']}','{$_POST['r_name']}','{$_POST['lang']}','$file' )";*/
      	
      	
      	$out .= "<fieldset><legend>Реєстраційна картка</legend>
        
    <label for='science'>Науковий ступінь, вчене звання</label>
    <input type='text' value='".$ret['science']."' class='required' id='science' title='Науковий ступінь, вчене звання.' name='science'>
    <div class='clear'></div>
    
    <label for='surname'>Прізвище</label>
    <input type='text' value='".$ret['surname']."' id='surname' title='Прізвище.' class='required email' name='surname'>
    <div class='clear'></div>
    
    <label for='name'>Ім`я</label>
    <input id='name' type='text' value='".$ret['name']."' name='name'>
    <div class='clear'></div>
    
    
    <label for='f_name'>По-батькові</label>
    <input id='f_name' type='text' value='".$ret['f_name']."' name='f_name'>
    <div class='clear'></div>
    

	<label for='kaf'>Кафедра</label>
    <input id='kaf' type='text' value='".$ret['kaf']."' name='kaf'>
    <div class='clear'></div>
    
    <label for='place'>Установа</label>
    <input id='place' type='text' value='".$ret['place']."' name='place'>
    <div class='clear'></div>
    
    <label for='who'>Посада</label>
    <input id='who' type='text' value='".$ret['who']."' name='who'>
    <div class='clear'></div>
    
    <label for='h_addr'>Домашня адреса</label>
    <input id='h_addr' type='text' value='".$ret['h_addr']."' name='h_addr'>
    <div class='clear'></div>
    
    <label for='city'>Місто</label>
    <input id='city' type='text' value='".$ret['city']."' name='city'>
    <div class='clear'></div>
    
    <label for='country'>Країна</label>
    <input id='country' type='text' value='".$ret['country']."' name='country'>
    <div class='clear'></div>
    
    <label for='index'>Поштовий індекс</label>
    <input id='index' type='text' value='".$ret['index']."' name='index'>
    <div class='clear'></div>
    
    <label for='nova_poshta_number'>Номер складу Нової Пошти</label>
    <input id='nova_poshta_number' type='text' value='".$ret['nova_poshta_number']."' name='nova_poshta_number'>
    <div class='clear'></div>
	
    <label for='h_tel'>Домашній телефон (код міста)</label>
    <input id='h_tel' type='text' value='".$ret['h_tel']."' name='h_tel'>
    <div class='clear'></div>
    
    <label for='w_tel'>Робочий телефон</label>
    <input id='w_tel' type='text' value='".$ret['w_tel']."' name='w_tel'>
    <div class='clear'></div>
    
    <label for='email'>Електронна адреса (email)</label>
    <input id='email' type='text' value='".$ret['email']."' name='email'>
    <div class='clear'></div>
    
    <label for='r_name'>Назва доповіді</label>
    <input id='r_name' type='text' value='".$ret['r_name']."' name='r_name'>
    <div class='clear'></div>
    
    <label for='section'>Cекція</label>
    <input id='section' type='text' value='".$ret['section']."' name='section'>
    <div class='clear'></div>
    
    <label for='rep_type'>Тип доповіді</label>
    <input id='rep_type' type='text' value='".$ret['rep_type']."' name='rep_type'>
    <div class='clear'></div>
    
    
    <label for='lang'>Мова</label>
    <input id='lang' type='text' value='".$ret['lang']."' name='lang'>
    </fieldset>
    <div class='clear'></div>";
    
    //(science,surname,name,f_name,kaf,place,who,h_addr,city,country,`index`,h_tel,w_tel,email,r_name,lang,file_upl,section,reg_type) 
    
    	
    	$l="'javascript: void(0)' onclick='xajax_parser([\"cmd\",\"obj\",\"science\",\"surname\",\"name\",\"f_name\",\"kaf\",\"place\",\"who\",\"h_addr\",\"city\",\"country\",\"index\",\"nova_poshta_number\",\"h_tel\",\"w_tel\",\"email\",\"r_name\",\"lang\",\"section\",\"rep_type\",\"id\"],[\"edit\",\"register\",z(\"science\"),z(\"surname\"),z(\"name\"),z(\"f_name\"),z(\"kaf\"),z(\"place\"),z(\"who\"),z(\"h_addr\"),z(\"city\"),z(\"country\"),z(\"index\"),z(\"nova_poshta_number\"),z(\"h_tel\"),z(\"w_tel\"),z(\"email\"),z(\"r_name\"),z(\"lang\"),z(\"section\"),z(\"rep_type\"),\"$id\"]);'";
    	
		$out .= "<a href = $l>Принять</a>";
    	
    	
    	$out .= "&nbsp&nbsp";
    	$l="'javascript: void(0)' onclick='xajax_parser([\"cmd\",\"obj\"],[\"view\",\"register\"]);'";
    	$out .= "<a href = $l>Назад</a>";
    	
    
    
      	return $out;
      }
      
      
      
      function  reg_DisplayTab($c)
      {
      	$eng = new Engine();
		$sql = new cMysql($c["sql_host"],$c["sql_db"],$c["sql_login"],$c["sql_pass"]);
		$tab = "mpzis_register";
		
		$s  = "select * from $tab";
		
		$ret = $sql->query($s,false,'UTF8');
		/*id	int(11)			Нет	Нет		 	 	 	 	 	 
 	science	varchar(50)	utf8_unicode_ci		Нет	Нет		 	 	 	 	 	 
 	surname	varchar(50)	utf8_unicode_ci		Нет	Нет		 	 	 	 	 	 	 
 	name	varchar(50)	utf8_unicode_ci		Нет	Нет		 	 	 	 	 	 	 
 	f_name	varchar(50)	utf8_unicode_ci		Нет	Нет		 	 	 	 	 	 	 
 	kaf	varchar(100)	utf8_unicode_ci		Нет	Нет		 	 	 	 	 	 	 
 	place	varchar(100)	utf8_unicode_ci		Нет	Нет		 	 	 	 	 	 	 
 	who	varchar(50)	utf8_unicode_ci		Нет	Нет		 	 	 	 	 	 	 
 	h_addr	varchar(100)	utf8_unicode_ci		Нет	Нет		 	 	 	 	 	 	 
 	city	varchar(20)	utf8_unicode_ci		Нет	Нет		 	 	 	 	 	 	 
 	country	varchar(20)	utf8_unicode_ci		Нет	Нет		 	 	 	 	 	 	 
 	index	varchar(10)	utf8_unicode_ci		Нет	Нет		 	 	 	 	 	 	 
 	h_tel	varchar(20)	utf8_unicode_ci		Нет	Нет		 	 	 	 	 	 	 
 	w_tel	varchar(20)	utf8_unicode_ci		Нет	Нет		 	 	 	 	 	 	 
 	email	varchar(30)	utf8_unicode_ci		Нет	Нет		 	 	 	 	 	 	 
 	r_name	varchar(200)	utf8_unicode_ci		Нет	Нет		 	 	 	 	 	 	 
 	lang	varchar(20)	utf8_unicode_ci		Нет	Нет		 	 	 	 	 	 	 
 	file_upl	varchar(256)	utf8_unicode_ci		Нет	Нет		 	 	 	 	 	 	 
 	OK	int(11)*/
 	$out .= "<table style='float:left;'>";
			$out2 .= "<table style='float:left;'>";
			$out2.="<tr><td>Файл</td><td></td></tr>";
	$out .= "<tr align=center><td>Фамилия</td><td>Имя</td><td>Отчество</td><td>Должность</td><td>Город</td><td>Email</td></tr>";
		while( $kor = mysql_fetch_assoc($ret) )
		{
			//$out .= "<div class='block_reg'>";
			
			$out .= "<tr align=center>";
			
			$out .= "<td>{$kor["surname"]}</td><td>{$kor["name"]}</td><td>{$kor["f_name"]}</td><td>{$kor["science"]}</td><td>{$kor["city"]}</td><td>{$kor["email"]}</td></tr>";
			/*
			$out .= "<tr><td>Фамилия</td><td><div class=''>{$kor["surname"]}</div></td></tr>";
			$out .= "<tr><td>Имя</td><td><div class=''>{$kor["name"]}</div></td></tr>";	
			$out .= "<tr><td>Отчество</td><td><div class=''>{$kor["f_name"]}</div></td></tr>";
			$out .= "<tr><td>Должность</td><td><div class=''>{$kor["science"]}</div></td></tr>";
			$out .= "<tr><td>Город</td><td><div class=''>{$kor["city"]}</div></td></tr>";
			$out .= "<tr><td>Email</td><td><div class=''>{$kor["email"]}</div></td></tr>";
			
			$out .= "<tr><td></td><td><div class=''>{$kor["kaf"]}</div></td></tr>";
			$out .= "<tr><td></td><td><div class=''>{$kor["place"]}</div></td></tr>";
			$out .= "<tr><td></td><td><div class=''>{$kor["who"]}</div></td></tr>";
			$out .= "<tr><td></td><td><div class=''>{$kor["h_addr"]}</div></td></tr>";
			
			$out .= "<tr><td></td><td><div class=''>{$kor["country"]}</div></td></tr>";
			$out .= "<tr><td></td><td><div class=''>{$kor["index"]}</div></td></tr>";
			$out .= "<tr><td></td><td><div class=''>{$kor["h_tel"]}</div></td></tr>";
			$out .= "<tr><td></td><td><div class=''>{$kor["w_tel"]}</div></td></tr>";
			
			$out .= "<tr><td></td><td><div class=''>{$kor["r_name"]}</div></td></tr>";	
			$out .= "<tr><td></td><td><div class=''>{$kor["lang"]}</div></td></tr>";
			*/
			
			
			$out2.= "<tr><td><a href = {$kor["file_upl"]}>Link</a></td><td>";
			
			if($kor["OK"] == 1) $msg = "OK";
			else				$msg = "Check";
			
			$l="'javascript: void(0)' onclick='xajax_parser([\"cmd\",\"obj\",\"id\"],[\"accept\",\"register\",\"{$kor["id"]}\"]);'"; 
			$out2 .= "<a href=$l>$msg</a>";	
			
			$out2 .= "</td>";
			
			$out2 .= "<td>";
			$l="'javascript: void(0)' onclick='xajax_parser([\"cmd\",\"obj\",\"id\"],[\"del_quest\",\"register\",\"{$kor["id"]}\"]);'"; 
			$out2 .= "<a href = $l> Del</a>";
			
			$out2 .= "</td>";
			$out2 .= "<td>";
			
			$l="'javascript: void(0)' onclick='xajax_parser([\"cmd\",\"obj\",\"id\"],[\"edit_form\",\"register\",\"{$kor["id"]}\"]);'"; 
			$out2 .= "<a href = $l> Edit</a>";
			
			$out2 .= "</td>";
			$out2 .= "</tr>"; // main tr
			
			
			//$out .= "</div>";
		}
		
	$out .= "</table>";

		$l="'javascript: void(0)' onclick='xajax_parser([\"cmd\",\"obj\",\"id\"],[\"del_all_quest\",\"register\",\"{$kor["id"]}\"]);'"; 
		$out2 .= "<a href = $l> Удалить все</a>";

	    $l="'javascript: void(0)' onclick='xajax_parser([\"cmd\",\"obj\",\"id\"],[\"view_for_print\",\"register\",\"{$kor["id"]}\"]);'"; 
		$out2 .= "<a href = $l> Для печати</a>";
      	
        $out2 .= "</table>";
      	return $out.$out2;
      }

      function reg_DisplayTabForPrint( $c )
      {

     	$eng = new Engine();
		$sql = new cMysql($c["sql_host"],$c["sql_db"],$c["sql_login"],$c["sql_pass"]);
		$tab = "mpzis_register";
		
		$s  = "select * from $tab";
		
		$ret = $sql->query($s,false,'UTF8');

 		$out .= "<table style='float:left;border:1px solid black;'>";
		$out2 .= "<table style='float:left;border:1px solid black;'>";

		$out .= "<tr align=center style=\"border:1px solid black;\"><td style=\"border:1px solid black;\">Фамилия</td><td style=\"border:1px solid black;\">Имя</td><td style=\"border:1px solid black;\">Отчество</td><td style=\"border:1px solid black;\">Телефон</td><td style=\"border:1px solid black;\">Город</td><td style=\"border:1px solid black;\">Email</td></tr>";
		while( $kor = mysql_fetch_assoc($ret) )
		{	
			$out .= "<tr align=center>";	
			$out .= "<td style=\"border:1px solid black;\">{$kor["surname"]}</td><td style=\"border:1px solid black;\">{$kor["name"]}</td><td style=\"border:1px solid black;\">{$kor["f_name"]}</td><td style=\"border:1px solid black;\">{$kor["w_tel"]}</td><td style=\"border:1px solid black;\">{$kor["city"]}</td><td style=\"border:1px solid black;\">{$kor["email"]}</td></tr>";
						

			
			$l="'javascript: void(0)' onclick='xajax_parser([\"cmd\",\"obj\",\"id\"],[\"accept\",\"register\",\"{$kor["id"]}\"]);'"; 
			$out2 .= "<a href=$l>$msg</a>";	
			
			$out2 .= "</td>";
			
			$out2 .= "<td style=\"border:1px solid black;\">";
			
			$out2 .= "</td>";
			$out2 .= "</tr>"; // main tr

		}
		
	    $out .= "</table>";
      	
        $out2 .= "</table>";
      	return $out.$out2;
	
      }
      
      function reg_accept( $id )
      {
      	$eng = new Engine;
		$c = $eng->InitConf('conf.ini');
		$sql = new cMysql($c["sql_host"],$c["sql_db"],$c["sql_login"],$c["sql_pass"]);
		$tab = "mpzis_register";
		
		
		$s = "select `OK` from $tab where id = $id";
		
		$ret = $sql->query($s,1,'UTF8');
		
		$ok =  ! $ret['OK'];
		$s = "UPDATE $tab SET `OK` = '$ok' where `id` = $id";
		$sql->query($s,false,'UTF8');
		
		//return $s;	
      }
      
      function reg_delete( $id )
      {
      	$eng = new Engine;
		$c = $eng->InitConf('conf.ini');
		$sql = new cMysql($c["sql_host"],$c["sql_db"],$c["sql_login"],$c["sql_pass"]);
		$tab = "mpzis_register";
		$s = "delete from  $tab where `id` = $id";
		$sql->query($s,false,'UTF8');
		
      }
      
	  function reg_delete_all()
	{
     	$eng = new Engine;
		$c = $eng->InitConf('conf.ini');
		$sql = new cMysql($c["sql_host"],$c["sql_db"],$c["sql_login"],$c["sql_pass"]);
		$tab = "mpzis_register";
		$s = "delete from  $tab";
		$sql->query($s,false,'UTF8');
	}

      function reg_del_quest($id)
      {
      	
      	$out .= "Удаление $id. Вы уверены?";
      	
      	$l="'javascript: void(0)' onclick='xajax_parser([\"cmd\",\"obj\",\"id\"],[\"del\",\"register\",\"$id\"]);'";
      	$out .= "<a href = $l>Да</a>;&nbsp&nbsp";
      	
      	$l="'javascript: void(0)' onclick='xajax_parser([\"cmd\",\"obj\"],[\"view\",\"register\"]);'";
      	$out .= "<a href = $l>Нет</a>";
      	
      	return $out;
		      	
      }

      function reg_del_all_quest()
      {
      	
      	$out .= "Удаление всех участников. Вы уверены?";
      	
      	$l="'javascript: void(0)' onclick='xajax_parser([\"cmd\",\"obj\",\"id\"],[\"del_all\",\"register\",\"$id\"]);'";
      	$out .= "<a href = $l>Да</a>;&nbsp&nbsp";
      	
      	$l="'javascript: void(0)' onclick='xajax_parser([\"cmd\",\"obj\"],[\"view\",\"register\"]);'";
      	$out .= "<a href = $l>Нет</a>";
      	
      	return $out;
		      	
      }
?>