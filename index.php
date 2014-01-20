<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/global/site.inc.php");

if(isset($_POST["form_name"])) {
	
	$_POST["formerror"]=false;
	$PageParts=array_reverse(explode("/",ereg_replace("/$","",$_GET["PAGE"])));
	$PageFileName=$PageParts[0];
	
	$PageDetails=MySQLArray("SELECT * FROM pages WHERE page_file_name='".$PageFileName."' AND page_status='1'");
	
	if(isset($_POST["recaptcha_response_field"])) {
		require_once($_SERVER["DOCUMENT_ROOT"].'/global/recaptchalib.php');
		$privatekey = "6LeFmOwSAAAAAO6MRYNINZwFC5DcwrfU2x9uOsvB";
		$resp = recaptcha_check_answer ($privatekey,$_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);
		if (!$resp->is_valid) {
		$_POST["formerror"]=true;
		$_POST["formerrormsg"]="Please re-enter the words shown in the bottom panel";
		}
	}
	
	if(!$_POST["formerror"]) {
		$FD=Array("form_name"=>PrepareData("form_name"),"sess_id" => $_COOKIE["PHPSESSID"],"user_ip" => $_SERVER["REMOTE_ADDR"],"date_created" => date("Y-m-d H:i:s"));
		if(mysql_query(MySQLInsert($FD,"form_submissions"))) {
			$form_id=mysql_insert_id();
			$HTML="Hi there, the following information has just been received on the Sail and Anchor website\n\n";
			foreach($_POST as $key=>$val) {
				if($key!="mandatory") {
					$FD2=array("form_id"=>$form_id,"field_name"=>$key,"field_value"=>addslashes($val),"date_created" => date("Y-m-d H:i:s"));
					mysql_query(MySQLInsert($FD2,"form_data"));
					$HTML.=$key.': '.$val."\n";
				}
			}
			$mailto="scottdogs@mac.com";
			if($mailto!="") {
			mail($mailto,"Form submission on sailandanchor.com.au",$HTML,"From: noreply@sailandanchor.com.au");
			}
		}
		
		if(isset($_POST["isajax"])) {
		header("location: /".MySQLResult("SELECT page_file_name FROM pages WHERE sub_id=".$PageDetails["page_id"]." ORDER BY sort_order ASC LIMIT 1")."?isajax=1");
		}
		else {
		header("location: /".MySQLResult("SELECT page_file_name FROM pages WHERE sub_id=".$PageDetails["page_id"]." ORDER BY sort_order ASC LIMIT 1")."?");
		}
		exit;
	}
}

if(isset($_POST["ofage"]) && $_POST["ofage"]=='Y') {
	setCookie("ofage","Y",time()+3600*24*30*12,"/",".madewithed.com",false);
	header("location: /");
	exit;
}

if(isset($_GET["PAGE"])) {

	$PageParts=array_reverse(explode("/",ereg_replace("/$","",$_GET["PAGE"])));
	$PageFileName=$PageParts[0];
	
	## Check DB for matching page
	if($PageDetails=MySQLArray("SELECT * FROM pages WHERE page_file_name='".$PageFileName."' AND page_status='1'")) {
		
		$NavTree=GetRecursiveSubNav($PageDetails["sub_id"]);
		$NavTreeForward=GetReverseRecursiveSubNav($PageDetails["page_id"]);
		//echo '<pre>';
		//print_r($NavTreeForward);
		//echo '</pre>';
		
		## which template?
		if($PageDetails["is_home"]=="Y") {
		include_once($_SERVER["DOCUMENT_ROOT"]."/templates/home.tpl.php");
		exit;
		}
		
		else {
			
			if(count($NavTreeForward)>1) {
			$HasSubPages=true;
			}
			
			if(MySQLResult("SELECT count(*) FROM pages WHERE sub_id='".$NavTree[1]["page_id"]."' AND sub_id>0")>0) {
			$NavRoot=$NavTree[1]["page_id"];
			$NavRootName=$NavTree[1]["page_name"];
			$NavRootFileName=$NavTree[1]["page_file_name"];
			}
			else {
			$NavRoot=$PageDetails["page_id"];
			$NavRootFileName=$PageDetails["page_file_name"];
			$NavRootName=$PageDetails["page_name"];
			}
			
			if($PageDetails["section_id"]=='the pub') {
			include_once($_SERVER["DOCUMENT_ROOT"]."/templates/thepub.tpl.php");
			}
			else {
			include_once($_SERVER["DOCUMENT_ROOT"]."/templates/content.tpl.php");
			}
		exit;
		}
		
	exit;
	}
	
	## Display page not found 404
	$PageDetails=MySQLArray("SELECT * FROM pages WHERE page_id=1");
	include_once($_SERVER["DOCUMENT_ROOT"]."/templates/home.tpl.php");
	exit;

}



## Display home page
$PageDetails=MySQLArray("SELECT * FROM pages WHERE page_id=1");
include_once($_SERVER["DOCUMENT_ROOT"]."/templates/home.tpl.php");

?>