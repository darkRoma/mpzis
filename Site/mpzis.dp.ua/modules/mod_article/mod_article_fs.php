<?php
	function mod_article_fs($str)
	{
		require_once(dirname(__FILE__).'/../../include/sub_tpl_parser.php');
		$tpl['block'] = 300;
		$eng = new Engine();
		$core = $eng->InitConf("admin/conf.ini");
		$str=urldecode($str);
		$sql = new cMysql($core["sql_host"],$core["sql_db"],$core["sql_login"],$core["sql_pass"]);
		$tab = $core["sql_main_tab"];
		
		$s = "SELECT * FROM $tab WHERE `type` = 'article' AND 
								(`v1` LIKE '%$str%' OR `v3` LIKE '%$str%' OR `v6` LIKE '%$str%')";
								
		$ret = $sql->query($s);
		//168	article	9	v1=title	avatar2.jpeg	article	 	2008-16-10	category	page1
		//"SELECT * FROM $table1 WHERE title LIKE '%$good%' or text LIKE '%$good%'";
		while($row = mysql_fetch_assoc($ret))
		{
			//$row['v1'] = ereg_replace($highlight, "<span class = found>\\0</span>", $row['v1']);
		    //$row['v3'] = ereg_replace($highlight, "<span class = found>\\0</span>", $row['v3']);
		    //$row['v6'] = ereg_replace($highlight, "<span class = found>\\0</span>", $row['v6']);
		    
		    $v1=strip_tags($row['v1']);
		    $v3=html_entity_decode(strip_tags($row['v3']));
		    $v6=strip_tags($row['v6']);
		    //$row['v1'] = ereg_replace($highlight, "<font color = red>\\0</font>", $v1);
//		    $row['v3'] = ereg_replace($highlight, "<font color = red>\\0</font>",$v3);
//		    $row['v6'] = ereg_replace($highlight, "<font color = red>\\0</font>", $v6);
		    $arr=explode($str,$v3);
		    if(count($arr)>1)
		    {
        $v3='...'.substr($arr[0],-50).'<b>'.$str.'</b>'.substr($arr[1],0,50).'...';
        $sub_tpl=dirname(__FILE__).'/../../templates/search_search_result.tpl';
      $tplr=new tpl($sub_tpl); 
        $tplr->addValue('SERVER','http://'.$_SERVER['HTTP_HOST'].'/');
        $tplr->addValue('link',"http://{$_SERVER['HTTP_HOST']}/{$row['v7']}/{$row['v9']}");
        $tplr->addValue('text',$v3);
        $tplr->addValue('title',$v1);
        $r=$tplr->parse(true);
        $out.=$r;
		    }
		    
		}
			$s = "SELECT * FROM $tab WHERE `type` = 'portfolio' AND 
								(`v1` LIKE '%$str%' OR `v4` LIKE '%$str%' OR `v3` LIKE '%$str%')";
								
		$ret = $sql->query($s);
		//168	article	9	v1=title	avatar2.jpeg	article	 	2008-16-10	category	page1
		//"SELECT * FROM $table1 WHERE title LIKE '%$good%' or text LIKE '%$good%'";
		while($row = mysql_fetch_assoc($ret))
		{
			//$row['v1'] = ereg_replace($highlight, "<span class = found>\\0</span>", $row['v1']);
		    //$row['v3'] = ereg_replace($highlight, "<span class = found>\\0</span>", $row['v3']);
		    //$row['v6'] = ereg_replace($highlight, "<span class = found>\\0</span>", $row['v6']);
		    
		    $v1=strip_tags($row['v1']);
		    $v3=html_entity_decode(strip_tags($row['v3']));
		    $v6=strip_tags($row['v4']);
		    //$row['v1'] = ereg_replace($highlight, "<font color = red>\\0</font>", $v1);
//		    $row['v3'] = ereg_replace($highlight, "<font color = red>\\0</font>",$v3);
//		    $row['v6'] = ereg_replace($highlight, "<font color = red>\\0</font>", $v6);
		    $arr=explode($str,str_replace('@','',str_replace('*','',$v3)));
		    $arr2=explode($str,$v6);
		    if(count($arr2)>1)
		    {
        $v3='...'.substr($arr2[0],-50).'<b>'.$str.'</b>'.substr($arr2[1],0,50).'...';
        $sub_tpl=dirname(__FILE__).'/../../templates/search_search_result.tpl';
      $tplr=new tpl($sub_tpl); 
        $tplr->addValue('SERVER','http://'.$_SERVER['HTTP_HOST'].'/');
        $tplr->addValue('link',"http://{$_SERVER['HTTP_HOST']}/{$row['v7']}/{$row['v9']}");
        $tplr->addValue('text',$v3);
        $tplr->addValue('title',$v1);
        $r=$tplr->parse(true);
        $out.=$r;
        }else if(count($arr)>1)
		    {
        $v3='...'.substr($arr[0],-50).'<b>'.$str.'</b>'.substr($arr[1],0,50).'...';
        $sub_tpl=dirname(__FILE__).'/../../templates/search_search_result.tpl';
      $tplr=new tpl($sub_tpl); 
        $tplr->addValue('SERVER','http://'.$_SERVER['HTTP_HOST'].'/');
        $tplr->addValue('link',"http://{$_SERVER['HTTP_HOST']}/{$row['v7']}/{$row['v9']}");
        $tplr->addValue('text',$v3);
        $tplr->addValue('title',$v1);
        $r=$tplr->parse(true);
        $out.=$r;
		    }
		    
		}
		
		
		
		return $out;
	}
	
?>