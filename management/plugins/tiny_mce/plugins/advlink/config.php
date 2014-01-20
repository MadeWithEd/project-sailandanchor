<?php
$LoadCMS=false;
include_once($_SERVER["DOCUMENT_ROOT"]."/management/index.php");
include_once(FCPATH."core/config.inc.php");
include_once(FCPATH."core/functions.inc.php");
include_once(FCPATH."core/database.inc.php");

$siteUrl=$CONFIG['site']['url'];

// directory where tinymce files are located
$tinyMCE_dir = FCPATH.'plugins/tiny_mce';

// base url for images
$tinyMCE_base_url = '';

if (!ereg('/$', $HTTP_SERVER_VARS['DOCUMENT_ROOT']))
  $tinyMCE_root = $HTTP_SERVER_VARS['DOCUMENT_ROOT'].$tinyMCE_dir;
else
  $tinyMCE_root = $HTTP_SERVER_VARS['DOCUMENT_ROOT'].substr($tinyMCE_dir,1,strlen($tinyMCE_dir)-1);


$Config["docs"]["uploads_nice"]=SITEPATHNICE.$CONFIG['site']['user_uploads'].'/mediamanager/docs/';
$Config["docs"]["uploads"]=SITEPATH.$CONFIG['site']['user_uploads'].'/mediamanager/docs/';

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
?>
