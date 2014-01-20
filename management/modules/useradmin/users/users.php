<?php
$error=false;
$task=(isset($_REQUEST["task"]))?$_REQUEST["task"]:"";
switch($task) {

	case "create":
		$_REQUEST["id"]=(isset($_REQUEST["id"]))?$_REQUEST["id"]:"-1";
		$Details=MySQLArray("SELECT * FROM CMS_users WHERE user_id='".$_REQUEST["id"]."'");
	DisplayUserForm($Details);
	break;
	
	case "save":
		if($_POST["u_status"]==3 && $_POST["id"] > 0) { ## remove
		mysql_query("DELETE FROM CMS_users WHERE user_id='".$_POST["id"]."'");
		}
		else {
			if(!$MISSING = CheckMandatoryFields()) {
				## Create SQL for insert/Update queries
				$FD=Array(
				"usertype_id" => PrepareData("usertype_id"),
				"u_name" => PrepareData("u_name"),
				"u_email" => PrepareData("u_email"),
				"u_status{update}" => PrepareData("u_status"),
				"u_pass" => PrepareData("u_pass"),
				"date_created{insert}" => date("Y-m-d H:i:s")
				);
				if($_POST["id"]) { ## update
					if(!mysql_query(MySQLUpdate($FD,"CMS_users","user_id",$_POST["id"]))) {
					$msg="Oops! We reported an error: ".@mysql_error();
					}
					else {
					$msg="Your changes have been saved.";
					}
				}
				else { ## new
					if(!mysql_query(MySQLInsert($FD,"CMS_users"))) {
					$msg="Oops! We reported an error: ".@mysql_error();
					}
					else {
					$msg="Your changes have been saved.";
					}
				}
				header("location: ".FCPATHNICE."?mod=useradmin&msg=".urlencode($msg));
				exit;
			}
		}
	break;
	
	default:
		if(isset($_REQUEST["v"]) && $_REQUEST["v"]=="search") {
		DisplayUserSearch();
		}
		else {
		$orderby=(isset($_REQUEST["orderby"]) && $_REQUEST["orderby"]!="")?$_REQUEST["orderby"]:"a.u_name";
		DisplayUsers();
		}
	break;

}
?>