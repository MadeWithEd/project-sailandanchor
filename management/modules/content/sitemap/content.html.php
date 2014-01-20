<style type="text/css"><!--
#SiteMap UL {
	margin:2px 0px 2px 5px;
	padding:2px 0px 2px 5px;
}
UL LI.SiteMapFolder {
	list-style-type:none;
	background:transparent url(modules/pages/images/folder.gif) no-repeat top left;
	padding-left:20px;
}
UL LI.SiteMapPage {
	list-style-type:none;
	background:transparent url(modules/pages/images/page.gif) no-repeat top left;
	padding-left:20px;
}
UL LI.SiteMapPageIndent {
	list-style-type:none;
	background:transparent url(modules/pages/images/page.gif) no-repeat top left;
	padding-left:20px;
	margin-left:20px;
}
UL LI.SiteMapHome {
	list-style-type:none;
	background:transparent url(modules/pages/images/ico_home.png) no-repeat top left;
	padding-left:20px;
}
UL LI.SiteMapFolder A {
	color:#000;
}
UL LI.SiteMapPage A {
	color:#000;
}
/* Tabs classes */

.tabs {
	float: left;
	width: 100%;
	line-height: normal;
	background-image: url("modules/pages/images/xp/tabs_bg.gif");
}

.tabs ul {
	margin: 0;
	padding: 0 0 0;
	list-style: none;
}

.tabs li {
	float: left;
	background: url("modules/pages/images/xp/tab_bg.gif") no-repeat left top;
	margin: 0;
	margin-left: 0;
	margin-right: 2px;
	padding: 0 0 0 10px;
	line-height: 18px;
}

.tabs li.current {
	background: url("modules/pages/images/xp/tab_sel_bg.gif") no-repeat left top;
	margin-right: 2px;
}

.tabs span {
	float: left;
	display: block;
	background: url("modules/pages/images/xp/tab_end.gif") no-repeat right top;
	padding: 0px 10px 0 0;
}

.tabs .current span {
	background: url("modules/pages/images/xp/tab_sel_end.gif") no-repeat right top;
}

.tabs a {
	text-decoration: none;
	font-family: Verdana, Arial;
	font-size: 10px;
}

.tabs a:link, .tabs a:visited, .tabs a:hover {
	color: black;
}

.tabs a:hover {
}

.tabs .current {
}

.tabs .current a, .tabs .current a:link, .tabs .current a:visited {
}

.panel_wrapper div.panel {
	display: none;
}

.panel_wrapper div.current {
	display: block;
	width: 100%;
	height: 500px;
	overflow: auto; /* Should be auto but that breaks Safari */
}

.panel_wrapper {
	border: 1px solid #919B9C;
	border-top: 0px;
	padding: 10px;
	padding-top: 5px;
	clear: both;
	background-color: white;
}

fieldset {
	border: 1px solid #919B9C;
	font-family: Verdana, Arial;
	font-size: 10px;
	padding: 0;
	margin: 0;
	padding: 4px;
}

legend {
	color: #2B6FB6;
	font-weight: bold;
}

.properties {
	width: 100%;
}

.properties .column1 {
}

.properties .column2 {
	text-align: left;
}

a:link, a:visited {
	color: black;
}

a:hover {
	color: #2B6FB6;
}

#plugintable thead {
	font-weight: bold;
	background-color: #DDDDDD;
}

#plugintable, #about #plugintable td {
	border: 1px solid #919B9C;
}

#plugintable {
	width: 99%;
	margin-top: 10px;
}

#pluginscontainer {
	height: 290px;
	overflow: auto;
}
#SiteMap {
	float:left;
	width:300px;
	margin-right:20px;
}
#ContentArea {
	float:right;
	width:700px;
}
li.sortme {
cursor:pointer;
border-bottom:#000000 1px dotted;
}
//--></style>
<script language="javascript" type="text/javascript" src="modules/pages/js/mctabs.js"></script>
<?php
function DisplayTemplates() {
GLOBAL $search,$CONFIG,$Config,$_REQUEST,$orderby,$Details,$error;
	?>
	<h1>Viewing your site: <?=(isset($_REQUEST["nav_id"]))?'/'.$Details["nav_name"]:'';?></h1>
	<?=(isset($error))?$error:'';?>
	<div id="SiteMap">
		<ul>
		<li class="SiteMapHome"> <a href="<?=FCPATHNICE;?>?mod=<?=$_REQUEST["mod"];?>&sub=<?=$_REQUEST["sub"];?>&homedir=pages"><?=$CONFIG['site']['url'].$Config["pages"]["doc_root_nice"];?></a>
			<ul>
			<?=DisplaySiteDirectories();?>
			</ul>
		</li>
		</ul>
	</div>
	
	<div id="ContentArea">
		<form name="Form" method="post" action="<?=$_SERVER["PHP_SELF"];?>">
		<input type="hidden" name="id" value="<?php if(isset($_REQUEST["id"]) && $_REQUEST["id"]!="") { echo $_REQUEST["id"]; } ?>" />
		<input type="hidden" name="nav_id" value="<?php if(isset($_REQUEST["nav_id"]) && $_REQUEST["nav_id"]!="") { echo $_REQUEST["nav_id"]; } ?>" />
		<input type="hidden" name="homedir" value="<?php if(isset($_REQUEST["homedir"]) && $_REQUEST["homedir"]!="") { echo $_REQUEST["homedir"]; } ?>" />
		<input type="hidden" name="mod" value="<?=$_REQUEST["mod"];?>" />
		<input type="hidden" name="sub" value="<?=$_REQUEST["sub"];?>" />
		<input type="hidden" name="task" value="save" />
		<?php if(isset($_REQUEST["id"])) { ?>
		<script language="javascript" type="text/javascript" src="<?=FCPATHNICE;?>plugins/tiny_mce/tiny_mce_gzip.php"></script>
<script language="javascript" type="text/javascript">
	tinyMCE.init({
	mode : "exact",
	elements : "page_html",
	theme : "advanced",
	plugins : "fullpage,iforms,ibrowser,iespell,table,advhr,advimage,advlink,emotions,iespell,contextmenu,paste,directionality",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_buttons1 : "formatselect,fontselect,fontsizeselect,bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,separator,bullist,numlist,outdent,indent,separator,undo,redo",
	theme_advanced_buttons2 : "tablecontrols,ibrowser,iforms,separator,link,unlink,anchor,separator,charmap,sub,sup,separator,code",
	theme_advanced_buttons3 : "",
	theme_advanced_styles : "Grey Text=Teaser;White Text=Plain;Picture Left=Picleft;Picture Right=PicRight",
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
	extended_valid_elements : "iframe[id|src|border|width|height|frameborder|marginheight|marginwidth|scrolling],script[src|language|type],div[id|class|name],php,span[class],form[name|id|action|method|enctype|accept-charset|onsubmit|onreset|target|style|class|summary],input[id|name|type|value|size|maxlength|checked|accept|src|width|height|disabled|readonly|tabindex|accesskey|onfocus|onblur|onchange|onselect|onclick|required|style|class|summary],textarea[id|name|rows|cols|disabled|readonly|tabindex|accesskey|onfocus|onblur|onchange|onselect|onclick|required|style|class|summary],option[name|id|value|selected|style|class|summary],select[id|name|type|value|size|maxlength|checked|accept|src|width|height|disabled|readonly|tabindex|accesskey|onfocus|onblur|onchange|onselect|onclick|length|options|selectedIndex|required|style|class|summary]",
	remove_linebreaks: false,
	document_base_url : "<?=$_SERVER["SERVER_NAME"];?>"
	});
</script>
		<input type="hidden" name="mandatory" value="page_name,template_id,nav_id" />
		<div id="Controls">
			<div><input type="image" src="<?=TEMPLATEPATHNICE.'images/save_f2.png';?>" alt="" name="save" /><br />save</div>
			<div><input type="image" src="<?=TEMPLATEPATHNICE.'images/preview_f2.png';?>" alt="" name="preview" /><br />preview</div>
			<?php if($_REQUEST["id"]>0) { ?><div><input type="image" src="<?=TEMPLATEPATHNICE.'images/delete_f2.png';?>" border="0" alt="" name="remove" onclick="if(!confirm('Warning! Clicking OK will permanently remove this page')) { return false; }" /><br />delete</div>
			<div><input type="image" src="<?=TEMPLATEPATHNICE.'images/publish_f2.png';?>" border="0" alt="" name="publish" onclick="if(!confirm('Click OK to publish this page LIVE to the internet')) { return false; }" /><br />publish</div><?php } ?>
		</div>
		<div style="clear:both;">&nbsp;</div>
		<br />
		<div class="tabs">
			<ul>
				<li id="general_tab" class="current"><span><a href="javascript:mcTabs.displayTab('general_tab','general_panel');" onmousedown="return false;">Page Information</a></span></li>
				<li id="seo_tab"><span><a href="javascript:mcTabs.displayTab('seo_tab','seo_panel');" onmousedown="return false;">Search Engine Optimisation</a></span></li>
				<li id="popup_tab"><span><a href="javascript:mcTabs.displayTab('popup_tab','popup_panel');" onmousedown="return false;">Redirection</a></span></li>
				<li id="content_tab"><span><a href="javascript:mcTabs.displayTab('content_tab','content_panel');" onmousedown="return false;">Edit Content</a></span></li>
			</ul>
		</div>
		<div class="panel_wrapper">
			<div id="general_panel" class="panel current">
				<div<?=HighlightMandatory("page_name");?>>
					<div>Page name <?= ShowMandatory("page_name"); ?></div>
					<input type="text" size="40" name="page_name" value="<?= ShowDataText("page_name"); ?>" />
				</div>
		
				<div<?=HighlightMandatory("template_id");?>>
					<div>Template <?= ShowMandatory("template_id"); ?></div>
					<select name="template_id">
					<option value="">-- select option --</option>
					<?=PopulateSelect("pages_templates","template_id","template_name",ShowDataText("template_id"),"");?>
					</select>
				</div>
				
				<div<?=HighlightMandatory("nav_id");?>>
					<div>Assigned to <?= ShowMandatory("nav_id"); ?></div>
					<select name="nav_id">
					<option value="">-- select option --</option>
					<?=PopulateSelect("pages_navigation","nav_id","nav_name",ShowDataText("nav_id"),"");?>
					</select>
				</div>
				
				<div>
					<div>Optional Sub-section</div>
					<select name="sub_id">
					<option value="">-- select option --</option>
					<?=PopulateSelect("pages","page_id","page_name",ShowDataText("sub_id"),"nav_id='".ShowDataText("nav_id")."' AND sub_id='0'");?>
					</select>
				</div>
	
				<div<?=HighlightMandatory("file_name");?>>
					<div>File name (no spaces, dots, apostrophies, etc)</div>
					<input type="text" size="40" name="page_file_name" value="<?= ShowDataText("page_file_name"); ?>" />
				</div>
		
				<div<?=HighlightMandatory("in_nav");?>>
					<label>Display in navigation</label>
					<input type="checkbox" name="in_nav" value="Y"<?=ShowChecked("Y","in_nav",$Details);?> onclick="if(this.checked==true) { MM_findObj('W_page_link_title').style.display='';MM_findObj('W_page_link_order').style.display=''; } else { MM_findObj('W_page_link_title').style.display='none';MM_findObj('W_page_link_order').style.display='none'; }" /> Yes
				</div>
				
				<div id="W_page_link_title" style="display:<?=(ShowDataText('in_nav')=='Y')?'':'none;';?>"<?=HighlightMandatory("page_link_title");?>>
					<div>Title to display <?= ShowMandatory("page_link_title"); ?></div>
					<input type="text" size="40" name="page_link_title" value="<?= ShowDataText("page_link_title"); ?>" />
				</div>	
				
				<div id="W_page_link_order" style="display:<?=(ShowDataText('in_nav')=='Y')?'':'none;';?>"<?=HighlightMandatory("sort_order");?>>
					<?php if(ShowDataText("nav_id") > 0) { ?>
					<script>
					window.addEvent('domready', function() {
						new Sortables($('Sortable1'), {
							onComplete: function() {
								new Ajax("modules/pages/pages/sortable.php?order="+this.serialize(function(el) {
								return el.id.replace("item_","");
								})).request()
							}
						});
					});
					</script>
					<style>
					ul#Sortable1 { 
					position: inherit;
					}
					li.sortme {
					cursor:pointer;
					border-bottom:#000000 1px dotted;
					}
					</style>
					<ol id="Sortable1">
					<?php
					$psql=@mysql_query("SELECT * FROM pages WHERE nav_id='".$Details["nav_id"]."' AND in_nav='Y' ORDER BY sort_order ASC");
					while($prow=@mysql_fetch_array($psql)) {
					?><li class="sortme" id="P<?=$prow["page_id"];?>"><?=stripslashes($prow["page_name"]);?></li><?php
					}
					?>
					</ol>
					<?php } ?>
				</div>
				
				<div<?=HighlightMandatory("is_home");?>>
					<label>Make home page</label>
					<input type="checkbox" name="is_home" value="Y"<?=ShowChecked("Y","is_home",$Details);?> onclick="if(this.checked==true) { alert('Ticking this box will cause your home page to be overwritten with this one when you next publish it.'); }" /> Yes
				</div>
				
				<?php if($_REQUEST["id"]>0) { ?>
				<div<?=HighlightMandatory("page_status");?>>
					<label>Status</label>
					<?=($Details["page_status"]>0)?$Config["pages"]["status"][$Details["page_status"]]:$Config["pages"]["status"][0];?>
				</div>
				<div<?=HighlightMandatory("page_status");?>>
					<label>Date Created</label>
					<?=$Details["date_created"];?>
				</div>
				<div<?=HighlightMandatory("page_status");?>>
					<label>Last Updated</label>
					<?=$Details["last_updated"];?>
				</div>
				<?php } ?>
			</div>
			<div id="popup_panel" class="panel">
			
			</div>
			<div id="seo_panel" class="panel">
				<div class="TextPromptGrey">Using the fields below you are able to tailor this page for search engine spiders. By providing a relevant description and keywords that match the page content you significantly increase the chance of this page being ranked.</div>
				<div<?=HighlightMandatory("page_title");?>>
					<div>Page Title (optional)</div>
					<input type="text" size="40" name="page_title" value="<?=ShowDataText("page_title");?>" />
				</div>
		
				<div<?=HighlightMandatory("page_description");?>>
					<div>Page Description </div>
					<textarea name="page_description" rows="6" cols="40"><?=ShowDataText("page_description");?></textarea>
				</div>
		
				<div<?=HighlightMandatory("page_keywords");?>>
					<div>Keywords (separate by commas)</div>
					<textarea name="page_keywords" rows="6" cols="40"><?=ShowDataText("page_keywords");?></textarea>
				</div>
			</div>
			<div id="content_panel" class="panel">
				<textarea name="page_html" id="page_html" rows="50" cols="50" style="width:650px;height:480px;" class="mceEditor"><?= str_replace(array("<",">"),array("&lt;","&gt;"),ShowDataText("page_html")); ?></textarea>
			</div>
		</div>
		
		<?php } else if(isset($_REQUEST["nav_id"])) { ?>
		<input type="hidden" name="mandatory" value="nav_name" />
		<div id="Controls">
			<div><input type="image" src="<?=TEMPLATEPATHNICE.'images/save_f2.png';?>" alt="" name="save" /><br />save</div>
			<?php if($_REQUEST["nav_id"]) { ?><div><input type="image" src="<?=TEMPLATEPATHNICE.'images/delete_f2.png';?>" border="0" alt="" name="remove" onclick="if(!confirm('Warning! Clicking OK will permanently remove this directory')) { return false; }" /><br />delete</div><?php } ?>
		</div>
		<div style="clear:both;">&nbsp;</div>
		<br />
		<div class="tabs">
			<ul>
				<li id="general_tab" class="current"><span><a href="javascript:mcTabs.displayTab('general_tab','general_panel');" onmousedown="return false;">Directory Information</a></span></li>
				<li id="popup_tab"><span><a href="javascript:mcTabs.displayTab('popup_tab','popup_panel');" onmousedown="return false;">Create Sub Directory</a></span></li>
				<li id="password_tab"><span><a href="javascript:mcTabs.displayTab('password_tab','password_panel');" onmousedown="return false;">Password Protection</a></span></li>
				<li id="page_tab"><span><a href="javascript:mcTabs.displayTab('page_tab','page_panel');" onmousedown="return false;">Create New Page</a></span></li>
			</ul>
		</div>
		<div class="panel_wrapper">
			<div id="general_panel" class="panel current">
				<div class="TextPrompt">Use the fields below to modify this directory.</div>
					<div<?=HighlightMandatory("nav_name");?>>
						<div>Name <?= ShowMandatory("nav_name"); ?></div>
						<input type="text" size="40" name="nav_name" value="<?= ShowDataText("nav_name"); ?>" />
					</div>
			
					<div<?=HighlightMandatory("nav_dir");?>>
						<div>Physical directory name (optional field, above value used if left blank)</div>
						<input type="text" size="40" name="nav_dir" value="<?= ShowDataText("nav_dir"); ?>" <?php if($_REQUEST["nav_id"]>0) { ?>onfocus="if(this.value!='') { if(confirm('Please note, if you change this value you will also need to change any links linking to pages within this directory. Would you like us to try and redirect all the links for you?')) { MM_findObj('DivRelink').style.display=''; } else { MM_findObj('DivRelink').style.display='none'; } }"<?php } ?> />
					</div>
			</div>
			<div id="popup_panel" class="panel">
				<div class="TextPrompt">To create a sub-directory within /<?=$_REQUEST["nav_id"];?>/, enter a directory name below.</div>
					<div<?=HighlightMandatory("nav_name");?>>
						<div>Name <?= ShowMandatory("sub_name"); ?></div>
						<input type="text" size="40" name="sub_name" />
					</div>
			
					<div<?=HighlightMandatory("nav_dir");?>>
						<div>Physical directory name (optional field, above value used if left blank)</div>
						<input type="text" size="40" name="sub_dir" />
					</div>
			
					<?php if(MySQLResult("SELECT count(*) FROM pages_navigation WHERE nav_dir!='".$_REQUEST["nav_id"]."'")>0) { ?>
					<div>
						<div>Sublevel of (optional)</div>
						<select name="sub_id">
						<option value="">-- Select level (optional) --</option>
						<?=PopulateSelect("pages_navigation","nav_id","nav_name",$Details["nav_id"],"1");?>
						</select>
					</div>
					<?php } ?>
			</div>
			<div id="password_panel" class="panel">
				<div class="TextPrompt">If you wish to protect all content within this directory simply enter a username and passsword in the fields below.</div>
					<div<?=HighlightMandatory("nav_name");?>>
						<div>Username <?= ShowMandatory("nav_username"); ?></div>
						<input type="text" size="40" name="nav_username" value="<?= ShowDataText("nav_username"); ?>" />
					</div>
			
					<div<?=HighlightMandatory("nav_name");?>>
						<div>Password <?= ShowMandatory("nav_password"); ?></div>
						<input type="text" size="40" name="nav_password" value="<?= ShowDataText("nav_password"); ?>" />
					</div>
			</div>
			<div id="page_panel" class="panel">
				<div class="TextPrompt">To create a new page within the directory /<?=$_REQUEST["nav_id"];?>/ fill out the details below.</div>
				<div<?=HighlightMandatory("page_name");?>>
					<div>Page name <?= ShowMandatory("page_name"); ?></div>
					<input type="text" size="40" name="page_name" value="<?= ShowDataText("page_name"); ?>" />
				</div>
		
				<div<?=HighlightMandatory("template_id");?>>
					<div>Template <?= ShowMandatory("template_id"); ?></div>
					<select name="template_id">
					<option value="">-- select option --</option>
					<?=PopulateSelect("pages_templates","template_id","template_name",ShowDataText("template_id"),"");?>
					</select>
				</div>
				
				<div<?=HighlightMandatory("nav_id");?>>
					<div>Assigned to <?= ShowMandatory("nav_id"); ?></div>
					<select name="nav_id">
					<option value="">-- select option --</option>
					<?=PopulateSelect("pages_navigation","nav_id","nav_name",ShowDataText("nav_id"),"");?>
					</select>
				</div>
				
				
	
				<div<?=HighlightMandatory("file_name");?>>
					<div>File name (no spaces, dots, apostrophies, etc)</div>
					<input type="text" size="40" name="page_file_name" value="<?= ShowDataText("page_file_name"); ?>" />
				</div>
		
				<div<?=HighlightMandatory("in_nav");?>>
					<label>Display in navigation</label>
					<input type="checkbox" name="in_nav" value="Y" onclick="if(this.checked==true) { MM_findObj('W_page_link_title').style.display='';MM_findObj('W_page_link_order').style.display=''; } else { MM_findObj('W_page_link_title').style.display='none';MM_findObj('W_page_link_order').style.display='none'; }" /> Yes
				</div>
				
				<div id="W_page_link_title" style="display:<?=(ShowDataText('in_nav')=='Y')?'':'none;';?>"<?=HighlightMandatory("page_link_title");?>>
					<div>Title to display <?= ShowMandatory("page_link_title"); ?></div>
					<input type="text" size="40" name="page_link_title" value="<?= ShowDataText("page_link_title"); ?>" />
				</div>
			</div>
		</div>
		<?php } else if(isset($_REQUEST["homedir"])) { ?>
		<input type="hidden" name="mandatory" value="nav_name" />
		<div id="Controls">
			<div><input type="image" src="<?=TEMPLATEPATHNICE.'images/save_f2.png';?>" alt="" name="save" /><br />save</div>
		</div>
		<div style="clear:both;">&nbsp;</div>
		<br />
		<div class="tabs">
			<ul>
				<li id="popup_tab" class="current"><span><a href="javascript:mcTabs.displayTab('popup_tab','popup_panel');" onmousedown="return false;">Create Sub Directory</a></span></li>
				<li id="password_tab"><span><a href="javascript:mcTabs.displayTab('password_tab','password_panel');" onmousedown="return false;">Password Protection</a></span></li>
				<li id="page_tab"><span><a href="javascript:mcTabs.displayTab('page_tab','page_panel');" onmousedown="return false;">Create New Page</a></span></li>
			</ul>
		</div>
		<div class="panel_wrapper">
			<div id="popup_panel" class="panel current">
				<div class="TextPrompt">To create a sub-directory within /<?=$_REQUEST["homedir"];?>/, enter a directory name below.</div>
					<div<?=HighlightMandatory("nav_name");?>>
						<div>Name <?= ShowMandatory("nav_name"); ?></div>
						<input type="text" size="40" name="nav_name" />
					</div>
			
					<div<?=HighlightMandatory("nav_dir");?>>
						<div>Physical directory name (optional field, above value used if left blank)</div>
						<input type="text" size="40" name="nav_dir" />
					</div>
			</div>
			<div id="password_panel" class="panel">
				<div class="TextPrompt">If you wish to protect all content within this directory simply enter a username and passsword in the fields below.</div>
					<div<?=HighlightMandatory("nav_name");?>>
						<div>Username <?= ShowMandatory("nav_username"); ?></div>
						<input type="text" size="40" name="nav_username" value="<?= ShowDataText("nav_username"); ?>" />
					</div>
			
					<div<?=HighlightMandatory("nav_name");?>>
						<div>Password <?= ShowMandatory("nav_password"); ?></div>
						<input type="text" size="40" name="nav_password" value="<?= ShowDataText("nav_password"); ?>" />
					</div>
			</div>
			<div id="page_panel" class="panel">
				<div class="TextPrompt">To create a new page within the directory /<?=$_REQUEST["homedir"];?>/ fill out the details below.</div>
				<div<?=HighlightMandatory("page_name");?>>
					<div>Page name <?= ShowMandatory("page_name"); ?></div>
					<input type="text" size="40" name="page_name" value="<?= ShowDataText("page_name"); ?>" />
				</div>
		
				<div<?=HighlightMandatory("template_id");?>>
					<div>Template <?= ShowMandatory("template_id"); ?></div>
					<select name="template_id">
					<option value="">-- select option --</option>
					<?=PopulateSelect("pages_templates","template_id","template_name",ShowDataText("template_id"),"");?>
					</select>
				</div>
				
				<div<?=HighlightMandatory("nav_id");?>>
					<div>Assigned to <?= ShowMandatory("nav_id"); ?></div>
					<select name="nav_id">
					<option value="">-- select option --</option>
					<?=PopulateSelect("pages_navigation","nav_id","nav_name",ShowDataText("nav_id"),"");?>
					</select>
				</div>
				
				<div>
					<div>Optional Sub-section</div>
					<select name="sub_id">
					<option value="">-- select option --</option>
					<?=PopulateSelect("pages","page_id","page_name",ShowDataText("sub_id"),"nav_id='".ShowDataText("nav_id")."' AND sub_id='0'");?>
					</select>
				</div>
	
				<div<?=HighlightMandatory("file_name");?>>
					<div>File name (no spaces, dots, apostrophies, etc)</div>
					<input type="text" size="40" name="page_file_name" value="<?= ShowDataText("page_file_name"); ?>" />
				</div>
		
				<div<?=HighlightMandatory("in_nav");?>>
					<label>Display in navigation</label>
					<input type="checkbox" name="in_nav" value="Y" onclick="if(this.checked==true) { MM_findObj('W_page_link_title').style.display='';MM_findObj('W_page_link_order').style.display=''; } else { MM_findObj('W_page_link_title').style.display='none';MM_findObj('W_page_link_order').style.display='none'; }" /> Yes
				</div>
				
				<div id="W_page_link_title" style="display:<?=(ShowDataText('in_nav')=='Y')?'':'none;';?>"<?=HighlightMandatory("page_link_title");?>>
					<div>Title to display <?= ShowMandatory("page_link_title"); ?></div>
					<input type="text" size="40" name="page_link_title" value="<?= ShowDataText("page_link_title"); ?>" />
				</div>
			</div>
		</div>
		<?php } ?>
		</form>
	</div>
	
	<div style="clear:both;">&nbsp;</div>
	<?php
}

function DisplaySiteDirectories($sub_id='') {
GLOBAL $search,$CONFIG,$Config,$_REQUEST,$orderby;
$HTML='';
$x=1;
	$WHERE=($sub_id>0)?' AND sub_id='.$sub_id:' AND sub_id=0';
	$sql=mysql_query("SELECT * FROM pages_navigation WHERE 1 $WHERE ORDER BY nav_name ASC");
	while($row=mysql_fetch_array($sql)) {
		$HTML.='<li class="SiteMapFolder""><a href="'.FCPATHNICE.'?mod='.$_REQUEST["mod"].'&sub='.$_REQUEST["sub"].'&nav_id='.$row["nav_id"].'">'.stripslashes($row["nav_name"]).'</a>';
		
			##pages?
			if(MySQLResult("SELECT count(*) FROM pages WHERE nav_id='".$row["nav_id"]."'")>0) {
			//$HTML.='<ul id="Sortable'.$x.'">';
				
				$pagesql=@mysql_query("SELECT * FROM pages WHERE nav_id='".$row["nav_id"]."' ORDER BY sort_order ASC");
				while($pagerow=mysql_fetch_array($pagesql)) {
					if($pagerow["sub_id"]>0) {
					$HTML.='<li class="SiteMapPageIndent" id="P'.$pagerow["page_id"].'"><a href="'.FCPATHNICE.'?mod='.$_REQUEST["mod"].'&sub='.$_REQUEST["sub"].'&id='.$pagerow["page_id"].'">'.stripslashes($pagerow["page_name"]).'</a></li>';
					}
					else {
					$HTML.='<li class="SiteMapPage" id="P'.$pagerow["page_id"].'"><a href="'.FCPATHNICE.'?mod='.$_REQUEST["mod"].'&sub='.$_REQUEST["sub"].'&id='.$pagerow["page_id"].'">'.stripslashes($pagerow["page_name"]).'</a></li>';
					}
				}
				
			//$HTML.='</ul>';
			}
			
			if(MySQLResult("SELECT count(*) FROM pages_navigation WHERE sub_id='".$row["nav_id"]."'")>0) {
			$HTML.='<ul>';
			$HTML.=DisplaySiteDirectories($row["nav_id"]);
			$HTML.='</ul>';
			}
		
			
		$HTML.='</li>';
		
		
		$x++;
		
	}
return $HTML;
}

?>