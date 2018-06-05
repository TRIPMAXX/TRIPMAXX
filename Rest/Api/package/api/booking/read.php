<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		if(isset($server_data['data']) && isset($server_data['data']['booking_type']) && $server_data['data']['booking_type']=="R"):
			$where_coulse= "WHERE ";
			$exicute=array();
			if(isset($server_data['data']['date_from']) && $server_data['data']['date_from']!="" && isset($server_data['data']['date_to']) && $server_data['data']['date_to']!=""):
				$date_from_obj=date_create_from_format("d/m/Y",$server_data['data']['date_from']);
				$date_from=date_format($date_from_obj,"Y-m-d");
				$date_to_obj=date_create_from_format("d/m/Y", $server_data['data']['date_to']);
				$date_to=date_format($date_to_obj,"Y-m-d");
				$where_coulse.= "booking_date >= '".$date_from."' AND booking_date <= '".$date_to."' ";
			endif;
			if(isset($server_data['data']['booking_status']) && $server_data['data']['booking_status']!="A"):
				$where_coulse.= " AND status=:status ";
				$exicute[":status"]=$server_data['data']['booking_status'];
			endif;
			
			$booking_list = tools::find("all", TM_BOOKINGS, '*', $where_coulse, $exicute);
			if(isset($booking_list) !empty($booking_list)):
				foreach($booking_list as $booking_list_key => $booking_list_val):
					$package_list = tools::find("first", TM_PACKAGES." as p, ".TM_COUNTRIES." as co", 'p.*, co.name as co_name', "WHERE p.country=co.id AND p.id=:id ", array(":id"=>$booking_list_val['package_id']));
					$booking_list[$booking_list_key]['package_list']=$package_list;
				endforeach;
			endif;
		endif;

		if(isset($server_data['data']) && isset($server_data['data']['package_id']) && $server_data['data']['package_id']!=""):
			if(isset($server_data['data']) && isset($server_data['data']['booking_id']) && $server_data['data']['booking_id']!=""):
				$booking_list = tools::find("first", TM_BOOKINGS, '*', "WHERE id=:id AND package_id=:package_id ", array(":id"=>base64_decode($server_data['data']['booking_id']), ":package_id"=>base64_decode($server_data['data']['package_id'])));
			else:
				$booking_list = tools::find("all", TM_BOOKINGS, '*', "WHERE package_id=:package_id ", array(":package_id"=>base64_decode($server_data['data']['package_id'])));
			endif;
			$return_data['status']="success";
			$return_data['results']=$booking_list;
			$return_data['msg']="Data received successfully.";
		else:
			$return_data['status']="error";
			$return_data['msg']="Package id missing.";
		endif;
	endif;
	echo json_encode($return_data);	
?>