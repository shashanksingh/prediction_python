<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sms{

	public function __construct(){
		$this->CI =& get_instance();
		$this->CI->load->library('sms_valuefirst');
	}

	//authentication
	private $gupshup_cred    = array('userid'=>'2000103861','pass'=>'TVFWcfAjx');


	public function gupshup_sms($numbers_csv,$msg)
	{


		$server_url = "http://enterprise.smsgupshup.com/GatewayAPI/rest";

		#curl for sending gupshup api request		
		$ch = curl_init();

		#adding field to GET Request
		$query  = "?method=sendMessage";
		$query .= "&send_to=".$numbers_csv;
		$query .= "&msg=".$msg;
		$query .= "&msg_type=TEXT";
		$query .= "&auth_scheme=plain";
		$query .= "&userid=".$this->gupshup_cred['userid'];
		$query .= "&password=".$this->gupshup_cred['pass'];
		$query .= "&v=1.1";
		$query .= "&format=json";

		#final url
		curl_setopt($ch, CURLOPT_URL, $server_url.$query);

		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		#executing query
		$reply = curl_exec($ch);
		curl_close($ch);

		return $reply;

	}

	public function valuefirst_sms($numbers_csv,$msg)
	{
		$admin_recepients	= array();
		$to = array_merge((array)$numbers_csv, (array)$admin_recepients);
		//if (self::DEBUG) 
			//echo '<pre>'; var_dump($to);
		$this->CI->sms_valuefirst->num = $to;
		$this->CI->sms_valuefirst->msg = $msg;
		$this->CI->sms_valuefirst->tag = 'p2p';
		$this->CI->sms_valuefirst->send();
		return $this->CI->sms_valuefirst->var_dump(true);
	}

}