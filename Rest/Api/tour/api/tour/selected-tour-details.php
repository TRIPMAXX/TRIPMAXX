<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		if(isset($server_data['data']) && isset($server_data['data']['step_1']) && !empty($server_data['data']['step_1']) && isset($server_data['data']['step_3']) && !empty($server_data['data']['step_3'])):
			$add_day=0;
			$total_tour_price=0.00;
			ob_start();
?>
		<div class="box-body">
			<table aria-describedby="example1_info" id="example" class="table table-bordered table-striped dataTable">
				<thead>
					<tr role="row">
						<th style = "text-align:left;">Tour Sites</th>
					</tr>
				</thead>
				<tbody aria-relevant="all" aria-live="polite" role="alert">
<?php
			$city_index=-1;
			$prev_city_id="";
			$prev_booking_date="";
			$default_currency=tools::find("first", TM_SETTINGS." as s, ".TM_CURRENCIES." as c", 'c.*', "WHERE c.id=s.default_currency ", array());
			foreach($server_data['data']['step_3'] as $offer_key=>$offer_val):
				$explode_offer_data=explode("-", $offer_val);
				$offer_id=end($explode_offer_data);
				$offer_details = tools::find("first", TM_OFFERS, '*', "WHERE id=:id ", array(":id"=>$offer_id));
				$tour_details = tools::find("first", TM_TOURS, '*', "WHERE id=:id ", array(":id"=>$offer_details['tour_id']));
				$markup_percentage=0.00;
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
				$offer_price_list = tools::find("first", TM_OFFER_PRICES, '*', "WHERE offer_id=:offer_id AND start_date<=:start_date AND end_date>=:end_date AND status=:status", array(":offer_id"=>$offer_details['id'], ":start_date"=>$server_data['data']['step_3_all']['tour_booking_tour_date_arr'][$offer_key], ":end_date"=>$server_data['data']['step_3_all']['tour_booking_tour_date_arr'][$offer_key], ':status'=>1));
				if(!empty($offer_price_list)):
					$offer_day_price=$offer_price_list['price_per_person'];
				else:
					$offer_day_price=$offer_details['price_per_person'];
				endif;
				$total_price=$total_price+$offer_day_price;
				$nationality_charge=($total_price * $nationality_addon_percentage)/100;
				$agent_commision=($total_price * $markup_percentage)/100;
				$each_tour_price=$total_price+$agent_commision+$nationality_charge;
				$total_tour_price=$total_tour_price+$total_price+$agent_commision+$nationality_charge;
				if($prev_booking_date=="" || $prev_booking_date!=$server_data['data']['step_3_all']['tour_booking_tour_date_arr'][$offer_key]):
					$prev_booking_date=$server_data['data']['step_3_all']['tour_booking_tour_date_arr'][$offer_key];
?>
				<tr class="odd">
					<td style = "text-align:left;padding-bottom: 0;" colspan="100%">
						<h4 style="margin: 0;"><?php echo tools::module_date_format($server_data['data']['step_3_all']['tour_booking_tour_date_arr'][$offer_key]);?></h4>
					</td>
				</tr>
<?php
				endif;
?>
				<tr class="odd">
					<td style = "text-align:left;">
						<?php echo $tour_details['tour_title'];?> - <?php echo $offer_details['offer_title'];?> - <?php echo $offer_details['service_type'];?> ( Capacity:  <?php echo $offer_details['offer_capacity'];?> )
						<br/>
						Price: <?php echo $default_currency['currency_code'].number_format($each_tour_price, 2,".",",");?>
						<?php
						if($server_data['data']['step_3_all']['tour_pickuptime_arr'][$offer_key]!=""):
						?>
						<br/>
						From: <?php echo date("h:i A", strtotime($server_data['data']['step_3_all']['tour_pickuptime_arr'][$offer_key].":00"));?>
						<?php
						endif;
						if($server_data['data']['step_3_all']['tour_dropofftime_arr'][$offer_key]!=""):
						?>
						<br/>
						To: <?php echo date("h:i A", strtotime($server_data['data']['step_3_all']['tour_dropofftime_arr'][$offer_key].":00"));?>
						<?php
						endif;
						?>
					</td>
				</tr>
<?php
			endforeach;
?>
				</tbody>
			</table>
		</div>
<?php
			$tour_details_html=ob_get_clean();
			$return_data['status']="success";
			$return_data['msg']="Date fetched successfully.";
			$return_data['tour_details']=$tour_details_html;
			$return_data['tour_price']=$total_tour_price;
			$return_data['default_currency']=$default_currency['currency_code'];
		else:
			$return_data['status']="error";
			$return_data['msg']="Some data missing.";
		endif;
	endif;
	echo json_encode($return_data);	
?>