<?php
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		if(isset($server_data['data']) && isset($server_data['data']['step_1']) && !empty($server_data['data']['step_1']) && isset($server_data['data']['step_2']) && !empty($server_data['data']['step_2'])):
			$total_hotel_price=0.00;
			ob_start();
?>
		<div class="box-body">
			<table aria-describedby="example1_info" id="example" class="table table-bordered table-striped dataTable">
				<thead>
					<tr role="row">
						<th style = "text-align:left;">Hotel</th>
						<th style = "text-align:center;">Room Type</th>
						<th style = "text-align:center;">Check In</th>
						<th style = "text-align:center;">Check Out</th>
						<th style = "text-align:center;">Rooms</th>
						<th style = "text-align:center;">Nights</th>
					</tr>
				</thead>
				<tbody aria-relevant="all" aria-live="polite" role="alert">
<?php
			$add_day=0;
			foreach($server_data['data']['step_2'] as $room_key=>$room_val):
				$explode_room_data=explode("-", $room_val);
				$room_id=end($explode_room_data);
				$room_details = tools::find("first", TM_ROOMS, '*', "WHERE id=:id ", array(":id"=>$room_id));
				$hotel_details = tools::find("first", TM_HOTELS, '*', "WHERE id=:id ", array(":id"=>$room_details['hotel_id']));
				$markup_percentage=0.00;
				if(isset($server_data['data']['step_1']['booking_type']) && $server_data['data']['step_1']['booking_type']=="agent" && isset($server_data['data']['step_1']['agent_name']) && $server_data['data']['step_1']['agent_name']!=""):
					$room_agent_markup = tools::find("first", TM_ROOM_AGENT_MARKUP, '*', "WHERE room_id=:room_id AND agent_id=:agent_id ", array(":agent_id"=>$server_data['data']['step_1']['agent_name'], ":room_id"=>$room_details['id']));
					if(!empty($room_agent_markup)):
						$markup_percentage=$room_agent_markup['markup_price'];
					endif;
				endif;
				$checkin_date_obj=date_create_from_format("d/m/Y", $server_data['data']['step_1']['checkin']);
				$checkin_date=date_format($checkin_date_obj, "Y-m-d");
				$checkin_date_on_city=date("Y-m-d", strtotime($checkin_date)+(24*60*60*$add_day));
				$add_day=$add_day+$server_data['data']['step_1']['number_of_night'][$room_key];
				$checkout_date_on_city=date("Y-m-d", strtotime($checkin_date_on_city)+(24*60*60*$server_data['data']['step_1']['number_of_night'][$room_key]));
				$total_price=0.00;
				for($i=strtotime($checkin_date_on_city);$i<strtotime($checkout_date_on_city);):
					$complete_date=date("Y-m-d", $i);
					$room_price_list = tools::find("first", TM_ROOM_PRICES, '*', "WHERE room_id=:room_id AND start_date<=:start_date AND end_date>=:end_date AND status=:status", array(":room_id"=>$room_details['id'], ":start_date"=>$complete_date, ":end_date"=>$complete_date, ':status'=>1));
					if(!empty($room_price_list)):
						$room_day_price=$room_price_list['price_per_night'];
					else:
						$room_day_price=$room_details['price'];
					endif;
					$total_price=$total_price+$room_day_price;
					$i=$i+(24*60*60);
				endfor;
				$agent_commision=($total_price * $markup_percentage)/100;
				$total_hotel_price=$total_hotel_price+$total_price+$agent_commision;
?>
				<tr class="odd">
					<td style = "text-align:left;"><?php echo $hotel_details['hotel_name'];?></td>
					<td style = "text-align:center;">
						<?= $room_details['room_type'];?>
						<br/>
						<font color="red"><?= $room_details['room_description'];?></font>
					</td>
					<td style = "text-align:center;"><?php echo tools::module_date_format($checkin_date_on_city, "Y-m-d");?></td>
					<td style = "text-align:center;"><?php echo tools::module_date_format($checkout_date_on_city, "Y-m-d");?></td>
					<td style = "text-align:center;"><?= $room_details['number_of_rooms'];?></td>
					<td style = "text-align:center;"><?php echo $server_data['data']['step_1']['number_of_night'][$room_key];?></td>
				</tr>
<?php
			endforeach;
?>
				</tbody>
			</table>
		</div>
<?php
			$room_details_html=ob_get_clean();
			$default_currency=tools::find("first", TM_SETTINGS." as s, ".TM_CURRENCIES." as c", 'c.*', "WHERE c.id=s.default_currency ", array());
			$return_data['status']="success";
			$return_data['msg']="Date fetched successfully.";
			$return_data['room_details']=$room_details_html;
			$return_data['hotel_price']=$total_hotel_price;
			$return_data['default_currency']=$default_currency['currency_code'];
		else:
			$return_data['status']="error";
			$return_data['msg']="Some data missing.";
		endif;
	endif;
	echo json_encode($return_data);	
?>