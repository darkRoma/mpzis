/**
 * @author Eugene Kozachenko
 */

/**
 * Функция для администрирования статей. Показывает форму выбора картинки и загрузчик.
 * Активирует загрузчик на iFrame и назначает для него действие на окончание загрузки.
 * Назначает списку существующих картинок событие на нажатие.
 * 
 * оnSubmit (начало загрузки) показывает полупрозрачное полотно, говорящее о том, что 
 * идет загрузка.<br>
 * По завершении загрузки, (при помощи данных загруженных в iFrame) проверяет статус 
 * загрузки картинки и производит соотвтествующие статусу действия.<br>
 * <br>
 * Статус картинки заносится файлом upload.php в div id=status<br>
 * Результат загрузки — div id=result<br>
 * <br>
 * Если статус = 220, то загрузкa успешна и результат заносится в src картинки и в поле 
 * art_prev_pic_URL, и которого потом будет записано в БД.
 * <br>
 * При любом другом статусе загрузки будет выдано сообщение об ошибке при загрузке.
 */
function chiliShowPicSelect()
{
  var id = document.getElementById('picsSelector');
  id.style.display='block';
  id=document.getElementById('uplForm');
  id.target=document.getElementById("frame").name;
  alert(id.target);
  //Событие на загрузку файла.
  document.getElementById("uplForm").onsubmit=function()
  {
  	var par=document.getElementById("picsSelector");
	var loader=document.createElement('div');
	par.style.position='relative';
	loader.style.position='absolute';
	loader.id='tempLoading';
	loader.style.left='0px';
	loader.style.top='0px';
	loader.style.width='100%';
	loader.style.height='100%';
	loader.style.backgroundColor='#ffffff';
	//opacity
	loader.style.opacity = (0.7);
    loader.style.MozOpacity = (0.7);
    loader.style.KhtmlOpacity = (0.7);
    loader.style.filter = "alpha(opacity=" + 70 + ")";
    var img=document.createElement('img');
	img.src='admin_article/i/ajax.gif';
	img.border=0;
	img.align='center';
	img.valign='middle';
	loader.appendChild(img);
	par.appendChild(loader);
  }
  document.getElementById("frame").onload=function()
  {
  	document.getElementById("tempLoading").parentNode.removeChild(document.getElementById("tempLoading"));
	var status=frames[0].document.getElementById('status').innerHTML;
	if (status == '220') {
		var result = frames[0].document.getElementById('result').innerHTML;
		document.getElementById('art_prev_pic').src = '' + result;
		document.getElementById('art_prev_pic_URL').value = result;
  	    var id = document.getElementById('picsSelector');
 		id.style.display='none';
 		
 		//chiliShowPicSelect();
	}else{
		chiliAlert('При загрузке картинки-превью произошла ошибка.','error');
	}
  }
  //Событие onclick для уже загруженных картинок
  var ar=document.getElementById("pics").getElementsByTagName('img');
  for (i=0;i<ar.length;i++)
  {
  	ar[i].onclick=function()
	{
		document.getElementById('art_prev_pic').src = '' + this.src;
		document.getElementById('art_prev_pic_URL').value = this.src;
  	    var id = document.getElementById('picsSelector');
 		id.style.display='none';
	}
  }
}
