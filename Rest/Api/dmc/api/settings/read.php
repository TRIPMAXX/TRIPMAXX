<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		if(isset($server_data['data']) && isset($server_data['data']['setting_id']) && $server_data['data']['setting_id']!=""):
			if(isset($server_data['data']) && isset($server_data['data']['setting_id']) && $server_data['data']['setting_id']!=""):
				$setting_data = tools::find("first", TM_SETTINGS, '*', "WHERE id=:id ", array(":id"=>$server_data['data']['setting_id']));
			else:
				$setting_data = tools::find("first", TM_SETTINGS, '*', "WHERE id=:id ", array(":id"=>1));
			endif;
			$return_data['status']="success";
			$return_data['results']=$setting_data;
			$return_data['msg']="Data received successfully.";
		else:
			$return_data['status']="error";
			$return_data['msg']="Setting id missing.";
		endif;
	endif;
	echo json_encode($return_data);	
?>