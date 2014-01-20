<?php
## Set reporting to all
## Should disable when going live
#error_reporting(E_ALL);

## Specify main includes directory
$system_folder=pathinfo(__FILE__,PATHINFO_DIRNAME).'/core/';


## define some constants

define('BASEPATH', $system_folder); ## core folder
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME)); ## this file name
define('FCPATH', str_replace(SELF,'',__FILE__)); ## full path to admin
define('FCPATHNICE',ereg_replace("/management/(.*)","/management/",$_SERVER["PHP_SELF"])); ## html friendly version of above
define('SITEPATH',ereg_replace("[^\/]*$","",dirname(__FILE__))); ## full path to site root (1 up from here)
define('SITEPATHNICE',ereg_replace("[^\/]*$","",substr(FCPATHNICE,0,strlen(FCPATHNICE)-1)));

if (stristr(PHP_OS, 'WIN')) { 
define('SITEOS','WINDOWS');
} else { 
define('SITEOS','UNIX');
}

## setup template
if(!defined('TEMPLATEPATH')) {
	define('TEMPLATEPATH',FCPATH.'templates/default/'); ## path to default template
	define('TEMPLATEPATHNICE',FCPATHNICE.'templates/default/'); ## html friendly to above
}

if (!defined('E_STRICT')) {
	define('E_STRICT', 2048);
}

## start sessions
//ini_set("session.save_handler", "files");
//session_save_path (FCPATH."tmp/");
//include_once(BASEPATH."sessions.inc.php");
session_start();


if(isset($_REQUEST["logout"])) {
$_SESSION = array();
	if (isset($_COOKIE[session_name()])) {
	setcookie(session_name(), '', time()-42000, '/');
	}
session_destroy();
header("location:".FCPATHNICE);
exit;
}

if(!isset($LoadCMS)) {
## load up include files
require_once BASEPATH.'procms.inc.php';
exit;
}


?>