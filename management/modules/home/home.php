<?php
$error=false;
$task=(isset($_REQUEST["task"]))?$_REQUEST["task"]:"";

switch($task) {


	case "create":
		$_REQUEST["id"]=(isset($_REQUEST["id"]))?$_REQUEST["id"]:"-1";
		$Details=MySQLArray("SELECT * FROM home_promos WHERE promo_id='".$_REQUEST["id"]."'");
	DisplayPageForm($Details);
	break;
	
	case "save":
		if(isset($_POST["remove_x"]) && $_POST["id"] > 0) { ## remove
		mysql_query("DELETE FROM home_promos WHERE promo_id='".$_POST["id"]."'");
		RedirectTo(FCPATHNICE."?mod=".$_REQUEST["mod"]."&msg=".urlencode("Your contact has been removed"));
		exit;
		}
		if(isset($_POST["cancel_x"])) {
		RedirectTo(FCPATHNICE."?mod=".$_REQUEST["mod"]."&msg=".urlencode("cancelled"));
		exit;
		}
		
		if(!$MISSING = CheckMandatoryFields()) {
			
			$FD=Array(
			"promo_name" => PrepareData("promo_name"),
			"promo_url" => PrepareData("promo_url")
			);
			
			if(isset($_FILES["promo_image"]["tmp_name"]) && is_uploaded_file($_FILES["promo_image"]["tmp_name"])) {
				$filename=base_convert( md5(microtime()), 10, 36 );
				$ext='.'.strtolower(substr(strrchr($_FILES["promo_image"]["name"],'.'), 1));
				if(copy($_FILES["promo_image"]["tmp_name"],$Config["content"]["images"].$filename.$ext)) {
				$FD=array_merge($FD,array("promo_image" => $filename.$ext));
				}
			}
							
			if(!$error) {
				if($_POST["id"]>0) { ## update
					if(!mysql_query(MySQLUpdate($FD,"home_promos","promo_id",$_POST["id"]))) {
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
			$Details=MySQLArray("SELECT * FROM home_promos WHERE promo_id='".$_REQUEST["id"]."'");
			DisplayPageForm($Details);
			}
		}
		else {
		$Details=MySQLArray("SELECT * FROM home_promos WHERE promo_id='".$_REQUEST["id"]."'");
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
?>