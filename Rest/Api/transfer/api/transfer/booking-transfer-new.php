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
				$transfer_prev_html=$transfer_all_html=$transfer_date=$transfer_svg_line='';
				if(isset($server_data['data']['booking_details_list']) && $server_data['data']['booking_details_list']!=""):
					$booking_details_list=$server_data['data']['booking_details_list'];
					if(isset($booking_details_list) && !empty($booking_details_list)):
						foreach($booking_details_list['booking_destination_list'] as $b_key=>$b_val):
							if(isset($b_val['booking_transfer_list']) && !empty($b_val['booking_transfer_list'])):
								foreach($b_val['booking_transfer_list'] as $t_key=>$t_val):
									if($b_val['country_id']==$counrty_val && $b_val['city_id']==$server_data['data']['city'][$country_key]):
										if($transfer_date!="" && $transfer_date!=$t_val['booking_start_date']):
											$transfer_all_html.='<div class="each_date_div_'.$transfer_date.' each_date_div" data-date_time="'.strtotime($transfer_date).'">';
												$transfer_all_html.='<div class="col-md-12 date_heading_div">';
													$transfer_all_html.='<h4>Date: '.tools::module_date_format($transfer_date).'</h4>';
													$transfer_all_html.='<div class="clock_img_div">';
														$clock_am_div=$clock_pm_div='';
														foreach($booking_details_list['booking_destination_list'] as $svg_b_key=>$svg_b_val):
															if(isset($svg_b_val['booking_transfer_list']) && !empty($svg_b_val['booking_transfer_list'])):
																foreach($svg_b_val['booking_transfer_list'] as $svg_t_key=>$svg_t_val):
																	if($transfer_date==$svg_t_val['booking_start_date']):
																		$booking_start_time = explode(':', $svg_t_val['pickup_time']);
																		$start_point=((($booking_start_time[0]*60 + $booking_start_time[1])*644)/1440);
																		$booking_end_time = explode(':', $svg_t_val['dropoff_time']);
																		$end_point=((($booking_end_time[0]*60 + $booking_end_time[1])*644)/1440);
																		//$transfer_all_html.='<line x1="'.$start_point.'" y1="0" x2="'.$end_point.'" y2="0" class="'.rand(1, 1000).'"/>';
																		$start_angle=($booking_start_time[0]*60+$booking_start_time[1])*.5;
																		$end_angle=($booking_end_time[0]*60+$booking_end_time[1])*.5;
																		if($start_angle < 360 && $end_angle < 360):
																			$clock_am_div.='<path class="am_old_transfer_'.$svg_t_val['id'].'" fill="green" d="'.tools::describeArc(51, 37, 31, $start_angle, $end_angle).'"></path>';
																			$clock_pm_div.='<path class="pm_old_transfer_'.$svg_t_val['id'].'" fill="green" d=""></path>';
																		elseif($start_angle < 360 && $end_angle > 359):
																			$clock_am_div.='<path class="am_old_transfer_'.$svg_t_val['id'].'" fill="green" d="'.tools::describeArc(51, 37, 31, $start_angle, 359).'"></path>';
																			$clock_pm_div.='<path class="pm_old_transfer_'.$svg_t_val['id'].'" fill="green" d="'.tools::describeArc(51, 37, 31, 360, $end_angle).'"></path>';
																		elseif($start_angle > 359 && $end_angle < 720):
																			$clock_am_div.='<path class="am_old_transfer_'.$svg_t_val['id'].'" fill="green" d=""></path>';
																			$clock_pm_div.='<path class="pm_old_transfer_'.$svg_t_val['id'].'" fill="green" d="'.tools::describeArc(51, 37, 31, $start_angle, $end_angle).'"></path>';
																		endif;
																	endif;
																endforeach;
															endif;
															if(isset($svg_b_val['booking_tour_list']) && !empty($svg_b_val['booking_tour_list'])):
																foreach($svg_b_val['booking_tour_list'] as $svg_t_key=>$svg_t_val):
																	if($transfer_date==$svg_t_val['booking_start_date']):
																		$booking_start_time = explode(':', $svg_t_val['pickup_time']);
																		$start_point=((($booking_start_time[0]*60 + $booking_start_time[1])*644)/1440);
																		$booking_end_time = explode(':', $svg_t_val['dropoff_time']);
																		$end_point=((($booking_end_time[0]*60 + $booking_end_time[1])*644)/1440);
																		//$transfer_all_html.='<line x1="'.$start_point.'" y1="0" x2="'.$end_point.'" y2="0" class="'.rand(1, 1000).'"/>';
																		$start_angle=($booking_start_time[0]*60+$booking_start_time[1])*.5;
																		$end_angle=($booking_end_time[0]*60+$booking_end_time[1])*.5;
																		if($start_angle < 360 && $end_angle < 360):
																			$clock_am_div.='<path class="am_old_tour_'.$svg_t_val['id'].'" fill="green" d="'.tools::describeArc(51, 37, 31, $start_angle, $end_angle).'"></path>';
																			$clock_pm_div.='<path class="pm_old_tour_'.$svg_t_val['id'].'" fill="green" d=""></path>';
																		elseif($start_angle < 360 && $end_angle > 359):
																			$clock_am_div.='<path class="am_old_tour_'.$svg_t_val['id'].'" fill="green" d="'.tools::describeArc(51, 37, 31, $start_angle, 359).'"></path>';
																			$clock_pm_div.='<path class="pm_old_tour_'.$svg_t_val['id'].'" fill="green" d="'.tools::describeArc(51, 37, 31, 360, $end_angle).'"></path>';
																		elseif($start_angle > 359 && $end_angle < 720):
																			$clock_am_div.='<path class="am_old_tour_'.$svg_t_val['id'].'" fill="green" d=""></path>';
																			$clock_pm_div.='<path class="pm_old_tour_'.$svg_t_val['id'].'" fill="green" d="'.tools::describeArc(51, 37, 31, $start_angle, $end_angle).'"></path>';
																		endif;
																	endif;
																endforeach;
															endif;
														endforeach;
														$transfer_all_html.='<div class="change_clock_am_div">';
															$transfer_all_html.='<input type="checkbox" checked data-toggle="toggle" data-on="AM" data-off="PM" data-onstyle="primary" data-offstyle="danger" class="toggle-demo" onchange="change_clock($(this))">';
														$transfer_all_html.='</div>';
														$transfer_all_html.='<div class="clock clock_am_div">';
															$transfer_all_html.='<svg>';
																$transfer_all_html.=$clock_am_div;
															$transfer_all_html.='</svg>';
														$transfer_all_html.='</div>';
														$transfer_all_html.='<div class="clock clock_pm_div">';
															$transfer_all_html.='<svg>';
																$transfer_all_html.=$clock_pm_div;
															$transfer_all_html.='</svg>';
														$transfer_all_html.='</div>';
														//$transfer_all_html.='<img src="assets/img/final_rular.png" border="0" alt="">';
													$transfer_all_html.='</div>';
												$transfer_all_html.='</div>';
												$transfer_all_html.=$transfer_prev_html;
												$transfer_all_html.='<div class="clearfix"></div>';
											$transfer_all_html.='</div>';
											$transfer_prev_html='';
										endif;
										$transfer_date=$t_val['booking_start_date'];
										$find_transfer_details = tools::find("first", TM_TRANSFER, '*', "WHERE id=:id", array(":id"=>$t_val['transfer_id']));
										$allow_pickup_type=$allow_dropoff_type="";
										if($find_transfer_details['allow_pickup_type']!=""):
											$transfer_attributes_pickup = tools::find("first", TM_ATTRIBUTES, "GROUP_CONCAT(attribute_name SEPARATOR ', ') as allow_pickup_type", "WHERE id IN (".$find_transfer_details['allow_pickup_type'].") ", array());
											$allow_pickup_type=$transfer_attributes_pickup['allow_pickup_type'];
										endif;
										$find_transfer_offer_details = tools::find("first", TM_OFFERS, '*', "WHERE id=:id", array(":id"=>$t_val['offer_id']));
										$find_transfer_offer_list = tools::find("all", TM_OFFERS, '*', "WHERE transfer_id=:transfer_id AND service_type=:service_type", array(":transfer_id"=>$find_transfer_details['id'], ':service_type'=>$find_transfer_offer_details['service_type']));
										$nationality_charge=($t_val['price'] * $t_val['nationality_addon_percentage'])/100;
										$agent_commision=($t_val['price'] * $t_val['agent_markup_percentage'])/100;
										$each_transfer_price=$t_val['price']+$agent_commision+$nationality_charge;
										$loginTime = strtotime($t_val['pickup_time'].':00');
										$logoutTime = strtotime($t_val['dropoff_time'].':00');
										$diff = $logoutTime - $loginTime;
										$hour=$diff/3600;
										$transfer_prev_html.='<div class="form-group col-md-12 each_transfer_row_outer">';
											$transfer_prev_html.='<div style="border:1px solid red;background-color:red;">';
												$transfer_prev_html.='<div class="col-md-3" style="font-weight:bold;color:#fff;">Transfer Title</div>';
												$transfer_prev_html.='<!-- <div class="col-md-2" style="font-weight:bold;color:#fff;">Transfer Type</div> -->';
												$transfer_prev_html.='<div class="col-md-3" style="font-weight:bold;color:#fff;text-align:center;">Availability</div>';
												$transfer_prev_html.='<div class="col-md-2" style="font-weight:bold;color:#fff;text-align:center;">Rate</div>';
												$transfer_prev_html.='<div class="col-md-4" style="font-weight:bold;color:#fff;">Transfer Details</div>';
												$transfer_prev_html.='<div class="clearfix"></div>';
											$transfer_prev_html.='</div>';
											$transfer_prev_html.='<div style="padding:20px 0 0 0;border:1px solid red;">';
												$transfer_prev_html.='<div class="col-md-3" style="font-weight:bold;">'.$find_transfer_details['transfer_title'].'</div>';
												$transfer_prev_html.='<!-- <div class="col-md-2" style="font-weight:bold;">'.$find_transfer_details['transfer_service'].'</div> -->';
												$transfer_prev_html.='<div class="col-md-3" style="font-weight:bold;text-align:center;">';
												if($t_val['avalibility_status']=="A"):
													$transfer_prev_html.='<button type="button" class="btn btn-success next-step">AVAILABLE</button>';
												else:
													$transfer_prev_html.='<button type="button" class="btn btn-danger next-step">On Request</button>';
												endif;
												$transfer_prev_html.='</div>';
												$transfer_prev_html.='<div class="col-md-2 default_price_div" style="font-weight:bold;text-align:center;" data-default_price="'.$default_currency['currency_code'].number_format($each_transfer_price, 2,".",",").'">'.$default_currency['currency_code'].number_format($each_transfer_price, 2,".",",").'</div>';
												$transfer_prev_html.='<div class="col-md-4">';
													$transfer_prev_html.='<img src="assets/img/delete.png" width="12" height="18" border="0" alt="Delete" class="delete_transfer_row" onclick="delete_transfer_row($(this))">';
													$transfer_prev_html.='<input type="hidden" name="selected_booking_transfer_date['.$b_val['city_id'].']['.$t_val['transfer_id'].'][old'.$t_key.']" id="selected_booking_transfer_date['.$b_val['city_id'].']['.$t_val['transfer_id'].'][old'.$t_key.']" value="'.$t_val['booking_start_date'].'" class="selected_booking_transfer_date">';
													$transfer_prev_html.='<input type="hidden" name="selected_service_type['.$b_val['city_id'].']['.$t_val['transfer_id'].'][old'.$t_key.']" id="selected_service_type['.$b_val['city_id'].']['.$t_val['transfer_id'].'][old'.$t_key.']" value="'.$find_transfer_offer_details['service_type'].'" class="selected_service_type">';
													$transfer_prev_html.='<strong>Pickup/Dropoff Type: </strong>'.$transfer_attributes_pickup['allow_pickup_type'].'<br>';
													$transfer_prev_html.='<input type="hidden" name="selected_pickup_dropoff_type['.$b_val['city_id'].']['.$t_val['transfer_id'].'][old'.$t_key.']" id="pickup_dropoff_type['.$b_val['city_id'].']['.$t_val['transfer_id'].'][old'.$t_key.']" value="'.$find_transfer_details['allow_pickup_type'].'" class="pickup_dropoff_type">';
													$transfer_prev_html.='<strong>Airport: </strong>'.$t_val['airport'].'<br>';
													$transfer_prev_html.='<input type="hidden" name="selected_airport['.$b_val['city_id'].']['.$t_val['transfer_id'].'][old'.$t_key.']" id="selected_airport['.$b_val['city_id'].']['.$t_val['transfer_id'].'][old'.$t_key.']" value="'.$t_val['airport'].'" class="selected_airport">';
													$transfer_prev_html.='<strong>Pickup: </strong>'.tools::module_date_format($t_val['booking_start_date']).'<input type="time" name="selected_pickuptime['.$b_val['city_id'].']['.$t_val['transfer_id'].'][old'.$t_key.']" id="selected_pickuptime['.$b_val['city_id'].']['.$t_val['transfer_id'].'][old'.$t_key.']" value="'.$t_val['pickup_time'].'" class="pickuptime" onkeyup="calculate_time($(this), \'p\')"><br>';
													$transfer_prev_html.='<strong>Dropoff: </strong>'.tools::module_date_format($t_val['booking_start_date']).'<input type="time" name="selected_dropofftime['.$b_val['city_id'].']['.$t_val['transfer_id'].'][old'.$t_key.']" id="selected_dropofftime['.$b_val['city_id'].']['.$t_val['transfer_id'].'][old'.$t_key.']" value="'.$t_val['dropoff_time'].'" class="dropofftime" onkeyup="calculate_time($(this), \'d\')"><br>';
													$transfer_prev_html.='<strong>Time: </strong><span class="calculated_time_diff">'.$hour.' hours</span><br>';
													$transfer_prev_html.='<input type="hidden" name="svg_path_id_input_hidden" class="svg_path_id_input" value="old_transfer_'.$t_val['id'].'">';
												$transfer_prev_html.='</div>';
												$transfer_prev_html.='<div class="clearfix"></div>';
												$transfer_prev_html.='<div class="col-md-3">';
												if($find_transfer_details['transfer_images']!=""):
													$image_arr=explode(",", $find_transfer_details['transfer_images']);
													$transfer_prev_html.='<img src="'.TRANSFER_IMAGE_PATH.$image_arr[0].'" border="0" alt="" width="250" height="150" onerror="this.remove;">';
												else:
													echo "N/A";
												endif;
												$transfer_prev_html.='</div>';
												$transfer_prev_html.='<div class="col-md-9">';
												$transfer_prev_html.='</div>';
												$transfer_prev_html.='<div class="clearfix"></div>';
												$transfer_prev_html.='<div class="col-md-12">';
													$transfer_prev_html.='<a href="'.DOMAIN_NAME_PATH_ADMIN.'edit_transfer?transfer_id='.base64_encode($find_transfer_details['id']).'" target="_blank" style="font-size:16px;"><b>MORE INFO</b></a> | <a href="javascript:void(0);" onclick="show_transfers(\'transfer'.$t_val['booking_start_date'].'-old-'.$find_transfer_details['id'].'\');" style="font-size:16px;"><b>VIEW AVAILABLE OFFERS</b></a>';
												$transfer_prev_html.='</div>';
												$transfer_prev_html.='<div class="clearfix"></div>';
												$transfer_prev_html.='<div id="transfer'.$t_val['booking_start_date'].'-old-'.$find_transfer_details['id'].'" class="transfer_offer_cls">';
													$transfer_prev_html.='<div style="border:1px solid gray;background-color:gray;margin-top:10px;">';
														$transfer_prev_html.='<div class="col-md-1" style="font-weight:bold;color:#fff;">#</div>';
														$transfer_prev_html.='<div class="col-md-3" style="font-weight:bold;color:#fff;">Offer Title</div>';
														$transfer_prev_html.='<div class="col-md-3" style="font-weight:bold;color:#fff;">Service Type</div>';
														$transfer_prev_html.='<div class="col-md-3" style="font-weight:bold;color:#fff;">Capacity</div>';
														$transfer_prev_html.='<div class="col-md-2" style="font-weight:bold;color:#fff;text-align:center;">Total Amount</div>';
														$transfer_prev_html.='<div class="clearfix"></div>';
													$transfer_prev_html.='</div>';
													foreach($find_transfer_offer_list as $list_key=>$list_val):
														if($list_val['id']==$find_transfer_offer_details['id']):
															if($t_val['avalibility_status']=="A"):
																$offer_avaliability_status="avaliable";
															else:
																$offer_avaliability_status="not avaliable";
															endif;
														else:
															$autentication_data=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.BOOKING_API_PATH."authorized.php"));
															if(isset($autentication_data->status)):
																if($autentication_data->status=="success"):
																	$post_data['token']=array(
																		"token"=>$autentication_data->results->token,
																		"token_timeout"=>$autentication_data->results->token_timeout,
																		"token_generation_time"=>$autentication_data->results->token_generation_time
																	);
																	$post_data['data']['offer_id']=$t_val['offer_id'];
																	$post_data['data']['transfer_id']=$t_val['transfer_id'];
																	$post_data['data']['booking_start_date']=$t_val['booking_start_date'];
																	$post_data['data']['booking_end_date']=$t_val['booking_start_date'];
																	$post_data_str=json_encode($post_data);
																	$ch = curl_init();
																	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
																	curl_setopt($ch, CURLOPT_HEADER, false);
																	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
																	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
																	curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.BOOKING_API_PATH."booking/booked-transfer.php");
																	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
																	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
																	$return_data_counter = curl_exec($ch);
																	curl_close($ch);
																	$return_data_arr=json_decode($return_data_counter, true);
																	if($return_data_arr['status']=="success"):
																		$total_prev_booking=$return_data_arr['results']['count_id'];
																	endif;
																endif;
															endif;
															$offer_avaliability_status="";
															if($total_prev_booking>=$list_val['offer_capacity']):
																$offer_avaliability_status="not avaliable";
															elseif($total_prev_booking<$list_val['offer_capacity']):
																$offer_avaliability_status="avaliable";
															endif;
														endif;
														$transfer_prev_html.='<div class="radio_button_row '.($list_val['id']==$find_transfer_offer_details['id'] ? "radio_button_row_background" : "").'" onclick="select_transfer_radio_row($(this))">';
															$transfer_prev_html.='<div class="col-md-1" style="font-weight:bold;">';
																if($offer_avaliability_status=="avaliable"):
																	$avalibility_status="A";
																	$transfer_prev_html.='<img src="assets/img/a_icon.png" border="0" alt="Avaliable" title="Avaliable">';
																elseif($offer_avaliability_status=="not avaliable"):
																	$avalibility_status="N";
																	$transfer_prev_html.='<img src="assets/img/r_icon.png" border="0" alt="On Request" title="On Request">';
																endif;
																$transfer_prev_html.='<br>';
																$transfer_prev_html.='<input type="radio" name="selected_transfer['.$b_val['city_id'].']['.$list_val['id'].'][old'.$t_key.']" class="selected_transfer" onclick="change_transfer_radio($(this))" value="'.$b_val['city_id'].'-'.$avalibility_status.'-'.$list_val['id'].'" data-price="'.$default_currency['currency_code'].number_format($list_val['price_per_person'], 2,".",",").'" '.($list_val['id']==$find_transfer_offer_details['id'] ? 'checked="checked' : "").'">';
															$transfer_prev_html.='</div>';
															$transfer_prev_html.='<div class="col-md-3" style="font-weight:bold;">';
																$transfer_prev_html.=''.$list_val['offer_title'].'';
															$transfer_prev_html.='</div>';
															$transfer_prev_html.='<div class="col-md-3">'.$list_val['service_type'].'</div>';
															$transfer_prev_html.='<div class="col-md-3">'.$list_val['offer_capacity'].'</div>';
															$transfer_prev_html.='<div class="col-md-2" style="font-weight:bold;color:red;text-align:center;">';
																$transfer_prev_html.=''.$default_currency['currency_code'].number_format($list_val['price_per_person'], 2,".",",").'';
															$transfer_prev_html.='</div>';
															$transfer_prev_html.='<div class="clearfix"></div>';
														$transfer_prev_html.='</div>';
													endforeach;
												$transfer_prev_html.='</div>';
											$transfer_prev_html.='</div>';
										$transfer_prev_html.='</div>';
									endif;
								endforeach;
								if($transfer_date!="" && $transfer_prev_html!=""):
									$transfer_all_html.='<div class="each_date_div_'.$transfer_date.' each_date_div" data-date_time="'.strtotime($transfer_date).'">';
										$transfer_all_html.='<div class="col-md-12 date_heading_div">';
											$transfer_all_html.='<h4>Date: '.tools::module_date_format($transfer_date).'</h4>';
											$transfer_all_html.='<div class="clock_img_div">';
												$clock_am_div=$clock_pm_div='';
												foreach($booking_details_list['booking_destination_list'] as $svg_b_key=>$svg_b_val):
													if(isset($svg_b_val['booking_transfer_list']) && !empty($svg_b_val['booking_transfer_list'])):
														foreach($svg_b_val['booking_transfer_list'] as $svg_t_key=>$svg_t_val):
															if($transfer_date==$svg_t_val['booking_start_date']):
																$booking_start_time = explode(':', $svg_t_val['pickup_time']);
																$start_point=((($booking_start_time[0]*60 + $booking_start_time[1])*644)/1440);
																$booking_end_time = explode(':', $svg_t_val['dropoff_time']);
																$end_point=((($booking_end_time[0]*60 + $booking_end_time[1])*644)/1440);
																//$transfer_all_html.='<line x1="'.$start_point.'" y1="0" x2="'.$end_point.'" y2="0" class="'.rand(1, 1000).'"/>';
																$start_angle=($booking_start_time[0]*60+$booking_start_time[1])*.5;
																$end_angle=($booking_end_time[0]*60+$booking_end_time[1])*.5;
																if($start_angle < 360 && $end_angle < 360):
																	$clock_am_div.='<path class="am_old_transfer_'.$svg_t_val['id'].'" fill="green" d="'.tools::describeArc(51, 37, 31, $start_angle, $end_angle).'"></path>';
																	$clock_pm_div.='<path class="pm_old_transfer_'.$svg_t_val['id'].'" fill="green" d=""></path>';
																elseif($start_angle < 360 && $end_angle > 359):
																	$clock_am_div.='<path class="am_old_transfer_'.$svg_t_val['id'].'" fill="green" d="'.tools::describeArc(51, 37, 31, $start_angle, 359).'"></path>';
																	$clock_pm_div.='<path class="pm_old_transfer_'.$svg_t_val['id'].'" fill="green" d="'.tools::describeArc(51, 37, 31, 360, $end_angle).'"></path>';
																elseif($start_angle > 359 && $end_angle < 720):
																	$clock_am_div.='<path class="am_old_transfer_'.$svg_t_val['id'].'" fill="green" d=""></path>';
																	$clock_pm_div.='<path class="pm_old_transfer_'.$svg_t_val['id'].'" fill="green" d="'.tools::describeArc(51, 37, 31, $start_angle, $end_angle).'"></path>';
																endif;
															endif;
														endforeach;
													endif;
													if(isset($svg_b_val['booking_tour_list']) && !empty($svg_b_val['booking_tour_list'])):
														foreach($svg_b_val['booking_tour_list'] as $svg_t_key=>$svg_t_val):
															if($transfer_date==$svg_t_val['booking_start_date']):
																$booking_start_time = explode(':', $svg_t_val['pickup_time']);
																$start_point=((($booking_start_time[0]*60 + $booking_start_time[1])*644)/1440);
																$booking_end_time = explode(':', $svg_t_val['dropoff_time']);
																$end_point=((($booking_end_time[0]*60 + $booking_end_time[1])*644)/1440);
																//$transfer_all_html.='<line x1="'.$start_point.'" y1="0" x2="'.$end_point.'" y2="0" class="'.rand(1, 1000).'"/>';
																$start_angle=($booking_start_time[0]*60+$booking_start_time[1])*.5;
																$end_angle=($booking_end_time[0]*60+$booking_end_time[1])*.5;
																if($start_angle < 360 && $end_angle < 360):
																	$clock_am_div.='<path class="am_old_tour_'.$svg_t_val['id'].'" fill="green" d="'.tools::describeArc(51, 37, 31, $start_angle, $end_angle).'"></path>';
																	$clock_pm_div.='<path class="pm_old_tour_'.$svg_t_val['id'].'" fill="green" d=""></path>';
																elseif($start_angle < 360 && $end_angle > 359):
																	$clock_am_div.='<path class="am_old_tour_'.$svg_t_val['id'].'" fill="green" d="'.tools::describeArc(51, 37, 31, $start_angle, 359).'"></path>';
																	$clock_pm_div.='<path class="pm_old_tour_'.$svg_t_val['id'].'" fill="green" d="'.tools::describeArc(51, 37, 31, 360, $end_angle).'"></path>';
																elseif($start_angle > 359 && $end_angle < 720):
																	$clock_am_div.='<path class="am_old_tour_'.$svg_t_val['id'].'" fill="green" d=""></path>';
																	$clock_pm_div.='<path class="pm_old_tour_'.$svg_t_val['id'].'" fill="green" d="'.tools::describeArc(51, 37, 31, $start_angle, $end_angle).'"></path>';
																endif;
															endif;
														endforeach;
													endif;
												endforeach;
												$transfer_all_html.='<div class="change_clock_am_div">';
													$transfer_all_html.='<input type="checkbox" checked data-toggle="toggle" data-on="AM" data-off="PM" data-onstyle="primary" data-offstyle="danger" class="toggle-demo" onchange="change_clock($(this))">';
												$transfer_all_html.='</div>';
												$transfer_all_html.='<div class="clock clock_am_div">';
													$transfer_all_html.='<svg>';
														$transfer_all_html.=$clock_am_div;
													$transfer_all_html.='</svg>';
												$transfer_all_html.='</div>';
												$transfer_all_html.='<div class="clock clock_pm_div">';
													$transfer_all_html.='<svg>';
														$transfer_all_html.=$clock_pm_div;
													$transfer_all_html.='</svg>';
												$transfer_all_html.='</div>';
												//$transfer_all_html.='<img src="assets/img/final_rular.png" border="0" alt="">';
											$transfer_all_html.='</div>';
										$transfer_all_html.='</div>';
										$transfer_all_html.=$transfer_prev_html;
										$transfer_all_html.='<div class="clearfix"></div>';
									$transfer_all_html.='</div>';
									$transfer_svg_line='';
								endif;
								$transfer_svg_line=$transfer_date=$transfer_prev_html='';
							endif;
						endforeach;
					endif;
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
						$country_city_rcd_html.='<input type="hidden" name="search_counter" class="search_counter" value="1">';
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
								<select name="selected_airport'.$server_data['data']['city'][$country_key].'"  id="selected_airport'.$server_data['data']['city'][$country_key].'" class="form-control ">';
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
					$country_city_rcd_html.='<div id="" class="all_rcd_row">'.$transfer_all_html.'</div>';
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