<?php
$error=false;
$task=(isset($_REQUEST["task"]))?$_REQUEST["task"]:"browse";
switch($task) {

	case "save":
		if(isset($_FILES["image_file"]) && is_uploaded_file($_FILES["image_file"]["tmp_name"])) { ## upload file
			$mediadir=(isset($_POST["mediadir"]))?$_POST["mediadir"]:'';
			$FileName=CreateFileName($_FILES["image_file"]["name"]);
			if(!@copy($_FILES["image_file"]["tmp_name"],SITEPATH.$mediadir.$FileName)) {
			$error="Unable to copy file: ".SITEPATH.$mediadir.$FileName;
			}
			else {
			$msg="Your image has been uploaded";
			}
		}
		if(isset($_POST["mediadir_name_new"]) && $_POST["mediadir_name_new"]!="") { ## new dir
			$mediadir=(isset($_POST["mediadir"]))?$_POST["mediadir"]:'';
			$FileName=CreateFileName($_POST["mediadir_name_new"]);
			if(!is_dir(SITEPATH.$mediadir.$FileName)) {
				@umask(0000);
				if(!mkdir(SITEPATH.$mediadir.$FileName)) {
				$error="unable to create directory: ".SITEPATH.$mediadir.$FileName;
				}
			}
			else {
			$error="A directory with that name already exists";
			}
		}
		if(isset($_POST["file_name_new"]) && $_POST["file_name_new"]!="") { ## new file
			$mediadir=(isset($_POST["mediadir"]))?$_POST["mediadir"]:'';
			$FileName=CreateFileName($_POST["file_name_new"]);
			if(!is_dir(SITEPATH.$mediadir.$FileName) && !is_file(SITEPATH.$mediadir.$FileName)) {
				$fp=@fopen(SITEPATH.$mediadir.$FileName,"w");
				fwrite($fp," ");
				@fclose($fp);
				header("location: ".FCPATHNICE."?mod=".$_REQUEST["mod"]."&mediadir=".$mediadir."&id=".$FileName);
				exit;
			}
			else {
			$error="A directory/file already exists with the same name";
			}
		}
		if(isset($_POST["mediadir_name"]) && $_POST["mediadir_name"]!="") { ## rename dir
			$mediadir=(isset($_POST["mediadir"]))?$_POST["mediadir"]:'';
			$FileName=CreateFileName($_POST["mediadir_name"]);
			$mediaarr=explode("/",$_POST["mediadir"]);
			$old=$mediaarr[count($mediaarr)-2];
			$CurrentDir=substr(SITEPATH.$mediadir,0,strlen(SITEPATH.$mediadir)-1);
			$NewDir=str_replace($old,$FileName,$CurrentDir);
			if(!rename($CurrentDir,$NewDir)) {
			$error="unable to rename directory";
			}
			else {
			$mediadir=str_replace($old,$FileName,$mediadir);
			}
		}
		if(isset($_POST["username"]) && $_POST["username"]!="" && isset($_POST["password"]) && $_POST["password"]!="") {
			$mediadir=(isset($_POST["mediadir"]))?$_POST["mediadir"]:'';
			if(!$fp=@fopen(SITEPATH.$mediadir.".htaccess")) {
			$error="unable to write to ".$mediadir;
			}
			else {
				fwrite($fp,"AuthName \"Restricted Area\"\nAuthType Basic\nAuthUserFile /".$mediadir.".htpasswd\nAuthGroupFile /dev/null\nrequire valid-user");
				@fclose($fp);
				if(!$fp=@fopen(SITEPATH.$mediadir.".htpasswd")) {
				$error="unable to write to ".$mediadir;
				}
				else {
				fwrite($fp,$_POST["username"].":".crypt($_POST["password"], base64_encode($_POST["password"])));
				@fclose($fp);
				}
			}
		}
		if(!$error) {
		header("location: ".FCPATHNICE."?mod=".$_REQUEST["mod"]."&mediadir=".$mediadir."&msg=".urlencode($msg));
		exit;
		}
		else {
		DisplayTemplates();
		}
	break;
	
	case "edit":
		$mediadir=(isset($_REQUEST["mediadir"]))?$_REQUEST["mediadir"]:'';
		$file=(isset($_REQUEST["file_name"]) && $_REQUEST["file_name"] !="")?$_REQUEST["file_name"]:$_REQUEST["id"];
		if(!$fp=@fopen(SITEPATH.$mediadir.$file,"w")) {
		$error="unable to open ".$file;
		}
		else {
			if(!fwrite($fp,str_replace(array("&lt;","&gt;"),array("<",">"), stripslashes($_POST["file_code"])))) {
			$error="unable to write to ".$file;
			}
		@fclose($fp);
		}
		if(!$error) {
		header("location: ".FCPATHNICE."?mod=".$_REQUEST["mod"]."&mediadir=".$mediadir."&msg=".urlencode("Your changes have been saved."));
		exit;
		}
		else {
		DisplayEditFile();
		}
	break;
	
	case "remove":
		if(isset($_REQUEST["id"])) {
		$mediadir=(isset($_REQUEST["mediadir"]))?$_REQUEST["mediadir"]:'';
			if(is_file(SITEPATH.$mediadir.$_REQUEST["id"])) {
				if(!@unlink(SITEPATH.$mediadir.$_REQUEST["id"])) {
				$error="Unable to remove file: ".SITEPATH.$mediadir.$_REQUEST["id"];
				}
				else {
				$msg="Your image has been removed";
				}
			}
		}
		if(!$error) {
		header("location: ".FCPATHNICE."?mod=".$_REQUEST["mod"]."&mediadir=".$mediadir."&msg=".urlencode($msg));
		exit;
		}
		else {
		DisplayTemplates();
		}
	break;
	
	case "browse":
		$_REQUEST["id"]=(isset($_REQUEST["id"]))?$_REQUEST["id"]:'';
		$mediadir=(isset($_REQUEST["mediadir"]))?$_REQUEST["mediadir"]:'';
		if($_REQUEST["id"]!="") {
		DisplayEditFile();
		}
		else {
		DisplayTemplates();
		}
	break;

}
?>