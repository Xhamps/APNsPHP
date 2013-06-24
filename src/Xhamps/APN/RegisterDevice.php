<?php

namespace Xhamps\APN;

use Xhamps\APN\Exception\RegisterException;

/**
 * @author Xhamps <xhamps@gmail.com>
 */

class RegisterDevice {
	private $_db;

	private $_token = '';
	function __construct($db){
		$this->_db = $db;
	}
	public function setToken($token){
		$this->_token = $token;
	}
	public function save($args){

		if( $this->validToken($args['token']) &&
				$this->validDeviceToken($args['devicetoken'] ) &&
				$this->validDeviceName($args['devicename'] ) &&
				$this->validDeviceModel($args['devicemodel'] ) &&
				$this->validDeviceVersion($args['deviceversion'] )
				){

			$register = $this->_registerDevice(
							$args['devicetoken'],
							$args['devicename'],
							$args['devicemodel'],
							$args['deviceversion']
						);

			if(! $register) throw new RegisterException('Error to store the device.');
		}

	}
	private function _registerDevice( $devicetoken, $devicename, $devicemodel, $deviceversion){

		try{
			// store device for push notifications
			$this->_db->exec("SET CHARACTER SET utf8");
			$stmt =$this->_db->prepare("INSERT INTO apn_devices (id , devicetoken, devicename, devicemodel, deviceversion, status, created, modified ) VALUES
																													 (NULL , :devicetoken, :devicename, :devicemodel, :deviceversion, 'active' , :created, :modified)");

			$stmt->bindParam(':devicetoken', $devicetoken);
			$stmt->bindParam(':devicename', $devicename);
			$stmt->bindParam(':devicemodel', $devicemodel);
			$stmt->bindParam(':deviceversion', $deviceversion);
			$date = date("Y-m-d H:i:s");
			$stmt->bindParam(':created', $date);
			$stmt->bindParam(':modified', $date);

			$stmt->execute();

			echo "Save";

			return true;
		}catch(PDOException $err){
			return false;
		}

	}
	private function validToken($token){
		if(strlen($token) == 0)throw new RegisterException('Token must not be blank.');
		else if($token != $this->_token)  throw new RegisterException('Invelid token.');

		return true;
	}
	private function validDeviceToken($token){
		if(strlen($token) == 0)  throw new RegisterException('Device Token must not be blank.');
		else if(strlen($token)!=64)  throw new RegisterException('Device Token must be 64 characters in length.');

		return true;
	}
	private function validDeviceName($name){
		if(strlen($name) == 0) throw new RegisterException('Device Name must not be blank.');

		return true;
	}
	private function validDeviceModel($model){
		if(strlen($model) == 0) throw new RegisterException('Device Model must not be blank.');

		return true;
	}
	private function validDeviceVersion($version){
		if(strlen($version) == 0) throw new RegisterException('Device Version must not be blank.');

		return true;
	}
}