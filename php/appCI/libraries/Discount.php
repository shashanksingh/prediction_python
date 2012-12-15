<?php

/* Discount class handles all the stuff related to discount */
class Discount{
  private $service_city;
  private $lead_source;
  private $pickup_hour;
  private $pickup_min;
  private $service_comments;
  private $pickup_date;
  private $price_per_km;
  private $price_per_wait_minute;
  private $discount;
  private $cities = array(BANGALORE, DELHI, MUMBAI);

  const MUMBAI_HAPPYOLA_PER_KM = 15;
  const MUMBAI_HAPPYOLA_WAIT_MINUTE = 2;
  const BANGALORE_HAPPYOLA_PER_KM = 15;
  const BANGALORE_HAPPYOLA_WAIT_MINUTE = 1;
  const MUMBAI_TRAIN20_DISCOUNT = '20%';

  // added again for delhi oldrate thing
  const OLD_DELHI_RATE = 18;
  const DELHI_PER_KM  = 21;
  const DELHI_WAIT_PER_KM = 2;

  
  public function __construct($params) {
    $this->service_city     =  $params[0]["service_city"];
    $this->lead_source      =  $params[0]["lead_source"];
    $this->pickup_hour      =  $params[0]["pickup_hour"];
    $this->pickup_min       =  $params[0]["pickup_min"];
    $this->service_comments =  $params[0]["service_comments"];
    $this->pickup_date      =  $params[0]["pickup_date"];
    $this->price_per_km     =  $params[1]['PERKMS'];
    $this->price_per_wait_minute = $params[1]['PERMIN'];
    $this->discount         =  0;
    $this->calculate();
  }

  public function getPricePerKm(){
    return $this->price_per_km;
  }

  public function getPricePerWaitMinute(){
    return $this->price_per_wait_minute;
  }

  public function getDiscountValue(){
    return $this->discount * 1;
  }

  public function getDiscountType(){
    return (preg_match('/%/i', $this->discount)) ? 'PERCENT': 'FIXED';
  }

  public function getDiscount(){
    return $this->discount;
  }

  // calculate the discount here
  private function calculate(){
    foreach ($this->cities as $city) {
      //$this->sorryDiscount($this->service_comments);
      $isHappyOla = $this->happyolaDiscount();
      $isTrain20  = $this->train20Discount($this->service_city, $this->service_comments);
      $isOlaRate  = $this->oldRateDiscount($this->service_city, $this->pickup_date, $this->service_comments);
      $isMmt      = $this->mmtDiscount($this->lead_source, $this->service_comments);
      $isMobile   = $this->mobileDiscount($this->lead_source, $isHappyOla);
      $isWelcome50= $this->welcome50($this->service_comments, array($isMobile, $isHappyOla));
      $isOffer100 = $this->offer100($this->service_comments, array($isMobile, $isHappyOla, $isWelcome50));
    }
  }

  private function sorryDiscount($service_comments){
    if (preg_match('/sorry50/i', $service_comments) == true){ // sorry 50
      $this->discount = '50';
    }else if (preg_match('/sorry100/i', $service_comments) == true){ // sorry 100
      $this->discount = '100';
    }else if (preg_match('/sorry150/i', $service_comments) == true){ // sorry 150
      $this->discount = '150';
    }
  }

  private function welcome50($service_comments, $applied) {
    if (in_array(true, $applied))
      return false;
    if (preg_match('/welcome50/i', $service_comments) == true) { 
      $this->discount = '50';
	  return true; 
	}
	return false;
  }

  private function offer100($service_comments, $applied) {
    if (in_array(true, $applied))
      return false;
      if (preg_match('/offer100/i', $service_comments) == true) { 
        $this->discount = '100';
      return true; 
    }
    return false;
  }

  private function mobileDiscount($lead_source, $isHappyOla){
    if($isHappyOla)
      return false;
    if ($this->isMobileAppBooking($lead_source) == true) { 
      $this->discount='10%';
      return true;
    }
    return false;
  }
 
  // discount for happy hours
  // mumbai from 11 to 16 and 23 to 04
  // bangalore from 11 to 16 only
  // no delhi discount
  private function happyolaDiscount(){
    if ($this->isDiscountHours($this->service_city, $this->pickup_hour, $this->pickup_min)) { // happy hour      
      if (($this->isMobileAppBooking($this->lead_source) == true) || (preg_match('/happyola/i', $this->service_comments) == true)){
	$this->setHappyolaPricing($this->service_city);
	return true;
      }
    }
    return false;
  }
  
  private function setHappyolaPricing($service_city){
    if($service_city == MUMBAI){
      $this->price_per_km          = self::MUMBAI_HAPPYOLA_PER_KM;
      $this->price_per_wait_minute = self::MUMBAI_HAPPYOLA_WAIT_MINUTE;
    }else if($service_city == BANGALORE){
      $this->price_per_km          = self::BANGALORE_HAPPYOLA_PER_KM;
      $this->price_per_wait_minute = self::BANGALORE_HAPPYOLA_WAIT_MINUTE;
    }
  }


  private function isDiscountHours($service_city, $hour, $min) { // happy hour
    if($service_city == MUMBAI){
      if (($hour >= 11 && $hour < 16) || ($hour >= 23) || ($hour < 4) ||
	  ($hour == 4 && $min < 1) || ($hour == 16 && $min < 1)){
	return true; 
      }
    }else if($service_city == BANGALORE){
      if (($hour >= 11 && $hour < 16) || ($hour == 16 && $min < 1))
	return true; 
    }
    return false;
  }



  // discount for train station drop offs
  private function train20Discount($service_city, $service_comments){
    if($service_city == MUMBAI){
      if (preg_match('/train20/i', $service_comments) == true) { // Independent Happy Journey offer for mumbai
	       $this->discount  = self::MUMBAI_TRAIN20_DISCOUNT;
      }
    }
  }
  

  private function isMobileAppBooking($source) { 
    if (preg_match('/mobile_app|android_app|iphone_app|blackberry_app/i', $source)) 
      return true; 
    return false; 
  }
  
  
  function oldRateDiscount($service_city, $pickup_date, $service_comments){
    if ($service_city == 'delhi' && preg_match('/oldratedelhi/i', $service_comments) == true) { // old delhi rates
      $this->price_per_km          = self::OLD_DELHI_RATE; 
      $this->price_per_wait_minute = self::DELHI_WAIT_PER_KM;
    } else if ($service_city == 'delhi' && $pickup_date > '2012-11-07') { // new delhi rates
      $this->price_per_km          = self::DELHI_PER_KM; 
      $this->price_per_wait_minute = self::DELHI_WAIT_PER_KM;
    }
  }

  // MMT (makemytrip 10% discount for all cities and any time) 
  function mmtDiscount($lead_source, $service_comments){
    if (($lead_source == 'makemytrip') || (preg_match('/MMT_Airport/i', $service_comments))) {
      $this->discount = '10%';
    }
  }
}

?>
