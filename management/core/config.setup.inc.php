<?php
/**
 **
 ** Pro:cms
 ** Revision: 4.07.2007
 ** author: Scott Dilley
 ** version: 2.0
 **
 ** Generates Setup file config.inc.php
 **
*/

if (!defined('BASEPATH')) exit('No direct script access allowed');

if(isset($_POST["SETUP"])) {
	if($_POST["SETUP"]=="config") {
		if(!$MISSING = CheckMandatoryFields()) {
			## setup DB, first lets test the connection
			if (! $DBConn = @mysql_connect($_POST['db_hostname'], $_POST['db_username'], $_POST['db_password'])) {
			$error="We were unable to connect to <b>".$_POST["db_driver"]."</b> using the username or password you specified.";
			$MISSING["db_username"]=true;
			$MISSING["db_password"]=true;
			$Show="1";
			}
			if(!isset($error)) {
				if (! @mysql_select_db($_POST['db_database'], $DBConn)) {
				$error="We were unable to connect to a database named <b>".$_POST["db_database"]."</b>. Please make sure that you have created the database and that the name has been entered correctly.";
				$MISSING["db_database"]=true;
				$Show="1";
				}
			}
			if(!isset($error)) {
				if(!is_dir(SITEPATH.$_POST["site_uploads"])) {
				@umask(0000);
					if(!mkdir(SITEPATH.$_POST["site_uploads"])) {
					$error="Unable to create directory: ".SITEPATH.$_POST["site_uploads"];
					$Show="1";
					}
				}
			}
			if(!isset($error)) { ## write to file
				if($fp=@fopen(BASEPATH.'config.inc.php','w')) {
				@fwrite($fp,"<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');\n");
				@fwrite($fp,"\$CONFIG['site']['url']='".$_POST["site_url"]."';\n");
				@fwrite($fp,"\$CONFIG['site']['site_path']='".SITEPATH."';\n");
				@fwrite($fp,"\$CONFIG['site']['site_path_nice']='".SITEPATHNICE."';\n");
				@fwrite($fp,"\$CONFIG['site']['user_uploads']='".$_POST["site_uploads"]."';\n");
				@fwrite($fp,"\$CONFIG['site']['name']='".$_POST["site_name"]."';\n");
				@fwrite($fp,"\$CONFIG['session']['name']='".$_POST["session_name"]."';\n");
				@fwrite($fp,"\$CONFIG['db']['default']['hostname'] = '".$_POST['db_hostname']."';\n");
				@fwrite($fp,"\$CONFIG['db']['default']['username'] = '".$_POST['db_username']."';\n");
				@fwrite($fp,"\$CONFIG['db']['default']['password'] = '".$_POST['db_password']."';\n");
				@fwrite($fp,"\$CONFIG['db']['default']['database'] = '".$_POST['db_database']."';\n");
				@fwrite($fp,"\$CONFIG['db']['default']['dbdriver'] = '".$_POST['db_drive']."';\n");
				@fwrite($fp,"\$CONFIG['db']['default']['dbprefix'] = '';\n");
				@fwrite($fp,"\$CONFIG['db']['default']['active_r'] = TRUE;\n");
				@fwrite($fp,"\$CONFIG['db']['default']['pconnect'] = TRUE;\n");
				@fwrite($fp,"\$CONFIG['db']['default']['db_debug'] = TRUE;\n");
				@fwrite($fp,"\$CONFIG['db']['default']['cache_on'] = FALSE;\n");
				@fwrite($fp,"\$CONFIG['db']['default']['cachedir'] = '';\n");
				@fwrite($fp,"?>");
				@fclose($fp);
					## create tables, if needed
					$error=CheckMainTables($_POST["db_database"]);
					if(!$error) {
						## create admin account
						$FD=array(
						"usertype_id" => "1",
						"u_name" => PrepareData("admin_name"),
						"u_email" => PrepareData("admin_email"),
						"u_status" => "1",
						"u_pass" => PrepareData("admin_password"),
						"date_created{insert}" => date("Y-m-d H:i:s")
						);
						if(!mysql_query(MySQLInsert($FD,"CMS_users"))) {
						$error="Unable to add admin user to the database";
						}
					}
					else {
					$error="Unable to create tables: ".$error;
					}
				}
				else {
				$error="Unable to create file: ".BASEPATH."config.inc.php";
				}
			}
			if(!isset($error)) { ## show login
			$message="Welcome, please login.";
			include(FCPATH.'errors/error_login.php');
			die;
			}
		}
	}
}
?>