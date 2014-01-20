<?php
// profile updater

if(isset($_POST["mod"])) {
	if(!$MISSING = CheckMandatoryFields()) {
		## Create SQL for insert/Update queries
		$FD=Array(
		"u_name" => PrepareData("u_name"),
		"u_email" => PrepareData("u_email"),
		"u_pass" => PrepareData("u_pass"),
		"u_results" => PrepareData("u_results"),
		"last_updated" => date("Y-m-d H:i:s")
		);
		if(!mysql_query(MySQLUpdate($FD,"CMS_users","user_id",$_SESSION[$CONFIG['session']['name']]["UID"]))) {
		$msg="Oops! We reported an error: ".@mysql_error();
		}
		else {
		$msg="Your profile has been updated.";
		}
	header("location: ".FCPATHNICE."?".urlencode($msg));
	exit;
	}
}


	$Details=MySQLArray("SELECT * FROM CMS_users WHERE user_id='".$_SESSION[$CONFIG['session']['name']]["UID"]."'");
	DisplayUserProfile($Details);

?>