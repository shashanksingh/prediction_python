<?php

class Referral extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this -> olacabs_contact_no = '022 - 33553355';
		$this -> load -> library('templates');
		$this -> load -> model('alert_model');
		$this -> load -> library('email');
		$this -> load -> library('sms');
	}

	public function index() {
		//request type
		if (!$this -> valid_input(array('type'), $_REQUEST)) {
			echo json_encode(array("failure" => "Invalid request for sending referral email"));
			$this -> push_result_in_db("failure", "Invalid request for sending referral email", $_REQUEST['recipient']);
			return 0;
		}

		if ($_REQUEST['type'] == 'email') {
			//validates if requested params are passed

			if (!$this -> valid_input(array('recipient', 'template_name', 'referred', 'referrer', 'creditgain'), $_REQUEST) or (!$this -> valid_input(array('email'), $_REQUEST) and !$this -> valid_input(array('phone'), $_REQUEST))) {
				$this -> push_result_in_db("failure", "Invalid request for sending referral email", $_REQUEST['recipient']);
				return 0;
			} else {
				$template_name = $_REQUEST['template_name'];
				if (($template_name == "invite" or $template_name == "reminder") and !$this -> valid_input(array('referralcode'), $_REQUEST)) {
					$this -> push_result_in_db("failure", "Invalid request for sending referral email", $_REQUEST['recipient']);
					return 0;
				}
			}

			$template = $this -> templates -> get_email_by_template_name($template_name, $_REQUEST);
			$subject = $template['subject'];

			if ($template == "") {
				echo json_encode(array("failure" => "Template Not Found"));
				$this -> push_result_in_db("failure", "Template Not Found", $_REQUEST['recipient']);
				return 0;
			}

			$this -> send_email($_REQUEST['email'], $template['subject'], $template['message']);
			$message['status'] = "";
			$message['status_detail'] = "";

			$this -> push_result_in_db($message['status'], $message['status_detail'], $_REQUEST['email']);
			echo json_encode(array('success' => "MAIL successfully sent"));

		} elseif ($_REQUEST['type'] == 'sms') {
			$phone_mobile = $_REQUEST['phone'];

			if (strlen($phone_mobile) < 10 || (!preg_match("^[0-9]+$^", $phone_mobile))) {
				echo json_encode(array("failure" => "Incorrect mobile number"));
				return 0;
			}

			$template_name = $_REQUEST['template_name'];

			$message_text = NULL;
			if ($template_name == 'ola_money_reserved') {
				if (!$this -> valid_input(array('crn'), $_REQUEST)) {
					return 0;
				}
				$message_text = $this -> get_sms_text('sms_template_views/ola_money_reserved', $_REQUEST);
			} elseif ($template_name == 'ola_money_ride_completed_with_invoice') {
				if (!$this -> valid_input(array('call_center_no', 'ola_money_balance', 'bill', 'crn'), $_REQUEST)) {
					return 0;
				}
				$message_text = $this -> get_sms_text('sms_template_views/ola_money_ride_completed_with_invoice', $_REQUEST);
			} elseif ($template_name == 'ola_money_ride_completed_without_invoice') {
				if (!$this -> valid_input(array('call_center_no', 'ola_money_balance', 'bill', 'crn'), $_REQUEST)) {
					return 0;
				}
				$message_text = $this -> get_sms_text('sms_template_views/ola_money_ride_completed_without_invoice', $_REQUEST);
			} elseif ($template_name == 'ola_money_pickup_cancellation') {
				if (!$this -> valid_input(array('call_center_no', 'crn'), $_REQUEST)) {
					return 0;
				}
				$message_text = $this -> get_sms_text('sms_template_views/ola_money_pickup_cancellation', $_REQUEST);
			}

			//sending message using gupshup as we can track its delievery
			$url_encoded_msg = urlencode($message_text);
			$url_encoded_msg = str_replace('+', '%20', $url_encoded_msg);
			$url_encoded_msg = str_replace('\"', '\'', $url_encoded_msg);

			$result = $this -> sms -> gupshup_sms($phone_mobile, $url_encoded_msg);
			if ($result != '') {
				$gupshup_result = json_decode($result, true);
			}

			$status = ' Gupshup=' . $gupshup_result['response']['status'];
			$status_detail = ' Gupshup=' . $gupshup_result['response']['details'];
			$this -> push_result_in_db($status, $status_detail, $phone_mobile, '');
			if ($gupshup_result['response']['status'] == 'failure') {
				echo json_encode(array('success' => "SMS successfully sent"));
			} else {
				echo json_encode(array('failure' => "SMS sending failed"));

			}
		}
	}

	//get sms template from view
	public function get_sms_text($template, $data) {
		return $this -> load -> view($template, $data, TRUE);
	}

	//send email to customer
	public function send_email($to, $subject, $body) {
		$host = $this -> config -> item('host');
		$port = $this -> config -> item('port');
		$username = $this -> config -> item('username');
		$password = $this -> config -> item('password');
		$this -> email -> from("noreply@olacabs.com");
		$this -> email -> to($to);
		$this -> email -> subject($subject);
		$this -> email -> message($body);
		$this -> email -> send();
	}

	//take array of params and validates there presence in input
	public function valid_input($required_parameters, $input) {
		foreach ($required_parameters as $v) {
			if (!array_key_exists($v, $input)) {
				echo json_encode(array("failure" => "Query Incomplete missing param: " . $v));
				return false;
			}
		}
		return true;
	}

	public function push_result_in_db($status, $status_detail, $to) {
		$dbarray['template_name'] = array_key_exists('template_name', $_REQUEST) ? $_REQUEST['template_name'] : ' ';
		$dbarray['msg_type'] = 'email';
		$dbarray['service_provider'] = 'gmail';
		$dbarray['recipient'] = $to;

		$dbarray['recipient_tag'] = $_REQUEST['recipient'];

		if (array_key_exists('request_source', $_REQUEST)) {$dbarray['request_source'] = $_REQUEST['request_source'];
		} else {$dbarray['request_source'] = '';
		}

		if (array_key_exists('schedule_at', $_REQUEST)) {$dbarray['schedule_at'] = $_REQUEST['schedule_at'];
		} else {$dbarray['schedule_at'] = '';
		}

		$dbarray['status'] = $status;
		$dbarray['status_detail'] = $status_detail;

		$id = $this -> alert_model -> insert_alert_info($dbarray);
		$this -> alert_model -> insert_alert_recipient_info($dbarray, $id);
	}

}
