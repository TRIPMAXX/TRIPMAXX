<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		if(isset($server_data['data']) && isset($server_data['data']['offer_id']) && $server_data['data']['offer_id']!=""):
			if(isset($server_data['data']) && isset($server_data['data']['offer_price_id']) && $server_data['data']['offer_price_id']!=""):
				$offer_price_list = tools::find("first", TM_OFFER_PRICES, '*', "WHERE id=:id AND offer_id=:offer_id ", array(":id"=>base64_decode($server_data['data']['offer_price_id']), ":offer_id"=>base64_decode($server_data['data']['offer_id'])));
			else:
				$offer_price_list = tools::find("all", TM_OFFER_PRICES, '*', "WHERE offer_id=:offer_id ", array(":offer_id"=>base64_decode($server_data['data']['offer_id'])));
			endif;
			$return_data['status']="success";
			$return_data['results']=$offer_price_list;
			$return_data['msg']="Data received successfully.";
		else:
			$return_data['status']="error";
			$return_data['msg']="Tour offer id missing.";
		endif;
	endif;
	echo json_encode($return_data);	
?>