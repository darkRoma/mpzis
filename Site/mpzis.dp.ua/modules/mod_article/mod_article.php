<?php

/* Модуль вывода статей

Вызов:
[chili count=5]article[/chili]
count - кол-во статей на одной странице
block - кол-во букв в блоке статьи (preview mode)

[chili title=1]article[/chili]
Вывод заголовка

Таблица resources:
v1 = title     		- заголовок страницы
v2 = pic_prev_path  - путь к картинке(preview)
v3 = text           - текс статьи
v4 = author         - автор
v5 = date           - дата
v6 = category       - категория статьи
v7 = page           - раздел для статьи

Сортировка по
по аффтару
по категории
*/

	function mod_article($u,$tpl,$c)
	{
	if (!isset($u['page']))$u['page']='index';
		////// DEBUG/////////////////////////////////////////////////////////////////
       /*
	    print "<br><b>mod_article [u_args]</b><br>";
		while(list($key,$value) = each($u))
   		{

   			print "key = $key val = $value<br>";
   			print "";
        }

        print "<br><b>mod_article [tpl_args]</b><br>";
        while(list($key,$value) = each($tpl))
   		{

   			print "key = $key val = $value<br>";
   			print "";
        }
        */
        ////// END DEBUG
        //////////////////////////////////////////////////////////////////////////////

		// echo title
		if(isset($tpl["title"])){
			m_art_get_title($c,$u);
			return 0;
		}

//print_r($u);
		if ( !isset($u["p1"]) || $u['p1']=='page')  // вывести список
		{

			 m_art_echo_all($u,$tpl,$c);
		}
		else   					//одна статья
		{
            m_art_echo_singl($u,$c) ;
		}
	}

	function m_art_echo_all($u,$tpl,$c)
	{
		
		
		$sql = new cMysql($c["sql_host"],$c["sql_db"],$c["sql_login"],$c["sql_pass"]);
		$tab = $c["sql_main_tab"];


		// GET PARAMS
		$count = $tpl["count"];
		if(!isset($count)) 		$count = 5;
		//ekwo UPD постраничный вывод
    if($u['p1']=='page'){
      $pos=$u['p2'];
    }
// 		$pos = $u["pos"];
 		if(!isset($pos)) 		$pos = 0;

        $sort = $u["sort"];
        if(!isset($sort))	 	$sort_str = "";
        else{		        	$sort_str = " AND `v6`='".$sort . "'";
        						$s_s_next = "&sort=$sort";
        }
        $author = $u["author"];
        if(!isset($author))	 	$auth_str = "";
        else{		        	$auth_str = " AND `v4`='".$author . "'";
        						$s_a_next = "&author=$author";
        }

		///////// END GET PARAMS

		$s = "SELECT `gid` FROM $tab WHERE `type` = 'article' AND `v7`='".$u["page"]."'".$sort_str . $auth_str;
  		$ret = $sql->query($s,false,'UTF8');
        $n = mysql_num_rows($ret);

        //print "<h1>'n=$n'</h1>";

        $s = "SELECT * FROM $tab WHERE `type` = 'article' AND `v7`='".$u["page"]."'".$sort_str . $auth_str .
        													" LIMIT " . $pos * $count.",$count";
  		$ret = $sql->query($s,false,'UTF8');

        //print "<h1>'$s'</h1>";

		// если одна статья - то открываем ее ......
		if(mysql_num_rows($ret) == 1 && $u['p1']!='page')
		{
			
			$row = mysql_fetch_array($ret,MYSQL_ASSOC);
			$u["p1"] = $row['v9'];
			//ekwo UPD (Отмечаем, что это просто главная статья, отличается, от статьи в списке)
			m_art_echo_singl($u,$c,true) ;
			return 0;
		}
          $s = "SELECT * FROM `{$c['sql_struct_tab']}` WHERE c_id ='".$u["page"]."'";
  		$rets = $sql->query($s,false,'UTF8');
  		$t=mysql_result($rets,0,'c_adm_title');
    echo "<div class = \"art_title\" id='main_title'><h1>$t</h1></div>";
		// вывод статьи
		while ($row = mysql_fetch_array($ret,MYSQL_ASSOC))
		{
			 //TITLE
			 if($u["page"]!='index')
             $l  = "".$u["page"]."/".$row["lid"];
        else
        {
              $s="SELECT  `c_id` FROM  `ch_struct` WHERE  `c_adm_title` IN (SELECT  `v1` FROM  `resources` WHERE  `lid` =  '{$row['lid']}')";
              $ttt=$sql->query($s,false,'UTF8');
              $l=mysql_result($ttt,0,'c_id');
        }

			echo "<div class='article'><div class = \"art_title\" ><a href ='$l'>".$row["v1"]."</a></div>\n";
	$ceil++;
	//	echo "<div class='article' id='$ceil'><div class = \"art_title\" >".$row["v1"]."</div>\n";
			// PICTURE (preview)
		//	echo "<div class = \"art_pic\" ><img src=".$row["v2"]."></div>\n";

			/// TEXT
      if ($u['page']=='index') $isind=true; else $isind=false;
			echo "<div class = \"art_text\">". m_art_gettext($row["v3"],$l,$tpl,$isind)."</div></div>\n";

			//AUTHOR
			$l  = "index.php?page=".$u["page"]."&author=".$row["v4"];
		//	echo "<div class = \"art_author\"><a href =$l>".$row["v4"]."</a></div>\n";

            //DATE
			//echo "<div class = \"art_date\">".   	$row["v5"]."</div>\n";
            //CATEGORY
            $l  = "index.php?page=".$u["page"]."&sort=".$row["v6"];
		   // echo "<div class = \"art_category\"><a href =$l>".$row["v6"]."</a></div></div>\n";



			//print "<hr>";
		}

		// Вывод линков на след страницу

		$n = ceil($n / $count);


        if ($n > 1){
        echo "<div class='pages'>Страница: ";
        if ($pos!=0)echo "<a href='".$u["page"]."/page/".($pos-1)."$s_s_next$s_a_next'>Предыдущая</a>";
			for($i = 0;$i<$n;$i++)
			{
			    print "<span class = \"art_search\">\n";
	            if ($i == $pos)  	print "<a class='cur_page'>".($i+1)."</a>";
				else       			print "<a class='page' href='".$u["page"]."/page/$i$s_s_next$s_a_next'>".($i+1)."</a>";
				print "</span>\n";

			}
      if ($pos!=$n-1)echo "<a href='".$u["page"]."/page/".($pos+1)."$s_s_next$s_a_next'>Следующая</a>";
      echo "</div>";
		}
	}

	function m_art_echo_singl($u,$c,$mainPage=false)
	{
        $sql = new cMysql($c["sql_host"],$c["sql_db"],$c["sql_login"],$c["sql_pass"]);
		$tab = $c["sql_main_tab"];

		$s = "SELECT * FROM $tab WHERE `type` = 'article' AND `v9` ='".$u["p1"]."'";
  		$ret = $sql->query($s,false,'UTF8');

		// вывод статьи
		$row = mysql_fetch_array($ret,MYSQL_ASSOC);

			/// TITLE
    //if ($mainPage){
//			echo "<h1 class='tle'>".$row["v1"]."</h1>";
   // }else{
      $s = "SELECT * FROM `{$c['sql_struct_tab']}` WHERE c_id ='".$u["page"]."'";
  		$ret = $sql->query($s,false,'UTF8');
  		$t=mysql_result($ret,0,'c_adm_title');
      echo "<h1 class='tle'>".$row["v1"]."</h1>";
      $text=$row['v3'];
			echo $text;

			      
	}

	function m_art_get_title($c,$u)
	{
		if(!isset($u["p1"])) return false;

		$sql = new cMysql($c["sql_host"],$c["sql_db"],$c["sql_login"],$c["sql_pass"]);
		$tab = $c["sql_main_tab"];

		$s = "SELECT * FROM $tab WHERE `type` = 'article' AND `v9` ='".$u["art"]."'";
  		$ret = $sql->query($s,1,'UTF8');

  		print $ret["v1"];
	}

	function m_art_gettext($text,$href,$tpl,$is_index=false)
	{
		/*$size = $tpl["block"];
		if(!isset($size)) 		$size = 1000;

		$buff = substr($text,0,$size);


        $arr = explode(" ",$buff);


        for($i=0;$i<=count($arr);$i++)
        {
        	 if($i == count($arr) - 3)
        	 {
        	 	//$ret .="<span class = art_text_link><a href=".$href.">";

        	 }

        	 $ret .=$arr[$i] . " ";
        }
        //$ret .= "...</a></span>";
*/

	//	return $ret;
	$ar=explode('<div style="page-break-after: always; "><span style="DISPLAY:none">&nbsp;</span></div>',$text);
	if($is_index){
  return $ar[0];
  }else{
	return '';//$ar[0];
	}
	}



?>