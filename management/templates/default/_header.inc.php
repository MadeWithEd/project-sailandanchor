<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<title>Sail &amp; Anchor : Logged in Admin</title>
	<meta name="generator" content="BBEdit 7.0" />
	<link rel="stylesheet" href="<?=TEMPLATEPATHNICE;?>css/style.css" type="text/css" />
	<link rel="stylesheet" href="<?=TEMPLATEPATHNICE;?>css/ui-lightness/jquery-ui-1.8.15.custom.css" />
	<script language="javascript" src="<?=TEMPLATEPATHNICE;?>js/jquery-1.4.4.min.js"></script>
	<script language="javascript" src="<?=TEMPLATEPATHNICE;?>js/jquery-ui-1.8.15.custom.min.js"></script>
	<script language="javascript" src="<?=TEMPLATEPATHNICE;?>js/_functions.js"></script>
	
</head>

<body>
<div id="NonFooter">
	<div id="TopRow"></div>
	<div>
		<div style="width:600px;float:left;"><?=ShowTopModules();?></div>
		<div style="width:380px;float:right;text-align:right">
			<a href="?mod=profile"><img src="<?=TEMPLATEPATHNICE;?>images/bt_profile.gif" border="0" alt="" /></a>
			<img src="<?=TEMPLATEPATHNICE;?>images/spacer.gif" border="0" width="4" height="1" alt="" />
			<a href="?logout=true"><img src="<?=TEMPLATEPATHNICE;?>images/bt_logout.gif" border="0" alt="" /></a>
			<img src="<?=TEMPLATEPATHNICE;?>images/spacer.gif" border="0" width="4" height="1" alt="" />
		</div>
	</div>
	<div id="ContentContainer" style="clear:both;">
		<ul id="CookieTrail">
		<li>You are here: </li>
		<li><a href="<?=FCPATHNICE?>">Home</a></li>
		<?=ShowCookieTrail();?>
		</ul>
		<?php if(isset($_REQUEST["msg"]) && $_REQUEST["msg"]!="") { ?><div class="TextPrompt" id="MSGContainer"><?=$_REQUEST["msg"];?></div><script type="text/javascript"><!--
		$('#MSGContainer').animate({'opacity':0}, 3000, function() {$('#MSGContainer').hide();});
		//--></script><?php } ?>


		