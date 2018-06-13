<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		$home_sliders = tools::find("all", TM_HOME_SLIDER, '*', "WHERE status=:status ", array(":status"=>1));
		$return_data['status']="success";
		$return_data['results']=$home_sliders;
		$return_data['msg']="Data received successfully.";
		//print_r($server_data);exit;
	endif;
	echo json_encode($return_data);	
?>