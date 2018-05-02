<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		if(isset($server_data['data']) && isset($server_data['data']['supplier_id']) && $server_data['data']['supplier_id']!=""):
			$supplier_list = tools::find("first", TM_SUPPLIER, '*', "WHERE id=:id ", array(":id"=>base64_decode($server_data['data']['supplier_id'])));
		elseif(isset($server_data['data']) && isset($server_data['data']['not_in_supplier_ids_str'])):
			$supplier_list = tools::find("all", TM_SUPPLIER, '*', "WHERE id NOT IN (".$server_data['data']['not_in_supplier_ids_str'].") AND status=:status ORDER BY supplier_priority ASC ", array(":status"=>1));
		else:
			$supplier_list = tools::find("all", TM_SUPPLIER, '*', "WHERE :all ", array(":all"=>1));
		endif;
		$return_data['status']="success";
		$return_data['results']=$supplier_list;
		$return_data['msg']="Data received successfully.";
	endif;
	echo json_encode($return_data);	
?>