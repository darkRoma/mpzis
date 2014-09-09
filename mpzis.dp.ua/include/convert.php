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
 * Типограф, версия 1.0.6 (PHP4)
 * ------------------------------------------------------------
 * Страничка: http://rmcreative.ru/article/programming/typograph/  
 *  
 * Авторы:
 * – Оранский Максим ( http://smee-again.livejournal.com/ )
 *    Большая часть кода и регулярных выражений, тестирование.
 * – Макаров Александр ( http://rmcreative.ru/ )
 *    Код, тестирование, идеи.
 * 
 * При создании типографа помимо личного опыта использовались:
 * – http://philigon.ru/
 * – http://artlebedev.ru/kovodstvo/
 * – http://pvt.livejournal.com/
 *  
 * ------------------------------------------------------------
 */

//setlocale(LC_ALL, 'ru_RU.CP1251');

// Форматирует число как телефон
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

// Вызывает типограф, обходя html-блоки.
// Если возникнет необходимость не обрабатывать текст внутри какого-либо блока —
// добавьте его в массив $safe_blocks.

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


// Главная функция типографа.
// Если вам понадобится изменить символы замены, см. $sym и readme.txt
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
		'minus'   => '&ndash;', // соотв. по ширине символу +, есть во всех шрифтах

		'hellip'  => '&hellip;',
		'copy'    => '&copy;',
		'trade'   => '<sup>&trade;</sup>',
		'apos'    => '&#39;',   // см. http://fishbowl.pastiche.org/2003/07/01/the_curse_of_apos
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
		//Начало слова
		$phrase_begin = "(?:$hellip|\w)";
		//Конец слова
        $phrase_end   = '(?:[)!?.:#*\\\]|$|\w|'.$sym['rquote'].'|'.$sym['rquote2'].'|&quot;|"|'.$sym['hellip'].'|'.$sym['copy'].'|'.$sym['trade'].'|'.$sym['apos'].'|'.$sym['reg'].'|\')';
        //Знаки препинания (троеточие и точка - отдельный случай!)
        $punctuation = '[?!:,]';
		//Аббревиатуры
        $abbr = '(?:ООО|ОАО|ЗАО|ЧП|ИП|НПФ|НИИ)';
        //Предлоги и союзы
        $prepos = '(:?а|в|во|вне|и|или|к|о|с|у|о|со|об|обо|от|ото|то|на|не|ни|но|из|изо|за|уж|на|по|под|подо|пред|предо|про|над|надо|как|без|безо|что|да|для|до|там|ещё|их|или|ко|меж|между|перед|передо|около|через|сквозь|для|при|я)';

		$any_quote    = "(?:$sym[lquote]|$sym[rquote]|$sym[lquote2]|$sym[rquote2]|&quot;|\")";
	
	    $replace = array(
			// Много пробелов или табуляций -> один пробел
			'~( |\t)+~' => ' ',

		     // Разносим неправильные кавычки
		     '~([^"]\w+)"(\w+)"~' => '$1 "$2"',
		     '~"(\w+)"(\w+)~' => '"$1" $2',

		     //Слепляем скобки со словами
		     '~\(\s~s' => '(',
			 '~\s\)~s' => ')',

			//Знаки с предшествующим пробелом... нехорошо!
			'~('.$phrase_end.') +('.$punctuation.'|'.$sym['hellip'].')~' => '$1$2',
			'~('.$punctuation.')('.$phrase_begin.')~' => '$1 $2',

		     //Неразрывные названия организаций и абревиатуры форм собственности
			'~('.$abbr.')\s+"(.*)"~' => $sym['lnowrap'].'$1 "$2"'.$sym['rnowrap'],

			 //Нельзя отрывать имя собственное от относящегося к нему сокращения.
			 //Например: тов. Сталин, г. Воронеж
			 //Ставит пробел, если его нет.
			 '~([гГ]|гр|тов|пос)\.\s*([А-Я]+)~s' => '$1.'.$sym['nbsp'].'$2',

			 //Не отделять стр., с. и т.д. от номера.
			 '~(стр|с|табл|рис)\.\s*(\d+)~si' => '$1.'.$sym['nbsp'].'$2',

			 //Не разделять 2007 г., ставить пробел, если его нет
			'~([0-9]+)\s*([гГ])\.~s' => '$1'.$sym['nbsp'].'$2.',

			 // Превращаем кавычки в ёлочки. Двойные кавычки склеиваем.
			 "~(?<=\s|^|[>(])($html_tag*)($any_quote+)($html_tag*$phrase_begin$html_tag*)~"	=> '$1'.$sym['lquote'].'$3',
			 "~($html_tag*$phrase_end$html_tag*)($any_quote+)($html_tag*$phrase_end$html_tag*|\s|[,<-])~"	=> '$1'.$sym['rquote'].'$3',

			// Знак дефиса или два знака дефиса подряд — на знак длинного тире.
			// + Нельзя разрывать строку перед тире, например: Знание — сила, Курить — здоровью вредить.
			'/(\s+|^)(--?)(?=\s)/' => '$1'.$sym['mdash'],

			// Знак дефиса, ограниченный с обоих сторон цифрами — на знак короткого тире.
			'/(?<=\d)-(?=\d)/' => $sym['ndash'],

			// Нельзя оставлять в конце строки предлоги и союзы
			'/(?<=\s|^|\W)'.$prepos.'(\s+)/i' => '$1'.$sym['nbsp'],

			// Нельзя отрывать частицы бы, ли, же от предшествующего слова, например: как бы, вряд ли, так же.
			"/(?<=\S)(\s+)(ж|бы|б|же|ли|ль|либо|или)(?=$html_tag*[\s)!?.])/i" => $sym['nbsp'].'$2',

			// Неразрывный пробел после инициалов.
			'~([А-ЯA-Z]\.)\s?([А-ЯA-Z]\.)\s?([А-Яа-яA-Za-z]+)~s' => '$1$2'.$sym['nbsp'].'$3',

			// Русские денежные суммы, расставляя пробелы в нужных местах.
			'~(\d+)\s?(руб.)~s'	=>	'$1'.$sym['nbsp'].'$2',
			'~(\d+)\s?(млн.|тыс.)?\s?(руб.)~s'	=>	'$1'.$sym['nbsp'].'$2'.$sym['nbsp'].'$3',

			// Неразрывные пробелы в кавычках
			//"/($sym[lquote]\S*)(\s+)(\S*$sym[rquote])/U" => '$1'.$sym["nbsp"].'$3',

			// От 2 до 5 знака точки подряд - на знак многоточия (больше - мб авторской задумкой).
			"~$hellip~" => $sym['hellip'],

	        // Знаки (c), (r), (tm)
			'~\((c|с)\)~i' 	=> $sym['copy'],
			'~\(r\)~i' 	=>	$sym['reg'],
			'~\(tm\)~i'	=>	$sym['trade'],

			// Спецсимволы для 1/2 1/4 3/4
			'~\b1/2\b~'	=> $sym['1/2'],
			'~\b1/4\b~' => $sym['1/4'],
			'~\b3/4\b~' => $sym['3/4'],

			"~'~"		=>	$sym['apos'],
			// Размеры 10x10, правильный знак
			'~(\d+)[x|X|х|Х|*](\d+)~' => '$1'.$sym['multiply'].'$2',

			//Телефоны
			'~тел[:.] ?(\d+)~ie' => "'<span style=\"white-space:nowrap\">тел: '.typo_phone('$1').'</span>'",
			//+-
			'~([^\+]|^)\+-~' => '$1'.$sym['plusmn'],
			//arrows
			//'~([^-]|^)->~' => '$1'.$sym['rarr'],
			//'~<-([^-]|$)~' => $sym['larr'].'$1',

			//Номер версии программы пишем неразрывно с буковкой v.
			'~([vв]\.) ?([0-9])~i' => '$1'.$sym['nbsp'].'$2',
			'~(\w) ([vв]\.)~i' => '$1'.$sym['nbsp'].'$2'
		);

	$str = preg_replace(array_keys($replace), array_values($replace), $str);
	return $str;
}

      function hyphenation($text)
      {
    #буква (letter)
    $l = '(?:[ёЁА-я]  #ё Ё А-я (все) 
           | [a-zA-Z] 
           )'; 

    #гласная (vowel)
    $v = '(?:[аеиоуыэюяё]  #аеиоуыэюяё (гласные) 
           | [АЕИОУЫЭЮЯЁ]         #АЕИОУЫЭЮЯЁ (гласные) 

           )'; 

    #согласная (consonant) 
    $c = '(?:[бвгджзклмнпрстфхцчшщ]  #бвгджзклмнпрстфхцчшщ (согласные) 
           | [БВГДЖЗКЛМНПРСТФХЦЧШЩ]                #БВГДЖЗКЛМНПРСТФХЦЧШЩ (согласные) 

           )'; 

    #специальные 
    $x = '(?:[ЙЪЬйъь])';   #ЙЪЬйъь (специальные) 

    /* 
    #алгоpитм П.Хpистова в модификации Дымченко и Ваpсанофьева 
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
    $text = preg_replace($rules, "$1\xad$2", $text); // не \xc2\xad 
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