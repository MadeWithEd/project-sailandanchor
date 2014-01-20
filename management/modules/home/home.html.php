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
	$SD["header"]["fields"]["title"]=array("","Name","Url (Optional)","");
	$SD["header"]["fields"]["name"]=array("","promo_name","promo_url","");
	$SD["header"]["fields"]["width"]=array("20","180","","30");
	$SD["header"]["fields"]["content"]=array("","{promo_name}","{promo_url}","<a href=\"".FCPATHNICE."?mod=".$_REQUEST["mod"]."&task=create&id={promo_id}\"><img src=\"".TEMPLATEPATHNICE."images/ico_edit.gif\" border=\"0\" alt=\"\" /></a>");
	$SD["query"]["select"]="select *";
	$SD["query"]["from"]="home_promos";
	$SD["query"]["where"]="1 $WHERE";
	$SD["data"]["class"]="DataRow";
	//$SD["extra"]["buttons"]["label"]=array("Download CSV");
	//$SD["extra"]["buttons"]["onclick"]=array("doDownloadAll('&','".FCPATHNICE."?Hideoutput=1&mod=".$_REQUEST["mod"]."&task=download');");
	//$SD["extra"]["buttons"]["class"]=array("130");
	//$SD["extra"]["buttons"]["id"]=array("Sl");
	?>
			
	<h1>Displaying Carousels</h1>
			
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
	
	<h1><?=($_REQUEST["id"]>0)?'Editing '.$Details["promo_name"]:'Add New Carousel';?></h1>
	
	<?=DisplayError();?>
	<form name="Form" id="Form" method="post" action="<?=$_SERVER["PHP_SELF"];?>" enctype="multipart/form-data">
	<input type="hidden" name="id" value="<?php if(isset($_REQUEST["id"]) && $_REQUEST["id"]!="") { echo $_REQUEST["id"]; } ?>" />
	<input type="hidden" name="mod" value="<?=$_REQUEST["mod"];?>" />
	<input type="hidden" name="task" value="save" />
	<input type="hidden" name="mandatory" value="promo_name" />
		
		<div id="Controls">
			<div><input type="image" src="<?=TEMPLATEPATHNICE.'images/save_f2.png';?>" alt="" name="save" /><br />save</div>
			<div><input type="image" src="<?=TEMPLATEPATHNICE.'images/cancel_f2.png';?>" alt="" name="cancel" /><br />cancel</div>
			<?php if($_REQUEST["id"]>0) { ?><div><input type="image" src="<?=TEMPLATEPATHNICE.'images/delete_f2.png';?>" border="0" alt="" name="remove" onclick="if(!confirm('Warning! Clicking OK will permanently remove this page')) { return false; }" /><br />delete</div><?php } ?>
		</div>
		<div style="clear:both;">&nbsp;</div>
		
		<h2>Details</h2>
		
		<div<?=HighlightMandatory("promo_name");?>>
			<label>Name</label>
			<input type="text" name="promo_name" id="promo_name" size="30" value="<?=ShowDataText("promo_name");?>" />
		</div>
		<div<?=HighlightMandatory("promo_url");?>>
			<label>URL (opt)</label>
			<input type="text" name="promo_url" id="promo_url" size="30" value="<?=ShowDataText("promo_url");?>" />
		</div>
		<div class="row">
			<label>Promo image</label>
			<input type="file" name="promo_image" value="" /> <span style="font-size:10px;">JPG,PNG 852px X 425px</span>
		</div>
		<?php if($Details["promo_image"]!="" && is_file($Config["content"]["images"].$Details["promo_image"])) { ?>
			<img src="<?=$Config["content"]["images_nice"].$Details["promo_image"];?>" border="0" alt="" style="display:block;" />
		<?php } ?>
		
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
