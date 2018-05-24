<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	//$server_data=json_decode('{"token":{"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE1MjY2Mjk4MzEsImp0aSI6Ik40cEhHS2FhcGI5bEp5UG1mcThnK3V2cUZncWVqVkhEbUxLWTNuWSs1XC9rPSIsImlzcyI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvdHJpcG1heHhcL1Jlc3RcL0FwaVwvdHJhbnNmZXJcL2FwaVwvIiwibmJmIjoxNTI2NjI5ODMxLCJleHAiOjE1MjY2Mjk5MzEsImRhdGEiOnsiZmlsZV9uYW1lIjoiMTUyNjYyOTgzMV8xNzAudHh0In19.aRVwTqi-ZZWPgMj7cs8G_gkeyn6GPr8dHD9dWQTVVLSSN2-hzf-fWSDuHSdv3sgC6USj6V_DL2OvXRvDauE8NA","token_timeout":100,"token_generation_time":1526629831},"data":{"booking_type":"personal","agent_name":"","checkin":"18\/05\/2018","checkout":"22\/05\/2018","country":["101","101"],"city":["5312","5313"],"number_of_night":["2","2"],"hotel_ratings":[["3"],["2"]],"first_page_hotel":["2","4"],"sel_nationality":"101","country_residance":"101","sel_currency":"1","rooms":"1","adult":["1"],"child":[""],"offset":0,"record_per_page":10,"type":"1","sort_order":"","city_id":"","country_id":"","search_val":"","booking_details_list":{"id":"34","booking_number":"","quotation_name":"kkkk","checkin_date":"2018-05-18","checkout_date":"2018-05-22","booking_type":"personal","dmc_id":"1","agent_id":"0","nationality":"101","residance_country":"101","invoice_currency":"1","number_of_rooms":"1","adult":"[\"1\"]","child":"[]","total_amount":"330.00","status":"0","is_deleted":"N","creation_date":"2018-05-18 12:40:14","last_updated":"2018-05-18 12:40:14","currency_code":"INR","currency_name":"Indian","booking_supplier_list":[{"id":"15","booking_master_id":"34","supplier_id":"7","status":"0","creation_date":"2018-05-10 16:35:40","last_updated":"2018-05-10 16:35:40"},{"id":"35","booking_master_id":"34","supplier_id":"7","status":"0","creation_date":"2018-05-18 12:40:17","last_updated":"2018-05-18 12:40:17"}],"booking_destination_list":[{"id":"78","booking_master_id":"34","country_id":"101","city_id":"5312","no_of_night":"2","hotel_rating":"3","creation_date":"2018-05-18 12:40:14","last_updated":"2018-05-18 12:40:14","co_name":"India","ci_name":"24 Parganas (n)","booking_hotel_list":[{"id":"78","booking_destination_id":"78","hotel_id":"2","room_id":"5","price":"20.00","booking_start_date":"2018-05-18","booking_end_date":"2018-05-20","agent_markup_percentage":"0","currency_id":"1","avalibility_status":"N","status":"0","creation_date":"2018-05-18 12:40:14","last_updated":"2018-05-18 12:40:14"}],"booking_tour_list":[{"id":"90","booking_destination_id":"78","tour_id":"3","offer_id":"4","price":"200.00","number_of_person":"1","booking_start_date":"2018-05-18","booking_end_date":"2018-05-20","agent_markup_percentage":"0","nationality_addon_percentage":"0","avalibility_status":"N","creation_date":"2018-05-18 12:40:15","last_updated":"2018-05-18 12:40:15"}],"booking_transfer_list":[{"id":"44","booking_destination_id":"78","transfer_id":"5","offer_id":"8","price":"20.00","number_of_person":"1","booking_start_date":"2018-05-18","booking_end_date":"2018-05-20","agent_markup_percentage":"0","nationality_addon_percentage":"0","avalibility_status":"A","creation_date":"2018-05-18 12:40:15","last_updated":"2018-05-18 12:40:15"}]},{"id":"79","booking_master_id":"34","country_id":"101","city_id":"5313","no_of_night":"2","hotel_rating":"2","creation_date":"2018-05-18 12:40:15","last_updated":"2018-05-18 12:40:15","co_name":"India","ci_name":"24 Parganas (s)","booking_hotel_list":[{"id":"79","booking_destination_id":"79","hotel_id":"4","room_id":"9","price":"20.00","booking_start_date":"2018-05-20","booking_end_date":"2018-05-22","agent_markup_percentage":"0","currency_id":"1","avalibility_status":"N","status":"0","creation_date":"2018-05-18 12:40:15","last_updated":"2018-05-18 12:40:15"}],"booking_tour_list":[{"id":"91","booking_destination_id":"79","tour_id":"4","offer_id":"6","price":"30.00","number_of_person":"1","booking_start_date":"2018-05-20","booking_end_date":"2018-05-22","agent_markup_percentage":"0","nationality_addon_percentage":"0","avalibility_status":"A","creation_date":"2018-05-18 12:40:15","last_updated":"2018-05-18 12:40:15"}],"booking_transfer_list":[{"id":"45","booking_destination_id":"79","transfer_id":"6","offer_id":"9","price":"40.00","number_of_person":"1","booking_start_date":"2018-05-20","booking_end_date":"2018-05-22","agent_markup_percentage":"0","nationality_addon_percentage":"0","avalibility_status":"A","creation_date":"2018-05-18 12:40:15","last_updated":"2018-05-18 12:40:15"}]}]}}}', true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		if(isset($server_data['data']) && isset($server_data['data']['country']) && !empty($server_data['data']['country'])):
			$total_person=0;
			foreach($server_data['data']['adult'] as $key_adult=>$val_adult):
				$total_person=$total_person+$val_adult;
			endforeach;
			foreach($server_data['data']['child'] as $key_child=>$val_child):
				$total_person=$total_person+$val_child;
			endforeach;
			$city_tab_html='';
			$country_city_rcd_html='';
			$heading_count_rcd=0;
			$add_day=0;
			$default_currency=tools::find("first", TM_SETTINGS." as s, ".TM_CURRENCIES." as c", 'c.*', "WHERE c.id=s.default_currency ", array());
			$markup_percentage=0;
			$nationality_addon_percentage=0;
			foreach($server_data['data']['country'] as $country_key=>$counrty_val):	
				$checkin_date_obj=date_create_from_format("d/m/Y", $server_data['data']['checkin']);
				$checkin_date=date_format($checkin_date_obj, "Y-m-d");
				$checkin_date_on_city=date("Y-m-d", strtotime($checkin_date)+(24*60*60*$add_day));
				$add_day=$add_day+$server_data['data']['number_of_night'][$country_key];
				$checkout_date_on_city=date("Y-m-d", strtotime($checkin_date_on_city)+(24*60*60*$server_data['data']['number_of_night'][$country_key]));
				if(isset($server_data['data']['booking_details_list']) && $server_data['data']['booking_details_list']!=""):
					$booking_details_list=$server_data['data']['booking_details_list'];
				endif;
				$contry_list = tools::find("first", TM_COUNTRIES, '*', "WHERE id=:id ORDER BY name ASC ", array(":id"=>$counrty_val));
				$city_list = tools::find("first", TM_CITIES, '*', "WHERE id=:id ", array(":id"=>$server_data['data']['city'][$country_key]));
				$country_name=$contry_list['name'];
				$city_name=$city_list['name'];
				if(count($server_data['data']['country'])>1 && $server_data['data']['sort_order']=="" && $server_data['data']['type']!=3 && $server_data['data']['type']!=2):
					$city_tab_html.='<div class="col-sm-3 cls_each_city_transfer_tab_div '.($country_key==0 ? "cls_each_city_tab_div_active" : "").'" data-tab_id="transfer_city'.$server_data['data']['city'][$country_key].'" onclick="change_city_transfer($(this))">'.$city_name.'</div>';
				endif;
				$country_city_rcd_html.='<div class="each_transfer_tab_content '.($country_key==0 ? "active_each_tab_content" : "").'" id="transfer_city'.$server_data['data']['city'][$country_key].'" data-city_id="'.$server_data['data']['city'][$country_key].'" data-country_id="'.$counrty_val.'">';
					$country_city_rcd_html.='<div class="col-md-12 heading_count_rcd">';
						$country_city_rcd_html.='<p>Your search for <font color="red"><b>'.$country_name.'</b></font>, <font color="red"><b>'.$city_name.'</b></font> for <font color="red"><b>'.tools::module_date_format($checkin_date_on_city).' - '.tools::module_date_format($checkout_date_on_city).'</b></font> for <font color="red"><b>'.$total_person.' Passenger(s)</b></font></p>';
					$country_city_rcd_html.='</div>';
					$country_city_rcd_html.='<h3>Create New Transfer</h3>';
					$country_city_rcd_html.='<form name="form_third_step" id="form_third_step" class="form_third_step" method="POST" onsubmit="filter_transfer_search($(this), '.$server_data['data']['city'][$country_key].');return false;" data-country_id="'.$counrty_val.'">';
						$country_city_rcd_html.='<div class="form-group col-sm-6">';
							$country_city_rcd_html.='<label for="inputName" class="control-label">Choose Day</label>';
							$country_city_rcd_html.='
								<select name="booking_transfer_date'.$server_data['data']['city'][$country_key].'"  id="booking_transfer_date'.$server_data['data']['city'][$country_key].'" class="form-control validate[required]">';
								$country_city_rcd_html.='<option value="">Select Date</option>';
							for($i=strtotime($checkin_date_on_city);$i<=strtotime($checkout_date_on_city);):
								$choosen_date_value=date("Y-m-d", $i);
								$choosen_date_text=date("Y-m-d", $i);
								$country_city_rcd_html.='<option value="'.$choosen_date_value.'">'.tools::module_date_format($choosen_date_text).'</option>';
								$i=$i+(24*60*60);
							endfor;
							$country_city_rcd_html.='</select>';
						$country_city_rcd_html.='</div>';
						$transfer_attribute_list = tools::find("all", TM_ATTRIBUTES, '*', "WHERE status=:status ORDER BY serial_number ASC ", array(":status"=>1));
						$country_city_rcd_html.='<div class="form-group col-sm-6">';
							$country_city_rcd_html.='<label for="inputName" class="control-label">Pickup/Dropoff Type</label>';
							$country_city_rcd_html.='
								<select name="pickup_dropoff_type'.$server_data['data']['city'][$country_key].'"  id="pickup_dropoff_type'.$server_data['data']['city'][$country_key].'" class="form-control validate[required]">';
								$country_city_rcd_html.='<option value="">Select Pickup/Dropoff Type</option>';
								foreach($transfer_attribute_list as $attr_key=>$attr_val):
									$country_city_rcd_html.='<option value="'.$attr_val['id'].'">'.$attr_val['attribute_name'].'</option>';
								endforeach;
							$country_city_rcd_html.='</select>';
						$country_city_rcd_html.='</div>';
						$transfer_city_airport_list = tools::find("all", TM_AIRPORTS, '*', "WHERE countryName=:countryName ORDER BY name ASC ", array(":countryName"=>$country_name));
						$country_city_rcd_html.='<div class="form-group col-sm-6">';
							$country_city_rcd_html.='<label for="inputName" class="control-label">Choose Airport</label>';
							$country_city_rcd_html.='
								<select name="selected_airport'.$server_data['data']['city'][$country_key].'"  id="selected_airport'.$server_data['data']['city'][$country_key].'" class="form-control validate[required]">';
								$country_city_rcd_html.='<option value="">Choose Airport</option>';
								foreach($transfer_city_airport_list as $airport_key=>$airport_val):
									$country_city_rcd_html.='<option value="'.$airport_val['id'].'">'.$airport_val['name'].'</option>';
								endforeach;
							$country_city_rcd_html.='</select>';
						$country_city_rcd_html.='</div>';
						$country_city_rcd_html.='<div class="form-group col-sm-6">';
							$country_city_rcd_html.='<label for="inputName" class="control-label">Arrival/Departure Time</label>';
							$country_city_rcd_html.='<input type="time" class="form-control validate[required]" name="arr_dept_time'.$server_data['data']['city'][$country_key].'" id="arr_dept_time'.$server_data['data']['city'][$country_key].'" value="" >';
						$country_city_rcd_html.='</div>';
						$country_city_rcd_html.='<div class="form-group col-sm-6">';
							$country_city_rcd_html.='<label for="inputName" class="control-label">Service Type</label>';
							$country_city_rcd_html.='
								<select name="selected_service_type'.$server_data['data']['city'][$country_key].'"  id="selected_service_type'.$server_data['data']['city'][$country_key].'" class="form-control validate[required]">';
								$country_city_rcd_html.='<option value = "">Select Service Type</option>';
								$country_city_rcd_html.='<option value = "Private">Private</option>';
								$country_city_rcd_html.='<option value = "Shared">Shared</option>';
							$country_city_rcd_html.='</select>';
						$country_city_rcd_html.='</div>';
						$country_city_rcd_html.='<div class="form-group col-sm-5 text-left">';
							$country_city_rcd_html.='<button type="submit" class="btn btn-primary next-step" style="margin-top:23px;" >Search</button>';
						$country_city_rcd_html.='</div>';
						$country_city_rcd_html.='<div class="clearfix"></div>';
					$country_city_rcd_html.='</form>';
					$country_city_rcd_html.='<div id="" class="all_rcd_row"></div>';
				$country_city_rcd_html.='</div>';
			endforeach;
			$city_tab_html.='<div class="clearfix"></div>';
			$return_data['status']="success";
			$return_data['msg']="Date fetched successfully.";
			$return_data['country_city_rcd_html']=$country_city_rcd_html;
			$return_data['city_tab_html']=$city_tab_html;
			$return_data['heading_count_rcd']=$heading_count_rcd;
		else:
			$return_data['status']="error";
			$return_data['msg']="Some data missing.";
		endif;
	endif;
	echo json_encode($return_data);	
?>