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
			$find_transfer = tools::find("first", TM_TRANSFER." as t, ".TM_COUNTRIES." as co, ".TM_STATES." as s, ".TM_CITIES." as ci", 't.*, co.name as co_name, s.name as s_name, ci.name as ci_name', "WHERE t.country=co.id AND t.state=s.id AND t.city=ci.id AND t.id=:id ", array(":id"=>$server_data['data']['transfer_id']));
			$allow_pickup_type=$allow_dropoff_type="";
			if($find_transfer['allow_pickup_type']!=""):
				$transfer_attributes_pickup = tools::find("first", TM_ATTRIBUTES, "GROUP_CONCAT(attribute_name SEPARATOR ', ') as allow_pickup_type", "WHERE id IN (".$find_transfer['allow_pickup_type'].") ", array());
				$allow_pickup_type=$transfer_attributes_pickup['allow_pickup_type'];
			endif;
			if($find_transfer['allow_dropoff_type']!=""):
				$transfer_attributes_dropoff = tools::find("first", TM_ATTRIBUTES, "GROUP_CONCAT(attribute_name SEPARATOR ', ') as allow_dropoff_type", "WHERE id IN (".$find_transfer['allow_dropoff_type'].") ", array());
				$allow_dropoff_type=$transfer_attributes_dropoff['allow_dropoff_type'];
			endif;
			$return_data['find_transfer']=$find_transfer;
			$return_data['find_transfer']['allow_pickup_type']=$allow_pickup_type;
			$return_data['find_transfer']['allow_dropoff_type']=$allow_dropoff_type;
		endif;
		if(isset($server_data['data']) && isset($server_data['data']['offer_id']) && $server_data['data']['offer_id']!=""):
			$find_offer = tools::find("first", TM_OFFERS, '*', "WHERE id=:id AND transfer_id=:transfer_id ", array(":id"=>$server_data['data']['offer_id'], ":transfer_id"=>$server_data['data']['transfer_id']));
			$return_data['find_offer']=$find_offer;
		endif;
		$return_data['status']="success";
		$return_data['msg']="Data received successfully.";
	endif;
	echo json_encode($return_data);	
?>