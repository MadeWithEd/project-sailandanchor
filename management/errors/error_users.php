<html>
<head>
<title>Error</title>
<link rel="stylesheet" href="<?=TEMPLATEPATHNICE;?>css/style.css" type="text/css" />
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
</head>
<body>
<div id="NonFooter">
	<div id="TopRow"></div>
	<div id="ContentContainer">
	<form id="content" method="post" action="<?=$_SERVER["PHP_SELF"]?>" class="element">
	<input type="hidden" name="SETUP" value="adduser" />
	<input type="hidden" name="mandatory" value="admin_name,admin_email,admin_password,admin_password2" />
		<h1>No Admin User</h1>
		<p>
		Oops! We were unable to find any account for an administrator in the database. To create one now fill in the fields below.
		</p>
		
		<div<?=HighlightMandatory("admin_name");?>>
			<label for="admin_name">Full name</label>
			<input type="text" size="25" name="admin_name" value="<?=ShowDataText("admin_name");?>" />
		</div>
		
		<div<?=HighlightMandatory("admin_email");?>>
			<label for="admin_email">Email</label>
			<input type="text" size="25" name="admin_email" value="<?=ShowDataText("admin_email");?>" />
		</div>
		
		<div<?=HighlightMandatory("admin_password");?>>
			<label for="admin_password">Password</label>
			<input type="password" size="25" name="admin_password" value="<?=ShowDataText("admin_password");?>" />
		</div>
		
		<div<?=HighlightMandatory("admin_password2");?>>
			<label for="admin_password2">Confirm Password</label>
			<input type="password" size="25" name="admin_password2" value="<?=ShowDataText("admin_password2");?>" />
		</div>
		
		<div>
			<label>&nbsp;</label>
			<input type="submit" value="Save" />
		</div>
		
	</form>
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