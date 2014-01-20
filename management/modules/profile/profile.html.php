<?php
function DisplayUserProfile($Details) {
?>
<h1>Update your profile</h1>
<form name="Form" method="post" action="<?=$_SERVER["PHP_SELF"];?>">
<input type="hidden" name="id" value="<?php if(isset($_REQUEST["id"])) { echo $_REQUEST["id"]; } ?>" />
<input type="hidden" name="mandatory" value="u_name,u_email,u_pass,u_pass2" />
<input type="hidden" name="mod" value="<?=$_REQUEST["mod"];?>" />
<input type="hidden" name="task" value="save" />
	
	<div class="element atStart">
		
		<div<?=HighlightMandatory("u_name");?>>
			<label for="">Full name</label>
			<input type="text" size="30" name="u_name" value="<?= ShowDataText("u_name"); ?>" />
		</div>
		<div<?=HighlightMandatory("u_email");?>>
			<label for="">Email address</label>
			<input type="text" size="30" name="u_email" value="<?= ShowDataText("u_email"); ?>" />
		</div>
		<div<?=HighlightMandatory("u_pass");?>>
			<label for="">Password</label>
			<input type="password" size="30" name="u_pass" value="<?= ShowDataText("u_pass"); ?>" />
		</div>
		<div<?=HighlightMandatory("u_pass2");?>>
			<label for="">Confirm Password</label>
			<input type="password" size="30" name="u_pass2" value="<?= ShowDataText("u_pass"); ?>" />
		</div>
		<div<?=HighlightMandatory("u_results");?>>
			<label for="">Results per page</label>
			<select name="u_results">
			<option value="10"<?= ShowSelected("10","u_results",$Details); ?>>10</option>
			<option value="15"<?= ShowSelected("15","u_results",$Details); ?>>15</option>
			<option value="20"<?= ShowSelected("20","u_results",$Details); ?>>20</option>
			<option value="25"<?= ShowSelected("25","u_results",$Details); ?>>25</option>
			<option value="30"<?= ShowSelected("30","u_results",$Details); ?>>30</option>
			<option value="40"<?= ShowSelected("40","u_results",$Details); ?>>40</option>
			<option value="50"<?= ShowSelected("50","u_results",$Details); ?>>50</option>
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