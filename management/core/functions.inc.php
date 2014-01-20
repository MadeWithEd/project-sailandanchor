<?php
/**
 **
 ** Pro:cms
 ** Revision: 4.07.2007
 ** author: Scott Dilley
 ** version: 2.0
 **
 ** Common functions
 **
*/

if (!defined('BASEPATH')) exit('No direct script access allowed');
	if(isset($CONFIG)) {
	$limit=(isset($_SESSION[$CONFIG['session']['name']]["u_results"]))?$_SESSION[$CONFIG['session']['name']]["u_results"]:10; ## MAIN DISPLAY LIMIT FOR ALL PAGES
	}
if(isset($_GET["offset"])) { $offset=$_GET["offset"]; } else { $offset=0; }
if(isset($_GET["orderby"])) {
	if(isset($_SESSION["usersession"][$_SERVER["PHP_SELF"]]["orderby"])) {
		if(isset($_SESSION["usersession"][$_SERVER["PHP_SELF"]]["orderby"]) && $_SESSION["usersession"][$_SERVER["PHP_SELF"]]["orderby"]!=$_GET["orderby"]) {
		$_SESSION["usersession"][$_SERVER["PHP_SELF"]]["orderby"]=$_GET["orderby"];
		}
	}
	else {
	$_SESSION["usersession"][$_SERVER["PHP_SELF"]]["orderby"]=$_GET["orderby"];
	}
}

####################################################################
## Function created for displaying the tabulated data for each
## main section eg pages.html
## Accepts Array of parameters
####################################################################
## $SD["table"]["width"]="695";
## $SD["table"]["border"]="0";
## $SD["table"]["cellpadding"]="0";
## $SD["table"]["cellspacing"]="0";
## $SD["table"]["class"]="0";
## $SD["header"]["class"]="RowHeader";
## $SD["header"]["divider"]="SpacerRow";
## $SD["header"]["link"]["class"]="RowHeader";
## $SD["header"]["fields"]["title"]=array(); // Row Headings
## $SD["header"]["fields"]["name"]=array(); // Cell name
## $SD["header"]["fields"]["width"]=array(); // Cell width
## $SD["header"]["fields"]["content"]=array(); // cell content. can be php code #- echo date(); -# or SQL query #s- SELECT * FROM users -s# or database var {field_name}
## $SD["query"]["select"]="select *";
## $SD["query"]["from"]="categories";
## $SD["query"]["where"]="";
## $SD["data"]["class"]="DataRow";
####################################################################

function ShowDisplayHeader($SD) {
GLOBAL $offset,$limit,$orderby,$HTTP_SESSION_VARS,$ThisPage,$HideNextPrevButtons,$Config;
$DispStatus=ShowDispStatus();
$STATUS["0"]="Draft";
$STATUS["1"]="Live";
$STATUS["2"]="Archived";

	//if($_SESSION["usersession"][$ThisPage]["orderby"]) {
	//$orderby=$_SESSION["usersession"][$ThisPage]["orderby"];
	//echo $orderby;
	//}
	
	## query the DB
	$WHERE=(isset($SD["query"]["where"]) && $SD["query"]["where"]!="")?" WHERE ".$SD["query"]["where"]:"";
	$ON=(isset($SD["query"]["on"]) && $SD["query"]["on"]!="")?" ON ".$SD["query"]["on"]:"";
	$FROM=(isset($SD["query"]["from"]) && $SD["query"]["from"]!="")?"FROM ".$SD["query"]["from"]:"";
	$ORDERBY=(isset($orderby) && $orderby!="")?"ORDER BY ".$orderby:"";
	$LIMIT=(eregi("show",$SD["query"]["select"]))?"":"LIMIT $offset,$limit";

$Res=@mysql_num_rows(mysql_query($SD["query"]["select"]." ".$FROM." ".$ON." ". $WHERE));

	## Start table
	$HTML='<table border="'.$SD["table"]["border"].'" width="'.$SD["table"]["width"].'" cellpadding="'.$SD["table"]["cellpadding"].'" cellspacing="'.$SD["table"]["cellspacing"].'">'."\n";
	$HTML.='<tr>'."\n";
	
		for($x=0;$x<count($SD["header"]["fields"]["title"]);$x++) {
		$HTML.='<td class="'.$SD["header"]["class"].'" width="'.$SD["header"]["fields"]["width"][$x].'">';
		
			if($SD["header"]["fields"]["name"][$x]!="") {
				if(ereg("orderby",$_SERVER["QUERY_STRING"])) {
				$QSorderby=ereg_replace("orderby=([^&]*)","orderby=".$SD["header"]["fields"]["name"][$x].$DispStatus,$_SERVER["QUERY_STRING"]);
				}
				else {
				$QSorderby=$_SERVER["QUERY_STRING"]."&orderby=".$SD["header"]["fields"]["name"][$x].$DispStatus;
				}
			$HTML.='<a href="'.$ThisPage.'?'.$QSorderby.'" class="'.$SD["header"]["link"]["class"].'">';
			}
			
			$HTML.=$SD["header"]["fields"]["title"][$x];
			if($SD["header"]["fields"]["name"][$x]!="") {
			$HTML.=ShowDispOrder($SD["header"]["fields"]["name"][$x]);
			}
			
			if($SD["header"]["fields"]["name"][$x]!="") {
			$HTML.='</a>';
			}
		
		$HTML.='</td>'."\n";
		}
	
	$HTML.='</tr>'."\n";
	
	$HTML.='<tr>'."\n";
	$HTML.='<td class="'.$SD["header"]["divider"].'" colspan="'.count($SD["header"]["fields"]["title"]).'"></td>'."\n";
	$HTML.='</tr>'."\n";
	
	$SQL=$SD["query"]["select"]." ".$FROM." ".$ON." ".$WHERE." ".$ORDERBY." ".$LIMIT;
	if(!$QUERY=mysql_query($SQL)) {
	echo @mysql_error();
	echo '<br /><br />'.$SQL;
	die;
	}
	
	$ClassCount=0;
	$DataCount=0;
	while($ROW=@mysql_fetch_array($QUERY)) {
	$HTML.='<tr>'."\n";
	$TableName=$ROW[0];
	
		for($x=0;$x<count($SD["header"]["fields"]["title"]);$x++) {
		$ClassArr=explode(",",$SD["data"]["class"]);
			$ColAlign=(isset($SD["header"]["fields"]["align"][$x]))?$SD["header"]["fields"]["align"][$x]:"left";
			
			if(count($ClassArr)>1) {
				if($ClassCount==count($ClassArr)) {
				$ClassCount=0;
				}
			$HTML.='<td class="'.$ClassArr[$ClassCount].'" align="'.$ColAlign.'">';
			}
			else {
			$HTML.='<td class="'.$SD["data"]["class"].'" align="'.$ColAlign.'">';
			}
		
			$Content=$SD["header"]["fields"]["content"][$x];
			
			## first, look for escaped $vars shown as {&var&} to translate
			preg_match_all("/\{&([a-zA-Z0-9_-]*)&\}/i",$Content,$v);
				foreach($v[1] as $vr) {
				$Content=ereg_replace("\{&".$vr."&\}",$ROW[$vr],$Content);
				}
			
			## first, look for $vars shown as {var} to translate
			preg_match_all("/\{([a-zA-Z0-9_-]*)\}/i",$Content,$v);
				foreach($v[1] as $vr) {
				$Content=ereg_replace("\{".$vr."\}",stripslashes($ROW[$vr]),$Content);
				}
				
			## now look for code to execute shown as #- code -#
			preg_match_all("/\#-(.*)-\#/",$Content,$c);
				foreach($c[1] as $cd) {			
				
				//echo $cd."<br>";
				ob_start();
				$cde=eval($cd);
				$code = ob_get_contents();
				ob_end_clean();
				//echo $code;
				//echo "<br>".$cd;
				//$cd=ereg_replace("\$","\\$",$cd);
				$Content=str_replace("#-".$cd."-#",$code,$Content);
				}
				
			## now look for <sql> statements
			preg_match_all("/\#s-(.*)-s\#/",$Content,$s);
				foreach($s[1] as $sl) {
				$result=@mysql_result(mysql_query($sl),0);
				$Content=str_replace("#s-".$sl."-s#",$result,$Content);
				$DATA[$x]=$result;
				}			
			
			$HTML.=$Content;			
			
		$HTML.='</td>'."\n";
		}		
	$HTML.='</tr>'."\n";
	
	$HTML.='<tr>'."\n";
	$HTML.='<td class="'.$SD["header"]["divider"].'" colspan="'.count($SD["header"]["fields"]["title"]).'"></td>'."\n";
	$HTML.='</tr>'."\n";
	$ClassCount++;
	$DataCount++;
	}
	mysql_free_result($QUERY);

	$HTML.='</table>'."\n";
	
	## Look for Buttons
	if(@is_array($SD["extra"]["buttons"])) {
	$HTML.='<div id="ButtonLayer"><br />';
	
		for($a=0;$a<count($SD["extra"]["buttons"]["label"]);$a++) {
		$HTML.='<div class="Btn'.$SD["extra"]["buttons"]["class"][$a].'" id="'.$SD["extra"]["buttons"]["id"][$a].'" onclick="'.$SD["extra"]["buttons"]["onclick"][$a].'">'.$SD["extra"]["buttons"]["label"][$a].'</div>';
		}
	
	$HTML.='</div>'."\n";
	}
	
	if($LIMIT!="" && !isset($HideNextPrevButtons)) {
	## Next/Prev Links	
	$HTML.='<table style="clear:both;" border="'.$SD["table"]["border"].'" width="'.$SD["table"]["width"].'" cellpadding="'.$SD["table"]["cellpadding"].'" cellspacing="'.$SD["table"]["cellspacing"].'">'."\n";
	$HTML.='<tr>'."\n";
	
		if($offset > 0) {
		$HTML.='<td align="left"><br /><input type="button" value="Back" onclick="document.location.href=\''.$ThisPage.'?'.str_replace("&offset=$offset","",$_SERVER["QUERY_STRING"]).'&offset='.($offset - $limit).'\';" /></td>'."\n";
		}
	
		if($Res > ($offset + $limit)) {
		$HTML.='<td align="right"><br /><input type="button" value="Next" onclick="document.location.href=\''.$ThisPage.'?'.str_replace("&offset=$offset","",$_SERVER["QUERY_STRING"]).'&offset='.($offset + $limit).'\';" /></td>'."\n";
		}
		
	$HTML.='</tr>'."\n";
	$HTML.='</table>'."\n";
	}
	
	return $HTML;
}



function ShowDispStatus() {
GLOBAL $orderby;
	if(eregi("DESC",$orderby)) {
	$Status="+ASC";
	}
	else {
	$Status="+DESC";
	}
return $Status;
}

function ShowDispOrder($FieldName) {
GLOBAL $orderby;

	if(@eregi($FieldName,$orderby)) {
		if(@eregi("DESC",$orderby)) {
		$Html='<img src="templates/default/images/arrow_up.gif" border="0" alt="" width="9" height="9"  hspace="5" />';
		}
		else {
		$Html='<img src="templates/default/images/arrow_dn.gif" border="0" alt="" width="9" height="9"  hspace="5" />';
		}
	}
	else {
	$Html='<img src="templates/default/images/spacer.gif" border="0" width="9" height="9" alt=""  hspace="5" />';
	}
return $Html;
}

function CreateFileName($Name) {
	if($Name != "") {
	$Name=ereg_replace("[^a-zA-Z0-9._]","-",$Name);
	}
return strtolower($Name);
}

########################################################
## Handles selected="selected" on all SELECT fields
########################################################

function ShowSelected($needle,$haystack,$data) {
GLOBAL $HTTP_POST_VARS,$HTTP_GET_VARS;
	if(@is_array($_POST[$haystack]) || ereg(",",$data[$haystack])) {
		if(@in_array($needle,$_POST[$haystack])) {
		return " selected=\"selected\"";
		}
		else {
			$Data=explode(",",$data[$haystack]);
			if(@in_array($needle,$Data)) {
			return " selected=\"selected\"";
			}
		}
	
	}
	else {
		if(isset($_POST[$haystack]) && $_POST[$haystack]==$needle) {
		return " selected=\"selected\"";
		}
		else {
			if($data[$haystack]==$needle) {
			return " selected=\"selected\"";
			}
		}
	}
}

function ShowSelectedTime($needle,$haystack,$data) {
GLOBAL $HTTP_POST_VARS,$HTTP_GET_VARS;
	if(@is_array($_POST[$haystack]) || ereg(",",$data[$haystack])) {
		if(@in_array($needle,$_POST[$haystack])) {
		return " selected=\"selected\"";
		}
		else {
			$Data=explode(",",$data[$haystack]);
			if(@in_array($needle,$Data)) {
			return " selected=\"selected\"";
			}
		}
	
	}
	else {
		if(isset($_POST[$haystack]) && $_POST[$haystack]==substr($needle,0,5)) {
		return " selected=\"selected\"";
		}
		else {
			if(isset($data[$haystack]) && $data[$haystack]==$needle) {
			return " selected=\"selected\"";
			}
		}
	}
}
########################################################
## Handles checked="checked" on all Checkbox and Radio
## fields
########################################################

function ShowChecked($needle,$haystack,$data,$defaultchecked='') {
GLOBAL $HTTP_POST_VARS;
	if(@is_array($_POST[$haystack]) || ereg(",",$data[$haystack])) {
		if(@in_array($needle,$_POST[$haystack])) {
		return " checked=\"checked\"";
		}
		else {
			$Data=explode(",",$data[$haystack]);
			if(@in_array($needle,$Data)) {
			return " checked=\"checked\"";
			}
		}
	
	}
	else {
		if(isset($_POST[$haystack]) && $_POST[$haystack]==$needle) {
		return " checked=\"checked\"";
		}
		else {
			if(isset($data[$haystack]) && $data[$haystack]==$needle) {
			return " checked=\"checked\"";
			}
			else {
				if($defaultchecked=="Y") {
				return " checked=\"checked\"";
				}
			}
		}
	}
}

########################################################
## Grabs Posted data or $Details if available
########################################################

function ShowDataText($Val,$Arr='') {
GLOBAL $HTTP_POST_VARS, $Details;
$Value="";
	$Araay=($Arr!="")?$Arr:$Details;
	if(ereg("\[",$Val)) { ## Is array
	preg_match_all("/\[([0-9 ]*)\]/i",$Val,$v);
		foreach($v[1] as $vr) {
		$Araay2=ereg_replace("\[".$vr."\]","",$Val);
			if(isset($_REQUEST[$Araay2][$vr]) && $_REQUEST[$Araay2][$vr]!="") {
			$Value=trim(addslashes($_REQUEST[$Araay2][$vr]));
			}
			else {
				if(@is_array($Araay[$Araay2])) {
					if(isset($Araay[$Araay2][$vr])) {
					$Value=stripslashes($Araay[$Araay2][$vr]);
					}
				}
				else {
					if(isset($Arr[$Araay2])) {
					$Value=stripslashes($Arr[$Araay2]);
					}
				}			
			}
		}
		
		//print_r($Araay);
	}
	else {
		if(ereg(",",$Val)) {
			$Vals=explode(",",$Val);
			foreach($Vals as $k=>$d) {
				if(isset($_REQUEST[$d]) && $_REQUEST[$d] !="") {
				$Value=stripslashes($_REQUEST[$d]);
				}
				elseif(isset($Araay[$d])) {
				$Value=stripslashes($Araay[$d]);
				}
			}
		}
		else {
			if(isset($_REQUEST[$Val]) && $_REQUEST[$Val] !="") {
			$Value=stripslashes($_REQUEST[$Val]);
			}
			elseif(isset($Araay[$Val])) {
			$Value=stripslashes($Araay[$Val]);
			}
		}
	}
	//$Value=htmlentities($Value, ENT_QUOTES);
return $Value;
}

########################################################
## Form handling stuff
########################################################

function PrepareData($Val) {
GLOBAL $_REQUEST;
	if(ereg("\[",$Val)) { ## Is array
	preg_match_all("/\[([0-9 ]*)\]/i",$Val,$v);
		foreach($v[1] as $vr) {
		$Araay=ereg_replace("\[".$vr."\]","",$Val);
		$Value=trim(addslashes($_REQUEST[$Araay][$vr]));
		}	
	}
	else {
	$Value=(isset($_REQUEST[$Val]))?trim(addslashes($_REQUEST[$Val])):'';
	}
return $Value;
}


function PrepareDataEnum($Val,$defaultvalue='') {
GLOBAL $_REQUEST;
	if(ereg("\[",$Val)) { ## Is array
	preg_match_all("/\[([0-9 ]*)\]/i",$Val,$v);
		foreach($v[1] as $vr) {
		$Araay=ereg_replace("\[".$vr."\]","",$Val);
		$Value=trim(addslashes($_REQUEST[$Araay][$vr]));
			if($Value=="") {
				if($defaultvalue!="") {
				$Value=$defaultvalue;
				}
				else {
				$Value="N";
				}
			}
		}	
	}
	else {
		if(isset($_REQUEST[$Val]) && $_REQUEST[$Val]!="") {
		$Value=$_REQUEST[$Val];
		}
		else {
			if($defaultvalue!="") {
			$Value=$defaultvalue;
			}
			else {
			$Value="N";
			}
		}
	}
return $Value;
}

function PrepareDataInt($Val) {
GLOBAL $_REQUEST;
	$Value=trim(str_replace(",","",$_REQUEST[$Val]));
return $Value;
}

function PrepareDataTime($Val) {
GLOBAL $_REQUEST;
	if($_REQUEST[$Val."_ampm"]=="pm") { ## add 12
	//echo $_REQUEST[$Val];
	//exit;
	$Value=(($_REQUEST[$Val][0].$_REQUEST[$Val][1]) + 12).":".$_REQUEST[$Val][3].$_REQUEST[$Val][4].":00";
	}
	else {
	$Value=$_REQUEST[$Val];
	}
return $Value;
}

function needs_zeroOLD($Val) {
	if($Val < 10) {
	$Value="0".$Val;
	}
	else {
	$Value=$Val;
	}
return $Value;
}

function needs_zero($Val) {
	if($Val < 10 && strlen($Val)==1) {
	$Value="0".$Val;
	}
	else {
	$Value=$Val;
	}
return $Value;
}

function PrepareDataRegi($Val, $tp) {
   if($tp == "reg"){
	if(strlen($_REQUEST[$Val])>0) {
	$Value="Y";
	}
	else {
	$Value="N";
	}
   }else{
   	if(strlen($_REQUEST[$Val])>0) {
	$Value="1";
	}
	else {
	$Value="3";
	}
   }
return $Value;
}

########################################################
## Function to handle SQL UPDATES/ INSERTS
########################################################

function MySQLUpdate($Input,$Table,$PrimaryKey,$PrimaryVal) {
$UpdateSQL="";
	foreach($Input as $key=>$val) {
		if(!ereg("\{insert\}",$key)) {
		$UpdateSQL.="`".str_replace("{update}","",$key)."`='".$val."',";
		}
	}
	
	$UpdateSQL=substr($UpdateSQL,0,strlen($UpdateSQL)-1);
	$SQL="UPDATE `".$Table."` SET ".$UpdateSQL." WHERE `".$PrimaryKey."`='".$PrimaryVal."'";

return $SQL;
}

function MySQLInsert($Input,$Table) {
$InsertFields="";$InsertVals="";
	foreach($Input as $key=>$val) {
		if(!ereg("\{update\}",$key)) {
		$InsertFields.="`".str_replace("{insert}","",$key)."`,";
		$InsertVals.="'".$val."',";
		}
	}
	
	$InsertFields=substr($InsertFields,0,strlen($InsertFields)-1);
	$InsertVals=substr($InsertVals,0,strlen($InsertVals)-1);
	$SQL="INSERT INTO `".$Table."` (".$InsertFields.") VALUES (".$InsertVals.")";

return $SQL;
}

function MySQLResult($SQL) {
	if(!$Value=@mysql_result(mysql_query($SQL),0)) {
	$Value="0";
	}
return $Value;
}

function MySQLArray($SQL) {
	if(!$Data=@mysql_fetch_array(mysql_query($SQL))) {
	$Data=0;
	}
return $Data;
}

function MySQLDelete($Table,$PrimaryKey,$PrimaryVal) {
	$SQL="DELETE FROM `".$Table."` WHERE `".$PrimaryKey."`='".$PrimaryVal."'";
return $SQL;
}

########################################################
## Shows mandatory asterisk, either black or red
## if found missing
########################################################

function ShowMandatory($FieldName) {
GLOBAL $MISSING;
$err=false;

	if(is_array($FieldName)) {
		foreach($FieldName as $Field) {
			if($MISSING[$Field]) {
			$err=true;
			}
		}
	}
	else {
		if(isset($MISSING)) {
			if(isset($MISSING[$FieldName])) {
			$err=true;
			}			
		}
	}
	if($err==true) {
	$HTML="<font color=\"red\">*</font>";
	}
	else  {
	$HTML="*";
	}
return $HTML;
}

function PopulateSelect($Table,$IDField,$ValueField,$Selected="",$Where="") {
$IDExtra="";$HTML="";
	$WHERE=($Where!="")?" WHERE ".$Where:"";
	## Split up values if comma separated
	$ValueArray=@explode(",",$ValueField);
	if(count($ValueArray) > 1) {
	$ValueFields="CONCAT(".$ValueField.") as dField";
	$ValueField="dField";
	}
	else {
	$ValueFields=$ValueField;
	}
	
	if(ereg("\.",$IDField)) {
	$IDFieldRef=substr($IDField,(strpos($IDField, ".")+1),strlen($IDField));
	}
	else {
		if(ereg("\{",$IDField)) { ## extract bracketed value
		preg_match_all("/\{([a-zA-Z_0-9\-]*)\}/i",$IDField,$v);
			foreach($v[1] as $vr) {
			$IDFieldParse=$vr;
			$IDField=ereg_replace("\{".$vr."\}","",$IDField);
			}
		}
		if(ereg("\[",$IDField)) { ## extract bracketed value
		preg_match_all("/\[([a-zA-Z_0-9\- ]*)\]/i",$IDField,$v);
			foreach($v[1] as $vr) {
			$IDExtra=$vr;
			$IDField=ereg_replace("\[".$vr."\]","",$IDField);
			}
		}
		$IDFieldRef=$IDField;
		
	}
	
	//echo "SELECT $IDField,$ValueFields FROM $Table $WHERE";
	$SQL=mysql_query("SELECT $IDField,$ValueFields FROM $Table $WHERE");
	while($ROW=mysql_fetch_array($SQL)) {
		if(isset($IDFieldParse)) {
		$IDExtra=$ROW[$IDFieldParse].$IDExtra;
		}
	$HTML.='<option value="'.$IDExtra.$ROW[$IDFieldRef].'"';
		if($Selected!="") {
			if(ereg(",",$Selected)) { ## multi-select
				$SelectedArr=explode(",",$Selected);
				if(@in_array($ROW[$IDField],$SelectedArr)) {
				$HTML.=' selected="selected"';
				}
			}
			else {
				if($ROW[$IDField]==$Selected) {
				$HTML.=' selected="selected"';
				}
			}
		}
	$HTML.='>'.stripslashes(substr($ROW[$ValueField],0,100)).'</option>'."\n";
	}
return $HTML;
}

function PopulateSelectState($States,$Selected="") {
	if(is_array($States)) {
	$HTML='';	
		foreach($States as $key=>$val) {
		$HTML.='<option value="'.$val.'"';
			if($Selected==$val) {
			$HTML.=' selected="selected"';
			}
		$HTML.='>'.$val.'</option>';
		}
	return $HTML;
	}
}
function PopulateSelectGeneric($ids,$names,$Selected="") {
	if(is_array($ids)) {
	$HTML='';	
		for($i=0; $i< count($ids); $i++) {
		$HTML.='<option value="'.$ids[$i].'"';
			if($Selected==$ids[$i]) {
			$HTML.=' selected="selected"';
			}
		$HTML.='>'.$names[$i].'</option>';
		}
	return $HTML;
	}
}

function PopulateList($Table,$IDField,$ValueField,$Selected="",$Where="",$Type="") {
$IDExtra="";$HTML="";
	$WHERE=($Where!="")?" WHERE ".$Where:"";
	## Split up values if comma separated
	$ValueArray=@explode(",",$ValueField);
	if(count($ValueArray) > 1) {
	$ValueFields="CONCAT(".$ValueField.") as dField";
	$ValueField="dField";
	}
	else {
	$ValueFields=$ValueField;
	}
	
	if(ereg("\.",$IDField)) {
	$IDFieldRef=substr($IDField,(strpos($IDField, ".")+1),strlen($IDField));
	}
	else {
		if(ereg("\{",$IDField)) { ## extract bracketed value
		preg_match_all("/\{([a-zA-Z_0-9\-]*)\}/i",$IDField,$v);
			foreach($v[1] as $vr) {
			$IDFieldParse=$vr;
			$IDField=ereg_replace("\{".$vr."\}","",$IDField);
			}
		}
		if(ereg("\[",$IDField)) { ## extract bracketed value
		preg_match_all("/\[([a-zA-Z_0-9\- ]*)\]/i",$IDField,$v);
			foreach($v[1] as $vr) {
			$IDExtra=$vr;
			$IDField=ereg_replace("\[".$vr."\]","",$IDField);
			}
		}
		$IDFieldRef=$IDField;
		
	}
	
	$SQL=mysql_query("SELECT $IDField,$ValueFields FROM $Table $WHERE");
	if(@mysql_num_rows($SQL)>0) {
	$HTML= ($Type=="ol")?"<ol>":"<ul>";
	}
	while($ROW=mysql_fetch_array($SQL)) {
		if(isset($IDFieldParse)) {
		$IDExtra=$ROW[$IDFieldParse].$IDExtra;
		}
	$HTML.='<li';
		if($Selected!="") {
			if(ereg(",",$Selected)) { ## multi-select
				$SelectedArr=explode(",",$Selected);
				if(@in_array($ROW[$IDField],$SelectedArr)) {
				$HTML.=' class="selected"';
				}
			}
			else {
				if($ROW[$IDField]==$Selected) {
				$HTML.=' class="selected"';
				}
			}
		}
	$HTML.='>'.stripslashes(substr($ROW[$ValueField],0,100)).'</li>'."\n";
	}
	if(@mysql_num_rows($SQL)>0) {
	$HTML.= ($Type=="ol")?"</ol>":"</ul>";
	}
return $HTML;
}



function DaysOfMonthThingy($Day) {
	$DaysOfMonthThingy=Array(" ","st","nd","rd","th","th","th","th",
	"th","th","th","th","th","th","th","th","th","th","th","th",
	"th","st","nd","rd","th","th","th","th","th"
	);
return $DaysOfMonthThingy[$Day];
}

function CheckIfFileExists($File,$Table,$FieldName) {
	if($File!="") {
		$Exists=@mysql_result(mysql_query("SELECT $FieldName FROM $Table WHERE $Fieldname='".$File."'"),0);
		if($Exists) {
		$File=$Exists."_2";
		}
	}
return $File;
}

##########################################################
## Creates a thumbnail from given image
##########################################################

function CreateThumbNail($Src,$SaveTo,$Width,$Height) {
	
	$Str=explode(".",$SaveTo);
	$TmpFile=ereg_replace(".".$Str[count($Str)-1],"",$SaveTo)."TMP.".$Str[count($Str)-1];
	copy($Src,$TmpFile);
	$Inf=@getimagesize($TmpFile);
	$thumb=new thumbnail($TmpFile);

	if($Width > 0 && $Height > 0) {
	$thumb->size_both($Width,$Height);
	}
	elseif($Width > 0 && $Height < 1) {
	$thumb->size_width($Width);
	}
	elseif($Width < 1 && $Height > 0) {
	$thumb->size_height($Height);
	}
	$thumb->jpeg_quality(100);
	$thumb->save($SaveTo);
	
	//@copy($Src,$SaveTo);
}

########################################################
## Returns all URLs from within content and does a 
## search and replace to make them clickable links.
########################################################

function FindUrlsHTML($content) {	
	$urls = '(http|www|ftp)';
	$ltrs = '\w';
	$gunk = '/#~;:.?+=&%@!\-{}\[\]\.';
	$punc = '.:?\-';
	$any = "$ltrs$gunk$punc";
	preg_match_all("{\b$urls:[$any]+?(?=[$punc]*[^$any]|$)}x", $content, $matches);
	
	foreach ($matches[0] as $u) {
		//if(!eregi(".gif",$u) && !eregi(".jpg",$u) && !eregi(".jpeg",$u) && !eregi(".css",$u) && !eregi(".js",$u)) {	
		## look for hash
		$pos=strpos($u,"#");
			if($pos===false || $pos !="0") {
			$href=(!eregi("http",$u)) ? "http://".$u : $u;
			$content=str_replace($u,'<a href="'.$href.'">'.$u.'</a>',$content);
			}    	
		//}
	}
return $content;
}

#########################################################
## Displays the main page content 
##
#########################################################

function DisplayPageContent($Content) {
	if($Content) {
	$TmpContent='<?php
		if($_REQUEST["kw"]) {
		echo nl2br(stripslashes(eregi_replace($_REQUEST["kw"],"<span class=\"kw\">".$_REQUEST["kw"]."</span>","'.$Content.'")));
		}
		else {
		?>
		'.nl2br(stripslashes($Content)).'
		<?php	
		}
	?>';
	}
return $TmpContent;
}

function HighlightMandatory($FieldName) {
GLOBAL $MISSING;
$err=false;

	if(is_array($FieldName)) {
		foreach($FieldName as $Field) {
			if($MISSING[$Field]) {
			$err=true;
			}
		}
	}
	else {
		if(isset($MISSING[$FieldName])) {
			if($MISSING[$FieldName]) {
			$err=true;
			}			
		}
	}
	if($err==true) {
	$HTML=" style=\"background-color:#FCEA7B;color:red;border-color:#666666;border-width:1px;border-style: solid;\"";
	}
	else  {
	$HTML="";
	}
return $HTML;
}

function dateDiff($dformat, $endDate, $beginDate) {
$date_parts1=explode($dformat, $beginDate);
$date_parts2=explode($dformat, $endDate);
$start_date=gregoriantojd($date_parts1[0], $date_parts1[1], $date_parts1[2]);
$end_date=gregoriantojd($date_parts2[0], $date_parts2[1], $date_parts2[2]);
return $end_date - $start_date;
}

############################################################
##
##
############################################################

function DisplaySubModules($ThePage) {
GLOBAL $ThisPage,$UserAccess;
	$PageArray=explode("/",str_replace(".create","",$ThePage));
	$Page=$PageArray[count($PageArray)-1];
	$ModID=MySQLResult("SELECT mod_id FROM CMS_modules WHERE mod_url='".$Page."' AND sub_id='0'");
	if($ModID>0) {
		$HasSubs=MySQLResult("SELECT count(*) FROM CMS_modules WHERE sub_id='".$ModID."' AND is_active='Y'");
		if($HasSubs>0) {
		$x=1;
		$HTML.='<table border="0" cellpadding="0" cellspacing="0">
			<tr>
			<td background="images/bg_subnav_blank.gif"><img src="images/spacer.gif" border="0" width="3" height="25" alt="" /></td>
			<td width="1"><img src="images/subnav_left.gif" border="0" width="1" height="25" alt="" /></td>';
				$sql=mysql_query("SELECT * FROM CMS_modules WHERE sub_id='".$ModID."' AND is_active='Y' ORDER BY sort_order");
				while($row=mysql_fetch_array($sql)) {
					if(@in_array($mrow["mod_id"],$UserAccess)) {
					$HTML.='<td width="'.$row["mod_width"].'" align="center" background="images/bg_subnav';
						if(ereg($row["mod_url"],$ThisPage) || ereg(str_replace(".html","",$row["mod_url"].".create"),$ThisPage)) {
						$HTML.='_on';
						}
					$HTML.='.gif" class="Nav"><a href="'.$row["mod_url"].'" class="Nav">'.stripslashes($row["mod_name"]).'</a></td>'."\n";
						if($x!=$HasSubs) {
						$HTML.='<td width="3" background="images/bg_subnav.gif"><img src="images/nav_right.gif" border="0" width="3" height="25" alt="" /></td>'."\n";
						}
					$x++;
					}
				}
				if($x>1) {
				$HTML.='<td width="3" background="images/bg_subnav.gif"><img src="images/subnav_right.gif" border="0" width="3" height="25" alt="" /></td>';
				}
			$HTML.='
			</tr>
			</table>';
		}
	}
return $HTML;
}

function CheckMandatoryFields($Mandatories='') {
GLOBAL $HTTP_POST_VARS;
	$Mandatories=(isset($Mandatories) && $Mandatories!="")?$_POST[$Mandatories]:$_POST["mandatory"];
	if(isset($Mandatories)) {
	$Fields=explode(",",$Mandatories);
		for($x=0;$x<count($Fields);$x++)  {
			if(ereg("{email}",$Fields[$x])) {
				$Data=str_replace("{email}","",$Fields[$x]);
				if(!eregi("^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$",PrepareData($Data)) || PrepareData($Data)=="") {
				$MISSING[$Data]=true;
				}
			}
			elseif(ereg("{phone}",$Fields[$x])) {
				$Data=str_replace("{phone}","",$Fields[$x]);
				if(eregi("[a-zA-Z]", PrepareData($Data)) || PrepareData($Data)=="") {
				$MISSING[$Data]=true;
				}
			}
			elseif(ereg("{number}",$Fields[$x])) {
				$Data=str_replace("{number}","",$Fields[$x]);
				if(eregi("[a-zA-Z]", PrepareData($Data)) || PrepareData($Data)=="") {
				$MISSING[$Data]=true;
				}
			}
			elseif(ereg("{amount}",$Fields[$x])) {
				$Data=str_replace("{amount}","",$Fields[$x]);
				if(eregi("[^0-9\.\$]", PrepareData($Data)) || PrepareData($Data)=="") {
				$MISSING[$Data]=true;
				}
			}
			elseif(ereg("{stringint}",$Fields[$x])) {
				$Data=str_replace("{stringint}","",$Fields[$x]);
				if(ereg("[^a-zA-Z0-9]", PrepareData($Data)) || PrepareData($Data)=="") {
				$MISSING[$Data]=true;
				}
			}
			elseif(ereg("{!=",$Fields[$x])) { ## field{!=$value}
				$Value=substr($Fields[$x],strpos($Fields[$x],"=")+1,-1);
				$Field=str_replace("{!=".$Value."}","",$Fields[$x]);
				if(PrepareData($Field)==$Value) {
				$MISSING[$Field]=true;
				}
			}
			elseif(ereg("{=",$Fields[$x])) { ## field1{=field2}
				$Field1=substr($Fields[$x],strpos($Fields[$x],"=")+1,strrpos($Fields[$x],"}")-1);
				$Field2=str_replace("{=".$Field1."}","",$Fields[$x]);
				if(PrepareData($Field1) != PrepareData($Field2)) {
				$MISSING[$Field2]=true;
				}
			}
			elseif(ereg("\[\]",$Fields[$x])) { ## multiple select
			
			}
			elseif(ereg("\(",$Fields[$x])) { ## grouping of checkboxes
			$Data=str_replace(array(")","("),array("",""),$Fields[$x]);
				$SubData=explode("|",$Data);
				if(count($SubData)>1) {
				$NotSelected=true;
					foreach($SubData as $key) {
						$ElNum=ereg_replace("[^0-9]","",$key);
						$El=ereg_replace("\[[0-9]*\]","",$key);
						if($_POST[$El][$ElNum]!="") {
						$NotSelected=false;
						}
					}
				}
				if($NotSelected==true) {
				$MISSING[$SubData[0]]=true;
				}
			}
			else {
				$Data=$Fields[$x];
				if(isset($_FILES[$Fields[$x]])) {
					if($_FILES[$Fields[$x]]=="") {
					$MISSING[$Fields[$x]]=true;
					}
				}
				if(strlen(PrepareData($Data)) < 1) {
				$MISSING[$Data]=true;
				}
			}
		}
		if(isset($MISSING)) {
		return $MISSING;
		}
		else {
		return false;
		}
	}
}

function CheckMandatoryFieldsOLD() {
GLOBAL $HTTP_POST_VARS;
	if(isset($_POST["mandatory"])) {
	$Fields=explode(",",$_POST["mandatory"]);
		for($x=0;$x<count($Fields);$x++)  {
			if(ereg("{email}",$Fields[$x])) {
				$Data=str_replace("{email}","",$Fields[$x]);
				if(!eregi("^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$",$_POST[$Data]) || $_POST[$Data]=="") {
				$MISSING[$Data]=true;
				}
			}
			elseif(ereg("{number}",$Fields[$x])) {
				$Data=str_replace("{number}","",$Fields[$x]);
				if(eregi("[a-zA-Z]", $_POST[$Data]) || $_POST[$Data]=="") {
				$MISSING[$Data]=true;
				}
			}
			elseif(ereg("{amount}",$Fields[$x])) {
				$Data=str_replace("{amount}","",$Fields[$x]);
				if(eregi("[^0-9\.\$]", $_POST[$Data]) || $_POST[$Data]=="") {
				$MISSING[$Data]=true;
				}
			}
			elseif(ereg("\[\]",$Fields[$x])) { ## multiple select
			
			}
			else {
				if(isset($_FILES[$Fields[$x]])) {
					if($_FILES[$Fields[$x]]=="") {
					$MISSING[$Fields[$x]]=true;
					}
				}
				if(isset($_POST[$Fields[$x]])) {
					if($_POST[$Fields[$x]]=="") {
					$MISSING[$Fields[$x]]=true;
					}
				}
			}
		}
		if(isset($MISSING)) {
		return $MISSING;
		}
		else {
		return false;
		}
	}
}

#################################################################
## SETUP FUNCTIONS
##
#################################################################

function DisplayError() {
GLOBAL $MISSING,$error,$errormsg;
$HTML="";
	if($errormsg!="") {
	$HTML='<div class="TextError">'.$errormsg.'</div>';
	}
	else {
		if($error!="") {
		$HTML='<div class="TextError">'.$error.'</div>';
		}
		else {
			if($MISSING!="") {
			$HTML='<div class="TextError">Please fill in the fields highlighted</div>';
			}
		}
	}
return $HTML;
}

function split_sql($sql) {
	$sql = trim($sql);
	$sql = ereg_replace("\n#[^\n]*\n", "\n", $sql);

	$buffer = array();
	$ret = array();
	$in_string = false;

	for($i=0; $i<strlen($sql)-1; $i++) {
		if($sql[$i] == ";" && !$in_string) {
			$ret[] = substr($sql, 0, $i);
			$sql = substr($sql, $i + 1);
			$i = 0;
		}

		if($in_string && ($sql[$i] == $in_string) && $buffer[1] != "\\") {
			$in_string = false;
		}
		elseif(!$in_string && ($sql[$i] == '"' || $sql[$i] == "'") && (!isset($buffer[0]) || $buffer[0] != "\\")) {
			$in_string = $sql[$i];
		}
		if(isset($buffer[1])) {
			$buffer[0] = $buffer[1];
		}
		$buffer[1] = $sql[$i];
	}

	if(!empty($sql)) {
		$ret[] = $sql;
	}
	return($ret);
}

function ExecuteSQLFile($SQL) {
	$error=false;
	$pieces  = split_sql($SQL);
	for ($i=0; $i<count($pieces); $i++) {
		$pieces[$i] = trim($pieces[$i]);
		if(!empty($pieces[$i]) && $pieces[$i] != "#") {
			if (!@mysql_query($pieces[$i])) {
			$error=@mysql_error();
			}
		}
	}
return $error;
}

function CheckMainTables($DB) {
	$error=false;
	$notables=false;
	$MandTables=array('CMS_users','CMS_usertypes','CMS_logins','CMS_modules','CMS_setup');
	$sql = "SHOW TABLES";
	$result = mysql_query($sql);
	//if(mysql_num_rows($result) > 0) {
	//$notables=false;
	//}
	//if($notables==false) { ## check anyway
		while($row=@mysql_fetch_row($sql)) {
			if(!in_array($row[0],$MandTables)) {
			$notables=true;
			}
		}
	//}
	if($notables==true) {
		$query = fread( fopen(BASEPATH.'database.sql', 'r' ), filesize( BASEPATH.'database.sql' ) );
		$pieces  = split_sql($query);
		for ($i=0; $i<count($pieces); $i++) {
			$pieces[$i] = trim($pieces[$i]);
			if(!empty($pieces[$i]) && $pieces[$i] != "#") {
				if (!mysql_query($pieces[$i])) {
				$error=mysql_error();
				}
			}
		}
	}
return $error;
}

function ShowCookieTrail() {
GLOBAL $mod,$sub;
	$HTML="";
	if(isset($_REQUEST["mod"]) && $_REQUEST["mod"]!="") {
		if($_REQUEST["mod"]=="profile") {
		$mod_name="Profile";
		}
		elseif($_REQUEST["mod"]=="setup") {
		$mod_name="Setup";
		}
		else {
		$mod_name=MySQLResult("SELECT mod_name FROM CMS_modules WHERE mod_short_name='".$_REQUEST["mod"]."'");
		}
	$HTML='<li><a href="'.FCPATHNICE.'?mod='.$_REQUEST["mod"].'">'.$mod_name.'</a></li>';
	}
	if(isset($_REQUEST["sub"]) && $_REQUEST["sub"]!="") {
	$HTML.='<li><a href="'.FCPATHNICE.'?mod='.$_REQUEST["mod"].'&sub='.$_REQUEST["sub"].'">'.ucfirst($_REQUEST["sub"]).'</a></li>';
	}
return $HTML;
}

function ShowTopModules() {
GLOBAL $mod,$sub,$task,$UserAccess;
	if(isset($_REQUEST["mod"])) {
		echo '<span class="TopModuleButton"><a href="?"><img src="'.TEMPLATEPATHNICE.'images/ico_home.png" border="0" alt="" vspace="0" width="26" /></a></span>';
		$x=1;
		$msql=@mysql_query("SELECT * FROM CMS_modules ORDER BY sort_order ASC");
		while($mrow=@mysql_fetch_array($msql)) {
			if(@in_array($mrow["mod_id"],$UserAccess)) {
			echo '<span class="TopModuleButton"><a href="?mod='.$mrow["mod_short_name"].'">';
				if(is_file(FCPATH.'modules/'.$mrow["mod_short_name"].'/'.$mrow["mod_short_name"].'.gif') || is_file(FCPATH.'modules/'.$mrow["mod_short_name"].'/'.$mrow["mod_short_name"].'.png')) {
				$ext=(is_file(FCPATH.'modules/'.$mrow["mod_short_name"].'/'.$mrow["mod_short_name"].'.gif'))?'.gif':'.png';
				echo '<img src="'.FCPATHNICE.'/modules/'.$mrow["mod_short_name"].'/'.$mrow["mod_short_name"].$ext.'" border="0" alt="" vspace="0" width="26" />';
				}
			echo '</a></span>';
			$x++;
			}
		}
	}
}

function GenerateSubMenu($Config) {
GLOBAL $mod,$sub,$task;
	$HTML='<div style="text-align:right;"><ul id="SubMenu">';
	if(isset($Config["options"]) && count($Config["options"]["link"])>0) {
		for($x=0;$x<count($Config["options"]["link"]);$x++) {
		$base_link=(isset($_REQUEST["sub"]) && $_REQUEST["sub"]!="")?'?mod='.$_REQUEST["mod"].'&sub='.$_REQUEST["sub"].$Config["options"]["link"][$x]:'?mod='.$_REQUEST["mod"].$Config["options"]["link"][$x];
		$HTML.='<li><a';
			if(isset($_REQUEST["task"]) && $_REQUEST["task"]==strtolower($Config["options"]["label"][$x])) {
			$HTML.=' class="current"';
			}
		$HTML.=' href="'.$base_link.'">'.$Config["options"]["label"][$x].'</a></li>';
		}
	}
	$HTML.='</ul></div>';
echo $HTML;
}

#########################################################
##
#########################################################

function findfile($location='',$fileregex='') {
	if (!$location or !is_dir($location) or !$fileregex) {
	return false;
	}

	$matchedfiles = array();

	$all = opendir($location);
	while ($file = readdir($all)) {
		if (@is_dir($location.'/'.$file) and $file <> ".." and $file <> "." and $file <> '__MACOSX') {
		$subdir_matches = findfile($location.'/'.$file,$fileregex);
		$matchedfiles = array_merge($matchedfiles,$subdir_matches);
		unset($file);
		}
		elseif (!@is_dir($location.'/'.$file)) {
			if (preg_match($fileregex,$file)) {
			array_push($matchedfiles,$location.'/'.$file);
			}
		}
	}
	closedir($all);
	unset($all);
return $matchedfiles;
}

function removefiles($location='',$fileregex='') {
	if (!$location or !is_dir($location) or !$fileregex) {
	return false;
	}

	

	$all = opendir($location);
	while ($file = readdir($all)) {
		if (@is_dir($location.'/'.$file) and $file <> ".." and $file <> "." and ($file[0].$file[1] !="._")) {
		$subdir_matches = findfile($location.'/'.$file,$fileregex);		
		unset($file);
		}
		elseif (!@is_dir($location.'/'.$file)) {
			if (preg_match($fileregex,$file)) {
			@unlink($location.'/'.$file);
			}
		}
	}
	closedir($all);
	unset($all);
}

#########################################################
##
#########################################################

function Paginate($res) {
GLOBAL $offset,$limit,$orderby,$HTTP_SESSION_VARS,$ThisPage;
$SearchRes=$res;
$DispLimit=$limit;
$DispOffset=($offset>0)?$offset:0;
$DispNumPages=($res > $DispLimit)?ceil($res/$DispLimit):1;
$DispStart=($offset > 40)?(($DispOffset+1)/$DispLimit)-3:$DispOffset+1;
$DispEnd=($res > ($DispStart + $DispLimit))?($DispStart+$DispLimit)-1:$res;	
	
	$HTML='';
		if($DispNumPages > 1) {
			if($DispOffset > 0) {
			$HTML.='<a href="'.$ThisPage.'?'.str_replace("&offset=$offset","",$_SERVER["QUERY_STRING"]).'&offset='.($offset - $limit).'">&lt;</a>';
			}
			if($SearchRes > $limit) {
			$TotalPages=ceil($SearchRes/$limit);
			}
			else {
			$TotalPages=1;
			}
		
			//if($offset > 40) {
			//$StartPage=($DispOffset / $DispLimit) - 3;
			//}
			//else {
			$StartPage=1;
			//}
			
			$a=1;
			for($x=$StartPage;$x<=$TotalPages;$x++) {
				if($a < 11) {
					if(($DispOffset / $DispLimit) + 1 != $x) {
					$HTML.= '<a href="'.$ThisPage.'?'.ereg_replace("&offset=[0-9]*","",$_SERVER["QUERY_STRING"]).'&amp;offset='.($x-1) * $DispLimit.'" class="Paginater">';
					}
				$HTML.= $x.' ';
					if(($DispOffset / $DispLimit) + 1 != $x) {
					$HTML.= '</a>';
					}
				}
			$a++;
			}
			
			if($SearchRes > ($DispOffset+$DispLimit)) {
			$HTML.='<a href="'.$ThisPage.'?'.str_replace("&offset=$offset","",$_SERVER["QUERY_STRING"]).'&offset='.($offset + $limit).'">&gt;</a>';
			}
		}
	$HTML.='';
return $HTML;
}

#########################################################
## Ajax Chat Shoutbox
#########################################################

function getShoutBoxContent() {
	// URL to the chat directory:
	if(!defined('AJAX_CHAT_URL')) {
		define('AJAX_CHAT_URL', './chat/');
	}
	
	// Path to the chat directory:
	if(!defined('AJAX_CHAT_PATH')) {
		define('AJAX_CHAT_PATH', realpath(dirname($_SERVER['SCRIPT_FILENAME']).'/chat').'/');
	}
	
	// Validate the path to the chat:
	if(@is_file(AJAX_CHAT_PATH.'lib/classes.php')) {
		
		// Include Class libraries:
		require_once(AJAX_CHAT_PATH.'lib/classes.php');
		
		// Initialize the shoutbox:
		$ajaxChat = new CustomAJAXChatShoutBox();
		
		// Parse and return the shoutbox template content:
		return $ajaxChat->getShoutBoxContent();
	}
	
	return null;
}

function RedirectTo($URL) {
echo "<script type=\"text/javascript\">
window.location.href='".$URL."';
</script>
";
}

function ordinalize($number) {
	if (in_array(($number % 100),range(11,13))){
		return $number.'th';
		}else{
		switch (($number % 10)) {
		case 1:
		return $number.'st';
		break;
		case 2:
		return $number.'nd';
		break;
		case 3:
		return $number.'rd';
		default:
		return $number.'th';
		break;
		}
	}
}
?>