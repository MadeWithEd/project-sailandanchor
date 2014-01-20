<?php
/**
 **
 ** Pro:cms
 ** Revision: 4.07.2007
 ** author: Scott Dilley
 ** version: 2.0
 **
 ** Main include file
 **
*/

if (!defined('BASEPATH')) exit('No direct script access allowed');

//echo $_SERVER["DOCUMENT_ROOT"];
## check for writeable directories
//$DirList=Array("core/config.inc.php");
$DirWriteableError=false;
//foreach($DirList as $dir) {
//	if(!is_writeable(FCPATH.$dir)) {
//		if(is_dir(FCPATH.$dir)) {
//		@chmod(FCPATH.$dir,"0777");
//		}
//		else {
//		@chmod(FCPATH.$dir,"0666");
//		}
//	}
//	if(!is_writeable(FCPATH.$dir)) {
//	$DirWriteableError[]=FCPATH.$dir;
//	}
//}

if($DirWriteableError) {
$heading="Configuration error";
$message="The following directories/files need to be made writeable. Please chmod to 777.<br /><br />";
	foreach($DirWriteableError as $dir) {
	$message.=$dir."<br />";
	}
$message.='<br /><a href="'.FCPATHNICE.'">Click here</a> to refresh the page once ready.';
include_once(FCPATH.'errors/error_general.php');
die;
}

## now grab main config file
require_once BASEPATH.'config.inc.php';

## grab some important files
require_once BASEPATH.'functions.inc.php';


if(!isset($CONFIG)) {
include(BASEPATH.'config.setup.inc.php');
include(FCPATH.'errors/error_config.php');
die;
}

## connect to db
require_once BASEPATH.'database.inc.php';

## Now look for libraries to load
require_once BASEPATH.'autoload.inc.php';

## ok now authenticate
require_once BASEPATH.'authenticate.inc.php';

## check for mod to load
$mod_to_load=false;
$mod_html_safe=false;$mod_php_safe=false;
if(isset($_REQUEST["mod"])) {
	## check perms & test module
	$mod_to_load=true;
	$mod_html=$_REQUEST["mod"].'.html.php';
	$mod_php=$_REQUEST["mod"].'.php';
	$mod_config=$_REQUEST["mod"].'.config.php';
	$mod_path='modules/'.$_REQUEST["mod"].'/';
	if(is_file(FCPATH.$mod_path.$mod_html)) {
	$mod_html_safe=true;
	}
	if(is_file(FCPATH.$mod_path.$mod_php)) {
	$mod_php_safe=true;
	}
}

## Render page
if(!isset($_REQUEST["Hideoutput"])) {
ob_start();
include_once(TEMPLATEPATH.'_header.inc.php');

## load modules
	if($mod_to_load) {
		if($mod_html_safe && $mod_php_safe) {
			if(is_file(FCPATH.$mod_path.$mod_config)) {
			require_once(FCPATH.$mod_path.$mod_config);
			}
				## look for config options
				if(isset($Config["options"]["sublevel"])) {
					if(isset($_REQUEST["sub"])) {
					$sub_path=$_REQUEST["sub"].'/';
					$sub_html=$_REQUEST["sub"].'.html.php';
					$sub_php=$_REQUEST["sub"].'.php';
					if(isset($Config[$_REQUEST["sub"]])) {
					GenerateSubMenu($Config[$_REQUEST["sub"]]);
					}	
						if(is_file(FCPATH.$mod_path.$sub_path.$sub_html)) {
						require_once(FCPATH.$mod_path.$sub_path.$sub_html);
						}
						if(is_file(FCPATH.$mod_path.$sub_path.$sub_php)) {
						include_once(FCPATH.$mod_path.$sub_path.$sub_php);
						}
					}
					else {
						## display sublevels
						$x=1;
						echo '<h1>'.$Config["options"]["modname"].'</h1>';
						echo '<div id="ContentLeft">';
						foreach($Config["options"]["sublevel"] as $key=>$val) {
							$sub_path=strtolower($val).'/';
							echo '<div class="ModuleView"><a href="?mod='.$_REQUEST["mod"].'&sub='.strtolower($val).'">';
							if(is_file(FCPATH.$mod_path.$sub_path.strtolower($val).'.gif') || is_file(FCPATH.$mod_path.$sub_path.strtolower($val).'.png')) {
							$ext=(is_file(FCPATH.$mod_path.$sub_path.strtolower($val).'.gif'))?'.gif':'.png';
							echo '<img src="'.FCPATHNICE.$mod_path.$sub_path.strtolower($val).$ext.'" border="0" alt="" vspace="8" /><br />';
							}
							echo stripslashes($val);
							echo '</a></div>';
							if($x==4) {
							$x=0;
							echo '<div style="clear:both;height:10px;"></div>';
							}
						$x++;
						}
						echo '</div>';
						
						echo '<div id="ContentRight">';
						require_once(FCPATH.$mod_path.$mod_html);
						include_once(FCPATH.$mod_path.$mod_php);
						echo '</div>';
					}
				}
				else {
					if(isset($Config["options"]["label"])) {
					GenerateSubMenu($Config);
					}
				require_once(FCPATH.$mod_path.$mod_html);
				include_once(FCPATH.$mod_path.$mod_php);
				}
			
		
		}
		else {
		$heading="Module loading error";
		$message="We were unable to load the module ".$_REQUEST["mod"].".";
		include_once(FCPATH.'errors/error_php.php');
		}
	}
	else {
		echo '<h1>Sail &amp; Anchor:cms</h1>';
		echo '<div id="ContentLeft">';
		## load modules
		$x=1;
		$msql=@mysql_query("SELECT * FROM CMS_modules ORDER BY sort_order ASC");
		while($mrow=@mysql_fetch_array($msql)) {
			if(@in_array($mrow["mod_id"],$UserAccess)) {
			echo '<div class="ModuleView"><a href="?mod='.$mrow["mod_short_name"].'">';
				if(is_file(FCPATH.'modules/'.$mrow["mod_short_name"].'/'.$mrow["mod_short_name"].'.gif') || is_file(FCPATH.'modules/'.$mrow["mod_short_name"].'/'.$mrow["mod_short_name"].'.png')) {
				$ext=(is_file(FCPATH.'modules/'.$mrow["mod_short_name"].'/'.$mrow["mod_short_name"].'.gif'))?'.gif':'.png';
				echo '<img src="'.FCPATHNICE.'/modules/'.$mrow["mod_short_name"].'/'.$mrow["mod_short_name"].$ext.'" border="0" alt="" vspace="8" /><br />';
				}
				echo stripslashes($mrow["mod_name"]);
			echo '</a></div>';
				if($x==4) {
				$x=0;
				echo '<div style="clear:both;height:10px;"></div>';
				}
			$x++;
			}
		}
		echo '</div>';
		
		echo '<div id="ContentRight">';
		include_once('dashboard.inc.php');
		echo '</div>';
	}

include_once(TEMPLATEPATH.'_footer.inc.php');
ob_end_flush();
}
else {
	if($mod_html_safe && $mod_php_safe) {
		if(is_file(FCPATH.$mod_path.$mod_config)) {
		require_once(FCPATH.$mod_path.$mod_config);
		}
	require_once(FCPATH.$mod_path.$mod_html);
	include_once(FCPATH.$mod_path.$mod_php);
	}
}
?>