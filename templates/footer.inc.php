	<div class="clear"></div>
	<div class="push"></div>
</div>

	<footer id="Footer">
		<div class="Wrapper">
			<a href="/" class="footer-logo"><span>Dani Le Ray Gymnastics</span></a>
			<p class="copyright" style="color:#f7b8c5;">&copy; <?=date("Y");?> LeRay Gymnastics</p>
			<p class="contactinfo"><b>EMAIL:</b> hi@leraygymnastics.com.au<br /><b>PHONE:</b> 0416 133 792</p>
			<div class="clear"></div>	
		</div>
	</footer>

	<div id="popup"></div>
	<div id="LightboxPanel"></div>
	<div id="Activity"></div>
	<div id="MobileMenu">
		<div class="closemenu"><span>Close</span></div>
		<h1>Site Menu</h1>
		<ul>
			<li id="navhome2"><a href="/" class="<?=($PageDetails["section_id"]=='home')?'current':'';?>"><span>Home</span></a></li>
			<li id="navcentres2"><a href="/centres" class="<?=($PageDetails["section_id"]=='centres')?'current':'';?>"><span>Our Centres</span></a></li>
			<li id="navabout2"><a href="/about" class="<?=($PageDetails["section_id"]=='about')?'current':'';?>"><span>About Dani Le Ray</span></a></li>
			<li id="navregister2"><a href="/register" class="<?=($PageDetails["section_id"]=='register')?'current':'';?>"><span>Registrations</span></a></li>
			<li id="navcontact2"><a href="/contact" class="<?=($PageDetails["section_id"]=='contact')?'current':'';?>"><span>Contact</span></a></li>		
		</ul>
	</div>
	<script>
	scrollTo(0,0);
	$('#MobileMenu').css('left',parseInt($(window).width())+'px').css('height',$(document).height());
	$('#Menu').click(function(){
		$('#MobileMenu').show().animate({'left':'0px'},300);
	return false;
	});
	
	$('.closemenu').click(function(){
		$('#MobileMenu').show().animate({'left':parseInt($(window).width())+'px'},300,function(){
			$('#MobileMenu').hide();
		});
	return false;
	});
	</script>
</body>
</html>