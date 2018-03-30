<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		if(isset($server_data['data']) && isset($server_data['data']['state_id']) && $server_data['data']['state_id']!=""):
			$state_list = tools::find("first", TM_STATES, '*', "WHERE id=:id ", array(":id"=>base64_decode($server_data['data']['state_id'])));
		elseif(isset($server_data['data']) && isset($server_data['data']['country_id']) && $server_data['data']['country_id']!=""):
			$state_list = tools::find("all", TM_STATES, '*', "WHERE country_id=:country_id ORDER BY name ASC ", array(":country_id"=>$server_data['data']['country_id']));
		else:
			$state_list = tools::find("all", TM_STATES, '*', "WHERE :all ORDER BY name ASC ", array(":all"=>1));
		endif;		
		$return_data['status']="success";
		$return_data['msg']="Data received successfully.";
		$return_data['results']=$state_list;
	endif;
	echo json_encode($return_data);	
?>