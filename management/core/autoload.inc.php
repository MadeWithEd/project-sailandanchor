<?php
/**
 **
 ** Pro:cms
 ** Revision: 4.07.2007
 ** author: Scott Dilley
 ** version: 2.0
 **
 ** Loads any external php libraries
 **
*/

if (!defined('BASEPATH')) exit('No direct script access allowed');
//"excel"=>"Writer.php",
## define each library to load
$PCLIBRARIES=array("PHPMailer"=>"class.phpmailer.php","excel"=>"Writer.php");
if(is_array($PCLIBRARIES)) {
	foreach($PCLIBRARIES as $PCLDIR=>$PCLFILE) {
		if(!$error = include_once(FCPATH.'libraries/'.$PCLDIR.'/'.$PCLFILE)) {
		$heading="Error loading library";
		$message="Unable to load library: ".$PCLDIR."/".$PCLFILE;
		include_once(FCPATH.'errors/error_general.php');
		die;
		}
	}
}

?>