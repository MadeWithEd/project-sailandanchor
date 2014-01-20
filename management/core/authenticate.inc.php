<?php
/**
 **
 ** Pro:cms
 ** Revision: 4.07.2007
 ** author: Scott Dilley
 ** version: 2.0
 **
 ** Main authenticator for cms
 **
*/

if (!defined('BASEPATH')) exit('No direct script access allowed');

## check that an admin user exists
if(! MysqlResult("SELECT count(*) FROM CMS_users")) {
$UserAdded=false;	
	if(isset($_POST["SETUP"])) {
		if($_POST["SETUP"]=="adduser") {
			if(!$MISSING = CheckMandatoryFields()) {
				$FD=array(
				"usertype_id" => "1",
				"u_name" => PrepareData("admin_name"),
				"u_email" => PrepareData("admin_email"),
				"u_status" => "1",
				"u_pass" => PrepareData("admin_password"),
				"date_created{insert}" => date("Y-m-d H:i:s")
				);
				if(!mysql_query(MySQLInsert($FD,"CMS_users"))) {
				$errormsg="Oops! We reported an error: ".@mysql_error();
				}
				else {
				$UserAdded=true;
				}
			}
		}
	}
	if(!$UserAdded) {
	include(FCPATH.'errors/error_users.php');
	die;
	}
}


if(session_is_registered($CONFIG['session']['name'])) {

	## check perms
	$UserDetails=@mysql_fetch_array(mysql_query("SELECT a.*,b.access FROM CMS_users AS a, CMS_usertypes AS b WHERE a.user_id='".$_SESSION[$CONFIG['session']['name']]["UID"]."' AND a.usertype_id=b.usertype_id"));
	$AccessType=$UserDetails["usertype_id"];
	$UserName=$UserDetails["u_name"];
	$UserAccess=@explode(",",$UserDetails["access"]);
	$_SESSION[$CONFIG['session']['name']]["u_results"]=$UserDetails["u_results"];

	## is this a client wanting to approve something?
	if(strlen($_SERVER["QUERY_STRING"])>0) {
	
	}
}
else {

	if(isset($_POST["Login"])) { ## user wants to log in
	
		if(isset($_POST["remind"]) && PrepareData("remind")=="Y") { ## send reminder email
		$Details=@mysql_fetch_array(mysql_query("SELECT u_name,u_email,u_pass FROM CMS_users WHERE u_email='".trim($_POST["u_email"])."'"));
		
			if($Details["u_email"]) {
			$EmailSubject="Forgotten Password for $SiteName";
$EmailBody="Hi ".$Details["u_name"].",

This is an automated email which has been activated by someone trying to login
to the $SiteName admin area and requesting for a password.
If you did not request this email you may want to login to the admin and
change your password.

Login name: ".$Details["u_email"]."
Password: ".$Details["u_pass"]."

-------------------------------------------------------------------------------
";
			## send email
			mail($Details["u_email"],$EmailSubject,$EmailBody,"From: no-reply@".$_SERVER["SERVER_NAME"]);
			$message="An email containing your password has been sent to ".$Details["u_email"];
			include(FCPATH.'errors/error_login.php');
			exit;
			}			
			else {
			$message="We were unable to find a matching email address. Please check your details and try again.";
			include(FCPATH.'errors/error_login.php');
			exit;
			} 
		}
		else {
			$res=@mysql_fetch_array(mysql_query("SELECT user_id,usertype_id,lang_id FROM CMS_users WHERE u_email='".trim($_POST["u_email"])."' AND u_pass='".trim($_POST["u_pass"])."'"));
			
			if($res["user_id"] > 0) {
			session_register($CONFIG['session']['name']);
			$_SESSION[$CONFIG['session']['name']]["UID"]=$res["user_id"];	
			$_SESSION[$CONFIG['session']['name']]["TID"]=$res["usertype_id"];
						
			## Record this login
			mysql_query("INSERT INTO CMS_logins (user_id,last_login) VALUES ('".$res["user_id"]."',now())");
				
				if($_POST["Page"]!= "") {
				$url=$_POST["Page"];
				}
				else {
				$url="index.html";
				}
		
			header("location: $url");
			exit;
			}			
			else {
			$message="Unable to login, please check your details and try again.";
			include(FCPATH.'errors/error_login.php');
			exit;
			}
		}
	}
	else {
	$message="Welcome, please login...";
	include(FCPATH.'errors/error_login.php');
	exit;
	}
}
?>