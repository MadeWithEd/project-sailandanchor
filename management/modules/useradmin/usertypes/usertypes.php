<?php
$error=false;
$task=(isset($_REQUEST["task"]))?$_REQUEST["task"]:"";
switch($task) {

	case "create":
		$_REQUEST["id"]=(isset($_REQUEST["id"]))?$_REQUEST["id"]:"-1";
		$Details=MySQLArray("SELECT * FROM CMS_usertypes WHERE usertype_id='".$_REQUEST["id"]."'");
	DisplayUserTypeForm($Details);
	break;
	
	case "save":
		if($_POST["ut_status"]==3 && $_POST["id"] > 0) { ## remove
		mysql_query("DELETE FROM CMS_usertypes WHERE usertype_id='".$_POST["id"]."'");
		}
		else {
			if(!$MISSING = CheckMandatoryFields()) {
				foreach($_POST["access"] as $key=>$val) {
				$Access.=$val.",";
				}
	
				## Create SQL for insert/Update queries
				$FD=Array(
				"usertype" => PrepareData("usertype"),
				"access" => $Access
				);
				if($_POST["id"]) { ## update
					if(!mysql_query(MySQLUpdate($FD,"CMS_usertypes","usertype_id",$_POST["id"]))) {
					$error="Oops! We reported an error: ".@mysql_error();
					}
					else {
					$msg="Your changes have been saved.";
					}
				}
				else { ## new
					if(!mysql_query(MySQLInsert($FD,"CMS_usertypes"))) {
					$error="Oops! We reported an error: ".@mysql_error();
					}
					else {
					$msg="Your changes have been saved.";
					}
				}
				if(!$error) {
				header("location: ".FCPATHNICE."?mod=".$_REQUEST["mod"]."&sub=".$_REQUEST["sub"]."&msg=".urlencode($msg));
				exit;
				}
				else {
				$Details=MySQLArray("SELECT * FROM CMS_usertypes WHERE usertype_id='".$_REQUEST["id"]."'");
				DisplayUserTypeForm($Details);
				}
			}
		}
	break;
	
	default:
		if(isset($_REQUEST["v"]) && $_REQUEST["v"]=="search") {
		DisplayUserSearch();
		}
		else {
		$orderby=(isset($_REQUEST["orderby"]) && $_REQUEST["orderby"]!="")?$_REQUEST["orderby"]:"usertype";
		DisplayUserTypes();
		}
	break;

}
?>