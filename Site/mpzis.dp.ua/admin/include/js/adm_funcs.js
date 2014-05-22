
/**
 * Ќаходит все элементы с заданным классом и возвращает их в массиве.
 * @param {Object} searchClass Ч искомый класс
 * @param {Object} domNode Ч им€ родительского объекта (объекта, внутри которого производитс€ поиск)
 * @param {Object} tagName Ч им€ искомого тэга, т.е. поиск может происходить не только по всем объектам, но и по отдельным классам
 * @example var arrayOfObjects=getElementsByClass("className","document","a"); 
 * Ќайдет все элементы a с классом className
 */
function getElementsByClass( searchClass, domNode, tagName) {
	if (domNode == null) domNode = document;
	if (tagName == null) tagName = 'input';
	var el = new Array();
	var tags = domNode.getElementsByTagName(tagName);
	var tcl = " "+searchClass+" ";
	for(i=0,j=0; i<tags.length; i++) {
		var test = " " + tags[i].className + " ";
		if (test.indexOf(tcl) != -1)
			el[j++] = tags[i];
	}
	return el;
}

function mtGetIdVal(id)
{
	var s = document.getElementById( id ).value;
	
	return s;
}


// изменение таблиц
function SaveModsInstall(mod,name,vals)
{
	var o_n=new String;
	var o_v=new String;
	
	for (var x=0;x<name.length;x++)
	{
		o_n += document.getElementById(name[x]).title+ ',';	
		o_v += document.getElementById(vals[x]).value+ ',';
	}
	
	xajax_kern_SaveModsInstallSett(mod,o_n,o_v);
}

// изменение путей
function SaveModsInstallDir(mod,name,vals)
{
	var o_n=new String;
	var o_v=new String;
	
	for (var x=0;x<name.length;x++)
	{
		o_n += document.getElementById(name[x]).title+ ',';	
		o_v += document.getElementById(vals[x]).value+ ',';
	}
	
	xajax_kern_SaveModsInstallSettDir(mod,o_n,o_v);
}

// изменение данных модул€

function SaveModsInstallVar(mod,name,vals)
{
	
	
	var o_n=new String;
	var o_v=new String;
	
	for (var x=0;x<name.length;x++)
	{
		o_n += document.getElementById(name[x]).title+ ',';	
		o_v += document.getElementById(vals[x]).value+ ',';
	}

	
	xajax_kern_SaveModsInstallSettVar(mod,o_n,o_v);
}




// —мотреть в функции отображени€ списка модулей ( €дро ) 

// Ёта функци€ откр./закр таблицу прав дл€ модул€

function ToggleModAccessTable( d_name )
{
	if(document.getElementById( d_name ).style.display == 'none')
		document.getElementById( d_name ).style.display = 'inline';
	else
		document.getElementById( d_name ).style.display = 'none';
}

function ToggleDiv( d_name )
{
	if(document.getElementById( d_name ).style.display == 'none')
		document.getElementById( d_name ).style.display = 'inline';
	else
		document.getElementById( d_name ).style.display = 'none';
}

/*
ƒелает активной панель админки

tags : dirs, prepareinterface
updates: ekwo added second attribute
*/

function adm_InlinePanel(pan_id)
{
	var page	=	$('#cur_page').html();
	var tpl 	=	$('#tpl_path').html();
	//$(pan_id+'_1').innerHTML = 'test'; 
	 
	//alert(pan_id);
	xajax_parser(["obj","page","tpl_path"],
				[pan_id,page,tpl]);
			
	//alert(pan_id +' => '+ $(pan_id).style.display);
				
	//adm_active_panel(pan_id);
}



function adm_active_panel(pan_id)
{
	var els  = getElementsByClass("panel",null,"div");

	
	for (var x=0;x<els.length;x++)
	{
		//alert('pan_id=>'+pan_id+'|' + els[x].id);
		if(els[x].id == pan_id)		els[x].style.display='inline';
		else						els[x].style.display='none';	
	}
}

function adm_PanelRedirect(pan,id)
{
	var page	=	$('cur_page').innerHTML;
	var tpl 	=	$('tpl_path').innerHTML;
	 

	xajax_parser(["obj","page","tpl_path","obj_redirect"],
				[pan,page,tpl,id]);
		
}

function generateArrayOfIds()
{
	var allTheCheckboxes = getElementsByClass('toRemCheckbox',null,'input');
	var str = "";
	var i = 0;
	var alreadyAppended = 0;
	
	for (i = 0;i<allTheCheckboxes.length;i++)
	{
		if (allTheCheckboxes[i].checked)
		{
			if(alreadyAppended == 1)
			{
				str = str + "," + allTheCheckboxes[i].name;
			}
			else
			{
				str = allTheCheckboxes[i].name;
				alreadyAppended = 1;
			}
		}
		
	}
	
	return str;
}



