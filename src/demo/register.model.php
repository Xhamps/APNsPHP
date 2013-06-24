<?php

  require_once("bootstrap.php");

  use Xhamps\APN\RegisterDevice;



  $db = new PDO('mysql:host=localhost;dbname=device', 'root', 'root');

  $token = '49dc30989de7381dfb4ed4374bd13f43';

  $register = new RegisterDevice($db);
  $register->setToken($token);
  $register->save($_GET);

