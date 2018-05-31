<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		if(isset($server_data['data'])):
			
			$where_coulse = "WHERE ";
			$exicute = array();
			$flag=$flag1=$flag2=true;
			if(isset($server_data['data']['date_from']) && $server_data['data']['date_from']!="" && isset($server_data['data']['date_to']) && $server_data['data']['date_to']!=""):
				$date_from=date("Y-m-d",strtotime($server_data['data']['date_from']));
				$date_to=date("Y-m-d",strtotime($server_data['data']['date_to']));
				$where_coulse.= "booking_start_date BETWEEN ".$date_from." AND ".$date_to." ";
			else:
				$flag=false;
			endif;
			if(isset($server_data['data']['hotels']) && $server_data['data']['hotels']!="all"):
				$where_coulse.= ($flag==true?"AND ":"")."hotel_id=:hotel_id ";
				$exicute[":hotel_id"]=$server_data['data']['hotels'];
			else:
				$flag1=false;
			endif;
			if(isset($server_data['data']['booking_status']) && $server_data['data']['booking_status']!="A"):
				$where_coulse.= ($flag1==true?"AND ":"")."status=:status ";
				$exicute[":status"]=$server_data['data']['booking_status'];
			else:
				$flag2=false;
			endif;
			if($flag==false && $flag1==false && $flag2==false):
				$where_coulse.= "1";
			endif;
			//print_r($exicute);
			//echo $where_coulse;
			$booking_destination_list = tools::find("first", TM_BOOKING_HOTEL_DETAILS, 'GROUP_CONCAT(booking_destination_id) as destination_ids', $where_coulse, $exicute);
			if(!empty($booking_destination_list) && $booking_destination_list['destination_ids']!=""):
				$booking_ids_list = tools::find("first", TM_BOOKING_DESTINATION, 'GROUP_CONCAT(booking_master_id) as booking_ids', "WHERE id IN (".$booking_destination_list['destination_ids'].") ", array());
				if(!empty($booking_ids_list) && $booking_ids_list['booking_ids']!=""):	
						$booking_list = tools::find("all", TM_BOOKING_MASTERS." as b, ".TM_CURRENCIES." as cu", 'b.*,	cu.currency_code as currency_code, cu.currency_name as currency_name', "WHERE b.invoice_currency=cu.id AND b.id IN (".$booking_ids_list['booking_ids'].") AND b.is_deleted = :is_deleted ", array(":is_deleted"=>"N"));
					if(!empty($booking_list)):
						foreach($booking_list as $booking_key=>$booking_val):
							$booking_destination_list = tools::find("all", TM_BOOKING_DESTINATION." as b, ".TM_COUNTRIES." as co, ".TM_CITIES." as ci", 'b.*, co.name as co_name, ci.name as ci_name', "WHERE b.country_id=co.id AND b.city_id=ci.id AND booking_master_id=:booking_master_id ", array(":booking_master_id"=>$booking_val['id']));
							if(!empty($booking_destination_list)):
								foreach($booking_destination_list as $destination_key=>$destination_val):
									$booking_hotel_list = tools::find("all", TM_BOOKING_HOTEL_DETAILS, '*', "WHERE booking_destination_id=:booking_destination_id ", array(":booking_destination_id"=>$destination_val['id']));

									//$booking_tour_list = tools::find("all", TM_BOOKING_TOUR_DETAILS, '*', "WHERE booking_destination_id=:booking_destination_id ", array(":booking_destination_id"=>$destination_val['id']));
									//$booking_transfer_list = tools::find("all", TM_BOOKING_TRANSFER_DETAILS, '*', "WHERE booking_destination_id=:booking_destination_id ", array(":booking_destination_id"=>$destination_val['id']));

									$booking_destination_list[$destination_key]['booking_hotel_list']=$booking_hotel_list;

									//$booking_destination_list[$destination_key]['booking_tour_list']=$booking_tour_list;
									//$booking_destination_list[$destination_key]['booking_transfer_list']=$booking_transfer_list;
								endforeach;
							endif;
							$booking_list[$booking_key]['booking_destination_list']=$booking_destination_list;
						endforeach;
					endif;
				endif;
				//print_r($booking_list);exit;
				$booking_return_data['status']="success";
				$booking_return_data['results']=$booking_list;
				$booking_return_data['msg']="Data received successfully.";
				//print_r($booking_return_data);exit;
			else:
				$booking_return_data['status']="error";
				$booking_return_data['msg']="No record found.";
			endif;
		else:
			$booking_return_data['status']="error";
			$booking_return_data['msg']="Invalid hotel id.";
		endif;
	endif;
	echo json_encode($booking_return_data);	
?>