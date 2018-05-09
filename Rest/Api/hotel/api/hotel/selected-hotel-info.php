<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		if(isset($server_data['data']) && isset($server_data['data']['step_1']) && !empty($server_data['data']['step_1']) && isset($server_data['data']['step_2']) && !empty($server_data['data']['step_2']) && isset($server_data['data']['saved_booking_destination'])):
			$total_hotel_price=0.00;
			$add_day=0;
			foreach($server_data['data']['step_2'] as $room_key=>$room_val):
				$explode_room_data=explode("-", $room_val);
				$room_id=end($explode_room_data);
				$room_details = tools::find("first", TM_ROOMS, '*', "WHERE id=:id ", array(":id"=>$room_id));
				$hotel_details = tools::find("first", TM_HOTELS, '*', "WHERE id=:id ", array(":id"=>$room_details['hotel_id']));
				$markup_percentage=0.00;
				if(isset($server_data['data']['booking_type']) && $server_data['data']['booking_type']=="agent" && isset($server_data['data']['agent_name']) && $server_data['data']['agent_name']!=""):
					$autentication_data_agent=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."authorized.php"));
					if(isset($autentication_data_agent->status)):
						if($autentication_data_agent->status=="success"):
							$post_data_agent['token']=array(
								"token"=>$autentication_data_agent->results->token,
								"token_timeout"=>$autentication_data_agent->results->token_timeout,
								"token_generation_time"=>$autentication_data_agent->results->token_generation_time
							);
							$post_data_agent['data']['agent_id']=$server_data['data']['agent_name'];
							$post_data_agent_str=json_encode($post_data_agent);
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
							curl_setopt($ch, CURLOPT_HEADER, false);
							curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
							curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
							curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."agent/booking-agent.php");
							curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_agent_str);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
							$return_data_agent = curl_exec($ch);
							curl_close($ch);
							$return_data_agent_arr=json_decode($return_data_agent, true);
							if($return_data_agent_arr['status']=="success"):
								$markup_percentage=$return_data_agent_arr['results']['hotel_price'];
							endif;
						endif;
					endif;
				endif;
				if(isset($server_data['data']['step_1']['booking_type']) && $server_data['data']['step_1']['booking_type']=="agent" && isset($server_data['data']['step_1']['agent_name']) && $server_data['data']['step_1']['agent_name']!=""):
					$room_agent_markup = tools::find("first", TM_ROOM_AGENT_MARKUP, '*', "WHERE room_id=:room_id AND agent_id=:agent_id ", array(":agent_id"=>$server_data['data']['step_1']['agent_name'], ":room_id"=>$room_details['id']));
					if(!empty($room_agent_markup)):
						$markup_percentage=$room_agent_markup['markup_price'];
					endif;
				endif;
				$checkin_date_obj=date_create_from_format("d/m/Y", $server_data['data']['step_1']['checkin']);
				$checkin_date=date_format($checkin_date_obj, "Y-m-d");
				$checkin_date_on_city=date("Y-m-d", strtotime($checkin_date)+(24*60*60*$add_day));
				$add_day=$add_day+$server_data['data']['step_1']['number_of_night'][$room_key];
				$checkout_date_on_city=date("Y-m-d", strtotime($checkin_date_on_city)+(24*60*60*$server_data['data']['step_1']['number_of_night'][$room_key]));
				$total_price=0.00;
				for($i=strtotime($checkin_date_on_city);$i<strtotime($checkout_date_on_city);):
					$complete_date=date("Y-m-d", $i);
					$room_price_list = tools::find("first", TM_ROOM_PRICES, '*', "WHERE room_id=:room_id AND start_date<=:start_date AND end_date>=:end_date AND status=:status", array(":room_id"=>$room_details['id'], ":start_date"=>$complete_date, ":end_date"=>$complete_date, ':status'=>1));
					if(!empty($room_price_list)):
						$room_day_price=$room_price_list['price_per_night'];
					else:
						$room_day_price=$room_details['price'];
					endif;
					$total_price=$total_price+$room_day_price;
					$i=$i+(24*60*60);
				endfor;
				$agent_commision=($total_price * $markup_percentage)/100;
				$total_hotel_price=$total_hotel_price+$total_price+$agent_commision;
				if($server_data['data']['country_key']==$room_key):
					$result['hotel_id']=$hotel_details['id'];
					$result['room_id']=$room_details['id'];
					$result['price']=$total_price;
					$result['booking_start_date']=$checkin_date_on_city;
					$result['booking_end_date']=$checkout_date_on_city;
					$result['agent_markup_percentage']=$markup_percentage;
					$result['avalibility_status']=$explode_room_data[1];
				endif;
			endforeach;
			$default_currency=tools::find("first", TM_SETTINGS." as s, ".TM_CURRENCIES." as c", 'c.*', "WHERE c.id=s.default_currency ", array());
			$return_data['status']="success";
			$return_data['msg']="Date fetched successfully.";
			$return_data['results']=$result;
			$return_data['default_currency']=$default_currency['id'];
		else:
			$return_data['status']="error";
			$return_data['msg']="Some data missing.";
		endif;
	endif;
	echo json_encode($return_data);	
?>