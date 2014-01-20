							<?php if($PageDetails["featured"]=='Y') { ?>
							<div class="block">
								<h4><?=stripslashes($PageDetails["featured_title"]);?></h4>
								<?php if(is_file($_SERVER["DOCUMENT_ROOT"].'/uploads/'.$PageDetails["featured_image"])) { ?>
								<img src="/uploads/<?=$PageDetails["featured_image"];?>" width="245" alt="image description" />
								<?php } ?>
								<div class="text-holder">
									<?=stripslashes($PageDetails["featured_description"]);?>
									<div class="holder">
										<?php if($PageDetails["featured_url"]!="") { ?>
										<a href="<?=stripslashes($PageDetails["featured_url"]);?>" class="more"<?=(!ereg("audreypage.com.au",$PageDetails["featured_url"]))?' target="_blank"':'';?>>&gt; More</a>
										<?php } ?>
									</div>
								</div>
							</div>
							<?php } ?>
							