var FCKPagebreak = function(name)  {  
	this.Name = name;  
}  
FCKPagebreak.prototype.Execute = function()  {  
	FCK.InsertHtml('<span title="Page Break" style="width:100%; FONT-SIZE: 1px; PAGE-BREAK-AFTER: always; VERTICAL-ALIGN: middle; HEIGHT: 1px; BACKGROUND-COLOR: #c0c0c0"></span>');
}  
 
FCKPagebreak.prototype.GetState = function()  {  
	return FCK_TRISTATE_OFF;  
}

FCKCommands.RegisterCommand( 'Pagebreak', new FCKPagebreak('Pagebreak'));

var oPagebreakItem = new FCKToolbarButton( 'Pagebreak', FCKLang.PagebreakBtn, null, null, false, true);
oPagebreakItem.IconPath = FCKPlugins.Items['pagebreak'].Path + 'pagebreak.gif';
FCKToolbarItems.RegisterItem( 'Pagebreak', oPagebreakItem );
