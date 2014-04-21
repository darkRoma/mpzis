<?php
  session_start();
   $chk=$_GET['chk'];
   if($chk==$_SESSION['chili_captcha'])
   {
      $_SESSION['captcha_passed']='true';
      echo 'ok';
   }else{
      echo 'error';
      $_SESSION['captcha_passed']='false';
      session_destroy();
   }
?>