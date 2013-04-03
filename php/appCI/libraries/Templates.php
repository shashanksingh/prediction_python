<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Templates{
	function __construct(){
		$this->ci=& get_instance();
	
	}
	//this number is same when used with city code, so hardcoded here
	public $olacabs_contact_no = '33553355';

	public function get_sms_gcm_message_by_template_name($template_name,$booking_info)
	{
		/*argument passed if present i request or a  single space passed for sms-templates to work*/
		$arguments['recipient'] = 			isset($booking_info['recipient'])?$booking_info['recipient']:'';
		$arguments['name'] = 			isset($booking_info['name'])?$booking_info['name']:'';
		$arguments['crn_no'] = 			isset($booking_info['crn_no'])?$booking_info['crn_no']:'';
		$arguments['bill_amount'] = 	isset($booking_info['bill_amount'])?$booking_info['bill_amount']:'';
		$arguments['discount'] = 		isset($booking_info['discount'])?$booking_info['discount']:0;
		$arguments['bill_amount'] = (int)$arguments['bill_amount'] - (int)$arguments['discount'];
		
		$arguments['customer_email_id'] = 	isset($booking_info['customer_email_id'])?$booking_info['customer_email_id']:'_';
		$arguments['customer_phone'] = 	isset($booking_info['customer_phone'])?$booking_info['customer_phone']:'_';
		
		$arguments['driver_details'] = 		isset($booking_info['driver_first_name'])?$booking_info['driver_first_name'].' ' :'';
		//ony first name of driver to be sent in sms
		//$arguments['driver_details'].= 		isset($booking_info['driver_last_name'])?$booking_info['driver_last_name'].' ' :'';
		$arguments['driver_details'].= 		isset($booking_info['driver_phone'])?'+91 '.$booking_info['driver_phone']:'';
		$arguments['driver_phone'] = 		isset($booking_info['driver_phone'])?'+91 '.$booking_info['driver_phone']:'';

		$arguments['car_details'] = 		isset($booking_info['car_model'])?$booking_info['car_model'].'  ':'_';
		$arguments['car_details'].= 		isset($booking_info['car_license_no'])?$booking_info['car_license_no']:'';

		$arguments['pickup_datetime'] = 	isset($booking_info['pickup_date'])?$booking_info['pickup_date'].' ':'_';

		$arguments['location_adjusted'] = 	isset($booking_info['location_adjusted'])?$booking_info['location_adjusted']:false;
		$arguments['pickup_location_sane'] = 	isset($booking_info['pickup_location_sane'])?$booking_info['pickup_location_sane'].' ':'_';
		$arguments['pickup_location'] = 	isset($booking_info['pickup_location'])?$booking_info['pickup_location'].' ':'_';
		

		$arguments['service_city'] =        isset($booking_info['service_city'])?$booking_info['service_city']:'';
		$arguments['city_waitingcharge'] =  isset($booking_info['city_waitingcharge'])?$booking_info['city_waitingcharge']:'_';
		
		#for referral changes
		$arguments['referred'] = isset($booking_info['referred'])?$booking_info['referred']:'';
		$arguments['referrer'] = isset($booking_info['referrer'])?$booking_info['referrer']:'';
		$arguments['creditgain'] = isset($booking_info['creditgain'])?$booking_info['creditgain']:'';
		$arguments['referralcode'] = isset($booking_info['referralcode'])?$booking_info['referralcode']:'';
		
		
		//add city code to the ola contact number based on service city
		switch ($arguments['service_city']) {
			case 'mumbai':
				$arguments['olacabs_contact_no'] = 	'022 '.$this->olacabs_contact_no;
				break;
			
			case 'delhi':
				$arguments['olacabs_contact_no'] = 	'011 '.$this->olacabs_contact_no;
				break;

			case 'bangalore':
				$arguments['olacabs_contact_no'] = 	'080 '.$this->olacabs_contact_no;
				break;

			default:
				$arguments['olacabs_contact_no'] = 	$this->olacabs_contact_no;
				break;
		}

		switch ($booking_info['service_type']) {
			case 'p2p':
				$arguments['service_type'] = 'City Taxi';
				break;
			
			case 'local_half_rental':
			case 'local_full_rental':
			case 'local_half_rental':
			case 'local_full_rental':
				$arguments['service_type'] = 'Local Rental';
				break;

			case 'outstation':
				$arguments['service_type'] = 'Outstation Rental';
				break;

			default:
				$arguments['service_type'] = '';
				break;
		}

		//bill amount in RS integer part
		$amount_array = explode(".",$arguments['bill_amount']);
		$arguments['bill_amount'] = $amount_array[0];

		//change date to specified format
		$arguments['pickup_datetime'] = date("g:iA, j M",strtotime($arguments['pickup_datetime']));
		$arguments['pickup_date']     = date("j-M",strtotime($booking_info['pickup_date']));
		$arguments['pickup_time']     = date("H:i",strtotime($booking_info['pickup_date']));

		/*template called according to the code
		the load(view,arg,TRUE) returns the parsed view as string*/


		switch ($template_name) 
		{
			case "confirmation":
				if($arguments['crn_no']=='')
				{
					$message_text = 'ERROR';
					break;
				}

				if ($arguments['customer_email_id']=='_' or $arguments['customer_email_id']=='') {
					# code...
					//if email id is not present send different template
					$message_text = $this->ci->load->view('sms_template_views/confirmation_no_email.php',$arguments,TRUE);
				}
				else
				{
					$message_text = $this->ci->load->view('sms_template_views/confirmation.php',$arguments,TRUE);
				}
				break;

			case "confirmation_allotment":
				if($arguments['crn_no']=='' or $arguments['driver_phone']=='')
				{
					$message_text = 'ERROR';
					break;
				}
				$message_text = $this->ci->load->view('sms_template_views/confirmation_allotment.php',$arguments,TRUE);
				break;
			
			case "allotment":
				if($arguments['crn_no']=='' or $arguments['driver_phone']=='')
				{
					$message_text = 'ERROR';
					break;
				}
				$message_text = $this->ci->load->view('sms_template_views/allotment.php',$arguments,TRUE);
				break;

			case "reallotment":
				if($arguments['crn_no']=='' or $arguments['driver_phone']=='')
				{
					$message_text = 'ERROR';
					break;
				}
				$message_text = $this->ci->load->view('sms_template_views/reallotment.php',$arguments,TRUE);
				break;

			case "upgrade":
				if($arguments['crn_no']=='' or $arguments['driver_phone']=='')
				{
					$message_text = 'ERROR';
					break;
				}
				$message_text = $this->ci->load->view('sms_template_views/upgrade.php',$arguments,TRUE);
				break;

			case "reached":
				if($arguments['crn_no']=='' or $arguments['driver_phone']=='')
				{
					$message_text = 'ERROR';
					break;
				}
				$message_text = $this->ci->load->view('sms_template_views/reached.php',$arguments,TRUE);
				break;

			case "billing":
				if($arguments['crn_no']=='' or $arguments['bill_amount']=='')
				{
					$message_text = 'ERROR';
					break;
				}
				if($arguments['bill_amount']=='0')
				{
					$message_text = 'ERROR';
					break;
				}
				$message_text = $this->ci->load->view('sms_template_views/billing.php',$arguments,TRUE);
				break;

			case "billing_invoice":
				if($arguments['crn_no']=='' or $arguments['bill_amount']=='')
				{
					$message_text = 'ERROR';
					break;
				}
				if($arguments['bill_amount']=='0')
				{
					$message_text = 'ERROR';
					break;
				}
				$message_text = $this->ci->load->view('sms_template_views/billing_invoice.php',$arguments,TRUE);
				break;

			case "stockout":
				if($arguments['crn_no']=='')
				{
					$message_text = 'ERROR';
					break;
				}
				$message_text = $this->ci->load->view('sms_template_views/stockout.php',$arguments,TRUE);
				break;

			case "cancellation":
				if($arguments['crn_no']=='')
				{
					$message_text = 'ERROR';
					break;
				}
				$message_text = $this->ci->load->view('sms_template_views/cancellation.php',$arguments,TRUE);
				break;

			case "cancellation_refund":
				if($arguments['crn_no']=='')
				{
					$message_text = 'ERROR';
					break;
				}
				$message_text = $this->ci->load->view('sms_template_views/cancellation_refund.php',$arguments,TRUE);
				break;

			case "transaction_successful":
				if($arguments['crn_no']=='')
				{
					$message_text = 'ERROR';
					break;
				}
				$message_text = $this->ci->load->view('sms_template_views/transaction_successful.php',$arguments,TRUE);
				break;

			case 'payment_attempt':
				if($arguments['name']=='')
				{
					$message_text = 'ERROR';
					break;
				}
				$message_text = $this->ci->load->view('sms_template_views/payment_attempt.php',$arguments,TRUE);
				break;


			case 'payment_confirmation':
				if($arguments['crn_no']=='')
				{
					$message_text = 'ERROR';
					break;
				}
				$message_text = $this->ci->load->view('sms_template_views/payment_confirmation.php',$arguments,TRUE);
				break;	

			case 'payment_confirmation_admin':
				if($arguments['crn_no']=='' or $arguments['name']=='')
				{
					$message_text = 'ERROR';
					break;
				}
				$message_text = $this->ci->load->view('sms_template_views/payment_confirmation_admin.php',$arguments,TRUE);
				break;					

			case 'payment_failed':
				if(0)
				{
					$message_text = 'ERROR';
					break;
				}
				$message_text = $this->ci->load->view('sms_template_views/payment_failed.php',$arguments,TRUE);
				break;				

			case 'payment_failed_admin':
				if($arguments['name']=='')
				{
					$message_text = 'ERROR';
					break;
				}
				$message_text = $this->ci->load->view('sms_template_views/payment_failed_admin.php',$arguments,TRUE);
				break;	

			case 'MMT_address_check':
				if($arguments['crn_no']=='')
				{
					$message_text = 'ERROR';
					break;
				}
				$message_text = $this->ci->load->view('sms_template_views/MMT_address_check.php',$arguments,TRUE);
				
				break;

			case 'customer_details':
				if ($arguments['customer_phone']) {
					//$message_text = 'ERROR';
					//break;
				}
				$message_text = $this->ci->load->view('sms_template_views/customer_details',$arguments,TRUE);
				break;

			case 'customer_no_show':
				$message_text = $this->ci->load->view('sms_template_views/customer_no_show.php',$arguments,TRUE);
				break;
				
			case 'invite':
				if($arguments['referred']=='' or $arguments['referrer']=='' or $arguments['creditgain']=='' or $arguments['referralcode']=='')
				{
					$message_text = 'ERROR';
					break;
				}
				$message_text = $this->ci->load->view('email_templates/ola_money/invite.php',$arguments,TRUE);
				
				break;
			
			case 'welcome':
				if($arguments['referred']=='' or $arguments['referrer']=='' or $arguments['creditgain']=='')
				{
					$message_text = 'ERROR';
					break;
				}
				$message_text = $this->ci->load->view('email_templates/ola_money/welcome.php',$arguments,TRUE);
				
				break;
			
			case 'reminder':
				if($arguments['referred']=='' or $arguments['referrer']=='' or $arguments['creditgain']=='' or $arguments['referralcode']=='')
				{
					$message_text = 'ERROR';
					break;
				}
				$message_text = $this->ci->load->view('email_templates/ola_money/reminder.php',$arguments,TRUE);
				
				break;
				
			case 'friend_joined':
				if($arguments['referred']=='' or $arguments['referrer']=='' or $arguments['creditgain']=='')
				{
					$message_text = 'ERROR';
					break;
				}
				$message_text = $this->ci->load->view('email_templates/ola_money/friend_joined.php',$arguments,TRUE);
				
				break;
				
			case 'credit_onride':
				if($arguments['referred']=='' or $arguments['referrer']=='' or $arguments['creditgain']=='')
				{
					$message_text = 'ERROR';
					break;
				}
				$message_text = $this->ci->load->view('email_templates/ola_money/credit_onride.php',$arguments,TRUE);
				
				break;
				
			default:
				# code...
				$message_text = "";
				break;
		}

		
		
		return $message_text;
	}

	public function get_email_by_template_name($template_name,$data)
	{
		//$template['subject'] = 'Test mail from localhost by Anubhav';
		//$template['message'] = 'Working Fine by Anubhav';

		//$data = array();

		//add city code to the ola contact number based on service city
		switch ($data['service_city']) {
			case 'mumbai':
				$data['olacabs_contact_no'] = 	'(022) '.$this->olacabs_contact_no;
				break;
			
			case 'delhi':
				$data['olacabs_contact_no'] = 	'(011) '.$this->olacabs_contact_no;
				break;

			case 'bangalore':
				$data['olacabs_contact_no'] = 	'(080) '.$this->olacabs_contact_no;
				break;

			default:
				$data['olacabs_contact_no'] = 	$this->olacabs_contact_no;
				break;
		}

		switch($template_name)
		{
			case "booking_details":
				$template['subject'] = $this->ci->load->view("email_templates/subject_booking_details",$data,TRUE);
				$template['message'] = $this->ci->load->view("email_templates/booking_details",$data,TRUE);
			break;

			case "booking_confirmed_notification":
				$template['subject'] = $this->ci->load->view("email_templates/subject_booking_confirmed_notification",$data,TRUE);
				$template['message'] = $this->ci->load->view("email_templates/booking_confirmed_notification",$data,TRUE);
			break;	

			case "payment_link":
				$template['subject'] = $this->ci->load->view("email_templates/subject_payment_link",$data,TRUE);
				$template['message'] = $this->ci->load->view("email_templates/payment_link",$data,TRUE);
			break;

			case "booking_failed_notification":
				$template['subject'] = $this->ci->load->view("email_templates/subject_booking_failed_notification",$data,TRUE);
				$template['message'] = $this->ci->load->view("email_templates/booking_failed_notification",$data,TRUE);
			break;

			case "payment_confirmation":
				$template['subject'] = $this->ci->load->view("email_templates/subject_payment_confirmation",$data,TRUE);
				$template['message'] = $this->ci->load->view("email_templates/payment_confirmation",$data,TRUE);
			break;

			case "payment_failed":
				$template['subject'] = $this->ci->load->view("email_templates/subject_payment_failed",$data,TRUE);
				$template['message'] = $this->ci->load->view("email_templates/payment_failed",$data,TRUE);
			break;

			case "booking_confirmation_ameyo":
				$template['subject'] = $this->ci->load->view("email_templates/subject_booking_confirmation_ameyo",$data,TRUE);
				$template['message'] = $this->ci->load->view("email_templates/booking_confirmation_ameyo",$data,TRUE);
			break;

			case "booking_confirmation":
				$template['subject'] = $this->ci->load->view("email_templates/subject_booking_confirmation_ameyo",$data,TRUE);
				$template['message'] = $this->ci->load->view("email_templates/booking_confirmation_ameyo",$data,TRUE);
			break;
					
			case 'invite':
				$template['subject'] = $this->ci->load->view("email_templates/ola_money/subject_invite",$data,TRUE);
				$template['message'] = $this->ci->load->view("email_templates/ola_money/invite",$data,TRUE);
				break;
				
			case 'welcome':
				$template['subject'] = $this->ci->load->view("email_templates/ola_money/subject_welcome",$data,TRUE);
				$template['message'] = $this->ci->load->view("email_templates/ola_money/welcome",$data,TRUE);
				break;
				
			case 'reminder':
				$template['subject'] = $this->ci->load->view("email_templates/ola_money/subject_reminder",$data,TRUE);
				$template['message'] = $this->ci->load->view("email_templates/ola_money/reminder",$data,TRUE);
				break;
				
			case 'friend_joined':
				$template['subject'] = $this->ci->load->view("email_templates/ola_money/subject_friend_joined",$data,TRUE);
				$template['message'] = $this->ci->load->view("email_templates/ola_money/friend_joined",$data,TRUE);
				break;
				
			case 'credit_onride':
				$template['subject'] = $this->ci->load->view("email_templates/ola_money/subject_credit_onride",$data,TRUE);
				$template['message'] = $this->ci->load->view("email_templates/ola_money/credit_onride",$data,TRUE);
				break;
				
		}


		//var_dump($template);die();
		if(isset($template))
		{	
			return $template;
		}
	}
} 
