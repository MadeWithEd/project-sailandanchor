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
SPAN.status {display:block;float:right;width:100px;}
SPAN.order {display:block;float:right;width:80px;}
SPAN.modify {display:block;float:right;width:80px;}
SPAN.lastupdated {display:block;float:right;width:140px;}
SPAN.view {display:block;float:right;width:40px;}
</style>
	
	<h1>Content</h1>
	
	<p>This section is for Admins only. Please be careful editing this content below.</p>
	
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
	
	
	//echo "SELECT * FROM pages WHERE 1 $WHERE ORDER BY sort_order ASC";
	
	if($sql=mysql_query("SELECT * FROM pages WHERE 1 $WHERE ORDER BY sort_order ASC")) {
		while($row=mysql_fetch_array($sql)) {
		
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
	}
	else {
	echo mysql_error();
	exit;
	}
return $HTML;
}

function DisplayTemplateForm($Details) {
GLOBAL $Config;
$IsSecondLevel=false;
	if($Details["page_id"]>0) {
	$sql=mysql_query("SELECT page_id,sub_id FROM pages WHERE sub_id='".$Details["page_id"]."'");
		//if(count($sql)>0) {	
			while($row=mysql_fetch_array($sql)) {
				if(MySQLResult("SELECT count(*) FROM pages WHERE sub_id='".$row["page_id"]."'")>0) {
				$IsSecondLevel=true;
				}
			}
		//}
	}
?>
	<script language="javascript" type="text/javascript" src="modules/content/js/jquery.js"></script>
	<script language="javascript" type="text/javascript" src="modules/content/js/mctabs.js"></script>
	<script language="javascript" type="text/javascript" src="modules/content/js/calendarDateInput.js"></script>
	
	<link rel="stylesheet" type="text/css" href="modules/content/css/style.css" />
	
	<style>
	.left50 {float:left;width:49%;}
	.right50 {float:right;width:49%;}
	</style>
	
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
		<div><input type="image" src="<?=TEMPLATEPATHNICE.'images/cancel_f2.png';?>" alt="" name="cancel" /><br />cancel</div>
	</div>
	<div style="clear:both;">&nbsp;</div>
	
	<?php if($Details["page_type"]!="php") { ?>
	<script language="javascript" type="text/javascript" src="<?=FCPATHNICE;?>plugins/tiny_mce3/tiny_mce.js"></script>
	<script type="text/javascript" src="<?=FCPATHNICE;?>plugins/tiny_mce3/plugins/tinybrowser/tb_tinymce.js.php"></script>
	<script language="javascript" type="text/javascript">
	tinyMCE.init({
	mode : "exact",
	elements : "page_html,page_donation_title",
	theme : "advanced",
	plugins : "emotions,spellchecker,advhr,insertdatetime,preview,advimage,table",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_buttons1 : "formatselect,bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,separator,bullist,numlist,outdent,indent,separator,undo,redo",
	theme_advanced_buttons2 : "tablecontrols,insertimage,link,unlink,anchor,separator,charmap,sub,sup,separator,code,separator,image",
	theme_advanced_buttons3 : "",
	theme_advanced_styles : "left50=left50,right50=right50",
	content_css : "/css/tiny.css",
	//theme_advanced_disable : "styleselect,formatselect,image,cleanup,help,hr",
	file_browser_callback : "tinyBrowser",
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
	valid_elements : ""
+"a[accesskey|charset|class|coords|dir<ltr?rtl|href|hreflang|id|lang|name"
  +"|onblur|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup"
  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|rel|rev"
  +"|shape<circle?default?poly?rect|style|tabindex|title|target|type],"
+"abbr[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"acronym[class|dir<ltr?rtl|id|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"address[class|align|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
  +"|onmouseup|style|title],"
+"applet[align<bottom?left?middle?right?top|alt|archive|class|code|codebase"
  +"|height|hspace|id|name|object|style|title|vspace|width],"
+"area[accesskey|alt|class|coords|dir<ltr?rtl|href|id|lang|nohref<nohref"
  +"|onblur|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup"
  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup"
  +"|shape<circle?default?poly?rect|style|tabindex|title|target],"
+"base[href|target],"
+"basefont[color|face|id|size],"
+"bdo[class|dir<ltr?rtl|id|lang|style|title],"
+"big[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"blockquote[cite|class|dir<ltr?rtl|id|lang|onclick|ondblclick"
  +"|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout"
  +"|onmouseover|onmouseup|style|title],"
+"body[alink|background|bgcolor|class|dir<ltr?rtl|id|lang|link|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onload|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|onunload|style|title|text|vlink],"
+"br[class|clear<all?left?none?right|id|style|title],"
+"button[accesskey|class|dir<ltr?rtl|disabled<disabled|id|lang|name|onblur"
  +"|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup|onmousedown"
  +"|onmousemove|onmouseout|onmouseover|onmouseup|style|tabindex|title|type"
  +"|value],"
+"caption[align<bottom?left?right?top|class|dir<ltr?rtl|id|lang|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|style|title],"
+"center[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"cite[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"code[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"col[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl|id"
  +"|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
  +"|onmousemove|onmouseout|onmouseover|onmouseup|span|style|title"
  +"|valign<baseline?bottom?middle?top|width],"
+"colgroup[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl"
  +"|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
  +"|onmousemove|onmouseout|onmouseover|onmouseup|span|style|title"
  +"|valign<baseline?bottom?middle?top|width],"
+"dd[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
+"del[cite|class|datetime|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
  +"|onmouseup|style|title],"
+"dfn[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"dir[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
  +"|onmouseup|style|title],"
+"div[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|style|title],"
+"dl[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
  +"|onmouseup|style|title],"
+"dt[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
+"em/i[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"fieldset[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"font[class|color|dir<ltr?rtl|face|id|lang|size|style|title],"
+"form[accept|accept-charset|action|class|dir<ltr?rtl|enctype|id|lang"
  +"|method<get?post|name|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|onreset|onsubmit"
  +"|style|title|target],"
+"frame[class|frameborder|id|longdesc|marginheight|marginwidth|name"
  +"|noresize<noresize|scrolling<auto?no?yes|src|style|title],"
+"frameset[class|cols|id|onload|onunload|rows|style|title],"
+"h1[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|style|title],"
+"h2[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|style|title],"
+"h3[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|style|title],"
+"h4[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|style|title],"
+"h5[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|style|title],"
+"h6[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|style|title],"
+"head[dir<ltr?rtl|lang|profile],"
+"hr[align<center?left?right|class|dir<ltr?rtl|id|lang|noshade<noshade|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|size|style|title|width],"
+"html[dir<ltr?rtl|lang|version],"
+"iframe[align<bottom?left?middle?right?top|class|frameborder|height|id"
  +"|longdesc|marginheight|marginwidth|name|scrolling<auto?no?yes|src|style"
  +"|title|width],"
+"img[align<bottom?left?middle?right?top|alt|border|class|dir<ltr?rtl|height"
  +"|hspace|id|ismap<ismap|lang|longdesc|name|onclick|ondblclick|onkeydown"
  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
  +"|onmouseup|src|style|title|usemap|vspace|width],"
+"input[accept|accesskey|align<bottom?left?middle?right?top|alt"
  +"|checked<checked|class|dir<ltr?rtl|disabled<disabled|id|ismap<ismap|lang"
  +"|maxlength|name|onblur|onclick|ondblclick|onfocus|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|onselect"
  +"|readonly<readonly|size|src|style|tabindex|title"
  +"|type<button?checkbox?file?hidden?image?password?radio?reset?submit?text"
  +"|usemap|value],"
+"ins[cite|class|datetime|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
  +"|onmouseup|style|title],"
+"isindex[class|dir<ltr?rtl|id|lang|prompt|style|title],"
+"kbd[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"label[accesskey|class|dir<ltr?rtl|for|id|lang|onblur|onclick|ondblclick"
  +"|onfocus|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout"
  +"|onmouseover|onmouseup|style|title],"
+"legend[align<bottom?left?right?top|accesskey|class|dir<ltr?rtl|id|lang"
  +"|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|style|title],"
+"li[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title|type"
  +"|value],"
+"link[charset|class|dir<ltr?rtl|href|hreflang|id|lang|media|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|rel|rev|style|title|target|type],"
+"map[class|dir<ltr?rtl|id|lang|name|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"menu[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
  +"|onmouseup|style|title],"
+"meta[content|dir<ltr?rtl|http-equiv|lang|name|scheme],"
+"noframes[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"noscript[class|dir<ltr?rtl|id|lang|style|title],"
+"object[align<bottom?left?middle?right?top|archive|border|class|classid"
  +"|codebase|codetype|data|declare|dir<ltr?rtl|height|hspace|id|lang|name"
  +"|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|standby|style|tabindex|title|type|usemap"
  +"|vspace|width],"
+"ol[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
  +"|onmouseup|start|style|title|type],"
+"optgroup[class|dir<ltr?rtl|disabled<disabled|id|label|lang|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|style|title],"
+"option[class|dir<ltr?rtl|disabled<disabled|id|label|lang|onclick|ondblclick"
  +"|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout"
  +"|onmouseover|onmouseup|selected<selected|style|title|value],"
+"p[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|style|title],"
+"param[id|name|type|value|valuetype<DATA?OBJECT?REF],"
+"pre/listing/plaintext/xmp[align|class|dir<ltr?rtl|id|lang|onclick|ondblclick"
  +"|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout"
  +"|onmouseover|onmouseup|style|title|width],"
+"q[cite|class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"s[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
+"samp[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"script[charset|defer|language|src|type],"
+"select[class|dir<ltr?rtl|disabled<disabled|id|lang|multiple<multiple|name"
  +"|onblur|onchange|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup"
  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|size|style"
  +"|tabindex|title],"
+"small[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"span[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
  +"|onmouseup|style|title],"
+"strike[class|class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
  +"|onmouseup|style|title],"
+"strong/b[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"style[dir<ltr?rtl|lang|media|title|type],"
+"sub[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"sup[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title],"
+"table[align<center?left?right|bgcolor|border|cellpadding|cellspacing|class"
  +"|dir<ltr?rtl|frame|height|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|rules"
  +"|style|summary|title|width],"
+"tbody[align<center?char?justify?left?right|char|class|charoff|dir<ltr?rtl|id"
  +"|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
  +"|onmousemove|onmouseout|onmouseover|onmouseup|style|title"
  +"|valign<baseline?bottom?middle?top],"
+"td[abbr|align<center?char?justify?left?right|axis|bgcolor|char|charoff|class"
  +"|colspan|dir<ltr?rtl|headers|height|id|lang|nowrap<nowrap|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|rowspan|scope<col?colgroup?row?rowgroup"
  +"|style|title|valign<baseline?bottom?middle?top|width],"
+"textarea[accesskey|class|cols|dir<ltr?rtl|disabled<disabled|id|lang|name"
  +"|onblur|onclick|ondblclick|onfocus|onkeydown|onkeypress|onkeyup"
  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|onselect"
  +"|readonly<readonly|rows|style|tabindex|title],"
+"tfoot[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl|id"
  +"|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
  +"|onmousemove|onmouseout|onmouseover|onmouseup|style|title"
  +"|valign<baseline?bottom?middle?top],"
+"th[abbr|align<center?char?justify?left?right|axis|bgcolor|char|charoff|class"
  +"|colspan|dir<ltr?rtl|headers|height|id|lang|nowrap<nowrap|onclick"
  +"|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove"
  +"|onmouseout|onmouseover|onmouseup|rowspan|scope<col?colgroup?row?rowgroup"
  +"|style|title|valign<baseline?bottom?middle?top|width],"
+"thead[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl|id"
  +"|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown"
  +"|onmousemove|onmouseout|onmouseover|onmouseup|style|title"
  +"|valign<baseline?bottom?middle?top],"
+"title[dir<ltr?rtl|lang],"
+"tr[abbr|align<center?char?justify?left?right|bgcolor|char|charoff|class"
  +"|rowspan|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title|valign<baseline?bottom?middle?top],"
+"tt[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
+"u[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
  +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],"
+"ul[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
  +"|onmouseup|style|title|type],"
+"var[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
  +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
  +"|title]",
	extended_valid_elements : "html,body,head,title,iframe[id|src|border|width|height|frameborder|marginheight|marginwidth|scrolling],script[src|language|type],div[id|class|name|style],php,span[class],form[name|id|action|method|enctype|accept-charset|onsubmit|onreset|target|style|class|summary],input[id|name|type|value|size|maxlength|checked|accept|src|width|height|disabled|readonly|tabindex|accesskey|onfocus|onblur|onchange|onselect|onclick|required|style|class|summary],textarea[id|name|rows|cols|disabled|readonly|tabindex|accesskey|onfocus|onblur|onchange|onselect|onclick|required|style|class|summary],option[name|id|value|selected|style|class|summary],select[id|name|type|value|size|maxlength|checked|accept|src|width|height|disabled|readonly|tabindex|accesskey|onfocus|onblur|onchange|onselect|onclick|length|options|selectedIndex|required|style|class|summary]",
	remove_linebreaks: false,
	document_base_url : "<?=$_SERVER["SERVER_NAME"];?>"
	});
</script>
	<?php } ?>
	
	<?php
	## Home page
	
	if($Details["is_home"]=="Y") {
	?>
	<input type="hidden" name="mandatory" value="page_short_desc" />
	<div class="tabs">
		<ul>
			<li id="one_tab"<?=(!isset($_REQUEST["sub"]))?' class="current"':'';?>><span><a href="javascript:mcTabs.displayTab('one_tab','one_panel');" onmousedown="return false;">About Content</a></span></li>
			<li id="two_tab"><span><a href="javascript:mcTabs.displayTab('two_tab','two_panel');" onmousedown="return false;">Promo Carousel</a></span></li>
			<li id="four_tab"><span><a href="javascript:mcTabs.displayTab('four_tab','four_panel');" onmousedown="return false;">Optional: Search Engine Metadata</a></span></li>
		</ul>
	</div>
	<div class="panel_wrapper">
		<div id="one_panel" class="panel<?=(!isset($_REQUEST["sub"]))?' current':'';?>">
			<div class="TextPromptGrey">The form below allows you to edit the "About" text home page</div>
			<div class="row">
				<label>About Text</label>
				<textarea rows="20" cols="60" name="page_short_desc" id="page_short_desc" style="width:800px"><?=ShowDataText("page_short_desc");?></textarea>
			</div>
			<script>
			tinyMCE.execCommand('mceAddControl', false, 'page_short_desc');
			</script>
		</div>
		<div id="two_panel" class="panel<?=(isset($_REQUEST["sub"]) && $_REQUEST["sub"]=="left")?' current':'';?>">
			<div class="TextPromptGrey">This section allows you to edit the Main carousel on the home page</div>
			<?php
			$x=1;
			$sql=mysql_query("SELECT * FROM home_promos");
			while($row=mysql_fetch_array($sql)) {
			?>
			
			<h2>Promo <?=$x;?></h2>
			
			<div style="float:right;width:400px;">
				<?php if($row["promo_image"]!="" && is_file($Config["content"]["images"].$row["promo_image"])) { ?>
					<img src="<?=$Config["content"]["images_nice"].$row["promo_image"];?>" border="0" alt="" style="display:block;width:350px;" />
				<?php } ?>
				<?php if($row["promo_text"]!="" && is_file($Config["content"]["images"].$row["promo_text"])) { ?>
				<img src="<?=$Config["content"]["images_nice"].$row["promo_text"];?>" border="0" alt="" style="display:block;width:350px;" />
				<?php } ?>
			</div>
			
			<div class="promo" style="float:left;width:60%;clear:none;">
				<input type="hidden" name="promo[]" value="<?=$row["promo_id"];?>" />
				<div class="row">
					<label>Promo name</label>
					<input type="text" name="promo_name[]" size="30" value="<?=ShowDataText("promo_name[]",$row);?>" />
				</div>
				<div class="row">
					<label>Promo url</label>
					<input type="text" name="promo_url[]" size="30" value="<?=ShowDataText("promo_url[]",$row);?>" />
				</div>
				<div class="row">
					<label>Promo image</label>
					<input type="file" name="promo_image[]" value="" /> <span style="font-size:10px;">JPG,PNG</span>
				</div>
				
			</div>
			<div class="clear"></div>
			<?php
			$x++;
			}
			?>
			
			<h2>Add Promo</h2>
			
			<div class="promo">
				<input type="hidden" name="promo[]" value="" />
				<div class="row">
					<label>Promo name</label>
					<input type="text" name="promo_name[]" size="30" value="" />
				</div>
				<div class="row">
					<label>Promo url</label>
					<input type="text" name="promo_url[]" size="30" value="" />
				</div>
				<div class="row">
					<label>Promo image</label>
					<input type="file" name="promo_image[]" value="" />
				</div>
			</div>
			
			<a href="javascript:;" id="AddItem">Add Another Promo</a>
			
			<script>
			$('#AddItem').click(function(){
				$('div.promo:last').clone().appendTo('div.promo:last');
			return false;
			});
			</script>
			
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
	
	## Content page
	
	else {
	?>
	

	<input type="hidden" name="mandatory" value="page_name" />
	<div class="tabs">
		<ul>
			<li id="one_tab"<?=(!isset($_REQUEST["step"]))?' class="current"':'';?>><span><a href="javascript:mcTabs.displayTab('one_tab','one_panel');" onmousedown="return false;">Step 1: Page Details</a></span></li>
			<?php if($_REQUEST["id"]>0) { ?>
			<li id="two_tab"><span><a href="javascript:mcTabs.displayTab('two_tab','two_panel');">Step 2: Page Content</a></span></li>
			<li id="three_tab"><span><a href="javascript:mcTabs.displayTab('three_tab','three_panel');" onmousedown="return false;">Optional: Search Engine Metadata</a></span></li>
			<li id="four_tab"><span><a href="javascript:mcTabs.displayTab('four_tab','four_panel');" onmousedown="return false;">Optional: Side Feature</a></span></li>
			<?php } ?>
		</ul>
	</div>
	<div class="panel_wrapper">
		<div id="one_panel" class="panel<?=(!isset($_REQUEST["step"]))?' current':'';?>">
		
			<h1>Step One: Page Details</h1>
			
			<div class="TextPromptGrey"><b>Page Title and Assignment</b> - Please provide a title for your page and specify under which area it is to appear.</div>
			
			<div<?=HighlightMandatory("page_name");?>>
				<label>Page Title <?= ShowMandatory("page_name"); ?></label>
				<input type="text" size="30" name="page_name" value="<?=ShowDataText("page_name");?>" />
			</div>
			
			<div>
				<label>Status</label>
				<select name="page_status">
				<?=PopulateSelectGeneric(array("0","1","2"),array("Draft","Live","Archived"),ShowDataText("page_status"));?>
				</select>
			</div>
			
			<div<?=HighlightMandatory("sub_id");?>>
				<label>Assigned To <?= ShowMandatory("sub_id"); ?></label>
				<select name="sub_id" id="sub_id" style="width:200px;">
				<option value="">- please select -</option>
				<?php
				$pageswhere=(!$_REQUEST["id"]>0)?' WHERE sub_id !=0':'';
				//$sql=execute_query("SELECT * , page_id AS SUB, (SELECT count( * ) FROM pages WHERE sub_id = SUB ) AS SUBCOUNT FROM `pages` HAVING SUBCOUNT >0");
				$sql=mysql_query("SELECT * , page_id AS SUB, (SELECT count( * ) FROM pages WHERE sub_id = SUB ) AS SUBCOUNT FROM `pages` ");
				while($row=mysql_fetch_array($sql)) {
				?><option value="<?=$row["page_id"];?>"<?=ShowSelected($row["page_id"],"sub_id",$Details);?>><?=DisplayRecursive($row["sub_id"]) . ' - '.stripslashes($row["page_name"]);?></option><?php
				}
				?>
				</select>
			</div>
			
			<div>
				<label>Display on Navigation</label>
				<input type="checkbox" name="in_nav" id="in_nav" value="Y"<?=ShowChecked("Y","in_nav",$Details);?> />
			</div>
			
			<div>
				<label>Hide Left Nav</label>
				<input type="checkbox" name="hide_nav" id="hide_nav" value="Y"<?=ShowChecked("Y","hide_nav",$Details);?> />
			</div>
			
			<div<?=HighlightMandatory("page_file_name");?>>
				<label>Page Url (optional)</label>
				<input type="text" size="30" name="page_file_name" value="<?=ShowDataText("page_file_name");?>" />
			</div>
			
			<div>
				<label>Template</label>
				<select name="section_id">
				<option value=""> - select option -</option>
				<?=PopulateSelectState(array("our beer","limited edition","the pub","whats on","find our beer","contact","product"),ShowDataText("section_id"));?>
				</select>
			</div>
			
			<?php if(is_file($Config["content"]["images"].$Details["page_masthead"])) { ?>
			<div class="row">
				<label>Current Background</label>
				<img src="<?=$Config["content"]["images_nice"].$Details["page_masthead"];?>" border="0" alt="" id="current_image" width="600" />
			</div>
			<div class="row">
				<label>&nbsp;</label>
				<input type="checkbox" name="remove_current_image" value="Y" /> Remove Image
			</div>
			<?php } ?>
			<div class="row">
				<label>Custom Background Image</label>
				<input type="file" id="page_masthead" name="page_masthead" value="Browse for Image" /> (328px high, unlimited width)
			</div>
			
			<div<?=HighlightMandatory("page_type");?>>
				<label>Page Type <?= ShowMandatory("page_type"); ?></label>
				<select name="page_type">
				<?=PopulateSelectState(array("html","php"),ShowDataText("page_type"));?>
				</select>
			</div>
	
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
					<textarea class="editor" rows="15" cols="70" name="page_html" id="page_html" style="width:800px"><?=ShowDataText("page_html");?></textarea>
				</div>
				
				
				<script>
				//tinyMCE.execCommand('mceAddControl', false, 'page_html');
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
				$sql=mysql_query("SELECT * from pages ORDER BY sort_order");
				while($row=mysql_fetch_array($sql)) {
				?><option value="<?=$row["page_id"];?>"<?=ShowSelected($row["page_id"],"redirect_id",$Details);?>><?=DisplayRecursive($row["sub_id"]) . ' - '.stripslashes($row["page_name"]);?></option><?php
				}
				?>
				</select>
			</div>
			<?php
			}
			else {
			?>
				<div>
					<label>Optional Heading</label>
					<input type="text" size="50" name="page_title" id="page_title" value="<?=ShowDataText("page_title");?>" />
				</div>
				<h3>Content Section</h3>
				<textarea name="page_html" id="page_html" rows="30" cols="50" style="width:100%;height:600px;" class="mceEditor"><?= str_replace(array("<",">"),array("&lt;","&gt;"),ShowDataText("page_html")); ?></textarea>
		
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
		
		<div id="four_panel" class="panel<?=(isset($_REQUEST["sub"]) && $_REQUEST["sub"]=="feature")?' current':'';?>">
			<div class="TextPromptGrey">This section allows you to add a featured element to this page's sidebar.</div>
			<div class="row">
				<input type="checkbox" name="featured" value="Y"<?=ShowChecked("Y","featured",$Details);?> /> Yes display a feature on this page
			</div>
			<?php if(is_file($Config["content"]["images"].$Details["featured_image"])) { ?>
			<div class="row">
				<label>Current Image</label>
				<img src="<?=$Config["content"]["images_nice"].$Details["featured_image"];?>" border="0" alt="" id="featured_image" width="245" />
			</div>
			<div class="row">
				<label>&nbsp;</label>
				<input type="checkbox" name="remove_featured_image" value="Y" /> Remove Image
			</div>
			<?php } ?>
			<div class="row">
				<label>Featured Image</label>
				<input type="file" name="featured_image" /> (width: 245px)
			</div>
			<div class="row">
				<label>Featured Title</label>
				<input type="text" size="30" name="featured_title" value="<?=ShowDataText("featured_title");?>" />
			</div>
			<div class="row">
				<label>Featured Copy</label>
				<textarea rows="7" cols="40" name="featured_description"><?=ShowDataText("featured_description");?></textarea>
			</div>
			<div class="row">
				<label>Featured url</label>
				<input type="text" size="30" name="featured_url" value="<?=ShowDataText("featured_url");?>" />
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