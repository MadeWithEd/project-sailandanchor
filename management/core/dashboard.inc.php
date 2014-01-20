<h2>Latest Enquiries</h2>

<?php
$SD["table"]["width"]="100%";
$SD["table"]["border"]="0";
$SD["table"]["cellpadding"]="0";
$SD["table"]["cellspacing"]="0";
$SD["table"]["class"]="0";
$SD["header"]["class"]="RowHeader";
$SD["header"]["divider"]="SpacerRow";
$SD["header"]["link"]["class"]="RowHeader";
$SD["header"]["fields"]["title"]=array("Email", "Date submitted","");
$SD["header"]["fields"]["name"]=array("","date_created","");
$SD["header"]["fields"]["width"]=array("", "130","30");
$SD["header"]["fields"]["content"]=array("#s- SELECT field_value FROM form_data WHERE form_id={form_id} AND field_name='email' -s#", "{date_created}","<a href=\"".FCPATHNICE."?mod=forms&task=create&id={form_id}\"><img src=\"".TEMPLATEPATHNICE."images/ico_edit.gif\" border=\"0\" alt=\"\" /></a>");
$SD["query"]["select"]="select *";
$SD["query"]["from"]="form_submissions";
$SD["query"]["where"]="1 $WHERE";
$SD["data"]["class"]="DataRow";
?>
<?=ShowDisplayHeader($SD); ?>