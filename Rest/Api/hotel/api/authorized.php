<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../init.php');
	$return_data['status']="error";
	$return_data['msg']="Autentication failed.";
	if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']) && $_SERVER['PHP_AUTH_USER']==API_USERNAME && $_SERVER['PHP_AUTH_PW']==API_PASSWORD)
	{
		$data_array['token'] = TOKEN;
		$data_array['token_timeout'] = TOKEN_TIMEOUT;
		$data_array['token_generation_time'] = time();
		$return_data['status']="success";
		$return_data['msg']="Data received successfully.";
		$return_data['results']=$data_array;
	}
	echo json_encode($return_data);	
?>