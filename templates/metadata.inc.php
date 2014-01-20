<?php
	if(!isset($_COOKIE["ofage"])) {
	include_once("ageblock.php");
	exit;
	}
?><!DOCTYPE html>
<!--[if lt IE 7 ]> <html class="no-js ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]>    <html class="no-js ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]>    <html class="no-js ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
	<head>
		<title>Sail &amp; Anchor</title>
		<meta charset="utf-8">
		<meta http-equiv="cleartype" content="on">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
			
		<link rel="stylesheet" href="/css/site.css" />
		<link rel="stylesheet" href="/css/tablet.css" media="only screen and (max-width: 1000px), only screen and (max-device-width: 1000px)"/>
		<link rel="stylesheet" href="/css/mini.css" media="only screen and (max-width: 750px), only screen and (max-device-width: 750px)"/>
		<link rel="stylesheet" href="/css/mobile.css" media="only screen and (max-width: 600px), only screen and (max-device-width: 600px)"/>
	
		<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBLu1Lm9GIA0yV5y3i4eSm3CrzNWLKVaPY&amp;sensor=false"></script>
		<script src="/js/jquery-1.7.2.min.js"></script>
		<script src="/js/jquery.validate.min.js"></script>
		<script src="/js/waypoints.min.js"></script>
		<script src="/js/jquery.cycle2.min.js"></script>
		<script src="/js/jquery.cycle2.swipe.min.js"></script>
		<script src="/js/functions.js"></script>
		<script src="/js/functions.mapping.js"></script>
		<script>
		$(document).ready(function(){
			$('#HomeBody #Header ul li a').live('click',function(){
				
				if($('#Header').height()>379) {
					$('#Header').animate({'height':HeaderHeight},500,function(){
						$('#MobileNav').html('');
					});
				}
				
				$('html, body').animate({scrollTop: $('#' + $(this).attr('data-rel')).offset().top - 150}, 1000);
				
			return false;
			});
		});
		
		StickyHeader=false;
		$(document).scroll(function(){
			var DocPosition = parseInt($(document).scrollTop());
			if(DocPosition > 114) {
				if(!StickyHeader) {
					$('#Header').addClass('sticky').css('opacity','0.1');
					$('#HeaderSpacer').addClass('sticky');
					$('#Header').animate({'opacity':'1'},800,function(){});
				StickyHeader=true;
				}
			}
			
			else {
				if(StickyHeader) {
					$('#Header,#HeaderSpacer').removeClass('sticky');
				StickyHeader=false;
				}
			}
			
			$('#OurBeer').waypoint(function(direction){
				
			}, { offset: 1 });
		});
		</script>
	</head>