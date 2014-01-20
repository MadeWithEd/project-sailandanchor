<?php
function DisplayTemplates() {
?>
<style type="text/css">
UL {margin:0;padding:0;}
UL#SiteMapRoot { }
UL#SiteMapRoot LI {list-style-type:none;border-top:1px dotted #000;line-height:30px;clear:both;}
LI.folder {list-style-type:none;background:transparent url(smodules/content/images/folder.gif) no-repeat left 10px;padding-left:20px;}
LI.page {list-style-type:none;background:transparent url(smodules/content/images/page.gif) no-repeat left 50%;padding-left:20px;}
LI.home {list-style-type:none;background:transparent url(smodules/content/images/homepage.png) no-repeat left 50%;padding-left:20px;}
SPAN.icon {display:block;float:left;width:20px;padding-top:10px;}
SPAN.pagename {display:block;float:left;width:auto;}
SPAN.status {display:block;float:right;width:130px;}
SPAN.order {display:block;float:right;width:130px;}
SPAN.modify {display:block;float:right;width:130px;}
SPAN.lastupdated {display:block;float:right;width:170px;}
SPAN.view {display:block;float:right;width:40px;}
</style>
	
	<h1>Content</h1>
	
	<ul id="SiteMapRoot">
	<li>
		<span class="pagename"><b>Page</b></span>
		<span class="view">&nbsp;</span>
		<span class="lastupdated"><b>Last updated</b></span>
		<span class="modify"><b>Modify</b></span>
		<span class="order"><b>Order</b></span>
		<span class="status"><b>Status</b></span>
	</li>
	
	<?= DisplaySiteDirectories();?>
	
	</ul>
<?php
}

#################################################
## Function for displaying the sitemap
#################################################

function DisplaySiteDirectories($page_id='') {
GLOBAL $search,$CONFIG,$Config,$_REQUEST,$orderby;
$HTML='';
$x=1;
	$WHERE=($page_id>0)?' AND sub_id='.$page_id:' AND sub_id=0';
	$sql=execute_query("SELECT * FROM pages WHERE 1 $WHERE ORDER BY sort_order ASC");
	foreach($sql as $row) {
		
		$HasPages=MySQLResult("SELECT count(*) FROM pages WHERE sub_id='".$row["page_id"]."'");
		$ListClass=($HasPages)?'folder':'page';
		if($row["is_home"]=="Y") { $ListClass='home'; }
		
		$HTML.='<li class="'.$ListClass.'">';
		
			if($row["is_home"]=="Y") { $img='homepage.png';}
			else if($HasPages) { $img='folder.gif'; }
			else { $img='page.gif'; }
		
		$HTML.='
			<span class="icon"><a href="'.FCPATHNICE.'?mod='.$_REQUEST["mod"].'&task=create&id='.$row["page_id"].'"><img src="modules/content/images/'.$img.'" border="0" alt="" width="16" /></a></span>
			<span class="pagename">'.stripslashes($row["page_name"]).'</span>
			<span class="view"><a href="/'.$row["page_file_name"].'" target="_blank"><img src="'.TEMPLATEPATHNICE.'images/ico_preview.gif" border="0" alt="" /></a></span>
			<span class="lastupdated">'.date("d-m-y / H:m",mktime(substr($row["last_updated"],11,2),substr($row["last_updated"],14,2),0,substr($row["last_updated"],5,2),substr($row["last_updated"],8,2),substr($row["last_updated"],0,4))).'</span>
			<span class="modify"><a href="'.FCPATHNICE.'?mod='.$_REQUEST["mod"].'&task=create&id='.$row["page_id"].'"><img src="'.TEMPLATEPATHNICE.'images/ico_edit.gif" border="0" alt="" /></a></span>
			<span class="order">';
			$HTML.=($HasPages)?'<a href="'.FCPATHNICE.'?mod='.$_REQUEST["mod"].'&task=moveup&id='.$row["page_id"].'">^</a>':'<a href="'.FCPATHNICE.'?mod='.$_REQUEST["mod"].'&task=moveup&id='.$row["page_id"].'">^</a>';
			$HTML.='</span>
			<span class="status">'.$Config["status"][$row["page_status"]].'</span>
			<span style="clear:both"></span>
			';
		
			if($HasPages>0) {
			$HTML.='<ul>';
			$HTML.=DisplaySiteDirectories($row["page_id"]);
			$HTML.='</ul>';
			}
		
			
		$HTML.='</li>';
		
		
		$x++;
		
	}
return $HTML;
}

function DisplayTemplateForm($Details) {
GLOBAL $Config;
$IsSecondLevel=false;
	if($Details["page_id"]>0) {
	$sql=execute_query("SELECT page_id,sub_id FROM pages WHERE sub_id='".$Details["page_id"]."'");
		if(count($sql)>0) {	
			foreach($sql as $row) {
				if(MySQLResult("SELECT count(*) FROM pages WHERE sub_id='".$row["page_id"]."'")>0) {
				$IsSecondLevel=true;
				}
			}
		}
	}
?>
	<script language="javascript" type="text/javascript" src="modules/content/js/jquery.js"></script>
	<script language="javascript" type="text/javascript" src="modules/content/js/mctabs.js"></script>
	<script language="javascript" type="text/javascript" src="modules/content/js/calendarDateInput.js"></script>
	<script language="javascript" type="text/javascript" src="<?=FCPATHNICE;?>plugins/tiny_mce/tiny_mce_gzip.php"></script>
	<script language="javascript" type="text/javascript">
	tinyMCE.init({
	//mode : "specific_textareas",
	//editor_selector : "editor",
	mode : "none",
	theme : "advanced",
	plugins : "youtube,iespell,table,advhr,advimage,advlink,emotions,iespell,contextmenu,paste,directionality",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_buttons1 : "formatselect,bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,separator,bullist,numlist,outdent,indent,separator,undo,redo",
	theme_advanced_buttons2 : "tablecontrols,separator,link,unlink,anchor,separator,charmap,sub,sup,separator,youtube,separator,code",
	theme_advanced_buttons3 : "",
	theme_advanced_styles : "Grey Text=Teaser;White Text=Plain;Picture Left=Picleft;Picture Right=fltrt",
	//theme_advanced_disable : "styleselect,formatselect,image,cleanup,help,hr",
	theme_advanced_statusbar_location : false,
	paste_use_dialog : false,
	theme_advanced_resizing : true,
	theme_advanced_resize_horizontal : false,
	paste_auto_cleanup_on_paste : true,
	paste_convert_headers_to_strong : false,
	paste_strip_class_attributes : "all",
	paste_remove_spans : false,
	paste_remove_styles : false,
	paste_strip_class_attributes : "none",
	relative_urls : false,
	remove_script_host : false,
	fix_content_duplication : false,
	extended_valid_elements : "label[for],iframe[id|src|border|width|height|frameborder|marginheight|marginwidth|scrolling],script[src|language|type],div[id|class|name|align],php,span[class],form[name|id|action|method|enctype|accept-charset|onsubmit|onreset|target|style|class|summary],input[id|name|type|value|size|maxlength|checked|accept|src|width|height|disabled|readonly|tabindex|accesskey|onfocus|onblur|onchange|onselect|onclick|required|style|class|summary],textarea[id|name|rows|cols|disabled|readonly|tabindex|accesskey|onfocus|onblur|onchange|onselect|onclick|required|style|class|summary],option[name|id|value|selected|style|class|summary],select[id|name|type|value|size|maxlength|checked|accept|src|width|height|disabled|readonly|tabindex|accesskey|onfocus|onblur|onchange|onselect|onclick|length|options|selectedIndex|required|style|class|summary]",
	remove_linebreaks: false,
	document_base_url : "<?=$_SERVER["SERVER_NAME"];?>"
	});
	</script>
	
	<link rel="stylesheet" type="text/css" href="modules/content/css/style.css" />
	
	<script type="text/javascript" src="modules/content/ajax/js/jquery-pack.js"></script>
	<script type="text/javascript" src="modules/content/ajax/js/jquery.imgareaselect.min.js"></script>
	<script type="text/javascript" src="modules/content/ajax/js/jquery.ocupload-packed.js"></script>
	
	<h1><?=($_REQUEST["id"]>0)?'Editing: '.stripslashes($Details["page_name"]):'Create new page';?></h1>
	<?=DisplayError();?>
	<form name="Form" method="post" action="<?=$_SERVER["PHP_SELF"];?>" enctype="multipart/form-data">
	<input type="hidden" name="id" value="<?php if(isset($_REQUEST["id"]) && $_REQUEST["id"]!="") { echo $_REQUEST["id"]; } ?>" />
	<input type="hidden" name="mod" value="<?=$_REQUEST["mod"];?>" />
	<input type="hidden" name="task" value="save" />
	<input type="hidden" name="comeback" value="" />
	<div id="Controls">
		<div><input type="image" src="<?=TEMPLATEPATHNICE.'images/save_f2.png';?>" alt="" name="save" /><br />save</div>
		<?php if($_REQUEST["id"]>0 && $Details["is_locked"]!='Y') { ?><div><input type="image" src="<?=TEMPLATEPATHNICE.'images/delete_f2.png';?>" border="0" alt="" name="remove" onclick="if(!confirm('Warning! Clicking OK will permanently remove this page')) { return false; }" /><br />delete</div>
		<div><input type="image" src="<?=TEMPLATEPATHNICE.'images/archive_f2.png';?>" alt="" name="archive" onclick="if(!confirm('Click OK to archive this page')) { return false; }" /><br />archive</div><?php } ?>
		<?php if($_REQUEST["id"]>0) { ?><div><input type="image" src="<?=TEMPLATEPATHNICE.'images/publish_f2.png';?>" border="0" alt="" name="publish" onclick="if(!confirm('Click OK to publish this page LIVE to the internet')) { return false; }" /><br />publish</div><?php } ?>
		<div><input type="image" src="<?=TEMPLATEPATHNICE.'images/cancel_f2.png';?>" alt="" name="cancel" /><br />cancel</div>
	</div>
	<div style="clear:both;">&nbsp;</div>
	
	<?php
	## Home page
	
	if($Details["is_home"]=="Y") {
	?>
	<input type="hidden" name="mandatory" value="page_short_desc" />
	<div class="tabs">
		<ul>
			<li id="one_tab"<?=(!isset($_REQUEST["sub"]))?' class="current"':'';?>><span><a href="javascript:mcTabs.displayTab('one_tab','one_panel');" onmousedown="return false;">Home Page Image</a></span></li>
			<li id="two_tab"><span><a href="javascript:mcTabs.displayTab('two_tab','two_panel');" onmousedown="return false;">Left Column</a></span></li>
			<li id="three_tab"><span><a href="javascript:mcTabs.displayTab('three_tab','three_panel');" onmousedown="return false;">Middle Column</a></span></li>
			<li id="four_tab"><span><a href="javascript:mcTabs.displayTab('four_tab','four_panel');" onmousedown="return false;">Optional: Search Engine Metadata</a></span></li>
		</ul>
	</div>
	<div class="panel_wrapper">
		<div id="one_panel" class="panel<?=(!isset($_REQUEST["sub"]))?' current':'';?>">
			<table border="0">
			<tr>
			<td width="500" valign="top">
			
			<div class="TextPromptGrey">The form below allows you to edit the introduction text and image on the landing page</div>
			<div class="row">
				<label>Mission Statement</label>
				<textarea rows="7" cols="40" name="page_short_desc"><?=ShowDataText("page_short_desc");?></textarea>
			</div>
			<div class="row">
				<label>Add Image</label>
				<span style="display:block;float:left;">
					<input type="button" id="upload_link" value="Browse for Image" />
				</span>
			</div>
			<div id="HomePhotos">
				
			</div>
			<script>
			$('#HomePhotos').load('modules/content/ajax/_ajax.home.inc.php?id=<?=$_REQUEST["id"];?>');
			</script>
			</td>
			<td width="20">&nbsp;</td>
			<td valign="top">
			
				<div id="upload_status" style="display:none;" class="TextPrompt"></div>
			
				<div>
					<span id="loader" style="display:none;"><img src="modules/content/ajax/loader.gif" alt="Loading..."/></span> <span id="progress"></span>
				</div>
				<div id="thumbnail_form" style="display:none;">
					<button id="save_thumb">Save Thumbnail</button>
					<input type="hidden" name="x1" value="" id="x1" />
					<input type="hidden" name="y1" value="" id="y1" />
					<input type="hidden" name="x2" value="" id="x2" />
					<input type="hidden" name="y2" value="" id="y2" />
					<input type="hidden" name="w" value="" id="w" />
					<input type="hidden" name="h" value="" id="h" />
					<input type="hidden" name="page_thumbnail" id="page_thumbnail" value="<?=ShowDataText("page_thumbnail");?>" />
				</div>
				<div id="uploaded_image"></div>		
			
			</td>
			</tr>
			</table>
			
			<script type="text/javascript">
function loadingmessage(msg, show_hide){
	if(show_hide=="show"){
		$('#loader').show();
		$('#progress').show().text(msg);
		$('#uploaded_image').html('');
	}else if(show_hide=="hide"){
		$('#loader').hide();
		$('#progress').text('').hide();
	}else{
		$('#loader').hide();
		$('#progress').text('').hide();
		$('#uploaded_image').html('');
	}
}

function preview(img, selection) {
	//get width and height of the uploaded image.
	var current_width = $('#uploaded_image').find('#thumbnail').width();
	var current_height = $('#uploaded_image').find('#thumbnail').height();

	var scaleX = 510 / selection.width; 
	var scaleY = 200 / selection.height; 
	
	$('#uploaded_image').find('#thumbnail_preview').css({ 
		width: Math.round(scaleX * current_width) + 'px', 
		height: Math.round(scaleY * current_height) + 'px',
		marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px', 
		marginTop: '-' + Math.round(scaleY * selection.y1) + 'px' 
	});
	$('#x1').val(selection.x1);
	$('#y1').val(selection.y1);
	$('#x2').val(selection.x2);
	$('#y2').val(selection.y2);
	$('#w').val(selection.width);
	$('#h').val(selection.height);
}

function deleteimage(large_image, thumbnail_image){
	loadingmessage('Please wait, deleting images...', 'show');
	$.ajax({
		type: 'POST',
		url: 'modules/content/ajax/image_handling.php',
		data: 'a=delete&large_image=<?=$_SERVER["DOCUMENT_ROOT"];?>'+large_image+'&thumbnail_image='+thumbnail_image,
		cache: false,
		success: function(response){
			loadingmessage('', 'hide');
			response = unescape(response);
			var response = response.split("|");
			var responseType = response[0];
			var responseMsg = response[1];
			if(responseType=="success"){
			//	$('#upload_status').show().html('<h1>Success</h1><p>'+responseMsg+'</p>');
				$('#uploaded_image').html('');
			}else{
				$('#upload_status').show().html('<h1>Unexpected Error</h1><p>Please try again</p>'+response);
			}
		}
	});
}

		$(document).ready(function () {
		$('#loader').hide();
		$('#progress').hide();
		var myUpload = $('#upload_link').upload({
		   name: 'image',
		   action: 'modules/content/ajax/image_handling.php',
		   enctype: 'multipart/form-data',
		   params: {upload:'Upload'},
		   autoSubmit: true,
		   onSubmit: function() {
		   		$('#upload_status').html('').hide();
				loadingmessage('Please wait, uploading file...', 'show');
		   },
		   onComplete: function(response) {
		   		loadingmessage('', 'hide');
				response = unescape(response);
				var response = response.split("|");
				var responseType = response[0];
				var responseMsg = response[1];
				if(responseType=="success"){
					var current_width = response[2];
					var current_height = response[3];
					//display message that the file has been uploaded
					$('#upload_status').show().html('<p>The image has been uploaded. Using your mouse, click and drag on the image to define where we need to crop.</p>');
					//put the image in the appropriate div
					$('#uploaded_image').html('<div style="display:none;border:1px #e5e5e5 solid; float:left; position:relative; overflow:hidden; width:235px; height:200px;"><img src="'+responseMsg+'" style="position: relative;" id="thumbnail_preview" alt="Thumbnail Preview" /></div><img src="'+responseMsg+'" style="float: left; margin-left: 10px;" id="thumbnail" alt="Create Thumbnail" />');
					//find the image inserted above, and allow it to be cropped
					$('#uploaded_image').find('#thumbnail').imgAreaSelect({ aspectRatio: '1:0.392', onSelectChange: preview }); 
					//display the hidden form
					$('#thumbnail_form').show();
					
					
					
				}else if(responseType=="error"){
					$('#upload_status').show().html('<h1>Error</h1><p>'+responseMsg+'</p>');
					$('#uploaded_image').html('');
					$('#thumbnail_form').hide();
				}else{
					$('#upload_status').show().html('<h1>Unexpected Error</h1><p>Please try again</p>'+response);
					$('#uploaded_image').html('');
					$('#thumbnail_form').hide();
				}
		   }
		});
		
		
		$('#save_thumb').click(function() {
		var x1 = $('#x1').val();
		var y1 = $('#y1').val();
		var x2 = $('#x2').val();
		var y2 = $('#y2').val();
		var w = $('#w').val();
		var h = $('#h').val();
		
		if(x1=="" || y1=="" || x2=="" || y2=="" || w=="" || h==""){
			alert("You must make a selection first");
			return false;
		}else{
			//hide the selection and disable the imgareaselect plugin
			$('#uploaded_image').find('#thumbnail').imgAreaSelect({ disable: true, hide: true }); 
			loadingmessage('Please wait, saving thumbnail....', 'show');
			$.ajax({
				type: 'POST',
				url: 'modules/content/ajax/image_handling.php',
				data: 'save_thumb=Save Thumbnail&x1='+x1+'&y1='+y1+'&x2='+x2+'&y2='+y2+'&w='+w+'&h='+h+'&tw=510&th=200',
				cache: false,
				success: function(response){
					loadingmessage('', 'hide');
					response = unescape(response);
					var response = response.split("|");
					var responseType = response[0];
					var responseLargeImage = response[1];
					var responseThumbImage = response[2];
					if(responseType=="success"){
						$('#upload_status').show().html('<h1>Success</h1><p>The thumbnail has been saved!</p>');
						//load the new images
						//$('#uploaded_image').html('<img src="'+responseLargeImage+'" alt="Large Image"/>&nbsp;<img src="'+responseThumbImage+'" alt="Thumbnail Image"/><br /><a href="javascript:deleteimage(\''+responseLargeImage+'\', \''+responseThumbImage+'\');">Delete Images</a>');
						deleteimage(responseLargeImage, responseThumbImage);
						//hide the thumbnail form
						$('#thumbnail_form').hide();
						
						$('#HomePhotos').load('modules/content/ajax/_ajax.home.inc.php?id=<?=$_REQUEST["id"];?>&newimage='+responseThumbImage);
						
					}else{
						$('#upload_status').show().html('<h1>Unexpected Error</h1><p>Please try again</p>'+response);
						//reactivate the imgareaselect plugin to allow another attempt.
						$('#uploaded_image').find('#thumbnail').imgAreaSelect({ aspectRatio: '1:0.392', onSelectChange: preview }); 
						$('#thumbnail_form').show();
					}
				}
			});
			
			return false;
		}
	});		
		
		
});
</script>
			
			
		</div>
		<div id="two_panel" class="panel<?=(isset($_REQUEST["sub"]) && $_REQUEST["sub"]=="left")?' current':'';?>">
			<div class="TextPromptGrey">This section allows you to edit the content shown in the lower left column of the home page</div>
			<div class="row">
				<label>Copy</label>
				<textarea rows="7" cols="40" name="page_home_left_copy" id="page_home_left_copy" style="width:800px"><?=ShowDataText("page_home_left_copy");?></textarea>
			</div>
			<script>
			tinyMCE.execCommand('mceAddControl', false, 'page_home_left_copy');
			</script>
			<?php if(is_file($Config["content"]["images"].$Details["page_home_left_image"])) { ?>
			<div class="row">
				<label>Current Image</label>
				<img src="<?=$Config["content"]["images_nice"].$Details["page_home_left_image"];?>" border="0" alt="" />
			</div>
			<div class="row">
				<label>&nbsp;</label>
				<input type="checkbox" name="remove_page_home_left_image" value="Y" /> Remove Image
			</div>
			<?php } ?>
			<div class="row">
				<label>Image</label>
				<input type="file" name="page_home_left_image" /> (230px x 120px)
			</div>
			<div class="row">
				<label>Learn more link</label>
				<input type="text" name="page_home_left_link" value="<?=ShowDataText("page_home_left_link");?>" size="30" />
			</div>
		</div>
		<div id="three_panel" class="panel<?=(isset($_REQUEST["sub"]) && $_REQUEST["sub"]=="right")?' current':'';?>">
			<div class="TextPromptGrey">This section allows you to edit the content shown in the lower middle column of the home page</div>
			<div class="row">
				<label>Copy</label>
				<textarea rows="7" cols="40" name="page_home_right_copy" id="page_home_right_copy" style="width:800px"><?=ShowDataText("page_home_right_copy");?></textarea>
			</div>
			<script>
			tinyMCE.execCommand('mceAddControl', false, 'page_home_right_copy');
			</script>
			<?php if(is_file($Config["content"]["images"].$Details["page_home_right_image"])) { ?>
			<div class="row">
				<label>Current Image</label>
				<img src="<?=$Config["content"]["images_nice"].$Details["page_home_right_image"];?>" border="0" alt="" />
			</div>
			<div class="row">
				<label>&nbsp;</label>
				<input type="checkbox" name="remove_page_home_right_image" value="Y" /> Remove Image
			</div>
			<?php } ?>
			<div class="row">
				<label>Image</label>
				<input type="file" name="page_home_right_image" /> (230px x 120px)
			</div>
		</div>
		<div id="four_panel" class="panel<?=(isset($_REQUEST["sub"]) && $_REQUEST["sub"]=="seo")?' current':'';?>">
			<div class="TextPromptGrey">This section allows you to edit the meta data that search engines use to help rank your page.</div>
			<div class="row">
				<label>Title</label>
				<input type="text" size="30" name="page_meta_title" value="<?=ShowDataText("page_meta_title");?>" />
			</div>
			<div class="row">
				<label>Keywords</label>
				<input type="text" size="30" name="page_meta_keywords" value="<?=ShowDataText("page_meta_keywords");?>" />
			</div>
			<div class="row">
				<label>Description</label>
				<textarea rows="7" cols="40" name="page_meta_description"><?=ShowDataText("page_meta_description");?></textarea>
			</div>
		</div>
	</div>
	<?php
	}
	
	## Landing page
	
	else if(MySQLResult("SELECT count(*) FROM pages WHERE sub_id='".$Details["page_id"]."'")>0 && $_REQUEST["id"]>0 && $IsSecondLevel) {
	?>
	<input type="hidden" name="mandatory" value="page_name,page_short_desc" />
	<div class="tabs">
		<ul>
			<li id="one_tab"<?=(!isset($_REQUEST["sub"]))?' class="current"':'';?>><span><a href="javascript:mcTabs.displayTab('one_tab','one_panel');" onmousedown="return false;">Step 1: Landing Page Details</a></span></li>
			<?php if($_REQUEST["id"]>0) { ?>
			<li id="two_tab"><span><a href="javascript:mcTabs.displayTab('two_tab','two_panel');" onmousedown="return false;">Optional: Search Engine Metadata</a></span></li>
			<?php } ?>
		</ul>
	</div>
	<div class="panel_wrapper">
		<div id="one_panel" class="panel<?=(!isset($_REQUEST["sub"]))?' current':'';?>">
			
			<table border="0">
			<tr>
			<td width="500" valign="top">
			
			<div class="TextPromptGrey">The form below allows you to edit the introduction text and image on the landing page</div>
			<div class="row">
				<label>Page Title</label>
				<input type="text" size="30" name="page_name" value="<?=ShowDataText("page_name");?>" />
			</div>
			<div<?=HighlightMandatory("sub_id");?> style="display:none;">
				<label>Assigned To <?= ShowMandatory("sub_id"); ?></label>
				<select name="sub_id" id="sub_id">
				<option value="">- please select -</option>
				<?php
				$pageswhere=(!$_REQUEST["id"]>0)?' WHERE sub_id !=0':'';
				$sql=execute_query("SELECT * , page_id AS SUB, (SELECT count( * ) FROM pages WHERE sub_id = SUB ) AS SUBCOUNT FROM `pages` HAVING SUBCOUNT >0");
				foreach($sql as $row) {
				?><option value="<?=$row["page_id"];?>"<?=ShowSelected($row["page_id"],"sub_id",$Details);?>><?=DisplayRecursive($row["sub_id"]) . ' - '.stripslashes($row["page_name"]);?></option><?php
				}
				?>
				</select>
			</div>
			
			<div class="row">
				<label>Description</label>
				<textarea rows="7" cols="40" name="page_short_desc"><?=ShowDataText("page_short_desc");?></textarea>
			</div>
			<?php if(is_file($Config["content"]["images"].$Details["page_thumbnail"])) { ?>
			<div class="row">
				<label>Current Image</label>
				<img src="<?=$Config["content"]["images_nice"].$Details["page_thumbnail"];?>" border="0" alt="" id="current_image" />
			</div>
			<div class="row">
				<label>&nbsp;</label>
				<input type="checkbox" name="" value="Y" /> Remove Image
			</div>
			<?php } ?>
			<div class="row">
				<label>Upload Image</label>
				<span style="display:block;float:left;">
					<input type="button" id="upload_link" value="Browse for Image" /> (235px x 200px)
				</span>
			</div>
			<div>
				<label>Display on Navigation</label>
				<input type="checkbox" name="in_nav" id="in_nav" value="Y"<?=ShowChecked("Y","in_nav",$Details);?> />
			</div>
			
			</td>
			<td width="20">&nbsp;</td>
			<td valign="top">
			
			<div id="upload_status" style="display:none;" class="TextPrompt"></div>
			
			<div>
				<span id="loader" style="display:none;"><img src="modules/content/ajax/loader.gif" alt="Loading..."/></span> <span id="progress"></span>
			</div>
			<div id="thumbnail_form" style="display:none;">
				<button id="save_thumb">Save Thumbnail</button>
				<input type="hidden" name="x1" value="" id="x1" />
				<input type="hidden" name="y1" value="" id="y1" />
				<input type="hidden" name="x2" value="" id="x2" />
				<input type="hidden" name="y2" value="" id="y2" />
				<input type="hidden" name="w" value="" id="w" />
				<input type="hidden" name="h" value="" id="h" />
				<input type="hidden" name="page_thumbnail" id="page_thumbnail" value="<?=ShowDataText("page_thumbnail");?>" />
			</div>
			<div id="uploaded_image"></div>			
			
			</td>
			</tr>
			</table>
	
<script type="text/javascript">
function loadingmessage(msg, show_hide){
	if(show_hide=="show"){
		$('#loader').show();
		$('#progress').show().text(msg);
		$('#uploaded_image').html('');
	}else if(show_hide=="hide"){
		$('#loader').hide();
		$('#progress').text('').hide();
	}else{
		$('#loader').hide();
		$('#progress').text('').hide();
		$('#uploaded_image').html('');
	}
}

function preview(img, selection) {
	//get width and height of the uploaded image.
	var current_width = $('#uploaded_image').find('#thumbnail').width();
	var current_height = $('#uploaded_image').find('#thumbnail').height();

	var scaleX = 235 / selection.width; 
	var scaleY = 200 / selection.height; 
	
	$('#uploaded_image').find('#thumbnail_preview').css({ 
		width: Math.round(scaleX * current_width) + 'px', 
		height: Math.round(scaleY * current_height) + 'px',
		marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px', 
		marginTop: '-' + Math.round(scaleY * selection.y1) + 'px' 
	});
	$('#x1').val(selection.x1);
	$('#y1').val(selection.y1);
	$('#x2').val(selection.x2);
	$('#y2').val(selection.y2);
	$('#w').val(selection.width);
	$('#h').val(selection.height);
}

function deleteimage(large_image, thumbnail_image){
	loadingmessage('Please wait, deleting images...', 'show');
	$.ajax({
		type: 'POST',
		url: 'modules/content/ajax/image_handling.php',
		data: 'a=delete&large_image=<?=$_SERVER["DOCUMENT_ROOT"];?>'+large_image+'&thumbnail_image='+thumbnail_image,
		cache: false,
		success: function(response){
			loadingmessage('', 'hide');
			response = unescape(response);
			var response = response.split("|");
			var responseType = response[0];
			var responseMsg = response[1];
			if(responseType=="success"){
			//	$('#upload_status').show().html('<h1>Success</h1><p>'+responseMsg+'</p>');
				$('#uploaded_image').html('');
			}else{
				$('#upload_status').show().html('<h1>Unexpected Error</h1><p>Please try again</p>'+response);
			}
		}
	});
}

		$(document).ready(function () {
		$('#loader').hide();
		$('#progress').hide();
		var myUpload = $('#upload_link').upload({
		   name: 'image',
		   action: 'modules/content/ajax/image_handling.php',
		   enctype: 'multipart/form-data',
		   params: {upload:'Upload'},
		   autoSubmit: true,
		   onSubmit: function() {
		   		$('#upload_status').html('').hide();
				loadingmessage('Please wait, uploading file...', 'show');
		   },
		   onComplete: function(response) {
		   		loadingmessage('', 'hide');
				response = unescape(response);
				var response = response.split("|");
				var responseType = response[0];
				var responseMsg = response[1];
				if(responseType=="success"){
					var current_width = response[2];
					var current_height = response[3];
					//display message that the file has been uploaded
					$('#upload_status').show().html('<p>The image has been uploaded. Using your mouse, click and drag on the image to define where we need to crop.</p>');
					//put the image in the appropriate div
					$('#uploaded_image').html('<div style="border:1px #e5e5e5 solid; float:left; position:relative; overflow:hidden; width:235px; height:200px;"><img src="'+responseMsg+'" style="position: relative;" id="thumbnail_preview" alt="Thumbnail Preview" /></div><img src="'+responseMsg+'" style="float: left; margin-left: 10px;" id="thumbnail" alt="Create Thumbnail" />');
					//find the image inserted above, and allow it to be cropped
					$('#uploaded_image').find('#thumbnail').imgAreaSelect({ aspectRatio: '1:0.85', onSelectChange: preview }); 
					//display the hidden form
					$('#thumbnail_form').show();
					
					
					
				}else if(responseType=="error"){
					$('#upload_status').show().html('<h1>Error</h1><p>'+responseMsg+'</p>');
					$('#uploaded_image').html('');
					$('#thumbnail_form').hide();
				}else{
					$('#upload_status').show().html('<h1>Unexpected Error</h1><p>Please try again</p>'+response);
					$('#uploaded_image').html('');
					$('#thumbnail_form').hide();
				}
		   }
		});
		
		
		$('#save_thumb').click(function() {
		var x1 = $('#x1').val();
		var y1 = $('#y1').val();
		var x2 = $('#x2').val();
		var y2 = $('#y2').val();
		var w = $('#w').val();
		var h = $('#h').val();
		
		if(x1=="" || y1=="" || x2=="" || y2=="" || w=="" || h==""){
			alert("You must make a selection first");
			return false;
		}else{
			//hide the selection and disable the imgareaselect plugin
			$('#uploaded_image').find('#thumbnail').imgAreaSelect({ disable: true, hide: true }); 
			loadingmessage('Please wait, saving thumbnail....', 'show');
			$.ajax({
				type: 'POST',
				url: 'modules/content/ajax/image_handling.php',
				data: 'save_thumb=Save Thumbnail&x1='+x1+'&y1='+y1+'&x2='+x2+'&y2='+y2+'&w='+w+'&h='+h+'&tw=235&th=200',
				cache: false,
				success: function(response){
					loadingmessage('', 'hide');
					response = unescape(response);
					var response = response.split("|");
					var responseType = response[0];
					var responseLargeImage = response[1];
					var responseThumbImage = response[2];
					if(responseType=="success"){
						$('#upload_status').show().html('<h1>Success</h1><p>The thumbnail has been saved!</p>');
						//load the new images
						//$('#uploaded_image').html('<img src="'+responseLargeImage+'" alt="Large Image"/>&nbsp;<img src="'+responseThumbImage+'" alt="Thumbnail Image"/><br /><a href="javascript:deleteimage(\''+responseLargeImage+'\', \''+responseThumbImage+'\');">Delete Images</a>');
						deleteimage(responseLargeImage, responseThumbImage);
						//hide the thumbnail form
						$('#thumbnail_form').hide();
						$('#current_image').attr('src',responseThumbImage);
						$('#page_thumbnail').val(responseThumbImage);
					}else{
						$('#upload_status').show().html('<h1>Unexpected Error</h1><p>Please try again</p>'+response);
						//reactivate the imgareaselect plugin to allow another attempt.
						$('#uploaded_image').find('#thumbnail').imgAreaSelect({ aspectRatio: '1:0.85', onSelectChange: preview }); 
						$('#thumbnail_form').show();
					}
				}
			});
			
			return false;
		}
	});		
		
		
});
</script>
		
		
		
		
		
		</div>
		<div id="two_panel" class="panel<?=(isset($_REQUEST["sub"]) && $_REQUEST["sub"]=="seo")?' current':'';?>">
			<div class="TextPromptGrey">This section allows you to edit the meta data that search engines use to help rank your page.</div>
			<div class="row">
				<label>Title</label>
				<input type="text" size="30" name="page_meta_title" value="<?=ShowDataText("page_meta_title");?>" />
			</div>
			<div class="row">
				<label>Keywords</label>
				<input type="text" size="30" name="page_meta_keywords" value="<?=ShowDataText("page_meta_keywords");?>" />
			</div>
			<div class="row">
				<label>Description</label>
				<textarea rows="7" cols="40" name="page_meta_description"><?=ShowDataText("page_meta_description");?></textarea>
			</div>
		</div>
	</div>
	<?php
	}
	
	## Content page
	
	else {
	?>
	<input type="hidden" name="mandatory" value="page_name,page_short_desc" />
	<div class="tabs">
		<ul>
			<li id="one_tab"<?=(!isset($_REQUEST["step"]))?' class="current"':'';?>><span><a href="javascript:mcTabs.displayTab('one_tab','one_panel');" onmousedown="return false;">Step 1: Page Details</a></span></li>
			<?php if($_REQUEST["id"]>0) { ?>
			<li id="two_tab"<?=(isset($_REQUEST["step"]) && $_REQUEST["step"]=="2")?' class="current"':'';?>><span><a href="javascript:mcTabs.displayTab('two_tab','two_panel');">Step 2: Page Content</a></span></li>
			<li id="three_tab"><span><a href="javascript:mcTabs.displayTab('three_tab','three_panel');" onmousedown="return false;">Optional: Search Engine Metadata</a></span></li>
			<?php } ?>
		</ul>
	</div>
	<div class="panel_wrapper">
		<div id="one_panel" class="panel<?=(!isset($_REQUEST["step"]))?' current':'';?>">
		
			<table border="0">
			<tr>
			<td width="500" valign="top">
			
			<h1>Step One: Page Details</h1>
			
			<div class="TextPromptGrey"><b>Page Title and Assignment</b> - Please provide a title for your page and specify under which area it is to appear.</div>
			
			<div<?=HighlightMandatory("page_name");?>>
				<label>Page Title <?= ShowMandatory("page_name"); ?></label>
				<input type="text" size="30" name="page_name" value="<?=ShowDataText("page_name");?>" />
			</div>
			
			<div<?=HighlightMandatory("sub_id");?>>
				<label>Assigned To <?= ShowMandatory("sub_id"); ?></label>
				<select name="sub_id" id="sub_id" style="width:200px;">
				<option value="">- please select -</option>
				<?php
				$pageswhere=(!$_REQUEST["id"]>0)?' WHERE sub_id !=0':'';
				//$sql=execute_query("SELECT * , page_id AS SUB, (SELECT count( * ) FROM pages WHERE sub_id = SUB ) AS SUBCOUNT FROM `pages` HAVING SUBCOUNT >0");
				$sql=execute_query("SELECT * , page_id AS SUB, (SELECT count( * ) FROM pages WHERE sub_id = SUB ) AS SUBCOUNT FROM `pages` ");
				foreach($sql as $row) {
				?><option value="<?=$row["page_id"];?>"<?=ShowSelected($row["page_id"],"sub_id",$Details);?>><?=DisplayRecursive($row["sub_id"]) . ' - '.stripslashes($row["page_name"]);?></option><?php
				}
				?>
				</select>
			</div>
			
			<div class="TextPromptGrey"><b>Landing Page Description and Image</b> - Copy copy copy.</div>
			
			<?php if(is_file($Config["content"]["images"].$Details["page_thumbnail"])) { ?>
			<div>
				<label>Current Image</label>
				<img src="<?=$Config["content"]["images_nice"].$Details["page_thumbnail"];?>" border="0" alt="" id="current_image" />
			</div>
			<div>
				<label>&nbsp;</label>
				<input type="checkbox" name="" value="Y" /> Remove Image
			</div>
			<?php } ?>
			<div class="row">
				<label>Upload Image</label>
				<span style="display:block;float:left;">
					<input type="button" id="upload_link" value="Browse for Image" /> (235px x 120px)
				</span>
			</div>
			
			<div<?=HighlightMandatory("page_short_desc");?>>
				<label>Description</label>
				<textarea rows="7" cols="40" name="page_short_desc"><?=ShowDataText("page_short_desc");?></textarea>
			</div>
			
			<div>
				<label>Display on Navigation</label>
				<input type="checkbox" name="in_nav" id="in_nav" value="Y"<?=ShowChecked("Y","in_nav",$Details);?> />
			</div>
			<div>
				<label>Hide Page</label>
				<input type="checkbox" name="is_hidden" id="is_hidden" value="Y"<?=ShowChecked("Y","is_hidden",$Details);?> /> (do not display a link to this page)
			</div>
			
			</td>
			
			</td>
			<td width="20">&nbsp;</td>
			<td valign="top">
			
			<div id="upload_status" style="display:none;" class="TextPrompt"></div>
			
			<div>
				<span id="loader" style="display:none;"><img src="modules/content/ajax/loader.gif" alt="Loading..."/></span> <span id="progress"></span>
			</div>
			<div id="thumbnail_form" style="display:none;">
				<button id="save_thumb">Save Thumbnail</button>
				<input type="hidden" name="x1" value="" id="x1" />
				<input type="hidden" name="y1" value="" id="y1" />
				<input type="hidden" name="x2" value="" id="x2" />
				<input type="hidden" name="y2" value="" id="y2" />
				<input type="hidden" name="w" value="" id="w" />
				<input type="hidden" name="h" value="" id="h" />
				<input type="hidden" name="page_thumbnail" id="page_thumbnail" value="<?=ShowDataText("page_thumbnail");?>" />
			</div>
			<div id="uploaded_image"></div>			
			
			</td>
			</tr>
			</table>
	
<script type="text/javascript">
function loadingmessage(msg, show_hide){
	if(show_hide=="show"){
		$('#loader').show();
		$('#progress').show().text(msg);
		$('#uploaded_image').html('');
	}else if(show_hide=="hide"){
		$('#loader').hide();
		$('#progress').text('').hide();
	}else{
		$('#loader').hide();
		$('#progress').text('').hide();
		$('#uploaded_image').html('');
	}
}

function preview(img, selection) {
	//get width and height of the uploaded image.
	var current_width = $('#uploaded_image').find('#thumbnail').width();
	var current_height = $('#uploaded_image').find('#thumbnail').height();

	var scaleX = 235 / selection.width; 
	var scaleY = 120 / selection.height; 
	
	$('#uploaded_image').find('#thumbnail_preview').css({ 
		width: Math.round(scaleX * current_width) + 'px', 
		height: Math.round(scaleY * current_height) + 'px',
		marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px', 
		marginTop: '-' + Math.round(scaleY * selection.y1) + 'px' 
	});
	$('#x1').val(selection.x1);
	$('#y1').val(selection.y1);
	$('#x2').val(selection.x2);
	$('#y2').val(selection.y2);
	$('#w').val(selection.width);
	$('#h').val(selection.height);
}

function deleteimage(large_image, thumbnail_image){
	loadingmessage('Please wait, deleting images...', 'show');
	$.ajax({
		type: 'POST',
		url: 'modules/content/ajax/image_handling.php',
		data: 'a=delete&large_image=<?=$_SERVER["DOCUMENT_ROOT"];?>'+large_image+'&thumbnail_image='+thumbnail_image,
		cache: false,
		success: function(response){
			loadingmessage('', 'hide');
			response = unescape(response);
			var response = response.split("|");
			var responseType = response[0];
			var responseMsg = response[1];
			if(responseType=="success"){
			//	$('#upload_status').show().html('<h1>Success</h1><p>'+responseMsg+'</p>');
				$('#uploaded_image').html('');
			}else{
				$('#upload_status').show().html('<h1>Unexpected Error</h1><p>Please try again</p>'+response);
			}
		}
	});
}

		$(document).ready(function () {
		$('#loader').hide();
		$('#progress').hide();
		var myUpload = $('#upload_link').upload({
		   name: 'image',
		   action: 'modules/content/ajax/image_handling.php',
		   enctype: 'multipart/form-data',
		   params: {upload:'Upload'},
		   autoSubmit: true,
		   onSubmit: function() {
		   		$('#upload_status').html('').hide();
				loadingmessage('Please wait, uploading file...', 'show');
		   },
		   onComplete: function(response) {
		   		loadingmessage('', 'hide');
				response = unescape(response);
				var response = response.split("|");
				var responseType = response[0];
				var responseMsg = response[1];
				if(responseType=="success"){
					var current_width = response[2];
					var current_height = response[3];
					//display message that the file has been uploaded
					$('#upload_status').show().html('<p>The image has been uploaded. Using your mouse, click and drag on the image to define where we need to crop.</p>');
					//put the image in the appropriate div
					$('#uploaded_image').html('<div style="border:1px #e5e5e5 solid; float:left; position:relative; overflow:hidden; width:235px; height:120px;"><img src="'+responseMsg+'" style="position: relative;" id="thumbnail_preview" alt="Thumbnail Preview" /></div><img src="'+responseMsg+'" style="float: left; margin-left: 10px;" id="thumbnail" alt="Create Thumbnail" />');
					//find the image inserted above, and allow it to be cropped
					$('#uploaded_image').find('#thumbnail').imgAreaSelect({ aspectRatio: '1:0.51', onSelectChange: preview }); 
					//display the hidden form
					$('#thumbnail_form').show();
					
					
					
				}else if(responseType=="error"){
					$('#upload_status').show().html('<h1>Error</h1><p>'+responseMsg+'</p>');
					$('#uploaded_image').html('');
					$('#thumbnail_form').hide();
				}else{
					$('#upload_status').show().html('<h1>Unexpected Error</h1><p>Please try again</p>'+response);
					$('#uploaded_image').html('');
					$('#thumbnail_form').hide();
				}
		   }
		});
		
		
		$('#save_thumb').click(function() {
		var x1 = $('#x1').val();
		var y1 = $('#y1').val();
		var x2 = $('#x2').val();
		var y2 = $('#y2').val();
		var w = $('#w').val();
		var h = $('#h').val();
		
		if(x1=="" || y1=="" || x2=="" || y2=="" || w=="" || h==""){
			alert("You must make a selection first");
			return false;
		}else{
			//hide the selection and disable the imgareaselect plugin
			$('#uploaded_image').find('#thumbnail').imgAreaSelect({ disable: true, hide: true }); 
			loadingmessage('Please wait, saving thumbnail....', 'show');
			$.ajax({
				type: 'POST',
				url: 'modules/content/ajax/image_handling.php',
				data: 'save_thumb=Save Thumbnail&x1='+x1+'&y1='+y1+'&x2='+x2+'&y2='+y2+'&w='+w+'&h='+h+'&tw=235&th=120',
				cache: false,
				success: function(response){
					loadingmessage('', 'hide');
					response = unescape(response);
					var response = response.split("|");
					var responseType = response[0];
					var responseLargeImage = response[1];
					var responseThumbImage = response[2];
					if(responseType=="success"){
						$('#upload_status').show().html('<h1>Success</h1><p>The thumbnail has been saved!</p>');
						//load the new images
						//$('#uploaded_image').html('<img src="'+responseLargeImage+'" alt="Large Image"/>&nbsp;<img src="'+responseThumbImage+'" alt="Thumbnail Image"/><br /><a href="javascript:deleteimage(\''+responseLargeImage+'\', \''+responseThumbImage+'\');">Delete Images</a>');
						deleteimage(responseLargeImage, responseThumbImage);
						//hide the thumbnail form
						$('#thumbnail_form').hide();
						$('#current_image').attr('src',responseThumbImage);
						$('#page_thumbnail').val(responseThumbImage);
					}else{
						$('#upload_status').show().html('<h1>Unexpected Error</h1><p>Please try again</p>'+response);
						//reactivate the imgareaselect plugin to allow another attempt.
						$('#uploaded_image').find('#thumbnail').imgAreaSelect({ aspectRatio: '1:0.51', onSelectChange: preview }); 
						$('#thumbnail_form').show();
					}
				}
			});
			
			return false;
		}
	});		
		
		
});
</script>
		
		
			
	
		</div>
		<div id="two_panel" class="panel<?=(isset($_REQUEST["step"]))?' current':'';?>">
			<h1>Step Two: Page Content</h1>
			
			<?php
			#################
			## FORM PAGE
			#################
			
			if($Details["is_form"]=="Y") {
			?>
				<div class="TextPromptGrey"><b>Description<?php if($Details["form_type"]=="donation" || $Details["form_type"]=="monthly-donation") { ?> and Amounts<?php } ?></b></div>
				<div>
					<label>Heading</label>
					<input type="text" size="50" name="page_donation_title" id="page_donation_title" value="<?=ShowDataText("page_donation_title");?>" />
				</div>
				<div>
					<label>Description</label>
					<textarea class="editor" rows="15" cols="70" name="page_donation_intro" id="page_donation_intro" style="width:800px"><?=ShowDataText("page_donation_intro");?></textarea>
				</div>
				<?php if($Details["form_type"]=="donation" || $Details["form_type"]=="monthly-donation") { ?>
				<div>
					<label>Amounts</label>
					1. <input type="text" size="4" name="donation_amount_1" value="<?=ShowDataText("donation_amount_1");?>" /> 
					2. <input type="text" size="4" name="donation_amount_2" value="<?=ShowDataText("donation_amount_2");?>" /> 
					3. <input type="text" size="4" name="donation_amount_3" value="<?=ShowDataText("donation_amount_3");?>" /> 
					4. <input type="text" size="4" name="donation_amount_4" value="<?=ShowDataText("donation_amount_4");?>" />
				</div>
				<?php } ?>
				<script>
				tinyMCE.execCommand('mceAddControl', false, 'page_donation_intro');
				</script>
								
				<div class="TextPromptGrey"><b>Confirmation Email</b></div>
				<div>
					<label>&nbsp;</label>
					<input type="checkbox" name="page_donation_send_email" value="Y"<?=ShowChecked("Y","page_donation_send_email",$Details);?> /> Send confirmation email to recipient
				</div>
				<div>
					<label>Email Subject</label>
					<input type="text" size="50" name="page_donation_email_subject" id="page_donation_email_subject" value="<?=ShowDataText("page_donation_email_subject");?>" />
				</div>
				<div>
					<label>Email Text</label>
					<textarea class="editor" rows="15" cols="70" name="page_donation_email" id="page_donation_email" style="width:800px"><?=ShowDataText("page_donation_email");?></textarea>
				</div>
				<script>
				tinyMCE.execCommand('mceAddControl', false, 'page_donation_email');
				</script>
				<div class="TextPromptGrey"><b>Confirmation Page</b></div>
				<div<?=HighlightMandatory("redirect_id");?>>
				<label>Redirect To <?= ShowMandatory("redirect_id"); ?></label>
				<select name="redirect_id" id="redirect_id">
				<option value="">- please select -</option>
				<?php
				$pageswhere='';
				$sql=execute_query("SELECT * from pages ORDER BY sort_order");
				foreach($sql as $row) {
				?><option value="<?=$row["page_id"];?>"<?=ShowSelected($row["page_id"],"redirect_id",$Details);?>><?=DisplayRecursive($row["sub_id"]) . ' - '.stripslashes($row["page_name"]);?></option><?php
				}
				?>
				</select>
			</div>
			<?php
			}
			else {
			
				$x=1;
				$lsql=execute_query("SELECT * FROM pages_lumps WHERE page_id='".$Details["page_id"]."' ORDER BY sort_order ASC");
				if(count($lsql)==0) { $lsql=array(0=>array("lump_id"=>0,"lump_heading"=>"","lump_copy"=>"","lump_image_class"=>"","lump_image_size"=>"","lump_pdf"=>"","lump_link_text"=>"","lump_image"=>"")); }
				foreach($lsql as $lrow) {
				?>
				
				<?=($x>1)?'<div class="TextPrompt"><span style="float:right;"><input type="checkbox" name="remove_block['.$x.']" value="Y" /> remove </span><strong>Content Block</strong><span style="clear:both;"></span></div><br />':'';?>
				
				<input type="hidden" name="LUMP[<?=$x;?>]" value="<?=$lrow["lump_id"]; ?>" />
				<div class="Block" id="Block<?=$x;?>">
					<div>
						<label>Display Order</label>
						<input type="text" size="4" name="sort_order[<?=$x;?>]" value="<?=ShowDataText("sort_order[".$x."]",$lrow);?>" />
					</div>
					<div>
						<label>Heading</label>
						<input type="text" size="50" name="lump_heading[<?=$x;?>]" id="lump_heading[<?=$x;?>]" value="<?=ShowDataText("lump_heading[".$x."]",$lrow);?>" />
					</div>
					
					<div>
						<label>Description</label>
						<textarea class="editor" rows="15" cols="70" name="lump_copy[<?=$x;?>]" id="lump_copy[<?=$x;?>]" style="width:800px"><?=ShowDataText("lump_copy[".$x."]",$lrow);?></textarea>
					</div>
					
					<div class="TextPromptGrey"><b>Add Image</b><br />To add an image please use the browse button below to locate it and then select the desired positioning. 
					The image can be any size but must not be wider than 560 pixels.</div>
					
					<?php if(is_file($Config["content"]["images"].$lrow["lump_image"])) { ?>
					<div>
						<label>Current Image</label>
						<img src="<?=$Config["content"]["images_nice"].$lrow["lump_image"];?>" border="0" alt="" />
					</div>
					<div>
						<label>&nbsp;</label>
						<input type="checkbox" name="remove_image[<?=$x;?>]" value="Y" /> Remove Image
					</div>
					<?php } ?>
					<div>
						<label>Image</label>
						<input type="file" name="lump_image-<?=$x;?>" /> (235px x 200px)
					</div>
					<div>
						<label>Image Alignment</label>
						<select name="lump_image_class[<?=$x;?>]" id="lump_image_class[<?=$x;?>]">
						<option value="left"<?= ShowSelected("left","lump_image_class",$lrow); ?>>Align left (wrap text to right)</option>
						<option value="center"<?= ShowSelected("center","lump_image_class",$lrow); ?>>Align center (start text on new line)</option>
						<option value="right"<?= ShowSelected("right","lump_image_class",$lrow); ?>>Align right (wrap text to left)</option>
						</select>
					</div>
					<div>
						<label>Image Size</label>
						<select name="lump_image_size[<?=$x;?>]" id="lump_image_size[<?=$x;?>]">
						<option value="235"<?= ShowSelected("235","lump_image_size",$lrow); ?>>Medium</option>
						<option value="150"<?= ShowSelected("150","lump_image_size",$lrow); ?>>Small</option>
						<option value="500"<?= ShowSelected("500","lump_image_size",$lrow); ?>>Large</option>
						</select>
					</div>
					<?php if(is_file($Config["content"]["images"].'pdf/'.$lrow["lump_pdf"])) { ?>
					<div>
						<label>Current PDF</label>
						<a href="<?=$Config["content"]["images_nice"].'pdf/'.$lrow["lump_pdf"];?>"><?=$lrow["lump_pdf"];?></a>
					</div>
					<div>
						<label>&nbsp;</label>
						<input type="checkbox" name="remove_pdf[<?=$x;?>]" value="Y" /> Remove PDF
					</div>
					
					<?php } ?>
					<div>
						<label>PDF</label>
						<input type="file" name="lump_pdf-<?=$x;?>" /> 
					</div>
					
					
				</div>
				<script>
				tinyMCE.execCommand('mceAddControl', false, 'lump_copy[<?=$x;?>]');
				</script>
				
				<p>&nbsp;</p>
				<?php
				$x++;
				}
				?>
			
				<div></div>
			
				<p>&nbsp;</p>
			
				<p>
					<label>&nbsp;</label>
					<button type="button" id="AddBlock">Add New Block</button>
				</p>
				<script>
				$('#AddBlock').click(function(){
					$('#two_panel div:last').after('<div></div>').load('modules/content/ajax/_ajax.lumps.inc.php?id=<?=$_REQUEST["id"];?>');
					
				});
				</script>
			<?php
			}
			?>
		</div>
		<div id="three_panel" class="panel<?=(isset($_REQUEST["sub"]) && $_REQUEST["sub"]=="seo")?' current':'';?>">
			<div class="TextPromptGrey">This section allows you to edit the meta data that search engines use to help rank your page.</div>
			<div class="row">
				<label>Title</label>
				<input type="text" size="30" name="page_meta_title" value="<?=ShowDataText("page_meta_title");?>" />
			</div>
			<div class="row">
				<label>Keywords</label>
				<input type="text" size="30" name="page_meta_keywords" value="<?=ShowDataText("page_meta_keywords");?>" />
			</div>
			<div class="row">
				<label>Description</label>
				<textarea rows="7" cols="40" name="page_meta_description"><?=ShowDataText("page_meta_description");?></textarea>
			</div>
		</div>
	</div>
	<div style="clear:both;">&nbsp;</div>
	<?php
	}
	
	?>
	
	</form>
<?php
}
?>