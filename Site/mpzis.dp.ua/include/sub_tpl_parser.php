<?php
class tpl{
  var $tpl_path;
  var $replacement=array();
  var $replacement_loop=array();
  function __construct($tpl=false)
  {
    if($tpl)
    {
      $this->tpl_path=$tpl;
    }
  }
  function addValue($find,$replace)
  {
    if(is_array($replace))
    {
      $this->replacement_loop['{'.$find.'}']=$replace;
    }else
     $this->replacement['{'.$find.'}']=$replace;
  }
  function addValues($array)
  {
    foreach ($array as $find=>$replace) {
    	$this->addValue($find,$replace);
    }
  }
  
  function parse($ret=false)
  {
    if(!($template=file_get_contents($this->tpl_path)))$errors[]='Failed Loading template file '.$this->tpl_path;
      $template=strtr($template, $this->replacement);
      if(count($this->replacement_loop)>0)
      {
        //parsing loops
        foreach ($this->replacement_loop as $key=>$value) {
          $tpl_arr=explode($key,$template);
          $template=$tpl_arr[0];
          $sub_tpl_tmp=$tpl_arr[1];
          $key=str_replace('{', '{/',$key);
          $tpl_arr=explode($key, $sub_tpl_tmp);
          $sub_tpl_tmp=$tpl_arr[0];
          foreach ($value as $find=>$replace) {
            foreach ($replace as $key=>$value) {
            	$replace1['{'.$key.'}']=$value;
            }
          	$template.=strtr($sub_tpl_tmp, $replace1);
          }
          $template.=$tpl_arr[1];
          //eoparsing loop
        }
        }
        if(!$ret)
        {
          echo $template;                
        }else{
          return $template;
        }    
  }
  
}
?>