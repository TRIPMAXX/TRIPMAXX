<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		$supplier_list = tools::find("all", TM_SUPPLIER, '*', "WHERE supplier_priority >:supplier_priority ORDER BY supplier_priority ASC LIMIT 0,1", array(":supplier_priority"=>0));
		$return_data['status']="success";
		$return_data['results']=$supplier_list;
		$return_data['msg']="Data received successfully.";
	endif;
	echo json_encode($return_data);	
?>