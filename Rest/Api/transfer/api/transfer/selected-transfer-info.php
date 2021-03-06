<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		if(isset($server_data['data']) && isset($server_data['data']['step_1']) && !empty($server_data['data']['step_1']) && isset($server_data['data']['step_4']) && !empty($server_data['data']['step_4'])):
			$add_day=0;
			$total_transfer_price=0.00;
			$city_index=-1;
			$prev_city_id="";
			$result=array();
			foreach($server_data['data']['step_4'] as $offer_key=>$offer_val):
				$explode_offer_data=explode("-", $offer_val);
				$offer_id=end($explode_offer_data);
				$offer_details = tools::find("first", TM_OFFERS, '*', "WHERE id=:id ", array(":id"=>$offer_id));
				$transfer_details = tools::find("first", TM_TRANSFER, '*', "WHERE id=:id ", array(":id"=>$offer_details['transfer_id']));
				$allow_pickup_type=$allow_dropoff_type="";
				if($transfer_details['allow_pickup_type']!=""):
					$transfer_attributes_pickup = tools::find("first", TM_ATTRIBUTES, "GROUP_CONCAT(attribute_name SEPARATOR ', ') as allow_pickup_type", "WHERE id IN (".$transfer_details['allow_pickup_type'].") ", array());
					$allow_pickup_type=$transfer_attributes_pickup['allow_pickup_type'];
				endif;
				if($transfer_details['allow_dropoff_type']!=""):
					$transfer_attributes_dropoff = tools::find("first", TM_ATTRIBUTES, "GROUP_CONCAT(attribute_name SEPARATOR ', ') as allow_dropoff_type", "WHERE id IN (".$transfer_details['allow_dropoff_type'].") ", array());
					$allow_dropoff_type=$transfer_attributes_dropoff['allow_dropoff_type'];
				endif;
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
				$nationality_addon_percentage=0;
				if(isset($server_data['data']['step_1']['booking_type']) && $server_data['data']['step_1']['booking_type']=="agent" && isset($server_data['data']['step_1']['agent_name']) && $server_data['data']['step_1']['agent_name']!=""):
					$offer_agent_markup = tools::find("first", TM_OFFER_AGENT_MARKUP, '*', "WHERE offer_id=:offer_id AND agent_id=:agent_id ", array(":agent_id"=>$server_data['data']['step_1']['agent_name'], ":offer_id"=>$offer_details['id']));
					if(!empty($offer_agent_markup)):
						$markup_percentage=$offer_agent_markup['markup_price'];
					endif;
				endif;
				if($prev_city_id=="" || $prev_city_id!=$explode_offer_data[0]):
					$city_index++;
					$checkin_date_obj=date_create_from_format("d/m/Y", $server_data['data']['step_1']['checkin']);
					$checkin_date=date_format($checkin_date_obj, "Y-m-d");
					$checkin_date_on_city=date("Y-m-d", strtotime($checkin_date)+(24*60*60*$add_day));
					$add_day=$add_day+$server_data['data']['step_1']['number_of_night'][$city_index];
					$checkout_date_on_city=date("Y-m-d", strtotime($checkin_date_on_city)+(24*60*60*$server_data['data']['step_1']['number_of_night'][$city_index]));
					$prev_city_id=$explode_offer_data[0];
				endif;
				$total_price=0.00;
				$offer_addon_price = tools::find("first", TM_OFFER_ADDON_PRICES, '*', "WHERE offer_id=:offer_id AND country_id=:country_id AND status=:status", array(":offer_id"=>$offer_details['id'], ":country_id"=>$server_data['data']['step_1']['city'][$city_index], ':status'=>1));
				if(!empty($offer_addon_price))
				{
					$nationality_addon_percentage=$offer_addon_price['addon_price'];
				}
				/*for($i=strtotime($checkin_date_on_city);$i<strtotime($checkout_date_on_city);):
					$complete_date=date("Y-m-d", $i);
					$offer_price_list = tools::find("first", TM_OFFER_PRICES, '*', "WHERE offer_id=:offer_id AND start_date<=:start_date AND end_date>=:end_date AND status=:status", array(":offer_id"=>$offer_details['id'], ":start_date"=>$complete_date, ":end_date"=>$complete_date, ':status'=>1));
					if(!empty($offer_price_list)):
						$offer_day_price=$offer_price_list['price_per_person'];
					else:
						$offer_day_price=$offer_details['price_per_person'];
					endif;
					$total_price=$total_price+$offer_day_price;
					$i=$i+(24*60*60);
				endfor;*/
				$offer_price_list = tools::find("first", TM_OFFER_PRICES, '*', "WHERE offer_id=:offer_id AND start_date<=:start_date AND end_date>=:end_date AND status=:status", array(":offer_id"=>$offer_details['id'], ":start_date"=>$server_data['data']['step_4_all']['transfer_booking_transfer_date_arr'][$offer_key], ":end_date"=>$server_data['data']['step_4_all']['transfer_booking_transfer_date_arr'][$offer_key], ':status'=>1));
				if(!empty($offer_price_list)):
					$offer_day_price=$offer_price_list['price_per_person'];
				else:
					$offer_day_price=$offer_details['price_per_person'];
				endif;
				$total_price=$total_price+$offer_day_price;
				$nationality_charge=($total_price * $nationality_addon_percentage)/100;
				$agent_commision=($total_price * $markup_percentage)/100;
				$total_transfer_price=$total_transfer_price+$total_price+$agent_commision+$nationality_charge;
				if($server_data['data']['city_val']==$explode_offer_data[0]):
					$each_result['transfer_id']=$transfer_details['id'];
					$each_result['offer_id']=$offer_details['id'];
					$each_result['price']=$total_price;
					//$each_result['booking_start_date']=$checkin_date_on_city;
					$each_result['booking_start_date']=$server_data['data']['step_4_all']['transfer_booking_transfer_date_arr'][$offer_key];
					//$each_result['booking_end_date']=$checkout_date_on_city;
					$each_result['booking_end_date']=$server_data['data']['step_4_all']['transfer_booking_transfer_date_arr'][$offer_key];
					$each_result['pickup_time']=$server_data['data']['step_4_all']['transfer_pickuptime_arr'][$offer_key];
					$each_result['dropoff_time']=$server_data['data']['step_4_all']['transfer_dropofftime_arr'][$offer_key];
					$each_result['airport']=$server_data['data']['step_4_all']['transfer_airport_arr'][$offer_key];
					$each_result['flight_number_name']=$server_data['data']['step_4_all']['transfer_flight_number_arr'][$offer_key];
					$each_result['agent_markup_percentage']=$markup_percentage;
					$each_result['nationality_addon_percentage']=$nationality_addon_percentage;
					$each_result['avalibility_status']=$explode_offer_data[1];
					array_push($result, $each_result);
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