<?php
//ini_set('session.save_path','/www/danmillm/users/danmillm-mpzis/tmp');
require_once('../include/stdfx.php');

$eng = new Engine();
$c_args = $eng->InitConf("conf.ini");

$sql = new cMysql($c_args["sql_host"],$c_args["sql_db"],$c_args["sql_login"],$c_args["sql_pass"]);
$tab = $c_args['sql_users_tab'];

if ((isset($_POST['login']))&&(isset($_POST['pass'])))
{
$login=$_POST['login'];
$pass=$_POST['pass'];

$s="SELECT * FROM $tab WHERE `login`='$login'";
$ret = $sql->query($s);


 if (mysql_numrows($ret)>0)
 {
  $row = mysql_fetch_assoc($ret);
  
  
  //print_r($_POST);
  //print $p;
  
  if (MD5($pass) == $row['pass'])
  {
    @session_start();
 	  @session_register("login");
	  @session_register("pass");  
	  @session_register("access");
      @session_register("e-mail");
	  @session_register("fio");
	  @session_register("photo");
	  @session_register("id");
	  	
	$_SESSION['login']	=$row['login'];
 	$_SESSION['pass']	=$row['pass'];
    $_SESSION['logged']	=true;
    $_SESSION['access']	=$row['access'];
    $_SESSION['e-mail']	=$row['e-mail'];
    $_SESSION['fio']	=$row['fio'];
    $_SESSION['photo']	=$row['photo'];
    $_SESSION['id']		=$row['id'];
    
    setcookie ("username", "$login", time()+2592000); 
    header("Location: admin.php"); 
	 //print "redirect";   
 }else{
  echo '
<link href="include/style.css" rel="stylesheet" type="text/css" />
<title>Вход</title>
<style type="text/css" media="screen">@import url(../css/niceforms-default.css);</style>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
</head>
<script>
function showEdit()
{
 document.getElementById("edit").innerHTML="<input type=text name=login length=10>";
  document.getElementById("imgU").src="include/userR.gif";
}
</script>
<body>
';
  echo '<div class="login"><div class=error>Неверный пароль или имя пользователя</div><form action="#" Method=POST>';
  if (isset($_COOKIE["username"])){echo "<img id='imgU' src='include/user.gif' title='Логин' alt='Логин'><div id=edit><input type='hidden' name='login' value='".$_COOKIE["username"]."'>".$_COOKIE['username']."<br><a href='javascript:void(0);' onclick='showEdit();' title='Нажмите здесь, если указанный выше логин не Ваш'>Это не вы?</a></div>";}else{ echo "<img src='include/userR.gif' title='Логин' alt='Логин'><input type=text maxlength=20 name='login'>";}  
  echo "<img src='include/keyR.gif' alt='Пароль' title='Пароль'><input type=password maxlength=32 name='pass'>
<input type=submit value='Войти'>
</form>
  </div>";  
 }
}else{
  echo '
<link href="include/style.css" rel="stylesheet" type="text/css" />
<title>.minima&sup2;&mdash;Вход</title>
<style type="text/css" media="screen">@import url(../css/niceforms-default.css);</style>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
</head>
<script>
function showEdit()
{
 document.getElementById("edit").innerHTML="<input type=text name=login length=10>";
  document.getElementById("imgU").src="include/userR.gif";
}
</script>
<body>
';
  echo '<div class="login"><div class=error>Неверный пароль или имя пользователя</div><form action="#" Method=POST>';
  if (isset($_COOKIE["username"])){echo "<img id='imgU' src='include/user.gif' title='Логин' alt='Логин'><div id=edit><input type='hidden' name='login' value='".$_COOKIE["username"]."'>".$_COOKIE['username']."<br><a href='javascript:void(0);' onclick='showEdit();' title='Нажмите здесь, если указанный выше логин не Ваш'>Это не вы?</a></div>";}else{ echo "<img src='include/userR.gif' title='Логин' alt='Логин'><input type=text maxlength=20 name='login'>";}  
  echo "<img src='include/keyR.gif' alt='Пароль' title='Пароль'><input type=password maxlength=32 name='pass'>
<input type=submit value='Войти'>
</form>
  </div>";  
 }
}
if ((!isset($_POST['login']))||(!isset($_POST['pass']))):
?>
<html>
<head>
<link href="include/style.css" rel="stylesheet" type="text/css" />
<link href="../css/niceforms-default.css" rel="stylesheet" type="text/css" />
<title>Вход</title>



<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
</head>
<script>
function showEdit()
{
 document.getElementById('edit').innerHTML="<input type=text name=login length=10>";
}
</script>
<body>

<div class='login'>
<form action='#' Method=POST>
<?php
if (isset($_COOKIE["username"])){echo "<img src='include/user.gif' title='Логин' alt='Логин'><div id=edit><input type='hidden' name='login' value='".$_COOKIE["username"]."'>".$_COOKIE['username']."<br><a href='javascript:void(0);' onclick='showEdit();' title='Нажмите здесь, если указанный выше логин не Ваш'>Это не вы?</a></div>";}else{ echo "<img src='include/user.gif' title='Логин' alt='Логин'><input type=text maxlength=20 name='login'>";}?>
<img src='include/key.gif' alt='Пароль' title='Пароль'><input title='Пароль' type=password maxlength=32 name='pass'>
<input type=submit value='Войти'>
</form>
</div>
<?php endif;?>
</body>
</html>
