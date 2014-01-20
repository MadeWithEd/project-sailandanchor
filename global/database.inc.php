<?php

$DBHOST = "localhost";			/* database server hostname */
$DBNAME = "sailmade_sail";			/* database name */
$DBUSER = "sailmade_sail";		/* database user */
$DBPASS = "lD9WyEFSC1cK6ZU1S";		/* database password */
    

if (! $DBConn = @mysql_connect($DBHOST, $DBUSER, $DBPASS)) {
	echo "cannot connect: ".@mysql_error();
	die;
}

if (! @mysql_select_db($DBNAME, $DBConn)) {
	echo "cannot select:". @mysql_error();
	die;
}

function execute_query($sql) {
	$results = array();
	//$resultset = mysql_query($sql);
	if(eregi("SELECT",substr($sql,0,10))) {
		if(mysql_num_rows(mysql_query($sql))==1) {
		$one = @mysql_result(mysql_query($sql),0);
		return $one;
		}
		else {
   			while($row = mysql_fetch_array(mysql_query($sql))) {
        	$results[] = $row;
    		}
    	return $results;
    	}
	}
	elseif(eregi("INSERT",substr($sql,0,10))) {
		$id=mysql_insert_id();
		//$id=$con->lastInsertID("tmp_donations", "donation_id");
		return $id;
	}
	else {
		return false;
	}
	
}


?>