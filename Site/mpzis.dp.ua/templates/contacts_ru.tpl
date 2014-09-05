<html>
<head>
<base href="/">
<link rel='stylesheet' href='templates/style/style.css' type='text/css'>
<title>
Контакти - МПЗИС-
<?php 
echo date('Y');
?>
</title>
<script type='text/javascript' src='templates/js/jquery.js'></script>
<script type='text/javascript' src='templates/js/jqcolor.js'></script>
<script type='text/javascript' src='templates/js/jquery.scrollto-min.js'></script>
<script type='text/javascript' src='templates/js/jquery.cookie-1.4.1.min.js'></script>
<script type='text/javascript' src='templates/js/setCookieFunc.js'></script>
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=ABQIAAAAEsx8-wbt3MaGaw8FeIfA0RRMqp5vcLZQV-ku1GnBdGbonP8gbhS9BbnTk-GzxfQJ4WSJgaiV1vyg_g" type="text/javascript"></script>
</script>
    <script type="text/javascript"> 
    var map
    function initialize() {
      if (GBrowserIsCompatible()) {
        var coords = new GLatLng(48.460657,35.07102);
        map = new GMap2(document.getElementById("map_canvas"));
        map.setCenter(coords, 15);
        map.setMapType(G_HYBRID_MAP);
        var marker = new GMarker(coords);
        map.addOverlay(marker);
        map.addControl(new GLargeMapControl());
        GEvent.addListener(marker, "click", function() {
  			map.openInfoWindowHtml(coords, '<b>МПЗІС-2009</b><br>Місце знаходження: Палац студентів ДНУ ім. О. Гончара'); 
		});
        map.openInfoWindowHtml(coords, '<b>МПЗІС-2009</b><br>Місце знаходження: Палац студентів ДНУ ім. О. Гончара'); 
      }
    }
 
    </script> 
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/> 
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
<ul class='small_links'>
<li><a href='register/'>Взяти участь</a></li>
<li><a href='conditions/'>Умови участі</a></li>
<li><a href='members/'>Учасники</a></li>
<li><a href='program/'>Програма</a></li>
<li><a href='orgcommitee/'>Оргкомітет</a></li>
<li><a href='archieve/'>Архів</a></li>
</ul>
<div class='wrap_info'>
[chili]article[/chili]
<div id="map_canvas" style="width: 100%; height: 700px"></div>
</div>
<div class='clear'></div>
<div class='footer'><a href='http://chili.co.ua' target='_blank' style='color: silver'>Розробка та підтримка сайту<img src='http://chili.co.ua/logos/101ball_chililogo.gif' border=0 alt='Разработка сайта Креативное агентство «chili»'></a></div>
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
          //years builder
      var year_start=2003;
      var d = new Date();
      var year_end=d.getFullYear();
      var number_of_conf=year_end-year_start+1;
      $('#noc').html(romanize(number_of_conf));
      number_of_conf=7;
      //alert(number_of_conf);
      for(i=0;i<number_of_conf;i++)
      {
        var obj=document.createElement('div');
        obj.innerHTML=year_end-(6-i);
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
        var prior_version=$.browser.version;
        prior_version=prior_version.split('.');
        prior_version=prior_version[0]+'.'+prior_version[1];
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
          $(this).css('font-size','2em').click(function(){document.location='http://mpzis.dp.ua/'}).css('cursor','pointer');
          $('.title').css('position','absolute').css('left',x+50+$('.years').offset().left).css('top',y+$('.years').offset().top+$(this).height()+50);
        }
      });
      $('.years>div:last').css('width','50').css('height','50').addClass('current');
    //eo years builder
    initialize();
    $('#organizers_map').click(function(){ 
    $.scrollTo('#map_canvas',{duration:400});
    var coords=new GLatLng(48.458409, 35.056021);
      map.panTo(coords);
      var marker = new GMarker(coords);
        map.addOverlay(marker);
        map.openInfoWindowHtml(coords, '<b>ФПМ ДНУ ім. О. Гончара</b><br>буд. 35, корпус 3, кiмн.35<br> пр.К.Маркса, м. Днiпропетровськ, 49044, Україна'); 
        GEvent.addListener(marker, "click", function() {
  			 map.openInfoWindowHtml(coords, '<b>ФПМ ДНУ ім. О. Гончара</b><br>буд. 35, корпус 3, кiмн.35<br> пр.К.Маркса, м. Днiпропетровськ, 49044, Україна');
		});
    });
    $('#univ_map').click(function(){ 
    $.scrollTo('#map_canvas',{duration:400});
    var coords=new GLatLng(48.433173, 35.043157);
      map.panTo(coords);
      var marker = new GMarker(coords);
        map.addOverlay(marker);
        map.openInfoWindowHtml(coords, '<b>ДНУ ім. О. Гончара</b><br>пр. Га­гарiна 72,<br> Днiпропетровськ, 49050, Україна'); 
        GEvent.addListener(marker, "click", function() {
  			  map.openInfoWindowHtml(coords, '<b>ДНУ ім. О. Гончара</b><br>пр. Га­гарiна 72,<br> Днiпропетровськ, 49050, Україна'); 
		});
    });
    $('#palac_map').click(function(){ 
    $.scrollTo('#map_canvas',{duration:400});
    var coords = new GLatLng(48.460657,35.07102);
      map.panTo(coords);
      var marker = new GMarker(coords);
        map.addOverlay(marker);
        map.openInfoWindowHtml(coords, '<b>МПЗІС-2009</b><br>Місце знаходження: Палац студентів ДНУ ім. О. Гончара');  
        GEvent.addListener(marker, "click", function() {
  			 map.openInfoWindowHtml(coords, '<b>МПЗІС-2009</b><br>Місце знаходження: Палац студентів ДНУ ім. О. Гончара'); 
		});
    });
    $('#typography_map').click(function(){
    $.scrollTo('#map_canvas',{duration:400}); 
    var coords=new GLatLng(48.458409, 35.056021);
      map.panTo(coords);
      var marker = new GMarker(coords);
        map.addOverlay(marker);
        map.openInfoWindowHtml(coords, '<b>ФПМ ДНУ ім. О. Гончара</b><br>буд. 35, корпус 3, кiмн.35<br> пр.К.Маркса, м. Днiпропетровськ, 49044, Україна'); 
        GEvent.addListener(marker, "click", function() {
  			 map.openInfoWindowHtml(coords, '<b>ФПМ ДНУ ім. О. Гончара</b><br>буд. 35, корпус 3, кiмн.35<br> пр.К.Маркса, м. Днiпропетровськ, 49044, Україна');
		});
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