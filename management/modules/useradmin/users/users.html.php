<?php
function DisplayUsers() {
	$WHERE="";
	$SD["table"]["width"]="100%";
	$SD["table"]["border"]="0";
	$SD["table"]["cellpadding"]="0";
	$SD["table"]["cellspacing"]="0";
	$SD["table"]["class"]="0";
	$SD["header"]["class"]="RowHeader";
	$SD["header"]["divider"]="SpacerRow";
	$SD["header"]["link"]["class"]="RowHeader";
	$SD["header"]["fields"]["title"]=array("Name" ,"Email" ,"Access type" ,"Status" ,"Last logged in" ,"");
	$SD["header"]["fields"]["name"]=array("u_name" ,"u_email" ,"usertype" ,"u_status" ,"last_login" ,"");
	$SD["header"]["fields"]["width"]=array("160" ,"" ,"140" ,"100" ,"130" ,"30");
	$SD["header"]["fields"]["content"]=array("{u_name}" ,"{u_email}" ,"{usertype}" ,"#- if({u_status}=='1') { echo 'Active'; } elseif({u_status}=='2') { echo 'Archived'; } -#" ,"#s- SELECT last_login FROM CMS_logins WHERE user_id='{user_id}' ORDER BY last_login DESC LIMIT 1 -s#" ,"<a href=\"".FCPATHNICE."?mod=".$_REQUEST["mod"]."&sub=".$_REQUEST["sub"]."&task=create&id={user_id}\"><img src=\"".TEMPLATEPATHNICE."images/ico_edit.gif\" border=\"0\" alt=\"\" /></a>");
	$SD["query"]["select"]="select *";
	$SD["query"]["from"]="CMS_users AS a, CMS_usertypes AS b";
	$SD["query"]["where"]="a.usertype_id=b.usertype_id $WHERE";
	$SD["data"]["class"]="DataRow";
	?>
	<h1>Displaying Users</h1>
	<form name="FormDisplay">
	<?=ShowDisplayHeader($SD); ?>
	</form>
	<?php
}

function DisplayUserSearch() {
	?>
	<h1>Search for a user</h1>
	<form name="Form" method="post" action="<?=$_SERVER["PHP_SELF"];?>">
	<input type="hidden" name="mod" value="<?=$_REQUEST["mod"];?>" />
	<input type="hidden" name="sub" value="<?=$_REQUEST["sub"];?>" />
	<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
	<td colspan="2" class="Text10">Use the fields below to search the database for specific users.<br /><br /></td>
	</tr>
			
	<tr>
	<td width="140" class="Text10" align="left">Full name</td>
	<td class="Text10"><input type="text" size="30" name="u_name" /></td>
	</tr>
		
	<tr>
	<td class="Text10" align="left">Email address</td>
	<td class="Text10"><input type="text" size="30" name="u_email" /></td>
	</tr>
			
	<tr>
	<td></td>
	<td><br /><input type="submit" value="Search" name="search" /></td>
	</tr>
	
	</table>
	</form>
	<?php
}

function DisplayUserForm($Details) {
	?>
	<h1><?=($_REQUEST["id"]>0)?'Edit '.$Details["u_name"]:'Add new user';?></h1>
	<?=DisplayError();?>
	<form name="Form" method="post" action="<?=$_SERVER["PHP_SELF"];?>">
	<input type="hidden" name="id" value="<?php if(isset($_REQUEST["id"]) && $_REQUEST["id"]>0) { echo $_REQUEST["id"]; } ?>" />
	<input type="hidden" name="mandatory" value="u_name,u_email,usertype_id,u_pass,u_pass2" />
	<input type="hidden" name="mod" value="<?=$_REQUEST["mod"];?>" />
	<input type="hidden" name="sub" value="<?=$_REQUEST["sub"];?>" />
	<input type="hidden" name="task" value="save" />
		
	<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
	<td width="140" class="Text10" align="left">Full name <?= ShowMandatory("u_name"); ?></td>
	<td class="Text10"><input type="text" size="30" name="u_name" value="<?= ShowDataText("u_name"); ?>" /></td>
	</tr>
		
	<tr>
	<td class="Text10" align="left">Email address <?= ShowMandatory("u_email"); ?></td>
	<td class="Text10"><input type="text" size="30" name="u_email" value="<?= ShowDataText("u_email"); ?>" /></td>
	</tr>
				
	<tr>
	<td class="Text10" align="left">Password <?= ShowMandatory("u_pass"); ?></td>
	<td class="Text10" align="left"><input type="password" size="30" name="u_pass" value="<?= ShowDataText("u_pass"); ?>" /></td>
	</tr>
	
	<tr>
	<td class="Text10" align="left">Confirm password <?= ShowMandatory("u_pass2"); ?></td>
	<td class="Text10" align="left"><input type="password" size="30" name="u_pass2" value="<?= ShowDataText("u_pass"); ?>" /></td>
	</tr>
		
	<tr>
	<td colspan="2"><div class="TextPromptGrey"><b>Access Type</b> Using the options below you can assign this person to access to specific areas of this cms. These access rights 
	can be administered via the Usertypes module.</div></td>
	</tr>
						
	<tr>
	<td class="Text10" align="left">Access type <?= ShowMandatory("u_name"); ?></td>
	<td class="Text10" align="left"><?php
	$sql=mysql_query("SELECT * FROM CMS_usertypes ORDER BY usertype_id ASC");
	while($row=mysql_fetch_array($sql)) {
	?><input type="radio" name="usertype_id" value="<?= $row["usertype_id"]; ?>" <?=ShowChecked($row["usertype_id"],"usertype_id",$Details); ?> /> <?= $row["usertype"]; ?> <?php
	}
	?></td>
	</tr>
	
	<?php if($_REQUEST["id"]) { ?>
	<tr>
	<td class="Text10">&nbsp;</td>
	</tr>
		
	<tr>
	<td class="Text10" align="left">Status</td>
	<td class="Text10" align="left"><input type="radio" name="u_status" value="1"<?php ShowChecked("1","u_status",$Details); ?> /> Active 
	<input type="radio" name="u_status" value="2"<?php ShowChecked("2","u_status",$Details); ?> /> Archived 
	<input type="radio" name="u_status" value="3" onclick="alert('This will permanently remove this user from the database.');" /> Remove</td>
	</tr>
	<?php } ?>
		
	<tr>
	<td></td>
	<td align="left"><br /><input type="button" value="Back" onClick="history.go(-1);" class="Button" />&nbsp;<input type="submit" value="Save" class="Button" /></td>
	</tr>
		
	</table>
	</form>
	<?php
}
?>