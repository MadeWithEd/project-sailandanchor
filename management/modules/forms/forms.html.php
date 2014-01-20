<?php
function DisplayPages() {
GLOBAL $search,$CONFIG,$Config,$_REQUEST,$orderby;
	$WHERE="";
	if(isset($_REQUEST["search"])) {
		if(isset($_REQUEST["form_name"])) {
		$WHERE.=" AND form_name = '".$_REQUEST["form_name"]."'";
		}
		if(isset($_REQUEST["season_id"])) {
		$WHERE.=" AND (";
			foreach($_REQUEST["season_id"] as $key=>$val) {
			$WHERE.="a.season_id='".$val."' OR ";
			}
		$WHERE=substr($WHERE,0,strlen($WHERE)-4).")";
		}
	}
	$JscriptParams=$_SERVER["QUERY_STRING"];
	$orderby=(isset($_REQUEST["orderby"]) && $_REQUEST["orderby"]!="")?$_REQUEST["orderby"]:"date_created DESC";
	$res=MySQLResult("SELECT count(*) FROM form_submissions WHERE 1 $WHERE");
	$SD["table"]["width"]="100%";
	$SD["table"]["border"]="0";
	$SD["table"]["cellpadding"]="0";
	$SD["table"]["cellspacing"]="0";
	$SD["table"]["class"]="0";
	$SD["header"]["class"]="RowHeader";
	$SD["header"]["divider"]="SpacerRow";
	$SD["header"]["link"]["class"]="RowHeader";
	$SD["header"]["fields"]["title"]=array("", "Form","Email","Replied",  "Date submitted","");
	$SD["header"]["fields"]["name"]=array("", "form_name","","", "date_created","");
	$SD["header"]["fields"]["width"]=array("30", "","220", "110", "130","30");
	$SD["header"]["fields"]["content"]=array("<input type=\"checkbox\" name=\"sess_id[]\" value=\"{sess_id}\" />", "{form_name}","#s- SELECT field_value FROM form_data WHERE form_id={form_id} AND field_name='email' -s#","#- if(MySQLResult('SELECT count(*) FROM form_replies WHERE form_id={form_id}')>0) { echo 'Y'; } -#", "{date_created}","<a href=\"".FCPATHNICE."?mod=".$_REQUEST["mod"]."&task=create&id={form_id}\"><img src=\"".TEMPLATEPATHNICE."images/ico_edit.gif\" border=\"0\" alt=\"\" /></a>");
	$SD["query"]["select"]="select *";
	$SD["query"]["from"]="form_submissions";
	$SD["query"]["where"]="1 $WHERE";
	$SD["data"]["class"]="DataRow";
	$SD["extra"]["buttons"]["label"]=array("Select All", "Download Selected", "Download All");
	$SD["extra"]["buttons"]["onclick"]=array("doSelectAll('sess_id[]',this);", "doDownloadSelected('sess_id[]','".FCPATHNICE."?Hideoutput=1&mod=".$_REQUEST["mod"]."&task=download');", "doDownloadAll('&".$JscriptParams."','".FCPATHNICE."?Hideoutput=1&mod=".$_REQUEST["mod"]."&task=download');");
	$SD["extra"]["buttons"]["class"]=array("130", "130", "130");
	$SD["extra"]["buttons"]["id"]=array("Sl","","");
	?>
	<h1>Viewing your form submissions</h1>
	
	<p>
		<div style="float:left;width:300px;">Viewing <?=$res;?> records</div>
		<div style="float:right;width:500px;text-align:right;"><?=Paginate($res);?></div>
	</p>
	<form name="FormDisplay" style="clear:both;" action="<?=$_SERVER["PHP_SELF"];?>" method="post">
	<input type="hidden" name="mod" value="<?=$_REQUEST["mod"];?>" />
	<input type="hidden" name="sub" value="<?=$_REQUEST["sub"];?>" />
	<input type="hidden" name="task" value="publish" />
	&nbsp;<br />
	<?=ShowDisplayHeader($SD); ?>
	</form>
	<?php if(isset($_GET["preview"])) { ?>
		<script type="text/javascript">
		var Pop=window.open('about:blank','test','width=100,height=100,scrollbars=0,menubar=1');
		if(Pop) {
		Pop.close();
		window.open('<?=$_GET["preview"];?>','','');
		}
		else {
		alert('you need to turn off popup blockers');
		}
		</script>
	<?php
	}
}


function DisplayPageForm($Details) {
GLOBAL $Config;
?>
	<style type="text/css"><!--
	#Form DIV Label {
		display:-moz-inline-stack;
		display:inline-block;
		zoom:1;
		*display:inline;
		float:left;
		width:150px;
	}
	
	#Form DIV {
		margin:5px 0 5px 0;
	}
	
	#GuestCol {
		width:49%;
		float:left;
	}
	
	#MemberCol {
		width:49%;
		float:left;
	}
	
	#MidCol {
		float:left;
		width:10px;
	}
	//--></style>
	<script>
	$('#SubMenu li:eq(2) a').addClass('current');
	</script>
	
	
	<h1>Viewing Enquiry</h1>
	
	<?=DisplayError();?>
	<form name="Form" id="Form" method="post" action="<?=$_SERVER["PHP_SELF"];?>" enctype="multipart/form-data">
	<input type="hidden" name="id" value="<?php if(isset($_REQUEST["id"]) && $_REQUEST["id"]!="") { echo $_REQUEST["id"]; } ?>" />
	<input type="hidden" name="mod" value="<?=$_REQUEST["mod"];?>" />
	<input type="hidden" name="task" value="save" />
	<input type="hidden" name="mandatory" value="subject,to,body,from" />
		
		<div id="Controls">
			<div><input type="image" src="<?=TEMPLATEPATHNICE.'images/save_f2.png';?>" alt="" name="save" /><br />save</div>
			<div><input type="image" src="<?=TEMPLATEPATHNICE.'images/cancel_f2.png';?>" alt="" name="cancel" /><br />cancel</div>
		</div>
		<div style="clear:both;">&nbsp;</div>
			
		<div style="float:left;width:45%;">
			
			<h2>Enquiry Details</h2>
			
			<?php
			$sql=mysql_query("SELECT * FROM form_data WHERE form_id='".$Details["form_id"]."'");
			while($row=mysql_fetch_array($sql)) {
			?>
			<div style="border-bottom:1px solid #ccc;padding:8px 0;">
				<label><?=$row["field_name"];?></label>
				<?=ShowDataText("field_value",$row);?>
			</div>
			<?php
			}
			?>
			
		</div>
		<div style="float:right;width:45%;">
			
			
			
		</div>
		<div style="clear:both;"></div>
		
	</form>
	<?php
}

function DisplayResellerSearch(){
?>
	<style type="text/css"><!--
	#Form DIV Label {
		display:-moz-inline-stack;
		display:inline-block;
		zoom:1;
		*display:inline;
		float:left;
		width:150px;
	}
	
	#Form DIV {
		margin:5px 0 5px 0;
	}
	
	#GuestCol {
		width:49%;
		float:left;
	}
	
	#MemberCol {
		width:49%;
		float:left;
	}
	
	#MidCol {
		float:left;
		width:10px;
	}
	//--></style>
	<script>
	$('#SubMenu li:eq(1) a').addClass('current');
	</script>
	
<h1>Form Submissions &gt; Search</h1>
	<form name="Form" id="Form" method="get" action="<?=$_SERVER["PHP_SELF"];?>">
	<input type="hidden" name="mod" value="<?=$_REQUEST["mod"];?>" />
	<input type="hidden" name="search" value="search" />
	
		
		<div>
			<label>Form Name</label>
			<select name="form_name">
			<option value="">- select - </option>
			<?=PopulateSelect("form_submissions","form_name","form_name",ShowDataText("form_name"),"1 GROUP BY form_name ORDER BY form_name");?>
			</select>
		</div>
		
		
		<div>
			<label>&nbsp;</label>
			<button type="submit">Search</button>
		</div>
	
	</form>
<?php
}

?>