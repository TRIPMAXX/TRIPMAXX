<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	//$server_data=json_decode('{"token":{"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE1MjcxNjgyMjcsImp0aSI6IkdNSW9KU0pjdXBJQzErVE45QkhoWE1PdzRSQ1BHUlFtYlVwYWFRN3dGYkU9IiwiaXNzIjoiaHR0cDpcL1wvbG9jYWxob3N0XC90cmlwbWF4eFwvUmVzdFwvQXBpXC90cmFuc2ZlclwvYXBpXC8iLCJuYmYiOjE1MjcxNjgyMjcsImV4cCI6MTUyNzE2ODMyNywiZGF0YSI6eyJmaWxlX25hbWUiOiIxNTI3MTY4MjI3XzMyNTY4LnR4dCJ9fQ.5JAcrXMBqBEQ0iSfklaqxC4CxgAZP0Xybtd-_9nA6JncENuSfUrBlIDkBo2Zt7CZPIN1ikTehmiX4t3STAot5w","token_timeout":100,"token_generation_time":1527168227},"data":{"booking_type":"personal","agent_name":"","checkin":"26\/04\/2018","checkout":"29\/04\/2018","country":["101","101"],"city":["5313","5312"],"number_of_night":["1","2"],"hotel_type":["",""],"hotel_ratings":[["2","3"],["2","3"]],"first_page_hotel":["4","2"],"sel_nationality":"10","country_residance":"3","sel_currency":"1","rooms":"1","adult":["3"],"child":[""],"offset":0,"record_per_page":10,"type":"3","sort_order":"","city_id":"5313","country_id":"101","search_val":"","booking_transfer_date":"2018-04-26","pickup_dropoff_type":"1","selected_airport":"1983","arr_dept_time":"10:00","selected_service_type":"Shared","booking_details_list":{"id":"2","booking_number":"","quotation_name":"uuu","checkin_date":"2018-04-26","checkout_date":"2018-04-29","booking_type":"personal","dmc_id":"1","agent_id":"0","nationality":"10","residance_country":"3","invoice_currency":"1","number_of_rooms":"1","adult":"[\"3\"]","child":"[]","total_amount":"315.00","payment_type":"credit","payment_status":"P","payment_date":"2018-05-23 10:00:40","pay_within_days":"0","is_emailed":"0","status":"0","is_deleted":"N","creation_date":"2018-04-26 12:07:32","last_updated":"2018-05-23 13:30:40","currency_code":"INR","currency_name":"Indian","booking_supplier_list":[{"id":"2","booking_master_id":"2","supplier_id":"7","status":"1","creation_date":"2018-04-30 15:18:06","last_updated":"2018-05-11 12:37:46"}],"booking_destination_list":[{"id":"101","booking_master_id":"2","country_id":"101","city_id":"5313","no_of_night":"1","hotel_rating":"2,3","creation_date":"2018-05-23 13:30:40","last_updated":"2018-05-23 13:30:40","co_name":"India","ci_name":"24 Parganas (s)","booking_hotel_list":[{"id":"101","booking_destination_id":"101","hotel_id":"4","room_id":"9","price":"10.00","booking_start_date":"2018-04-26","booking_end_date":"2018-04-27","agent_markup_percentage":"0","currency_id":"1","avalibility_status":"A","status":"0","creation_date":"2018-05-23 13:30:40","last_updated":"2018-05-23 13:30:40"}],"booking_tour_list":[{"id":"113","booking_destination_id":"101","tour_id":"4","offer_id":"6","price":"15.00","number_of_person":"3","booking_start_date":"2018-04-26","booking_end_date":"2018-04-27","agent_markup_percentage":"0","nationality_addon_percentage":"0","avalibility_status":"N","creation_date":"2018-05-23 13:30:40","last_updated":"2018-05-23 13:30:40"}],"booking_transfer_list":[{"id":"66","booking_destination_id":"101","transfer_id":"6","offer_id":"9","price":"20.00","number_of_person":"3","booking_start_date":"2018-04-26","booking_end_date":"2018-04-27","agent_markup_percentage":"0","nationality_addon_percentage":"0","avalibility_status":"N","creation_date":"2018-05-23 13:30:40","last_updated":"2018-05-23 13:30:40"}]},{"id":"102","booking_master_id":"2","country_id":"101","city_id":"5312","no_of_night":"2","hotel_rating":"2,3","creation_date":"2018-05-23 13:30:40","last_updated":"2018-05-23 13:30:40","co_name":"India","ci_name":"24 Parganas (n)","booking_hotel_list":[{"id":"102","booking_destination_id":"102","hotel_id":"2","room_id":"5","price":"20.00","booking_start_date":"2018-04-27","booking_end_date":"2018-04-29","agent_markup_percentage":"0","currency_id":"1","avalibility_status":"A","status":"0","creation_date":"2018-05-23 13:30:40","last_updated":"2018-05-23 13:30:40"}],"booking_tour_list":[{"id":"115","booking_destination_id":"102","tour_id":"2","offer_id":"5","price":"30.00","number_of_person":"3","booking_start_date":"2018-04-27","booking_end_date":"2018-04-29","agent_markup_percentage":"0","nationality_addon_percentage":"0","avalibility_status":"A","creation_date":"2018-05-23 13:30:40","last_updated":"2018-05-23 13:30:40"},{"id":"114","booking_destination_id":"102","tour_id":"3","offer_id":"4","price":"200.00","number_of_person":"3","booking_start_date":"2018-04-27","booking_end_date":"2018-04-29","agent_markup_percentage":"0","nationality_addon_percentage":"0","avalibility_status":"A","creation_date":"2018-05-23 13:30:40","last_updated":"2018-05-23 13:30:40"}],"booking_transfer_list":[{"id":"67","booking_destination_id":"102","transfer_id":"5","offer_id":"8","price":"20.00","number_of_person":"3","booking_start_date":"2018-04-27","booking_end_date":"2018-04-29","agent_markup_percentage":"0","nationality_addon_percentage":"0","avalibility_status":"N","creation_date":"2018-05-23 13:30:40","last_updated":"2018-05-23 13:30:40"}]}]}}}', true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		if(isset($server_data['data']) && isset($server_data['data']['country']) && !empty($server_data['data']['country'])):
			$offset=0;
			if(isset($server_data['data']['offset']) && $server_data['data']['offset']!=""):
				$offset=$server_data['data']['offset'];
			endif;
			$limit=RECORD_PER_PAGE;
			if(isset($server_data['data']['record_per_page']) && $server_data['data']['record_per_page']!=""):
				$limit=$server_data['data']['record_per_page'];
			endif;
			$total_person=0;
			foreach($server_data['data']['adult'] as $key_adult=>$val_adult):
				$total_person=$total_person+$val_adult;
			endforeach;
			foreach($server_data['data']['child'] as $key_child=>$val_child):
				$total_person=$total_person+$val_child;
			endforeach;
			$city_tab_html='';
			$transfer_list_html='';			
			$country_city_rcd_html='';
			$heading_count_rcd=0;
			$search_query="";
			$add_day=0;
			$default_currency=tools::find("first", TM_SETTINGS." as s, ".TM_CURRENCIES." as c", 'c.*', "WHERE c.id=s.default_currency ", array());
			$markup_percentage=0;
			$nationality_addon_percentage=0;
			$search_counter=1;
			if(isset($server_data['data']['search_counter']) && $server_data['data']['search_counter']!=""):
				$search_counter=$server_data['data']['search_counter'];
			endif;
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
							$markup_percentage=$return_data_agent_arr['results']['transfer_price'];
						endif;
					endif;
				endif;
			endif;
			$find_selected_airport = tools::find("first", TM_AIRPORTS, '*', "WHERE id=:id ", array(":id"=>$server_data['data']['selected_airport']));
			$find_pickup_dropoff_type = tools::find("first", TM_ATTRIBUTES, '*', "WHERE id=:id ", array(":id"=>$server_data['data']['pickup_dropoff_type']));
			$pickuptime=$dropofftime="";
			$pickupdate=$dropoffdate=$server_data['data']['booking_transfer_date'];
			if($find_pickup_dropoff_type['id']==1)
			{
				$pickup_time_str=strtotime($server_data['data']['booking_transfer_date']." ".$server_data['data']['arr_dept_time'].":00")+($server_data['data']['threshold_booking_time']['threshold_booking_time']*60*60);
				$pickuptime=date("H:i", $pickup_time_str);
				$dropoffdate=$pickupdate=date("Y-m-d", $pickup_time_str);
			}
			elseif($find_pickup_dropoff_type['id']==4)
			{
				$dropoff_time_str=strtotime($server_data['data']['booking_transfer_date']." ".$server_data['data']['arr_dept_time'])-($server_data['data']['threshold_booking_time']['threshold_booking_time']*60*60);
				$dropofftime=date("H:i", $dropoff_time_str);
				$pickupdate=$dropoffdate=date("Y-m-d", $dropoff_time_str);
			}
			foreach($server_data['data']['country'] as $country_key=>$counrty_val):	
				$transfer_first_row=1;
				$order_by='ORDER BY t.id DESC';
				$service_where="";
				if(isset($server_data['data']['city_id']) && $server_data['data']['city_id']!=""):
					if(isset($server_data['data']['country_id']) && $server_data['data']['country_id']!="" && $counrty_val==$server_data['data']['country_id'] && $server_data['data']['city_id']==$server_data['data']['city'][$country_key]):
						if(isset($server_data['data']['sort_order']) && $server_data['data']['sort_order']!=""):
							if($server_data['data']['sort_order']=="price"):
								$order_by='ORDER BY t.id DESC';
							elseif($server_data['data']['sort_order']=="name"):
								$order_by='ORDER BY t.transfer_title ASC';
							endif;
						endif;

						if(isset($server_data['data']['search_val']) && $server_data['data']['search_val']!=""):
							$search_query="AND (transfer_title LIKE :transfer_title OR transfer_service LIKE :transfer_service OR service_note LIKE :service_note) ";
							$execute[':transfer_title']="%".$server_data['data']['search_val']."%";
							$execute[':transfer_service']="%".$server_data['data']['search_val']."%";
							$execute[':service_note']="%".$server_data['data']['search_val']."%";
						endif;
						$find_transfer_ids=tools::find("first", TM_OFFERS, 'GROUP_CONCAT(DISTINCT(transfer_id)) as transfer_ids', "WHERE status=:status AND service_type=:service_type", array(":status"=>1, ":service_type"=>$server_data['data']['selected_service_type']));
						if(!empty($find_transfer_ids) && $find_transfer_ids['transfer_ids']!=""):
							$search_query=" AND t.id IN (".$find_transfer_ids['transfer_ids'].") ";
						endif;
						$search_query.=" AND allow_pickup_type=:allow_pickup_type ";
						$execute[':allow_pickup_type']=$server_data['data']['pickup_dropoff_type'];
						$service_where=" AND service_type=:service_type ";
						$offer_exe[':service_type']=$server_data['data']['selected_service_type'];
					else:
						continue;
					endif;
				endif;
				$checkin_date_obj=date_create_from_format("d/m/Y", $server_data['data']['checkin']);
				$checkin_date=date_format($checkin_date_obj, "Y-m-d");
				$checkin_date_on_city=date("Y-m-d", strtotime($checkin_date)+(24*60*60*$add_day));
				$add_day=$add_day+$server_data['data']['number_of_night'][$country_key];
				$checkout_date_on_city=date("Y-m-d", strtotime($checkin_date_on_city)+(24*60*60*$server_data['data']['number_of_night'][$country_key]));
				$transfer_list_html='';
				$each_first_price="--";
				$execute[':co_id']=$counrty_val;
				$execute[':ci_id']=$server_data['data']['city'][$country_key];
				if(isset($server_data['data']['booking_details_list']) && $server_data['data']['booking_details_list']!=""):
					$booking_details_list=$server_data['data']['booking_details_list'];
				endif;
				$edit_avalibility_status="";
				$transfer_list = tools::find("all", TM_TRANSFER." as t, ".TM_COUNTRIES." as co, ".TM_STATES." as s, ".TM_CITIES." as ci", 't.*, co.name as co_name, s.name as s_name, ci.name as ci_name', "WHERE t.country=co.id AND t.state=s.id AND t.city=ci.id AND co.id=:co_id AND ci.id=:ci_id ".$search_query.$order_by." LIMIT ".$offset.", ".$limit." ", $execute);
				if(!empty($transfer_list)):
					$country_name=$transfer_list[0]['co_name'];
					$city_name=$transfer_list[0]['ci_name'];
					$total_transfer=count($transfer_list);
					foreach($transfer_list as $transfer_key=>$transfer_val):
						$each_first_price="--";
						$selected_first_price="";
						$offer_exe[':transfer_id']=$transfer_val['id'];
						$offer_where="WHERE transfer_id=:transfer_id ";
						$offers_list = tools::find("all", TM_OFFERS, '*', $offer_where.$service_where, $offer_exe);
						$offer_html='';
						$transfer_avalibility_status="";
						$transfer_edit_avalibility_status="";
						if(!empty($offers_list)):
							foreach($offers_list as $offer_key=>$offer_val):
								$edit_avalibility_status="";
								if(isset($booking_details_list) && !empty($booking_details_list)):
									foreach($booking_details_list['booking_destination_list'] as $b_key=>$b_val):
										if(isset($b_val['booking_transfer_list']) && !empty($b_val['booking_transfer_list'])):
											foreach($b_val['booking_transfer_list'] as $t_key=>$t_val):
												if($b_val['country_id']==$counrty_val && $b_val['city_id']==$server_data['data']['city'][$country_key] && $t_val['transfer_id']==$transfer_val['id'] && $t_val['offer_id']==$offer_val['id'] && $edit_avalibility_status==""):
													$edit_avalibility_status=$t_val['avalibility_status'];
													$transfer_edit_avalibility_status=$t_val['avalibility_status'];
													break;
												endif;
											endforeach;
										endif;
									endforeach;
								endif;
								if(isset($server_data['data']['booking_type']) && $server_data['data']['booking_type']=="agent" && isset($server_data['data']['agent_name']) && $server_data['data']['agent_name']!=""):
									$offer_agent_markup = tools::find("first", TM_OFFER_AGENT_MARKUP, '*', "WHERE offer_id=:offer_id AND agent_id=:agent_id ", array(":agent_id"=>$server_data['data']['agent_name'], ":offer_id"=>$offer_val['id']));
									if(!empty($offer_agent_markup)):
										$markup_percentage=$offer_agent_markup['markup_price'];
									endif;
								endif;
								$total_prev_booking=0;
								$autentication_data=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.BOOKING_API_PATH."authorized.php"));
								if(isset($autentication_data->status)):
									if($autentication_data->status=="success"):
										$post_data['token']=array(
											"token"=>$autentication_data->results->token,
											"token_timeout"=>$autentication_data->results->token_timeout,
											"token_generation_time"=>$autentication_data->results->token_generation_time
										);
										$post_data['data']['offer_id']=$offer_val['id'];
										$post_data['data']['transfer_id']=$transfer_val['id'];
										$post_data['data']['booking_start_date']=$checkin_date_on_city;
										$post_data['data']['booking_end_date']=$checkout_date_on_city;
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
								if($total_prev_booking>=$offer_val['offer_capacity']):
									if($offer_key==0)
										$transfer_avalibility_status="not avaliable";
									$offer_avaliability_status="not avaliable";
								elseif($total_prev_booking<$offer_val['offer_capacity']):
									if($offer_key==0)
										$transfer_avalibility_status="avaliable";
									$offer_avaliability_status="avaliable";
								endif;
								ob_start();
?>
								<?php
								$previous_week=0;
								$main_html='';
								$total_price=0.00;
								$offer_addon_price = tools::find("first", TM_OFFER_ADDON_PRICES, '*', "WHERE offer_id=:offer_id AND country_id=:country_id AND status=:status", array(":offer_id"=>$offer_val['id'], ":country_id"=>$counrty_val, ':status'=>1));
								if(!empty($offer_addon_price))
								{
									$nationality_addon_percentage=$offer_addon_price['addon_price'];
								}
								for($i=strtotime($checkin_date_on_city);$i<=strtotime($checkout_date_on_city);):
									$complete_date=date("Y-m-d", $i);
									$offer_price_list = tools::find("first", TM_OFFER_PRICES, '*', "WHERE offer_id=:offer_id AND start_date<=:start_date AND end_date>=:end_date AND status=:status", array(":offer_id"=>$offer_val['id'], ":start_date"=>$complete_date, ":end_date"=>$complete_date, ':status'=>1));
									if(!empty($offer_price_list)):
										$offer_day_price=$offer_price_list['price_per_person'];
									else:
										$offer_day_price=$offer_val['price_per_person'];
									endif;
									//$total_price=$total_price+$offer_day_price;
									$total_price=$offer_day_price;
									$agent_commision=($total_price * $markup_percentage)/100;
									$each_day_agent_commision=($offer_day_price * $markup_percentage)/100;
									$i=$i+(24*60*60);
									break;
								endfor;
								$nationality_charge=($total_price * $nationality_addon_percentage)/100;
								?>
								<div class="radio_button_row <?php echo(isset($edit_avalibility_status) && $edit_avalibility_status!="" ? 'radio_button_row_background' : "");?>" onclick="select_transfer_radio_row($(this))">
									<div class="col-md-1" style="font-weight:bold;">
										<?php
										if($offer_avaliability_status=="avaliable" || $edit_avalibility_status=="A"):
											$avalibility_status="A";
										?>
										<img src="assets/img/a_icon.png" border="0" alt="Avaliable" title="Avaliable">
										<?php
										elseif($offer_avaliability_status=="not avaliable" || $edit_avalibility_status=="N"):
											$avalibility_status="N";
										?>
										<img src="assets/img/r_icon.png" border="0" alt="On Request" title="On Request">
										<?php
										endif;
										?>
										<br>
										<input type="radio" name="selected_transfer[<?= $server_data['data']['city'][$country_key];?>][<?php echo $transfer_val['id'];?>][<?php echo $search_counter;?>]" class="selected_transfer" onclick="change_transfer_radio($(this))" value="<?= $server_data['data']['city'][$country_key]."-".$avalibility_status."-".$offer_val['id'];?>" data-price="<?php echo $default_currency['currency_code'].number_format($total_price+$agent_commision+$nationality_charge, 2,".",",");?>"  <?php echo(isset($edit_avalibility_status) && $edit_avalibility_status!="" ? 'checked="checked"' : "");?> style="display:none;">
									</div>
									<div class="col-md-3" style="font-weight:bold;">
										<?= $offer_val['offer_title'];?>
									</div>
									<div class="col-md-3"><?= $offer_val['service_type'];?></div>
									<div class="col-md-3"><?= $offer_val['offer_capacity'];?></div>
									<div class="col-md-2" style="font-weight:bold;color:red;text-align:center;">
									<?php echo $default_currency['currency_code'].number_format((($total_price+$agent_commision+$nationality_charge)), 2,".",",");?>
									</div>
									<div class="clearfix"></div>
								</div>
<?php
								$each_offer_html=ob_get_clean();
								$offer_html.=$each_offer_html;
								if(isset($edit_avalibility_status) && $edit_avalibility_status!="")
									$selected_first_price=$default_currency['currency_code'].number_format((($total_price+$agent_commision+$nationality_charge)), 2,".",",");
								if($each_first_price=="--")
									$each_first_price=$default_currency['currency_code'].number_format((($total_price+$agent_commision+$nationality_charge)), 2,".",",");
							endforeach;
						endif;
						ob_start();
						if($offer_html!=""):
?>
							<div class="form-group col-md-12 each_transfer_row_outer">
								<div style="border: 1px solid #dd625e;background-color: #dd625e;margin: 10px 0 0 0;border-radius: 10px 10px 0 0;">
									<div class="col-md-3" style="font-weight:bold;color:#000;border:0px solid red;">Transfer Title</div>
									<!-- <div class="col-md-2" style="font-weight:bold;color:#000;border:0px solid red;">Transfer Type</div> -->
									<div class="col-md-1" style="font-weight:bold;color:#000;border:0px solid red;text-align:center;">Availability</div>
									<div class="col-md-2" style="font-weight:bold;color:#000;border:0px solid red;text-align:center;">Rate</div>
									<div class="col-md-6" style="font-weight:bold;color:#000;border:0px solid red;">Transfer Details</div>
									<div class="clearfix"></div>
								</div>
								<div style="padding: 5px 0px 5px 0;border: 1px solid #dd625e;border-radius: 0 0 10px 10px;">
									<div class="col-md-3" style="font-weight:bold;">
										<div><?php echo $transfer_val['transfer_title'];?></div>
										<?php
										if($transfer_val['transfer_images']!=""):
											$image_arr=explode(",", $transfer_val['transfer_images']);
											//if($image_arr[0]!="" && file_exists(TRANSFER_IMAGE_PATH.$image_arr[0])):
										?>
											<img src = "<?php echo(TRANSFER_IMAGE_PATH."thumb/".$image_arr[0]);?>" border = "0" alt = "" style="width:150px" onerror="this.remove;"/>
										<?php
											/*else:
												echo "N/A";
											endif;*/
										else:
											echo "N/A";
										endif;
										?>
									</div>
									<!-- <div class="col-md-2" style="font-weight:bold;"><?php echo $transfer_val['transfer_service'];?></div> -->
									<div class="col-md-1" style="font-weight:bold;text-align:center;">
										<?php
										if($transfer_avalibility_status=="avaliable" || $transfer_edit_avalibility_status=="A"):
										?>
										<button type="button" class="btn btn-success next-step">AVAILABLE</button>
										<?php
										elseif($transfer_avalibility_status=="not avaliable" || $transfer_edit_avalibility_status=="N"):
										?>
										<button type="button" class="btn btn-danger next-step">On Request</button>
										<?php
										endif;
										?>
									</div>
									<div class="col-md-2 default_price_div" style="font-weight:bold;text-align:center;" data-default_price="<?php echo $each_first_price;?>"><?php echo(isset($selected_first_price) && $selected_first_price!="" ? $selected_first_price : $each_first_price);?></div>
									<div class="col-md-6 calculate_time">
										<img src="assets/img/delete.png" width="12" height="18" border="0" alt="Delete" class="delete_transfer_row" onclick="delete_transfer_row($(this))">
										<input type="hidden" name="selected_booking_transfer_date[<?= $server_data['data']['city'][$country_key];?>][<?php echo $transfer_val['id'];?>][<?php echo $search_counter;?>]" id="selected_booking_transfer_date[<?= $server_data['data']['city'][$country_key];?>][<?php echo $transfer_val['id'];?>][<?php echo $search_counter;?>]" value="<?php echo $server_data['data']['booking_transfer_date'];?>" class="selected_booking_transfer_date">
										<input type="hidden" name="selected_service_type[<?= $server_data['data']['city'][$country_key];?>][<?php echo $transfer_val['id'];?>][<?php echo $search_counter;?>]" id="selected_service_type[<?= $server_data['data']['city'][$country_key];?>][<?php echo $transfer_val['id'];?>][<?php echo $search_counter;?>]" value="<?php echo $server_data['data']['selected_service_type'];?>" class="selected_service_type">
										<strong>Pickup/Dropoff Type: </strong><?php echo $find_pickup_dropoff_type['attribute_name'];?><br/>
										<input type="hidden" name="selected_pickup_dropoff_type[<?= $server_data['data']['city'][$country_key];?>][<?php echo $transfer_val['id'];?>][<?php echo $search_counter;?>]" id="pickup_dropoff_type[<?= $server_data['data']['city'][$country_key];?>][<?php echo $transfer_val['id'];?>][<?php echo $search_counter;?>]" value="<?php echo(isset($find_pickup_dropoff_type['id']) ? $find_pickup_dropoff_type['id'] : "");?>" class="pickup_dropoff_type">
										<?php
										if(isset($find_selected_airport['name'])):
										?>
										<strong>Airport: </strong><?php echo $find_selected_airport['name'];?><br/>
										<?php
										endif;
										?>
										<input type="hidden" name="selected_airport[<?= $server_data['data']['city'][$country_key];?>][<?php echo $transfer_val['id'];?>][<?php echo $search_counter;?>]" id="selected_airport[<?= $server_data['data']['city'][$country_key];?>][<?php echo $transfer_val['id'];?>][<?php echo $search_counter;?>]" value="<?php echo(isset($find_selected_airport['name']) ? $find_selected_airport['name'] : "");?>" class="selected_airport">
										<?php
										if(isset($server_data['data']['arr_dept_flight_number'])):
										?>
										<strong>Flight Number and Name: </strong><?php echo $server_data['data']['arr_dept_flight_number'];?><br/>
										<?php
										endif;
										?>
										<input type="hidden" name="arr_dept_flight_number[<?= $server_data['data']['city'][$country_key];?>][<?php echo $transfer_val['id'];?>][<?php echo $search_counter;?>]" id="arr_dept_flight_number[<?= $server_data['data']['city'][$country_key];?>][<?php echo $transfer_val['id'];?>][<?php echo $search_counter;?>]" value="<?php echo(isset($server_data['data']['arr_dept_flight_number']) ? $server_data['data']['arr_dept_flight_number'] : "");?>" class="arr_dept_flight_number">
										<div>
											<div style="display:inline-block">
												<strong>Pickup: </strong><?php echo tools::module_date_format($pickupdate);?><input type="time" name="selected_pickuptime[<?= $server_data['data']['city'][$country_key];?>][<?php echo $transfer_val['id'];?>][<?php echo $search_counter;?>]" id="selected_pickuptime[<?= $server_data['data']['city'][$country_key];?>][<?php echo $transfer_val['id'];?>][<?php echo $search_counter;?>]" value="<?php echo $pickuptime;?>" class="pickuptime" onkeyup="calculate_time($(this), 'p')">
											</div>
											<div style="display:inline-block">
												<strong>Dropoff: </strong><?php echo tools::module_date_format($dropoffdate);?><input type="time" name="selected_dropofftime[<?= $server_data['data']['city'][$country_key];?>][<?php echo $transfer_val['id'];?>][<?php echo $search_counter;?>]" id="selected_dropofftime[<?= $server_data['data']['city'][$country_key];?>][<?php echo $transfer_val['id'];?>][<?php echo $search_counter;?>]" value="<?php echo $dropofftime;?>" class="dropofftime" onkeyup="calculate_time($(this), 'd')">
											</div>
										</div>
										<strong>Time: </strong><span class="calculated_time_diff">--</span>
									</div>
									<div class="clearfix"></div>
									<div class="col-md-9">
										<?php echo nl2br($transfer_val['service_note']);?>
									</div>
									<div class="clearfix"></div>
									<div class="col-md-12">
										<a href="<?php echo DOMAIN_NAME_PATH_ADMIN;?>edit_transfer?transfer_id=<?php echo base64_encode($transfer_val['id']);?>" target="_blank" style="font-size:14px;"><b>MORE INFO</b></a> | <a href="javascript:void(0);" onclick="show_transfers('transfer<?php echo $transfer_val['id'];?>');" style="font-size:14px;"><b>VIEW AVAILABLE OFFERS</b></a>
									</div>
									<div class="clearfix"></div>
									<div id="transfer<?php echo $transfer_val['id'];?>" <?php echo($transfer_first_row==1 && $offset==0 ? '' : 'style="display:none;"');?>  class="transfer_offer_cls">
										<div style="border:1px solid gray;background-color:gray;margin-top:10px;">
											<div class="col-md-1" style="font-weight:bold;color:#fff;">#</div>
											<div class="col-md-3" style="font-weight:bold;color:#fff;">Offer Title</div>
											<div class="col-md-3" style="font-weight:bold;color:#fff;">Service Type</div>
											<div class="col-md-3" style="font-weight:bold;color:#fff;">Capacity</div>
											<div class="col-md-2" style="font-weight:bold;color:#fff;text-align:center;">Total Amount</div>
											<div class="clearfix"></div>
										</div>
										<?php echo $offer_html;?>
									</div>
								</div>
							</div>
<?php
						endif;
						$each_transfer_list_html=ob_get_clean();
						$transfer_list_html.=$each_transfer_list_html;
						$transfer_first_row++;
					endforeach;
				else:
					$contry_list = tools::find("first", TM_COUNTRIES, '*', "WHERE id=:id ORDER BY name ASC ", array(":id"=>$counrty_val));
					$city_list = tools::find("first", TM_CITIES, '*', "WHERE id=:id ", array(":id"=>$server_data['data']['city'][$country_key]));
					$country_name=$contry_list['name'];
					$city_name=$city_list['name'];
					$total_transfer=0;
				endif;
				if(count($server_data['data']['country'])>1 && $server_data['data']['sort_order']=="" && $server_data['data']['type']!=3 && $server_data['data']['type']!=2):
					$city_tab_html.='<div class="col-sm-3 cls_each_city_transfer_tab_div '.($country_key==0 ? "cls_each_city_tab_div_active" : "").'" data-tab_id="transfer_city'.$server_data['data']['city'][$country_key].'" onclick="change_city_transfer($(this))">'.$city_name.'</div>';
				endif;
				if($transfer_list_html==""):
					$transfer_list_html='<div class="col-md-12 text-center no_rcd" style="padding:30px;color:red;">No '.($server_data['data']['type']==2 ? "more " : "").'record found</div>';
				endif;
				if($server_data['data']['sort_order']=="" && $server_data['data']['type']!=3 && $server_data['data']['type']!=2):
					$country_city_rcd_html.='<div class="each_transfer_tab_content '.($country_key==0 ? "active_each_tab_content" : "").'" id="transfer_city'.$server_data['data']['city'][$country_key].'" data-city_id="'.$server_data['data']['city'][$country_key].'" data-country_id="'.$counrty_val.'">';
				endif;
					$country_city_rcd_html.='<div class="col-md-12 heading_count_rcd">';
						$heading_count_rcd=$total_transfer;
						$country_city_rcd_html.='<p>Your search for <font color="red"><b>'.$country_name.'</b></font>, <font color="red"><b>'.$city_name.'</b></font> for <font color="red"><b>'.tools::module_date_format($checkin_date_on_city).'</b></font> for <font color="red"><b>'.$total_person.' Passenger(s)</b></font> fetched <font color="red"><b><span class="total_transfer_number">'.$heading_count_rcd.'</span> Transfer(s)</b></font></p>';
					$country_city_rcd_html.='</div>';
					$country_city_rcd_html.='<h3>Create New Transfer</h3>';
					$country_city_rcd_html.='<form name="form_third_step" id="form_third_step" method="POST" onsubmit="filter_transfer_search($(this), '.$server_data['data']['city'][$country_key].');return false;" data-country_id="'.$counrty_val.'">';
						$country_city_rcd_html.='<div class="form-group col-sm-6">';
							$country_city_rcd_html.='<label for="inputName" class="control-label">Choose Day</label>';
							$country_city_rcd_html.='
								<select name="booking_transfer_date'.$server_data['data']['city'][$country_key].'"  id="booking_transfer_date'.$server_data['data']['city'][$country_key].'" class="form-control">';
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
								<select name="pickup_dropoff_type'.$server_data['data']['city'][$country_key].'"  id="pickup_dropoff_type'.$server_data['data']['city'][$country_key].'" class="form-control">';
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
								<select name="selected_airport'.$server_data['data']['city'][$country_key].'"  id="selected_airport'.$server_data['data']['city'][$country_key].'" class="form-control">';
								$country_city_rcd_html.='<option value="">Choose Airport</option>';
								foreach($transfer_city_airport_list as $airport_key=>$airport_val):
									$country_city_rcd_html.='<option value="'.$airport_val['id'].'">'.$airport_val['name'].'</option>';
								endforeach;
							$country_city_rcd_html.='</select>';
						$country_city_rcd_html.='</div>';
						$country_city_rcd_html.='<div class="form-group col-sm-6">';
							$country_city_rcd_html.='<label for="inputName" class="control-label">Arrival/Departure Time</label>';
							$country_city_rcd_html.='<input type="time" class="form-control" name="arr_dept_time'.$server_data['data']['city'][$country_key].'" id="arr_dept_time'.$server_data['data']['city'][$country_key].'" value="">';
						$country_city_rcd_html.='</div>';
						$country_city_rcd_html.='<div class="form-group col-sm-6">';
							$country_city_rcd_html.='<label for="inputName" class="control-label">Service Type</label>';
							$country_city_rcd_html.='
								<select name="selected_service_type'.$server_data['data']['city'][$country_key].'"  id="selected_service_type'.$server_data['data']['city'][$country_key].'" class="form-control">';
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
					$country_city_rcd_html.='<div class="all_rcd_row">';
						$country_city_rcd_html.=$transfer_list_html;
						$country_city_rcd_html.='<div class="clearfix"></div>';
					$country_city_rcd_html.='</div>';
				if($server_data['data']['sort_order']=="" && $server_data['data']['type']!=3):
						$country_city_rcd_html.='<input type="hidden" class="transfer_list_tab_current_page" value="1"/>';
						$country_city_rcd_html.='<input type="hidden" class="transfer_list_tab_no_more_record_status" value="0"/>';
					$country_city_rcd_html.='</div>';
				endif;
			endforeach;
			$city_tab_html.='<div class="clearfix"></div>';
			$return_data['status']="success";
			$return_data['msg']="Date fetched successfully.";
			if($server_data['data']['sort_order']!="" || $server_data['data']['type']==3 || $server_data['data']['type']==2 ):
				$return_data['country_city_rcd_html']=$transfer_list_html.'<div class="clearfix"></div>';
				$return_data['post_data']['country_city_rcd_date']=$server_data['data']['booking_transfer_date'];
				$return_data['post_data']['country_city_rcd_date_time']=strtotime($server_data['data']['booking_transfer_date']);
				$return_data['post_data']['country_city_rcd_formated_date']=tools::module_date_format($server_data['data']['booking_transfer_date']);
				$return_data['post_data']['country_city_rcd_arr_dept_time']=$server_data['data']['arr_dept_time'];
				$return_data['post_data']['find_selected_airport']=$find_selected_airport;
				$return_data['post_data']['find_pickup_dropoff_type']=$find_pickup_dropoff_type;
			else:
				$return_data['country_city_rcd_html']=$country_city_rcd_html;
			endif;
			$return_data['city_tab_html']=$city_tab_html;
			$return_data['heading_count_rcd']=$heading_count_rcd;
		else:
			$return_data['status']="error";
			$return_data['msg']="Some data missing.";
		endif;
	endif;
	echo json_encode($return_data);	
?>