<?php
  require_once("bootstrap.php");

  use Xhamps\APN\Notification;

  $send = false;
  if(isset($_POST['mensagem'])){

    $db = new PDO('mysql:host=localhost;dbname=device', 'root', 'root');

    $notification = new Notification($db , Notification::SANDBOX , 'cert/PushDevCert.pem' );
    $notification->sendMenssage($_POST['mensagem']);
    $send  =true;
  }



  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>
<body>
  <?php if($send) echo "Message sent"; ?>
<div id="mainContent">
<form method="post" action="">
Mensagem: <input type="text" name="mensagem"/>
<input type="submit" value="Enviar"/>
</form>
</div>
</body>
</html>