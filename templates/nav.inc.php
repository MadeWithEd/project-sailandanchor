					<?php
					function GenerateSideNav($PageID,$NewLevel) {
					GLOBAL $NavRootFileName,$PageParts,$PageDetails,$NavRoot;
					$Started=false;
					$HTML='';
					$aboveurl=MySQLResult("SELECT page_file_name FROM pages WHERE page_id=".$PageID);
					
						$sql=mysql_query("SELECT page_id,page_file_name,page_name,form_type FROM pages WHERE sub_id='".$PageID."' AND in_nav='Y' AND page_status='1' ORDER BY sort_order ASC");
						if(mysql_num_rows($sql)>0) {
							
							while($row=mysql_fetch_array($sql)) {
							
									$rooturl=SITEURL.'/'.$aboveurl;
									$NavRootFileNameNew=($NewLevel=='')?'':$NavRootFileName.'/';
									$class=($row["page_file_name"]==$PageDetails["page_file_name"])?"current":"";
									$class2=($NewLevel!='')?' subnav':'';
									
									$HTML.='<li class="'.$class.' '.$class2.'"><a href="'.$rooturl.'/'.$NavRootFileNameNew.$row["page_file_name"].'" title="'.stripslashes($row["page_name"]).'" class="opener';
									$HTML.=($row["page_file_name"]==$PageDetails["page_file_name"])?' selected':'';
									$HTML.='"><span class="l"></span><span>'.stripslashes($row["page_name"]).'</span><span class="r"></span></a>'."\n";
								
										## look for sub-levels
										if((MySQLResult("SELECT count(*) FROM pages WHERE sub_id='".$row["page_id"]."' AND in_nav='Y' AND page_status='1'")>0 && $row["page_file_name"]==$PageDetails["page_file_name"])
										|| 
										($row["page_id"]) == MySQLResult("SELECT sub_id FROM pages WHERE page_id='".$PageDetails["page_id"]."'")) {
										$HTML.= GenerateSideNav($row["page_id"],'true');
										$NewLevel='';
										}
								
									$HTML.='</li>'."\n";
								
							}
							
						}	
						
						
					return $HTML;
					}
					?>
					
					<div class="abovenav"></div>
					<ul>
					<?=GenerateSideNav($NavRoot,'');?>
					</ul>
					<div class="belownav"></div>
					