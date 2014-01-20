jQuery.validator.addMethod("notEqual", function(value, element, param) {
		  return this.optional(element) || value != param;
}, "Please specify a different (non-default) value");

jQuery.validator.addMethod("afterDate", function(value, element, param) {
		  
		  var arr = value.split("-");
		  var arr2 = param.split("-");
		  var startDate = new Date(arr[2], arr[1] - 1, arr[0], 0, 0, 0, 0);
		  var thisDate = new Date(arr2[2], arr2[1] - 1, arr2[0], 0, 0, 0, 0);
		  
		  if(startDate < thisDate) {
		  return false;
		  }
		  else {
		  return true;
		  }
		  
}, "Please specify a different (non-default) value");

var DoVote=function(questionID,Answer){
	$('#question'+questionID).html('').addClass('loading').load('/question/vote/'+questionID+'/'+Answer);
return false;
};		

var DoShuffle=function(saleID){
	$('#sale'+saleID+' .card:first-child img').removeClass('greyed');
	$('#sale'+saleID+' .share,#sale'+saleID+' .svote').hide();
	var cards = $('#sale'+saleID+' .card').length;
	if(cards==2) {
		$('#sale'+saleID+' .card:last-child').animate({left: '-180px', top: '40px'},400,function(){
			$('#sale'+saleID+' .card:last-child').insertBefore($('#sale'+saleID+' .card:first-child'));
			$('#sale'+saleID+' .card:first-child img').addClass('greyed');
			$('#sale'+saleID+' .card:last-child').css('left','10px').css('top','10px');
			$('#sale'+saleID+' .card:first-child').animate({left: '0px', top: '0px'},300,function(){			
			});
		});
	}
	if(cards==3) {
		$('#sale'+saleID+' .card:last-child').animate({left: '-180px', top: '40px'},400,function(){
			$('#sale'+saleID+' .card:last-child').insertBefore($('#sale'+saleID+' .card:first-child'));
			$('#sale'+saleID+' .card:first-child img').addClass('greyed');
			$('#sale'+saleID+' .card:nth-child(2)').css('left','10px').css('top','10px');
			$('#sale'+saleID+' .card:last-child').css('left','20px').css('top','20px');
			$('#sale'+saleID+' .card:first-child').animate({left: '0px', top: '0px'},300,function(){			
			});
		});
	}
return false;
};

SearchVisible=true;

/*
$(document).scroll(function(){
	var DocPosition = parseInt($(document).scrollTop());
	
	if(DocPosition > 180) {
		if(SearchVisible) {
			SearchVisible=false;
			hideSearch();
		}
	}
	
	if(DocPosition < 170) {
		if(!SearchVisible) {
			SearchVisible=true;
			showSearch();
		}
	}
});
*/

function hideSearch() {
	$('#Header form').animate({'top':'-220px'},1700,function(){});
}

function showSearch() {
	$('#Header form').animate({'top':'0px'},1700,function(){});
}

		
$(document).ready(function(){
	
	historyurl='';
	
	// ALL LIGHTBOX LINKS
	$('.lightbox').live('click',function() {
		//showActivty();
		var WinTop = ($(document).scrollTop() > 0)?$(document).scrollTop() + 60 + 'px':'60px';
		var WinLeft= 0;
		$("#popup").css('width','100%');
		//$("#popup").css('height','auto');
		$('body').css('overflow','hidden');
		hideFields();
		hideFlash();
		showLightboxPanel(6500);
		$("#popup").css('z-index','1000000');
		$("#popup").css("left",WinLeft +'px');
		$("#popup").css("top", WinTop);
		$("#popup").fadeIn(1000);
		$('#popup').load(this.href);
	return false;
	});
	
	$('.sales').on('click','.view',function(){
		var WinTop = ($(document).scrollTop() > 0)?$(document).scrollTop() + 0 + 'px':'0px';
		var WinLeft= 0;
		$("#popupSale").css('width','100%');
		//$('body').css('overflow','hidden');
		hideFields();
		hideFlash();
		//showLightboxPanel(6510);
		$("#LightboxPanelSale").height($(document).height());	
		$("#LightboxPanelSale").width($(document).width());
		$("#LightboxPanelSale").css("left",0);
		$("#LightboxPanelSale").css("top",0);
		$("#LightboxPanelSale").css("z-index", "6510");
		$("#LightboxPanelSale").fadeIn(500);
		$("#popupSale").css('z-index','1000000');
		$("#popupSale").css("left",WinLeft +'px');
		$("#popupSale").css("top", WinTop);
		$("#popupSale").fadeIn(1000);
		$('#popupSale').load(this.href + '?ajax=true');
		historyurl=window.location.href;
		window.history.pushState("", "YupNup : view", this.href);
	return false;
	});
	
	$('.linktopanel').live('click',function(){
		$('#' + $(this).attr('rel')).html('<span class="loading"></span>').load($(this).attr('href'));
	return false;
	});
	
	// LIGHTBOX CLOSE
	$('.BtClosePopup,.BtClose,.closelightbox').live('click', function() {
		$('#popup').hide();
		$("#popup").css('z-index','1');
		$('#popup').html('');
		$("#LightboxPanel").fadeOut(500);
		$("#LightboxPanelSale").fadeOut(500);
		$('body').css('overflow','auto');
		showFields();
		showFlash();
	});
	
	$('.BtCloseYupnup').live('click',function(){
		$('#popupSale').html('').hide();
		$("#LightboxPanel").fadeOut(500);
		$("#LightboxPanelSale").fadeOut(500);
		showFields();
		showFlash();
		//$('body').css('overflow','auto');
		window.history.pushState("", "YupNup", historyurl);
		historyurl=window.location.href;
	return false;
	});
	
	$('.CloseProfile').live('click',function(){
		$('#popup').html(' ').hide();
		showFields();
		showFlash();
	return false;
	});
     
     $('.ajaxlink').live('click',function(){
     	if($(this).attr('rel')!='') {
     	$('#' + $(this).attr('rel')).load($(this).attr('href'));
     	}
     return false;
     });
	
});

var showTick = function(){
	var WinTop = ($(document).scrollTop() > 0)?$(document).scrollTop():0;
	$('.greentick').css('opacity',10).css('top',parseInt(WinTop + (($(window).height()-150)/2)) + 'px' ).show().animate({'opacity':0},6000,function(){
	$('.greentick').hide()
	});
}

var triggerTopPage = function(which,url){
	DisplayHeight=($(window).height() < 800)?800:$(window).height();
	ParentBG=$(which).parent().css('background-color');
	PageUrl=url;
	PageRel=$(which).attr('rel');
	// If new page then clear old contents befor sliding
	if(ActivePage!=PageRel) {
	$('#TopPage .wrapper').html(' ');
	}
	// now slide down
	$('#TopPage').css('background',ParentBG).animate({'height':DisplayHeight - 280 + 'px'},1000,function(){
		IsClosed=false;
		if(ActivePage!=PageRel) {
			$('#TopPage .wrapper').html(' ').html('<div class="loader"></div>').load(PageUrl);
			ActivePage=PageRel;						
		}
	});
}


var triggerProfilebox = function(url){
	//showActivty();
	var WinTop = '180px';
	var WinLeft= 0;
	$("#popup").css('width','100%');
	//$("#popup").css('height','auto');
	//$('body').css('overflow','hidden');
	hideFields();
	//showLightboxPanel(500);
	$("#popup").css('z-index','490');
	$("#popup").css("left",WinLeft +'px');
	$("#popup").css("top", WinTop);
	$("#popup").fadeIn(1000);
	$('#popup').load(url);
}

/*
	LIGHTBOX BACKGROUND

*/

var triggerLightbox = function(url){
	//showActivty();
		var WinTop = ($(document).scrollTop() > 0)?$(document).scrollTop() + 60 + 'px':'60px';
		var WinLeft= 0;
		$("#popup").css('width','100%');
		//$("#popup").css('height','auto');
		$('body').css('overflow','hidden');
		hideFields();
		hideFlash();
		showLightboxPanel(6500);
		$("#popup").css('z-index','1000000');
		$("#popup").css("left",WinLeft +'px');
		$("#popup").css("top", WinTop);
		$("#popup").fadeIn(1000);
		$('#popup').load(url);
}

var showActivty = function(){
	$("#Activity").height($(window).height());	
	$("#Activity").width($(window).width());
	$('#Activity').show();
}

var showLightboxPanel = function(zindex){
	$("#LightboxPanel").height($(document).height());	
	$("#LightboxPanel").width($(document).width());
	$("#LightboxPanel").css("left",0);
	$("#LightboxPanel").css("top",0);
	$("#LightboxPanel").css("z-index", zindex);
	if(jQuery.browser.msie){
		$("#LightboxPanel").css("filter", "alpha( opacity=75 )");
	}
	if (jQuery.browser.mozilla){
		$("#LightboxPanel").css("opacity", ".75");
	}
	if (jQuery.browser.safari || jQuery.browser.opera)
	{
		$("#LightboxPanel").css("opacity", "0.5");
	}
	$("#LightboxPanel").fadeIn(500);
	
	//alert($("#ContentWrapper").offset().left);
}

var showLightboxPanelNoFade = function(zindex){
	$("#LightboxPanel").height($(document).height());	
	$("#LightboxPanel").width($(document).width());
	$("#LightboxPanel").css("left",0);
	$("#LightboxPanel").css("top",0);
	$("#LightboxPanel").css("z-index", zindex);
	$("#LightboxPanel").fadeIn(500);
	
	//alert($("#ContentWrapper").offset().left);
}

function showFields() {
	var flashObjects = document.getElementsByTagName("select");
	for (i = 0; i < flashObjects.length; i++) {
		flashObjects[i].style.visibility = "visible";
	}
}

function hideFields() {
	var flashObjects = document.getElementsByTagName("select");
	for (i = 0; i < flashObjects.length; i++) {
		flashObjects[i].style.visibility = "hidden";
	}
}

function showFlash(){
	var flashObjects = document.getElementsByTagName("object");
	for (i = 0; i < flashObjects.length; i++) {
		flashObjects[i].style.visibility = "visible";
	}

	var flashEmbeds = document.getElementsByTagName("embed");
	for (i = 0; i < flashEmbeds.length; i++) {
		flashEmbeds[i].style.visibility = "visible";
	}
}

// ---------------------------------------------------

function hideFlash(){
	var flashObjects = document.getElementsByTagName("object");
	for (i = 0; i < flashObjects.length; i++) {
		flashObjects[i].style.visibility = "hidden";
	}

	var flashEmbeds = document.getElementsByTagName("embed");
	for (i = 0; i < flashEmbeds.length; i++) {
		flashEmbeds[i].style.visibility = "hidden";
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
	else if(DataType=="stringint") {
		if (/[^a-zA-Z0-9]/g.test(V)) {
		El.value = V.replace(/[^a-zA-Z0-9]/g, '');
		}
	}
	else if(DataType=="username") {
		if (/[^a-zA-Z0-9_\-]/g.test(V)) {
		El.value = V.replace(/[^a-zA-Z0-9_\-]/g, '');
		}
	}
	else if(DataType=="password") {
		if (/[^a-zA-Z0-9_!\$\.]/g.test(V)) {
		El.value = V.replace(/[^a-zA-Z0-9_!\$\.]/g, '');
		}
	}
	else if(DataType=="amount") {
		if (/[^0-9\.\$]/g.test(V)) {
		El.value = V.replace(/[^0-9\.\$]/g, '');
		}
	}
	else if(DataType=="nofloat") {
		if (/[^0-9\$]/g.test(V)) {
		alert('Sorry, only whole dollar amounts are permitted.');
		El.value = V.replace(/[^0-9\$]/g, '');
		}
	}
}