<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		if(isset($server_data['data']) && isset($server_data['data']['agent_id']) && $server_data['data']['agent_id']!=""):
			$booking_total_active = tools::find("first", TM_BOOKING_MASTERS, 'COUNT(*) as total_active', "WHERE agent_id=:agent_id AND status=:status AND is_deleted = :is_deleted ", array(":agent_id"=>$server_data['data']['agent_id'], ":is_deleted"=>"N", ':status'=>0));
			$booking_total_completed = tools::find("first", TM_BOOKING_MASTERS, 'COUNT(*) as total_completed', "WHERE agent_id=:agent_id AND status=:status AND is_deleted = :is_deleted ", array(":agent_id"=>$server_data['data']['agent_id'], ":is_deleted"=>"N", ':status'=>1));
			$booking_total_cancelled = tools::find("first", TM_BOOKING_MASTERS, 'COUNT(*) as total_cancelled', "WHERE agent_id=:agent_id AND status=:status AND is_deleted = :is_deleted ", array(":agent_id"=>$server_data['data']['agent_id'], ":is_deleted"=>"N", ':status'=>2));
			$booking_total_amount = tools::find("first", TM_BOOKING_MASTERS, 'SUM(total_amount) as booking_total_amount', "WHERE agent_id=:agent_id AND status=:status AND is_deleted = :is_deleted ", array(":agent_id"=>$server_data['data']['agent_id'], ":is_deleted"=>"N", ':status'=>1));
			$return_data['status']="success";
			$return_data['results']['total_active']=$booking_total_active['total_active'];
			$return_data['results']['total_completed']=$booking_total_completed['total_completed'];
			$return_data['results']['total_cancelled']=$booking_total_cancelled['total_cancelled'];
			$return_data['results']['total_amount']=$booking_total_amount['booking_total_amount'];
			$return_data['msg']="Data received successfully.";
		else:			
			$return_data['status']="error";
			$return_data['msg']="Some data missing.";
		endif;
	endif;
	echo json_encode($return_data);	
?>