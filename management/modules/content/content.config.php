<?php
// setup
$Config["options"]["label"][0]="Browse";
$Config["options"]["link"][0]="&v=browse";
$Config["options"]["label"][1]=(isset($_REQUEST["id"]) && $_REQUEST["id"]>0)?"Edit":"Create";
$Config["options"]["link"][1]=(isset($_REQUEST["id"]) && $_REQUEST["id"]>0)?"&task=create&id=".$_REQUEST["id"]:"&task=create";

//set uploads directory
$Config["content"]["images"]=$_SERVER["DOCUMENT_ROOT"].'/uploads/';
$Config["content"]["images_nice"]='/uploads/';

$Config["status"]=array("Draft","Live","Archived");

?>