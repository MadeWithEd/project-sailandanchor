<?php
$error=false;
$task=(isset($_REQUEST["task"]))?$_REQUEST["task"]:"";

switch($task) {


	case "create":
		$_REQUEST["id"]=(isset($_REQUEST["id"]))?$_REQUEST["id"]:"-1";
		$Details=MySQLArray("SELECT * FROM pages WHERE page_id='".$_REQUEST["id"]."'");
	DisplayPageForm($Details);
	break;
	
	case "save":
		if(isset($_POST["remove_x"]) && $_POST["id"] > 0) { ## remove
		mysql_query("DELETE FROM pages WHERE page_id='".$_POST["id"]."'");
		RedirectTo(FCPATHNICE."?mod=".$_REQUEST["mod"]."&msg=".urlencode("Your contact has been removed"));
		exit;
		}
		if(isset($_POST["cancel_x"])) {
		RedirectTo(FCPATHNICE."?mod=".$_REQUEST["mod"]."&msg=".urlencode("cancelled"));
		exit;
		}
		
		if(!$MISSING = CheckMandatoryFields()) {
			
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
				if($_POST["id"]>0) { ## update
					if(!mysql_query(MySQLUpdate($FD,"pages","page_id",$_POST["id"]))) {
					$error=true;
					$errormsg="Oops! We reported an error: ".@mysql_error();
					$product_id=$_POST["id"];
					}
					
				}
				else {
					if(!mysql_query(MySQLInsert($FD,"contacts"))) {
					$error=true;
					$errormsg="Oops! We reported an error: ".@mysql_error();
					}
				}
			}
				
			if(!$error) {
			RedirectTo(FCPATHNICE."?mod=".$_REQUEST["mod"]."&task=other&msg=".urlencode("Your changes have been saved"));
			exit;
			}
			else {
			$Details=MySQLArray("SELECT * FROM pages WHERE page_id='".$_REQUEST["id"]."'");
			DisplayPageForm($Details);
			}
		}
		else {
		$Details=MySQLArray("SELECT * FROM pages WHERE page_id='".$_REQUEST["id"]."'");
		DisplayPageForm($Details);
		}
		
	break;
	
	default:
		if(isset($_REQUEST["v"]) && $_REQUEST["v"]=="search") {
		DisplaySearch();
		}
		else {
		DisplayOthers();
		}
	break;
}

function FindValue($Content,$ROW) {
	## first, look for $vars shown as {var} to translate
	preg_match_all("/\{([a-zA-Z0-9_-]*)\}/i",$Content,$v);
		foreach($v[1] as $vr) {
		$Content=ereg_replace("\{".$vr."\}",stripslashes($ROW[$vr]),$Content);
		}
				
	## now look for code to execute shown as #- code -#
	preg_match_all("/\#-(.*)-\#/",$Content,$c);
		foreach($c[1] as $cd) {			
				
		ob_start();
		$cde=eval($cd);
		$code = ob_get_contents();
		ob_end_clean();
		$Content=str_replace("#-".$cd."-#",$code,$Content);
		}
				
	## now look for <sql> statements
	preg_match_all("/\#s-(.*)-s\#/",$Content,$s);
		foreach($s[1] as $sl) {
		$result=@mysql_result(mysql_query($sl),0);
		$Content=str_replace("#s-".$sl."-s#",$result,$Content);
		$DATA[$x]=$result;
		}
return $Content;
}

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