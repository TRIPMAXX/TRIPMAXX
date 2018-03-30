<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		if(isset($server_data['data']) && isset($server_data['data']['hotel_id']) && $server_data['data']['hotel_id']!=""):
			if(isset($server_data['data']) && isset($server_data['data']['room_id']) && $server_data['data']['room_id']!=""):
				$room_list = tools::find("first", TM_ROOMS, '*', "WHERE id=:id AND hotel_id=:hotel_id ", array(":id"=>base64_decode($server_data['data']['room_id']), ":hotel_id"=>base64_decode($server_data['data']['hotel_id'])));
			else:
				$room_list = tools::find("all", TM_ROOMS, '*', "WHERE hotel_id=:hotel_id ", array(":hotel_id"=>base64_decode($server_data['data']['hotel_id'])));
			endif;
			$return_data['status']="success";
			$return_data['results']=$room_list;
			$return_data['msg']="Data received successfully.";
		else:
			$return_data['status']="error";
			$return_data['msg']="Hotel id missing.";
		endif;
	endif;
	echo json_encode($return_data);	
?>