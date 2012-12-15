<?php
/*
 * CellNext SMS API

 * Author: Ankit Bhati
 * Email: ankit@olacabs.com
 * Project Page: http://ankitbhati.in/cellnext-api
 *
 */

// The actual class
class Sms_valuefirst
{
	private $user='olacabs';
	private $password='olacab11';
	protected $serverURL = 'http://api.myvaluefirst.com/psms/servlet/psms.Eservice2';

	//Vars used during Sending of the SMS
	var $sender_id='Olacabs';		//Sender ID
	var $msg;										//Body of the SMS
	var $num;										//The number or array of numbers the SMS should be sent to
	var $type="send";			//send or schedule or status or status_delete
	var $tag;  									//Reason the message is being sent
	var $schedule_time;					//When should the $type="scheduled" msg be delivered
	var $recipient='customer';			//Who's recipient? 'customer', 'admin', 'driver', 'operator'
	var $recipient_id=0;				//CustomerID/adminID/.. and so on
	var $relation='misc';					//Campaign ID/BookingID/Other relations
	var $relation_id=0;					//Campaign ID/BookingID/Other relations
	var $related;								//Transaction related to the api
	var $status='queued';
	var $action='send';					//Action for the API
	
	//Vars Received From the CellNext API
	var $pnr;  									//Unique ID received from CellNext 
	var $submit_time;						//Time of the request submission
	var $response_code;					//Response from cellnext

	var $result=false;					//Contains response from CellNext
		
	//Vars used to check and save status
	var $error_code;
	var $delivery_time;
	
	//Internal Variables
	var $error=array();
	var $silent=true;
	var $disabled=false;
	var $mode='transaction';
	
	public function __construct()
	{
		$this->ci=& get_instance();
	}

	public function __destruct()
	{
		unset($this);	
		return true;
	}

	//For Debug purposes only.....
	public function var_dump($asVar=true)
	{
		$argOrder=array('num', 'msg', 'schedule_time', 'type', 'status', 'result', 'recipient', 'recipient_id', 'related', 'tag', 'pnr', 'error');
		$varArray=array();
		$i=0;
		while(isset($argOrder[$i])){
			$varArray[$argOrder[$i]]=$this->$argOrder[$i];
			$i+=1;
		}
		return $varArray;
	}
	
	//Reset the sms object after sending the last one;
	public function reset() 
	{ 
		$result=$this->result;
		$pnr=$this->pnr;
		$status=$this->status;
		$error=$this->error;
		$silent=$this->silent;
		$disabled=$this->disabled;
		
		$this->ci->sms= new SMS;
		
		$this->ci->sms->result=$result;
		$this->ci->sms->pnr=$pnr;
		$this->ci->sms->status=$status;
		$this->ci->sms->error=$error;
		$this->ci->sms->silent=$silent;
		$this->ci->sms->disabled=$disabled;
		
		return true;
	}

	public function valid_schedule_time()
	{
		if($this->schedule_time!=null && $this->schedule_time!=''){
			if($this->schedule_time > time()){
				return true;
			}
			else{
				array_push($this->error, array('code'=>'schedule_time_past_dated', 'text'=>'Schedule time is in the past'));
				return false;
			}
		}
		else{
			array_push($this->error, array('code'=>'invalid_schedule_time', 'text'=>'DateTime Not Set'));
		}
		return false;
	}
	
	//execute uploading the XML Request and parsing results into readable results
	private function execute()
	{
		$this->recipient=
			(is_array($this->recipient)) ? $this->recipient : array($this->recipient);
		$this->recipient_id=
			(is_array($this->recipient_id)) ? $this->recipient_id : array($this->recipient_id);
		if(!$this->silent) $this->var_dump();				//Dump data when in debug mode
		if($XMLString=$this->buildXML()){						//Build XML Request using method buildXML()
			if(!$this->silent) var_dump('XMLString', $XMLString);
			// echo "<br><br>====================<br><br>";
			// echo htmlentities($XMLString);
			// echo "<br><br>====================<br><br>";
			if($XMLResponse=$this->send_request($XMLString)){ 			//Upload request to server 
				if(!$this->silent) var_dump('XMLResponse:', $XMLResponse);
				// echo "<br><br>====================<br><br>";
				// echo htmlentities($XMLResponse);
				// echo "<br><br>====================<br><br>";
				if($this->result=$this->process_response($XMLResponse)){ 	//Parse XML Response
					$retVal=$this->interpret_results();
					$this->save();
					$this->reset();
					return $retVal;
				}
				else{
					array_push($this->error, array('code'=>'invalid_response','text'=>'Response Parsing Error'));
					return false;
				}
			}
			else{
				array_push($this->error, array('code'=>'connection_failed','text'=>'Failed To Connect'));
				return false;
			}
		}
		else{
			array_push($this->error, array('code'=>'invalid_structure','text'=>'Failed To Connect'));
			return false;
		}
		return false;
	}
	
	public function send()
	{
		//Set inline variables to object vars  (Same comments apply to schedule, schedule_delete, status)
		$argOrder=array('num', 'msg', 'sender_id', 'recipient', 'recipient_id', 'related', 'tag');
		$argv=func_get_args();
		$i=0;
		while(isset($argv[$i]) && isset($argOrder[$i])){
			$this->$argOrder[$i]=$argv[$i];
			$i+=1;
		}
		
		$this->action='send';			//set action
		$this->type='send';				//set type
		if(isset($this->num) && $this->num!=null && (is_numeric($this->num) || is_array($this->num))){
			$this->msg=htmlentities($this->msg, ENT_COMPAT);			//Encode msg
			$this->num=(is_array($this->num))? $this->num : array($this->num);
			
			$bool=$this->execute();		//execute
			$this->status=($bool)?'success':'fail';
			return $bool;
		}
		else{
			array_push($this->error, array('code'=>'invalid_num','text'=>'Unrecognized format of Number'));
		}
		return false;
	}

	public function schedule()
	{
		$argOrder=array('num', 'msg', 'schedule_time', 'sender_id', 'recipient_id', 'related', 'tag');
		$argv=func_get_args();
		$i=0;
		while(isset($argv[$i]) && isset($argOrder[$i])){
			$this->$argOrder[$i]=$argv[$i];
			$i+=1;
		}
		
		$this->type='schedule';
		$this->action='send';
		if(isset($this->num) && $this->num!=null && (is_numeric($this->num) || is_array($this->num))){
			$this->msg=htmlentities($this->msg, ENT_COMPAT);
			$this->num=(is_array($this->num))? $this->num : array($this->num);
			if($this->valid_schedule_time()){
				$bool=$this->execute();		//execute 
				return $bool;
			}
			else return false;
		}
		else{
			array_push($this->error, array('code'=>'invalid_num','text'=>'Unrecognized format of Number'));
			return false;
		}
		return false;
	}

	public function get_status()
	{
		$this->action='status';
		$this->type='status';
		if($this->pnr!='' && $this->pnr != null && (is_string($this->pnr) || is_array($this->pnr))){
			$bool=$this->execute();
			return $bool;
		}
		else{
			array_push($this->error, array('code'=>'invalid_pnr','text'=>'Unrecognized format of PNR'));
		}
		return false;
	}
	
	public function schedule_delete()
	{
		$this->action='schedule';
		$this->type='schedule_delete';
		if($this->pnr!='' && $this->pnr != null && (is_string($this->pnr) || is_array($this->pnr))){
			$bool=$this->execute();
			return $bool;
		}
		else{
			array_push($this->error, array('code'=>'invalid_pnr','text'=>'Unrecognized format of PNR'));
		}
		return false;
	}
	
	private function buildXML()
	{
		$key=1; $XMLString=''; $scheduleString=""; $recipient=$this->recipient; $sender_id=array();
		if($this->type=='send' || $this->type=='schedule'){ //Build XML Request for schedule and send methods
			if($this->type=='schedule') { 
				$dateTime=new DateTime('@'.$this->schedule_time); $dateTime->setTimezone(new DateTimeZone('Asia/Calcutta'));
				//Add formatted time for schedule method
				$scheduleString='SEND_ON="'.$dateTime->format('Y-m-d H:i:s +0530').'"'; 
			}
			$addressString=''; $i=0;
			foreach($this->num as $num){
				$sender_id[$i]=$this->sender_id; if(!isset($recipient[$i])) $recipient[$i]='customer';
				if($recipient[$i]!='customer'){
					$suffix=rand(1, 9);
					if(!is_numeric($this->sender_id)) $sender_id[$i].=$suffix;
				}
				if($this->disabled) {
					$num=123;
				}
				else{
					if($this->ci->config->item('send_sms')=='safe'){
						$num=9869312835;
						if($i>2) $num=123;
					}
					elseif($this->ci->config->item('send_sms')=='yes'){
						$num=$num;
					}
				}
				$addressString.='<ADDRESS FROM="Olacab" TO="'.$num.'" SEQ="'.($i+1).'" TAG="'.$this->tag.'" />';
				$i++; 
			}
			
			$this->sender_id=$sender_id;
			$XMLString=
				'<?xml version="1.0" encoding="ISO-8859-1"?>'.
				'<!DOCTYPE MESSAGE SYSTEM "http://127.0.0.1/psms/dtd/messagev12.dtd" >'.
				'<MESSAGE VER="1.2">'.
					'<USER USERNAME="'.$this->user.'" PASSWORD="'.$this->password.'"/>'.
					'<SMS  UDH="0" CODING="1" TEXT="'.$this->msg.'" PROPERTY="0" '.$scheduleString.' ID="1">'.
						$addressString.
					'</SMS>'.
				'</MESSAGE>'
			;
			return $XMLString;
		}
		
		elseif($this->type=='status'){		//Build XML For status method
			// CASE WHEN $this->pnr='sdfsdf--OLACABS'
			if(is_string($this->pnr)) $GUID=array(array('GUID'=>$this->pnr, 'SEQ'=>0));
			//CASE WHEN $this->pnr=array('sdfsdf--OLACABS', 'sdfsdf--OLACABS'....)
			elseif(is_array($this->pnr)){
				$Garray=$this->pnr; $GUID=array();
				if(is_string($Garray[0])) {
					foreach($Garray as $ID) array_push($GUID, array('GUID'=>$ID, 'SEQ'=>0));
				}
				//CASE WHEN $this->pnr=array(array('GUID'=>'kb9ab242691231f4610141jcaf','SEQ'=>1))
				elseif(is_array($Garray[0])) $GUID=$this->pnr;
			}
			$GUIDString=''; 
			foreach($GUID as $ID){ 
				$gid=$ID['GUID']; $seq=$ID['SEQ'];
				$GUIDString.='<GUID GUID="'.$gid.'">';
				if($seq) $GUIDString.='<STATUS SEQ="'.$seq.'" />';
				$GUIDString.='</GUID>';
			}
			$XMLString=
				'<?xml version="1.0" encoding="ISO-8859-1"?>'.
				'<!DOCTYPE STATUSREQUEST SYSTEM "http://127.0.0.1/psms/dtd/requeststatusv12.dtd" >'.
				'<STATUSREQUEST VER="1.2">'.
					'<USER USERNAME="'.$this->user.'" PASSWORD="'.$this->password.'"/>'.$GUIDString.
				'</STATUSREQUEST>'
			;
			return $XMLString;
		}
		
		elseif($this->type=='schedule_delete'){ 	//Build XML for schedule_delete method
			$GUIDString='';
			$this->pnr=(is_array($this->pnr))? $this->pnr : array($this->pnr);
			foreach($this->pnr as $i=>$GUID){
				$GUIDString.='<GUID GUID="'.$GUID.'" MODIFIER="" />';
			}
			$XMLString=
				'<?xml version="1.0" encoding="ISO-8859-1"?>'.
				'<!DOCTYPE SCHEDULE SYSTEM "http://127.0.0.1/psms/dtd/schedule_q.dtd" >'.
				'<SCHEDULE ACTION="DELETE">'.
					'<USER USERNAME="'.$this->user.'" PASSWORD="'.$this->password.'"/>'.$GUIDString.
				'</SCHEDULE>'
			;
			return $XMLString;
		}

		return false;
	}
	
	//Send XML that was built using buildXML method
	private function send_request($XMLString)
	{
		$action=$this->action;
		$content = stripslashes($XMLString);
		$content = htmlentities($XMLString, ENT_COMPAT);
		
		$queryString='data='. urlencode($XMLString);
		$url=$this->serverURL;
		$data="action=$action&$queryString";

		$objURL = curl_init($url);
		curl_setopt($objURL, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($objURL,CURLOPT_POST,1);
		curl_setopt($objURL, CURLOPT_POSTFIELDS,$data);
		//if($this->ci->config->item('send_sms')=='yes' || $this->ci->config->item('send_sms')=='safe') 
			$retval = trim(curl_exec($objURL));
		//else{
		//	var_dump("THIS IS DEMO RESPONSE BECAUSE DEBUG OPTION IS ON");
		//	$retval=$this->ci->config->item('sms_'.$this->type.'_response');
		//}
		
		curl_close($objURL);
		return $retval;
	}
	
	private function process_response($XMLResponse)
	{
		$xml = simplexml_load_string($XMLResponse); 
		$result=array(); $nums=$this->num;
		if($this->type=='send' || $this->type=='schedule'){
			$GUID=(string) $xml->GUID[0]->attributes()->GUID;
			$errors=(isset($xml->GUID->ERROR))?$xml->GUID->ERROR:array(); 
			$i=0; $seqs=array();
			$this->pnr=(string) $xml->GUID[0]->attributes()->GUID; $seqArray=array();
			if(count($errors>0)){
				foreach($errors as $i=>$error){
					$SEQ=$error->attributes()->SEQ; 
					if($SEQ==''){
						$SEQ=1;
						$customCode=(int) $error->attributes()->CODE;
					}
					$result[$SEQ-1]=array(
													'num'=>(isset($nums[$SEQ-1]))?$nums[$SEQ-1]:99999999,
													'SEQ'=>(int) $SEQ, 
													'CODE'=>(int) $error->attributes()->CODE
												 )
					;
				}
			}
			foreach($nums as $i=>$num){
				if(!isset($result[$i])){
					$result[$i]=array(
						'num'=>$num,
						'SEQ'=>$i+1, 
						'CODE'=>($this->type=='schedule')?13568:((isset($customCode))?$customCode:999)
					);
				}
			}
			$this->pnr=$GUID;
			return $result;
		}
		
		elseif($this->action=='status'){
			$globalStat=array();
			//var_dump(array_flatten((array) $xml));
			foreach($xml->GUID as $GNode){
				$curGUID=(string) $GNode->attributes()->GUID;
				if(!isset($GNode->STATUS) || count($GNode->STATUS)===0) { $this->status='unknown'; }
				{
					foreach($GNode->STATUS as $status){
						$statusAttrs=$status->attributes();
						$statusSEQ=array(
															'SEQ'=>(string) $statusAttrs->SEQ,
															'ERR'=>(string) $statusAttrs->ERR,
															'DONEDATE'=>(string) $statusAttrs->DONEDATE,
															'REASONCODE'=>(string) $statusAttrs->REASONCODE,
															'GUID'=>$curGUID
											)
						;
						$globalStat[$curGUID][$statusSEQ['SEQ']]= $statusSEQ;
					}
				}
			}
			$this->status=$globalStat;
			if(count($globalStat)){
				return $globalStat;
			}
			else{
				$this->status='unknown';
				return true;
			}
		}
		
		elseif($this->type=='schedule_delete'){ 
			if(!((int) $xml->attributes()->ERROR)){
				$result=array();
				foreach($xml->GUID as $GUID){
					$result[]=array(
														'GUID'=>(string) $GUID->attributes()->GUID,
														'ERROR'=>(string) $GUID->attributes()->ERROR
													)
					;
				}
				return $result;
			}
		}
	}
	
	private function interpret_results()
	{
		if($this->type=='send' || $this->type=='schedule'){
		$retVal=true;
		ksort($this->result, SORT_NUMERIC);
		$status=array(); 
			foreach($this->result as $key=>$unit){
				$unit['status']='queued'; $unit['error']='success';
				if(!($unit['CODE']==000 || $unit['CODE']==13568)){
					$unit['status']='failed';
					$retVal=false;
				}
				$unit['error']=$this->interpreter($unit['CODE']);
				$unit['sender_id']='Olacab';
				$unit['recipient']=(isset($this->recipient[$key]))?$this->recipient[$key]:'customer';
				$unit['recipient_id']=(isset($this->recipient_id[$key]))?$this->recipient_id[$key]:0;
				$status[]=$unit;
			}
			$this->result=$status;
			return $retVal;
		}
		
		elseif($this->type=='status'){
			$retVal=true; $status=array();
			ksort($this->result, SORT_NUMERIC);
			foreach($this->status as $GUID=>$unit){
				foreach($unit as $SEQ=>$seqUnit){
					$unit[$SEQ]['error']=$this->interpreter($unit[$SEQ]['ERR']);
					$unit[$SEQ]['error_reason']=$this->interpreter($unit[$SEQ]['REASONCODE']);
				}
				$status[$GUID]=$unit;
			}
			$this->result=$status;
		}
		
		elseif($this->type=='schedule_delete'){
			$retVal=true; $status=array();
			ksort($this->result, SORT_NUMERIC);
			foreach($this->result as $unit){
				$unit['error']=$this->interpreter($unit['ERROR']);
				$status[]=$unit;
			}
			$this->result=$status;
		}
	}
	
	private function save()
	{
		if($this->mode=='transaction') {
			$this->ci->load->model('adm/sms_model');
		}
		else{
			$this->ci->load->model('adm/sms_campaign_model');
		}
		switch($this->type){
			case ($this->type=='schedule' || $this->type=='send'):
				$insert=array();
				$insert['group']=array(
					'guid'=>$this->pnr, 
					'count'=>count($this->num),
					'msg'=>$this->msg, 
					'relation'=>$this->relation, 
					'relation_id'=>$this->relation_id, 
					'submit_timestamp'=>time(), 
					'queue_status'=>$this->status, 
					'tag'=>$this->tag,
					'schedule_timestamp'=>($this->type=='send')?0:$this->schedule_time,
					'type'=>$this->type
				);
				if($this->mode=='transaction') {
					//$smsGroupID=$this->ci->sms_model->insert_sms_group($insert['group']);
				}
				else{
					$smsGroupID=$this->ci->sms_campaign_model->insert_sms_group($insert['group']);
				}
				foreach($this->result as $record){
					$insert['seq'][]=array(
						'num'=>$record['num'], 
						'seq'=>$record['SEQ'], 
						'recipient'=>$record['recipient'], 
						'recipient_id'=>$record['recipient_id'], 
						'submit_timestamp'=>time(), 
						'queue_status'=>$record['status'], 
						'sender_id'=>'Olacabs', 
						'submit_error_code'=>$record['CODE'], 
						'submit_error_text'=>$record['error'], 
						'guid'=>$this->pnr,
					//	'sms_group_id'=>$smsGroupID,
						'type'=>$this->type
					);
				}
				if($this->mode=='transaction') {
				//	$this->ci->sms_model->insert_sms_seq($insert['seq']);
				}
				else{
				//	$this->ci->sms_campaign_model->insert_sms_seq($insert['seq']);
				}
				break;
			
			case ($this->type=='status' && $this->mode=='transaction'): 
				$update=array(); //echo 'SAVING=======';
				foreach($this->result as $GUID=>$grecord){
					foreach($grecord as $SEQ=>$record){
						$date=new DateTime($record['DONEDATE'], new DateTimeZone('Asia/Calcutta'));
						$update=array(
							'delivery_timestamp'=>$date->format('U'), 
							'delivery_error_code'=>$record['ERR'],  
							'delivery_error_text'=>$record['error'],  
							'error_subcode'=>$record['REASONCODE'],  
							'error_subtext'=>$record['error_reason'],  
						);
						$this->ci->sms_model->update_sms_seq($GUID, $SEQ, $update);
					}
				}
				break;
				
			case ($this->type=='schedule_delete'):
				foreach($this->result as $GUID){
					$this->ci->sms_model->update_sms_group($GUID['GUID'], array(
							'queue_status'=>($GUID['ERROR']==13568)?'deleted':'not_deleted',
							'error_code'=>$GUID['ERROR'], 
							'error_text'=>$GUID['error']
						)
					);
					$this->ci->sms_model->update_sms_seq($GUID['GUID'], 0, array(
							'submit_error_code'=>$GUID['ERROR'], 
							'submit_error_text'=>$GUID['error'], 
							'queue_status'=>($GUID['ERROR']==13568)?'deleted':'not_deleted'
						)
					);
				}
				break;
				
			default:
				echo 'error';
		}
		
	}
	
	public function interpreter($code)
	{
		switch($code){
			case 000:
				return 'Successfully delivered'; break;
			case 999:
				return 'Successfully submitted'; break;
			case 001:
				return 'Invalid Number'; break;
			case 002:
				return 'Absent Subscriber'; break;
			case 003:
				return 'Memory Capacity Exceeded '; break;
			case 004:
				return 'Mobile Equipment Error'; break;
			case 005:
				return 'Network Error'; break;
			case 006:
				return 'Barring'; break;
			case 009:
				return 'NDNC Failure'; break;
			case 100:
				return 'Unknown Error'; break;
			case 28673:
				return 'Destination number not numeric'; break;
			case 28674:
				return 'Destination number empty'; break;
			case 28675:
				return 'Sender address empty'; break;
			case 28676:
				return 'SMS over 160 character'; break;
			case 28677:
				return 'UDH is invalid'; break;
			case 28678:
				return 'Coding is invalid'; break;
			case 28679:
				return 'SMS text is empty'; break;
			case 28680:
				return 'Invalid sender ID'; break;
			case 28681:
				return 'Invalid/Repeated message. Submit failed'; break;
			case 28682:
				return 'Invalid Receiver ID'; break;
			case 28683:
				return 'Invalid Date time for message Schedule'; break;
			case 52992:
				return 'Username / Password incorrect'; break;
			case 57089:
				return 'Contract expired '; break;
			case 57090:
				return 'User Credit expired'; break;
			case 57091:
				return 'User disabled'; break;
			case 65280:
				return 'Service is temporarily unavailable'; break;
			case 65535:
				return 'The specified message does not conform to DTD'; break;
			case 8448:
				return 'Message delivered successfully '; break;
			case 8449:
				return 'Message failed'; break;
			case 8450:
				return 'Message ID is invalid'; break;
			case 13568:
				return 'Command Completed Successfully'; break;
			case 13569:
				return 'Cannot delete schedule since it has already been processed'; break;
			case 13570:
				return 'Cannot update schedule since the new date-time parameter is incorrect'; break;
			case 13571:
				return 'Invalid SMS ID/GUID'; break;
			case 13572:
				return 'Invalid Status type for schedule search query'; break;
			case 13573:
				return 'Invalid date time parameter for schedule search query'; break;
			case 13574:
				return 'Invalid GUID  for GUID search query'; break;
			case 13575:
				return 'Invalid command action'; break;
		}
	}
}
?>
