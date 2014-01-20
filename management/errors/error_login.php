<html>
<head>
<title>Login</title>
<script type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
function ChangeForm() {
	if(document.FormLogin.remind.checked==true) {
	document.FormLogin.submitbutton.value="Remind me";
	}
	else {
	document.FormLogin.submitbutton.value="Login";
	}
}

function CheckForm() {
	if(document.FormLogin.remind.checked==true) {
		if(document.FormLogin.u_email.value=="") {
		alert("Please provide your email address for us to email your password to.");
		return false;	
		}
	}
	else {
		if(document.FormLogin.u_email.value=="" || document.FormLogin.u_pass.value=="") {
		alert("Please provide both your email address and password to login.");
		return false;
		}
	}		
}
//--></script>
<link rel="stylesheet" href="<?=TEMPLATEPATHNICE;?>css/style.css" type="text/css" />
</head>
<body>
<div id="NonFooter">
	<div id="TopRow"></div>
	<div id="ContentContainer">
		<br /><br /><br /><br />
		<div id="contentcentred">
			<h1><?=ucfirst(strtolower($CONFIG['site']['name']));?> Login</h1>
			<?php echo $message; ?>
		
			<form name="FormLogin" method="post" action="<?=$_SERVER["PHP_SELF"]?>" onSubmit="return CheckForm();" class="element">
			<input type="hidden" name="Login" value="1" />
			<input type="hidden" name="Page" value="<?=$_SERVER["PHP_SELF"]?>?<?=$_SERVER["QUERY_STRING"]?>" />
			<div>
				<label>Email</label>
				<input type="text" size="30" name="u_email" value="<?=ShowDataText("u_email");?>" />
			</div>
			<div>
				<label>Password</label>
				<input type="password" size="30" name="u_pass" value="" />
			</div>
			<div>
				<label></label>
				<input type="checkbox" name="remind" value="Y" onClick="ChangeForm();" /> Forgotten your password?
			</div>
			<div>
				<label></label>
				<input type="submit" value="Login" name="submitbutton" class="Button" />
			</div>
			</form>
		</div>
</div>
</div>
<div id="Footer">
	<div id="BottomRow"></div>
	<div id="BottomRowLower">
	
	</div>
</div>

</body>
</html>