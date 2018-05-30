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
								<select name="tour_type'.$server_data['data']['city'][$country_key].'"  id="tour_type'.$server_data['data']['city'][$country_key].'" class="form-control validate[required]">';
								$country_city_rcd_html.='<option value="">Select Tour Type</option>';
								foreach($tour_attribute_list as $attr_key=>$attr_val):
									$country_city_rcd_html.='<option value="'.$attr_val['id'].'">'.$attr_val['attribute_name'].'</option>';
								endforeach;
							$country_city_rcd_html.='</select>';
						$country_city_rcd_html.='</div>';
						$country_city_rcd_html.='<div class="form-group col-sm-6">';
							$country_city_rcd_html.='<label for="inputName" class="control-label">Pickup Time</label>';
							$country_city_rcd_html.='<input type="time" class="form-control validate[required]" name="pick_time'.$server_data['data']['city'][$country_key].'" id="pick_time'.$server_data['data']['city'][$country_key].'" value="" >';
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