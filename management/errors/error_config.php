<html>
<head>
<title>Setup</title>
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
		<br /><br /><br /><br />
		<form id="content" method="post" action="<?=$_SERVER["PHP_SELF"]?>">
		<input type="hidden" name="SETUP" value="config" />
		<input type="hidden" name="mandatory" value="db_hostname,db_username,db_database,admin_name,admin_email,admin_password,site_url,session_name,site_name,site_uploads" />
			<h1>System Setup</h1>
			<p>
			Welcome to the pro:cms setup wizard, please follow the steps below to setup your cms.
			</p>
			<?= DisplayError();?>
			<div id="accordion">
			<h3 class="toggler atStart">CMS Configuration</h3>
				<div class="element atStart">
					<p>
					Please use the fields below to configure your cms.
					</p>
					<div<?=HighlightMandatory("site_name");?>>
						<label for="">Site name</label>
						<input type="text" size="25" name="site_name" value="<?=ShowDataText("site_name");?>" />
					</div>
					<div<?=HighlightMandatory("site_url");?>>
						<label for="">Site URL</label>
						<input type="text" size="25" name="site_url" value="<?=ShowDataText("site_url");?>" />
					</div>
					<div<?=HighlightMandatory("site_uploads");?>>
						<label for="">User uploads directory</label>
						<input type="text" size="25" name="site_uploads" value="<?=ShowDataText("site_uploads");?>" />
					</div>
					<div<?=HighlightMandatory("session_name");?>>
						<label for="">Session name</label>
						<input type="text" size="25" name="session_name" value="<?=ShowDataText("session_name");?>" />
					</div>
					<div>
						<label>&nbsp;</label>
						<input type="submit" value="Save" />
					</div>
					&nbsp;<br />
				</div>
				<h3 class="toggler atStart">Database Setup</h3>
				<div class="element atStart">
					<p>
					To setup your database please fill in the fields below.
					</p>
					<div<?=HighlightMandatory("db_hostname");?>>
						<label for="">Database host</label>
						<input type="text" size="25" name="db_hostname" value="<?=ShowDataText("db_hostname");?>" />
					</div>
					<div<?=HighlightMandatory("db_username");?>>
						<label for="">Database username</label>
						<input type="text" size="25" name="db_username" value="<?=ShowDataText("db_username");?>" />
					</div>
					<div<?=HighlightMandatory("db_password");?>>
						<label for="">Database password</label>
						<input type="text" size="25" name="db_password" value="<?=ShowDataText("db_password");?>" />
					</div>
					<div<?=HighlightMandatory("db_database");?>>
						<label for="">Database name</label>
						<input type="text" size="25" name="db_database" value="<?=ShowDataText("db_database");?>" />
					</div>
					<div<?=HighlightMandatory("db__driver");?>>
						<label for="">Database type</label>
						<select name="db_driver">
						<option value="mysql">MySQL</option>
						</select>
					</div>
					<div>
						<label>&nbsp;</label>
						<input type="submit" value="Save" />
					</div>
					&nbsp;<br />
				</div>
				<h3 class="toggler atStart">Create Admin Account</h3>
				<div class="element atStart">
					<p>
					Please use the fields below to create an admin account.
					</p>
					<div<?=HighlightMandatory("admin_name");?>>
						<label for="">Admin name</label>
						<input type="text" size="25" name="admin_name" value="<?=ShowDataText("admin_name");?>" />
					</div>
					<div<?=HighlightMandatory("admin_email");?>>
						<label for="">Admin email</label>
						<input type="text" size="25" name="admin_email" value="<?=ShowDataText("admin_email");?>" />
					</div>
					<div<?=HighlightMandatory("admin_password");?>>
						<label for="">Admin password</label>
						<input type="password" size="25" name="admin_password" value="<?=ShowDataText("admin_password");?>" />
					</div>
					<div<?=HighlightMandatory("admin_password2");?>>
						<label for="">Confirm password</label>
						<input type="password" size="25" name="admin_password2" value="<?=ShowDataText("admin_password2");?>" />
					</div>
					<div>
						<label>&nbsp;</label>
						<input type="submit" value="Save" />
					</div>
					&nbsp;<br />
				</div>
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