<?php include_once("metadata.inc.php");?>
	<body id="HomeBody">
		<div id="Wrapper">
			<div class="contentwidth">
				<?php include_once("header.inc.php");?>
		
				<div id="HomePromo">
					<div id="slideshow" class="cycle-slideshow">
						<?php
						$sql=mysql_query("SELECT * FROM home_promos WHERE promo_image!=''");
						while($row=mysql_fetch_array($sql)) {
							if(is_file("uploads/".$row["promo_image"])) {
							?>
							<div><?=($row["promo_url"]!="")?'<a href="'.$row["promo_url"].'">':'';?><img src="/uploads/<?=$row["promo_image"];?>" border="0" alt="" /><?=($row["promo_url"]!="")?'</a>':'';?></div>
							<?php
							}
						}
						?>
						<span class="cycle-pager"></span>
					</div>
					
					<a href="#" id="prevslide"><span>Previous Slide</span></a>
					<a href="#" id="nextslide"><span>Next Slide</span></a>
				</div>
				
				<script>
				$('#slideshow').cycle({
				fx: 'scrollHorz',
				slides: '> div',
				//timeout: '2000',
				prev: '#prevslide',
				next: '#nextslide',
				'pager-template': '<span>&bull;</span>',
				'pause-on-hover': true,
				swipe:true
				});
				</script>
		
				<div id="OurBeer" class="OurBeer">
					<div class="page"></div>
					<script>
					$('#OurBeer .page').load('/our-beer?ajax');
					</script>
					<div class="dividor"></div>
				</div>
		
				<div id="LimitedEdition" class="LimitedEdition">
					<div class="page"></div>
					<script>
					$('#LimitedEdition .page').load('/limited-edition?ajax');
					</script>
					<div class="dividor"></div>
				</div>
		
				<div id="ThePub" class="ThePub">
					<div class="page"></div>
					<script>
					$('#ThePub .page').load('/the-pub?ajax');
					</script>
					<div class="dividor"></div>
				</div>
		
				<div id="WhatsOn" class="WhatsOn">
					<div class="page"></div>
					<script>
					$('#WhatsOn .page').load('/whats-on?ajax');
					</script>
					<div class="dividor"></div>
				</div>
		
				<div id="FindUs" class="page FindUs">
					<h1>Find Our Beer</h1>
					
					<form id="FindUsForm">
						<input type="text" name="kw" id="kw" value="Enter your suburb or postcode" onfocus="if(this.value=='Enter your suburb or postcode') { this.value=''; }" onblur="if(this.value=='') { this.value='Enter your suburb or postcode'; }" />
						<button type="submit" id="findsubmit"><span>Search</span></button>
						<button type="button" id="locateme"><span>Locate me</span></button>
					</form>
					
					<div id="Map"></div>
					
					<div id="results"></div>
					<div class="clear"></div>
					
					<script>
					InitMap("-28.335448","134.745076",4,'');
					$('#locateme').click(function(){
						if (navigator.geolocation) {
						$('#locateme span').html('working...');
							navigator.geolocation.getCurrentPosition(handle_geolocation_query);
						}
					return false;
					});
					
					var handle_geolocation_query=function(position){
						InitMap(position.coords.latitude,position.coords.longitude,11,'');
						DisplaySites(position.coords.latitude,position.coords.longitude,true);
						
						$.getJSON('/_mapdata.php?lat='+position.coords.latitude+'&lng='+position.coords.longitude,function(json) {	
							if(json.markers.length>0) {
								$('#results').append('<div class="col"></div>');
								Col = $('#results .col:first-child');
								var x=1;
								$.each(json.markers, function(i,data){
									if((x-1) == Math.floor(json.markers.length/2)) {
										$('#results').append('<div class="col"></div>');
										Col = $('#results .col:last-child');
									}
									Col.append('<div id="S'+data.store_id+'" class="'+data.type+'"><img src="/pin.php?number='+x+'" border="0" /><b>'+data.name+' <span>('+data.distance+'km)</span></b>'+data.address+'</div>');
								x++;
								});
							}
							else {
							alert('We are regularly adding more bars and retailers. Check back soon');
							}
						});
									
						$('#locateme span').html('Locate me');
									
					};
					
					$('#FindUsForm').submit(function(){
						if($('#kw').val()!="" && $('#kw').val()!="Enter your suburb or postcode") {
							$('#findsubmit span').html('working...');
							$('#results').html('');
							$.getJSON("https://maps.googleapis.com/maps/api/geocode/json?address="+encodeURI($('#kw').val())+" Australia&sensor=true",function(json){
								if(json.status=='OK'){
									$('#results').show();
									var lat=json.results[0].geometry.location.lat;
									var lng=json.results[0].geometry.location.lng;
									
									InitMap(lat,lng,11,'');
									DisplaySites(lat,lng,true);
									//map.setZoom(11);
									offsetCenter(new google.maps.LatLng(lat, lng), 250, 1);
									
									$.getJSON('/_mapdata.php?lat='+lat+'&lng='+lng,function(json) {	
										if(json.markers.length>0) {
											$('#results').append('<div class="col"></div>');
											Col = $('#results .col:first-child');
											var x=1;
											$.each(json.markers, function(i,data){
												if((x-1) == Math.floor(json.markers.length/2)) {
													$('#results').append('<div class="col"></div>');
													Col = $('#results .col:last-child');
												}
												Col.append('<div id="S'+data.store_id+'" class="'+data.type+'"><img src="/pin.php?number='+x+'" border="0" /><b>'+data.name+' <span>('+data.distance+'km)</span></b>'+data.address+'</div>');
											x++;
											});
										}
										else {
										alert('We are regularly adding more bars and retailers. Check back soon');
										}
									});
									
									$('#findsubmit span').html('Search');
								}
							});
						}
					return false;
					});
					</script>
					
					<div class="dividor"></div>
				</div>
		
				<div id="ContactUs">
					<div class="page"></div>
					<script>
					$('#ContactUs .page').load('/contact?ajax');
					</script>
					<div class="dividor"></div>
				</div>
			</div>
		</div>		
	</body>
</html>