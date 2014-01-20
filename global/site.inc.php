<?php
//if(!eregi("202.171.161.242|203.222.164.19|203.222.164.3|203.98.193.250|124.170.64.170",$_SERVER["REMOTE_ADDR"])) {
//echo $_SERVER["REMOTE_ADDR"];
//exit;
//}

define("USERUPLOADS",$_SERVER["DOCUMENT_ROOT"].'/images/uploads/');
define("USERUPLOADSNICE","/images/uploads/");
define("SITEURL","http://leraygymnastics.madewithed.com");

session_start();

## sticking in a timer to see how long it takes to load a page
$starttime = microtime();
$startarray = explode(" ", $starttime);
$starttime = $startarray[1] + $startarray[0];

#-- This is where you tell me which of the default files to include --#
#-- Just comment the ones you aren't going to use --#

$INCLUDES["files"]["cache"] = "Cache_Lite/Lite.php";
$INCLUDES["files"]["db"]="MDB2.php";
$INCLUDES["files"]["mysql"] = "database.inc.php"; /* This is the default mysql connector and session handler */
$INCLUDES["files"]["function"] = "functions.inc.php"; /* This is the default include for functions */
$INCLUDES["files"]["images"] = "image.resize.class.php"; /* Image resizing class */
$INCLUDES["files"]["mailer"] = "PHPMailer/class.phpmailer.php";
$INCLUDES["files"]["gateway"] = "gateway/payway.class.php";
$INCLUDES["files"]["securimage"] = "securimage/securimage.php";

$ThisPage=ereg_replace("/\/\ $","",$_SERVER["SCRIPT_NAME"]);

#-- This discovers the include directory name --#

$INCLUDES["directory"] = pathinfo(__FILE__,PATHINFO_DIRNAME).'/';


#-- This includes all of the files sequentially --#
foreach($INCLUDES["files"] as $key=>$functionref){
	//echo $INCLUDES["directory"].$functionref."<br>";
	include_once($INCLUDES["directory"].$functionref);
}
unset($key);
unset($functionref);

?>