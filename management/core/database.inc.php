<?php
/**
 **
 ** Pro:cms
 ** Revision: 4.07.2007
 ** author: Scott Dilley
 ** version: 2.0
 **
 ** Database wrapper
 **
*/

if (!defined('BASEPATH')) exit('No direct script access allowed');

if (! $DBConn = @mysql_connect($CONFIG['db']['default']['hostname'], $CONFIG['db']['default']['username'], $CONFIG['db']['default']['password'])) {
	$message=@mysql_error();
	include(FCPATH.'/errors/error_db.php');
	die;
}

if (! @mysql_select_db($CONFIG['db']['default']['database'], $DBConn)) {
	$message=@mysql_error();
	include(FCPATH.'/errors/error_db.php');
	die;
}

## check default tables
if($error=CheckMainTables($CONFIG['db']['default']['database'])) {
$heading="Database error";
$message="Oops, we were unable to create any tables in your database. Please run the sql query below to generate the required tables.";
include(FCPATH.'/errors/error_tables.php');
die;
}
?>