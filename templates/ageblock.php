<!DOCTYPE html>
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
		<script src="/js/functions.js"></script>
	</head>
	<body id="HomeBody">
		<div id="Wrapper">
			<div class="contentwidth">
				<form id="ageblock" action="/" method="post">
					<input type="hidden" name="ofage" id="ofage" value="" />
					<h2>You must be of a legal <br />drinking age to enter</h2>
					<div>
						<span class="checkbox"><span></span></span>
						<label>I am of legal drinking age in my country of residence</label>
						<div class="clear"></div>
					</div>
					<div>
						<button type="submit"><span>Enter</span></button>
					</div>
				</form>
				<script>
				var init = function(){
					$('#ageblock').css('margin-top',parseInt($(window).height()-458)/2);
				};
				$(window).resize(function(){
					init();
				});
				init();
				
				$('#ageblock .checkbox').click(function(){
					if($('#ofage').val()=='Y') {
					$(this).removeClass('checked');
					$('#ofage').val('');
					}
					else {
					$(this).addClass('checked');
					$('#ofage').val('Y');
					}
				});
				
				$('#ageblock').submit(function(){
					if($('#ofage').val()=='Y') {
						return true;
					}
					else {
					alert("Sorry you must be of legal drinking age to enter this website");
					}
				return false;
				});
				</script>
			</div>
		</div>		
	</body>
</html>