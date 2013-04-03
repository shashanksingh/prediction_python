<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Email
| -------------------------------------------------------------------------
| This file lets you define parameters for sending emails.
| Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/libraries/email.html
|
*/

$config['useragent'] = "CodeIgniter";
$config['protocol'] = "smtp";
$config['_smtp_auth']   = TRUE;
$config['smtp_host'] = "ssl://smtp.googlemail.com";
$config['smtp_user'] = "adwords@olacabs.com";
$config['smtp_pass'] = "sanjayola";
$config['smtp_port'] = 465;

$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['newline'] = "\r\n";

/* End of file email.php */
/* Location: ./application/config/email.php */
