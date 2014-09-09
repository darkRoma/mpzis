<?php
      function mod_register($u,$tpl,$c)
      {
      	
       switch($tpl['cmd'])
       {
       	case "form":
       		mr_form_out();
       	break;
       	case "geo": // geoogr
       		mr_geo_out($c);
       	break;
       	case "list": // spisok 
       		mr_list_out($c);
       	break;
       	
       }
       }
    
	function mr_geo_out($c)
	{
		
		$sql 	= new cMysql($c["sql_host"],$c["sql_db"],$c["sql_login"],$c["sql_pass"]);
		
		$tab = "mpzis_register";
		
		$s = "select city from $tab";
		
		$ret = $sql->query($s,false,'UTF8');
		
		print "<div class = 'reg_city'>";
		
		while($row = mysql_fetch_assoc($ret))
		{
			$flag = false;
			for($i=0;$i<count($mas);$i++)
			{
			$value=$mas[$i];
				if($value == $row["city"])
					$flag = true;	
			} 
			
			if(!$flag)
			{
				$mas[] = $row["city"];
				
				$s 		= "select count(*) from $tab where `city` = '{$row["city"]}'";
				$count 	= $sql->query($s,1,'UTF8'); 
				
				print "<div class='geo_city'>{$row["city"]}({$count["count(*)"]})</div>";
			}
			//print_r($count);
		}
		
		print "</div>";
	}
	
	function mr_list_out($c)
	{
		$sql 	= new cMysql($c["sql_host"],$c["sql_db"],$c["sql_login"],$c["sql_pass"]);
		
		$tab = "mpzis_register";
		
		$s = "select * from $tab where `OK` = 1";
		
		$ret = $sql->query($s,false,'UTF8');
		
		print "<div class = 'reg_list'>";
		
		while($row = mysql_fetch_assoc($ret))
		{
			print "<div>";
			
			print "<b>{$row["surname"]} ";
			print "{$row["name"]} ";
			print "{$row["f_name"]}</b> (";
			print "{$row["city"]})";
			
			print "</div>";
		}
		
		print "</div>";
			
	}
	
	   
    
    function mr_form_out(){
		if($_COOKIE["language"] === "ru") 	
       {
        print "<form id='F' action='modules/mod_register/send.php' method='post' enctype='multipart/form-data'><fieldset>
		
		<legend>Регистрационная карточка</legend>
        
    <label for='science'>Научная степень, ученое звание</label>
    <input type='text' value='' class='required' id='science' name='science'>
    <div class='clear'></div>
    
    <label for='surname'>Фамилия</label>
    <input type='text' value='' id='surname' class='required' name='surname'>
    <div class='clear'></div>
    
    <label for='name'>Имя</label>
    <input id='name' type='text' value='' class='required' name='name'>
    <div class='clear'></div>
    
    
    <label for='f_name'>Отчество</label>
    <input id='f_name' type='text' value='' class='required' name='f_name'>
    <div class='clear'></div>
    
	<fieldset>
	<legend>Место работы</legend>
    
	<label for='kaf'>Кафедра</label>
    <input id='kaf' type='text' value='' class='required' name='kaf'>
    <div class='clear'></div>
    
    <label for='place'>Учреждение</label>
    <input id='place' type='text' value=''class='required' name='place'>
    <div class='clear'></div>
    
    <label for='who'>Должность</label>
    <input id='who' type='text' value='' class='required' name='who'>
    <div class='clear'></div>
    </fieldset>
    
    <label for='h_addr'>Домашний адрес</label>
    <input id='h_addr' type='text' value='' class='required' name='h_addr'>
    <div class='clear'></div>
    
    <label for='city'>Город</label>
    <input id='city' type='text' value='' class='required' name='city'>
    <div class='clear'></div>
    
    <label for='country'>Страна</label>
    <input id='country' type='text' value='' class='required' name='country'>
    <div class='clear'></div>
    
    <label for='index'>Почтовый индекс</label>
    <input id='index' type='text' value='' class='required' name='index'>
    <div class='clear'></div>
	
	
	<label for='nova_poshta_number'>Номер отделения Новой Почты</label>
    <input id='nova_poshta_number' type='text' value='' class='required' name='nova_poshta_number'>
    <div class='clear'></div>
    
    <label for='h_tel'>Домашний телефон (код города)</label>
    <input id='h_tel' type='text' value='' class='required' name='h_tel'>
    <div class='clear'></div>
    
    <label for='w_tel'>Рабочий телефон</label>
    <input id='w_tel' type='text' value='' name='w_tel'>
    <div class='clear'></div>
    
    <label for='email'>Электронный адрес (email)</label>
    <input id='email' type='text' value='' class='required email' name='email'>
    <div class='clear'></div>
    
    <label for='r_name'>Название доклада</label>
    <input id='r_name' type='text' value='' class='required' name='r_name'>
    <div class='clear'></div>
    
    <label for='section'>Секция</label>
    <select size='1' name='section' id = 'section' class='required'>
    <option value=''>(Выберите)</option>
    <option value='Нейронечіткі технології'>Нейронечеткие технологии</option>
    <option value='Експертні системи та системи, що навчають'>Экспертные системы и системы, которые обучают</option>
    <option value='Математичне і програмне забезпечення систем штучного інтелекту'>Математическое и программное обеспечение систем искусственного интеллекта</option>
    <option value='Інтелектуальні системи прийняття рішень'>Интеллектуальные системы принятия решений</option>
    <option value='Інженерія знань'>Инженерия знаний</option>
    <option value='Розпізнавання образів'>Распознавание образов</option>
    <option value='Використання інтелектуальних систем у навчальному процесі'>Использование интеллектуальных систем в учебном процессе</option>
    <option value='Інформаційні технології обробки даних для прийняття рішень'>Информационные технологии обработки данных и принятия решений</option>
    <option value='Системний аналіз складних систем різної природи'>Системный анализ сложных систем разной природы</option>
    <option value='Інформаційні технології в органах державної влади  та місцевого самоврядуванняь'>Информационные технологии в органах государственной власти и местного самоуправления</option>
    </select>
    <div class='clear'></div>
    
    <label for='rep_type'>Тип доклада</label>
    <select size='1' name='rep_type' id = 'rep_type' class='required'>
    <option value=''>(Выберите)</option>
    <option value='Пленарна'>Пленарна</option>
    <option value='Секційна'>Секционная</option>
    <option value='Стендова'>Стендовая</option>
    </select>
    <div class='clear'></div>
    
    <label for='lang'>Язык</label>
    <select id='lang' type='text' value='' name='lang' class='required'>
      <option value='Російська'>Русский</option>
      <option value='Українська'>Українська</option>
      <option value='Англійська'>Английский</option>
    </select>
    <div class='clear'></div>
    <span style='background: #ccc;display: block;'>При регистрации необходимо добавить файл с тезисами Вашего доклада, чтобы организаторы конференции смогли вовремя включить их в сборник тезисов и опубликовать его. <a href='http://mpzis-dnu.dp.ua/conditions/'>Условия оформления тезисов</a></span>
    	<div class='clear'></div>
    <label for='file_upl'>Добавить файл с тезисами</label><input type='file' size='20' name='file_upl' id='file_upl' class='required'>
    
    	<div class='clear'></div>
    <center><strong>Все поля обязательные</strong></center>
    <center><input type='submit' class='submit' value = 'Зарегистрироваться' title = 'Зарегистрироваться' class='send'></center>
	</form>";
      }

      else
      {

        print "<form id='F' action='modules/mod_register/send.php' method='post' enctype='multipart/form-data'><fieldset>
        
        <legend>Реєстраційна картка</legend>
        
    <label for='science'>Науковий ступінь, вчене звання</label>
    <input type='text' value='' class='required' id='science' name='science'>
    <div class='clear'></div>
    
    <label for='surname'>Прізвище</label>
    <input type='text' value='' id='surname' class='required' name='surname'>
    <div class='clear'></div>
    
    <label for='name'>Ім`я</label>
    <input id='name' type='text' value='' class='required' name='name'>
    <div class='clear'></div>
    
    
    <label for='f_name'>По-батькові</label>
    <input id='f_name' type='text' value='' class='required' name='f_name'>
    <div class='clear'></div>
    
    <fieldset>
    <legend>Місце роботи</legend>
    
    <label for='kaf'>Кафедра</label>
    <input id='kaf' type='text' value='' class='required' name='kaf'>
    <div class='clear'></div>
    
    <label for='place'>Установа</label>
    <input id='place' type='text' value=''class='required' name='place'>
    <div class='clear'></div>
    
    <label for='who'>Посада</label>
    <input id='who' type='text' value='' class='required' name='who'>
    <div class='clear'></div>
    </fieldset>
    
    <label for='h_addr'>Домашня адреса</label>
    <input id='h_addr' type='text' value='' class='required' name='h_addr'>
    <div class='clear'></div>
    
    <label for='city'>Місто</label>
    <input id='city' type='text' value='' class='required' name='city'>
    <div class='clear'></div>
    
    <label for='country'>Країна</label>
    <input id='country' type='text' value='' class='required' name='country'>
    <div class='clear'></div>
    
    <label for='index'>Поштовий індекс</label>
    <input id='index' type='text' value='' class='required' name='index'>
    <div class='clear'></div>
    
    
    <label for='nova_poshta_number'>Номер відділення Нової Пошти</label>
    <input id='nova_poshta_number' type='text' value='' class='required' name='nova_poshta_number'>
    <div class='clear'></div>
    
    <label for='h_tel'>Домашній телефон (код міста)</label>
    <input id='h_tel' type='text' value='' class='required' name='h_tel'>
    <div class='clear'></div>
    
    <label for='w_tel'>Робочий телефон</label>
    <input id='w_tel' type='text' value='' name='w_tel'>
    <div class='clear'></div>
    
    <label for='email'>Електронна адреса (email)</label>
    <input id='email' type='text' value='' class='required email' name='email'>
    <div class='clear'></div>
    
    <label for='r_name'>Назва доповіді</label>
    <input id='r_name' type='text' value='' class='required' name='r_name'>
    <div class='clear'></div>
    
    <label for='section'>Cекція</label>
    <select size='1' name='section' id = 'section' class='required'>
    <option value=''>(Оберіть)</option>
    <option value='Нейронечіткі технології'>Нейронечіткі технології</option>
    <option value='Експертні системи та системи, що навчають'>Експертні системи та системи, що навчають</option>
    <option value='Математичне і програмне забезпечення систем штучного інтелекту'>Математичне і програмне забезпечення систем штучного інтелекту</option>
    <option value='Інтелектуальні системи прийняття рішень'>Інтелектуальні системи прийняття рішень</option>
    <option value='Інженерія знань'>Інженерія знань</option>
    <option value='Розпізнавання образів'>Розпізнавання образів</option>
    <option value='Використання інтелектуальних систем у навчальному процесі'>Використання інтелектуальних систем у навчальному процесі</option>
    <option value='Інформаційні технології обробки даних для прийняття рішень'>Інформаційні технології обробки даних для прийняття рішень</option>
    <option value='Системний аналіз складних систем різної природи'>Системний аналіз складних систем різної природи</option>
    <option value='Інформаційні технології в органах державної влади  та місцевого самоврядуванняь'>Інформаційні технології в органах державної влади  та місцевого самоврядування</option>
    </select>
    <div class='clear'></div>
    
    <label for='rep_type'>Тип доповіді</label>
    <select size='1' name='rep_type' id = 'rep_type' class='required'>
    <option value=''>(Оберіть)</option>
    <option value='Пленарна'>Пленарна</option>
    <option value='Секційна'>Секційна</option>
    <option value='Стендова'>Стендова</option>
    </select>
    <div class='clear'></div>
    
    <label for='lang'>Мова</label>
    <select id='lang' type='text' value='' name='lang' class='required'>
      <option value='Українська'>Українська</option>
      <option value='Російська'>Російська</option>
      <option value='Англійська'>Англійська</option>
    </select>
    <div class='clear'></div>
    <span style='background: #ccc;display: block;'>Під час реєстрації необхідно додати файл із тезами Вашої доповіді, щоб організатори конференції змогли вчасно включити їх до збірника тез і опублікувати його. <a href='http://mpzis-dnu.dp.ua/conditions/'>Вимоги до оформлення тез</a></span>
        <div class='clear'></div>
    <label for='file_upl'>Додати файл із тезами</label><input type='file' size='20' name='file_upl' id='file_upl' class='required'>
    
        <div class='clear'></div>
    <center><strong>Усі поля обов'язкові</strong></center>
    <center><input type='submit' class='submit' value = 'Зареєструватися' title = 'Зареєструватися' class='send'></center>
    </form>";

      }


	  }
	  
	  
?>