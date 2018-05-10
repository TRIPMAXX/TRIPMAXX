<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	//$server_data=json_decode('{"token":{"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE1MjQwNDI4NTQsImp0aSI6InErKzJ5NjRtUjJQS0pxekpsaEpTZDFVT2ZNdlpCaU5cL1hOeFp1TENiT0tzPSIsImlzcyI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvdHJpcG1heHhcL1Jlc3RcL0FwaVwvaG90ZWxcL2FwaVwvIiwibmJmIjoxNTI0MDQyODU0LCJleHAiOjE1MjQwNTI4NTQsImRhdGEiOnsiZmlsZV9uYW1lIjoiMTUyNDA0Mjg1NF8yMTA0My50eHQifX0.AnoEXhnPtDJ74bV5RfQgJqDQuWrck3SgkNphxdhYDntrf0YyOiVIFQPh-OjiMP57r-MbaE5ZU18p-0wZGcMsqQ","token_timeout":10000,"token_generation_time":1524042854},"data":{"booking_type":"personal","agent_name":"","checkin":"18\/04\/2018","checkout":"21\/04\/2018","country":["101","101"],"city":["5312","5313"],"number_of_night":["2","2"],"hotel_ratings":[["2","3"],["2","3"]],"sel_nationality":"4","country_residance":"7","sel_currency":"1","rooms":"1","adult":["1"],"child":[""],"offset":1,"record_per_page":10,"sort_order":"","city_id":"","country_id":"","search_val":"", "type":2}}', true);
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
			$city_tab_html='';
			$hotel_list_html='';			
			$country_city_rcd_html='';
			$heading_count_rcd=0;
			$search_query="";
			$add_day=0;
			$default_currency=tools::find("first", TM_SETTINGS." as s, ".TM_CURRENCIES." as c", 'c.*', "WHERE c.id=s.default_currency ", array());
			$markup_percentage=0;
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
			foreach($server_data['data']['country'] as $country_key=>$counrty_val):	
				$order_by='ORDER BY h.id DESC';
				if(isset($server_data['data']['city_id']) && $server_data['data']['city_id']!=""):
					if(isset($server_data['data']['country_id']) && $server_data['data']['country_id']!="" && $counrty_val==$server_data['data']['country_id'] && $server_data['data']['city_id']==$server_data['data']['city'][$country_key]):
						if(isset($server_data['data']['sort_order']) && $server_data['data']['sort_order']!=""):
							if($server_data['data']['sort_order']=="price"):
								$order_by='ORDER BY h.id DESC';
							elseif($server_data['data']['sort_order']=="name"):
								$order_by='ORDER BY h.hotel_name ASC';
							elseif($server_data['data']['sort_order']=="rating"):
								$order_by='ORDER BY h.rating ASC';
							endif;
						endif;

						if(isset($server_data['data']['search_val']) && $server_data['data']['search_val']!=""):
							$search_query="AND (hotel_name LIKE :hotel_name OR email_address LIKE :email_address OR postal_code LIKE :postal_code OR short_description LIKE :short_description) ";
							$execute[':hotel_name']="%".$server_data['data']['search_val']."%";
							$execute[':email_address']="%".$server_data['data']['search_val']."%";
							$execute[':postal_code']="%".$server_data['data']['search_val']."%";
							$execute[':short_description']="%".$server_data['data']['search_val']."%";
						endif;
					else:
						continue;
					endif;
				endif;
				$checkin_date_obj=date_create_from_format("d/m/Y", $server_data['data']['checkin']);
				$checkin_date=date_format($checkin_date_obj, "Y-m-d");
				$checkin_date_on_city=date("Y-m-d", strtotime($checkin_date)+(24*60*60*$add_day));
				$add_day=$add_day+$server_data['data']['number_of_night'][$country_key];
				$checkout_date_on_city=date("Y-m-d", strtotime($checkin_date_on_city)+(24*60*60*$server_data['data']['number_of_night'][$country_key]));
				$hotel_list_html='';
				$each_first_price="--";
				$execute['co_id']=$counrty_val;
				$execute['ci_id']=$server_data['data']['city'][$country_key];
				if(isset($server_data['data']['booking_details_list']) && $server_data['data']['booking_details_list']!=""):
					$booking_details_list=$server_data['data']['booking_details_list'];
				endif;
				$edit_avalibility_status="";
				$hotel_list = tools::find("all", TM_HOTELS." as h, ".TM_COUNTRIES." as co, ".TM_STATES." as s, ".TM_CITIES." as ci", 'h.*, co.name as co_name, s.name as s_name, ci.name as ci_name', "WHERE h.country=co.id AND h.state=s.id AND h.city=ci.id AND co.id=:co_id AND ci.id=:ci_id ".$search_query.$order_by." LIMIT ".$offset.", ".$limit." ", $execute);
				if(!empty($hotel_list)):
					$country_name=$hotel_list[0]['co_name'];
					$city_name=$hotel_list[0]['ci_name'];
					$total_hotel=count($hotel_list);
					foreach($hotel_list as $hotel_key=>$hotel_val):
						$room_list = tools::find("all", TM_ROOMS, '*', "WHERE hotel_id=:hotel_id ", array(":hotel_id"=>$hotel_val['id']));
						$room_html='';
						$hotel_avalibility_status="";
						$hotel_edit_avalibility_status="";
						if(!empty($room_list)):
							foreach($room_list as $room_key=>$room_val):
								$edit_avalibility_status="";
								if(isset($booking_details_list) && !empty($booking_details_list)):
									foreach($booking_details_list['booking_destination_list'] as $b_key=>$b_val):
										if(isset($b_val['booking_hotel_list']) && !empty($b_val['booking_hotel_list'])):
											foreach($b_val['booking_hotel_list'] as $h_key=>$h_val):
												if($b_val['country_id']==$counrty_val && $b_val['city_id']==$server_data['data']['city'][$country_key] && $h_val['hotel_id']==$hotel_val['id'] && $h_val['room_id']==$room_val['id']):
													$edit_avalibility_status=$h_val['avalibility_status'];
													$hotel_edit_avalibility_status=$h_val['avalibility_status'];
													break;
												endif;
											endforeach;
										endif;
									endforeach;
								endif;
								if(isset($server_data['data']['booking_type']) && $server_data['data']['booking_type']=="agent" && isset($server_data['data']['agent_name']) && $server_data['data']['agent_name']!=""):
									$room_agent_markup = tools::find("first", TM_ROOM_AGENT_MARKUP, '*', "WHERE room_id=:room_id AND agent_id=:agent_id ", array(":agent_id"=>$server_data['data']['agent_name'], ":room_id"=>$room_val['id']));
									if(!empty($room_agent_markup)):
										$markup_percentage=$room_agent_markup['markup_price'];
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
										$post_data['data']['room_id']=$room_val['id'];
										$post_data['data']['hotel_id']=$hotel_val['id'];
										$post_data['data']['booking_start_date']=$checkin_date_on_city;
										$post_data['data']['booking_end_date']=$checkout_date_on_city;
										$post_data_str=json_encode($post_data);
										$ch = curl_init();
										curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
										curl_setopt($ch, CURLOPT_HEADER, false);
										curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
										curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
										curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.BOOKING_API_PATH."booking/booked-hotel.php");
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
								$room_avaliability_status="";
								if($total_prev_booking>=$room_val['number_of_rooms']):
									if($room_key==0)
										$hotel_avalibility_status="not avaliable";
									$room_avaliability_status="not avaliable";
								elseif($total_prev_booking<$room_val['number_of_rooms']):
									if($room_key==0)
										$hotel_avalibility_status="avaliable";
									$room_avaliability_status="avaliable";
								endif;
								ob_start();
								$previous_week=0;
								$week_day_th='';
								$week_day_td='';
								$main_html='';
								$total_price=0.00;
								for($i=strtotime($checkin_date_on_city);$i<strtotime($checkout_date_on_city);):
									$complete_date=date("Y-m-d", $i);
									$room_price_list = tools::find("first", TM_ROOM_PRICES, '*', "WHERE room_id=:room_id AND start_date<=:start_date AND end_date>=:end_date AND status=:status", array(":room_id"=>$room_val['id'], ":start_date"=>$complete_date, ":end_date"=>$complete_date, ':status'=>1));
									if(!empty($room_price_list)):
										$room_day_price=$room_price_list['price_per_night'];
									else:
										$room_day_price=$room_val['price'];
									endif;
									$total_price=$total_price+$room_day_price;
									$agent_commision=($total_price * $markup_percentage)/100;
									$each_day_agent_commision=($room_day_price * $markup_percentage)/100;
									$day_num=date("N", $i);
									$day_name=date("D", $i);
									$week_num=date("W", $i);
									if($day_num==7)
										$week_num=$week_num+1;
									if($week_num!=$previous_week && $previous_week > 0):
										$main_html.='
										<table aria-describedby="example1_info" class="table table-bordered table-striped dataTable" style="width:45%;display:inline-block">
											<tbody aria-relevant="all" aria-live="polite" role="alert">
												'.$week_day_td.'
											</tbody>
										</table>';
										$week_day_th='';
										$week_day_td='';
									endif;
									if($week_day_th==""):
										$week_day_th='
											<tr role="row">
												<th valign="middle" style="background-color:gray;color: #FFF;" align="center">#</th>
											</tr>
										';
										$week_day_td='
											<tr>
												<td valign="middle" style="background-color:gray;color: #FFF;" align="center">#</td>
												<td valign="middle"> Wk '.$week_num.' </td>
											</tr>
										';
									endif;
									$week_day_th.='
										<tr role="row">
											<th valign="middle" style="background-color:gray;" align="center">'.$day_name.'</th>
										</tr>
									';
									$week_day_td.='
										<tr>
											<td valign="middle" style="background-color:gray;color: #FFF;" align="center">'.$day_name.'</td>
											<td valign="middle">'.$default_currency['currency_code'].number_format($room_day_price+$each_day_agent_commision, 2,".",",").'</td>
										</tr>
									';

									$previous_week=$week_num;
									$i=$i+(24*60*60);
								endfor;
?>
								<div style="padding:10px 0 10px 0;border:1px solid gray;">
									<div class="col-md-1" style="font-weight:bold;">
										<?php
										if($room_avaliability_status=="avaliable" || $edit_avalibility_status=="A"):
											$avalibility_status="A";
										?>
										<img src="assets/img/a_icon.png" border="0" alt="Avaliable" title="Avaliable">
										<?php
										elseif($room_avaliability_status=="not avaliable" || $edit_avalibility_status=="N"):
											$avalibility_status="N";
										?>
										<img src="assets/img/r_icon.png" border="0" alt="On Request" title="On Request">
										<?php
										endif;
										?>
										<br>
										<input type="radio" name="selected_room[<?= $server_data['data']['city'][$country_key];?>]" class="selected_room" onclick="change_room_radio($(this))" value="<?= $server_data['data']['city'][$country_key]."-".$avalibility_status."-".$room_val['id'];?>" data-price="<?php echo $default_currency['currency_code'].number_format($total_price+$agent_commision, 2,".",",");?>" <?php echo(isset($edit_avalibility_status) && $edit_avalibility_status!="" ? 'checked="checked"' : "");?>>
									</div>
									<div class="col-md-3" style="font-weight:bold;">
										<?= $room_val['room_type'];?>
										<br>
										<font color="red"><?= $room_val['room_description'];?></font>
									</div>
									<div class="col-md-2"><?= $room_val['number_of_rooms'];?></div>
									<div class="col-md-4">
									<?php
									//if($week_day_th!="" && $week_day_td!=""):
									if($week_day_td!=""):
										$main_html.='
										<table aria-describedby="example1_info" class="table table-bordered table-striped dataTable" style="width:45%;display:inline-block">
											<tbody aria-relevant="all" aria-live="polite" role="alert">
												'.$week_day_td.'
											</tbody>
										</table>';
									endif;
									echo $main_html;
									?>
									</div>
									<div class="col-md-2" style="font-weight:bold;color:red;text-align:center;"><?php echo $default_currency['currency_code'].number_format($total_price+$agent_commision, 2,".",",");?></div>
									<div class="clearfix"></div>
								</div>
<?php
								$each_room_html=ob_get_clean();
								$room_html.=$each_room_html;
								if($each_first_price=="--")
									$each_first_price=$default_currency['currency_code'].number_format($total_price+$agent_commision, 2,".",",");
							endforeach;
						endif;
						ob_start();
						if($room_html!=""):
?>
							<div class="form-group col-md-12">
								<div style="border:1px solid red;background-color:red;">
									<div class="col-md-3" style="font-weight:bold;color:#fff;">Hotel Name</div>
									<div class="col-md-2" style="font-weight:bold;color:#fff;">Rating</div>
									<div class="col-md-2" style="font-weight:bold;color:#fff;">Location</div>
									<div class="col-md-3" style="font-weight:bold;color:#fff;text-align:center;">Availability</div>
									<div class="col-md-2" style="font-weight:bold;color:#fff;text-align:center;">Rate</div>
									<div class="clearfix"></div>
								</div>
								<div style="padding:20px 0 0 0;border:1px solid red;">
									<div class="col-md-3" style="font-weight:bold;"><?php echo $hotel_val['hotel_name'];?></div>
									<div class="col-md-2" style="font-weight:bold;">
										<div class="rate_content_div" data-rate="<?php echo $hotel_val['rating'];?>"></div>
									</div>
									<div class="col-md-2" style="font-weight:bold;"><?php echo $city_name;?></div>
									<div class="col-md-3" style="font-weight:bold;text-align:center;">
										<?php
										if($hotel_avalibility_status=="avaliable" || $hotel_edit_avalibility_status=="A"):
										?>
										<button type="button" class="btn btn-success next-step">AVAILABLE</button>
										<?php
										elseif($hotel_avalibility_status=="not avaliable" || $hotel_edit_avalibility_status=="N"):
										?>
										<button type="button" class="btn btn-danger next-step">On Request</button>
										<?php
										endif;
										?>
									</div>
									<div class="col-md-2 default_price_div" style="font-weight:bold;text-align:center;" data-default_price="<?php echo $each_first_price;?>"><?php echo $each_first_price;?></div>
									<div class="clearfix"></div>
									<div class="col-md-3">
										<?php
										if($hotel_val['hotel_images']!=""):
											$image_arr=explode(",", $hotel_val['hotel_images']);
											//if($image_arr[0]!="" && file_exists(HOTEL_IMAGE_PATH.$image_arr[0])):
										?>
											<img src = "<?php echo(HOTEL_IMAGE_PATH.$image_arr[0]);?>" border = "0" alt = "" width = "250" height = "150" onerror="this.remove;"/>
										<?php
											/*else:
												echo "N/A";
											endif;*/
										else:
											echo "N/A";
										endif;
										?>
									</div>
									<div class="col-md-9">
										<?php echo nl2br($hotel_val['short_description']);?>
									</div>
									<div class="clearfix"></div>
									<div class="col-md-12">
										<a href="<?php echo DOMAIN_NAME_PATH_ADMIN;?>view_hotel_details?hotel_id=<?php echo base64_encode($hotel_val['id']);?>" target="_blank" style="font-size:16px;"><b>MORE INFO</b></a> | <a href="javascript:void(0);" onclick="show_rooms('hotel<?php echo $hotel_val['id'];?>');" style="font-size:16px;"><b>VIEW AVAILABLE ROOMS</b></a>
									</div>
									<div class="clearfix"></div>
									<div id="hotel<?php echo $hotel_val['id'];?>" style="display:none;">
										<div style="border:1px solid gray;background-color:gray;margin-top:10px;">
											<div class="col-md-1" style="font-weight:bold;color:#fff;">#</div>
											<div class="col-md-3" style="font-weight:bold;color:#fff;">Room Type</div>
											<div class="col-md-2" style="font-weight:bold;color:#fff;">Rooms</div>
											<div class="col-md-4" style="font-weight:bold;color:#fff;">Room Breakup</div>
											<div class="col-md-2" style="font-weight:bold;color:#fff;text-align:center;">Total Amount</div>
											<div class="clearfix"></div>
										</div>
										<?php echo $room_html;?>
									</div>
								</div>
							</div>
<?php
						endif;
						$each_hotel_list_html=ob_get_clean();
						$hotel_list_html.=$each_hotel_list_html;
					endforeach;
				else:
					$contry_list = tools::find("first", TM_COUNTRIES, '*', "WHERE id=:id ORDER BY name ASC ", array(":id"=>$counrty_val));
					$city_list = tools::find("first", TM_CITIES, '*', "WHERE id=:id ", array(":id"=>$server_data['data']['city'][$country_key]));
					$country_name=$contry_list['name'];
					$city_name=$city_list['name'];
					$total_hotel=0;
				endif;
				if(count($server_data['data']['country'])>1 && $server_data['data']['sort_order']=="" && $server_data['data']['type']!=3 && $server_data['data']['type']!=2):
					$city_tab_html.='<div class="col-sm-3 cls_each_city_hotel_tab_div '.($country_key==0 ? "cls_each_city_tab_div_active" : "").'" data-tab_id="city'.$server_data['data']['city'][$country_key].'" onclick="change_city_hotel($(this))">'.$city_name.'</div>';
				endif;
				if($hotel_list_html==""):
					$hotel_list_html='<div class="col-md-12 text-center no_rcd" style="padding:30px;color:red;">No '.($server_data['data']['type']==2 ? "more " : "").'record found</div>';
				endif;
				if($server_data['data']['sort_order']=="" && $server_data['data']['type']!=3 && $server_data['data']['type']!=2):
					$country_city_rcd_html.='<div class="each_hotel_tab_content '.($country_key==0 ? "active_each_tab_content" : "").'" id="city'.$server_data['data']['city'][$country_key].'" data-city_id="'.$server_data['data']['city'][$country_key].'" data-country_id="'.$counrty_val.'">';
				endif;
					$country_city_rcd_html.='<div class="col-md-6 heading_count_rcd">';
						$heading_count_rcd=$total_hotel;
						$country_city_rcd_html.='<p>Your search for <font color="red"><b>'.$country_name.'</b></font>, <font color="red"><b>'.$city_name.'</b></font> for <font color="red"><b>'.$server_data['data']['number_of_night'][$country_key].' Night(s)</b></font> fetched <font color="red"><b><span class="total_hotel_number">'.$heading_count_rcd.'</span> Hotel(s)</b></font></p>';
					$country_city_rcd_html.='</div>';
					$country_city_rcd_html.='<div class="col-md-6">';
						$country_city_rcd_html.='<p><b>SORT BY:</b>&nbsp;&nbsp;&nbsp;';
						//$country_city_rcd_html.='<input type = "radio" name = "sort" value="price" onchange="change_order($(this))" data-city_id="'.$server_data['data']['city'][$country_key].'" data-country_id="'.$counrty_val.'" '.(isset($server_data['data']['sort_order']) && $server_data['data']['sort_order']=="price" ? 'checked="checked"' : '').'/>&nbsp;Price&nbsp;&nbsp;';
						$country_city_rcd_html.='<input type = "radio" name = "sort" value="name" onchange="change_order($(this))" data-city_id="'.$server_data['data']['city'][$country_key].'" data-country_id="'.$counrty_val.'"'.(isset($server_data['data']['sort_order']) && $server_data['data']['sort_order']=="name" ? 'checked="checked"' : '').'/>&nbsp;Hotel Name&nbsp;&nbsp;';
						$country_city_rcd_html.='<input type = "radio" name = "sort" value="rating" onchange="change_order($(this))" data-city_id="'.$server_data['data']['city'][$country_key].'" data-country_id="'.$counrty_val.'"'.(isset($server_data['data']['sort_order']) && $server_data['data']['sort_order']=="rating" ? 'checked="checked"' : '').'/>&nbsp;Rating</p>';
					$country_city_rcd_html.='</div>';
					$country_city_rcd_html.='<div class="clearfix"></div>';
					$country_city_rcd_html.='<form name="form_secend_step" id="form_secend_step" method="POST" onsubmit="filter_search($(this), '.$server_data['data']['city'][$country_key].');return false;" data-country_id="'.$counrty_val.'">';
						$country_city_rcd_html.='<div class="form-group col-sm-6">';
							$country_city_rcd_html.='<label for="inputName" class="control-label">Search</label>';
							$country_city_rcd_html.='<input type="text" class="form-control" name="keyword_search'.$server_data['data']['city'][$country_key].'" id="keyword_search'.$server_data['data']['city'][$country_key].'" value="'.(isset($server_data['data']['search_val']) && $server_data['data']['search_val']!="" ? $server_data['data']['search_val'] : "").'">';
						$country_city_rcd_html.='</div>';
						$country_city_rcd_html.='<div class="form-group col-sm-5 text-left">';
							$country_city_rcd_html.='<button type="submit" class="btn btn-primary next-step" style="margin-top:23px;" >Search</button>';
						$country_city_rcd_html.='</div>';
						$country_city_rcd_html.='<div class="clearfix"></div>';
					$country_city_rcd_html.='</form>';
					$country_city_rcd_html.='<div class="all_rcd_row">';
						$country_city_rcd_html.=$hotel_list_html;
						$country_city_rcd_html.='<div class="clearfix"></div>';
					$country_city_rcd_html.='</div>';
				if($server_data['data']['sort_order']=="" && $server_data['data']['type']!=3):
						$country_city_rcd_html.='<input type="hidden" class="hotel_list_tab_current_page" value="1"/>';
						$country_city_rcd_html.='<input type="hidden" class="hotel_list_tab_no_more_record_status" value="0"/>';
					$country_city_rcd_html.='</div>';
				endif;
			endforeach;
			$city_tab_html.='<div class="clearfix"></div>';
			$return_data['status']="success";
			$return_data['msg']="Date fetched successfully.";
			if($server_data['data']['sort_order']!="" || $server_data['data']['type']==3 || $server_data['data']['type']==2 ):
				
				$return_data['country_city_rcd_html']=$hotel_list_html.'<div class="clearfix"></div>';
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