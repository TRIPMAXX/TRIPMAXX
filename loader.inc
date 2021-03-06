<?php
session_start();
include_once('core/configurations/config.php');
include_once('core/configurations/dbconnection.php');
include_once('core/configurations/database_tables.php');
include_once('core/configurations/tools.php');
include_once('core/microservices/front_control.php');
$db_connection = new dbconnection();
$link = $db_connection->db_connect();
if(!$link){
	exit;
}
$current_page_name = tools::current_page();
$global_hotel_type_arr=array("1"=>"Family", "2"=>"Honeymoon", "3"=>"Joiner");

$general_setting=array();
$autentication_data_dmc=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.DMC_API_PATH."authorized.php"));
if(isset($autentication_data_dmc->status)):
	if($autentication_data_dmc->status=="success"):
		$post_data_dmc['token']=array(
			"token"=>$autentication_data_dmc->results->token,
			"token_timeout"=>$autentication_data_dmc->results->token_timeout,
			"token_generation_time"=>$autentication_data_dmc->results->token_generation_time
		);
		$post_data_dmc['data']['setting_id']=1;
		$post_dmc_data_str=json_encode($post_data_dmc);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.DMC_API_PATH."settings/read.php");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_dmc_data_str);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$return_data_dmc = curl_exec($ch);
		curl_close($ch);
		$return_dmc_data_arr=json_decode($return_data_dmc, true);
		if(!isset($return_dmc_data_arr['status'])):
			//$data['status'] = 'error';
			//$data['msg']="Some error has been occure during execution.";
		elseif($return_dmc_data_arr['status']=="success"):
			//$data['status'] = 'success';
			//$data['msg']="Data received successfully";
			$general_setting=$return_dmc_data_arr['results'];
		else:
			//$data['status'] = 'error';
			//$data['msg'] = $return_dmc_data_arr['msg'];
		endif;
	endif;
else:
	//$data['status'] = 'error';
	//$data['msg'] = $autentication_data_dmc->msg;
endif;
?>