<?php include_once("metadata.inc.php");?>
<body class="inner">
	<div id="wrapper" >
		<div class="w1">
			<?php include_once("header.inc.php");?>
			<div id="main">
				<div class="main-holder">
					<div class="twocolumns">
						<div id="content">
							<div class="heading">
								<h1><?=stripslashes($PageDetails["page_name"]);?></h1>
								<ul class="breadcrumbs">
									<?=GetPathToPage($PageDetails["page_id"]);?>
								</ul>
							</div>
							<div class="content-holder">
								
								
								
								
								
							</div>
						</div>
						<div id="aside">
							<div class="block">
								<h2>Featured Job</h2>
								<img src="<?=SITEURL;?>/images/img-1.gif" width="245" height="112" alt="image description" />
								<div class="text-holder">
									<p>Lorem ipsum consectetur adipiscing elit. Vivamus non pretium orci. Aliquam vestibulum magna vitae rhoncus.</p>
									<dl>
										<dt>Role:</dt>
										<dd>Recruiter</dd>
										<dt>Based:</dt>
										<dd>Sydney CBD</dd>
									</dl>
									<div class="holder">
										<a href="#" class="more">More</a>
										<a href="#" class="share">Share</a>
									</div>
								</div>
							</div>
							<div class="block">
								<h2>Our details</h2>
								<img src="<?=SITEURL;?>/images/map.gif" width="245" height="112" alt="image description" />
								<div class="text-holder">
									<dl class="contact">
										<dt>Switchboard</dt>
										<dd>+61 2 8246 7778</dd>
										<dt>Email</dt>
										<dd><a href="mailto:&#101;&#110;&#113;&#117;&#105;&#114;&#121;&#064;&#112;&#114;&#101;&#099;&#105;&#115;&#105;&#111;&#110;&#115;&#111;&#117;&#114;&#099;&#105;&#110;&#103;&#046;&#099;&#111;&#109;&#046;&#097;&#117;">&#101;&#110;&#113;&#117;&#105;&#114;&#121;&#064;&#112;&#114;&#101;&#099;&#105;&#115;&#105;&#111;&#110;&#115;&#111;&#117;&#114;&#099;&#105;&#110;&#103;&#046;&#099;&#111;&#109;&#046;&#097;&#117;</a></dd>
										<dt>Address</dt>
										<dd><address>Level 5, 8 Spring St, Sydney, 2000</address></dd>
									</dl>
								</div>
							</div>
						</div>
					</div>
					<div id="sidebar">
						<?php include_once("nav.inc.php");?>
					</div>
				</div>
			</div>
			<?php include_once("footer.inc.php");?>
		</div>
	</div>
</body>
</html>