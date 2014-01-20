<?php
$LoadCMS=false;
include_once("../../../index.php");
include_once(FCPATH."core/config.inc.php");
include_once(FCPATH."core/autoload.inc.php");
include_once(FCPATH."core/functions.inc.php");
include_once(FCPATH."core/database.inc.php");
include_once(FCPATH."modules/content/content.config.php");

if(isset($_REQUEST["newimage"])) {
	$img=str_replace("/images/uploads/","",$_REQUEST["newimage"]);
	$SortOrder=MySQLResult("SELECT Max(sort_order) FROM pages_lumps WHERE page_id='".$_REQUEST["id"]."'") + 1;
					
	$FD3=Array(
	"page_id{insert}" => $_REQUEST["id"],
	"lump_image" => $img,
	"sort_order{insert}" => $SortOrder
	);
	
	execute_query(MySQLInsert($FD3,"pages_lumps"));
}

if(isset($_GET["img"])) {
execute_query("DELETE FROM pages_lumps WHERE lump_id='".$_GET["img"]."'");
}

$x=1;
$sql=execute_query("SELECT * FROM pages_lumps WHERE page_id='".$_REQUEST["id"]."' ORDER BY sort_order ASC");
foreach($sql as $row) {
?>
	<?php if(is_file($Config["content"]["images"].$row["lump_image"])) { ?>
	<div>
		<label>Image <?=$x;?></label>
		<a href="javascript:;" class="DeleteImage" rel="<?=$row["lump_id"];?>"><img src="<?=$Config["content"]["images_nice"].$row["lump_image"];?>" border="0" alt="" width="300" /></a>
	</div>
	<?php } ?>
<?php
$x++;
}
?>
<div style="clear:both;"></div>

<script>
$('.DeleteImage').click(function(){
	if(confirm('Clicking OK will remove this image from your home page.')) {
	$('#HomePhotos').load('modules/content/ajax/_ajax.home.inc.php?id=<?=$_REQUEST["id"];?>&img='+this.rel);
	}
return false;
});
</script>