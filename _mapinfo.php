<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/global/site.inc.php");

if(isset($_GET["store_id"])) {
	$Details=MySQLArray("SELECT * FROM stores WHERE store_id='".addslashes(trim($_GET["store_id"]))."'");
	?>
	<div class="map-info">
		<h2><?=stripslashes($Details["store_name"]);?></h2>
		<p>
		<?=stripslashes($Details["store_street"].'<br />'.$Details["store_suburb"].'<br />'.$Details["store_state"]);?>
		</p>
		<p>
			<b>Phone:</b> <?=stripslashes($Details["store_phone"]);?>
		</p>
	</div>
	<?php
}

?>