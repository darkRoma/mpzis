<?php
//ini_set('session.save_path','/var/www/mpzis/data/bin-tmp');
  date_default_timezone_set("Europe/Kiev");
//print_r($_SESSION);
require("../include/core.php");
include ('xajax/xajax.inc.php');
//include('ekwo_sessioner.php');
//print_r($_SERVER);
//$c=new SESSION();
	$xajax = new Xajax();
	
//include ('mods_rights.php');	
	
if (session_id() == "") session_start();
//print_r($_SESSION);	

class AdminCore
{
	// ������ ����!
    var $core;

   	//��������� �� ������������ ����� GET
	var $u_args;

	//��������� �� ��������� ��� ������
	var $tpl_args;

    // ��������� �� ����
    var $c_args;
    
    var $MODS_DATA;
    

	function AdminCore()
	{
		$eng = new Engine();

		$this->c_args = $eng->InitConf("conf.ini");

		//print_r($this->c_args);
	}

	function CreateNewCat($link,$adm_title,$template)
	{
			
		$eng 	= new Engine();
		$c 		= $eng->InitConf("conf.ini");

		$sql 	= new cMysql($c["sql_host"],$c["sql_db"],$c["sql_login"],$c["sql_pass"]);
		$table 	= $c["sql_struct_tab"];
		
		$s = "Select `c_value` from ". $c["sql_struct_tab"] ." where `c_id`='templates_path'";
	   	$TD = $sql->query($s,true);
		
		$tpl = "../".$TD['c_value']."/".$template;
		$SOURCE = "../".$TD['c_value']."/template.tpl";
		
		/*
		copy($SOURCE,$tpl);
		// 	id	c_id	c_value	c_type	c_adm_title
		*/
		
		$adm_title = iconv("UTF-8","windows-1251",$adm_title);
		
		$s = "INSERT INTO `$table` (`id`,`c_id`,`c_value`,`c_type`,`c_adm_title`,`v1`)
								VALUES('','$link','$template','category','$adm_title',1)";
		
		$sql->query($s);
		
			
		$obj= new xajaxResponse();
		$obj->addscript("xajax_inter_CreateLeft('LEFT_PANEL');");
		
		//$obj->addscript($s);
		//$obj->addscript($s2);
		//$obj->addscript("xajax_inter_CreateLeft('LEFT_PANEL');");
		return $obj;
	}

	function CreateNewCatForm()
	{
		$eng 	= new Engine();
		$c 		= $eng->InitConf("conf.ini");

		$sql 	= new cMysql($c["sql_host"],$c["sql_db"],$c["sql_login"],$c["sql_pass"]);
		$table 	= $c["sql_struct_tab"];
		
		$s = "Select `c_value` from ". $c["sql_struct_tab"] ." where `c_id`='templates_path'";
	   	$TemplatesDir = $sql->query($s,true);
		
	
			//ekwo upd
			$out .= "<form><fieldset><legend>�������� ����� �������� �����</legend><label for='n_cat_link'>������� ���:</label>";
		
		$l = "document.getElementById(\"n_cat_temp\").value = this.value + \".tpl\";";
		$out .= "<input type=\"text\" id=\"n_cat_link\" name='n_cat_link' onchange='$l' value=\"test\"/><div class='clear'></div>";
		
		$out .= "<label for='n_cat_adm_title'>���������:</label>";
		$out .= "<input type=\"text\" id=\"n_cat_adm_title\" name='n_cat_adm_title'/><div class='clear'></div>";
		
		$out .= "<label for='n_cat_temp'>������:</label>";
		$out .= "<input type=\"text\" title=\"��� ��������� ������������ ������� - �� ����� ������\" id=\"n_cat_temp\" name='n_cat_temp' value=\"test.tpl\"/><div class='clear'></div>";
			
		/*
		$out .= "<div class = 'dir_temp' id='c_cat_dir_temp'>";
		$out .= "<ul>";
			
		$h = opendir("../".$TemplatesDir['c_value']);
		
		while($file = readdir($h))
		{
			if(!is_dir($file))
			{
				$l = "'javascript: void(0)' document.getElementById(\"n_cat_temp\").value=this.title;'";
				$out .= "<li><a href=$l title='$file'>$file</a></li>";
			}
		}
		
		$out .= "</ul>";
		$out .= "</div>";
		
		
		$l = "";
		$out .= "<a href=$l>�������</a><br>\n";
		*/
		
		
		/* mtGetIdVal(\"n_cat_adm_title\") */
		$l = "'javascript: void(0)' onclick='xajax_CreateNewCat(mtGetIdVal(\"n_cat_link\"),
																mtGetIdVal(\"n_cat_adm_title\"),
																mtGetIdVal(\"n_cat_temp\")
		);'";
		$out .= "<a href=$l>�������</a><br>\n";
		
		$obj= new xajaxResponse();
		//$obj->addalert('done');
		
		$obj->addassign('result',"innerHTML",$out);
	$obj->addscript("$('input').each(function(el){
  if (this.title!='')
  {
    var sp=$('<span></span>').attr('innerHTML',this.title).css('position','absolute').css('left',$(this).position().left+$(this).innerWidth()+20).css('top',$(this).position().top).attr('class','form_helper');
    $('#result').append(sp);
  }
});");
	//$obj->addScript('$(".rounded").corners("5px anti-alias");');
		$obj->setCharEncoding('windows-1251');
		
		return $obj;
	}
	
	function adm_WriteDynamicPart($page,$str)
	{
		
		$obj= new xajaxResponse();
		//$obj->addalert($str);	
		$obj->addassign("adm_st_dyn_$page","innerHTML",$str);
		//$obj->addScript('$(".rounded").corners("5px anti-alias");');
		$obj->setCharEncoding('windows-1251');
		return $obj;	
	} 
	
		/*-------------------- ������� ������������� ����� (��)/��������� ��� ������� */
	function SetVisibleCat($gid,$v)
	{
		$eng = new Engine();
		
		$c 	 = $eng->InitConf("conf.ini");
		 
		$sql = new cMysql($c["sql_host"],$c["sql_db"],$c["sql_login"],$c["sql_pass"]);
		$tab = $c["sql_struct_tab"];
		
		$s = "UPDATE $tab SET `v1` = $v WHERE `id` = $gid ";
		$sql->query($s);
		
		$obj = new xajaxResponse();

		//$obj->addassign('article','innerHTML',$s);
		//$obj->addalert($v);
		return $obj;
		
	}
	
	
	function inter_CreateLeft( $result )
		{
			$eng = new Engine;
			
			$c_args = $eng->InitConf('conf.ini');
			
			$sql = new cMysql($c_args["sql_host"],$c_args["sql_db"],$c_args["sql_login"],$c_args["sql_pass"]);
			$tab = $c_args["sql_struct_tab"];

			$s = "Select `c_value` from ". $c_args["sql_struct_tab"] ." where `c_id`='templates_path'";
	   		$TemplatesDir = $sql->query($s,true);

			$s = "SELECT * FROM $tab WHERE `c_type` = 'category'";

			$ret = $sql->query($s);
			
			$out .=  "��������� �����";
	        $out .= "<ul class='rounded structLeft' id = 'SITE_STRUCT_LIST'>";
		/*------------------------- ������� ����� ��������/������ ����� -----------------------------------*/
		
		if($_SESSION['access'] == 'Admin')
  		{
			$l ="'javascript: void(0)' onclick='xajax_CreateNewCatForm();'";
			$outCreatePage .= "<a class='new_page' href=$l>";
			$outCreatePage .= "������� ����� ������";
			$outCreatePage .= "</a>";		
		}
//eo ekwo UPD
		/*------------------------- ����� ������� ����� ��������/������ ����� -----------------------------*/	
		$_i=0;
		while($row = mysql_fetch_array($ret,MYSQL_ASSOC))
		{

		    $link = $TemplatesDir["c_value"]."/".$row["c_value"];
            //$link = preg_replace("/\.\./","../..",$link);
/*-----------------�����!!!!  : ����� � ��������� !!!! ������ � �������� �������� ����� !!!! -----------------------*/
			$link = "../" . $link;

            $page = $row["c_id"];
            //print "$link";
            //print "$page";
			$out .= "<li><nobr><a class='hidden_panel_link' href='#' id='plus_link_left_$_i'></a><a id = 'adm_st_href_$_i' href=";
			// href
			$l = "ch_adm_AllSlideIn($_i);";
			$out .= "'javascript: void(0)' onclick='xajax_parser([\"tpl_path\",\"page\"],[\"$link\",\"$page\"]); '";
			$out .= ">";
			$out .= $row["c_adm_title"];
			$out .= "</a></nobr></li>\n";
			
			/*------------ �������� ������������ ���� ��� ���������� ����� -----------------------*/
				$out .= "<div class='hidden_panel_left' id='adm_st_div_$_i'>";
			/*----------- STATIC PART --------------*/
			$out .= "<div id='adm_st_stat_$page'>";
			
			if($_SESSION['access'] == 'Admin')
			{
				/*------------------------------------ ������� ������ (��)������� ---------------------------*/
				$sl = "SELECT * FROM $tab WHERE `c_id` = '".$page."'";
				$ret1 = $sql->query($sl,1);
				//ekwo UPD
				//allias administrting
				$out.='alias';
				$ekwo_sql="SELECT * FROM $tab WHERE `c_type`='alias' AND `c_value`='$page'";
				$ret2=$sql->query($ekwo_sql);
				if(@mysql_numrows($ret2)>0)
				{
          for($i=0;$i<mysql_numrows($ret2);$i++)
          {
            $alias=mysql_result($ret2,$i,'c_id');
            $alias_vis=mysql_result($ret2,$i,'v1');
            $visible='';
            if($alias_vis==1)$visible='checked';
            $out.="<div><input type='checkbox' onclick='xajax_setAliasVisibility(\"$alias\",this.checked);' value='{$alias}_visible' $visible>$alias<a href='javascript: void(0)' onclick='xajax_removeAlias(\"$alias\")'>X</a></div>";
          }
        }
        $out.="<a href='javascript: void(0);' onclick='$(\"#alias_add_{$page}\").toggle();'>�������� �����</a><div style='display: none;' id='alias_add_{$page}'><label for='alias_add_input_{$page}'>�������� �����:</label><input type='text' value='' name='alias_add_input_{$page}' id='alias_add_input_{$page}'><br />";
				$out.="<a href='javascript: void(0);' onclick='xajax_addAlias(\"$page\",$(\"#alias_add_input_{$page}\").val());'>��������</a></div>";
				//$out.=print_r($ret1);
				//eo ekwo UPD
				$out .= "������:<br>";
				if( $ret1['v1']  ){
						
		            	$l = "onclick='xajax_SetVisibleCat(".$ret1['id'].",0)'";
						$out .=  "<input type=\"radio\" name=\"art_".$ret1['id']."\" value=\"0\" $l> �����<Br>"; 
						$l = "onclick='xajax_SetVisibleCat(".$ret1['id'].",1)'";
						$out .= "<input type=\"radio\" name=\"art_".$ret1['id']."\" checked=\"checked\" value=\"1\" $l>�����<Br>";
						
				}else{
						$l = "onclick='xajax_SetVisibleCat(".$ret1['id'].",0)'";
						$out .= "<input type=\"radio\" name=\"art_".$ret1['id']."\" checked=\"checked\" value=\"0\" $l>�����<Br>";  
						$l = "onclick='xajax_SetVisibleCat(".$ret1['id'].",1)'";
						$out .= "<input type=\"radio\" name=\"art_".$ret1['id']."\" value=\"1\" $l> �����<Br>";
				}
				/*---------------------------������ �������� ������� ---------------------------------------*/
				
				$l = "'javascript: void(0)' onclick='xajax_DeleteCategory({$row["id"]})'";
				$out .= "<a href = $l> ������� ������</a>";
				
			}
			
			
			$out .= "</div>";
						
			$out .= "</div>";
			
			$_i++;
		}
		
        $out .= "</ul>";
        
        $slides = "";
        
    //ekwo UPD  
        /* for($j=0;$j<$_i;$j++)
			{
				$slides .= "mySlide_$j = new Fx.Slide('adm_st_div_".$j."').hide(); ";	
				$slides .= "$('adm_st_href_".$j."').addEvent('click', function(e){ ";
				$slides .= "mySlide_$j.toggle(); ";
				$slides .= "}); ";
				
				/* $('ahref_struct_'+x).addEvent('click', function(e){
		   mySlide[x].toggle();*/
			/*}*/
      	$slides.="$('.hidden_panel_left').hide();";
      	$slides.="$('.hidden_panel_link').click(function(){
          $('#'+this.id.replace('plus_link_left_','adm_st_div_')).toggle('slow');
        });
        

        ";
			//eo ekwo UPD
		/*------------------------------ ������ ������� � ����� ������ ----------------------------*/
		$out.=$outCreatePage;
		$out .= "<ul style='list-style: none'>";
		
		$MODS = $_SESSION["MODS_DATA"];
		
		foreach($MODS as $val)
		{
			if( $val["group"] == "left" AND !empty($val["caption"]))
			{
				$l="'javascript: void(0)' onclick='xajax_parser([\"obj\"],[\"$val[name]\"]);'";
				$out .= "<li><a href = $l> {$val["caption"]}</a></li>";
				
			}
		}
		
		$out .= "</ul>";
        
        $obj = new xajaxResponse();
		$obj->addassign($result,'innerHTML',$out);
		$obj->setCharEncoding('windows-1251');
		//$obj->addScript('$(".rounded").corners("5px anti-alias");');
		$obj->addscript($slides); 
		//$obj->addalert($slides);
		
		//$obj->addalert($v);
		return $obj;
		}
		
			/**
	 * ekwo UPD
	 * Updater	 
	 */   	
  function checkForUpdates()
  {
    $eng = new Engine;	
		$c_args = $eng->InitConf('conf.ini');
			
		$sql = new cMysql($c_args["sql_host"],$c_args["sql_db"],$c_args["sql_login"],$c_args["sql_pass"]);
		$tab = $c_args["sql_struct_tab"];
		$s="SELECT `c_value` FROM $tab WHERE `c_id`='version'";
		$ret=$sql->query($s);
		$version=mysql_result($ret,0,'c_value');
		$text=@file_get_contents('http://chili.co.ua/engine_version.txt');
		if(floatval($version)<floatval($text))$out='<a onclick="xajax_update(\''.$text.'\');">�������� ����� ������ '.$text.'</a>';
		return $out;
  }
  function update($par)
  {
    $f=file_get_contents("http://chili.co.ua/engine_$par.tar");
    file_put_contents('tmp.tar', $f);
    require_once('include/ziparchive/archive.php');
    $zip=new tar_file('tmp.zip');
    $zip->set_options(array('basedir' => '/tmp','overwrite'=>'1'));
    $zip->extract_files();
    $f='';
    foreach ($zip->files as $file) {
    	$f.=$file['name'];
    }
    $obj=new xajaxResponse();
    $obj->addalert($f);
    return $obj;
  }
  /**
   *  ekwo UPD
   *  transliteration (global function)   
   *  @param $text - text to translit
   *  @param $update - object on a page to update via AJAX
   *  @param $type - type of value to update (innerHTML or value)
   *  @param $caseSensative - if true leaves the string case, if false lowercases it           
   */     
   function translit($text,$update,$type,$caseSensative=false)
   {
    $gost = array(
   "�"=>"YE","�"=>"I","�"=>"G","�"=>"i","�"=>"-","�"=>"ye","�"=>"g",
   "�"=>"A","�"=>"B","�"=>"V","�"=>"G","�"=>"D",
   "�"=>"E","�"=>"YO","�"=>"ZH",
   "�"=>"Z","�"=>"I","�"=>"J","�"=>"K","�"=>"L",
   "�"=>"M","�"=>"N","�"=>"O","�"=>"P","�"=>"R",
   "�"=>"S","�"=>"T","�"=>"U","�"=>"F","�"=>"X",
   "�"=>"C","�"=>"CH","�"=>"SH","�"=>"SHH","�"=>"'",
   "�"=>"Y","�"=>"","�"=>"E","�"=>"YU","�"=>"YA",
   "�"=>"a","�"=>"b","�"=>"v","�"=>"g","�"=>"d",
   "�"=>"e","�"=>"yo","�"=>"zh",
   "�"=>"z","�"=>"i","�"=>"j","�"=>"k","�"=>"l",
   "�"=>"m","�"=>"n","�"=>"o","�"=>"p","�"=>"r",
   "�"=>"s","�"=>"t","�"=>"u","�"=>"f","�"=>"x",
   "�"=>"c","�"=>"ch","�"=>"sh","�"=>"shh","�"=>"",
   "�"=>"y","�"=>"","�"=>"e","�"=>"yu","�"=>"ya",
   " "=>"_","�"=>"_",","=>"_","!"=>"","@"=>"_",
   "#"=>"-","$"=>"","%"=>"","^"=>"","&"=>"","*"=>"",
   "("=>"",")"=>"","+"=>"","="=>"",";"=>"",":"=>"",
   "'"=>"","\""=>"","~"=>"","`"=>"","?"=>"","/"=>"",
   "\\"=>"","["=>"","]"=>"","{"=>"","}"=>"","|"=>""
  );
    $text=iconv('utf-8', 'windows-1251',$text);
    $text=strtr($text,$gost);
    if(!$caseSensative)
    $text=strtolower($text);
    $obj = new xajaxResponse();
		$obj->addassign($update,$type,$text);
		return $obj;
   }
  /**
   *  ekwo UPD
   *  change alias visibility      
   */     
  function setAliasVisibility($alias,$checked)
  {
    $eng = new Engine;
			
		$c_args = $eng->InitConf('conf.ini');
			
		$sql = new cMysql($c_args["sql_host"],$c_args["sql_db"],$c_args["sql_login"],$c_args["sql_pass"]);
		$tab = $c_args["sql_struct_tab"];
		
		if($checked=='true')$checked=1;else $checked=0;
		$s = "UPDATE $tab SET `v1`='$checked' where `c_id` = '$alias'";
		$sql->query($s);
		
		$obj = new xajaxResponse();
		//$obj->addassign($result,'innerHTML',$out);
		//$obj->addscript("xajax_RawInterface();");
		return $obj;
  }
  /**
   *  ekwo UPD
   *  removing alias   
   */     
   function removeAlias($alias)
   {
    $eng = new Engine;
			
		$c_args = $eng->InitConf('conf.ini');
			
		$sql = new cMysql($c_args["sql_host"],$c_args["sql_db"],$c_args["sql_login"],$c_args["sql_pass"]);
		$tab = $c_args["sql_struct_tab"];
		
		$s = "DELETE FROM $tab where `c_id` = '$alias'";
		$sql->query($s);
		
		$obj = new xajaxResponse();
		//$obj->addassign($result,'innerHTML',$out);
		$obj->addscript("xajax_RawInterface();");
		return $obj;
   } 
     /**
   *  ekwo UPD
   *  adding alias   
   */     
   function addAlias($page,$alias)
   {
    $eng = new Engine;
			
		$c_args = $eng->InitConf('conf.ini');
			
		$sql = new cMysql($c_args["sql_host"],$c_args["sql_db"],$c_args["sql_login"],$c_args["sql_pass"]);
		$tab = $c_args["sql_struct_tab"];
		$s = "SELECT `c_adm_title` FROM $tab WHERE `c_id`='$page'";
		$ret=$sql->query($s);
		if(@mysql_numrows($ret)>0)$title=mysql_result($ret,0,'c_adm_title');else
		$title=$alias;
		$s = "INSERT INTO $tab VALUES ('','{$alias}','{$page}','alias','$title','1')";
		$sql->query($s);
		
		$obj = new xajaxResponse();
		//$obj->addassign($result,'innerHTML',$out);
		$obj->addscript("xajax_RawInterface();");
		return $obj;
   } 
/*
������� ��������� �� ����� 
������ �� ���� struct  
*/

	function DeleteCategory( $gid )
	{
		$eng = new Engine;
			
		$c_args = $eng->InitConf('conf.ini');
			
		$sql = new cMysql($c_args["sql_host"],$c_args["sql_db"],$c_args["sql_login"],$c_args["sql_pass"]);
		$tab = $c_args["sql_struct_tab"];
		
		$s = "DELETE FROM $tab where `id` = $gid";
		$sql->query($s);
		
		$obj = new xajaxResponse();
		//$obj->addassign($result,'innerHTML',$out);
		$obj->addscript("xajax_RawInterface();");
		return $obj;
	}


	function inter_CreateTop( $result )
	{
		
		$link  = "'javascript: void(0)' onclick='xajax_adm_adm_EditUserForm(\"".$_SESSION["id"]."\",
																			\"admin\");'";
	
		$out .= "<div id='chXajaxLoader' style='display: none;'></div>";
		$out .='<div id="updatesPAnel">'.AdminCore::checkForUpdates().'</div>';
        /*
        <script type='text/javascript' src='templates/js/jquery.js'></script>
        <script type='text/javascript' src='templates/js/jqcolor.js'></script>
        <script type='text/javascript' src='templates/js/jquery.cookie-1.4.1.min.js'></script>
        <script type='text/javascript' src='templates/js/setCookieFunc.js'></script>
        */
        $out .= "<script type='text/javascript' src='include/js/jquery.js'></script>";
        $out .= "<script type='text/javascript' src='include/js/jquery_cookie.js'></script>";
        $out .= "<script type='text/javascript' src='include/js/setCookieFunc.js'></script>";
        $out .= "<a href='javascript:setCookieRu()' class='flag_ru'></a>";
        $out .= "<a href='javascript:setCookieUa()' class='flag_ua'></a>";
		$out .= "<div id='userPanel'>";
		$out .= "<img src='{$_SESSION['photo']}' class='' border=0>{$_SESSION['fio']}&nbsp;(<abbr title='{$_SESSION['access_desc']}'>{$_SESSION['access']}</abbr>)";
		
		$out .= "<div><a href = $link>���&nbsp;�������</a>&nbsp;<a href = exit.php>�����</a></div>";
	
		$out .= "</div>"; // end div 'userPanel '

		$h   = opendir("../modules/");
		
		$MODS_DATA = $_SESSION["MODS_DATA"];
		  
		foreach($MODS_DATA as $val)
		{	
		 	if($val["group"] == "top")
		 	{
		 		$l="'javascript: void(0)' onclick='xajax_parser([\"obj\"],[\"$val[name]\"]);'";
		 		$out .= "<a class='rounded {transparent} button main' href=$l>$val[caption]</a>";
		 		$out .= "";
		 	} 
		 }
		  
  
		/*-------------------- MODULES LIST KERNEL FUNCTION  --------------------*/  
		$l="'javascript: void(0)' onclick='xajax_kern_DispModuleList();'";
			$out .= "<a class='rounded {transparent} button main2' style='display: none' href=$l>������ �������</a>";
			
		


		$obj = new xajaxResponse();
		$obj->addassign($result,'innerHTML',$out);
		$obj->setCharEncoding('windows-1251');
		//$obj->addalert($v);
		return $obj;
		
	}
		
	

	function RawInterface()
	{
		
		
		$response = new xajaxResponse();
		
		/*-------------------------- TOP PANEL ----------------------------------*/
		
		$out .=  "<div class='top' id = 'TOP_PANEL'>";
		$out .= "</div>";
		
		
		/*************************** LEFT PANEL **********************************/
       
        $out .= "<div class='left' id = 'LEFT_PANEL'>";
		$out .= "</div>"; // CLOSE div 'LEFT_PANEL' 
		
		
		/*************************** RESULT PANEL *******************************/
		$out .= "<div id='result'>\n";
		$out .= "</div>\n";
		
		$response->setCharEncoding('windows-1251');
		
		$response->addassign('ROOT_DIV','innerHTML',$out);
		
		$response->addscript("xajax_inter_CreateTop('TOP_PANEL');");
		$response->addscript("xajax_inter_CreateLeft('LEFT_PANEL');");
		
	
	
		// FIRST PAGE !!!! (BY DEFAULT)  
		
		$response->addscript("xajax_CreateNewCatForm();");
		
		
		
		return $response;
	}


	// ������� �������!!!
 function parser($u_n,$u_a)
	{
        ///VARS
        $obj= new xajaxResponse();
        
/***************************************************************************************************************/
        /*������ ������������������ ������ ��� ���� ���� �������*/
		//$ADM_MODS = array("admin","statistic","far");
		
		$MOD_PREV = false;
/***************************************************************************************************************/        
		$FL_ACT_PAN	= false; 
		
        $eng = new Engine();
		$c_args = $eng->InitConf("conf.ini");
      
        /*CREATE u_args ASSOC ARRAY*/

		$u_args = $eng->TransArgs($u_n,$u_a);

		$tpl_path = $u_args["tpl_path"];
		
		$panels_and_dirs = PrepareInterface($tpl_path,$m_data);
		 
		//print_r($_SESSION["MODS_DATA"]["orders"]);
		
		$MODS_DATA = $_SESSION["MODS_DATA"];
		
		// if($MODS_DATA[$u_args['obj']]["group"] == "top"  ) 	
		
		if( $MODS_DATA[$u_args['obj']]["group"] == "top"  )
			$MOD_PREV = true;
		if( $MODS_DATA[$u_args['obj']]["group"] == "left" and  
			isset( $MODS_DATA[$u_args['obj']]["caption"]) )
			$MOD_PREV = true;
		
		if( !$MOD_PREV ) /*---------------------   ����� �������� ������  --------------*/ 
		{		
/*---------------------������� ������� � ����������� �� �������--------------------*/
/*--------------------- �������� ������ ���  ������ ������� �������� ���� ������� --------------------*/
			if(isset($u_args['obj']))
			{
				$t = $m_data[ $u_args['obj'] ];
				unset($m_data);
				
				if( isset($u_args['obj_redirect']) )
				{
					$t2 = $t['R_E_P'][ $u_args['obj_redirect'] ];
					unset($t);
					
					$t['R_E_P']['1'] 		= $t2;
					$t['R_E_P']['count'] 	= 1;
					
					//print_r($t);
				}
				
				
				
				$m_data[$u_args['obj']] = $t;
				$FL_ACT_PAN = true;			// make this panel !!!INLINE!!
			}
				
				
				if(isset($m_data)){
					
					//while(list($mod,$tpl_args) = each($m_data))
					foreach($m_data AS $key => $val)
					{
						//print "'".$mod."'";
						//print_r($key);
						//print_r($val);
						//print $m_data["$mod"]['R_E_P']['count'];
						
							$pref="";
							for($i=0;$i < $m_data["$key"]['R_E_P']['count'];$i++)
							{
								/*���������� ����� ������������� ������ ���� ���� ������ ���. ����. ���*/
	
								
								$k = $i + 1;
								$u_args['result']	=	$m_data["$key"]['R_E_P']["$k"]['result'];
								$tpl_args			=   $m_data["$key"]['R_E_P']["$k"]['tpl_args'];
								$tpl_args['null']	=	true;
								
								
								$function = "xajax_admin_".$key."(".$eng->TransArgs($u_args).",".
																	$eng->TransArgs($tpl_args).",".
																	$eng->TransArgs($c_args).");";
                $function=nl2br($function);
																	
								$obj->addscript($function);
							}
								
					}	
				}
		}else{
/*------------------------------------------------------------------------------------------------------------------------*/
/*-------------------------------------------- ������� ������� ������� ---------------------------------------------------*/
			$u_args['result']='res_out';
			$function = "xajax_". $MODS_DATA[$u_args['obj']]["function"] . 
					"(".$eng->TransArgs($u_args).",". $eng->TransArgs($c_args).");";
			$function=nl2br($function);
			$obj->addscript($function); /*����� ������������������ ������*/
			
		}
		
		$declares = "<div id='tpl_path' style='display: none'>$tpl_path</div>"; 
		$declares.= "<div id='cur_page' style='display: none'>".$u_args['page']."</div>";
		
		$out .= $panels_and_dirs . $declares;
        
		
		//$obj->addscript('chiliAlert("fuck me","info")');
		//$obj->setCharEncoding('windows-1251');
		$obj->addassign('result','innerHTML',$out);
		
		
		if( $FL_ACT_PAN )
			$obj->addscript('adm_active_panel("'.$u_args['obj'].'");');
			
		//$obj->addalert( $u_a );
		return $obj;
	}
	
	function PrepareInstall( $data ,&$name,&$title,&$var)
	{
				foreach($data as $t)
				{
					$buff[1][] = $t["name"];
					$buff[2][] = $t["title"];
					$buff[3][] = $t["var"];
				}			
	
				while($str = array_shift($buff[1]))
					$str_name .= $str . ",";
				
				
				while($str = array_shift($buff[2]))
					$str_title .= $str . ",";
				
				while($str = array_shift($buff[3]))
					$str_var .= $str . ",";
				
				$str_name 	= trim($str_name,",");
				$str_title 	= trim($str_title,",");
				$str_var 	= trim($str_var,",");
				
				$name 	= $str_name;
				$title	= $str_title;
				$var 	= $str_var;
	}
	
	// ���������� � ������ ������ ��� ������ ������
	// ���� ������ �������!!!
	
		function InstallModule( $new )
	{
		$eng 	= new Engine;	
		$c 		= $eng->InitConf('conf.ini');
		
		$sql = new cMysql($c["sql_host"],$c["sql_db"],$c["sql_login"],$c["sql_pass"]);
		$tab = $c["sql_main_tab"];
		
		if(!is_array($new)) return 0;
		
		
		
		while($mod = array_shift( $new ))
		{
			$f1 = false;
			$f2 = false;
		
			
			// ������ ������...
			$tables = $this->MODS_DATA[$mod]["TABLES"];
			$dirs	= $this->MODS_DATA[$mod]["DIRS"];
			$vars   = $this->MODS_DATA[$mod]["VARS"];
			
			$tab_name = '';$tab_title='';$tab_var = '';
			$dir_name = '';$dir_title='';$dir_var = '';
			
			if(is_array($tables)){
				$this->PrepareInstall($tables,$tab_name,$tab_title,$tab_var);
				$f1 = true;
			}
			if(is_array($dirs)){
				$this->PrepareInstall($dirs,$dir_name,$dir_title,$dir_var);
				$f2 = true;	
			}
			
			if($f1 or $f2){
				//print_r ($tab_str."!!!".$mod);
				$s = "INSERT INTO `$tab` (`type`,`v1` ,`v2`,`v3`,`v4`,`v5`,`v6`,`v7` )
								VALUES ('_install_','$mod','$tab_name','$tab_title','$tab_var',
								'$dir_name','$dir_title','$dir_var')";
				//print($s);
				$sql->query($s);
			}
			// ������ VARS
			if(is_array($vars)){
				$this->PrepareInstall($vars,$var_name,$var_title,$var_var);
				
				$s = "INSERT INTO `$tab` (`type`,`v1` ,`v2`,`v3`,`v4` )
								VALUES ('_value_','$mod','$var_name','$var_title','$var_var')";
				//print($s);
				$sql->query($s);
			}
		
		}
	}
	
	function include_all($xajax)
	{
		//global $xajax;
		
		
	   $h   = opendir("../modules/");
		
		while ($file = readdir($h))
		{
			
			
				if( preg_match("/mod/",$file) )
				{
					
					$arr = explode("_",$file);
					
					// ���������� ������� ���� ������
					include("../modules/$file/admin/admin_$arr[1].php");
								
					// ��������� ������ ������ � ������
					$data = $arr[1] . "_INFO";
					$data = $$data;
					
					$this->MODS_DATA[ $data["name"] ] = $data;
					
					$xajax->registerFunction($this->MODS_DATA[$arr[1]]["function"]);
					
					//print_r($MODS_DATA[$arr[1]]["function"]);		
				}
		}
		
		//��������� ������ � ������� 
		
		$this->ModsAccessToDb($this->MODS_DATA,$new_mods);
		
		$this->InstallModule($new_mods);
		
		//print_r( $this->MODS_DATA["workers"] );
		
		@session_register("MODS_DATA");
		$_SESSION["MODS_DATA"] = $this->MODS_DATA;
		// add to database data for admin to make change it
		
		
		
		
		
		/*------------------------- REGISTER FUNCIONS ---------------------------------*/
		$xajax->registerFunction(array("parser","AdminCore","parser"));
		
		// ������� ���������� 
		$xajax->registerFunction(array("RawInterface","AdminCore","RawInterface"));
		$xajax->registerFunction(array("inter_CreateTop","AdminCore","inter_CreateTop"));
		$xajax->registerFunction(array("inter_CreateLeft","AdminCore","inter_CreateLeft"));
		
		$xajax->registerFunction(array("SetVisibleCat","AdminCore","SetVisibleCat"));	
		$xajax->registerFunction(array("adm_WriteDynamicPart","AdminCore","adm_WriteDynamicPart"));	
		
		// ����� ������
		$xajax->registerFunction(array("CreateNewCat","AdminCore","CreateNewCat"));	
		$xajax->registerFunction(array("CreateNewCatForm","AdminCore","CreateNewCatForm"));	
	  $xajax->registerFunction(array("DeleteCategory","AdminCore","DeleteCategory"));
    $xajax->registerFunction(array("addAlias","AdminCore","addAlias"));
	  $xajax->registerFunction(array("removeAlias","AdminCore","removeAlias"));
	   	$xajax->registerFunction(array("setAliasVisibility","AdminCore","setAliasVisibility"));
	   	$xajax->registerFunction(array("translit","AdminCore","translit"));
	   	$xajax->registerFunction(array("update","AdminCore","update"));
	   	
	   	
	   	// ������� ��������� � ������� ������� 
	   	
	   	
	   	//$xajax->registerFunction(array("kern_UpdateModsAccess","AdminCore","kern_UpdateModsAccess"));
	   	
	   	$xajax->processRequests();
	   	return $xajax;
	}
	
	
	//include js,and css files...
	function IncludeClassFiles($id,$pref,$ext)
	{  
	$h = opendir("include/$pref");
	while ($file = readdir($h))
	{
		if(!is_dir($file))
		{
			$a = explode(".",$file);
			if($a[1] == $ext)
			{	
				$src = "include/$pref/".$file;
				
				switch($id)
				{
					case "0":
					{
						print "<script type='text/javascript' src= \"". $src . "\"></script>";
						
						break;
					}	
					case "1":
					{
						print "<link rel='stylesheet' type='text/css' media='screen' href=\"".$src."\">";
						break;
					}
				}
				
				 
			}
		}		
	}
				
/*------------------------------ INCLUDE ADMINS JS-FILES -------------------------------------*/
	 $h   = opendir("../modules");
		while ($file = readdir($h))
		{
				if(is_dir("../modules/".$file))
				{
						
						    //print $file."/admin/$pref";
							if(!file_exists("../modules/".$file."/admin/$pref")) continue;
							
							$z = opendir("../modules/".$file."/admin/$pref");
						    
						    //print "$file";
						    while($f = readdir($z))
						    {
						    	$a = explode(".",$f);
							    	if($a[1] == $ext)
							    	{				
							    		$src = "../modules/$file/admin/$pref/".$f;
							    		switch($id)
										{
											case "0":
											{
												print "<script type='text/javascript' src= \"". $src . "\"></script>\n";
												break;
											}	
											case "1":
											{
												print "<link rel='stylesheet' type='text/css' media='screen' href=\"".$src."\">\n";
												break;
											}
										}
							    	}
						    }
						     
						     
						     //print "f='$f'<br>";					     
						
				}
		}		
	}

// ������� � �� ���������� � ������ ������ ������������� ��� �������
	 // � ����� ��������� ���������� �� �� 
	function ModsAccessToDb(&$data,&$new_mods)
	{
		$eng = new Engine;	
		$c_args = $eng->InitConf('conf.ini');
			
		$sql = new cMysql($c_args["sql_host"],$c_args["sql_db"],$c_args["sql_login"],$c_args["sql_pass"]);
		$tab = $c_args["sql_main_tab"];
		
		
		foreach($data as $val)
		{
			
			// ��������, ���� �� ������ � �� 
			// ���� ��, �� ������� ���� ���, �� �������
			
			$s = "SELECT * FROM $tab where `type`='_access_' and `v7`='{$val['name']}'";
			$res = $sql->query($s,true);
			
						
			if( empty($res) )
			{
				// ��������� ����� ������ ��� �������� 
				$new_mods[] = $val['name'];  
				
				
				// ��������� � �� ���� � ���������� ������� ��� ������
				$s = "INSERT INTO `$tab` (`type`, `v1`, `v2`,`v3`,`v4`,`v5`,`v6`,`v7` )
							VALUES ('_access_', '{$val['ACCESS']['r']['E']}',
							 '{$val['ACCESS']['r']['GE']}',
							  '{$val['ACCESS']['w']['E']}',
							   '{$val['ACCESS']['w']['GE']}',
							   '{$val['ACCESS']['a']['E']}',
							   '{$val['ACCESS']['a']['GE']}',
							   '{$val['name']}'
							   )";
							   
				//print $s;
				
				$sql->query($s);
			}else
			{// ��������� � ��� ����� ������ �� ��
				
				$data[$val['name']]['ACCESS']['r']['E'] 	= $res['v1'];
				$data[$val['name']]['ACCESS']['r']['GE'] 	= $res['v2'];
				$data[$val['name']]['ACCESS']['w']['E'] 	= $res['v3'];
				$data[$val['name']]['ACCESS']['w']['GE'] 	= $res['v4'];
				$data[$val['name']]['ACCESS']['a']['E'] 	= $res['v5'];
				$data[$val['name']]['ACCESS']['a']['GE'] 	= $res['v6'];
				
				// ������� � ������ ������ � ������������ ��������...
				
				$s = "SELECT * FROM $tab where `type`='_install_' and `v1`='{$val['name']}'";
				$r = $sql->query($s,true);
				
				if(!empty( $r )) // ���� ���� ���� install ��� ������...
				{
					// ������� ������� 
					$name 	= explode(",",$r['v2']);
					$title 	= explode(",",$r['v3']); 
					$vars 	= explode(",",$r['v4']);

					while($n = array_shift($name))
					{
						$t = array_shift($title);
						$v = array_shift($vars);
						
						$data[$val['name']]['TABLES'][$n] = array("name" => $n,"title" => $t,"var" => $v);
					}
					
					//������� ������
					$name 	= explode(",",$r['v5']);
					$title 	= explode(",",$r['v6']); 
					$vars 	= explode(",",$r['v7']);

					while($n = array_shift($name))
					{
						$t = array_shift($title);
						$v = array_shift($vars);
						
						$data[$val['name']]['DIRS'][$n] = array("name" => $n,"title" => $t,"var" => $v);
					}
					
				}
				
				// ������� �� ���� ������ � ������������ VARS
				
				$s = "SELECT * FROM $tab where `type`='_value_' and `v1`='{$val['name']}'";
				$r = $sql->query($s,true);
				
				if(!empty( $r )) // ���� ���� ���� value ��� ������...
				{
					$name 	= explode(",",$r['v2']);
					$title 	= explode(",",$r['v3']); 
					$vars 	= explode(",",$r['v4']);

					while($n = array_shift($name))
					{
						$t = array_shift($title);
						$v = array_shift($vars);
						
						$data[$val['name']]['VARS'][$n] = array("name" => $n,
																"title" => $t,
																"var" => $v);
					}
				}
				
				
				
			}
			
			
		}
		
		
	}

	
}/*-------------------------------END OF CLASS ADMINCORE-----------------------------*/
	
	
	
	/*----------------------------DECLARATION BLOCK-------------------------------------*/

   function _U( $msg )
   {
   		return iconv("windows-1251","UTF-8",$msg);
   }

   function ParseChiliTag($str,$tpl_args)
	{
		$core = new Core;

  		$mod = $core->ParseChiliTag($str);

  		$tpl_args = $core->tpl_args;

    	return $mod;
	}
	
	// function need in 'PrepareInterface()'
	function PanelsIntoStr( $pans )
	{	

		$out  = $pans['START'];
		foreach($pans as $key => $val)
		{
			if($key != 'START' and $key != 'END')
			{
				$out .= $pans[$key]['start'];
					
					$x=1;
				
				
				
				while(isset($pans[$key]['data_' . $x]))
				{
					if( $pans[$key]['count'] > 1)
						$stl = "class = redirect_panel onclick= adm_PanelRedirect('$key',$x)";
					else	$stl = '';
					
					preg_match('|\<div(.*)\>\<\/div\>|U',$pans[$key]['data_' . $x++],$m);
					$out .= "<div ".$m[1] ." $stl ></div>";
				}
				
				$out .= $pans[$key]['end'];
			}
		}
		
		$out .= $pans['END'];
		
		return $out;
	}
	
	
		/*������� ������ � ������� �� ��� �������-�������*/
	/*������� ������ � ������� �� ��� �������-�������*/
	function PrepareInterface($path,&$m_data)
	{	
		$file 	= @file($path);
        $n 		= count($file);
		
		$first = true;
		
		$dirs = "<div id=dirs><ul>\n";
		$panels['START'] = "<div class=out id=res_out>\n";
		 
		$p=0; 
		for($i=0;$i<$n;$i++)
        {
			if($str = strstr($file[$i],"[chili"))
			{
    			$mod = ParseChiliTag($str,&$tpl);

			/*------------- HE ��� ��������������� �T������� �������������� ������ � ������� -------*/
				if($_SESSION['access'] != 'Admin' AND $mod == 'raw') continue;
							if($mod!='search' && $mod!='seo')
			{
					if(!isset($m_data[$mod]))
					{
						
						$m_data[$mod]['R_E_P']['1']['tpl_args']	= $tpl;
						$m_data[$mod]['R_E_P']['1']['result']	= $mod . "_1";
						$m_data[$mod]['R_E_P']['count']   		= 1;
						
						$link = "'javascript: void(0)' onclick='adm_InlinePanel(\"$mod\");'";
						$dirs .= "<li class='rounded'><span id='dir_$mod'><a href=$link>$mod</a></li>";
						
						
						if($first) 	{$stl = ""; $first = false;}
						else		 $stl = "style='display: none'"; 
						
						$panels[$mod]['start'] 	= "<div class = panel id='$mod' $stl >";
						$panels[$mod]["data_1"] = "<div id='". $mod."_1'></div>";
						$panels[$mod]['count']	= 1;
						$panels[$mod]['end'] 	= "</div>";
							
					}else{ // add count of divs
					
						$rep = ++$m_data[$mod]['R_E_P']['count'];
						
						$t = $mod."_" . $rep;
						
						$m_data[$mod]['R_E_P'][$rep]['tpl_args']	= $tpl;
						$m_data[$mod]['R_E_P'][$rep]['result']		= $t;
						$panels[$mod]["data_" . $rep ] = "<div id='$t'></div>";
						$panels[$mod]['count']++;
						
					}
					
					}
				$p++;		
			}
        }
				       
        $panels['END'] = "</div>";
		$dirs 	.= "</ul></div>";
			
		//print_r($m_data);	
		$out =  PanelsIntoStr($panels) . $dirs;
			
		return $out;	
	}

		
/*--------------------------------END OF DECLARATION BLOCK-------------------------------------*/
     
    $admin = new AdminCore;
	$xajax=$admin->include_all( $xajax );
	
	$admin->IncludeClassFiles(0,'js','js');

?> 

<!--
<script type='text/javascript' src= "mootools.js"></script>
 -->

<html><head>
<!--
<script src='admin_article/article_preview_uploader.js'></script>
<script src='admin_article/checkboxs.js'></script>-->


<!---->
                                           
        
<?php
		$admin->IncludeClassFiles(1,'css','css');
      	$xajax->printJavascript('xajax/');  
      
      	//print OnBodyReady();
?>

<script>
xajax.loadingFunction =
                function(){ $('#loading').show(); }
            function hideLoadingMessage()
            {
                $('#loading').fadeOut('slow');                            
            }
            xajax.doneLoadingFunction = hideLoadingMessage;
$(document).ready(function(){
  //$(".rounded").corners("5px anti-alias");
});
</script>
<title>chiliEngine 1.0 beta</title>
</head><body>
<div id='loading' style='width: 100px; height: 34px;position: absolute;z-index: 999;top:0px; left:0px;'></div>
<?php
	
	
    

	
	
	
	print "<div id = 'ROOT_DIV'>"; // MAIN DIV IN PAGE
	
	if($_SESSION['logged'] )
	{
		
		//print_r($admin->MODS_DATA);
		
		/*-------------------�������� �� ���������---------------------------*/
		print "<script>";
		
		print "xajax_RawInterface();";
	
		//print "xajax_CreateNewCatForm();";
		
		print "</script>";


	}else{
		/*�������� �� �����������*/
		print "<script> window.location='index.php'</script>"; 
	}

	
	print "</div>"; // END div 'ROOT_DIV'
	
	
?>
</body></html>