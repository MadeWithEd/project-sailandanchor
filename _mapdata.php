<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/global/site.inc.php");
	
	if($_GET["lat"]) {
		$query = "SELECT *, (6371 * acos(cos(radians(".$_GET["lat"].")) * cos(radians(store_lat)) * cos(radians(store_lng) - radians(".$_GET["lng"].")) + sin(radians(".$_GET["lat"].")) * sin(radians(store_lat)))) AS distance ";
		$query .= "FROM stores WHERE 1 HAVING distance < 20 ";
		$query .= "ORDER BY distance ASC LIMIT 20";
	}			
	else {
		$query="SELECT * FROM stores WHERE 1";
	}
	
$HTML='';
?>{
    "markers": [
        <?php
       	$sql=mysql_query($query);
        while($row=mysql_fetch_array($sql)) {
        
        	$HTML.='
        	{
        	    "address": "'.str_replace("\"","",$row["store_street"]).', '.$row["store_suburb"].' '.$row["store_state"].'",
        	    "name": "'.$row["store_name"].'",
        	    "lat": "'.$row["store_lat"].'",
        	    "lng": "'.$row["store_lng"].'",
        	    "store_id": "'.$row["store_id"].'",
        	    "distance": "'.round($row["distance"],2).'"
        	},';
        }
        
        echo substr($HTML,0,-1);
        
        ?>
    ]
}
