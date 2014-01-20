<?php
function DisplayUserProfile($Details) {
GLOBAL $CONFIG;
?>
<h1>Setup</h1>
<form name="Form" method="post" action="<?=$_SERVER["PHP_SELF"];?>">
<input type="hidden" name="id" value="<?php if(isset($_REQUEST["id"])) { echo $_REQUEST["id"]; } ?>" />
<input type="hidden" name="mandatory" value="u_name,u_email,usertype_id,u_pass,u_pass2" />
<input type="hidden" name="mod" value="<?=$_REQUEST["mod"];?>" />
<input type="hidden" name="task" value="save" />
	
	<div class="element atStart">
		
		<div<?=HighlightMandatory("site_name");?>>
						<label for="">Site name</label>
						<input type="text" size="45" name="site_name" value="<?=$CONFIG['site']['name'];?>" />
					</div>
					<div<?=HighlightMandatory("site_url");?>>
						<label for="">Site URL</label>
						<input type="text" size="45" name="site_url" value="<?=$CONFIG['site']['url'];?>" />
					</div>
					<div<?=HighlightMandatory("site_path");?>>
						<label for="">Site path</label>
						<input type="text" size="45" name="site_path" value="<?=$CONFIG['site']['site_path'];?>" />
					</div>
					<div<?=HighlightMandatory("site_path_nice");?>>
						<label for="">Site root</label>
						<input type="text" size="45" name="site_path_nice" value="<?=$CONFIG['site']['site_path_nice'];?>" />
					</div>
					<div<?=HighlightMandatory("site_uploads");?>>
						<label for="">User uploads directory</label>
						<input type="text" size="45" name="site_uploads" value="<?=$CONFIG['site']['user_uploads'];?>" />
					</div>
					<div<?=HighlightMandatory("session_name");?>>
						<label for="">Session name</label>
						<input type="text" size="45" name="session_name" value="<?=$CONFIG['session']['name'];?>" />
					</div>
					
					<br />
					<h1>Database</h1>
					
					<div<?=HighlightMandatory("db_hostname");?>>
						<label for="">Database host</label>
						<input type="text" size="45" name="db_hostname" value="<?=$CONFIG['db']['default']['hostname'] ;?>" />
					</div>
					<div<?=HighlightMandatory("db_username");?>>
						<label for="">Database username</label>
						<input type="text" size="45" name="db_username" value="<?=$CONFIG['db']['default']['username'] ;?>" />
					</div>
					<div<?=HighlightMandatory("db_password");?>>
						<label for="">Database password</label>
						<input type="text" size="45" name="db_password" value="<?=$CONFIG['db']['default']['password'] ;?>" />
					</div>
					<div<?=HighlightMandatory("db_database");?>>
						<label for="">Database name</label>
						<input type="text" size="45" name="db_database" value="<?=$CONFIG['db']['default']['database'] ;?>" />
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
</form>
<?php
}
?>