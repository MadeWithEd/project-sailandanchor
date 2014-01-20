<?php
function DisplayOthers() {
GLOBAL $search,$CONFIG,$Config,$_REQUEST,$orderby;
	
	$WHERE='';
	$SD["table"]["width"]="100%";
	$SD["table"]["border"]="0";
	$SD["table"]["cellpadding"]="0";
	$SD["table"]["cellspacing"]="0";
	$SD["table"]["class"]="0";
	$SD["header"]["class"]="RowHeader";
	$SD["header"]["divider"]="SpacerRow";
	$SD["header"]["link"]["class"]="RowHeader";
	$SD["header"]["fields"]["title"]=array("","Name","Suburb","Postcode","State","");
	$SD["header"]["fields"]["name"]=array("","store_name","store_suburb","store_postcode","store_state","");
	$SD["header"]["fields"]["width"]=array("20","180","","150","150","30");
	$SD["header"]["fields"]["content"]=array("","{store_name}","{store_suburb}","{store_postcode}r","{store_state}","<a href=\"".FCPATHNICE."?mod=".$_REQUEST["mod"]."&task=create&id={store_id}\"><img src=\"".TEMPLATEPATHNICE."images/ico_edit.gif\" border=\"0\" alt=\"\" /></a>");
	$SD["query"]["select"]="select *";
	$SD["query"]["from"]="stores";
	$SD["query"]["where"]="1 $WHERE";
	$SD["data"]["class"]="DataRow";
	$SD["extra"]["buttons"]["label"]=array("Download CSV");
	$SD["extra"]["buttons"]["onclick"]=array("doDownloadAll('&','".FCPATHNICE."?Hideoutput=1&mod=".$_REQUEST["mod"]."&task=download');");
	$SD["extra"]["buttons"]["class"]=array("130");
	$SD["extra"]["buttons"]["id"]=array("Sl");
	?>
			
	<h1>Displaying Stores</h1>
			
	<?=ShowDisplayHeader($SD); ?>
	
<?php
}

function DisplayPageForm($Details) {
GLOBAL $Config;
?>
	<style type="text/css"><!--
	#Form DIV Label {
		display:-moz-inline-stack;
		display:inline-block;
		zoom:1;
		*display:inline;
		float:left;
		width:150px;
	}
	
	#Form DIV {
		margin:5px 0 5px 0;
	}
	
	#GuestCol {
		width:49%;
		float:left;
	}
	
	#MemberCol {
		width:49%;
		float:left;
	}
	
	#MidCol {
		float:left;
		width:10px;
	}
	//--></style>
	<script>
	$('#SubMenu li:eq(2) a').addClass('current');
	</script>
	
	<h1><?=($_REQUEST["id"]>0)?'Editing '.stripslashes($Details["store_name"]):'Add New Store';?></h1>
	
	<?=DisplayError();?>
	<form name="Form" id="Form" method="post" action="<?=$_SERVER["PHP_SELF"];?>" enctype="multipart/form-data">
	<input type="hidden" name="id" value="<?php if(isset($_REQUEST["id"]) && $_REQUEST["id"]!="") { echo $_REQUEST["id"]; } ?>" />
	<input type="hidden" name="mod" value="<?=$_REQUEST["mod"];?>" />
	<input type="hidden" name="task" value="save" />
	<input type="hidden" name="mandatory" value="store_name" />
		
		<div id="Controls">
			<div><input type="image" src="<?=TEMPLATEPATHNICE.'images/save_f2.png';?>" alt="" name="save" /><br />save</div>
			<div><input type="image" src="<?=TEMPLATEPATHNICE.'images/cancel_f2.png';?>" alt="" name="cancel" /><br />cancel</div>
			<?php if($_REQUEST["id"]>0) { ?><div><input type="image" src="<?=TEMPLATEPATHNICE.'images/delete_f2.png';?>" border="0" alt="" name="remove" onclick="if(!confirm('Warning! Clicking OK will permanently remove this page')) { return false; }" /><br />delete</div><?php } ?>
		</div>
		<div style="clear:both;">&nbsp;</div>
		
		<h2>Details</h2>
		
		<div<?=HighlightMandatory("store_name");?>>
			<label>Name</label>
			<input type="text" name="store_name" id="store_name" size="30" value="<?=ShowDataText("store_name");?>" />
		</div>
		<div<?=HighlightMandatory("store_street");?>>
			<label>Street</label>
			<input type="text" name="store_street" id="store_street" size="30" value="<?=ShowDataText("store_street");?>" />
		</div>
		<div<?=HighlightMandatory("store_suburb");?>>
			<label>Suburb</label>
			<input type="text" name="store_suburb" id="store_suburb" size="30" value="<?=ShowDataText("store_suburb");?>" />
		</div>
		<div<?=HighlightMandatory("store_postcode");?>>
			<label>Postcode</label>
			<input type="text" name="store_postcode" id="store_postcode" size="30" value="<?=ShowDataText("store_postcode");?>" />
		</div>
		<div<?=HighlightMandatory("store_state");?>>
			<label>State</label>
			<select name="store_state" id="store_state">
			<?=PopulateSelectState(array("NSW","ACT","SA","WA","VIC","TAS","QLD","NT"),ShowDataText("store_state"));?>
			</select>
		</div>
		<div<?=HighlightMandatory("store_phone");?>>
			<label>Phone</label>
			<input type="text" name="store_phone" id="store_phone" size="30" value="<?=ShowDataText("store_phone");?>" />
		</div>
		<div<?=HighlightMandatory("store_email");?>>
			<label>Email</label>
			<input type="text" name="store_email" id="store_email" size="30" value="<?=ShowDataText("store_email");?>" />
		</div>
		<div<?=HighlightMandatory("store_lat");?>>
			<label>Lattitude</label>
			<input type="text" name="store_lat" id="store_lat" size="30" value="<?=ShowDataText("store_lat");?>" />
		</div>
		<div<?=HighlightMandatory("store_lng");?>>
			<label>Longitude</label>
			<input type="text" name="store_lng" id="store_lng" size="30" value="<?=ShowDataText("store_lng");?>" />
		</div>
	</form>
	<?php
}


function DisplaySearch() {
?>
	<style type="text/css"><!--
	#Form DIV Label {
		display:-moz-inline-stack;
		display:inline-block;
		zoom:1;
		*display:inline;
		float:left;
		width:150px;
	}
	
	#Form DIV {
		margin:5px 0 5px 0;
	}
	
	#GuestCol {
		width:49%;
		float:left;
	}
	
	#MemberCol {
		width:49%;
		float:left;
	}
	
	#MidCol {
		float:left;
		width:10px;
	}
	//--></style>
	<script>
	$('#SubMenu li:eq(1) a').addClass('current');
	</script>
	
<h1>Contacts &gt; Search</h1>
	<form name="Form" id="Form" method="get" action="<?=$_SERVER["PHP_SELF"];?>">
	<input type="hidden" name="mod" value="<?=$_REQUEST["mod"];?>" />
	<input type="hidden" name="search" value="search" />
	<input type="hidden" name="task" value="other" />
	
		<div id="Controls">
			<div><input type="image" src="<?=TEMPLATEPATHNICE.'images/save_f2.png';?>" alt="" name="search" /><br />search</div>
			<div><input type="image" src="<?=TEMPLATEPATHNICE.'images/cancel_f2.png';?>" alt="" name="cancel" /><br />cancel</div>
		</div>
		<div style="clear:both;">&nbsp;</div>
	
		<div>
			<label>Name </label>
			<input type="text" size="30" name="name" value="<?= ShowDataText("name"); ?>" />
		</div>
		<div>
			<label>Contact type</label>
			<select name="contact_type[]" multiple="multiple" size="5">
			<?=PopulateSelectState(array("Staff","Health Practitioner","Health Fund","Customer","Pregnancy","cpap","Other"),ShowDataText("contact_type"));?>
			</select>
		</div>
		<div>
			<label>Business name </label>
			<input type="text" size="30" name="business_name" value="<?= ShowDataText("business_name"); ?>" />
		</div>
		<div>
			<label>Business postcode </label>
			<input type="text" size="10" name="business_postcode" value="<?= ShowDataText("business_postcode"); ?>" />
		</div>
		<div>
			<label>State</label>
			<select name="business_state">
			<option value="">- optional -</option>
			<?=PopulateSelectState(array("NSW","ACT","WA","QLD","SA","WA","NT","QLD","TAS"),ShowDataText("business_state"));?>
			</select>
		</div>
		
	</form>
<?php
}

function DisplayOptions() {
?>
<div style="position:relative;background:#fff;padding:20px;width:250px;" id="SaleOptions">
	
	<a href="javascript:;" id="Close" style="position:absolute;top:-10px;right:-10px;width:30px;height:30px;"><img src="/management/modules/messages/bt_close-popup.png" border="0" /></a>
	
	Options for: <?=MySQLResult("SELECT first_name FROM contacts WHERE contact_id='".$_REQUEST["id"]."'");?>
	
	<a class="link" href="/management/?mod=contacts&task=create&id=<?=$_REQUEST["id"];?>">View/Edit Contact</a>
	
	
</div>
<script>
$('#Close').click(function(){
	$('#popup').hide();
	$("#popup").css('z-index','1');
	$('#popup').html('');
	$("#LightboxPanel").fadeOut(500);
	showFields();
	$('TD.DataRow').parent().css('background','none');
return false;
});
</script>
<?php
}
?>
