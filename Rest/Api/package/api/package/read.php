<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		if(isset($server_data['data']) && isset($server_data['data']['package_id']) && $server_data['data']['package_id']!=""):
			$package_list = tools::find("first", TM_PACKAGES." as p, ".TM_COUNTRIES." as co", 'p.*, co.name as co_name', "WHERE p.country=co.id AND p.id=:id ", array(":id"=>base64_decode($server_data['data']['package_id'])));
		elseif(isset($server_data['data']) && isset($server_data['data']['status']) && $server_data['data']['status']!=""):
			$package_list = tools::find("all", TM_PACKAGES, '*', "WHERE status=:status", array(":status"=>1));
		else:
			$package_list = tools::find("all", TM_PACKAGES." as p, ".TM_COUNTRIES." as co", 'p.*, co.name as co_name', "WHERE p.country=co.id AND :all ", array(":all"=>1));
		endif;
		$return_data['status']="success";
		$return_data['results']=$package_list;
		$return_data['msg']="Data received successfully.";
	endif;
	echo json_encode($return_data);	
?>