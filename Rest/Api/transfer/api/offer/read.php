<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		if(isset($server_data['data']) && isset($server_data['data']['transfer_id']) && $server_data['data']['transfer_id']!=""):
			if(isset($server_data['data']) && isset($server_data['data']['offer_id']) && $server_data['data']['offer_id']!=""):
				$offer_list = tools::find("first", TM_OFFERS, '*', "WHERE id=:id AND transfer_id=:transfer_id ", array(":id"=>base64_decode($server_data['data']['offer_id']), ":transfer_id"=>base64_decode($server_data['data']['transfer_id'])));
			else:
				$offer_list = tools::find("all", TM_OFFERS, '*', "WHERE transfer_id=:transfer_id ", array(":transfer_id"=>base64_decode($server_data['data']['transfer_id'])));
			endif;
			$return_data['status']="success";
			$return_data['results']=$offer_list;
			$return_data['msg']="Data received successfully.";
		else:
			$return_data['status']="error";
			$return_data['msg']="Transfer id missing.";
		endif;
	endif;
	echo json_encode($return_data);	
?>