<?php
unset($tinyMCE_imglib_include);
$preview='';
include 'config.php';
?>
<html>
<head>
<title>{$lang_ibrowser_title}</title>
<script language="javascript" type="text/JavaScript" src="../../tiny_mce_popup.js"></script>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script language="javascript" type="text/JavaScript"><!--
function selectClick() {
	var inst = tinyMCE.getInstanceById(tinyMCE.getWindowArg('editor_id'));
	var elm = inst.getFocusElement();
	var formObj = document.forms[0];
	//if (elm != null && elm.nodeName == "FORM") {
	
	//}
	//else {
		var html="{form_id=<?=$_REQUEST["form_id"];?>}";
		tinyMCEPopup.execCommand("mceInsertContent", false, html);
	//}
	tinyMCE._setEventsEnabled(inst.getBody(), false);
	tinyMCEPopup.close();
}

function init() {
	if (tinyMCE.getWindowArg('formid') != '') {
		var formObj = document.forms[0];
		var inst = tinyMCE.getInstanceById(tinyMCE.getWindowArg('editor_id'));
		var elm = inst.getFocusElement();
	}
	window.focus();
}
//--></script>
<style type="text/css">
<!--
#libbrowser .previewWindow {
	background-color: #FFFFFF;
}
-->
</style>
</head>
<body onLoad="init()">
<script language="JavaScript" type="text/JavaScript">
    window.name = 'imglibrary';
</script>
<form name="libbrowser" id="libbrowser" method="post" action="iforms.php?" target="imglibrary">
	<fieldset style= "padding: 5 5 5 5; margin-top: -5px;">
		<legend>{$lang_iforms_img_sel}</legend>
		<table width="440" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td>
			<table width="100%"  border="0" cellpadding="2" cellspacing="0">
			<tr>
			<td width="210"><strong>{$lang_iforms_images}:</strong></td>
			<td width="5">&nbsp;</td>
			<td width="210"><strong>{$lang_iforms_preview}:</strong></td>
			</tr>

			<tr>
			<td><select name="form_id" size="15" style="width: 100%;" onChange="document.location.href='iforms.php?form_id='+ this.options[this.selectedIndex].value">
			<?=PopulateSelect("pages_forms","form_id","form_name",ShowDataText("form_id"),"");?>
			</select></td>
			<td>&nbsp;</td>
			<td width="210" align="left" valign="top"><iframe name="imgpreview"  class="previewWindow" src="<?php echo $preview?>" style="width: 100%; height: 100%;" scrolling="Auto" marginheight="0" marginwidth="0" frameborder="0"></iframe></td>
			</tr>

			<tr>
			<td colspan="3">
				<table width="100%"  border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td width="40%"></td>
				<td align="right"><input type="button" name="selectbt" value="{$lang_iforms_select}" class="bt" onClick="selectClick();">
                    <input type="button" value="{$lang_iforms_cancel}" class="bt" onClick="window.close();"></td>
                </tr>
			</table>
		</td>
		</tr>
		</table>
	</td>
	</tr>
	</table>
</fieldset>
</form>
</body>
</html>
