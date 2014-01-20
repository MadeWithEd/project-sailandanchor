<?php
$error=false;
$task=(isset($_REQUEST["task"]))?$_REQUEST["task"]:"";
$doc_root=(isset($Config["pages"]["doc_root"]))?$Config["pages"]["doc_root"]:$CONFIG['site']['site_path'].'/'.$_REQUEST["mod"].'/';
$doc_root_nice=(isset($Config["pages"]["doc_root_nice"]))?$Config["pages"]["doc_root_nice"]:'/'.$_REQUEST["mod"].'/';
switch($task) {

	case "save":
	
		################
		## PAGES
		################
	
		if(isset($_REQUEST["id"]) && $_REQUEST["id"]!="") {
			if(!$MISSING = CheckMandatoryFields()) {
				$FileName=(PrepareData("page_file_name")!="")?CreateFileName(PrepareData("page_file_name")):CreateFileName(PrepareData("page_name"));
				$FD=Array(
				"page_name" => PrepareData("page_name"),
				"page_file_name" => str_replace($Config["pages"]["ext"],'',$FileName).$Config["pages"]["ext"],
				"nav_id" => PrepareData("nav_id"),
				"sub_id" => PrepareData("sub_id"),
				"template_id" => PrepareData("template_id"),
				"page_html" => PrepareData("page_html"),
				"last_updated" => date("Y-m-d H:i:s"),
				"date_created{insert}" => date("Y-m-d H:i:s"),
				"page_status{insert}" => "0",
				"page_link_title" => PrepareData("page_link_title"),
				"page_title" => PrepareData("page_title"),
				"page_description" => PrepareData("page_description"),
				"page_keywords" => PrepareData("page_keywords"),
				"page_redirect" => PrepareDataEnum("page_redirect","N"),
				"page_redirect_url" => PrepareData("page_redirect_url"),
				"page_redirect_option" => PrepareData("page_redirect_option"),
				"in_nav" => PrepareDataEnum("in_nav","N"),
				"is_home" => PrepareDataEnum("is_home","N"),
				);
				if(PrepareData("is_home")=="Y") {
				mysql_query("UPDATE pages SET is_home='N' WHERE 1");
				}
				if(MySQLResult("SELECT page_status FROM pages WHERE page_id='".$_REQUEST["id"]."'")!="0") {
				$FD=array_merge($FD,array("page_status{update}" => "4"));
				}
				if(!mysql_query(MySQLUpdate($FD,"pages","page_id",$_POST["id"]))) {
				$error="Oops! We reported an error: ".@mysql_error();
				$page_id=$_POST["id"];
				}
				
				## publishing ##
				
				if(isset($_POST["publish_x"])) {
					
					if(!$error=PublishPage($_POST["id"])) {
					mysql_query(MySQLUpdate(Array("page_status"=>"1"),"pages","page_id",$_POST["id"]));
					header("location: ".FCPATHNICE."?mod=".$_REQUEST["mod"]."&sub=".$_REQUEST["sub"]."&msg=".urlencode("Your page has been published"));
					exit;
					}
				}
				
				## previewing ##
				
				if(isset($_POST["preview_x"])) {
					$url=PreviewPage($_POST["id"]);
					if(ereg("/",$url)) {
					header("location: ".FCPATHNICE."?mod=".$_REQUEST["mod"]."&sub=".$_REQUEST["sub"]."&preview=".$url."&msg=".urlencode("Your changes have been saved"));
					exit;
					}
				}
				
				if(!$error) {
				header("location: ".FCPATHNICE."?mod=".$_REQUEST["mod"]."&sub=".$_REQUEST["sub"]."&msg=".urlencode("Your changes have been saved"));
				exit;
				}
				else {
				$Details=MySQLArray("SELECT * FROM pages_navigation WHERE nav_id='".trim($_REQUEST["nav_id"])."'");
				DisplayTemplates();
				}
			}
			if(!$error) {
			header("location: ".FCPATHNICE."?mod=".$_REQUEST["mod"]."&sub=".$_REQUEST["sub"]."&msg=".urlencode("Your changes have been saved"));
			exit;
			}
			else {
			$Details=MySQLArray("SELECT * FROM pages_navigation WHERE nav_id='".trim($_REQUEST["nav_id"])."'");
			DisplayTemplates();
			}
		}
		
		#################
		## DIRECTORY
		#################
		
		elseif(isset($_POST["nav_id"]) && $_POST["nav_id"]!="") {
			if(!$MISSING = CheckMandatoryFields()) {
				$NavDir=(PrepareData("nav_dir")!="")?CreateFileName(PrepareData("nav_dir")):CreateFileName(PrepareData("nav_name"));
				$FD=Array(
				"nav_name" => PrepareData("nav_name"),
				"last_updated" => date("Y-m-d H:i:s"),
				"date_created{insert}" => date("Y-m-d H:i:s")
				);
				$CurrentDir=MySQLResult("SELECT nav_dir FROM pages_navigation WHERE nav_id='".$_POST["nav_id"]."'");
				if($NavDir!=$CurrentDir) { ## changing directory
					if(!rename($doc_root.$SubPath.$CurrentDir,$CONFIG['site']['site_path'].'/'.$_REQUEST["mod"].'/'.$SubPath.$NavDir)) {
					$error="Unable to rename directory ".$doc_root.$SubPath.$CurrentDir;
					}
					else {
						$FD=array_merge($FD,array("nav_dir" => $NavDir));
						if(PrepareData("relink")=="Y") { ## attempt to rename any links
						
						}
					}
				}
				if(!$error) {
					if(!mysql_query(MySQLUpdate($FD,"pages_navigation","nav_id",$_POST["nav_id"]))) {
					$error="Oops! We reported an error: ".@mysql_error();
					}
				}
				## new sub?
				if(PrepareData("sub_id")>0 && PrepareData("sub_name")!="") {
					$SubDir=(PrepareData("sub_dir")!="")?CreateFileName(PrepareData("sub_dir")):CreateFileName(PrepareData("sub_name"));
					$SubNav=array_reverse(GetRecursiveDir(PrepareData("sub_id")));
					$SubPath="";	
					for($x=0;$x<count($SubNav);$x++) {
					$SubPath.=$SubNav[$x]["nav_dir"]."/";
					}
					if(!is_dir($doc_root.$SubPath.$SubDir)) {
					@umask(0000);
					@mkdir($doc_root.$SubPath.$SubDir);
						if(is_dir($doc_root.$SubPath.$SubDir)) {
							$FD=Array(
							"nav_name" => PrepareData("sub_name"),
							"nav_dir" => $SubDir,
							"sub_id" => $_POST["nav_id"],
							"last_updated" => date("Y-m-d H:i:s"),
							"date_created{insert}" => date("Y-m-d H:i:s")
							);
							if(!mysql_query(MySQLInsert($FD,"pages_navigation"))) {
							$error="Oops! We reported an error: ".@mysql_error();
							}
							$nav_id=@mysql_insert_id();
							@copy('404.html', $doc_root.$SubPath.$SubDir.'/index.html');
							$FD=Array(
							"page_name" => PrepareData("sub_name"),
							"page_file_name" => 'index'.$Config["pages"]["ext"],
							"nav_id" => $nav_id,
							"last_updated" => date("Y-m-d H:i:s"),
							"date_created{insert}" => date("Y-m-d H:i:s"),
							"page_status{insert}" => "0",
							);
							@mysql_query(MySQLInsert($FD,"pages"));
						}
						else {
						$error="Unable to create directory: ".$doc_root.$SubPath.$SubDir;
						}
					}
					else {
					$error="Directory already exists for: ".$doc_root.$SubPath.$SubDir;
					}
				}
				## password protect?
				if(isset($_POST["nav_username"]) && $_POST["nav_username"]!="" && isset($_POST["nav_password"]) && $_POST["nav_password"]!="") {
					$SubDir=(PrepareData("sub_dir")!="")?CreateFileName(PrepareData("sub_dir")):CreateFileName(PrepareData("sub_name"));
					$SubNav=array_reverse(GetRecursiveDir(PrepareData("sub_id")));
					$SubPath="";	
					for($x=0;$x<count($SubNav);$x++) {
					$SubPath.=$SubNav[$x]["nav_dir"]."/";
					}
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
			}
			if(!$error) {
			header("location: ".FCPATHNICE."?mod=".$_REQUEST["mod"]."&sub=".$_REQUEST["sub"]."&msg=".urlencode("Your changes have been saved"));
			exit;
			}
			else {
			DisplayTemplates();
			}
		}
		
		####################################
		## HOME DIRECTORY
		####################################
		
		elseif(isset($_REQUEST["homedir"]) && $_REQUEST["homedir"]!="") {
			if(!$MISSING = CheckMandatoryFields()) {
				if(PrepareData("nav_name")) {
					$NavDir=(PrepareData("nav_dir")!="")?CreateFileName(PrepareData("nav_dir")):CreateFileName(PrepareData("nav_name"));
					$FD=Array(
					"nav_name" => PrepareData("nav_name"),
					"nav_dir" => $NavDir,
					"last_updated" => date("Y-m-d H:i:s"),
					"date_created{insert}" => date("Y-m-d H:i:s")
					);
					if(!is_dir($doc_root.$NavDir)) {
					@umask(0000);
					@mkdir($doc_root.$NavDir);
						if(is_dir($doc_root.$NavDir)) {
							if(!mysql_query(MySQLInsert($FD,"pages_navigation"))) {
							$error="Oops! We reported an error: ".@mysql_error();
							}
							$nav_id=@mysql_insert_id();
							## create blank page
							@copy('404.html', $doc_root.$NavDir.'/index.html');
							$FD=Array(
							"page_name" => PrepareData("nav_name"),
							"page_file_name" => 'index'.$Config["pages"]["ext"],
							"nav_id" => $nav_id,
							"last_updated" => date("Y-m-d H:i:s"),
							"date_created{insert}" => date("Y-m-d H:i:s"),
							"page_status{insert}" => "0",
							);
							@mysql_query(MySQLInsert($FD,"pages"));
						}
						else {
						$error="Unable to create directory: ".$doc_root.$NavDir;
						}
					}
					else {
					$error="Directory already exists for: ".$doc_root.$NavDir;
					}
				}
				
				if(!$error) {
				header("location: ".FCPATHNICE."?mod=".$_REQUEST["mod"]."&sub=".$_REQUEST["sub"]."&msg=".urlencode("Your changes have been saved"));
				exit;
				}
				else {
				DisplayTemplates();
				}
			}
			else {
			DisplayTemplates();
			}
		}
	break;
	
	default:
		if(isset($_REQUEST["id"])) {
		$Details=MySQLArray("SELECT * FROM pages WHERE page_id='".trim($_REQUEST["id"])."'");
		}
		elseif(isset($_REQUEST["nav_id"])) {
		$Details=MySQLArray("SELECT * FROM pages_navigation WHERE nav_id='".trim($_REQUEST["nav_id"])."'");
		}
		DisplayTemplates();
	break;
}

function PublishPage($PageID) {
GLOBAL $mod,$CONFIG,$doc_root;
	$Details=MySQLArray("SELECT * FROM pages WHERE page_id='".$PageID."'");
	## check for home page
		$PublishDir=$doc_root.GetRecursiveDir($Details["nav_id"],"dir");
		if(is_dir($PublishDir)) {
			if($fp=@fopen($PublishDir.'/'.$Details["page_file_name"],"w")) {
				ob_start();
				eval("?".">".stripslashes(MySQLResult("SELECT template_html FROM pages_templates WHERE template_id='".$Details["template_id"]."'"))."<"."?");
				$HTML = ob_get_contents();
				ob_end_clean();
				
				$CONTENT=stripslashes(MySQLResult("SELECT page_html FROM pages WHERE page_id='".$PageID."'"));
				
				## replace form
				preg_match_all("/\{form_id=([0-9])\}/i",$CONTENT,$v);
				foreach($v[1] as $vr) {
				$CONTENT=ereg_replace("\{form_id=".$vr."\}","<?php \$_REQUEST[\"form_id\"]=$vr; include_once('".FCPATH."modules/pages/forms/_ajax/formhandler.php');?>",$CONTENT);
				}
				
				ob_start();
				eval("?".">".$CONTENT."<"."?");
				$CONTENT = ob_get_contents();
				ob_end_clean();
				
				$HTML=str_replace("{jump}",$CONTENT,$HTML);
				@fwrite($fp,$HTML);
				@fclose($fp);
				
				## symlink to this page if home page
				if($Details["is_home"]=="Y") {
				exec('ln -sf '.$PublishDir.'/'.$Details["page_file_name"].' '.SITEPATH.'/_index.html');
				}
			}
			else {
			$error="Unable to create file: ".$PublishDir.'/'.$Details["page_file_name"];
			}
		}
		else {
		$error="Unable to publish to: ".$PublishDir;
		}
		if(!$error) {
		
		}
	//}
return $error;
}

function PreviewPage($PageID) {
GLOBAL $mod,$CONFIG,$doc_root,$doc_root_nice;
	$Details=MySQLArray("SELECT * FROM pages WHERE page_id='".$PageID."'");
	if(!is_dir($doc_root.'Tmp')) {
		@umask(0000);
		if(!mkdir($doc_root.'Tmp')) {
		$error="unable to create temporary directory to write to";
		}
	}
	if(!$error) {
		$FileName=time().'.html';
		if($fp=@fopen($doc_root.'Tmp/'.$FileName,"w")) {
			##load up template
			ob_start();
			eval("?".">".stripslashes(MySQLResult("SELECT template_html FROM pages_templates WHERE template_id='".$Details["template_id"]."'"))."<"."?");
			$HTML = ob_get_contents();
			ob_end_clean();
			//$CONTENT=stripslashes(MySQLResult("SELECT page_html FROM pages WHERE page_id='".$PageID."'"));
			$CONTENT=stripslashes(PrepareHTML($Details));
			$HTML=str_replace("{jump}",$CONTENT,$HTML);
			@fwrite($fp,$HTML);
		@fclose($fp);
		}
		else {
		$error="unable to create page";
		}
	}
	if(!$error) {
	return $doc_root_nice."Tmp/".$FileName;
	}
	else {
	return $error;
	}
}

function GetRecursiveDir($SubID,$Display='') {
	if($SubID>0) {
	$NavDetails=MySQLArray("SELECT sub_id,nav_name,nav_dir FROM pages_navigation WHERE nav_id='".$SubID."'");
	$SubNav[]=array("sub_id"=>$NavDetails["sub_id"],"nav_name"=>$NavDetails["nav_name"],"nav_dir"=>$NavDetails["nav_dir"]);	
		if($NavDetails["sub_id"]>0) {
		$SubNav=array_merge($SubNav,GetRecursiveDir($NavDetails["sub_id"]));	
		}
	}
	if($Display=="dir") {
	$Dir='';
		if(count($SubNav)>0) {
			for($x=0;$x<count($SubNav);$x++) {
			$Dir.=$SubNav[$x]["nav_dir"].'/';
			}
		}
	$SubNav=$Dir;
	}
return $SubNav;
}

function PrepareHTML($Details) {
	$ReplaceWhatr=array("{page_title}","{page_keywords}","{page_description}");
	$ReplaceWith=array($Details["page_title"],$Details["page_keywords"],$Details["page_description"]);
	$content=str_replace($ReplaceWhat,$ReplaceWith,$Details["page_html"]);
return $content;
}
?>