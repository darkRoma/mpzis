<html>
<head>
<base href="/">
<link rel='stylesheet' href='templates/style/style.css' type='text/css'>
<title>Сторінку не знайдено</title>
<script type='text/javascript' src='templates/js/jquery.js'></script>
<script type='text/javascript' src='templates/js/jqcolor.js'></script>
<script type='text/javascript' src='templates/js/jquery.cookie-1.4.1.min.js'></script>
<script type='text/javascript' src='templates/js/setCookieFunc.js'></script>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/> 
</head>
<body>
<div class='header'><a href='#' class='logo'></a>
<div class='years'></div>
<div class='title'><span class='row1'><span id='noc'></span>Міжнародна науково-практична конференція</span>
<span class='row2'>«Математичне і програмне забезпечення</span>
<span class='row3'>інтелектуальних систем»</span>
</div>
</div>
<div class='main_links'>
  <a href='javascript:setCookieRu()' class='flag_ru'></a>
  <a href='javascript:setCookieUa()' class='flag_ua'></a>
  <a href='#' class='home'></a>
  <a href='contacts/' class='contacts'></a>
</div>

<div class='wrap_info'>
Сторінку  
<?php
  echo '<em>'.$_SERVER['HTTP_HOST'].$_SERVER['REDIRECT_URL'].'</em>';
?>
 не знайдено (помилка 404).<br>
 
 <ul class='links'>
<li><a href='register/'>Взяти участь</a></li>
<li><a href='conditions/'>Умови участі</a></li>
<li><a href='members/'>Учасники</a></li>
<li><a href='program/'>Програма</a></li>
<li><a href='orgcommitee/'>Оргкомітет</a></li>
<li><a href='contacts/'>Контакти</a></li>
</ul>
</div>
<div class='clear'></div>
<div class='footer' style="height:30px"><a href='http://chili.co.ua' target='_blank' style='color: silver;'>Розробка та підтримка сайту<img src='chili_logos.jpg' style='margin-bottom:-12px;margin-left:5px' border=0 alt='Разработка сайта Креативное агентство «chili»'></a></div>
<script type='text/javascript'>
function romanize (num) {
	if (!+num)
		return false;
	var	digits = String(+num).split(""),
		key = ["","C","CC","CCC","CD","D","DC","DCC","DCCC","CM",
		       "","X","XX","XXX","XL","L","LX","LXX","LXXX","XC",
		       "","I","II","III","IV","V","VI","VII","VIII","IX"],
		roman = "",
		i = 3;
	while (i--)
		roman = (key[+digits.pop() + (i * 10)] || "") + roman;
	return Array(+digits.join("") + 1).join("M") + roman;
}

  $(document).ready(function(){
    //years builder
      var year_start=2003;
      var d = new Date();
      var year_end=d.getFullYear();
      var number_of_conf=year_end-year_start+1;
      $('#noc').html(romanize(number_of_conf));
      //alert(number_of_conf);
      for(i=0;i<number_of_conf;i++)
      {
        var obj=document.createElement('div');
        obj.innerHTML=year_start+i;
        var br=document.createElement('br');
        obj.appendChild(br);
        var subobj=document.createElement('div');
        subobj.className='year_rect';
        obj.appendChild(subobj);
        $('.years').append(obj);
      }
      var total_width=$('.years').width();
      var total_height=222;
      
      $('.years>div').each(function(el){
        x=el*(0.16)*(total_width*el*100/number_of_conf)/100+20;
        y=-0.00088*x*x+0.40568*x+24,3257;
        if($('.years').offset().left<100)
        {
          x=x+$('.years').offset().left+$(window).width()*0.03+128+20;
        }
        $(this).css('font-size',0.8+1/(number_of_conf/el)+'em').css('left',x).css('top',y);
        if(!$.browser.msie)
        {
          $(this).css('opacity',1/(number_of_conf/el)+0.1);
        }
        if(el!=number_of_conf-1){
        $(this).children('div').css('width',(30*el*100/number_of_conf)/100).css('height',(30*el*100/number_of_conf)/100).css('margin-left',($(this).width()-(30*el*100/number_of_conf)/100)/2);
        }
        if(el==number_of_conf-1)
        {
          $(this).css('font-size','2em').click(function(){document.location='/'}).css('cursor','pointer');
          $('.title').css('position','absolute').css('left',x+50+$('.years').offset().left).css('top',y+$('.years').offset().top+$(this).height()+50);
        }
      });
      $('.years>div:last').css('width','50').css('height','50').addClass('current');
    //eo years builder
    $('ul.small_links a').each(function(){
      var srt=document.location.href;
      srt=srt.replace('/','');
      if($(this).attr('href')==srt) $(this).parent().css('display','none');
    });
  });
</script>
<!-- Yandex.Metrika counter -->
<div style="display:none;"><script type="text/javascript">
(function(w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter10190617 = new Ya.Metrika({id:10190617, enableAll: true});
        }
        catch(e) { }
    });
})(window, "yandex_metrika_callbacks");
</script></div>
<script src="//mc.yandex.ru/metrika/watch.js" type="text/javascript" defer="defer"></script>
<noscript><div><img src="//mc.yandex.ru/watch/10190617" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
</body>
</html>