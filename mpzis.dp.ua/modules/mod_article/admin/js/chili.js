/**
 * @author Eugene Kozachenko
 */
/**
 * Функция выводит пользователю сообщение. Она не мешает и не «мозолит» глаза
 * пользвателю всплывающими сообщениями ОС, а (в зависимости от стиля) показывает
 * сообщение в свободной области экрана.
 * Сообщения можно различать цветом, иконками и т.п. по статусу.
 * Статусов можно задават неограниченное количество (главное, чтобы все они были описаны 
 * в стилях.
 * @param {String} msg — текст сообщения
 * @param {String} state — статус
 * @example chiliAlert('hello world','info');
 */
function chiliAlert(msg,state)
{
  var element = document.createElement('div');
  element.innerHTML=msg;
  element.className=state+'_alert';
  element.id='chiliMSG';
  var a=document.createElement('a');
  a.href='javascript: void(0)';
  a.onclick=function()
  {
  	this.parentNode.parentNode.removeChild(this.parentNode);
  }
  a.innerHTML='x';
  element.appendChild(a);
  document.body.appendChild(element);
  var t=setTimeout("var i=document.getElementById('chiliMSG'); if (i!=null){i.parentNode.removeChild(i);}",8000);
  delete(t);
}