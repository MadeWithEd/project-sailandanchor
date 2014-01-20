<?php
$error=false;
$task=(isset($_REQUEST["task"]))?$_REQUEST["task"]:"";

switch($task) {


	case "create":
		$_REQUEST["id"]=(isset($_REQUEST["id"]))?$_REQUEST["id"]:"-1";
		$Details=MySQLArray("SELECT * FROM jobs WHERE job_id='".$_REQUEST["id"]."'");
	DisplayPageForm($Details);
	break;
	
	case "save":
		if(isset($_POST["remove_x"]) && $_POST["id"] > 0) { ## remove
		mysql_query("DELETE FROM jobs WHERE job_id='".$_POST["id"]."'");
		RedirectTo(FCPATHNICE."?mod=".$_REQUEST["mod"]."&msg=".urlencode("Member has been removed"));
		exit;
		}
		if(isset($_POST["cancel_x"])) {
		RedirectTo(FCPATHNICE."?mod=".$_REQUEST["mod"]."&msg=".urlencode("cancelled"));
		exit;
		}
		
		if(!$MISSING = CheckMandatoryFields()) {
			
			$FD=Array(
			"is_featured" => PrepareDataEnum("is_featured") ,
			"job_title" => PrepareData("job_title") ,
			"job_summary" => PrepareData("job_summary") ,
			"job_description" => PrepareData("job_description") ,
			"last_updated" => date("Y-m-d") ,
			"date_created{insert}" => date("Y-m-d") ,
			);
			
			if(!$error) {
				if($_POST["id"]>0) { ## update
					if(!mysql_query(MySQLUpdate($FD,"jobs","job_id",$_POST["id"]))) {
					$error=true;
					$errormsg="Oops! We reported an error: ".@mysql_error();
					}
				}
				else {
					if(!mysql_query(MySQLInsert($FD,"jobs"))) {
					$error=true;
					$errormsg="Oops! We reported an error: ".@mysql_error();
					}
				}
			}
			
			if(!$error) {
				for($x=0;$x<count($_POST["tag"]);$x++) {
				mysql_query(MySQLUpdate(array("tag" => $_POST["tag"][$x]),"jobs_tags","tag_id",$_POST["tag_id"][$x]));
				}
			}
				
			if(!$error) {
			RedirectTo(FCPATHNICE."?mod=".$_REQUEST["mod"]."&msg=".urlencode("Your changes have been saved"));
			exit;
			}
			else {
			$Details=MySQLArray("SELECT * FROM jobs WHERE job_id='".$_REQUEST["id"]."'");
			DisplayPageForm($Details);
			}
		}
		else {
		$Details=MySQLArray("SELECT * FROM jobs WHERE job_id='".$_REQUEST["id"]."'");
		DisplayPageForm($Details);
		}
		
	break;
	
	case "search":
		DisplaySearch();
	break;
	
	case "download":
	
		
	
		$WHERE="";
		if(isset($_REQUEST["job_id"])) {
		$WHERE=" AND (";
			foreach($_REQUEST["job_id"] as $id) {
			$WHERE.="a.job_id='".$id."' OR ";
			}
		$WHERE=substr($WHERE,0,strlen($WHERE)-4);
		$WHERE.=")";
		}
		else {
			if(isset($_REQUEST["search"])) {
				if($_REQUEST["date_created1"]!="") {
					if($_REQUEST["date_created2"]!="") {
					$WHERE.=" AND (a.date_created BETWEEN '".trim($_REQUEST["date_created1"])."' AND '".$_REQUEST["date_created2"]."')";
					}
					else {
					$WHERE.=" AND (a.date_created = '".trim($_REQUEST["date_created1"])."')";
					}
				}
				if($_REQUEST["m_fname"]!="") {
				$WHERE.="AND (m_fname LIKE '%".addslashes($_REQUEST["m_fname"])."%')";
				}
				if($_REQUEST["m_lname"]!="") {
				$WHERE.="AND (m_lname LIKE '%".addslashes(PrepareData("m_lname"))."%')";
				}
				if($_REQUEST["m_email"]!="") {
				$WHERE.="AND (m_email LIKE '%".trim($_REQUEST["m_email"])."%')";
				}
				if($_REQUEST["m_state"]!="") {
				$WHERE.="AND (m_state = '".addslashes($_REQUEST["m_state"])."')";
				}
				if($_REQUEST["m_country"]!="") {
				$WHERE.="AND (m_country = '".addslashes($_REQUEST["m_country"])."')";
				}
			$JscriptParams=$_SERVER["QUERY_STRING"];
			}
		}
	
		
		$filename="jobs_".date("dmyHi").".xls";
		$workbook = new Spreadsheet_Excel_Writer();
		$workbook->send($filename);
		$worksheet =& $workbook->addWorksheet('Jobs');
		$Head=array(
		"job_id","m_title" ,"m_fname", "m_lname", "m_email", "m_address", "m_suburb", "m_postcode", "m_state", "m_country", "m_phone", "date_created"
		);
		$Fields=array(
		"{job_id}","{m_title}" ,"{m_fname}", "{m_lname}", "{m_email}", "{m_address}", "{m_suburb}", "{m_postcode}", "{m_state}", "{m_country}", "{m_phone}", "{date_created}"
		);
		$x=0;
		foreach($Head as $Title) {
		$worksheet->write(0, $x, $Title);
		$x++;
		}
		$Query="SELECT * FROM jobs WHERE 1 $WHERE";
		$sql=execute_query($Query);
		$y=1;
		foreach($sql as $row) {
			$x=0;
			foreach($Fields as $Field) {
			$Value=FindValue($Field,$row);
			$worksheet->write($y, $x, $Value);
			$x++;
			}
		$y++;
		}

		$workbook->close();
		
	break;
	
	default:
		if(isset($_REQUEST["v"]) && $_REQUEST["v"]=="search") {
			if(isset($_REQUEST["search_id"]) && $_REQUEST["search_id"]>0) {
			@execute_query("DELETE FROM saved_search WHERE search_id='".$_REQUEST["search_id"]."'");
			}
		Displayjobsearch();
		}
		else {
			if(isset($_REQUEST["search"])) {
				if(isset($_REQUEST["save_search"]) && $_REQUEST["save_search"]!="") { ## saved search
					$FD=Array(
					"search_type" => "participant",
					"search_query" => addslashes(str_replace("&save_search=".urlencode($_REQUEST["save_search"]),"",$_SERVER["QUERY_STRING"])),
					"search_name" => PrepareData("save_search"),
					"date_created" => date("Y-m-d H:i:s")
					);
				@execute_query(MySQLInsert($FD,"saved_search"));
				}
			}
		$orderby=(isset($_REQUEST["orderby"]) && $_REQUEST["orderby"]!="")?$_REQUEST["orderby"]:"a.date_created DESC";
		Displayjobs();
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
?>