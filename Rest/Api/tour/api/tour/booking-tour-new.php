<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
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
				$tour_prev_html=$tour_all_html=$tour_date=$tour_svg_line=$tour_marquee_html='';
				if(isset($server_data['data']['booking_details_list']) && $server_data['data']['booking_details_list']!=""):
					$booking_details_list=$server_data['data']['booking_details_list'];
					if(isset($booking_details_list) && !empty($booking_details_list)):
						foreach($booking_details_list['booking_destination_list'] as $b_key=>$b_val):
							if(isset($b_val['booking_tour_list']) && !empty($b_val['booking_tour_list'])):
								foreach($b_val['booking_tour_list'] as $t_key=>$t_val):
									if($b_val['country_id']==$counrty_val && $b_val['city_id']==$server_data['data']['city'][$country_key]):
										if($tour_date!="" && $tour_date!=$t_val['booking_start_date']):
											ob_start();
?>
											<div class="each_tour_date_div_<?php echo $tour_date;?> each_tour_date_div" data-date_time="<?php echo strtotime($tour_date);?>">
												<div class="col-md-12 date_heading_div" onclick="hide_show_tour_details($(this))">
													<h4><?php echo date("F j, Y", strtotime($tour_date));?></h4>
											<?php
													if($tour_marquee_html!=""):
											?>
														<marquee behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();"><?php echo $tour_marquee_html;?></marquee>
											<?php
													endif;
											?>
													<!-- <div class="clock_img_div"> -->
											<?php
														$clock_am_div=$clock_pm_div='';
														//foreach($booking_details_list['booking_destination_list'] as $svg_b_key=>$svg_b_val):
															/*if(isset($svg_b_val['booking_transfer_list']) && !empty($svg_b_val['booking_transfer_list'])):
																foreach($svg_b_val['booking_transfer_list'] as $svg_t_key=>$svg_t_val):
																	if($tour_date==$svg_t_val['booking_start_date']):
																		$booking_start_time = explode(':', $svg_t_val['pickup_time']);
																		$start_point=((($booking_start_time[0]*60 + $booking_start_time[1])*644)/1440);
																		$booking_end_time = explode(':', $svg_t_val['dropoff_time']);
																		$end_point=((($booking_end_time[0]*60 + $booking_end_time[1])*644)/1440);
																		//$transfer_all_html.='<line x1="'.$start_point.'" y1="0" x2="'.$end_point.'" y2="0" class="'.rand(1, 1000).'"/>';
																		$start_angle=($booking_start_time[0]*60+$booking_start_time[1])*.5;
																		$end_angle=($booking_end_time[0]*60+$booking_end_time[1])*.5;
																		if($start_angle < 360 && $end_angle < 360):
																			$clock_am_div.='<path class="am_old_transfer_'.$svg_t_val['id'].'" fill="green" d="'.tools::describeArc(30, 17.5, 17, $start_angle, $end_angle).'"></path>';
																			$clock_pm_div.='<path class="pm_old_transfer_'.$svg_t_val['id'].'" fill="green" d=""></path>';
																		elseif($start_angle < 360 && $end_angle > 359):
																			$clock_am_div.='<path class="am_old_transfer_'.$svg_t_val['id'].'" fill="green" d="'.tools::describeArc(30, 17.5, 17, $start_angle, 359).'"></path>';
																			$clock_pm_div.='<path class="pm_old_transfer_'.$svg_t_val['id'].'" fill="green" d="'.tools::describeArc(30, 17.5, 17, 360, $end_angle).'"></path>';
																		elseif($start_angle > 359 && $end_angle < 720):
																			$clock_am_div.='<path class="am_old_transfer_'.$svg_t_val['id'].'" fill="green" d=""></path>';
																			$clock_pm_div.='<path class="pm_old_transfer_'.$svg_t_val['id'].'" fill="green" d="'.tools::describeArc(30, 17.5, 17, $start_angle, $end_angle).'"></path>';
																		endif;
																	endif;
																endforeach;
															endif;*/
															/*if(isset($svg_b_val['booking_tour_list']) && !empty($svg_b_val['booking_tour_list'])):
																foreach($svg_b_val['booking_tour_list'] as $svg_t_key=>$svg_t_val):
																	if($tour_date==$svg_t_val['booking_start_date']):
																		$booking_start_time = explode(':', $svg_t_val['pickup_time']);
																		$start_point=((($booking_start_time[0]*60 + $booking_start_time[1])*644)/1440);
																		$booking_end_time = explode(':', $svg_t_val['dropoff_time']);
																		$end_point=((($booking_end_time[0]*60 + $booking_end_time[1])*644)/1440);
																		//$transfer_all_html.='<line x1="'.$start_point.'" y1="0" x2="'.$end_point.'" y2="0" class="'.rand(1, 1000).'"/>';
																		$start_angle=($booking_start_time[0]*60+$booking_start_time[1])*.5;
																		$end_angle=($booking_end_time[0]*60+$booking_end_time[1])*.5;
																		if($start_angle < 360 && $end_angle < 360):
																			$clock_am_div.='<path class="am_old_tour_'.$svg_t_val['id'].'" fill="green" d="'.tools::describeArc(30, 17.5, 17, $start_angle, $end_angle).'"></path>';
																			$clock_pm_div.='<path class="pm_old_tour_'.$svg_t_val['id'].'" fill="green" d=""></path>';
																		elseif($start_angle < 360 && $end_angle > 359):
																			$clock_am_div.='<path class="am_old_tour_'.$svg_t_val['id'].'" fill="green" d="'.tools::describeArc(30, 17.5, 17, $start_angle, 359).'"></path>';
																			$clock_pm_div.='<path class="pm_old_tour_'.$svg_t_val['id'].'" fill="green" d="'.tools::describeArc(30, 17.5, 17, 360, $end_angle).'"></path>';
																		elseif($start_angle > 359 && $end_angle < 720):
																			$clock_am_div.='<path class="am_old_tour_'.$svg_t_val['id'].'" fill="green" d=""></path>';
																			$clock_pm_div.='<path class="pm_old_tour_'.$svg_t_val['id'].'" fill="green" d="'.tools::describeArc(30, 17.5, 17, $start_angle, $end_angle).'"></path>';
																		endif;
																	endif;
																endforeach;
															endif;
														endforeach;*/
											?>
														<!-- <div class="change_clock_am_div">
															<input type="checkbox" checked data-toggle="toggle" data-on="AM" data-off="PM" data-onstyle="primary" data-offstyle="danger" class="toggle-demo" onchange="change_clock($(this))">
														</div>
														<div class="clock clock_am_div">
															<svg>
																<?php echo $clock_am_div;?>
															</svg>
														</div>
														<div class="clock clock_pm_div">
															<svg>
																<?php echo $clock_pm_div;?>
															</svg>
														</div> -->
														<!-- <img src="assets/img/final_rular.png" border="0" alt=""> -->
													<!-- </div> -->
												</div>
												<?php echo $tour_prev_html;?>
												<div class="clearfix"></div>
											</div>
<?php
											$concat_tour_all_html=ob_get_clean();
											$tour_all_html.=$concat_tour_all_html;
											$tour_prev_html='';
										endif;
										$tour_date=$t_val['booking_start_date'];
										$find_tour_details = tools::find("first", TM_TOURS, '*', "WHERE id=:id", array(":id"=>$t_val['tour_id']));
										$tour_type="";
										if($find_tour_details['tour_type']!=""):
											$tour_type_attributes = tools::find("first", TM_ATTRIBUTES, "GROUP_CONCAT(attribute_name SEPARATOR ', ') as tour_type", "WHERE id IN (".$find_tour_details['tour_type'].") ", array());
											$tour_type=$tour_type_attributes['tour_type'];
										endif;
										$find_tour_offer_details = tools::find("first", TM_OFFERS, '*', "WHERE id=:id", array(":id"=>$t_val['offer_id']));
										$find_tour_offer_list = tools::find("all", TM_OFFERS, '*', "WHERE tour_id=:tour_id AND service_type=:service_type", array(":tour_id"=>$find_tour_details['id'], ':service_type'=>$find_tour_offer_details['service_type']));
										$nationality_charge=($t_val['price'] * $t_val['nationality_addon_percentage'])/100;
										$agent_commision=($t_val['price'] * $t_val['agent_markup_percentage'])/100;
										$each_tour_price=$t_val['price']+$agent_commision+$nationality_charge;
										$loginTime = strtotime($t_val['pickup_time'].':00');
										$logoutTime = strtotime($t_val['dropoff_time'].':00');
										$diff = $logoutTime - $loginTime;
										$hour=$diff/3600;
										ob_start();
?>
										<div class="form-group col-md-12 each_tour_row_outer">
											<div style="border: 1px solid #dd625e;background-color: #dd625e;margin: 10px 0 0 0;border-radius: 10px 10px 0 0;">
												<div class="col-md-3" style="font-weight:bold;color:#000;border:0px solid red;">Tour Title</div>
												<!-- <div class="col-md-2" style="font-weight:bold;color:#000;border:0px solid red;">Tour Type</div> -->
												<div class="col-md-1" style="font-weight:bold;color:#000;border:0px solid red;text-align:center;">Availability</div>
												<div class="col-md-2" style="font-weight:bold;color:#000;border:0px solid red;text-align:center;">Rate</div>
												<div class="col-md-4" style="font-weight:bold;color:#000;border:0px solid red;">Tour Details</div>
												<div class="clearfix"></div>
											</div>
											<div style="padding: 5px 0px 5px 0;border: 1px solid #dd625e;border-radius: 0 0 10px 10px;">
												<div class="col-md-3" style="font-weight:bold;">
													<div><?php echo $find_tour_details['tour_title'];?></div>
													<?php
													if($find_tour_details['tour_images']!=""):
														$image_arr=explode(",", $find_tour_details['tour_images']);
													?>
														<img src="<?php echo TOUR_IMAGE_PATH."thumb/".$image_arr[0]?>" border="0" alt="" style="width:150px;" onerror="this.remove;">
													<?php
													else:
														echo "N/A";
													endif;
													?>
												</div>
												<!-- <div class="col-md-2" style="font-weight:bold;">'.$find_tour_details['tour_service'].'</div> -->
												<div class="col-md-1" style="font-weight:bold;text-align:center;">
									<?php
												if($t_val['avalibility_status']=="A"):
									?>
													<button type="button" class="btn btn-success next-step">AVAILABLE</button>
									<?php
												else:
									?>
													<button type="button" class="btn btn-danger next-step">On Request</button>
									<?php
												endif;
									?>
												</div>
												<div class="col-md-2 default_price_div" style="font-weight:bold;text-align:center;" data-default_price="<?php echo $default_currency['currency_code'].number_format($each_tour_price, 2,".",",");?>"><?php echo $default_currency['currency_code'].number_format($each_tour_price, 2,".",",");?></div>
												<div class="col-md-6 calculate_tour_time">
													<img src="assets/img/delete.png" width="12" height="18" border="0" alt="Delete" class="delete_tour_row" onclick="delete_tour_row($(this))">
													<input type="hidden" name="selected_booking_tour_date[<?php echo $b_val['city_id'];?>][<?php echo $t_val['tour_id'];?>][old<?php echo $t_key;?>]" id="selected_booking_tour_date[<?php echo $b_val['city_id'];?>][<?php echo $t_val['tour_id'];?>][old<?php echo $t_key;?>]" value="<?php echo $t_val['booking_start_date'];?>" class="selected_booking_tour_date">
													<input type="hidden" name="selected_service_type[<?php echo $b_val['city_id'];?>][<?php echo $t_val['tour_id'];?>][old<?php echo $t_key;?>]" id="selected_service_type[<?php echo $b_val['city_id'];?>][<?php echo $t_val['tour_id'];?>][old<?php echo $t_key;?>]" value="<?php echo $find_tour_offer_details['service_type'];?>" class="selected_service_type">
													<strong>Tour Type: </strong><?php echo $tour_type;?><br>
													<input type="hidden" name="selected_tour_type[<?php echo $b_val['city_id'];?>][<?php echo $t_val['tour_id'];?>][old<?php echo $t_key;?>]" id="tour_type[<?php echo $b_val['city_id'];?>][<?php echo $t_val['tour_id'];?>][old<?php echo $t_key;?>]" value="<?php echo $find_tour_details['tour_type'];?>" class="tour_type">
													<strong>From: </strong><?php echo date("h:i A", strtotime($t_val['pickup_time']));?><?php //echo tools::module_date_format($t_val['booking_start_date']);?><input type="time" name="selected_pickuptime[<?php echo $b_val['city_id'];?>][<?php echo $t_val['tour_id'];?>][old<?php echo $t_key;?>]" id="selected_pickuptime[<?php echo $b_val['city_id'];?>][<?php echo $t_val['tour_id'];?>][old<?php echo $t_key;?>]" value="<?php echo $t_val['pickup_time'];?>" class="pickuptime" onkeyup="calculate_tour_time($(this), 'p')" style="display:none"><br>
													<strong>To: </strong><?php echo date("h:i A", strtotime($t_val['dropoff_time']));?><?php //echo tools::module_date_format($t_val['booking_start_date']);?><input type="time" name="selected_dropofftime[<?php echo $b_val['city_id'];?>][<?php echo $t_val['tour_id'];?>][old<?php echo $t_key;?>]" id="selected_dropofftime[<?php echo $b_val['city_id'];?>][<?php echo $t_val['tour_id'];?>][old<?php echo $t_key;?>]" value="<?php echo $t_val['dropoff_time'];?>" class="dropofftime" onkeyup="calculate_tour_time($(this), 'd')" style="display:none"><br>
													<strong>Time: </strong><span class="calculated_time_diff"><?php echo $hour;?> hours</span><br>
													<input type="hidden" name="svg_path_id_input_hidden" class="svg_path_id_input" value="old_tour_<?php echo $t_val['id'];?>">
												</div>
												<div class="clearfix"></div>
												<div class="col-md-12">
												</div>
												<div class="clearfix"></div>
												<div class="col-md-12">
													<a href="<?php echo DOMAIN_NAME_PATH_ADMIN.'edit_tour?tour_id='.base64_encode($find_tour_details['id']);?>" target="_blank" style="font-size:14px;"><b>MORE INFO</b></a> | <a href="javascript:void(0);" onclick="show_offers(\'tour<?php echo $t_val['booking_start_date'].'-old-'.$find_tour_details['id'];?>\', $(this));" style="font-size:14px;"><b>VIEW AVAILABLE OFFERS</b></a>
												</div>
												<div class="clearfix"></div>
												<div id="tour<?php echo $t_val['booking_start_date'].'-old-'.$find_tour_details['id'];?>" class="tour_offer_cls tour<?php echo $t_val['booking_start_date'].'-old-'.$find_tour_details['id'];?>">
													<div style="border:1px solid gray;background-color:gray;margin-top:0px;">
														<div class="col-md-1" style="font-weight:bold;color:#fff;">#</div>
														<div class="col-md-3" style="font-weight:bold;color:#fff;">Offer Title</div>
														<div class="col-md-3" style="font-weight:bold;color:#fff;">Service Type</div>
														<div class="col-md-3" style="font-weight:bold;color:#fff;">Capacity</div>
														<div class="col-md-2" style="font-weight:bold;color:#fff;text-align:center;">Total Amount</div>
														<div class="clearfix"></div>
													</div>
										<?php
													foreach($find_tour_offer_list as $list_key=>$list_val):
														if($list_val['id']==$find_tour_offer_details['id']):
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
																	$post_data['data']['tour_id']=$t_val['tour_id'];
																	$post_data['data']['booking_start_date']=$t_val['booking_start_date'];
																	$post_data['data']['booking_end_date']=$t_val['booking_start_date'];
																	$post_data_str=json_encode($post_data);
																	$ch = curl_init();
																	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
																	curl_setopt($ch, CURLOPT_HEADER, false);
																	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
																	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
																	curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.BOOKING_API_PATH."booking/booked-tour.php");
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
														$marque_id='tour_marque_'.date("Ymdhis").'_'.rand(0, 100000).'_old';
										?>
														<div class="radio_button_row <?php echo($list_val['id']==$find_tour_offer_details['id'] ? "radio_button_row_background" : "");?>" onclick="select_tour_radio_row($(this))" data-tour="<?php echo $find_tour_details['tour_title'];?>" data-tour_offer="<?php echo $list_val['offer_title'];?>" data-tour_offer_service="<?php echo $list_val['service_type'];?>" data-tour_offer_capacity="<?php echo $list_val['offer_capacity'];?>" data-tour_type="<?php echo $tour_type;?>" data-price="<?php echo $default_currency['currency_code'].number_format($list_val['price_per_person'], 2,".",",");?>" data-marque_id="<?php echo $marque_id;?>">
															<div class="col-md-1" style="font-weight:bold;">
										<?php
																if($offer_avaliability_status=="avaliable"):
																	$avalibility_status="A";
										?>
																	<img src="assets/img/a_icon.png" border="0" alt="Avaliable" title="Avaliable">
										<?php
																elseif($offer_avaliability_status=="not avaliable"):
																	$avalibility_status="N";
										?>
																	<img src="assets/img/r_icon.png" border="0" alt="On Request" title="On Request">
										<?php
																endif;
										?>
																<br>
																<input type="radio" name="selected_tour[<?php echo $b_val['city_id'];?>][<?php echo $t_val['tour_id'];?>][old<?php echo $t_key;?>]" class="selected_tour" onclick="change_tour_radio($(this))" value="<?php echo $b_val['city_id'].'-'.$avalibility_status.'-'.$list_val['id'];?>" data-price="<?php echo $default_currency['currency_code'].number_format($list_val['price_per_person'], 2,".",",");?>" <?php echo($list_val['id']==$find_tour_offer_details['id'] ? 'checked="checked"' : "");?> style="display:none;">
															</div>
															<div class="col-md-3" style="font-weight:bold;">
																<?php echo $list_val['offer_title'];?>
															</div>
															<div class="col-md-3"><?php echo $list_val['service_type'];?></div>
															<div class="col-md-3"><?php echo $list_val['offer_capacity'];?></div>
															<div class="col-md-2" style="font-weight:bold;color:red;text-align:center;">
																<?php echo $default_currency['currency_code'].number_format($list_val['price_per_person'], 2,".",",");?>
															</div>
															<div class="clearfix"></div>
														</div>
											<?php
														if($list_val['id']==$find_tour_offer_details['id']):
															$tour_marquee_html.='<p class="'.$marque_id.'">'.$find_tour_details['tour_title'].' - '.$list_val['offer_title'].' - '.$list_val['service_type'].' - (Capacity: '.$list_val['offer_capacity'].')'.($tour_type!=""? ' - (Tour Type: '.$tour_type.')' : '').'<br/>';
															$tour_marquee_html.=''.$default_currency['currency_code'].number_format($list_val['price_per_person'], 2,".",",").' - '.date("h:i A", strtotime($t_val['pickup_time'].":00")).' - '.date("h:i A", strtotime($t_val['dropoff_time'].":00")).'</p>';
														endif;
													endforeach;
											?>
												</div>
											</div>
										</div>
								<?php
										$tour_offer_rows_html=ob_get_clean();
										$tour_prev_html.=$tour_offer_rows_html;
									endif;
								endforeach;
								if($tour_date!="" && $tour_prev_html!=""):
									ob_start();
?>
									<div class="each_tour_date_div_<?php echo $tour_date;?> each_tour_date_div" data-date_time="<?php echo strtotime($tour_date);?>">
										<div class="col-md-12 date_heading_div" onclick="hide_show_tour_details($(this))">
											<h4><?php echo date("F j, Y", strtotime($tour_date));?></h4>
									<?php
											if($tour_marquee_html!=""):
									?>
												<marquee behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();"><?php echo $tour_marquee_html;?></marquee>
									<?php
											endif;
									?>
											<!-- <div class="clock_img_div"> -->
									<?php
												$clock_am_div=$clock_pm_div='';
												/*foreach($booking_details_list['booking_destination_list'] as $svg_b_key=>$svg_b_val):
													if(isset($svg_b_val['booking_transfer_list']) && !empty($svg_b_val['booking_transfer_list'])):
														foreach($svg_b_val['booking_transfer_list'] as $svg_t_key=>$svg_t_val):
															if($tour_date==$svg_t_val['booking_start_date']):
																$booking_start_time = explode(':', $svg_t_val['pickup_time']);
																$start_point=((($booking_start_time[0]*60 + $booking_start_time[1])*644)/1440);
																$booking_end_time = explode(':', $svg_t_val['dropoff_time']);
																$end_point=((($booking_end_time[0]*60 + $booking_end_time[1])*644)/1440);
																//$transfer_all_html.='<line x1="'.$start_point.'" y1="0" x2="'.$end_point.'" y2="0" class="'.rand(1, 1000).'"/>';
																$start_angle=($booking_start_time[0]*60+$booking_start_time[1])*.5;
																$end_angle=($booking_end_time[0]*60+$booking_end_time[1])*.5;
																if($start_angle < 360 && $end_angle < 360):
																	$clock_am_div.='<path class="am_old_transfer_'.$svg_t_val['id'].'" fill="green" d="'.tools::describeArc(30, 17.5, 17, $start_angle, $end_angle).'"></path>';
																	$clock_pm_div.='<path class="pm_old_transfer_'.$svg_t_val['id'].'" fill="green" d=""></path>';
																elseif($start_angle < 360 && $end_angle > 359):
																	$clock_am_div.='<path class="am_old_transfer_'.$svg_t_val['id'].'" fill="green" d="'.tools::describeArc(30, 17.5, 17, $start_angle, 359).'"></path>';
																	$clock_pm_div.='<path class="pm_old_transfer_'.$svg_t_val['id'].'" fill="green" d="'.tools::describeArc(30, 17.5, 17, 360, $end_angle).'"></path>';
																elseif($start_angle > 359 && $end_angle < 720):
																	$clock_am_div.='<path class="am_old_transfer_'.$svg_t_val['id'].'" fill="green" d=""></path>';
																	$clock_pm_div.='<path class="pm_old_transfer_'.$svg_t_val['id'].'" fill="green" d="'.tools::describeArc(30, 17.5, 17, $start_angle, $end_angle).'"></path>';
																endif;
															endif;
														endforeach;
													endif;
													if(isset($svg_b_val['booking_tour_list']) && !empty($svg_b_val['booking_tour_list'])):
														foreach($svg_b_val['booking_tour_list'] as $svg_t_key=>$svg_t_val):
															if($tour_date==$svg_t_val['booking_start_date']):
																$booking_start_time = explode(':', $svg_t_val['pickup_time']);
																$start_point=((($booking_start_time[0]*60 + $booking_start_time[1])*644)/1440);
																$booking_end_time = explode(':', $svg_t_val['dropoff_time']);
																$end_point=((($booking_end_time[0]*60 + $booking_end_time[1])*644)/1440);
																//$transfer_all_html.='<line x1="'.$start_point.'" y1="0" x2="'.$end_point.'" y2="0" class="'.rand(1, 1000).'"/>';
																$start_angle=($booking_start_time[0]*60+$booking_start_time[1])*.5;
																$end_angle=($booking_end_time[0]*60+$booking_end_time[1])*.5;
																if($start_angle < 360 && $end_angle < 360):
																	$clock_am_div.='<path class="am_old_tour_'.$svg_t_val['id'].'" fill="green" d="'.tools::describeArc(30, 17.5, 17, $start_angle, $end_angle).'"></path>';
																	$clock_pm_div.='<path class="pm_old_tour_'.$svg_t_val['id'].'" fill="green" d=""></path>';
																elseif($start_angle < 360 && $end_angle > 359):
																	$clock_am_div.='<path class="am_old_tour_'.$svg_t_val['id'].'" fill="green" d="'.tools::describeArc(30, 17.5, 17, $start_angle, 359).'"></path>';
																	$clock_pm_div.='<path class="pm_old_tour_'.$svg_t_val['id'].'" fill="green" d="'.tools::describeArc(30, 17.5, 17, 360, $end_angle).'"></path>';
																elseif($start_angle > 359 && $end_angle < 720):
																	$clock_am_div.='<path class="am_old_tour_'.$svg_t_val['id'].'" fill="green" d=""></path>';
																	$clock_pm_div.='<path class="pm_old_tour_'.$svg_t_val['id'].'" fill="green" d="'.tools::describeArc(30, 17.5, 17, $start_angle, $end_angle).'"></path>';
																endif;
															endif;
														endforeach;
													endif;
												endforeach;*/
									?>
												<!-- <div class="change_clock_am_div">
													<input type="checkbox" checked data-toggle="toggle" data-on="AM" data-off="PM" data-onstyle="primary" data-offstyle="danger" class="toggle-demo" onchange="change_clock($(this))">
												</div>
												<div class="clock clock_am_div">
													<svg>
														<?php //echo $clock_am_div;?>
													</svg>
												</div>
												<div class="clock clock_pm_div">
													<svg>
														<?php //echo $clock_pm_div;?>
													</svg>
												</div> -->
												<!-- <img src="assets/img/final_rular.png" border="0" alt=""> -->
											<!-- </div> -->
										</div>
										<?php echo $tour_prev_html;?>
										<div class="clearfix"></div>
									</div>
<?php
									$concat_tour_all_html=ob_get_clean();
									$tour_all_html.=$concat_tour_all_html;
								endif;
								$tour_svg_line=$tour_date=$tour_prev_html='';
							endif;
						endforeach;
					endif;
				endif;
				$contry_list = tools::find("first", TM_COUNTRIES, '*', "WHERE id=:id ORDER BY name ASC ", array(":id"=>$counrty_val));
				$city_list = tools::find("first", TM_CITIES, '*', "WHERE id=:id ", array(":id"=>$server_data['data']['city'][$country_key]));
				$country_name=$contry_list['name'];
				$city_name=$city_list['name'];
				if(count($server_data['data']['country'])>1 && $server_data['data']['sort_order']=="" && $server_data['data']['type']!=3 && $server_data['data']['type']!=2):
					$city_tab_html.='<div class="col-sm-3 cls_each_city_tour_tab_div '.($country_key==0 ? "cls_each_city_tab_div_active" : "").'" data-tab_id="tour_city'.$server_data['data']['city'][$country_key].'" onclick="change_city_tour($(this))">'.$city_name.'</div>';
				endif;
				$country_city_rcd_html.='<div class="each_tour_tab_content '.($country_key==0 ? "active_each_tab_content" : "").'" id="tour_city'.$server_data['data']['city'][$country_key].'" data-city_id="'.$server_data['data']['city'][$country_key].'" data-country_id="'.$counrty_val.'">';
					$country_city_rcd_html.='<div class="col-md-12 heading_count_rcd">';
						$country_city_rcd_html.='<p>Your search for <font color="red"><b>'.$country_name.'</b></font>, <font color="red"><b>'.$city_name.'</b></font> for <font color="red"><b>'.tools::module_date_format($checkin_date_on_city).' - '.tools::module_date_format($checkout_date_on_city).'</b></font> for <font color="red"><b>'.$total_person.' Passenger(s)</b></font></p>';
					$country_city_rcd_html.='</div>';
					$country_city_rcd_html.='<h3>Create New Tour</h3>';
					$country_city_rcd_html.='<form name="form_third_step" id="form_third_step" class="form_third_step" method="POST" onsubmit="filter_tour_search($(this), '.$server_data['data']['city'][$country_key].');return false;" data-country_id="'.$counrty_val.'">';
						$country_city_rcd_html.='<input type="hidden" name="search_counter" class="search_counter" value="1">';
						$country_city_rcd_html.='<div class="form-group col-sm-6">';
							$country_city_rcd_html.='<label for="inputName" class="control-label">Choose Day</label>';
							$country_city_rcd_html.='
								<select name="booking_tour_date'.$server_data['data']['city'][$country_key].'"  id="booking_tour_date'.$server_data['data']['city'][$country_key].'" class="form-control validate[required]">';
								$country_city_rcd_html.='<option value="">Select Date</option>';
							for($i=strtotime($checkin_date_on_city);$i<=strtotime($checkout_date_on_city);):
								$choosen_date_value=date("Y-m-d", $i);
								$choosen_date_text=date("Y-m-d", $i);
								$country_city_rcd_html.='<option value="'.$choosen_date_value.'">'.tools::module_date_format($choosen_date_text).'</option>';
								$i=$i+(24*60*60);
							endfor;
							$country_city_rcd_html.='</select>';
						$country_city_rcd_html.='</div>';
						$tour_attribute_list = tools::find("all", TM_ATTRIBUTES, '*', "WHERE status=:status ORDER BY serial_number ASC ", array(":status"=>1));
						$country_city_rcd_html.='<div class="form-group col-sm-6">';
							$country_city_rcd_html.='<label for="inputName" class="control-label">Tour Type</label>';
							$country_city_rcd_html.='
								<select name="tour_type'.$server_data['data']['city'][$country_key].'"  id="tour_type'.$server_data['data']['city'][$country_key].'" class="form-control">';
								$country_city_rcd_html.='<option value="">Select Tour Type</option>';
								foreach($tour_attribute_list as $attr_key=>$attr_val):
									$country_city_rcd_html.='<option value="'.$attr_val['id'].'">'.$attr_val['attribute_name'].'</option>';
								endforeach;
							$country_city_rcd_html.='</select>';
						$country_city_rcd_html.='</div>';
						$country_city_rcd_html.='<div class="form-group col-sm-6">';
							$country_city_rcd_html.='<label for="inputName" class="control-label">Pickup Time</label>';
							$country_city_rcd_html.='<input type="time" class="form-control" name="pick_time'.$server_data['data']['city'][$country_key].'" id="pick_time'.$server_data['data']['city'][$country_key].'" value="" >';
						$country_city_rcd_html.='</div>';
						$country_city_rcd_html.='<div class="form-group col-sm-6">';
							$country_city_rcd_html.='<label for="inputName" class="control-label">Service Type</label>';
							$country_city_rcd_html.='
								<select name="selected_tour_service_type'.$server_data['data']['city'][$country_key].'"  id="selected_tour_service_type'.$server_data['data']['city'][$country_key].'" class="form-control">';
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
					$country_city_rcd_html.='<div id="" class="all_rcd_row">'.$tour_all_html.'</div>';
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