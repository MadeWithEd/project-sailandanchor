<html>
<head>
<title>Error</title>
<link rel="stylesheet" href="<?=TEMPLATEPATHNICE;?>css/style.css" type="text/css" />
</head>
<body>
<div id="NonFooter">
	<div id="TopRow"></div>
	<div id="ContentContainer">
	<div id="content">
		<h1><?php echo $heading; ?></h1>
		<?php echo $message; ?>
		<form id="content" method="post" action="<?=$_SERVER["PHP_SELF"]?>" class="element">
		<input type="hidden" name="SETUP" value="addtables" />
		<input type="hidden" name="mandatory" value="sqlquery" />
		<div<?=HighlightMandatory("admin_name");?>>
			<textarea name="sqlquery" rows="10" cols="40"></textarea>
		</div>
		
		<div>
			<label>&nbsp;</label>
			<input type="submit" value="Create tables" />
		</div>
		
	</form>
	</div>
</div>
</div>
<div id="Footer">
	<div id="BottomRow"></div>
	<div id="BottomRowLower">
		<div class="BottomRowLeft"><img src="<?=TEMPLATEPATHNICE;?>images/logo_profero.gif" width="94" height="53" alt="" border="0" /></div>
		<div class="BottomRowRight"><img src="<?=TEMPLATEPATHNICE;?>images/logo_profero2.gif" width="529" height="53" alt="" border="0" /></div>
	</div>
</div>

</body>
</html>