<?php

namespace Xhamps\APN;

use Xhamps\APN\Exception\NotificationException;

/**
 * @author Xhamps <xhamps@gmail.com>
 */

class Notification {
  const PRODUCTION = "production";
  const SANDBOX = "sandbox";
  private $url;
  private $_db;
  private $_environment;
  private $_certificate;
  private $ssl;
  private $payload;
  function __construct($db, $environment , $certificateFile){

    $this->_db = $db;

    if(!empty($environment) && ($environment ==  Notification::PRODUCTION || $environment ==  Notification::SANDBOX))$this->_environment = $environment ;
     else  throw new NotificationException("Certificaterti invalid!");

    if(!empty($certificateFile) && file_exists($certificateFile)) $this->_certificate = $certificateFile;
    else  throw new NotificationException("Certificaterti invalid!");

    $this->url = array(
        'production' => 'ssl://gateway.push.apple.com:2195',
        'sandbox' => 'ssl://gateway.sandbox.push.apple.com:2195'
      );

    $this->payload['aps'] = array('sound' => 'default');
  }
  public function sendMenssage($text){
    $this->payload['aps']['alert'] = stripslashes($text);
    $payload = json_encode($this->payload);

    $this->createStream();

    $stmt =$this->_db->prepare("SELECT *  FROM  apn_devices WHERE  status = 'active'");
    $stmt->execute();
    for($i=0; $row = $stmt->fetch(); $i++){
      $msg = chr(0) . pack('n', 32) . pack('H*', $row['devicetoken']) . pack('n', strlen($payload)) . $payload;
      $result = fwrite($this->ssl, $msg, strlen($msg));
    }
    unset($stmt);
    unset($row);
  }
  private function createStream() {
    $ctx = stream_context_create();
    stream_context_set_option($ctx, 'ssl', 'local_cert', $this->_certificate );
    $this->ssl = stream_socket_client($this->url[$this->_environment], $error, $errorString, 100, (STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT), $ctx);

    if(!$this->ssl){
      throw new NotificationException("Failed to connect to APNS: {$error} {$errorString}.");
      unset($this->ssl);
      return false;
    }
    return $this->ssl;
  }


}