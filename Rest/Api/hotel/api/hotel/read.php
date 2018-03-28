<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && $server_data['token']['token']==TOKEN && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		if(isset($server_data['data']) && isset($server_data['data']['hotel_id']) && $server_data['data']['hotel_id']!=""):
			$hotel_list = tools::find("first", TM_HOTELS." as h, ".TM_COUNTRIES." as co, ".TM_STATES." as s, ".TM_CITIES." as ci", 'h.*, co.name as co_name, s.name as s_name, ci.name as ci_name', "WHERE h.country=co.id AND h.state=s.id AND h.city=ci.id AND h.id=:id ", array(":id"=>base64_decode($server_data['data']['hotel_id'])));
		elseif(isset($server_data['data']) && isset($server_data['data']['status']) && $server_data['data']['status']!=""):
			$hotel_list = tools::find("all", TM_HOTELS, '*', "WHERE status=:status ORDER BY serial_number", array(":status"=>1));
		else:
			$hotel_list = tools::find("all", TM_HOTELS." as h, ".TM_COUNTRIES." as co, ".TM_STATES." as s, ".TM_CITIES." as ci", 'h.*, co.name as co_name, s.name as s_name, ci.name as ci_name', "WHERE h.country=co.id AND h.state=s.id AND h.city=ci.id AND :all ", array(":all"=>1));
		endif;
		$return_data['status']="success";
		$return_data['results']=$hotel_list;
		$return_data['msg']="Data received successfully.";
	endif;
	echo json_encode($return_data);	
?>