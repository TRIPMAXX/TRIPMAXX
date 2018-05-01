<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		$booking_destination_ids = tools::find("first", TM_BOOKING_DESTINATION, 'GROUP_CONCAT(id) as booking_ids', "WHERE id=:id", array(":id"=>$server_data['data']['booking_id']));
		if($save_booking = tools::update(TM_BOOKING_HOTEL_DETAILS, "status=:status", "WHERE booking_destination_id IN (".$booking_destination_ids['booking_ids'].") AND hotel_id=:hotel_id", array(":hotel_id"=>$server_data['data']['hotel_id'], ":status"=>$server_data['data']['status']))):
			$return_data['status']="success";
			$return_data['msg']="Hotel data updated successfully.";
		else:
			$return_data['status']="error";
			$return_data['msg']="We are having some problem. Please try later.";
		endif;
	endif;
	echo json_encode($return_data);	
?>