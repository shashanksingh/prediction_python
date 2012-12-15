<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gcm {

	private $apiKey = "AIzaSyD2HvCoejOq4FttFzWWAfiuAfoREVzG0yY";
	public function device_gcm($regid,$message)	
	{
			
		
		$registrationIDs = array($regid);
		//array( "APA91bGQdkIMs-bStjOoZdfZo4zAtiAOgAaLmrwbxI9PQKoC2BoFY-SGKpm5wjnAoSYV4EtaefGq6TNT19fY7Lfb8Baav-492sTHsPKxbQAWV8QGVyPAz0U7D6b9G8Ah7I2kK25V2Tp0hiHY8f74Ck4JtpPuH4X_0Q" );
		$url = 'https://android.googleapis.com/gcm/send';
		$fields = array(
                'registration_ids'  => $registrationIDs,
                'data'              => array( "msg" => $message ),
                );

		$headers = array( 
                    'Authorization: key='.$this->apiKey ,
                    'Content-Type: application/json'
                );

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );
		curl_setopt( $ch, CURLOPT_VERBOSE, true );
		$result = curl_exec($ch);
		curl_close($ch);
		
		return $result;
   }
		
}
