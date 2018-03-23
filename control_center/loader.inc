<?php
session_start();
include_once('core/configurations/config.php');
include_once('core/configurations/dbconnection.php');
include_once('core/configurations/database_tables.php');
include_once('core/configurations/tools.php');
include_once('core/microservices/control_center.php');
$db_connection = new dbconnection();
$link = $db_connection->db_connect();
if(!$link){
	exit;
}
$current_page_name = tools::current_page();
$general_settings = tools::find("first", TM_SETTINGS, $value='*', "WHERE id = 1", array());
?>