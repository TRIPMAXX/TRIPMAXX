<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		$checkin_date_obj=date_create_from_format("d/m/Y", $server_data['data']['step_1']['checkin']);
		$checkin_date=date_format($checkin_date_obj, "Y-m-d");
		$_POST['checkin_date']=$checkin_date;
		$checkout_date_obj=date_create_from_format("d/m/Y", $server_data['data']['step_1']['checkout']);
		$checkout_date=date_format($checkout_date_obj, "Y-m-d");
		$_POST['checkout_date']=$checkout_date;
		$_POST['booking_type']=$server_data['data']['step_1']['booking_type'];
		$_POST['agent_id']=$_POST['dmc_id']=0;
		if($server_data['data']['step_1']['booking_type']=="agent"):
			$_POST['agent_id']=$server_data['data']['step_1']['agent_name'];
		else:
			$_POST['dmc_id']=$server_data['data']['SESSION_DATA']['id'];
		endif;
		$_POST['nationality']=$server_data['data']['step_1']['sel_nationality'];
		$_POST['residance_country']=$server_data['data']['step_1']['country_residance'];
		$_POST['invoice_currency']=$server_data['data']['step_1']['sel_currency'];
		$_POST['number_of_rooms']=$server_data['data']['step_1']['rooms'];
		if(isset($server_data['data']['step_5']['quotation_name'])):
			$_POST['quotation_name']=$server_data['data']['step_5']['quotation_name'];
		elseif(isset($server_data['data']['booking_details_list']['quotation_name'])):
			$_POST['quotation_name']=$server_data['data']['booking_details_list']['quotation_name'];
		endif;
		$adult_arr=array();
		foreach($server_data['data']['step_1']['adult'] as $adult_key=>$adult_val):
			if($adult_val!="")
				array_push($adult_arr, $adult_val);
		endforeach;
		$_POST['adult']=json_encode($adult_arr);
		$child_arr=array();
		foreach($server_data['data']['step_1']['child'] as $child_key=>$child_val):
			if($child_val!=""):
				$child_details_array['child']=$child_val;
				if(isset($server_data['data']['step_1']['child_age']) && isset($server_data['data']['step_1']['child_age'][$child_key]) && !empty($server_data['data']['step_1']['child_age'][$child_key])):
					$child_details_array['child_age']=$server_data['data']['step_1']['child_age'][$child_key];
				endif;
				if(isset($server_data['data']['step_1']['bed_required']) && isset($server_data['data']['step_1']['bed_required'][$child_key]) && !empty($server_data['data']['step_1']['bed_required'][$child_key])):
					$child_details_array['bed_required']=$server_data['data']['step_1']['bed_required'][$child_key];
				endif;
				array_push($child_arr, $child_details_array);
			endif;
		endforeach;
		$_POST['child']=json_encode($child_arr);
		//$_POST['child_age']=$server_data['data']['step_1']['child_age'];
		//$_POST['bed_required']=$server_data['data']['step_1']['bed_required'];
		//print_r($server_data['data']);
		$_POST['total_amount']=$server_data['data']['total_price'];
		$_POST['id']=$server_data['data']['booking_details_list']['id'];
		if($save_booking = tools::module_form_submission("", TM_BOOKING_MASTERS)):
			$return_data['booking_id']=$_POST['id'];
			//Delete previous data start
			$delete_booking_destination = tools::find("first", TM_BOOKING_DESTINATION, 'GROUP_CONCAT(id) as destination_ids', "WHERE booking_master_id=:booking_master_id ", array(":booking_master_id"=>$server_data['data']['booking_details_list']['id']));
			tools::delete(TM_BOOKING_HOTEL_DETAILS, "WHERE booking_destination_id IN (".$delete_booking_destination['destination_ids'].")", array());
			tools::delete(TM_BOOKING_TOUR_DETAILS, "WHERE booking_destination_id IN (".$delete_booking_destination['destination_ids'].")", array());
			tools::delete(TM_BOOKING_TRANSFER_DETAILS, "WHERE booking_destination_id IN (".$delete_booking_destination['destination_ids'].")", array());
			tools::delete(TM_BOOKING_DESTINATION, "WHERE id IN (".$delete_booking_destination['destination_ids'].")", array());
			//Delete previous data end
			unset($_POST);
			foreach($server_data['data']['step_1']['country'] as $country_key=>$country_val):
				$_POST['booking_master_id']=$server_data['data']['booking_details_list']['id'];
				$_POST['country_id']=$country_val;
				$_POST['city_id']=$server_data['data']['step_1']['city'][$country_key];
				$_POST['no_of_night']=$server_data['data']['step_1']['number_of_night'][$country_key];
				$_POST['hotel_rating']=implode(",", $server_data['data']['step_1']['hotel_ratings'][$country_key]);
				$save_booking_destination = tools::module_form_submission("", TM_BOOKING_DESTINATION);
				if($save_booking_destination > 0):
					$return_hotel_ids=array();
					$autentication_data_hotel=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."authorized.php"));
					if(isset($autentication_data_hotel->status)):
						if($autentication_data_hotel->status=="success"):
							$post_data_hotel['token']=array(
								"token"=>$autentication_data_hotel->results->token,
								"token_timeout"=>$autentication_data_hotel->results->token_timeout,
								"token_generation_time"=>$autentication_data_hotel->results->token_generation_time
							);
							$post_data_hotel['data']['step_1']=$server_data['data']['step_1'];
							$post_data_hotel['data']['step_2']=$server_data['data']['step_2'];
							$post_data_hotel['data']['saved_booking_destination']=$save_booking_destination;
							$post_data_hotel['data']['country_key']=$country_key;
							$post_data_str_hotel=json_encode($post_data_hotel);
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
							curl_setopt($ch, CURLOPT_HEADER, false);
							curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
							curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
							curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."hotel/selected-hotel-info.php");
							curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_hotel);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
							$return_data_hotel = curl_exec($ch);
							curl_close($ch);
							//print_r($return_data_hotel);
							$return_data_arr_hotel=json_decode($return_data_hotel, true);
							$hotel_data=array();
							if(!isset($return_data_arr_hotel['status'])):
								//$data['status'] = 'error';
								//$data['msg']="Some error has been occure during execution.";
							elseif($return_data_arr_hotel['status']=="success"):
								//$data['status'] = 'success';
								//$data['msg']="Data received successfully";
								unset($_POST);
								array_push($return_hotel_ids, $return_data_arr_hotel['results']['hotel_id']);
								$_POST['booking_destination_id']=$save_booking_destination;
								$_POST['hotel_id']=$return_data_arr_hotel['results']['hotel_id'];
								$_POST['room_id']=$return_data_arr_hotel['results']['room_id'];
								$_POST['price']=$return_data_arr_hotel['results']['price'];
								$_POST['booking_start_date']=$return_data_arr_hotel['results']['booking_start_date'];
								$_POST['booking_end_date']=$return_data_arr_hotel['results']['booking_end_date'];
								$_POST['agent_markup_percentage']=$return_data_arr_hotel['results']['agent_markup_percentage'];
								$_POST['avalibility_status']=$return_data_arr_hotel['results']['avalibility_status'];
								$_POST['currency_id']=$return_data_arr_hotel['default_currency'];
								$save_booking_hotel_details = tools::module_form_submission("", TM_BOOKING_HOTEL_DETAILS);
								unset($_POST);
							else:
								//$data['status'] = 'error';
								//$data['msg'] = $return_data_arr_hotel['msg'];
							endif;
						endif;
					else:
						//$data['status'] = 'error';
						//$data['msg'] = $autentication_data_hotel->msg;
					endif;
					$return_data['return_hotel_ids']=$return_hotel_ids;
					$number_of_person=$number_of_adult=$number_of_child=0;
					foreach($server_data['data']['step_1']['adult'] as $adult_key=>$adult_val):
						if($adult_val!="")
							$number_of_adult=$number_of_adult+$adult_val;
					endforeach;
					foreach($server_data['data']['step_1']['child'] as $child_key=>$child_val):
						if($child_val!="")
							$number_of_child=$number_of_child+$child_val;
					endforeach;
					$number_of_person=$number_of_adult+$number_of_child;
					$autentication_data_tour=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.TOUR_API_PATH."authorized.php"));
					if(isset($autentication_data_tour->status)):
						if($autentication_data_tour->status=="success"):
							$post_data_tour['token']=array(
								"token"=>$autentication_data_tour->results->token,
								"token_timeout"=>$autentication_data_tour->results->token_timeout,
								"token_generation_time"=>$autentication_data_tour->results->token_generation_time
							);
							$post_data_tour['data']['step_1']=$server_data['data']['step_1'];
							$post_data_tour['data']['step_3']=$server_data['data']['step_3'];
							$post_data_tour['data']['saved_booking_destination']=$save_booking_destination;
							$post_data_tour['data']['city_val']=$server_data['data']['step_1']['city'][$country_key];
							$post_data_str_tour=json_encode($post_data_tour);
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
							curl_setopt($ch, CURLOPT_HEADER, false);
							curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
							curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
							curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.TOUR_API_PATH."tour/selected-tour-info.php");
							curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_tour);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
							$return_data_tour = curl_exec($ch);
							curl_close($ch);
							//print_r($return_data_tour);
							$return_data_arr_tour=json_decode($return_data_tour, true);
							$hotel_data=array();
							if(!isset($return_data_arr_tour['status'])):
								//$data['status'] = 'error';
								//$data['msg']="Some error has been occure during execution.";
							elseif($return_data_arr_tour['status']=="success"):
								//$data['status'] = 'success';
								//$data['msg']="Data received successfully";
								unset($_POST);
								$_POST['booking_destination_id']=$save_booking_destination;
								if(!empty($return_data_arr_tour['results'])):
									foreach($return_data_arr_tour['results'] as $result_key=>$result_val):
										$_POST['booking_destination_id']=$save_booking_destination;
										$_POST['tour_id']=$result_val['tour_id'];
										$_POST['offer_id']=$result_val['offer_id'];
										$_POST['price']=$result_val['price'];
										$_POST['number_of_person']=$number_of_person;
										$_POST['booking_start_date']=$result_val['booking_start_date'];
										$_POST['booking_end_date']=$result_val['booking_end_date'];
										$_POST['agent_markup_percentage']=$result_val['agent_markup_percentage'];
										$_POST['nationality_addon_percentage']=$result_val['nationality_addon_percentage'];
										$_POST['currency_id']=$return_data_arr_tour['default_currency'];
										$_POST['avalibility_status']=$result_val['avalibility_status'];
										$save_booking_tour_details = tools::module_form_submission("", TM_BOOKING_TOUR_DETAILS);
										unset($_POST);
									endforeach;
								endif;
							else:
								//$data['status'] = 'error';
								//$data['msg'] = $return_data_arr_tour['msg'];
							endif;
						endif;
					else:
						//$data['status'] = 'error';
						//$data['msg'] = $autentication_data_tour->msg;
					endif;
					$autentication_data_transfer=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.TRANSFER_API_PATH."authorized.php"));
					if(isset($autentication_data_transfer->status)):
						if($autentication_data_transfer->status=="success"):
							$post_data_transfer['token']=array(
								"token"=>$autentication_data_transfer->results->token,
								"token_timeout"=>$autentication_data_transfer->results->token_timeout,
								"token_generation_time"=>$autentication_data_transfer->results->token_generation_time
							);
							$post_data_transfer['data']['step_1']=$server_data['data']['step_1'];
							$post_data_transfer['data']['step_4']=$server_data['data']['step_4'];
							$post_data_transfer['data']['saved_booking_destination']=$save_booking_destination;
							$post_data_transfer['data']['city_val']=$server_data['data']['step_1']['city'][$country_key];
							$post_data_str_transfer=json_encode($post_data_transfer);
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
							curl_setopt($ch, CURLOPT_HEADER, false);
							curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
							curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
							curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.TRANSFER_API_PATH."transfer/selected-transfer-info.php");
							curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_transfer);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
							$return_data_transfer = curl_exec($ch);
							curl_close($ch);
							//print_r($return_data_transfer);
							$return_data_arr_transfer=json_decode($return_data_transfer, true);
							$hotel_data=array();
							if(!isset($return_data_arr_transfer['status'])):
								//$data['status'] = 'error';
								//$data['msg']="Some error has been occure during execution.";
							elseif($return_data_arr_transfer['status']=="success"):
								//$data['status'] = 'success';
								//$data['msg']="Data received successfully";
								unset($_POST);
								$_POST['booking_destination_id']=$save_booking_destination;
								if(!empty($return_data_arr_transfer['results'])):
									foreach($return_data_arr_transfer['results'] as $result_key=>$result_val):
										$_POST['booking_destination_id']=$save_booking_destination;
										$_POST['transfer_id']=$result_val['transfer_id'];
										$_POST['offer_id']=$result_val['offer_id'];
										$_POST['price']=$result_val['price'];
										$_POST['number_of_person']=$number_of_person;
										$_POST['booking_start_date']=$result_val['booking_start_date'];
										$_POST['booking_end_date']=$result_val['booking_end_date'];
										$_POST['agent_markup_percentage']=$result_val['agent_markup_percentage'];
										$_POST['nationality_addon_percentage']=$result_val['nationality_addon_percentage'];
										$_POST['currency_id']=$return_data_arr_transfer['default_currency'];
										$_POST['avalibility_status']=$result_val['avalibility_status'];
										$save_booking_transfer_details = tools::module_form_submission("", TM_BOOKING_TRANSFER_DETAILS);
										unset($_POST);
									endforeach;
								endif;
							else:
								//$data['status'] = 'error';
								//$data['msg'] = $return_data_arr_transfer['msg'];
							endif;
						endif;
					else:
						//$data['status'] = 'error';
						//$data['msg'] = $autentication_data_transfer->msg;
					endif;
				endif;
			endforeach;
			//$_POST['agent_id']=$save_agent;
			$return_data['status']="success";
			$return_data['msg'] = 'Booking has been created successfully.';
		else:
			$return_data['status']="error";
			$return_data['msg']="We are having some problem. Please try later.";
		endif;
	endif;
	echo json_encode($return_data);	
?>