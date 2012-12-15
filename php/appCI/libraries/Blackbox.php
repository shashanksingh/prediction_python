<?php

/**
 * Blackbox api
 * 
 * @author Prashanth Jonnala <prashanth@olacabs.com>
 */
class Blackbox
{
    /**
     * Instance of the singleton class
     */
    private static $instance;
    
    
    
    /**
     * Initializes the Blackbox api with the given configuration
     *
     * @param mixed config The configuration to use for initializing the api 
     * @throws Exception
     *
     * return bool true on successful initialization of the api 
     */
    public function initialize($config) {
        
        return true;
    }
    
    /**
     * Retrieve the instance of the Blackbox api
     *
     * @return object Blackbox
     */
    public static function getInstance($config = null) {
        if (!self::$instance) {
            self::$instance = new Blackbox($config);
        }
        
        return self::$instance;
    }
    
    
    
    public function create_guid() {
        $microTime = microtime();
        list($a_dec, $a_sec) = explode(" ", $microTime);

        $dec_hex = dechex($a_dec* 1000000);
        $sec_hex = dechex($a_sec);

        $this->ensure_length($dec_hex, 5);
        $this->ensure_length($sec_hex, 6);

        $guid = "";
        $guid .= $dec_hex;
        $guid .= $this->create_guid_section(3);
        $guid .= '-';
        $guid .= $this->create_guid_section(4);
        $guid .= '-';
        $guid .= $this->create_guid_section(4);
        $guid .= '-';
        $guid .= $this->create_guid_section(4);
        $guid .= '-';
        $guid .= $sec_hex;
        $guid .= $this->create_guid_section(6);

        return $guid;
    }

    public function create_guid_section($characters) {
        $return = "";
        for($i = 0; $i < $characters; $i++) {
            $return .= dechex(mt_rand(0, 15));
        }
        
        return $return;
    }
    public function ensure_length(&$string, $length) {
        $strlen = strlen($string);
        if ($strlen < $length) {
            $string = str_pad($string, $length, "0");
        } else if ($strlen > $length) {
            $string = substr($string, 0, $length);
        }
    }
    
}