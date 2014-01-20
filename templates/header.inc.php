				<div id="Header">
					<a href="/" id="TopLogo"><span>Sail &amp; Anchor</span></a>
					<ul id="Nav">
					<li><a href="/#OurBeer" data-rel="OurBeer"><span>Our Beer</span></a></li>
					<li><a href="/#LimitedEdition" data-rel="LimitedEdition"><span>Limited Edition</span></a></li>
					<li><a href="/#ThePub" data-rel="ThePub"><span>The Pub</span></a></li>
					<li><a href="/#WhatsOn" data-rel="WhatsOn"><span>What's On</span></a></li>
					<li><a href="/#FindUs" data-rel="FindUs"><span>Find Our Beer</span></a></li>
					<li><a href="/#ContactUs" data-rel="ContactUs"><span>Contact</span></a></li>
					<li class="facebook"><a href="http://www.facebook.com/" target="_blank"><span>Find us on Facebook</span></a></li>
					</ul>
					
					<div id="MobileMenu"></div>
					<div class="clear"></div>
					
					<div class="dividor"></div>
					<div class="spacer20">&nbsp;</div>
					<div id="MobileNav"></div>
					<div class="clear"></div>
					
				</div>
				<div id="HeaderSpacer"></div>
				
				<script>
				var HeaderHeight=$('#Header').height();
				
				$('#MobileMenu').click(function(){
					if($('#Header').height()>379) {
						$('#Header').animate({'height':HeaderHeight},500,function(){
							$('#MobileNav').html('');
						});
					}
					else {
						$('#MobileNav').html('<ul></ul>');
						$('#MobileNav ul').html($('ul#Nav').html());
						$('#Header').animate({'height':'380px'},500,function(){});
						
					}
				});
				</script>