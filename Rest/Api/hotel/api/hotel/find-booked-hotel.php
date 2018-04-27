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
			$find_hotel = tools::find("first", TM_HOTELS." as h, ".TM_COUNTRIES." as co, ".TM_STATES." as s, ".TM_CITIES." as ci", 'h.*, co.name as co_name, s.name as s_name, ci.name as ci_name', "WHERE h.country=co.id AND h.state=s.id AND h.city=ci.id AND h.id=:id ", array(":id"=>$server_data['data']['hotel_id']));
			$return_data['find_hotel']=$find_hotel;
		endif;
		if(isset($server_data['data']) && isset($server_data['data']['room_id']) && $server_data['data']['room_id']!=""):
			$find_room = tools::find("first", TM_ROOMS, '*', "WHERE id=:id AND hotel_id=:hotel_id ", array(":id"=>$server_data['data']['room_id'], ":hotel_id"=>$server_data['data']['hotel_id']));
			$return_data['find_room']=$find_room;
		endif;
		$return_data['status']="success";
		$return_data['msg']="Data fetch successfully.";
	endif;
	echo json_encode($return_data);	
?>