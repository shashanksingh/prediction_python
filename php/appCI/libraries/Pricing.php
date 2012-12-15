<?php

/* Pricing class handles all the stuff related to p2p pricing */
class Pricing{
  private $service_city;
  private $lead_source;
  private $pickup_hour;
  private $pickup_min;
  private $service_comments;
  private $pickup_date;
  private $price_per_km;
  private $price_per_wait_minute;
  private $discount;
  private $priority_tagging;
  private $cities = array(BANGALORE, DELHI, MUMBAI);

  // city pricing
  const DELHI_PER_KM  = 21;
  const MUMBAI_PER_KM = 21;
  const BANGALORE_PER_KM = 21;

  const OLD_DELHI_PER_KM  = 18;
  const OLD_MUMBAI_PER_KM = 18;
  const OLD_BANGALORE_PER_KM = 18;

  const DELHI_WAIT_PER_KM = 2;
  const MUMBAI_WAIT_PER_KM = 2;
  const BANGALORE_WAIT_PER_KM = 2;

  const OLD_DELHI_WAIT_PER_KM = 1;
  const OLD_MUMBAI_WAIT_PER_KM = 1;
  const OLD_BANGALORE_WAIT_PER_KM = 1;


  public function __construct($params) {
    $this->service_city     =  $params[0]["service_city"];
    $this->lead_source      =  $params[0]["lead_source"];
    $this->pickup_hour      =  $params[0]["pickup_hour"];
    $this->pickup_min       =  $params[0]["pickup_min"];
    $this->service_comments =  $params[0]["service_comments"];
    $this->pickup_date      =  $params[0]["pickup_date"];
    $this->priority_tagging =  $params[0]["priority_tagging"];
    $this->date_entered     =  $params[0]["date_entered"];
    $this->calculate();
  }
  
  public function getPricePerKm(){
    return $this->price_per_km;
  }

  public function getPricePerWaitMinute(){
    return $this->price_per_wait_minute;
  }

  // calculate the price here
  private function calculate(){
    if($this->service_city == MUMBAI){
      $this->price_per_km = $this->priceMumbaiPerKm();
      $this->price_per_wait_minute = $this->priceMumbaiPerMin();
    }else if($this->service_city == DELHI){
      $this->price_per_km = $this->priceDelhiPerKm();
      $this->price_per_wait_minute = $this->priceDelhiPerMin();
    }else if($this->service_city == BANGALORE){
      $this->price_per_km = $this->priceBangalorePerKm();
      $this->price_per_wait_minute = $this->priceBangalorePerMin();
    }
  }

  private function priceMumbaiPerKm(){
    if($this->date_entered >= date("2012-09-20 18:30") || $this->pickup_date >= date("2012-09-20 18:30")){
      return self::MUMBAI_PER_KM;
    }else{
      return self::OLD_MUMBAI_PER_KM;
    }
  }

  private function priceMumbaiPerMin(){
    if($this->date_entered >= date("2012-09-20 18:30") || $this->pickup_date >= date("2012-09-20 18:30")){
      return self::MUMBAI_WAIT_PER_KM;
    }else{
      return self::OLD_MUMBAI_WAIT_PER_KM;
    }
  }

  private function priceBangalorePerKm(){
    if($this->priority_tagging=="airport_drop" || $this->priority_tagging=="airport_pickup"){
      return self::OLD_BANGALORE_PER_KM;
    }else if(($this->date_entered >= date("2012-12-05 18:30") && $this->pickup_date >= date("2012-12-06 18:30")) || $this->date_entered >= date("2012-12-06 18:30")){
      return self::BANGALORE_PER_KM;
    }else{
      return self::OLD_BANGALORE_PER_KM;
    }
  }

  private function priceBangalorePerMin(){
    if(($this->date_entered >= date("2012-12-05 18:30") && $this->pickup_date >= date("2012-12-06 18:30")) || $this->date_entered >= date("2012-12-06 18:30")){
      return self::BANGALORE_WAIT_PER_KM;
    }else{
      return self::OLD_BANGALORE_WAIT_PER_KM;
    }
  }

  private function priceDelhiPerKm(){
    if($this->pickup_date >= date("2012-11-06 18:30")){
      return self::DELHI_PER_KM;
    }else{
      return self::OLD_DELHI_PER_KM;
    }
  }

  private function priceDelhiPerMin(){
    if($this->pickup_date >= date("2012-11-06 18:30")){
      return self::DELHI_WAIT_PER_KM;
    }else{
      return self::OLD_DELHI_WAIT_PER_KM;
    }
  }


}
