<?php
function DisplayUserTypes() {
	$WHERE="";
	$SD["table"]["width"]="100%";
	$SD["table"]["border"]="0";
	$SD["table"]["cellpadding"]="0";
	$SD["table"]["cellspacing"]="0";
	$SD["table"]["class"]="0";
	$SD["header"]["class"]="RowHeader";
	$SD["header"]["divider"]="SpacerRow";
	$SD["header"]["link"]["class"]="RowHeader";
	$SD["header"]["fields"]["title"]=array("Usertype" ,"# Users" ,"");
	$SD["header"]["fields"]["name"]=array("usertype" ,"usertype_id" ,"");
	$SD["header"]["fields"]["width"]=array("" ,"120" ,"30");
	$SD["header"]["fields"]["content"]=array("{usertype}" ,"#s- SELECT count(*) FROM CMS_users WHERE usertype_id='{usertype_id}' -s#" ,"<a href=\"".FCPATHNICE."?mod=".$_REQUEST["mod"]."&sub=".$_REQUEST["sub"]."&task=create&id={usertype_id}\"><img src=\"".TEMPLATEPATHNICE."images/ico_edit.gif\" border=\"0\" alt=\"\" /></a>");
	$SD["query"]["select"]="select *";
	$SD["query"]["from"]="CMS_usertypes";
	$SD["query"]["where"]="1";
	$SD["data"]["class"]="DataRow";
	?>
	<h1>Displaying Users</h1>
	<form name="FormDisplay">
	<?=ShowDisplayHeader($SD); ?>
	</form>
	<?php
}

function DisplayUserTypeForm($Details) {
	?>
	<h1><?=($_REQUEST["id"]>0)?'Edit '.$Details["usertype"]:'Add new usertype';?></h1>
	<?=DisplayError();?>
	<form name="Form" method="post" action="<?=$_SERVER["PHP_SELF"];?>">
	<input type="hidden" name="id" value="<?php if(isset($_REQUEST["id"]) && $_REQUEST["id"]>0) { echo $_REQUEST["id"]; } ?>" />
	<input type="hidden" name="mandatory" value="usertype,access" />
	<input type="hidden" name="mod" value="<?=$_REQUEST["mod"];?>" />
	<input type="hidden" name="sub" value="<?=$_REQUEST["sub"];?>" />
	<input type="hidden" name="task" value="save" />
		
	<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
	<td width="140" class="Text10" align="left">Usertype <?=ShowMandatory("usertype");?></td>
	<td class="Text10"><input type="text" size="30" name="usertype" value="<?php if(isset($_POST["usertype"])) { echo $_POST["usertype"]; } else { echo @stripslashes($Details["usertype"]); } ?>" /></td>
	</tr>
		
	<tr>
	<td class="Text10" align="left" valign="top">Allow access to <?=ShowMandatory("access");?></td>
	<td class="Text10"><?php
	$sql=mysql_query("SELECT * FROM CMS_modules WHERE is_active='Y' AND sub_id='0' ORDER BY sort_order");
	while($row=mysql_fetch_array($sql)) {
	?>
	<input type="checkbox" name="access[]" value="<?php echo $row["mod_id"]; ?>"<?= ShowChecked($row["mod_id"],"access",$Details); ?> /> <?php echo $row["mod_name"]; ?><br />
	<?php
		## select subs
		$sql2=@mysql_query("SELECT * FROM CMS_modules WHERE sub_id='".$row["mod_id"]."' AND is_active='Y' ORDER BY sort_order");
		while($row2=@mysql_fetch_array($sql2)) {
		?>
		&nbsp;&nbsp;&nbsp;<input type="checkbox" name="access[]" value="<?php echo $row2["mod_id"]; ?>"<?=ShowChecked($row2["mod_id"],"access",$Details); ?> /> <?php echo $row2["mod_name"]; ?><br />
		<?php
		}
	}
	?></td>
	</tr>
		
	<?php if(isset($_REQUEST["id"])) { ?>
	<tr>
	<td class="Text10" align="left">Status</td>
	<td class="Text10" align="left"><input type="radio" name="ut_status" value="1"<?= ShowChecked("1","ut_status",$Details); ?> /> Active 
	<input type="radio" name="ut_status" value="3" onclick="alert('Before you remove this user type please make sure there are no users assigned to this type.');" /> Remove</td>
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