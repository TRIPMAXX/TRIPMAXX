<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		$hotel_list=array();
		if(isset($server_data['data']) && isset($server_data['data']['city_id']) && $server_data['data']['city_id']!=""):
			$execute[':city']=$server_data['data']['city_id'];
			$execute[':status']=1;
			$hotel_type_where="";
			if(isset($server_data['data']['hotel_type']) && $server_data['data']['hotel_type']!=""):
				$hotel_type_where=" AND CONCAT(',', h.hotel_type, ',') LIKE :hotel_type ";
				$execute[':hotel_type']="%,".$server_data['data']['hotel_type'].",%";
			endif;
			$hotel_list = tools::find("all", TM_HOTELS." as h, ".TM_COUNTRIES." as co, ".TM_STATES." as s, ".TM_CITIES." as ci", 'h.*, co.name as co_name, s.name as s_name, ci.name as ci_name', "WHERE h.country=co.id AND h.state=s.id AND h.city=ci.id AND h.city=:city AND h.status=:status".$hotel_type_where, $execute);			
		endif;
		$return_data['status']="success";
		$return_data['results']=$hotel_list;
		$return_data['msg']="Data received successfully.";
	endif;
	echo json_encode($return_data);	
?>