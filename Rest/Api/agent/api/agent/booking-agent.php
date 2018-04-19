<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		if(isset($server_data['data']) && isset($server_data['data']['agent_id']) && $server_data['data']['agent_id']!=""):
			$find_agent = tools::find("first", TM_AGENT, '*', "WHERE id=:id", array(":id"=>$server_data['data']['agent_id']));
			$return_data['status']="success";
			$return_data['results']=$find_agent;
			$return_data['msg']="Data received successfully.";
		else:
			$return_data['status']="error";
			$return_data['msg']="No agent found.";
		endif;
	endif;
	echo json_encode($return_data);	
?>