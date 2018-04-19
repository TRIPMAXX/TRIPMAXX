<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		if(isset($server_data['data']) && isset($server_data['data']['hotel_id']) && $server_data['data']['hotel_id']!="" && isset($server_data['data']['room_id']) && $server_data['data']['room_id']!="" && isset($server_data['data']['booking_start_date']) && $server_data['data']['booking_start_date']!="" && isset($server_data['data']['booking_end_date']) && $server_data['data']['booking_end_date']!=""):
			$booking_count= tools::find("first", TM_BOOKING_HOTEL_DETAILS, 'COUNT(id) as count_id', "WHERE hotel_id=:hotel_id AND room_id=:room_id AND (booking_start_date>=:booking_start_date AND booking_end_date<=:booking_end_date) OR (:booking_start_date_1 BETWEEN booking_start_date AND booking_end_date OR :booking_end_date_1 BETWEEN booking_start_date AND booking_end_date)", array(":hotel_id"=>$server_data['data']['hotel_id'], ":room_id"=>$server_data['data']['room_id'], ":booking_start_date"=>$server_data['data']['booking_start_date'], ":booking_end_date"=>$server_data['data']['booking_end_date'], ":booking_start_date_1"=>$server_data['data']['booking_start_date'], ":booking_end_date_1"=>$server_data['data']['booking_end_date']));
			$return_data['status']="success";
			$return_data['results']=$booking_count;
			$return_data['msg']="Data received successfully.";
		else:
			$return_data['status']="error";
			$return_data['msg']="Some data missing.";
		endif;
	endif;
	echo json_encode($return_data);	
?>