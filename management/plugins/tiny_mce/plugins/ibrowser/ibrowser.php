<?php
// ================================================
// tinymce PHP WYSIWYG editor control
// ================================================
// Image library dialog
// ================================================
// Developed: j-cons.com, mail@j-cons.com
// Copyright: j-cons (c)2004 All rights reserved.
// ------------------------------------------------
//                                   www.j-cons.com
// ================================================
// $Revision: 1.10,               $Date: 2004/10/04
// ================================================

// unset $tinyMCE_imglib_include
unset($tinyMCE_imglib_include);
$_root=(empty($_root))?'':$_root;
// include image library config settings
include 'config.php';

$request_uri = urldecode(empty($_POST['request_uri'])?(empty($_GET['request_uri'])?'':$_GET['request_uri']):$_POST['request_uri']);

// if set include file specified in $tinyMCE_imglib_include
if (!empty($tinyMCE_imglib_include)) {
  include $tinyMCE_imglib_include;
}
$tinyMCE_base_url=$CONFIG['site']['url'].$CONFIG['site']['user_uploads'];
$curpath = isset($_POST['lib_path'])?$_POST['lib_path']:'';
$imglib = isset($_POST['lib'])?$_POST['lib']:'';
if (empty($imglib) && isset($_GET['lib'])) $imglib = $_GET['lib'];
$value_found = false;
// callback function for preventing listing of non-library directory
function is_array_value($value, $key, $_imglib) {
global $value_found;
	if (is_array($value)) array_walk($value, 'is_array_value',$_imglib);
	if ($value['value'].'|'.$value['site_root'].'|'.$value['library'].'|'.$value['url'] == $_imglib){
	$value_found=true;
	}
}
array_walk($tinyMCE_imglibs, 'is_array_value',$imglib);

if (!$value_found || empty($imglib)) {
$imgroot = $tinyMCE_imglibs[0]['site_root'];
$imglib = $tinyMCE_imglibs[0]['value'];
$imgdir = $tinyMCE_imglibs[0]['library'];
$imgurl = $tinyMCE_imglibs[0]['url'].'/'.$tinyMCE_imglibs[0]['library'];
} else {
	list($imglib,$imgurl) = explode('|',$imglib);
}

$lib_options = liboptions($tinyMCE_imglibs,'',$imglib.'|'.$imgurl);
if (@trim($curpath[0]) == '/') $curpath = substr($curpath,1);
if(@trim($curpath)!="") { $curpath='/'.$curpath; }
$workingpath = $_root.$imglib.$curpath.'/';

$img = isset($_POST['imglist'])?$_POST['imglist']:'';
$createDirName = isset($_POST['createDirName'])?$_POST['createDirName']:'';


$preview = '';

$errors = array();
if (isset($HTTP_POST_FILES['img_file']['size']) && $HTTP_POST_FILES['img_file']['size']>0)
{
  if ($img = uploadImg('img_file'))
  {
    $preview = $tinyMCE_base_url.$imglib.$img;
  }
}

// delete image
if ($allowDelete && isset($_POST['lib_action'])
	&& ($_POST['lib_action']=='delete') && !empty($img)) {
  deleteImg();
}
if ($allowCreateDir && isset($_POST['lib_action'])
	&& ($_POST['lib_action']=='createDir') && !empty($createDirName)) {
  createDir();
}
?>
<html>
<head>
<title>{$lang_ibrowser_title}</title>
<script language="javascript" type="text/JavaScript" src="../../tiny_mce_popup.js"></script>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script language="JavaScript" type="text/JavaScript">
	// click ok - select picture or save changes
	function selectClick() {
		if (document.libbrowser.imglist.selectedIndex >=0 ) {
			var obj = document.libbrowser.imglist;
			if (obj.options[obj.selectedIndex].label == 'dir') {
				document.libbrowser.lib_path.value = obj.options[obj.selectedIndex].value;
				document.libbrowser.submit();
				return true;
			}
			else {
				//alert('hey');
				insertAction();
				return true;
			}
		}
		if (validateParams()) {
			if (document.forms[0].src.value !='') {
				var src = document.forms[0].src.value;
				var alt = document.forms[0].alt.value;
				var border = document.forms[0].border.value;
				var vspace = document.forms[0].vspace.value;
				var hspace = document.forms[0].hspace.value;
				var width = document.forms[0].width.value;
				var height = document.forms[0].height.value;
				var align = document.forms[0].align.options[document.forms[0].align.selectedIndex].value;
				// tinymce 2.0.5.1 * fix *
	                        window.opener.TinyMCE_AdvancedTheme._insertImage(src, alt, border, hspace, vspace, width, height, align, '');
				//window.opener.tinyMCE.insertImage(src, alt, border, hspace, vspace, width, height, align);
				window.close();
			} else {
			alert(tinyMCE.getLang('lang_ibrowser_error')+ ' : '+ tinyMCE.getLang('lang_ibrowser_errornoimg'));}
    	}
	}

	// validate input values
	function validateParams() {
    	// check numeric values for attributes
    	if (isNaN(parseInt(libbrowser.width.value)) && libbrowser.width.value != '') {
 				alert(tinyMCE.getLang('lang_ibrowser_error')+ ' : '+ tinyMCE.getLang('lang_ibrowser_error_width_nan'));
 				libbrowser.width.focus();
      		return false;}

    	if (isNaN(parseInt(libbrowser.height.value)) && libbrowser.height.value != '') {
 				alert(tinyMCE.getLang('lang_ibrowser_error')+ ' : '+ tinyMCE.getLang('lang_ibrowser_error_height_nan'));
      		libbrowser.height.focus();
     		return false;}

    	if (isNaN(parseInt(libbrowser.border.value)) && libbrowser.border.value != '') {
			alert(tinyMCE.getLang('lang_ibrowser_error')+ ' : '+ tinyMCE.getLang('lang_ibrowser_error_border_nan'));
      		libbrowser.border.focus();
      		return false;}

    	if (isNaN(parseInt(libbrowser.hspace.value)) && libbrowser.hspace.value != '') {
			alert(tinyMCE.getLang('lang_ibrowser_error')+ ' : '+ tinyMCE.getLang('lang_ibrowser_error_hspace_nan'));
			libbrowser.hspace.focus();
      		return false;}

		if (isNaN(parseInt(libbrowser.vspace.value)) && libbrowser.vspace.value != '') {
			alert(tinyMCE.getLang('lang_ibrowser_error')+ ' : '+ tinyMCE.getLang('lang_ibrowser_error_vspace_nan'));
      		libbrowser.vspace.focus();
      		return false;}

	return true;

	}

	// delete image
	function deleteClick()
	{
		if (document.libbrowser.imglist.selectedIndex>=0)
	  	{
			if (confirm(tinyMCE.getLang('lang_ibrowser_confirmdelete')))
			{
				document.libbrowser.lib_action.value = 'delete';
				document.libbrowser.submit();
			}
	  	}
	}
	function createDirClick()
	{
		document.libbrowser.lib_action.value = 'createDir';
		document.libbrowser.submit();
	}

// set picture attributes on change
	function selectChange(obj)
	{
		if (obj.selectedIndex >=0 ) {
			if (obj.options[obj.selectedIndex].label == 'dir') {
				return true;
			} else {
				imgpreview.location.href = '<?php echo $tinyMCE_base_url.'/'.$imgdir;?>'+libbrowser.lib_path.value+ '/' + obj.options[obj.selectedIndex].value;
				
			}
		}
		var formObj = document.forms[0];
		formObj.src.value = libbrowser.lib_url.value + libbrowser.lib_path.value + '/' + obj.options[obj.selectedIndex].value;
		formObj.width.value = obj.options[obj.selectedIndex].lang;
		formObj.height.value = obj.options[obj.selectedIndex].id;
		formObj.size.value = obj.options[obj.selectedIndex].label;
		formObj.alt.value = obj.options[obj.selectedIndex].value;
		owidth = eval(formObj.width.value);
		oheight = eval(formObj.height.value);
		updateStyle();
		return true;
	}

	// init functions
	function init()
	{
		// if existing image (image properties)
		if (tinyMCE.getWindowArg('src') != '') {
			var formObj = document.forms[0];
			var inst = tinyMCE.getInstanceById(tinyMCE.getWindowArg('editor_id'));
			var elm = inst.getFocusElement();
			for (var i=0; i<document.forms[0].align.options.length; i++) {
				if (document.forms[0].align.options[i].value == tinyMCE.getWindowArg('align'))
				document.forms[0].align.options.selectedIndex = i;
			}

			formObj.src.value = tinyMCE.getWindowArg('src');
			formObj.alt.value = tinyMCE.getWindowArg('alt');
			formObj.border.value = tinyMCE.getWindowArg('border');
			formObj.vspace.value = tinyMCE.getWindowArg('vspace');
			formObj.hspace.value = tinyMCE.getWindowArg('hspace');
			formObj.width.value = tinyMCE.getWindowArg('width');
			formObj.height.value = tinyMCE.getWindowArg('height');
			formObj.classlist.value = tinyMCE.getAttrib(elm, 'class');
			formObj.size.value = 'n/a';
			owidth = eval(formObj.width.value);
			oheight = eval(formObj.height.value);
			GetClasses(tinyMCE.getAttrib(elm, 'class'));

			frameID = "imgpreview";
			document.all(frameID).src = tinyMCE.getWindowArg('src');
			updateStyle();
		}
		else {
		GetClasses('');
		//addClassesToList('classlist', 'theme_advanced_styles');
		}
		
		//alert(styles);
		window.focus();
	}
	
	function GetClasses(selectedItem) {
		var styleSelectElm = document.getElementById('classlist');
		var styles = tinyMCE.getParam('theme_advanced_styles', false);
		if (styles) {
			var stylesAr = styles.split(';');
			for (var i=0; i<stylesAr.length; i++) {
				if (stylesAr != "") {
				var key, value;
				key = stylesAr[i].split('=')[0];
				value = stylesAr[i].split('=')[1];
				styleSelectElm.options[styleSelectElm.length] = new Option(key, value);
					if(selectedItem!="") {
						if(selectedItem==value) {
						styleSelectElm.options.selectedIndex=i+1;
						}
					}
				}
			}
		}
	}

	// updates style settings
	function updateStyle() {
		//if (validateParams()) {
			document.getElementById('wrap').align = document.libbrowser.align.value;
			document.getElementById('wrap').vspace = document.libbrowser.vspace.value;
			document.getElementById('wrap').hspace = document.libbrowser.hspace.value;
			document.getElementById('wrap').border = document.libbrowser.border.value;
			document.getElementById('wrap').alt = document.libbrowser.alt.value;
			//}
	}

	// change picture dimensions
	var oheight; // original width
	var owidth;  // original height

	function changeDim(sel) {
		var formObj = document.forms[0];
		if (formObj.src.value!=''){
			f=oheight/owidth;
			if (sel==0){
				formObj.width.value = Math.round(formObj.height.value/f);
			} else {
				formObj.height.value= Math.round(formObj.width.value*f);
				}
		}
	}

	function resetDim() {
 		var formObj = document.forms[0];
		formObj.width.value = owidth;
		formObj.height.value = oheight;
	}
	
function insertAction() {
	var inst = tinyMCE.getInstanceById(tinyMCE.getWindowArg('editor_id'));
	var elm = inst.getFocusElement();
	var formObj = document.forms[0];
	var src = formObj.src.value;
	
	
	if (elm != null && elm.nodeName == "IMG") {
		setAttrib(elm, 'src', convertURL(src, tinyMCE.imgElement));
		setAttrib(elm, 'mce_src', src);
		setAttrib(elm, 'alt');
		setAttrib(elm, 'title');
		setAttrib(elm, 'border');
		setAttrib(elm, 'vspace');
		setAttrib(elm, 'hspace');
		setAttrib(elm, 'width');
		setAttrib(elm, 'height');
		setAttrib(elm, 'id');
		setAttrib(elm, 'dir');
		setAttrib(elm, 'lang');
		setAttrib(elm, 'longdesc');
		setAttrib(elm, 'usemap');
		setAttrib(elm, 'style');
		setAttrib(elm, 'class', getSelectValue(formObj, 'classlist'));
		setAttrib(elm, 'align', getSelectValue(formObj, 'align'));

		//tinyMCEPopup.execCommand("mceRepaint");

		// Repaint if dimensions changed
		if (formObj.width.value != orgImageWidth || formObj.height.value != orgImageHeight)
			inst.repaint();

		// Refresh in old MSIE
		if (tinyMCE.isMSIE5)
			elm.outerHTML = elm.outerHTML;
	} else {
		var html = "<img";

		html += makeAttrib('src', convertURL(src, tinyMCE.imgElement));
		html += makeAttrib('mce_src', src);
		html += makeAttrib('alt');
		html += makeAttrib('title');
		html += makeAttrib('border');
		html += makeAttrib('vspace');
		html += makeAttrib('hspace');
		html += makeAttrib('width');
		html += makeAttrib('height');
		html += makeAttrib('class', getSelectValue(formObj, 'classlist'));
		html += makeAttrib('align', getSelectValue(formObj, 'align'));
		html += " />";
		
		tinyMCEPopup.execCommand("mceInsertContent", false, html);
	}

	tinyMCE._setEventsEnabled(inst.getBody(), false);
	tinyMCEPopup.close();
}
	
function convertURL(url, node, on_save) {
return eval("tinyMCEPopup.windowOpener." + tinyMCE.settings['urlconverter_callback'] + "(url, node, on_save);");
}

function getImageSrc(str) {
	var pos = -1;

	if (!str)
		return "";

	if ((pos = str.indexOf('this.src=')) != -1) {
		var src = str.substring(pos + 10);

		src = src.substring(0, src.indexOf('\''));

		if (tinyMCE.getParam('convert_urls'))
			src = convertURL(src, null, true);

		return src;
	}

	return "";
}
	
	function getSelectValue(form_obj, field_name) {
	var elm = form_obj.elements[field_name];
		if (elm == null || elm.options == null)
		return "";
	return elm.options[elm.selectedIndex].value;
	}
	
	function setAttrib(elm, attrib, value) {
	var formObj = document.forms[0];
	var valueElm = formObj.elements[attrib];

	if (typeof(value) == "undefined" || value == null) {
		value = "";

		if (valueElm)
			value = valueElm.value;
	}

	if (value != "") {
		elm.setAttribute(attrib, value);

		if (attrib == "style")
			attrib = "style.cssText";

		if (attrib == "longdesc")
			attrib = "longDesc";

		if (attrib == "width") {
			attrib = "style.width";
			value = value + "px";
			value = value.replace(/%px/g, 'px');
		}

		if (attrib == "height") {
			attrib = "style.height";
			value = value + "px";
			value = value.replace(/%px/g, 'px');
		}

		if (attrib == "class")
			attrib = "className";

		eval('elm.' + attrib + "=value;");
	} else
		elm.removeAttribute(attrib);
}

function makeAttrib(attrib, value) {
	var formObj = document.forms[0];
	var valueElm = formObj.elements[attrib];

	if (valueElm) {
	value = valueElm.value;
	}

	if (value == "") {
		return "";
	}

	// XML encode it
	

	return ' ' + attrib + '="' + value + '"';
}
</script>
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
<form name="libbrowser" id="libbrowser" method="post" action="ibrowser.php?request_uri=<?php echo @trim($_GET['request_uri'])?>" enctype="multipart/form-data" target="imglibrary">
  <input type="hidden" name="request_uri" value="<?php echo @urlencode($request_uri)?>">
  <input type="hidden" name="lib_action" value="">
  <input type="hidden" name="lib_path" value="<?php echo $curpath; ?>">
  <input type="hidden" name="lib_url" value="<?php echo $imgurl; ?>">
  <fieldset style= "padding: 5 5 5 5; margin-top: -5px;">
  <legend>{$lang_ibrowser_img_sel}</legend>
  <table width="440" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td><table width="100%"  border="0" cellpadding="2" cellspacing="0">
          <tr>
            <td width="210"><strong>{$lang_ibrowser_images}:</strong><?php echo $curpath?></td>
            <td width="5">&nbsp;</td>
            <td width="210"><strong>{$lang_ibrowser_preview}:</strong></td>
          </tr>
          
          
          <tr>
            <td><?php
    if (!ereg('/$', $_SERVER['DOCUMENT_ROOT']))
      $_root = $_SERVER['DOCUMENT_ROOT'].'/';
    else
      $_root = $_SERVER['DOCUMENT_ROOT'];

	$_root = getcwd().'/';

    $d = opendir($workingpath);
  ?>
          <select name="imglist" size="15" style="width: 100%;"
    onChange="selectChange(this);" ondblclick="selectClick();">
            <?php
    	if ($d)
    {
	  $i = 0;$j=0;
       while (false !== ($entry = readdir($d))) {
        $ext = strtolower(substr(strrchr($entry,'.'), 1));
        if (is_file($workingpath.$entry) && in_array($ext,$tinyMCE_valid_imgs))
        {
			$arr_tinyMCE_image_files[$i]['file_name'] = str_replace($imgroot,"",$entry);
			$i++;
        } elseif (is_dir($workingpath.$entry)) {
        	$arr_tinyMCE_dirs[$j]['dir_name'] = $entry;
        	$j++;
        }

      }
      closedir($d);
	  // sort the list of image filenames alphabetically.
	  sort($arr_tinyMCE_image_files);
	  sort($arr_tinyMCE_dirs);
	  for($k=0; $k<count($arr_tinyMCE_dirs); $k++){
      $entry = $arr_tinyMCE_dirs[$k]['dir_name'];
	  if ($entry == '.') continue;
	  if ($entry == '..') {
	  	if (empty($curpath)) continue;
	  	$pathx = explode('/',$curpath);
	  	unset($pathx[count($pathx) - 1]);
	  	$nextpath = implode('/', $pathx);
	  }
	  else $nextpath = $curpath.'/'.$entry;
	  $entry = '['.$entry.']';
   ?>
            <option label="dir" value="<?php echo $nextpath?>"><?php echo $entry?></option>
            <?php
	  }
	  for($k=0; $k<count($arr_tinyMCE_image_files); $k++){
      $entry = $arr_tinyMCE_image_files[$k]['file_name'];
	  $size = getimagesize($workingpath.$entry);
	  $fsize = filesize($workingpath.$entry);

   ?>
            <option lang="<?php echo $size[0]; ?>" id="<?php echo $size[1]; ?>"  label="<?php echo filesize_h($fsize,2); ?>" value="<?php echo $entry?>" <?php echo ($entry == $img)?'selected':''?>><?php echo $entry?></option>
            <?php
	  }
    }
    else
    {
      $errors[] = '{$lang_ibrowser_errornodir}';
    }
  ?>
          </select></td>
            <td>&nbsp;</td>
            <td width="210" align="left" valign="top"><iframe name="imgpreview"  class="previewWindow" src="<?php echo $preview?>" style="width: 100%; height: 100%;" scrolling="Auto" marginheight="0" marginwidth="0" frameborder="0"></iframe>
            </td>
          </tr>
          <tr>
            <td colspan="3"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="40%"><nobr><?php if ($allowDelete) { ?>
                    <input type="button" value="{$lang_ibrowser_delete}" class="bt" onClick="deleteClick();">&nbsp;&nbsp;
                    <?php } ?>
                    <?php if ($allowCreateDir) { ?>
                    <input type="button" value="{$lang_ibrowser_create_dir}" class="bt" onClick="createDirClick();">
	                <input type="text" size="15" name="createDirName">
                    <?php } ?>
					</nobr>
                    </td><td align="right"><input type="button" name="selectbt" value="{$lang_ibrowser_select}" class="bt" onClick="selectClick();">
                    <input type="button" value="{$lang_ibrowser_cancel}" class="bt" onClick="window.close();"></td>
                </tr>
              </table></td>
          </tr>
        </table></td>
    </tr>
  </table>
  </fieldset>
  <fieldset style= "padding: 5 5 5 5; margin-top: 10px;">
  <legend>{$lang_ibrowser_img_info}</legend>
  <table width="440" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td><table width="440" border="0" cellpadding="2" cellspacing="0">
          <tr>
            <td width="80">{$lang_ibrowser_src}:</td>
            <td colspan="5"><input name="src" type="text" id="src" value="" style="width: 100%;" readonly="true"></td>
          </tr>
          <tr>
            <td>{$lang_ibrowser_alt}:</td>
            <td colspan="5"><input name="alt" type="text" id="alt" value="" style="width: 100%;" onChange="updateStyle()"></td>
          </tr>
          <tr>
            <td>{$lang_ibrowser_align}:</td>
            <td colspan="3"><select name="align" style="width: 100%;" onChange="updateStyle()">
                <option value="">{$lang_insert_image_align_default}</option>
                <option value="baseline">{$lang_insert_image_align_baseline}</option>
                <option value="top">{$lang_insert_image_align_top}</option>
                <option value="middle">{$lang_insert_image_align_middle}</option>
                <option value="bottom">{$lang_insert_image_align_bottom}</option>
                <option value="texttop">{$lang_insert_image_align_texttop}</option>
                <option value="absmiddle">{$lang_insert_image_align_absmiddle}</option>
                <option value="absbottom">{$lang_insert_image_align_absbottom}</option>
                <option value="left">{$lang_insert_image_align_left}</option>
                <option value="right">{$lang_insert_image_align_right}</option>
              </select></td>
            <td width="5">&nbsp;</td>
            <td width="210" rowspan="7" align="left" valign="top"><div id="stylepreview" style="padding:10px; width: 200; height:180;  overflow:hidden; background-color:#ffffff; font-size:8px" class="previewWindow">
                <p><img id="wrap" src="images/textflow.gif" width="45" height="45" align="" alt="" hspace="" vspace="" border="" />Lorem
                  ipsum, Dolor sit amet, consectetuer adipiscing loreum ipsum
                  edipiscing elit, sed diam nonummy nibh euismod tincidunt ut
                  laoreet dolore magna aliquam erat volutpat.Loreum ipsum edipiscing
                  elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore
                  magna aliquam erat volutpat. Ut wisi enim ad minim veniam,
                  quis nostrud exercitation ullamcorper suscipit. Lorem ipsum,
                  Dolor sit amet, consectetuer adipiscing loreum ipsum edipiscing
                  elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore
                  magna aliquam erat volutpat.</p>
              </div></td>
          </tr>
          <tr>
            <td>{$lang_ibrowser_size}:</td>
            <td colspan="3"><input name="size" type="text" id="size" value="" readonly="true" style="width: 100%;"></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>{$lang_ibrowser_height}:</td>
            <td width="40"><input name="height" type="text" id="height" value="" size="5" maxlength="4" style="text-align: right;" onChange="changeDim(0)"></td>
            <td width="25" rowspan="2" align="left" valign="middle"><a href="#" onClick="resetDim();" ><img src="images/constrain.gif" alt="{$lang_ibrowser_reset}" width="22" height="29" border="0"></a></td>
            <td rowspan="2">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>{$lang_ibrowser_width}:</td>
            <td><input name="width" type="text" id="width" value="" size="5" maxlength="4" style="text-align: right;" onChange="changeDim(1)"></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>{$lang_ibrowser_border}:</td>
            <td colspan="3"><input name="border" type="text" id="border" value="" size="5" maxlength="4" style="text-align: right;" onChange="updateStyle()"></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>{$lang_ibrowser_vspace}:</td>
            <td colspan="3"><input name="vspace" type="text" id="vspace" value="" size="5" maxlength="4" style="text-align: right;" onChange="updateStyle()"></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>{$lang_ibrowser_hspace}:</td>
            <td colspan="3"><input name="hspace" type="text" id="hspace" value="" size="5" maxlength="4" style="text-align: right;" onChange="updateStyle()"></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
		  <td>{$lang_class_name}</td>
		  <td colspan="3">
		  <select id="classlist" name="classlist">
		  <option value="" selected>{$lang_not_set}</option>
		  </select>
		  </td>
		  </tr>
        </table></td>
    </tr>
  </table>
  </fieldset>
  <?php  if ($allowUpload) { ?>
  <fieldset style= "padding: 5 5 5 5; margin-top: 10px;">
  <legend>{$lang_ibrowser_img_upload}</legend>
  <table width="440" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td><?php
    if (!empty($errors))
    {
      echo '<span class="error">';
      foreach ($errors as $err)
      {
        echo $err.'<br />';
      }
      echo '</span>';
    }
    ?>
        <?php
  if ($d) {
  ?>
        <table width="440" border="0" cellpadding="2" cellspacing="0">
          <tr>
            <td width="80">{$lang_ibrowser_uploadtxt}:</td>
            <td colspan="2"><input name="img_file" type="file" style="width: 100%;" size="50"></td>
          </tr>
          <tr>
            <td colspan="3"><input type="submit" name="btnupload" class="bt" value="{$lang_ibrowser_uploadbt}"></td>
          </tr>
        </table>
        <?php
  }
  ?>
      </td>
    </tr>
  </table>
  </fieldset>
  <?php  } ?>
</form>
</body>
</html>
<?php
function liboptions($arr, $prefix = '', $sel = '')
{
  global $allowUpload, $allowCreateDir, $allowDelete;
  $buf = '';
  foreach($arr as $lib) {
  	$selected = '';
  	//if ($lib['site_root'].'|'.$lib['library'].'|'.$lib['value'].'|'.$lib['url'] == $sel) {
  		$selected = ' selected';
  		$allowUpload = $lib['upload'];
  		$allowCreateDir = $lib['create_dir'];
  		$allowDelete = $lib['delete'];
  	//}
    //$buf .= '<option value="'.$lib['value'].'|'.$lib['url'].'"'.$selected.'>'.$prefix.$lib['text'].'</option>'."\n";
  }
  return $buf;
}
// upload image
function uploadImg($img) {

  global $HTTP_POST_FILES;
  global $HTTP_SERVER_VARS;
  global $tinyMCE_valid_imgs;
  global $imglib,$workingpath;
  global $errors;
  global $l;
  global $allowUpload;

  if (!$allowUpload) return false;

  if (!ereg('/$', $HTTP_SERVER_VARS['DOCUMENT_ROOT']))
    $_root = $HTTP_SERVER_VARS['DOCUMENT_ROOT'].'/';
  else
    $_root = $HTTP_SERVER_VARS['DOCUMENT_ROOT'];

  if ($HTTP_POST_FILES[$img]['size']>0) {
    $data['type'] = $HTTP_POST_FILES[$img]['type'];
    $data['name'] = $HTTP_POST_FILES[$img]['name'];
    $data['size'] = $HTTP_POST_FILES[$img]['size'];
    $data['tmp_name'] = $HTTP_POST_FILES[$img]['tmp_name'];

    // get file extension
    $ext = strtolower(substr(strrchr($data['name'],'.'), 1));
    if (in_array($ext,$tinyMCE_valid_imgs)) {
      $dir_name = $workingpath;

      $img_name = $data['name'];
      $i = 1;
      while (file_exists($dir_name.$img_name)) {
        $img_name = ereg_replace('(.*)(\.[a-zA-Z]+)$', '\1_'.$i.'\2', $data['name']);
        $i++;
      }
      if (!move_uploaded_file($data['tmp_name'], $dir_name.$img_name)) {
        $errors[] = '{lang_ibrowser_errorupload}';
        return false;
      }

      return $img_name;
    }
    else
    {
      $errors[] = '{$lang_ibrowser_errortype}';
    }
  }
  return false;
}

function deleteImg()
{
  global $HTTP_SERVER_VARS;
  global $imglib, $workingpath;
  global $img;
  global $allowDelete;
  global $errors;
  global $l;

  if (!$allowDelete) return false;

  if (!ereg('/$', $HTTP_SERVER_VARS['DOCUMENT_ROOT']))
    $_root = $HTTP_SERVER_VARS['DOCUMENT_ROOT'].'/';
  else
    $_root = $HTTP_SERVER_VARS['DOCUMENT_ROOT'];

  $full_img_name = $workingpath.$img;

  if (is_dir($full_img_name) && @rmdir($full_img_name)) {
  	return true;
  }
  elseif (@unlink($full_img_name)) {
  	return true;
  }
  else
  {
  	$errors[] = '{$lang_ibrowser_errordelete}';
	return false;
  }
}

function createDir() {
global $HTTP_SERVER_VARS;
global $imglib,$workingpath;
global $createDirName;
global $allowCreateDir;
global $errors;
global $l;

	if (!$allowCreateDir) return false;
	@umask(0000);
	if (!ereg('/$', $HTTP_SERVER_VARS['DOCUMENT_ROOT']))
	$_root = $HTTP_SERVER_VARS['DOCUMENT_ROOT'].'/';
	else
	$_root = $HTTP_SERVER_VARS['DOCUMENT_ROOT'];

	$full_path = $workingpath.$createDirName;

	if (@mkdir($full_path)) {
	return true;
	}
	else
	{
	$errors[] = '{$lang_ibrowser_errorcreatedir}';
	return false;
	}
}

// Return the human readable size of a file
// @param int $size a file size
// @param int $dec a number of decimal places

function filesize_h($size, $dec = 1)
{
	$sizes = array('byte(s)', 'kb', 'mb', 'gb');
	$count = count($sizes);
	$i = 0;

	while ($size >= 1024 && ($i < $count - 1)) {
		$size /= 1024;
		$i++;
	}

	return round($size, $dec) . ' ' . $sizes[$i];
}

?>
