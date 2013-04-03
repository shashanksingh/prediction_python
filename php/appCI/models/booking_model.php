<?php 

class Booking_model extends CI_model
{


	public function __construct()
	{
		parent::__construct();
	}

	public function get_booking_details($booking_id)
	{



		
		
		$db=$this->load->database();

		$this->db->select('b.crn               as crn_no');
		$this->db->select('b.service_city               as service_city');
		//$this->db->select('b.outstation_price_per_km               as price_per_km');
		$this->db->select('b.customer_email    as customer_email_id');
		$this->db->select("CONVERT_TZ(b.pickup_date,'+00:00','+05:30')       as pickup_date",FALSE);
		$this->db->select('b.total_ola_bill as bill_amount');
		$this->db->select('b.name as name');
		$this->db->select('b.pickup_location as pickup_address');
		$this->db->select('b.estimated_amount as estimated_amount');
		$this->db->select('b.advance_amount as advance');
		$this->db->select('b.short_link     as short_link');
		$this->db->select('b.service_type   as service_type');
		$this->db->select("b.lead_source       as lead_source");
		$this->db->select('b.advance_amount   as advance_amount');
		$this->db->select('b.service_city   as service_city');
		$this->db->select('b.driver_allowance   as driver_allowance');
		$this->db->select('b.service_comments   as service_comments');
		$this->db->select('b.priority_tagging   as priority_tagging');
		$this->db->select('b.date_entered   as date_entered');
		
		$this->db->select('cm.outstation_price_per_km   as outstation_price_per_km');
		$this->db->select('cm.outstation_driver_allowance   as outstation_driver_allowance');
		$this->db->select('cm.outstation_customer_min_kms   as outstation_customer_min_kms');
		$this->db->select('cm.local_price_full_day  as local_price_full_day');
		$this->db->select('cm.local_price_half_day   as local_price_half_day');
		$this->db->select('cm.local_price_extra_km  as local_price_extra_km');
		$this->db->select('cm.local_price_extra_hour  as local_price_extra_hour');

		$this->db->select('ic.distance  as intercity_distance');

		$this->db->select('cm.name as car_model');
		$this->db->select('c.name as car_license_no');
		$this->db->select('d.first_name as driver_first_name');
		$this->db->select('d.last_name as driver_last_name');
		$this->db->select('d.phone_mobile as driver_phone');

		$this->db->from('ola_bookings b');
		
		$this->db->join('intercity                 ic', 'b.destination = ic.intercity_id','left');

		$this->db->join('ola_bookgs_ola_citytaxi_c bct', 'bct.ola_boo38c2ings_idb = b.id and bct.deleted = 0','left');
		$this->db->join('ola_citytaxi ct', 'ct.id=bct.ola_booe1fbtaxi_ida and ct.deleted = 0','left');
		
		$this->db->join('ola_citytaxi_ola_cars_c ctc','ctc.ola_citfdd9taxi_ida = ct.id AND ctc.deleted = 0','left');
		$this->db->join('ola_cars c','ctc.ola_cite121cars_idb = c.id AND c.deleted = 0','left');
		
		$this->db->join('ola_cars_ola_carmodels_c ccm', 'ccm.ola_car38b1cars_idb = c.id AND ccm.deleted = 0','left');
		$this->db->join('ola_carmodels cm','ccm.ola_car80f1dels_ida = cm.id AND cm.deleted = 0','left');

		$this->db->join('ola_cityaxi_ola_drivers_c ctd','ctd.ola_cit8017taxi_ida = ct.id AND ctd.deleted = 0','left');
		$this->db->join('ola_drivers d','ctd.ola_citbd76vers_idb = d.id AND d.deleted = 0','left');

		$this->db->where('b.id',$booking_id);

		$result=$this->db->get();

		

		if ($result != NULL) {
			$row = $result->row_array();

			if($row['service_type']=='p2p'){
				$params[0]['service_city'] = $row['service_city'];
				$params[0]["service_comments"] = $row['service_comments'];
				$params[0]["pickup_hour"] = date('H',strtotime($row['pickup_date']));
				$params[0]["pickup_min"]  = date('i',strtotime($row['pickup_date']));
    			$params[0]["lead_source"]= $row['lead_source'];
				$params[0]["pickup_date"]=$row['pickup_date'];
				$params[0]["priority_tagging"] = $row['priority_tagging'];
   				$params[0]["date_entered"]     = $row['date_entered'];

				$this->load->library('pricing',$params);
				$row['price_per_km'] = $this->pricing->getPricePerKm();
				$row['price_per_min_waiting'] = $this->pricing->getPricePerWaitMinute();

   				$params[1]['PERKMS'] = $row['price_per_km'];
				$params[1]['PERMIN'] = $row['price_per_min_waiting'];

				$this->load->library('discount',$params);
				$row['price_per_km'] = $this->discount->getPricePerKm();
				$row['price_per_min_waiting'] = $this->discount->getPricePerWaitMinute();

			}

			return $row;
		}
		else return "";

	}

	function get_opportunities_info_by_id($booking_id)
	{
		$this->db->select('b.name              as name');
		$this->db->select("b.crn               as crn_no");
		$this->db->select("b.customer_email    as customer_email_id");
		$this->db->select("b.phone_mobile      as customer_phone");
		$this->db->select("b.service_city      as service_city");
		$this->db->select("b.lead_source       as lead_source");

		$this->db->from("opportunities b");

		$this->db->where("b.id",$booking_id);
		$this->db->where("b.deleted",0);

		$result = $this->db->get();

		if ($result != NULL) {
			# code...
			return $result->row_array();
		}
		else return "";

	}
}











