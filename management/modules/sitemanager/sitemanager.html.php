<?php
function DisplayTemplates() {
GLOBAL $search,$Config,$mediadir;
?>
<script type="text/javascript" src="js/mootools.js"></script>
<script>
Window.addEvent('domready', function() {
	var accordion = new Accordion('h3.atStart', 'div.atStart', {
		opacity: false,
		onActive: function(toggler, element){
			toggler.setStyle('color', '#ff3300');
		},

		onBackground: function(toggler, element){
			toggler.setStyle('color', '#222');
		},
		show: <?=(isset($Show))?$Show:"0";?>
	}, $('accordion'));
});
</script>
<?php
	$tinyMCE_valid_imgs=array('gif', 'jpg', 'jpeg', 'png');
	$i=0;$j=0;
	$mediadir=(isset($_REQUEST["mediadir"]))?$_REQUEST["mediadir"]:'images/uploads/';
	$rd=opendir(SITEPATH.$mediadir);
	while (false !== ($entry = readdir($rd))) {
		$ext = strtolower(substr(strrchr($entry,'.'), 1));	
		if(is_dir(SITEPATH.$mediadir.$entry)) {
			if($entry!="." && $entry!="..") {
			$arr_tinyMCE_dirs[$j]['mediadir_name'] = $entry;
				$x=0;
				$nrd=opendir(SITEPATH.$mediadir.$entry);
				while (false !== ($newentry = readdir($nrd))) {
				$newext = strtolower(substr(strrchr($newentry,'.'), 1));
					if ((is_file(SITEPATH.$mediadir.$entry.'/'.$newentry) || is_dir(SITEPATH.$mediadir.$entry.'/'.$newentry)) && !in_array(SITEPATH.$mediadir.$entry,$Config["dissallow"])) {
					$x++;
					}
				}
				closedir($nrd);
				$arr_tinyMCE_dirs[$j]['file_count'] = $x;
			$j++;
			}
		}
		if (is_file(SITEPATH.$mediadir.$entry) && !in_array($entry,$Config["dissallow"])) {
		$arr_tinyMCE_image_files[$i]['file_name']=$entry;
		$i++;
		}
	}
	closedir($rd);
	?>
	<link rel="stylesheet" href="js/hoverbox/hoverbox.css" type="text/css" media="screen" />
	
	<h1>Viewing <?=@count($arr_tinyMCE_image_files);?> files in <?=(isset($_REQUEST["mediadir"]))?'/'.$_REQUEST["mediadir"]:'';?></h1>
	
	<form name="Form" method="post" action="<?=$_SERVER["PHP_SELF"];?>" enctype="multipart/form-data">
	<input type="hidden" name="id" value="<?php if(isset($_REQUEST["id"]) && $_REQUEST["id"]!="") { echo $_REQUEST["id"]; } ?>" />
	<input type="hidden" name="mediadir" value="<?php if(isset($_REQUEST["mediadir"]) && $_REQUEST["mediadir"]!="") { echo $_REQUEST["mediadir"]; } ?>" />
	<input type="hidden" name="mod" value="<?=$_REQUEST["mod"];?>" />
	<input type="hidden" name="sub" value="<?php if(isset($_REQUEST["sub"])) { echo $_REQUEST["sub"]; } ?>" />
	<input type="hidden" name="task" value="save" />
	&nbsp;<br />
	<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
	<td valign="top">
	<?php
	if((!empty($arr_tinyMCE_dirs)>0 || !empty($arr_tinyMCE_image_files)>0) || isset($_REQUEST["mediadir"])) {
	echo '<table border="0">';
	echo '<tr>';
	}
	
	$x=0;
	if(!empty($_REQUEST["mediadir"])) {
	$prevdir=substr($_REQUEST["mediadir"],0,strrpos(substr($_REQUEST["mediadir"],0,strlen($_REQUEST["mediadir"])-1),"/"));
	$prevdir=($prevdir=="")?"":$prevdir."/";
	echo '<td align="center" width="120" valign="top">
			<table border="1" bordercolor="#000000" cellpadding="0" cellspacing="0" width="100" height="100">
			<tr>
			<td align="center"><a href="?mod='.$_REQUEST["mod"].'&mediadir='.$prevdir.'"><img src="modules/sitemanager/images/folder_back.png" border="0" /></a></td>
			</tr>
			</table>
			<table width="100" border="0">
			<tr>
			<td align="center"><b>..back</b></td>
			</tr>
			</table>
		</td>';
	$x++;
	}
		
	if(!empty($arr_tinyMCE_dirs)>0) {
	sort($arr_tinyMCE_dirs);
		for($k=0; $k<count($arr_tinyMCE_dirs); $k++){
		$entry = $arr_tinyMCE_dirs[$k]['mediadir_name'];
			if($entry!="." && $entry!="..") {
				if($x==5) {
				echo '</tr><tr>';
				$x=0;
				}
			echo '<td align="center" width="120" valign="top">
					<table border="1" bordercolor="#000000" cellpadding="0" cellspacing="0" width="100" height="100">
					<tr>
					<td align="center"><a href="?mod='.$_REQUEST["mod"].'&mediadir='.$mediadir.$entry.'/"><img src="modules/sitemanager/images/folder.png" border="0" alt="" onmouseover="this.src=\'modules/sitemanager/images/folder_f2.png\';" onmouseout="this.src=\'modules/sitemanager/images/folder.png\';" /></a></td>
					</tr>
					</table>
					<table width="100" border="0">
					<tr>
					<td align="center"><b>'.stripslashes(substr($entry,"0","35")).'</b><br />'.$arr_tinyMCE_dirs[$k]['file_count'].' files</td>
					</tr>
					</table>
				</td>';
			$x++;
			}
		}
	}
	
	if(!empty($arr_tinyMCE_image_files)>0) {
	sort($arr_tinyMCE_image_files);
		for($k=0; $k<count($arr_tinyMCE_image_files); $k++){
		$entry = $arr_tinyMCE_image_files[$k]['file_name'];
		$ext = strtolower(substr(strrchr($entry,'.'), 1));
		$fsize = filesize(SITEPATH.$mediadir.$entry);
			if(in_array($ext,$Config["images"]))  {
			$inf = getimagesize(SITEPATH.$mediadir.$entry);
				if($inf[0]>100) {
				$ThumbWidth=100;
				$ThumbHeight=100 * ($inf[1] / $inf[0]);
				}
				else {
				$ThumbWidth=$inf[0];
					if($inf[1] > 100) {
					$ThumbHeight=100 * ($inf[1] / $inf[0]);
					}
					else {
					$ThumbHeight=$inf[1];	
					}
				}
				if($ThumbHeight > 100) {
				$ThumbHeight=100;
					if($inf[0] > 100) {
					$ThumbWidth=100 * ($inf[0] / $inf[1]);
					}
					else {
					$ThumbWidth=$inf[0];	
					}
				}
				$html='<a href="'.SITEPATHNICE.$mediadir.$entry.'" target="_blank"><img class="preview" src="'.SITEPATHNICE.$mediadir.$entry.'" border="0" alt="" /><img src="'.SITEPATHNICE.$mediadir.$entry.'" border="0" alt="" width="'.round($ThumbWidth).'" height="'.round($ThumbHeight).'" /></a>';
			}
			elseif(in_array($ext,$Config["editable"])) {
				if(is_file("modules/sitemanager/images/".$ext.".png")) {
				$html='<a href="?mod='.$_REQUEST["mod"].'&mediadir='.$mediadir.'&id='.$entry.'"><img src="modules/sitemanager/images/'.$ext.'.png" border="0" alt="" /></a>';
				}
				else {
				$html='<img src="modules/sitemanager/generic" border="0" alt="" />';
				}
			}
			else {
				$html='<img src="modules/sitemanager/generic.png" border="0" alt="" />';
			}
						
			
			if($x==5) {
			echo '</tr><tr>';
			$x=0;
			}
			echo '<td align="center" width="120" valign="top">
					<table border="1" bordercolor="#000000" cellpadding="0" cellspacing="0" width="100" height="100">
					<tr>
					<td align="center" class="hoverbox"><div>'.$html.'</div></td>
					</tr>
					</table>
					<table width="100" border="0">
					<tr>
					<td align="center"><b>'.stripslashes(substr($entry,"0","35")).'</b></td>
					</tr>';
					if(in_array($ext,$Config["images"]))  {
					echo '
					<tr>
					<td align="center">'.$inf[0] .'px x '. $inf[1] .'px'.'</td>
					</tr>';
					}
					echo '<tr>
					<td align="center">'. round(($fsize / 1000),2) . "kb" .' <a href="?mod='.$_REQUEST["mod"].'&mediadir='.$mediadir.'&task=remove&id='.$entry.'" onclick="if(!confirm(\'Clicking OK will permanently remove this file\')) { return false; }"><img src="'.TEMPLATEPATHNICE.'images/ico_rubbish.gif" border="0" alt="" /></a></td>
					</tr>
					</table>
				</td>
				';
		$x++;
		}
	}
	if((!empty($arr_tinyMCE_dirs)>0 || !empty($arr_tinyMCE_image_files)>0) || isset($_REQUEST["mediadir"])) {
	echo '</tr>';
	echo '</table>';
	}
	?>
	</td>
	<td width="5">&nbsp;</td>
	<td valign="top" width="350">
		<div id="accordion">
			<h3 class="toggler atStart">Upload File</h3>
			<div class="element atStart">
				<p>Max file size: <b><?=get_cfg_var("upload_max_filesize");?></b></p>
				<div<?=HighlightMandatory("image_file");?>>
					<label>File <?= ShowMandatory("image_file"); ?></label>
					<input type="file" name="image_file" />
				</div>
				<div>
					<label>&nbsp;</label>
					<input type="submit" value="Upload File" />
				</div>
			</div>
			<?php if(!empty($_REQUEST["mediadir"])) { ?>
			<h3 class="toggler atStart">Edit Folder</h3>
			<div class="element atStart">
				<div<?=HighlightMandatory("mediadir_name");?>>
					<label>Folder name </label>
					<input type="text" size="20" name="mediadir_name_old" value="<?php $mediaarr=explode("/",$_REQUEST["mediadir"]); echo $mediaarr[count($mediaarr)-2];?>" readonly="readonly" />
				</div>
				<div<?=HighlightMandatory("mediadir_name");?>>
					<label>New Folder name <?= ShowMandatory("mediadir_name"); ?></label>
					<input type="text" size="20" name="mediadir_name" value="<?= ShowDataText("mediadir_name"); ?>" />
				</div>
				<div>
					<label>&nbsp;</label>
					<input type="submit" value="Save Changes" />
				</div>
			</div>
			<?php } ?>
			<h3 class="toggler atStart">Create New Folder</h3>
			<div class="element atStart">
				<div<?=HighlightMandatory("mediadir_name_new");?>>
					<label>Folder name <?= ShowMandatory("mediadir_name_new"); ?></label>
					<input type="text" size="20" name="mediadir_name_new" value="<?= ShowDataText("mediadir_name_new"); ?>" />
				</div>
				<div>
					<label>&nbsp;</label>
					<input type="submit" value="Create Folder" />
				</div>
			</div>
			<h3 class="toggler atStart">Create New File</h3>
			<div class="element atStart">
				<div<?=HighlightMandatory("file_name_new");?>>
					<label>File name <?= ShowMandatory("file_name_new"); ?></label>
					<input type="text" size="20" name="file_name_new" />
				</div>
				<div>
					<label>&nbsp;</label>
					<input type="submit" value="Create File" />
				</div>
			</div>
			<?php if(!empty($_REQUEST["mediadir"])) { ?>
			<h3 class="toggler atStart">Password protect this directory</h3>
			<div class="element atStart">
				<div<?=HighlightMandatory("username");?>>
					<label>Username <?= ShowMandatory("username"); ?></label>
					<input type="text" size="20" name="username" />
				</div>
				<div<?=HighlightMandatory("password");?>>
					<label>Password <?= ShowMandatory("password"); ?></label>
					<input type="text" size="20" name="password" />
				</div>
				<div>
					<label>&nbsp;</label>
					<input type="submit" value="Protect Directory" />
				</div>
			</div>
			<?php } ?>
		</div>
	</td>
	</tr>
	</table>
	</form>
	<?php
}

function DisplayEditFile() {
GLOBAL $Config,$mediadir;
	?>
	<h1><?=($_REQUEST["id"]!="")?'Edit '.stripslashes('/'.$mediadir.$_REQUEST["id"]):'Create new file';?></h1>
	<?=DisplayError();?>
	<form name="Form" method="post" action="<?=$_SERVER["PHP_SELF"];?>">
	<input type="hidden" name="id" value="<?php if(isset($_REQUEST["id"]) && $_REQUEST["id"]!="") { echo $_REQUEST["id"]; } ?>" />
	<input type="hidden" name="mod" value="<?=$_REQUEST["mod"];?>" />
	<input type="hidden" name="mediadir" value="<?=$_REQUEST["mediadir"];?>" />
	<input type="hidden" name="task" value="edit" />
	<input type="hidden" name="mandatory" value="" />
		
	<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
	<td valign="top">
		<?php if($_REQUEST["id"]!="") { $file=SITEPATH.$mediadir.$_REQUEST["id"]; } ?>
		<textarea name="file_code" id="file_code" rows="30" cols="50" style="width:770px;" class="mceEditor"><?php if(isset($file)) { echo str_replace(array("<",">"),array("&lt;","&gt;"), fread( fopen($file, 'r' ), filesize( $file ) ) ); } ?></textarea>
	</td>
	<td width="5">&nbsp;</td>
	<td valign="top" width="350">
		<?php if(is_writeable($file)) { ?>
		<div id="Controls">
			<div><input type="image" src="<?=TEMPLATEPATHNICE.'images/delete_f2.png';?>" border="0" alt="" name="remove" onclick="if(!confirm('Warning! Clicking OK will permanently remove this page')) { return false; }" /><br />delete</div>
			<div><input type="image" src="<?=TEMPLATEPATHNICE.'images/save_f2.png';?>" alt="" name="save" /><br />save</div>
		</div>
		<div style="clear:both;">&nbsp;</div>
		<?php } else { ?>
		<div class="TextPrompt">
		This file is not writeable.
		</div>
		<?php } ?>
		<br />
		<div id="accordion">
			<h3 class="toggler atStart">File Details</h3>
			<div class="element atStart">
				<div<?=HighlightMandatory("file_name_old");?>>
					<label>File name </label>
					<span><?=$_REQUEST["id"];?></span>
				</div>
				<div<?=HighlightMandatory("file_name");?>>
					<label>Rename file</label>
					<input type="text" size="20" name="file_name" />
				</div>
				<div<?=HighlightMandatory("file_name");?>>
					<label>Size</label>
					<span><?=round(filesize($file)/1000,2);?> kb</span>
				</div>
			</div>
		</div>
	</td>
	</tr>
	</table>
	</form>
	<?php
}
?>