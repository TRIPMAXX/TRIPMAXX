<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		$supplier_list = tools::find("all", TM_SUPPLIER, '*', "WHERE supplier_priority >:supplier_priority ORDER BY supplier_priority ASC LIMIT 0,1", array(":supplier_priority"=>0));
		if(isset($supplier_list[0]) && !empty($supplier_list[0]))
		{
			$autentication_data_booking=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.BOOKING_API_PATH."authorized.php"));
			if(isset($autentication_data_booking->status)):
				if($autentication_data_booking->status=="success"):
					$post_data_booking['token']=array(
						"token"=>$autentication_data_booking->results->token,
						"token_timeout"=>$autentication_data_booking->results->token_timeout,
						"token_generation_time"=>$autentication_data_booking->results->token_generation_time
					);
					$post_data_booking['data']['booking_id']=$server_data['data']['booking_id'];
					$post_data_booking['data']['supplier_id']=$supplier_list[0]['id'];
					$post_data_str_booking=json_encode($post_data_booking);
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_HEADER, false);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
					curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.BOOKING_API_PATH."booking/update-booking-supplier.php");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_booking);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					$return_data_booking = curl_exec($ch);
					curl_close($ch);
					//print_r($return_data_booking);
					$return_data_arr_booking=json_decode($return_data_booking, true);
					if(!isset($return_data_arr_booking['status'])):
						//$data['status'] = 'error';
						//$data['msg']="Some error has been occure during execution.";
					elseif($return_data_arr_booking['status']=="success"):
						//$data['status'] = 'success';
						//$data['msg']="Data received successfully";
					else:
						//$data['status'] = 'error';
						//$data['msg'] = $return_data_arr_tour['msg'];
					endif;
				endif;
			else:
				//$data['status'] = 'error';
				//$data['msg'] = $autentication_data_tour->msg;
			endif;
		}
		$return_data['status']="success";
		$return_data['results']=$supplier_list;
		$return_data['msg']="Data received successfully.";
	endif;
	echo json_encode($return_data);	
?>