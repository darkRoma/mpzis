/**
 * @author Eugene Kozachenko
 * e.kozachenko@chili.co.ua
 */



function test(a,c,d)
{
    for (i=0;i<a.length;i++)
	{
	alert(a[i]);		
	}
	for (i=0;i<c.length;i++)
	{
	alert(c[i]);		
	}
    for (i=0;i<d.length;i++)
	{
	alert(d[i]);		
	}
}

/**
 * Просматривает все элементы checkbox с классами author (class='author') и category (class='category')
 * Проверяет какие из них отмечены (checked) и создает массивы по классам authors, categories, которые 
 * содержат имена (name) отмеченных элементов checkbox. Находит элементы с id d1_d, d1_m, d1_y, d2_d, d2_m, 
 * d2_y - которые соответственно являются днем, месяцем и годом начальной и конечной даты.
 * <br><br>
 * Функция, указанная в funcToCall получит такие данные: <br>
 * 1) массив authors=['author1','author2']<br>
 * 2) массив categories=['category1','category2']<br>
 * 3) массив dates=['yyyy-mm-dd(start)','yyyy-mm-date(end)']<br>
 * 
 * @param {String} funcToCall — Определяет функцию, которой будут переданы массивы с отмеченными элементами и датой 
 * @example getFilterData('xajax_filterArticles'); 
 * Найдёт все отмеченные элементы checkbox с авторами и категориями, разобьет их на 2 отдельных массива,
 * сформирует начальную и конечную дату (в отдельный массив) и передаст все 3 массива в функцию xajax_filterArticles
 */
function chiliFilterData(args,args2,funcToCall)
{
	var auth = getElementsByClass(args2[0],null,'input');//'author'
	var cats = getElementsByClass(args2[1],null,'input');//'category'
	var authors = new Array();
	var categories = new Array();
	var dates = new Array();
	var k=0;
	//authors cycle
	for (i=0;i<auth.length;i++)
	{
		if (auth[i].checked)
		{
			authors[k]=auth[i].name;
			k++;
		}
	}
	//categories cycle
	k=0;
	for (i=0;i<cats.length;i++)
	{
		if (cats[i].checked)
		{
			categories[k]=cats[i].name;
			k++;
		}
	}
	//date gethering	
	//date1
	//var day=document.getElementById(args[0]).value;
	//var month=document.getElementById(args[1]).value;
	//var year=document.getElementById(args[2]).value;		
	//dates[0]=year+'-'+month+'-'+day;//start date
	//date2
	//day=document.getElementById(args[3]).value;
	//month=document.getElementById(args[4]).value;
	//year=document.getElementById(args[5]).value;		
	//dates[1]=year+'-'+month+'-'+day;//end date
	//result
	ev=funcToCall+"([";
	for(i=0;i<authors.length-1;i++)
	{
		ev+="'"+authors[i]+"',";
	}
	ev+="'"+authors[i]+"'],[";
	for(i=0;i<categories.length-1;i++)
	{
		ev+="'"+categories[i]+"',";
	}
	ev+="'"+categories[i]+"'],[";
		for(i=0;i<dates.length-1;i++)
	{
		ev+="'"+dates[i]+"',";
	}
	ev+="'"+dates[i]+
	"'],'"	+document.getElementById('tpl_path').innerHTML
	+"','"	+document.getElementById('cur_page').innerHTML
	+"');";
	//alert(ev);
	eval(ev);
}

