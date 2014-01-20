<?php
$error=false;
$task=(isset($_REQUEST["task"]))?$_REQUEST["task"]:"";

switch($task) {

	case "create":
		$_REQUEST["id"]=(isset($_REQUEST["id"]))?$_REQUEST["id"]:"-1";
		$Details=MySQLArray("SELECT * FROM form_submissions WHERE form_id='".$_REQUEST["id"]."'");
	DisplayPageForm($Details);
	break;
	
	case "save":
		
		if(isset($_POST["cancel_x"])) {
		RedirectTo(FCPATHNICE."?mod=".$_REQUEST["mod"]."&msg=".urlencode("cancelled"));
		exit;
		}
		
		if(isset($_POST["reply"])) {
			if(!$MISSING=CheckMandatoryFields()) {
				
				
				if(mail(PrepareData("to"),stripslashes($_POST["subject"]),stripslashes($_POST["body"]),"From: ".'Garage Sale Trail <'.$_POST["from"].'>')) {
					
					$FD=Array(
					"to" => PrepareData("to"),
					"subject" => PrepareData("subject"),
					"from" => PrepareData("from"),
					"body" => PrepareData("body"),
					"form_id" => $_REQUEST["id"],
					"date_created" => date("Y-m-d H:i:s")
					);
					mysql_query(MySQLInsert($FD,"form_replies"));
				}
			}
			else {
			$error=true;
			$errormsg="Please fill out missing fields";
			}
		}
		
		RedirectTo(FCPATHNICE."?mod=".$_REQUEST["mod"]."&msg=".urlencode("Your changes have been saved"));
		exit;
		
	break;
	
	case "download":
	
		$WHERE="";
		if(isset($_REQUEST["sess_id"]) && is_array($_REQUEST["sess_id"])) {
			$WHERE="AND";
			foreach($_REQUEST["sess_id"] as $reseller) {
			$WHERE.=" sess_id='".$reseller."' OR";
			}
		$WHERE=substr($WHERE,0,-3);
		}
		
		if(isset($_REQUEST["search"])) {
			if(isset($_REQUEST["form_name"])) {
			$WHERE.=" AND form_name = '".$_REQUEST["form_name"]."'";
			}
			if(isset($_REQUEST["season_id"])) {
			$WHERE.=" AND (";
				foreach($_REQUEST["season_id"] as $key=>$val) {
				$WHERE.="a.season_id='".$val."' OR ";
				}
			$WHERE=substr($WHERE,0,strlen($WHERE)-4).")";
			}
		}
		
		$filename="Submissions_".date("dmyHi").".xls";
		$workbook = new Spreadsheet_Excel_Writer();
		$workbook->send($filename);
		$worksheet =& $workbook->addWorksheet('Submissions');
		$Head=array(
		"date created","form_name","user_ip"
		);
		$Fields=array(
		"{date_created}","{form_name}","{user_ip}"
		);
		$x=0;
		foreach($Head as $Title) {
		$worksheet->write(0, $x, $Title);
		$x++;
		}
		$Query="SELECT * FROM form_submissions WHERE 1 $WHERE";
		$sql=mysql_query($Query);
		$y=1;
		while($row=mysql_fetch_array($sql)) {
			$x=0;
			foreach($Fields as $Field) {
			$Value=FindValue($Field,$row);
			$worksheet->write($y, $x, $Value);
			$x++;
			}
			
			$y++;$x=0;
			## now get all data for this submission
			$sql2=mysql_query("SELECT * FROM form_data WHERE form_id='".$row["form_id"]."'");
			while($row2=mysql_fetch_array($sql2)) {
			$worksheet->write($y, $x, $row2["field_name"]);
			$x++;
			}
			
			$y++;$x=0;
			## now get all data for this submission
			$sql2=mysql_query("SELECT * FROM form_data WHERE form_id='".$row["form_id"]."'");
			while($row2=mysql_fetch_array($sql2)) {
			$worksheet->write($y, $x, $row2["field_value"]);
			$x++;
			}
			
		$y++;
		}

		$workbook->close();
	
	break;
	
	default:
		if(isset($_REQUEST["v"]) && $_REQUEST["v"]=="search") {
		DisplayResellerSearch();
		}
		else {
		$orderby=(isset($_REQUEST["orderby"]) && $_REQUEST["orderby"]!="")?$_REQUEST["orderby"]:"date_created DESC";
		DisplayPages();
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