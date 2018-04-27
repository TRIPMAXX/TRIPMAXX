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
			$find_tour = tools::find("first", TM_TOURS." as t, ".TM_COUNTRIES." as co, ".TM_STATES." as s, ".TM_CITIES." as ci, ".TM_ATTRIBUTES." as a", 't.*, co.name as co_name, s.name as s_name, ci.name as ci_name, a.attribute_name as attribute_name', "WHERE t.country=co.id AND t.state=s.id AND t.city=ci.id AND t.tour_type=a.id AND t.id=:id ", array(":id"=>$server_data['data']['tour_id']));
			$return_data['find_tour']=$find_tour;
		endif;
		if(isset($server_data['data']) && isset($server_data['data']['offer_id']) && $server_data['data']['offer_id']!=""):
			$find_offer = tools::find("first", TM_OFFERS, '*', "WHERE id=:id AND tour_id=:tour_id ", array(":id"=>$server_data['data']['offer_id'], ":tour_id"=>$server_data['data']['tour_id']));
			$return_data['find_offer']=$find_offer;
		endif;
		$return_data['status']="success";
		$return_data['msg']="Data received successfully.";
	endif;
	echo json_encode($return_data);	
?>