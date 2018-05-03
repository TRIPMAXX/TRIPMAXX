<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		$_POST=$server_data['data'];
		if($save_booking = tools::module_form_submission("", TM_BOOKING_MASTERS)):
			$return_data['status']="success";
			$return_data['msg']="Booking data updated successfully.";
		else:
			$return_data['status']="error";
			$return_data['msg']="We are having some problem. Please try later.";
		endif;
	endif;
	echo json_encode($return_data);	
?>