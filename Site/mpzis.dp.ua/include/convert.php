<?php

  class date{
    function MysqlToShort($date)
    {
      $arr=explode('-',$date);
      return $arr[2].'.'.$arr[1].'.'.$arr[0];
    }
  }
  // | (?i:[aeiouy])      | (?i:sh|ch|qu|[bcdfghjklmnpqrstvwxz])
  class typograph{
/*
 * ��������, ������ 1.0.6 (PHP4)
 * ------------------------------------------------------------
 * ���������: http://rmcreative.ru/article/programming/typograph/  
 *  
 * ������:
 * � �������� ������ ( http://smee-again.livejournal.com/ )
 *    ������� ����� ���� � ���������� ���������, ������������.
 * � ������� ��������� ( http://rmcreative.ru/ )
 *    ���, ������������, ����.
 * 
 * ��� �������� ��������� ������ ������� ����� ��������������:
 * � http://philigon.ru/
 * � http://artlebedev.ru/kovodstvo/
 * � http://pvt.livejournal.com/
 *  
 * ------------------------------------------------------------
 */

//setlocale(LC_ALL, 'ru_RU.CP1251');

// ����������� ����� ��� �������
function typo_phone($phone){
    //89109179966 => 8 (910) 917-99-66
    $result = $phone[0].' ('.$phone[1].$phone[2].$phone[3].') ';
    $count = 0;
    $buff='';
    for ($i=strlen($phone)-1; $i>3; $i--){
        $left = $i-3;
        if ($left!=3){
            if ($count<1){
                $buff.=$phone[$i];
                $count++;
            }
            else{
                $buff.=$phone[$i].'-';
                $count=0;
            }
        }
        else{
            $buff.=strrev(substr($phone, $i-2, 3));
            break;
        }
    }
    $result.=strrev($buff);
    return $result;
}

// �������� ��������, ������ html-�����.
// ���� ��������� ������������� �� ������������ ����� ������ ������-���� ����� �
// �������� ��� � ������ $safe_blocks.

function typo($str){
	$safe_blocks = array(
		'<pre[^>]*>' => '<\/pre>',
		'<style[^>]*>' => '<\/style>',
		'<script[^>]*>' => '<\/script>',
		'<!--' => '-->',
	);

	$pattern = '((?U)';
	foreach ($safe_blocks as $start => $end) $pattern .= "$start.*$end|";
	$pattern .= '<[^>]*[\s][^>]*>)';
	$str = preg_replace_callback("~$pattern~is", '_typo_stack', $str);

	$str = typo_text($str);
	$str = strtr($str, _typo_stack());
	return $str;
}


function _typo_stack($m = false){	
	static $arr = array();
	if ($m !== false){
		$key = '<tag'.count($arr).'>';
		$arr[$key] = $m[0];
		return $key;
	}
	else{
		$t = $arr;
		unset($arr);
		return $t;
	}
}


// ������� ������� ���������.
// ���� ��� ����������� �������� ������� ������, ��. $sym � readme.txt
function typo_text($str){
	if (trim($str) == '') return '';
      
$sym = array(
        'nbsp'    => '&nbsp;',
		'lnowrap' => '<span style="white-space:nowrap">',
		'rnowrap' => '</span>',

		'lquote'  => '&laquo;',
		'rquote'  => '&raquo;',
		'lquote2' => '&lsquo;',
		'rquote2' => '&rsquo;',

		'mdash'   => '&mdash;',
		'ndash'   => '&ndash;',
		'minus'   => '&ndash;', // �����. �� ������ ������� +, ���� �� ���� �������

		'hellip'  => '&hellip;',
		'copy'    => '&copy;',
		'trade'   => '<sup>&trade;</sup>',
		'apos'    => '&#39;',   // ��. http://fishbowl.pastiche.org/2003/07/01/the_curse_of_apos
		'reg'     => '<sup><small>&reg;</small></sup>',
		'multiply' => '&times;',
		'1/2' => '&frac12;',
		'1/4' => '&frac14;',
		'3/4' => '&frac34;',
		'plusmn' => '&plusmn;',
		'rarr' => '&rarr;',
		'larr' => '&larr;',
);

	    $html_tag = '(?:(?U)<.*>)';
		$hellip = '\.{2,5}';
		//������ �����
		$phrase_begin = "(?:$hellip|\w)";
		//����� �����
        $phrase_end   = '(?:[)!?.:#*\\\]|$|\w|'.$sym['rquote'].'|'.$sym['rquote2'].'|&quot;|"|'.$sym['hellip'].'|'.$sym['copy'].'|'.$sym['trade'].'|'.$sym['apos'].'|'.$sym['reg'].'|\')';
        //����� ���������� (��������� � ����� - ��������� ������!)
        $punctuation = '[?!:,]';
		//������������
        $abbr = '(?:���|���|���|��|��|���|���)';
        //�������� � �����
        $prepos = '(:?�|�|��|���|�|���|�|�|�|�|�|��|��|���|��|���|��|��|��|��|��|��|���|��|��|��|��|���|����|����|�����|���|���|����|���|���|����|���|��|���|��|���|���|��|���|��|���|�����|�����|������|�����|�����|������|���|���|�)';

		$any_quote    = "(?:$sym[lquote]|$sym[rquote]|$sym[lquote2]|$sym[rquote2]|&quot;|\")";
	
	    $replace = array(
			// ����� �������� ��� ��������� -> ���� ������
			'~( |\t)+~' => ' ',

		     // �������� ������������ �������
		     '~([^"]\w+)"(\w+)"~' => '$1 "$2"',
		     '~"(\w+)"(\w+)~' => '"$1" $2',

		     //�������� ������ �� �������
		     '~\(\s~s' => '(',
			 '~\s\)~s' => ')',

			//����� � �������������� ��������... ��������!
			'~('.$phrase_end.') +('.$punctuation.'|'.$sym['hellip'].')~' => '$1$2',
			'~('.$punctuation.')('.$phrase_begin.')~' => '$1 $2',

		     //����������� �������� ����������� � ����������� ���� �������������
			'~('.$abbr.')\s+"(.*)"~' => $sym['lnowrap'].'$1 "$2"'.$sym['rnowrap'],

			 //������ �������� ��� ����������� �� ������������ � ���� ����������.
			 //��������: ���. ������, �. �������
			 //������ ������, ���� ��� ���.
			 '~([��]|��|���|���)\.\s*([�-�]+)~s' => '$1.'.$sym['nbsp'].'$2',

			 //�� �������� ���., �. � �.�. �� ������.
			 '~(���|�|����|���)\.\s*(\d+)~si' => '$1.'.$sym['nbsp'].'$2',

			 //�� ��������� 2007 �., ������� ������, ���� ��� ���
			'~([0-9]+)\s*([��])\.~s' => '$1'.$sym['nbsp'].'$2.',

			 // ���������� ������� � ������. ������� ������� ���������.
			 "~(?<=\s|^|[>(])($html_tag*)($any_quote+)($html_tag*$phrase_begin$html_tag*)~"	=> '$1'.$sym['lquote'].'$3',
			 "~($html_tag*$phrase_end$html_tag*)($any_quote+)($html_tag*$phrase_end$html_tag*|\s|[,<-])~"	=> '$1'.$sym['rquote'].'$3',

			// ���� ������ ��� ��� ����� ������ ������ � �� ���� �������� ����.
			// + ������ ��������� ������ ����� ����, ��������: ������ � ����, ������ � �������� �������.
			'/(\s+|^)(--?)(?=\s)/' => '$1'.$sym['mdash'],

			// ���� ������, ������������ � ����� ������ ������� � �� ���� ��������� ����.
			'/(?<=\d)-(?=\d)/' => $sym['ndash'],

			// ������ ��������� � ����� ������ �������� � �����
			'/(?<=\s|^|\W)'.$prepos.'(\s+)/i' => '$1'.$sym['nbsp'],

			// ������ �������� ������� ��, ��, �� �� ��������������� �����, ��������: ��� ��, ���� ��, ��� ��.
			"/(?<=\S)(\s+)(�|��|�|��|��|��|����|���)(?=$html_tag*[\s)!?.])/i" => $sym['nbsp'].'$2',

			// ����������� ������ ����� ���������.
			'~([�-�A-Z]\.)\s?([�-�A-Z]\.)\s?([�-��-�A-Za-z]+)~s' => '$1$2'.$sym['nbsp'].'$3',

			// ������� �������� �����, ���������� ������� � ������ ������.
			'~(\d+)\s?(���.)~s'	=>	'$1'.$sym['nbsp'].'$2',
			'~(\d+)\s?(���.|���.)?\s?(���.)~s'	=>	'$1'.$sym['nbsp'].'$2'.$sym['nbsp'].'$3',

			// ����������� ������� � ��������
			//"/($sym[lquote]\S*)(\s+)(\S*$sym[rquote])/U" => '$1'.$sym["nbsp"].'$3',

			// �� 2 �� 5 ����� ����� ������ - �� ���� ���������� (������ - �� ��������� ��������).
			"~$hellip~" => $sym['hellip'],

	        // ����� (c), (r), (tm)
			'~\((c|�)\)~i' 	=> $sym['copy'],
			'~\(r\)~i' 	=>	$sym['reg'],
			'~\(tm\)~i'	=>	$sym['trade'],

			// ����������� ��� 1/2 1/4 3/4
			'~\b1/2\b~'	=> $sym['1/2'],
			'~\b1/4\b~' => $sym['1/4'],
			'~\b3/4\b~' => $sym['3/4'],

			"~'~"		=>	$sym['apos'],
			// ������� 10x10, ���������� ����
			'~(\d+)[x|X|�|�|*](\d+)~' => '$1'.$sym['multiply'].'$2',

			//��������
			'~���[:.] ?(\d+)~ie' => "'<span style=\"white-space:nowrap\">���: '.typo_phone('$1').'</span>'",
			//+-
			'~([^\+]|^)\+-~' => '$1'.$sym['plusmn'],
			//arrows
			//'~([^-]|^)->~' => '$1'.$sym['rarr'],
			//'~<-([^-]|$)~' => $sym['larr'].'$1',

			//����� ������ ��������� ����� ���������� � �������� v.
			'~([v�]\.) ?([0-9])~i' => '$1'.$sym['nbsp'].'$2',
			'~(\w) ([v�]\.)~i' => '$1'.$sym['nbsp'].'$2'
		);

	$str = preg_replace(array_keys($replace), array_values($replace), $str);
	return $str;
}

      function hyphenation($text)
      {
    #����� (letter)
    $l = '(?:[���-�]  #� � �-� (���) 
           | [a-zA-Z] 
           )'; 

    #������� (vowel)
    $v = '(?:[����������]  #���������� (�������) 
           | [��������ߨ]         #��������ߨ (�������) 

           )'; 

    #��������� (consonant) 
    $c = '(?:[��������������������]  #�������������������� (���������) 
           | [��������������������]                #�������������������� (���������) 

           )'; 

    #����������� 
    $x = '(?:[������])';   #������ (�����������) 

    /* 
    #����p��� �.�p������ � ����������� �������� � ��p��������� 
    $rules = array( 
        # $1       $2 
        "/($x)     ($l$l)/sx", 
        "/($v)     ($v$l)/sx", 
        "/($v$c)   ($c$v)/sx", 
        "/($c$v)   ($c$v)/sx", 
        "/($v$c)   ($c$c$v)/sx", 
        "/($v$c$c) ($c$c$v)/sx" 
    ); 
    */ 

    #improved rules by D. Koteroff 
    $rules = array( 
        # $1       $2 
        "/($x)     ($l$l)/sx", 
        "/($v$c$c) ($c$c$v)/sx", 
        "/($v$c$c) ($c$v)/sx", 
        "/($v$c)   ($c$c$v)/sx", 
        "/($c$v)   ($c$v)/sx", 
        "/($v$c)   ($c$v)/sx", 
        "/($c$v)   ($v$l)/sx", 
    ); 

    #\xad = &shy; 
    $text = preg_replace($rules, "$1\xad$2", $text); // �� \xc2\xad 
    return  $text;
      }

  }
  class img{
      function getWidth($filename)
      {
      	$arr=getimagesize($filename);
         return $arr[0];
      }
     function getHeight($filename)
      {
      	$arr=getimagesize($filename);
         return $arr[1];
      }
     function setWidthHeight($filename,$newwidth=false,$newheight=false)
     {
     	  $arr=getimagesize($filename);
     	  if($newwidth && !$newheight)
     	  {
     	  	$arr2['w']=$newwidth;
     	  	$arr2['h']=$newwidth*$arr[1]/$arr[0];
     	  }
     	  if($newheight && !$newwidth)
     	  {
     	  	 $arr2['h']=$newheight;
     	  	 $arr2['w']=$arr[0]*$newheight/$arr[1];
     	  }
     	  if(!$newheight && !$newwidth)
     	  {
     	  	              $arr2['w']=$arr[0];
     	  	              $arr2['h']=$arr[1];
     	  }
     	  if($newheight && $newwidth)
     	  {
     	  	$arr2['w']=$newwidth;
     	  	$arr2['h']=$newheight;
     	  }
     	  return $arr2;         
     	
     }
  }
?>