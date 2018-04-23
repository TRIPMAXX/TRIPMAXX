<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	//$server_data=json_decode('{"token":{"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE1MjQyMTY1OTksImp0aSI6ImVcL3NJSjBnQW80WjZ1dHM2dEpNZU40UzRqUVwvOXFQWGZjcUF4ZFJRNXNvdz0iLCJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3RcL3RyaXBtYXh4XC9SZXN0XC9BcGlcL3RvdXJcL2FwaVwvIiwibmJmIjoxNTI0MjE2NTk5LCJleHAiOjE1MjQyMTY2OTksImRhdGEiOnsiZmlsZV9uYW1lIjoiMTUyNDIxNjU5OV8zMTk5OS50eHQifX0.UbAeYxqyBhyRw-bHf5w3qKkoCgzEKQCkcttHATzekLHTI53tFmegaPl_znhemZfcX4gy51V1ajVrCgu9HUg4Og","token_timeout":100,"token_generation_time":1524216599},"data":{"booking_type":"personal","agent_name":"","checkin":"20\/04\/2018","checkout":"23\/04\/2018","country":["101","101"],"city":["5312","5313"],"number_of_night":["2","2"],"hotel_ratings":[["2","3"],["2","3"]],"sel_nationality":"10","country_residance":"4","sel_currency":"1","rooms":"1","adult":["1"],"child":[""],"offset":0,"record_per_page":10,"type":"1","sort_order":"","city_id":"5312","country_id":"101","search_val":"aaaaa"}}', true);
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
			$tour_list_html='';			
			$country_city_rcd_html='';
			$heading_count_rcd=0;
			$search_query="";
			$add_day=0;
			$default_currency=tools::find("first", TM_SETTINGS." as s, ".TM_CURRENCIES." as c", 'c.*', "WHERE c.id=s.default_currency ", array());
			$markup_percentage=0;
			$nationality_addon_percentage=0;
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
							$markup_percentage=$return_data_agent_arr['results']['tour_price'];
						endif;
					endif;
				endif;
			endif;
			foreach($server_data['data']['country'] as $country_key=>$counrty_val):	
				$order_by='ORDER BY t.id DESC';
				if(isset($server_data['data']['city_id']) && $server_data['data']['city_id']!=""):
					if(isset($server_data['data']['country_id']) && $server_data['data']['country_id']!="" && $counrty_val==$server_data['data']['country_id'] && $server_data['data']['city_id']==$server_data['data']['city'][$country_key]):
						if(isset($server_data['data']['sort_order']) && $server_data['data']['sort_order']!=""):
							if($server_data['data']['sort_order']=="price"):
								$order_by='ORDER BY t.id DESC';
							elseif($server_data['data']['sort_order']=="name"):
								$order_by='ORDER BY t.tour_title ASC';
							endif;
						endif;

						if(isset($server_data['data']['search_val']) && $server_data['data']['search_val']!=""):
							$search_query="AND (tour_title LIKE :tour_title OR short_description LIKE :short_description) ";
							$execute[':tour_title']="%".$server_data['data']['search_val']."%";
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
				$tour_list_html='';
				$each_first_price="--";
				$execute['co_id']=$counrty_val;
				$execute['ci_id']=$server_data['data']['city'][$country_key];
				$tour_list = tools::find("all", TM_TOURS." as t, ".TM_COUNTRIES." as co, ".TM_STATES." as s, ".TM_CITIES." as ci", 't.*, co.name as co_name, s.name as s_name, ci.name as ci_name', "WHERE t.country=co.id AND t.state=s.id AND t.city=ci.id AND co.id=:co_id AND ci.id=:ci_id ".$search_query.$order_by." LIMIT ".$offset.", ".$limit." ", $execute);
				if(!empty($tour_list)):
					$country_name=$tour_list[0]['co_name'];
					$city_name=$tour_list[0]['ci_name'];
					$total_tour=count($tour_list);
					foreach($tour_list as $tour_key=>$tour_val):
						$each_first_price="--";
						$offers_list = tools::find("all", TM_OFFERS, '*', "WHERE tour_id=:tour_id ", array(":tour_id"=>$tour_val['id']));
						$offer_html='';
						$tour_avalibility_status="";
						if(!empty($offers_list)):
							foreach($offers_list as $offer_key=>$offer_val):
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
										$post_data['data']['tour_id']=$tour_val['id'];
										$post_data['data']['booking_start_date']=$checkin_date_on_city;
										$post_data['data']['booking_end_date']=$checkout_date_on_city;
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
								if($total_prev_booking>=$offer_val['offer_capacity']):
									if($offer_key==0)
										$tour_avalibility_status="not avaliable";
									$offer_avaliability_status="not avaliable";
								elseif($total_prev_booking<$offer_val['offer_capacity']):
									if($offer_key==0)
										$tour_avalibility_status="avaliable";
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
								<div style="padding:10px 0 10px 0;border:1px solid gray;">
									<div class="col-md-1" style="font-weight:bold;">
										<?php
										if($offer_avaliability_status=="avaliable"):
										?>
										<img src="assets/img/a_icon.png" border="0" alt="Avaliable" title="Avaliable">
										<?php
										elseif($offer_avaliability_status=="not avaliable"):
										?>
										<img src="assets/img/r_icon.png" border="0" alt="On Request" title="On Request">
										<?php
										endif;
										?>
										<br>
										<input type="radio" name="selected_offer[<?= $server_data['data']['city'][$country_key];?>][<?php echo $tour_val['id'];?>]" class="selected_offer" onclick="change_offer_radio($(this))" value="<?= $server_data['data']['city'][$country_key]."-".$offer_val['id'];?>" data-price="<?php echo $default_currency['currency_code'].number_format($total_price+$agent_commision+$nationality_charge, 2,".",".");?>">
									</div>
									<div class="col-md-3" style="font-weight:bold;">
										<?= $offer_val['offer_title'];?>
									</div>
									<div class="col-md-3"><?= $offer_val['service_type'];?></div>
									<div class="col-md-3"><?= $offer_val['offer_capacity'];?></div>
									<div class="col-md-2" style="font-weight:bold;color:red;text-align:center;">
									<?php echo $default_currency['currency_code'].number_format((($total_price+$agent_commision+$nationality_charge)*$total_person), 2,".",".");?>
									</div>
									<div class="clearfix"></div>
								</div>
<?php
								$each_offer_html=ob_get_clean();
								$offer_html.=$each_offer_html;
								if($each_first_price=="--")
									$each_first_price=$default_currency['currency_code'].number_format((($total_price+$agent_commision+$nationality_charge)*$total_person), 2,".",".");
							endforeach;
						endif;
						ob_start();
						if($offer_html!=""):
?>
							<div class="form-group col-md-12">
								<div style="border:1px solid red;background-color:red;">
									<div class="col-md-3" style="font-weight:bold;color:#fff;">Tour Title</div>
									<div class="col-md-2" style="font-weight:bold;color:#fff;">Tour Type</div>
									<div class="col-md-3" style="font-weight:bold;color:#fff;text-align:center;">Availability</div>
									<div class="col-md-2" style="font-weight:bold;color:#fff;text-align:center;">Rate</div>
									<div class="clearfix"></div>
								</div>
								<div style="padding:20px 0 0 0;border:1px solid red;">
									<div class="col-md-3" style="font-weight:bold;"><?php echo $tour_val['tour_title'];?></div>
									<div class="col-md-2" style="font-weight:bold;"><?php echo $tour_val['tour_type'];?></div>
									<div class="col-md-3" style="font-weight:bold;text-align:center;">
										<?php
										if($tour_avalibility_status=="avaliable"):
										?>
										<button type="button" class="btn btn-success next-step">AVAILABLE</button>
										<?php
										elseif($tour_avalibility_status=="not avaliable"):
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
										if($tour_val['tour_images']!=""):
											$image_arr=explode(",", $tour_val['tour_images']);
											//if($image_arr[0]!="" && file_exists(TOUR_IMAGE_PATH.$image_arr[0])):
										?>
											<img src = "<?php echo(TOUR_IMAGE_PATH.$image_arr[0]);?>" border = "0" alt = "" width = "250" height = "150" onerror="this.remove;"/>
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
										<?php echo nl2br($tour_val['short_description']);?>
									</div>
									<div class="clearfix"></div>
									<div class="col-md-12">
										<a href="<?php echo DOMAIN_NAME_PATH_ADMIN;?>edit_tour?tour_id=<?php echo base64_encode($tour_val['id']);?>" target="_blank" style="font-size:16px;"><b>MORE INFO</b></a> | <a href="javascript:void(0);" onclick="show_offers('tour<?php echo $tour_val['id'];?>');" style="font-size:16px;"><b>VIEW AVAILABLE OFFERS</b></a>
									</div>
									<div class="clearfix"></div>
									<div id="tour<?php echo $tour_val['id'];?>" style="display:none;">
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
						$each_tour_list_html=ob_get_clean();
						$tour_list_html.=$each_tour_list_html;
					endforeach;
				else:
					$contry_list = tools::find("first", TM_COUNTRIES, '*', "WHERE id=:id ORDER BY name ASC ", array(":id"=>$counrty_val));
					$city_list = tools::find("first", TM_CITIES, '*', "WHERE id=:id ", array(":id"=>$server_data['data']['city'][$country_key]));
					$country_name=$contry_list['name'];
					$city_name=$city_list['name'];
					$total_tour=0;
				endif;
				if(count($server_data['data']['country'])>1 && $server_data['data']['sort_order']=="" && $server_data['data']['type']!=3 && $server_data['data']['type']!=2):
					$city_tab_html.='<div class="col-md-3 cls_each_city_tour_tab_div '.($country_key==0 ? "cls_each_city_tab_div_active" : "").'" data-tab_id="tour_city'.$server_data['data']['city'][$country_key].'" onclick="change_city_tour($(this))">'.$city_name.'</div>';
				endif;
				if($tour_list_html==""):
					$tour_list_html='<div class="col-md-12 text-center no_rcd" style="padding:30px;color:red;">No '.($server_data['data']['type']==2 ? "more " : "").'record found</div>';
				endif;
				if($server_data['data']['sort_order']=="" && $server_data['data']['type']!=3 && $server_data['data']['type']!=2):
					$country_city_rcd_html.='<div class="each_tour_tab_content '.($country_key==0 ? "active_each_tab_content" : "").'" id="tour_city'.$server_data['data']['city'][$country_key].'" data-city_id="'.$server_data['data']['city'][$country_key].'" data-country_id="'.$counrty_val.'">';
				endif;
					$country_city_rcd_html.='<div class="col-md-8 heading_count_rcd">';
						$heading_count_rcd=$total_tour;
						$country_city_rcd_html.='<p>Your search for <font color="red"><b>'.$country_name.'</b></font>, <font color="red"><b>'.$city_name.'</b></font> for <font color="red"><b>'.tools::module_date_format($checkin_date_on_city).'</b></font> for <font color="red"><b>'.$total_person.' Passenger(s)</b></font> fetched <font color="red"><b><span class="total_tour_number">'.$heading_count_rcd.'</span> Tour(s)</b></font></p>';
					$country_city_rcd_html.='</div>';
					$country_city_rcd_html.='<div class="col-md-4">';
						$country_city_rcd_html.='<p><b>SORT BY:</b>&nbsp;&nbsp;&nbsp;';
						//$country_city_rcd_html.='<input type = "radio" name = "tour_sort" value="price" onchange="change_tour_order($(this))" data-city_id="'.$server_data['data']['city'][$country_key].'" data-country_id="'.$counrty_val.'" '.(isset($server_data['data']['sort_order']) && $server_data['data']['sort_order']=="price" ? 'checked="checked"' : '').'/>&nbsp;Price&nbsp;&nbsp;';
						$country_city_rcd_html.='<input type = "radio" name = "tour_sort" value="name" onchange="change_tour_order($(this))" data-city_id="'.$server_data['data']['city'][$country_key].'" data-country_id="'.$counrty_val.'"'.(isset($server_data['data']['sort_order']) && $server_data['data']['sort_order']=="name" ? 'checked="checked"' : '').'/>&nbsp;Tour Title&nbsp;&nbsp;';
						//$country_city_rcd_html.='<input type = "radio" name = "tour_sort" value="rating" onchange="change_tour_order($(this))" data-city_id="'.$server_data['data']['city'][$country_key].'" data-country_id="'.$counrty_val.'"'.(isset($server_data['data']['sort_order']) && $server_data['data']['sort_order']=="rating" ? 'checked="checked"' : '').'/>&nbsp;Rating</p>';
					$country_city_rcd_html.='</div>';
					$country_city_rcd_html.='<div class="clearfix"></div>';
					$country_city_rcd_html.='<form name="form_third_step" id="form_third_step" method="POST" onsubmit="filter_tour_search($(this), '.$server_data['data']['city'][$country_key].');return false;" data-country_id="'.$counrty_val.'">';
						$country_city_rcd_html.='<div class="form-group col-sm-6">';
							$country_city_rcd_html.='<label for="inputName" class="control-label">Search</label>';
							$country_city_rcd_html.='<input type="text" class="form-control" name="tour_keyword_search'.$server_data['data']['city'][$country_key].'" id="tour_keyword_search'.$server_data['data']['city'][$country_key].'" value="'.(isset($server_data['data']['search_val']) && $server_data['data']['search_val']!="" ? $server_data['data']['search_val'] : "").'">';
						$country_city_rcd_html.='</div>';
						$country_city_rcd_html.='<div class="form-group col-sm-5 text-left">';
							$country_city_rcd_html.='<button type="submit" class="btn btn-primary next-step" style="margin-top:23px;" >Search</button>';
						$country_city_rcd_html.='</div>';
						$country_city_rcd_html.='<div class="clearfix"></div>';
					$country_city_rcd_html.='</form>';
					$country_city_rcd_html.='<div class="all_rcd_row">';
						$country_city_rcd_html.=$tour_list_html;
						$country_city_rcd_html.='<div class="clearfix"></div>';
					$country_city_rcd_html.='</div>';
				if($server_data['data']['sort_order']=="" && $server_data['data']['type']!=3):
						$country_city_rcd_html.='<input type="hidden" class="tour_list_tab_current_page" value="1"/>';
						$country_city_rcd_html.='<input type="hidden" class="tour_list_tab_no_more_record_status" value="0"/>';
					$country_city_rcd_html.='</div>';
				endif;
			endforeach;
			$city_tab_html.='<div class="clearfix"></div>';
			$return_data['status']="success";
			$return_data['msg']="Date fetched successfully.";
			if($server_data['data']['sort_order']!="" || $server_data['data']['type']==3 || $server_data['data']['type']==2 ):
				
				$return_data['country_city_rcd_html']=$tour_list_html.'<div class="clearfix"></div>';
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