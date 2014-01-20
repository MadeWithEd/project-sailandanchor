<?php
// setup
$Config["options"]["modname"]="User Admin";

$Config["options"]["sublevel"][0]="Users";
$Config["options"]["sublevel"][1]="Usertypes";

$Config["users"]["options"]["label"][0]="Search";
$Config["users"]["options"]["link"][0]="&v=search";
$Config["users"]["options"]["label"][1]="Browse";
$Config["users"]["options"]["link"][1]="&v=browse";
$Config["users"]["options"]["label"][2]=(isset($_REQUEST["id"]) && $_REQUEST["id"]>0)?"Edit":"Create";
$Config["users"]["options"]["link"][2]=(isset($_REQUEST["id"]) && $_REQUEST["id"]>0)?"&task=create&id=".$_REQUEST["id"]:"&task=create";

$Config["usertypes"]["options"]["label"][0]="Browse";
$Config["usertypes"]["options"]["link"][0]="&v=browse";
$Config["usertypes"]["options"]["label"][1]=(isset($_REQUEST["id"]) && $_REQUEST["id"]>0)?"Edit":"Create";
$Config["usertypes"]["options"]["link"][1]=(isset($_REQUEST["id"]) && $_REQUEST["id"]>0)?"&task=create&id=".$_REQUEST["id"]:"&task=create";
?>