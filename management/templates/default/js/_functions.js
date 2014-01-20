var CheckedStatus="off";

function CheckContentForm(daForm) {
	var MissingFields=0;
	var ManFieldValue=daForm.mandatory.value;
	var ManFields=ManFieldValue.split(",");
	for(x=0;x<ManFields.length;x++) {
	FieldName=ManFields[x];
		// go through validation options	
		if(FieldName.indexOf("{email}")>1) {
			FieldName=FieldName.replace("{email}","");
			if(!TestEmail($F(FieldName))) {
			var MissingFields=MissingFields+1;
			}
		}
		else if(FieldName.indexOf("{number}")>1) {
			FieldName=FieldName.replace("{number}","");			
			if(isNaN($F(FieldName))) {
			var MissingFields=MissingFields+1;
			}
		}
		else if(FieldName.indexOf("{amount}")>1) {
			FieldName=FieldName.replace("{amount}","");			
			FieldValue=$F(FieldName).replace("$","");
			if(isNaN(FieldValue) || $F(FieldName)=="") {
			var MissingFields=MissingFields+1;
			}
		}
		else if(FieldName.indexOf("[]")>1) {
			FieldName=FieldName.replace("[]","");
			//alert(FieldName);
		}
		else {
			if($F(FieldName)=="") {
			var MissingFields=MissingFields+1;
			}
		}
	}
		
	if(ManFields.length > 0) {
		if(MissingFields>0) {
		alert("Please fill out all fields marked with an asterisk.");
		return false;
		}
		else {
		return true;
		}
	}
	else {
	return true;
	}
}


function RemoveJunk(El,DataType) {
var V = El.value;
	if(DataType=="int") {
		if (/[^0-9\.]/g.test(V)) {
		El.value = V.replace(/[^0-9\.]/g, '');
		}
	}
	else if(DataType=="string") {
		if (/[^a-zA-Z\.]/g.test(V)) {
		El.value = V.replace(/[^a-zA-Z\.]/g, '');
		}
	}
	else if(DataType=="amount") {
		if (/[^0-9\.\$]/g.test(V)) {
		El.value = V.replace(/[^0-9\.\$]/g, '');
		}
	}
}

function doPublishSelected(ElName) {
	var Len=document.FormDisplay.elements.length;
	var IsaGo=false;
	for(x=0;x<Len;x++) {
		if(document.FormDisplay.elements[x].name==ElName) {
			if(document.FormDisplay.elements[x].checked==true) {
			var IsaGo=true;
			}
		}
	}
	
	if(IsaGo==true) {
	return true;
	}
	else {
	alert('Please use the checkboxes to the left to select the item(s) you wish to publish');
	return false;
	}
}

function doActivateSupporters(ElName) {
	var Len=document.FormDisplay.elements.length;
	var IsaGo=false;
	for(x=0;x<Len;x++) {
		if(document.FormDisplay.elements[x].name==ElName) {
			if(document.FormDisplay.elements[x].checked==true) {
			var IsaGo=true;
			}
		}
	}
	
	if(IsaGo==true) {
	document.FormDisplay.ActivateOnly.value=1;
	document.FormDisplay.submit();
	}
	else {
	alert('Please use the checkboxes to the left to select the supporter(s) you wish to activate');
	}
}

function DoPublishPage(PageID) {
	if(confirm("Clicking OK will publish this page live to the internet")) {
	document.location.href='pages.publish.html?page_id[]=' + PageID;
	}
}

function DoPublishNavigation() {
	if(confirm("Clicking OK will publish any changes to the navigation live to the internet")) {
	document.location.href='pages.nav.html?PublishNavigation=true';
	}
}

function doPublishAll() {
	document.FormDisplay.PublishAll.value="Y";
	document.FormDisplay.submit();
}

function doSelectAll(ElName,El) {
	var Len=document.FormDisplay.elements.length;
	for(x=0;x<Len;x++) {
		if(document.FormDisplay.elements[x].name==ElName) {
			if(CheckedStatus=="off") {
				if(document.FormDisplay.elements[x].checked==false) {
				document.FormDisplay.elements[x].checked=true;
				}			
			}
			else {
			document.FormDisplay.elements[x].checked=false;
			}
		}
	}
	if(CheckedStatus=="off") {
	CheckedStatus="on";
	El.innerHTML="Deselect All";
	}
	else {
	CheckedStatus="off";
	El.innerHTML="Select All";
	}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

/*
## Function to toggle form elements on or off
*/

function ToggleFormLayer(Layer1,Layer2,OnValue,selValue) {
	if(selValue == OnValue) {
	MM_findObj(Layer1).style.display="";
	MM_findObj(Layer2).style.display="";
	}
	else {
	MM_findObj(Layer1).style.display="none";
	MM_findObj(Layer2).style.display="none";
	}
}

/*
## Download buttons
*/

function doDownloadSelected(El,CSVURL) {
	var len=document.FormDisplay.elements.length;
	var GetURL="";
	for(x=0;x<len;x++) {
		if(document.FormDisplay.elements[x].name==El) {
			if(document.FormDisplay.elements[x].checked==true) {
			GetURL=GetURL + El + "=" + document.FormDisplay.elements[x].value + "&";
			}
		}
	}
	
	if(GetURL !="") {
	var Qu=(CSVURL.indexOf('?')==-1)?'?':'&';
	document.location.href=CSVURL + Qu + GetURL;
	}
	else {
	alert("Please use the checkboxes provided to select the items you wish to download");
	}
}

function doDownloadAll(WhereClause,CSVURL) {
	if(WhereClause) {
	var Params=unescape(WhereClause);
	}
	var Qu=(CSVURL.indexOf('?')==-1)?'?':'&';
document.location.href=CSVURL + Qu + Params;
}