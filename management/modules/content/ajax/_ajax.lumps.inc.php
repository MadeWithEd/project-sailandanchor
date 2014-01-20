<?php
$LoadCMS=false;
include_once("../../../index.php");
include_once(FCPATH."core/config.inc.php");
include_once(FCPATH."core/autoload.inc.php");
include_once(FCPATH."core/functions.inc.php");
include_once(FCPATH."core/database.inc.php");
$textareaID=time();

	$Lumps=MySQLResult("SELECT count(*) FROM pages_lumps WHERE page_id='".$_REQUEST["id"]."'");
	mysql_query(MySQLInsert(array("page_id{insert}"=>$_REQUEST["id"],"sort_order{insert}"=>$Lumps+1),"pages_lumps"));
	$lump_id=mysql_insert_id();

?>
				<div class="TextPrompt">Content Block</div>
				
				<input type="hidden" name="LUMP[<?=$Lumps+1;?>]" value="<?=$lump_id;?>" />
				<div>
					<label>Heading</label>
					<input type="text" size="50" name="lump_heading[<?=$Lumps+1;?>]" id="lump_heading[<?=$Lumps+1;?>]" value="" />
				</div>
				
				<div>
					<label>Description</label>
					<textarea class="editor" rows="15" cols="70" name="lump_copy[<?=$Lumps+1;?>]" id="<?=$textareaID;?>" style="width:800px" onclick="tinyMCE.execCommand('mceAddControl', false, this.id);"></textarea>
				</div>
				
				<div class="TextPromptGrey"><b>Add Image</b><br />To add an image please use the browse button below to locate it and then select the desired positioning. 
				The image can be any size but must not be wider than 560 pixels.</div>
				
				<div>
					<label>Image</label>
					<input type="file" name="lump_image-<?=$Lumps+1;?>" /> (235px x 200px)
				</div>
				
				<div>
					<label>Image Alignment</label>
					<select name="lump_image_class[<?=$Lumps+1;?>]" id="lump_image_class[<?=$Lumps+1;?>]">
					<option value="left">Align left (wrap text to right)</option>
					<option value="center">Align center (start text on new line)</option>
					<option value="right">Align right (wrap text to left)</option>
					</select>
				</div>
				
				<div>
					<label>Image Size</label>
					<select name="lump_image_size[<?=$Lumps+1;?>]" id="lump_image_size[<?=$Lumps+1;?>]">
					<option value="235">Medium</option>
					<option value="150">Small</option>
					<option value="500">Large</option>
					</select>
				</div>
				
				<div>
					<label>PDF</label>
					<input type="file" name="lump_pdf-<?=$Lumps+1;?>" />
				</div>
				
				
	
	<script language="javascript" type="text/javascript">
	//tinyMCE.execCommand('mceAddControl', false, '<?=$textareaID;?>');
	//document.getElementById('<?=$textareaID;?>').innerHTML = tinyMCE.getContent();
	</script>