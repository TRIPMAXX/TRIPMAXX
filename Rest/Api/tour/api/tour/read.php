<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		if(isset($server_data['data']) && isset($server_data['data']['tour_id']) && $server_data['data']['tour_id']!=""):
			$tour_list = tools::find("first", TM_TOURS." as t, ".TM_COUNTRIES." as co, ".TM_STATES." as s, ".TM_CITIES." as ci, ".TM_ATTRIBUTES." as a", 't.*, co.name as co_name, s.name as s_name, ci.name as ci_name, a.attribute_name as attribute_name', "WHERE t.country=co.id AND t.state=s.id AND t.city=ci.id AND t.tour_type=a.id AND t.id=:id ", array(":id"=>base64_decode($server_data['data']['tour_id'])));
		elseif(isset($server_data['data']) && isset($server_data['data']['status']) && $server_data['data']['status']!=""):
			$tour_list = tools::find("all", TM_TOURS, '*', "WHERE status=:status ORDER BY serial_number", array(":status"=>1));
		else:
			$tour_list = tools::find("all", TM_TOURS." as t, ".TM_COUNTRIES." as co, ".TM_STATES." as s, ".TM_CITIES." as ci, ".TM_ATTRIBUTES." as a", 't.*, co.name as co_name, s.name as s_name, ci.name as ci_name, a.attribute_name as attribute_name', "WHERE t.country=co.id AND t.state=s.id AND t.city=ci.id AND t.tour_type=a.id AND :all ", array(":all"=>1));
		endif;
		$return_data['status']="success";
		$return_data['results']=$tour_list;
		$return_data['msg']="Data received successfully.";
	endif;
	echo json_encode($return_data);	
?>