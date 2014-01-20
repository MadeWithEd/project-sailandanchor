<?php
if(isset($limit)) { $limit=$limit; } else { $limit=10; } ## MAIN DISPLAY LIMIT FOR ALL PAGES

if(isset($_GET["offset"])) { $offset=$_GET["offset"]; } else { $offset=0; }

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
GLOBAL $offset,$limit,$orderby,$HTTP_SESSION_VARS,$ThisPage;
$DispStatus=ShowDispStatus();
$STATUS["0"]="Draft";
$STATUS["1"]="Live";
$STATUS["2"]="Archived";

	## query the DB
	if($SD["query"]["where"]!="") {
	$WHERE=" WHERE ".$SD["query"]["where"];
	}
	else {
	$WHERE="";
	}
	
	if($SD["query"]["on"]!="") {
	$ON=" ON ".$SD["query"]["on"];
	}
	else {
	$ON="";
	}

//$Res=execute_query($SD["query"]["select"]." FROM ".$SD["query"]["from"]." ".$ON." ". $WHERE);
$Res=@mysql_num_rows(mysql_query($SD["query"]["select"]." FROM ".$SD["query"]["from"]." ".$ON." ". $WHERE));

	## Start table
	$HTML='<table class="'.$SD["table"]["class"].'" border="'.$SD["table"]["border"].'" width="'.$SD["table"]["width"].'" cellpadding="'.$SD["table"]["cellpadding"].'" cellspacing="'.$SD["table"]["cellspacing"].'">'."\n";
	$HTML.='<tr>'."\n";
	
		for($x=0;$x<count($SD["header"]["fields"]["title"]);$x++) {
		$ColAlign=($SD["header"]["fields"]["align"][$x]!="")?$SD["header"]["fields"]["align"][$x]:"left";
		
			if($x==0) {
			$Col=" firstcell";
			}
			elseif($x==count($SD["header"]["fields"]["title"])-1) {
			$Col=" lastcell";
			}
			else {
			$Col="";
			}
		
		$HTML.='<th class="'.$SD["header"]["class"].$Col.'" width="'.$SD["header"]["fields"]["width"][$x].'" align="'.$ColAlign.'">';
		
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
		
		$HTML.='</th>'."\n";
		}
	
	$HTML.='</tr>'."\n";
	
	if(!empty($SD["header"]["divider"])) {
	$HTML.='<tr>'."\n";
	$HTML.='<td class="'.$SD["header"]["divider"].'" colspan="'.count($SD["header"]["fields"]["title"]).'"></td>'."\n";
	$HTML.='</tr>'."\n";
	}
	
	
	$SQL=$SD["query"]["select"]." FROM ".$SD["query"]["from"]." $ON $WHERE ORDER BY $orderby LIMIT $offset,$limit";
	//$QUERY=execute_query($SQL);
	$QUERY=mysql_query($SQL);
	
	//echo $SQL;
	
	$ClassCount=0;
	$DataCount=0;
	
	//foreach($QUERY as $ROW) {
	while($ROW=mysql_fetch_array($QUERY)) {
	$HTML.='<tr>'."\n";
	
		for($x=0;$x<count($SD["header"]["fields"]["title"]);$x++) {
			$ClassArr=explode(",",$SD["data"]["class"]);
			$ColAlign=($SD["header"]["fields"]["align"][$x]!="")?$SD["header"]["fields"]["align"][$x]:"left";
			
			if($x==0) {
			$Col=" firstcell";
			}
			elseif($x==count($SD["header"]["fields"]["title"])-1) {
			$Col=" lastcell";
			}
			else {
			$Col="";
			}
			
			if(count($ClassArr)>1) {
				if($ClassCount==count($ClassArr)) {
				$ClassCount=0;
				}
			$HTML.='<td class="'.$ClassArr[$ClassCount].$Col.'" align="'.$ColAlign.'">';
			}
			else {
			$HTML.='<td class="'.$SD["data"]["class"].$Col.'" align="'.$ColAlign.'">';
			}
						
			$Content=$SD["header"]["fields"]["content"][$x];
			
			## first, look for $vars shown as {var} to translate
			preg_match_all("/\{([a-zA-Z0-9_-]*)\}/i",$Content,$v);
				foreach($v[1] as $vr) {
				$Content=ereg_replace("\{".$vr."\}",stripslashes($ROW[$vr]),$Content);
				}
				
			## same but look for escape feature for nasties {.var.}
			preg_match_all("/\{\.([a-zA-Z0-9_-]*)\.\}/i",$Content,$v);
				foreach($v[1] as $vr) {
				$Content=ereg_replace("\{\.".$vr."\.\}",htmlentities($ROW[$vr]),$Content);
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
	
		if(!empty($SD["header"]["divider"])) {
		$HTML.='<tr>'."\n";
		$HTML.='<td class="'.$SD["header"]["divider"].'" colspan="'.count($SD["header"]["fields"]["title"]).'"></td>'."\n";
		$HTML.='</tr>'."\n";
		}
	$ClassCount++;
	$DataCount++;
	}
	mysql_free_result($QUERY);
	
	## Add footer if any
	for($x=0;$x<count($SD["footer"]);$x++) {
	$ClassArr=explode(",",$SD["footer"]["class"]);
	$ColCount=0;
	$ClassCount=0;
	$HTML.='<tr>'."\n";
		for($y=0;$y<count($SD["footer"][$x]["content"]);$y++) {
			if(count($ClassArr)>1) {
				if($ClassCount==count($ClassArr)) {
				$ClassCount=0;
				}
			$Class=$ClassArr[$ClassCount];
			}
			else {
			$Class=$SD["footer"]["class"];
			}
			
			if(count($SD["footer"]["align"])>1) {
				if($ColCount==count($SD["footer"]["align"])) {
				$ColCount=0;
				}
			$Col=$SD["footer"]["align"][$ColCount];
			}
			else {
			$Col=$SD["footer"]["align"];
			}
			$Col=($Col=="")?"left":$Col;
		$HTML.='<td class="'.$Class.'" align="'.$Col.'" colspan="'.$SD["footer"][$x]["span"].'">'.$SD["footer"][$x]["content"][$y].'</td>'."\n";
		$ColCount++;
		}
	$HTML.='</tr>'."\n";
	}
	
	## add extra row forcing widths with image
	$HTML.='<tr style="height:1px;display:none;">'."\n";
		for($x=0;$x<count($SD["header"]["fields"]["title"]);$x++) {
		$HTML.='<td><img src="/images/spacer.gif" border="0" height="1" width="'.$SD["header"]["fields"]["width"][$x].'" alt="" /></td>'."\n";
		}
	$HTML.='</tr>'."\n";

	$HTML.='</table>'."\n";
	
	## Look for Buttons
	if(is_array($SD["extra"]["buttons"])) {
	$HTML.='<div id="ButtonLayer"><br />';
	
		for($a=0;$a<count($SD["extra"]["buttons"]["label"]);$a++) {
		$HTML.='<div class="Btn'.$SD["extra"]["buttons"]["class"][$a].'" id="'.$SD["extra"]["buttons"]["id"][$a].'" onclick="'.$SD["extra"]["buttons"]["onclick"][$a].'">'.$SD["extra"]["buttons"]["label"][$a].'</div>';
		}
	
	$HTML.='</div>'."\n";
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
		$Html='<img src="/images/ico_order_desc.gif" border="0" alt="" width="14" height="12"  hspace="5" />';
		}
		else {
		$Html='<img src="/images/ico_order_asc.gif" border="0" alt="" width="14" height="12"  hspace="5" />';
		}
	}
	else {
	$Html='<img src="/images/spacer.gif" border="0" width="14" height="12" alt=""  hspace="5" />';
	}
return $Html;
}

#######################################################
## Returns nicely formatted name for file
#######################################################

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

function ShowDataText($Val,$Arr='',$Def='') {
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
	
	if($Value=='' && $Def != '') {
	$Value=$Def;
	}
	
return $Value;
}

function RemoveHtmlFormatting($val) {
	$ReplaceWhat=array("&#039;","&amp;");
	$ReplaceWith=Array("'","&");
	$Value=str_replace($ReplaceWhat,$ReplaceWith,$val);
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
	//if(!$Value=execute_query($SQL)) {
	$Value="0";
	}
	//print_r($Value);
return $Value;
}

function MySQLArray($SQL) {
	if(!$Data=@mysql_fetch_array(mysql_query($SQL))) {
	//if(!$Data=execute_query($SQL)) {
	$Data=0;
	}
	//print_r($Data[0]);
return $Data;
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
			if($MISSING[$FieldName]) {
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
		if(isset($MISSING)) {
			if($MISSING[$FieldName]) {
			$err=true;
			}			
		}
	}
	if($err==true) {
	$HTML=" FieldError";
	}
	else  {
	$HTML="";
	}
return $HTML;
}


function HighlightMandatoryNEW($FieldName) {
GLOBAL $MISSING,$Details,$MemberDetails;
$err=false;$HTML='';

	if(is_array($FieldName)) {
		foreach($FieldName as $Field) {
			if(isset($MISSING[$Field])) {
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
	$HTML=' class="MandatoryMissing"';
	}
	else  {
		if(is_array($FieldName)) {
			$FieldState='missing';
			foreach($FieldName as $Field) {
				if(!empty($_REQUEST[$Field])) {
				$FieldState='notmissing';
				}
			}
			if($FieldState=='missing') {
			$HTML=' class="Mandatory"';
			}
			else {
			$HTML=' class="MandatoryTick"';
			}
		}
		else {
			if(!empty($_REQUEST[$FieldName]) || !empty($ActivityDetails[$FieldName]) || !empty($Details[$FieldName]) || !empty($MemberDetails[$FieldName])) {
			$HTML=' class="MandatoryTick"';
			}
			else {
			$HTML=' class="Mandatory"';
			}
		}
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

function PopulateSelect($Table,$IDField,$ValueField,$Selected="",$Where="") {
	if($Where!="") {
	$WHERE=" WHERE ".$Where;
	}
	$HTML='';
	$IDExtra='';
	$IDFieldParse=false;
	## Split up values if comma separated
	$ValueArray=@explode(",",$ValueField);
	if(count($ValueArray) > 1) {
	$ValueFields="CONCAT(".$ValueField.") as dField";
	$ValueField="dField";
	}
	else {
	$ValueFields=$ValueField;
	}
	
	if(strstr($IDField,".")) {
	$IDFieldRef=substr($IDField,(strpos($IDField, ".")+1),strlen($IDField));
	}
	else {
		if(strstr($IDField,"{")) { ## extract bracketed value
		preg_match_all("/\{([a-zA-Z_0-9\-]*)\}/i",$IDField,$v);
			foreach($v[1] as $vr) {
			$IDFieldParse=$vr;
			$IDField=str_replace("{".$vr."}","",$IDField);
			}
		}
		if(strstr($IDField,"[")) { ## extract bracketed value
		preg_match_all("/\[([a-zA-Z_0-9\- ]*)\]/i",$IDField,$v);
			foreach($v[1] as $vr) {
			$IDExtra=$vr;
			$IDField=str_replace("[".$vr."]","",$IDField);
			}
		}
		$IDFieldRef=$IDField;
		
	}
	
	//echo "SELECT $IDField,$ValueFields FROM $Table $WHERE";
	$SQL=mysql_query("SELECT $IDField,$ValueFields FROM $Table $WHERE");
	while($ROW=mysql_fetch_array($SQL)) {
		if(isset($IDFieldParse) && !empty($IDFieldParse)) {
		$IDExtra=$ROW[$IDFieldParse].$IDExtra;
		}
	$HTML.='<option value="'.$IDExtra.$ROW[$IDFieldRef].'"';
		if($Selected!="") {
			if($ROW[$IDField]==$Selected) {
			$HTML.=' selected="selected"';
			}
		}
	$HTML.='>'.stripslashes($ROW[$ValueField]).'</option>'."\n";
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
		//$Exists=execute_query("SELECT $FieldName FROM $Table WHERE $Fieldname='".$File."'");
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

function CropThumbNail($imgfile,$cropW,$cropH) {
	$cropStartX = 0;
	$cropStartY = 0;

	// Create two images
	$origimg = imagecreatefromjpeg($imgfile);
	$cropimg = imagecreatetruecolor($cropW,$cropH);
	$white = imagecolorallocate($cropimg, 255, 255, 255);
	imagefill($cropimg,0,0,$white);
	
	// Get the original size
	list($width, $height) = getimagesize($imgfile);

	// Crop
	imagecopyresized($cropimg, $origimg, 0, 0, $cropStartX, $cropStartY, $width, $height, $width, $height);

	$imgformat=strtoupper(ereg_replace(".*\.(.*)$","\\1",$imgfile));
	
	if ($imgformat=="JPG" || $imgformat=="JPEG") {
	imageJPEG($cropimg,$imgfile,75);
	} elseif($imgformat == "GIF") {
	imageGIF($cropimg,$imgfile);
	} elseif($imgformat == "PNG") {
	imagePNG($cropimg,$imgfile);
	}
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

function ReverseEnum($Val) {
	if($Val=="Y") {
	$Value="Yes";
	}
	elseif($Val=="F") {
	$Value="Female";
	}
	elseif($Val=="M") {
	$Value="Male";
	}
	else {
	$Value="No";
	}
return $Value;
}

function ReverseDate($Date) {
	$D=explode("-",$Date);
	$NewDate=$D[2]."-".$D[1]."-".$D[0];
return $NewDate;
}

######################################################
## Function to display red progress indicator
## on members/teams pages
######################################################

function GetProgressLength($MaxWidth,$Target,$Achieved) {
$len=1;
	if($Achieved > 1 && $Target > 0) {
		if($Achieved > $Target) {
		$len=$MaxWidth;
		}
		else {
		$Ratio=$Achieved/$Target;
		$len=round($MaxWidth * $Ratio);
		}
	}
	else if($Achieved > 1 && $Target == 0) {
	$len=$MaxWidth;
	}
	else {
	$len=1;
	}
	
return $len;
}

function GetDonations($member_id,$team_id,$disp='',$numformat='') {
	if($disp=='') {$disp='team'; }
	
	if($member_id>0) {
	$TotalDonated=MySQLResult("SELECT SUM(d_amount) FROM donations WHERE member_id='".$member_id."'");
	}
	
	if($team_id>0 && $disp!='member') {
	$TotalDonated=MySQLResult("SELECT SUM(d_amount) FROM donations WHERE team_id='".$team_id."'");
	}
		
	if($TotalDonated>0) {
		$TotalDonated="\$".number_format($TotalDonated);
		if($numformat!='') {
		$TotalDonated=str_replace(",","",$TotalDonated);
		}
	}
	else {
	$TotalDonated="$0";
	}
return $TotalDonated;
}

########################################################
## Function to calculate Age from DOB
## yyyy-mm-dd
########################################################

function GetAge($DOB) {
## Split $DOB
$DOBArray = explode("-", $DOB);
$DobYear = $DOBArray[0];

$DobMonth = $DOBArray[1];
$DobDay = $DOBArray[2];
## Get today's year, month and day
$TodayDay = date('d');
$TodayMonth = date('m');
$TodayYear = date('Y');
## Work out Age in Years
	if (($TodayMonth > $DOBArray[1]) || (($TodayMonth == $DOBArray[1]) && ($TodayDay >= $DOBArray[2]))) {
	$AgeYear = $TodayYear - $DOBArray[0];
	} else {
	$AgeYear = $TodayYear - $DOBArray[0] - 1;
	}
## Calculate current "day of year"
$dayofyear = date('z', mktime(0, 0, 0, date("m"), date("d"), date("Y")));
$dayofyear = $dayofyear+1;
##Calculate birthday's "day of year"
$dayofbday = date('z', mktime(0, 0, 0, $DobMonth, $DobDay, date("Y")));
	if ($dayofbday > $dayofyear) { $days = 365 - ($dayofbday - $dayofyear);
	} else {
	$days = ($dayofyear - $dayofbday);
	}
## return the age
return $AgeYear;
}


##########################################################
## Function for generating the radnom team/member images.
## 
##########################################################

function GetRandomImage() {
GLOBAL $ActiveEventID;
$Img=false;
$Bres=MySQLResult("SELECT count(*) FROM events_photos WHERE event_id='".$ActiveEventID."'");
	if($Bres>0) {
		if($Bres==1) {
		$Bdetails=@mysql_fetch_array(mysql_query("SELECT * FROM events_photos WHERE event_id='".$ActiveEventID."'  LIMIT 1"));
		}
		else {
		$Boffset=rand("1",$Bres - 1);
		$Bdetails=@mysql_fetch_array(mysql_query("SELECT * FROM events_photos WHERE event_id='".$ActiveEventID."'  LIMIT $Boffset,1"));
		}
		//if($Bdetails["photo_name"] && @is_file($_SERVER["DOCUMENT_ROOT"]."/images/events/".$Bdetails["photo_name"])) {
		//$Inf=@getimagesize($_SERVER["DOCUMENT_ROOT"]."/images/events/".$Bdetails["photo_name"]);
		//$ImgDetails=array("width"=>$Inf[0],"height"=>$Inf[1],"source"=>$_SERVER["DOCUMENT_ROOT"]."/images/events/".$Bdetails["photo_name"]);
		//}
	$Img="/images/events/".$Bdetails["photo_name"];
	}
return $Img;
}

##########################################################
## looks to see if we have any family members and if so
## creates a nice long family name
##########################################################

function DisplayFamilyName($Details) {
	if($Details["m_name_alt"]!="") {
	$FamilyName=stripslashes($Details["m_name_alt"]);
	}
	else {
		if($Details["is_family"]=="Y") {
			$res=MySQLResult("SELECT count(*) FROM members_family WHERE member_id='".$Details["member_id"]."' AND history_id='".$Details["history_id"]."'");
			if($res>0) {
			$FamilyName=stripslashes($Details["m_fname"]);
			$x=1;
				$sql=@mysql_query("SELECT * FROM members_family WHERE member_id='".$Details["member_id"]."' AND history_id='".$Details["history_id"]."'");
				while($row=@mysql_fetch_array($sql)) {
					$FamilyName.=($x==$res)?" &amp; ":", ";
					$FamilyName.=stripslashes($row["ch_fname"]);
				$x++;
				}
			$FamilyName.=stripslashes(" ".$Details["m_lname"]);
			}
			else {
			$FamilyName=stripslashes($Details["m_fname"]." ".$Details["m_lname"]);
			}
		}
		else {
		$FamilyName=stripslashes($Details["m_fname"]." ".$Details["m_lname"]);
		}
	}
return $FamilyName;
}

###########################################################
## function to simply return the total number of people
## in a team, including family members
###########################################################

function CountTeamFamilyMembers($TeamID) {
	if($TeamID>0) {
		$sql=@mysql_query("SELECT a.member_id FROM members AS a, members_history AS b WHERE a.member_id=b.member_id AND b.team_id='".$TeamID."' AND b.is_paid='Y' AND a.m_status='1' AND a.is_family='Y'");
		while($row=@mysql_fetch_array($sql)) {
			$NumberFamily+=MySQLResult("SELECT count(*) FROM members_family WHERE member_id='".$row["member_id"]."'");
		}
	}
return $NumberFamily;
}


#############################################################
## Global mandatory field checker
##
#############################################################

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
			elseif(ereg("{=",$Fields[$x])) {
				$Field1=substr($Fields[$x],0,strpos($Fields[$x],"{"));
				$Field2=substr($Fields[$x],strpos($Fields[$x],"=")+1,-1);
				if(PrepareData($Field1) != PrepareData($Field2)) {
				$MISSING[$Field2]=true;
				}
			}
			elseif(ereg("{!=",$Fields[$x])) {
				$Field1=substr($Fields[$x],0,strpos($Fields[$x],"{"));
				$Field2=substr($Fields[$x],strpos($Fields[$x],"=")+1,-1);
				if(PrepareData($Field1) == $Field2) {
				$MISSING[$Field1]=true;
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



function new_html_entities($Content) {
return $Content;
}


###############################################
## Parses PHP content stored in DB
###############################################

function EvaluateContent($PHPContent) {
GLOBAL $Details,$EventDetails,$_SESSION,$SiteUrl,$SETUP,$ActiveEventID,$CaptainDetails,$MemberDetails,$TeamName,$LoginDetails,$PersonalPoNum,$DonationFrom,$NewPass;
	ob_start();
	eval("?".">".str_replace(array("$"),array("\$"),stripslashes($PHPContent))."<"."?");
	$Content = ob_get_contents();
	ob_end_clean();
	
	$ReplaceWhat=array("{event_name}","{event_date}");
	$ReplaceWith=array($EventDetails["event_name"],$EventDetails["event_date"]);
	
	return stripslashes(str_replace($ReplaceWhat,$ReplaceWith,$Content));
}

function GenerateRandomString($Table,$Field) {
$key=strtoupper(substr(base_convert( md5(microtime()), 10, 36 ), 0 , 6));
	if(MySQLResult("SELECT count(*) FROM ".$Table." WHERE `".$Field."`='".$key."'")>0) {
	GenerateRandomString($Table,$Field);
	}
	else {
	return $key;
	}
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

function ordinalizewithout($number) {
	if (in_array(($number % 100),range(11,13))){
		return 'th';
		}else{
		switch (($number % 10)) {
		case 1:
		return 'st';
		break;
		case 2:
		return 'nd';
		break;
		case 3:
		return 'rd';
		default:
		return 'th';
		break;
		}
	}
}


function force_download ($data, $name, $mimetype='', $filesize=false) { 
    // File size not set? 
    if ($filesize == false OR !is_numeric($filesize)) { 
        $filesize = strlen($data); 
    } 

    // Mimetype not set? 
    if (empty($mimetype)) { 
        $mimetype = 'application/octet-stream'; 
    } 

    // Make sure there's not anything else left 
    ob_clean_all(); 

    // Start sending headers 
    header("Pragma: public"); // required 
    header("Expires: 0"); 
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
    header("Cache-Control: private",false); // required for certain browsers 
    header("Content-Transfer-Encoding: binary"); 
    header("Content-Type: " . $mimetype); 
    header("Content-Length: " . $filesize); 
    header("Content-Disposition: attachment; filename=\"" . $name . "\";" ); 

    // Send data 
    echo $data; 
    die(); 
} 

function ob_clean_all () { 
    $ob_active = ob_get_length () !== false; 
    while($ob_active) { 
        ob_end_clean(); 
        $ob_active = ob_get_length () !== false; 
    } 

return true; 
}

function is_even($number) { return(!($number & 1)); }

function UpdateRankingPositions() {}

function strip_tags2($content,$tags) {
		foreach($tags as $tag) {
		$content=eregi_replace("<".$tag."|</".$tag."","",$content);
		}
		$content=eregi_replace("onmouseover|onclick","",$content);
	return $content;
}

#########################################################
## Get difference between 2 dates
##
#########################################################

function getDifference($startDate,$endDate,$format = 6)
{
    list($date,$time) = explode(' ',$endDate);
    $startdate = explode("-",$date);
    $starttime = explode(":",$time);

    list($date,$time) = explode(' ',$startDate);
    $enddate = explode("-",$date);
    $endtime = explode(":",$time);

    $secondsDifference = mktime($endtime[0],$endtime[1],$endtime[2],
        $enddate[1],$enddate[2],$enddate[0]) - mktime($starttime[0],
            $starttime[1],$starttime[2],$startdate[1],$startdate[2],$startdate[0]);
    
    switch($format){
        // Difference in Minutes
        case 1:
            return floor($secondsDifference/60);
        // Difference in Hours    
        case 2:
            return floor($secondsDifference/60/60);
        // Difference in Days    
        case 3:
            return floor($secondsDifference/60/60/24);
        // Difference in Weeks    
        case 4:
            return floor($secondsDifference/60/60/24/7);
        // Difference in Months    
        case 5:
            return floor($secondsDifference/60/60/24/7/4);
        case 6:
        //seconds
        return floor($secondsDifference);
        // Difference in Years    
        default:
            return floor($secondsDifference/365/60/60/24);
    }                
}

function GetRecursiveSubNav($SubNavID) {
	if(!@is_array($SubNav)) {
	$SubNav=array();
	}
	if($SubNavID > 0) {
		$NavDetails=MySQLArray("SELECT page_id,page_name,page_file_name,sub_id FROM pages WHERE page_id='".$SubNavID."'");
		if($NavDetails["sub_id"]>0) {
		$SubNav=@array_merge($SubNav,GetRecursiveSubNav($NavDetails["sub_id"]));		
		}
	$SubNav=@array_merge($SubNav,array($NavDetails));
	}
return $SubNav;
}

function GetReverseRecursiveSubNav($pageID) {
	if(!@is_array($SubNav)) {
	$SubNav=array();
	}
	if($pageID>0) {
		$NavDetails=MySQLArray("SELECT * FROM pages WHERE sub_id='".$pageID."'");
		if($NavDetails["page_id"]>0) {
		$SubNav=@array_merge($SubNav,GetReverseRecursiveSubNav($NavDetails["page_id"]));		
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
	$pageurl='<li><a href="'.SITEURL.'">Home</a></li>';
	$x=0;
	$PathArray=GetRecursiveSubNav($page_id);
	if(count($PathArray)>0) {	
		foreach($PathArray as $key=>$cat) {
			if($cat>0 && $x>0) { 
			$pageurl.='<li><a href="/'.stripslashes(MySQLResult('SELECT page_file_name FROM pages WHERE page_id='.$cat["page_id"])).'">'.stripslashes(MySQLResult('SELECT page_name FROM pages WHERE page_id='.$cat["page_id"])).'</a></li>';
			}
		$x++;
		}
	}
	//$pageurl.='</ul>';
return $pageurl;
}

?>