<?php
$error=false;
$task=(isset($_REQUEST["task"]))?$_REQUEST["task"]:"";

switch($task) {

	case "moveup":
		if($_REQUEST["id"]) {
		$ThisOne=MySQLResult("SELECT sort_order FROM pages WHERE page_id='".$_REQUEST["id"]."'");
		$Details=MySQLArray("SELECT * FROM pages WHERE page_id='".$_REQUEST["id"]."'");
		$Above=MySQLArray("SELECT MAX(sort_order) FROM pages WHERE sub_id='".$Details["sub_id"]."' AND sort_order < '".$ThisOne."'");
		mysql_query("UPDATE pages SET sort_order='99999' WHERE sub_id='".$Details["sub_id"]."' AND sort_order='".$Above."'");
		mysql_query("UPDATE pages SET sort_order='".$Above."' WHERE sub_id='".$Details["sub_id"]."' AND sort_order='".$ThisOne."'");
		mysql_query("UPDATE pages SET sort_order='".$ThisOne."' WHERE sub_id='".$Details["sub_id"]."' AND sort_order='99999'");
		}
		RedirectTo(FCPATHNICE."?mod=".$_REQUEST["mod"]."&msg=".urlencode("Your page has been moved"));
		exit;
	break;
	
	case "create":
		$_REQUEST["id"]=(isset($_REQUEST["id"]))?$_REQUEST["id"]:"-1";
		$Details=MySQLArray("SELECT * FROM pages WHERE page_id='".$_REQUEST["id"]."'");
		DisplayTemplateForm($Details);
	break;
	
	case "save":
		if(isset($_POST["remove_x"]) && $_POST["id"] > 0) { ## remove
		mysql_query("DELETE FROM pages WHERE page_id='".$_POST["id"]."'");
		RedirectTo(FCPATHNICE."?mod=".$_REQUEST["mod"]."&msg=".urlencode("Your page has been removed"));
		exit;
		}
		
		if(isset($_POST["cancel_x"])) { ## cancel
		RedirectTo(FCPATHNICE."?mod=".$_REQUEST["mod"]."&msg=".urlencode("Action cancelled"));
		exit;
		}
		
		if(isset($_POST["archive_x"])) { ## cancel
		mysql_query(MySQLUpdate(array("page_status"=>"2"),"pages","page_id",$_POST["id"]));
		RedirectTo(FCPATHNICE."?mod=".$_REQUEST["mod"]."&msg=".urlencode("Page has been archived"));
		exit;
		}
		
		if(!$MISSING = CheckMandatoryFields()) {
			
			$Details=MySQLArray("SELECT * FROM pages WHERE page_id='".$_REQUEST["id"]."'");
			$SortOrder=MySQLResult("SELECT MAX(sort_order) FROM pages WHERE sub_id='".PrepareData("sub_id")."'")+1;
			
			## Check if exists
			if(!$_REQUEST["id"]>0 && MySQLResult("SELECT count(*) FROM pages WHERE page_file_name='".CreateFileName(PrepareData("page_name"))."'") > 0) {
			$error=true;
			$errormsg="A page with the same name already exists";
			}
			$IsSecondLevel=false;
			if($Details["page_id"]>0) {
			$sql=mysql_query("SELECT page_id,sub_id FROM pages WHERE sub_id='".$Details["page_id"]."'");
				//if(count($sql)>0) {	
					while($row=mysql_fetch_array($sql)) {
						if(MySQLResult("SELECT count(*) FROM pages WHERE sub_id='".$row["page_id"]."'")>0) {
						$IsSecondLevel=true;
						}
					}
				//}
			}
			
			## Home page
			
			if($Details["is_home"]=="Y") {
				$FD=Array(
				"page_short_desc" => PrepareData("page_short_desc"),
				"page_home_left_copy" => PrepareData("page_home_left_copy"),
				"page_home_left_link" => PrepareData("page_home_left_link"),
				"page_home_right_copy" => PrepareData("page_home_right_copy"),
				"page_meta_title" => PrepareDataEnum("page_meta_title"),
				"page_meta_keywords" => PrepareDataEnum("page_meta_keywords",strip_tags(PrepareData('page_short_desc'))),
				"page_meta_description" => PrepareDataEnum("page_meta_description",strip_tags(PrepareData('page_short_desc'))),
				"last_updated" => date("Y-m-d H:i:s"),
				"date_created{insert}" => date("Y-m-d H:i:s")
				);
				if(!$error) {
					if($_REQUEST["id"]>0) {
						if(!mysql_query(MySQLUpdate($FD,"pages","page_id",$_POST["id"]))) {
						$error=true;
						$errormsg="Please try again";
						}
						$page_id=$_REQUEST["id"];
					}
					else {
						if(!mysql_query(MySQLInsert($FD,"pages"))) {
						$error=true;
						$errormsg="Please try again";
						}
						$IsNew=true;
						$page_id=mysql_insert_id();
					}
				}
				
				## now promos
				if(isset($_POST["promo"])) {
					for($x=0;$x<count($_POST["promo"]);$x++) {
						if($_POST["promo_name"][$x]!="") {
							$FD=Array(
							"promo_name" => addslashes($_POST["promo_name"][$x]),
							"promo_url" => addslashes($_POST["promo_url"][$x])
							);
							
							//echo '<pre>';
							//print_r($_FILES);
							//echo '</pre>';exit;
							
							if(isset($_FILES["promo_image"]["tmp_name"][$x]) && is_uploaded_file($_FILES["promo_image"]["tmp_name"][$x])) {
								$filename=base_convert( md5(microtime()), 10, 36 );
								$ext='.'.strtolower(substr(strrchr($_FILES["promo_image"]["name"][$x],'.'), 1));
								if(copy($_FILES["promo_image"]["tmp_name"][$x],$Config["content"]["images"].$filename.$ext)) {
								$FD=array_merge($FD,array("promo_image" => $filename.$ext));
								}
							}
							
							if(isset($_FILES["promo_text"]["tmp_name"][$x]) && is_uploaded_file($_FILES["promo_text"]["tmp_name"][$x])) {
								$filename=base_convert( md5(microtime()), 10, 36 );
								$ext='.'.strtolower(substr(strrchr($_FILES["promo_text"]["name"][$x],'.'), 1));
								if(copy($_FILES["promo_text"]["tmp_name"][$x],$Config["content"]["images"].$filename.$ext)) {
								$FD=array_merge($FD,array("promo_text" => $filename.$ext));
								}
							}
							
							if($_POST["promo"][$x]!="") {
								mysql_query(MySQLUpdate($FD,"home_promos","promo_id",$_POST["promo"][$x]));
							}
							else {
								mysql_query(MySQLInsert($FD,"home_promos"));
							}
						}
					}
				}
				
			}
			
			## Content page
			
			else {
			
				$page_file_name=($_POST["page_file_name"]!="")?CreateFileName(PrepareData("page_file_name")):CreateFileName(PrepareData("page_name"));
			
				$FD=Array(
				"hide_nav" => PrepareDataEnum("hide_nav"),
				"sub_id" => PrepareData("sub_id"),
				"page_name" => PrepareData("page_name"),
				"in_nav" => PrepareDataEnum("in_nav"),
				"in_meganav" => PrepareDataEnum("in_meganav"),
				"page_file_name" => $page_file_name,
				"page_meta_title" => PrepareDataEnum("page_meta_title",'Audrey & Associates : '.PrepareData('page_name')),
				"page_meta_keywords" => PrepareDataEnum("page_meta_keywords",PrepareData('page_short_desc')),
				"page_meta_description" => PrepareDataEnum("page_meta_description",PrepareData('page_short_desc')),
				"sort_order{insert}" =>$SortOrder,
				"section_id" => PrepareData("section_id"),
				"page_donation_title" => PrepareData("page_donation_title"),
				"page_html" => PrepareData("page_html"),
				"featured" => PrepareDataEnum("featured"),
				"featured_title" => PrepareData("featured_title"),
				"featured_url" => PrepareData("featured_url"),
				"featured_description" => PrepareData("featured_description"),
				"page_status" => PrepareData("page_status"),
				"page_type"=> PrepareData("page_type"),
				"page_title" => PrepareData("page_title"),
				"last_updated" => date("Y-m-d H:i:s"),
				"date_created{insert}" => date("Y-m-d H:i:s")
				);
				
				if($Details["is_form"]=="Y") {
					$FD2=Array(
					"redirect_id" => PrepareData("redirect_id"),
					"page_donation_title" => PrepareData("page_donation_title"),
					"page_donation_intro" => PrepareData("page_donation_intro"),
					"donation_amount_1" => PrepareData("donation_amount_1"),
					"donation_amount_2" => PrepareData("donation_amount_2"),
					"donation_amount_3" => PrepareData("donation_amount_3"),
					"donation_amount_4" => PrepareData("donation_amount_4"),
					"page_donation_send_email" => PrepareDataEnum("page_donation_send_email"),
					"page_donation_email_subject" => PrepareData("page_donation_email_subject"),
					"page_donation_email" => PrepareData("page_donation_email"),
					);
					$FD=array_merge($FD,$FD2);
				}
				
				if(is_uploaded_file($_FILES["page_masthead"]["tmp_name"])) {
					$filename=base_convert( md5(microtime()), 10, 36 );
					$ext='.'.strtolower(substr(strrchr($_FILES["page_masthead"]["name"],'.'), 1));
					if(copy($_FILES["page_masthead"]["tmp_name"],$Config["content"]["images"].$filename.$ext)) {
					$FD=@array_merge($FD,array("page_masthead" => $filename.$ext));
					}
					else {
					$error=true;
					}
				}
				
				if(is_uploaded_file($_FILES["featured_image"]["tmp_name"])) {
					$filename=base_convert( md5(microtime()), 10, 36 );
					$ext='.'.strtolower(substr(strrchr($_FILES["featured_image"]["name"],'.'), 1));
					if(copy($_FILES["featured_image"]["tmp_name"],$Config["content"]["images"].$filename.$ext)) {
					$FD=@array_merge($FD,array("featured_image" => $filename.$ext));
					}
					else {
					$error=true;
					}
				}
				
				if(isset($_POST["remove_current_image"])) {
				$FD=@array_merge($FD,array("page_masthead" => ""));
				}
				if(isset($_POST["remove_featured_image"])) {
				$FD=@array_merge($FD,array("featured_image" => ""));
				}
				
				if(!$error) {
					if($_REQUEST["id"]>0) {
						if(!mysql_query(MySQLUpdate($FD,"pages","page_id",$_POST["id"]))) {
						$error=true;
						$errormsg="Please try again";
						}
						$page_id=$_REQUEST["id"];
					}
					else {
						if(!mysql_query(MySQLInsert($FD,"pages"))) {
						$error=true;
						$errormsg="Please try again";
						}
						$page_id=mysql_insert_id();
						$IsNew=true;
					}
				}
				
				
			}
			
			if(!$error) {
				if(isset($_POST["publish_x"])) { ## cancel
				mysql_query(MySQLUpdate(array("page_status"=>"1"),"pages","page_id",$page_id));
				}
			}
			
			if(!$error) {
			RedirectTo(FCPATHNICE."?mod=".$_REQUEST["mod"]."&msg=".urlencode("Your changes have been saved"));
			exit;
			}
			else {
			$Details=MySQLArray("SELECT * FROM pages WHERE page_id='".$_REQUEST["id"]."'");
			DisplayTemplateForm($Details);
			}
		}
		else {
		$Details=MySQLArray("SELECT * FROM pages WHERE page_id='".$_REQUEST["id"]."'");
		DisplayTemplateForm($Details);
		}
		
	break;
	
	case "search":
		DisplaySearch();
	break;
	
	default:
		DisplayTemplates();
	break;
}


## Some functions used

function GetRecursiveSubNav($SubNavID) {
	if(!@is_array($SubNav)) {
	$SubNav=array();
	}
	if($SubNavID > 0) {
		$NavDetails=MySQLArray("SELECT * FROM pages WHERE page_id='".$SubNavID."'");
		if($NavDetails["sub_id"]>0) {
		$SubNav=@array_merge($SubNav,GetRecursiveSubNav($NavDetails["sub_id"]));		
		}
	$SubNav=@array_merge($SubNav,array($NavDetails["page_id"]));
	}
return $SubNav;
}

function DisplayRecursive($sub_id) {
	$string="";
	$Cats=GetRecursiveSubNav($sub_id);
	if(count($Cats)>0) {
		foreach($Cats as $key=>$cat) { 
			if($cat>0) { 
			$string.= MySQLResult('SELECT page_name FROM pages WHERE page_id='.$cat).' - ';
			}
		}
	}
	return substr($string,0,-3);
}

function GetPathToPage($page_id) {
	$pageurl='';
	$x=0;
	$PathArray=GetRecursiveSubNav($page_id);
	if(count($PathArray)>0) {	
		foreach($PathArray as $key=>$cat) {
			if($cat>0 && $x>0) { 
			$pageurl.='/'.MySQLResult('SELECT page_file_name FROM pages WHERE page_id='.$cat["page_id"]);
			}
		$x++;
		}
	}
return $pageurl;
}

?>