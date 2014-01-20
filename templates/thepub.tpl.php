<?php if(!isset($_GET["ajax"])) { ?>
	<?php include_once("metadata.inc.php");?>
		<body id="ContentBody" class="<?=$PageDetails["section_id"];?>">
			<div id="Wrapper">
				<div class="contentwidth">
					<?php include_once("header.inc.php");?>
					<div id="Content">
<?php } ?>
					<?= EvaluateContent(stripslashes($PageDetails["page_html"]));?>
					<div class="clear"></div>
<?php if(!isset($_GET["ajax"])) { ?>
				</div>
			</div>
		</div>		
	</body>
</html>
<?php } ?>