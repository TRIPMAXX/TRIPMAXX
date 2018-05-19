<?php
// include database and object files
include_once('core/configurations/config.php');
include_once('core/configurations/dbconnection.php');
include_once('core/configurations/database_tables.php');
require_once('vendor/autoload.php');
include_once('core/configurations/tools.php');
$db_connection = new dbconnection();
$link = $db_connection->db_connect();
if(!$link){
	exit;
}
$global_hotel_type_arr=array("1"=>"Family", "2"=>"Honeymoon", "3"=>"Joiner");
?>