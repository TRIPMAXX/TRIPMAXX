<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		if(isset($server_data['data']) && isset($server_data['data']['supplier_id']) && $server_data['data']['supplier_id']!=""):
			$supplier_booking_list = tools::find("first", TM_BOOKING_ASSIGNED_SUPPLIER, 'GROUP_CONCAT(booking_master_id) as booking_ids', "WHERE supplier_id=:supplier_id ", array(":supplier_id"=>$server_data['data']['supplier_id']));
			if(!empty($supplier_booking_list) && $supplier_booking_list['booking_ids']!=""):
				if(isset($server_data['data']) && isset($server_data['data']['booking_id']) && $server_data['data']['booking_id']!=""):
					$booking_list = tools::find("all", TM_BOOKING_MASTERS." as b, ".TM_CURRENCIES." as cu", 'b.*, cu.currency_code as currency_code, cu.currency_name as currency_name', "WHERE b.invoice_currency=cu.id AND b.id=:id AND b.is_deleted = :is_deleted ", array(":id"=>$server_data['data']['booking_id'], ":is_deleted"=>"N"));
				else:
					$booking_list = tools::find("all", TM_BOOKING_MASTERS." as b, ".TM_CURRENCIES." as cu", 'b.*,	cu.currency_code as currency_code, cu.currency_name as currency_name', "WHERE b.invoice_currency=cu.id AND b.id IN (".$supplier_booking_list['booking_ids'].") AND b.is_deleted = :is_deleted ", array(":is_deleted"=>"N"));
				endif;
				if(!empty($booking_list)):
					foreach($booking_list as $booking_key=>$booking_val):
						$booking_supplier_approval_status = tools::find("first", TM_BOOKING_ASSIGNED_SUPPLIER, '*', "WHERE booking_master_id=:booking_master_id AND supplier_id=:supplier_id ", array(":booking_master_id"=>$booking_val['id'], ":supplier_id"=>$server_data['data']['supplier_id']));
						$booking_list[$booking_key]['booking_supplier_approval_status']=$booking_supplier_approval_status;
						$booking_destination_list = tools::find("all", TM_BOOKING_DESTINATION." as b, ".TM_COUNTRIES." as co, ".TM_CITIES." as ci", 'b.*, co.name as co_name, ci.name as ci_name', "WHERE b.country_id=co.id AND b.city_id=ci.id AND booking_master_id=:booking_master_id ", array(":booking_master_id"=>$booking_val['id']));
						if(!empty($booking_destination_list)):
							foreach($booking_destination_list as $destination_key=>$destination_val):
								$booking_hotel_list = tools::find("all", TM_BOOKING_HOTEL_DETAILS, '*', "WHERE booking_destination_id=:booking_destination_id ", array(":booking_destination_id"=>$destination_val['id']));
								$booking_tour_list = tools::find("all", TM_BOOKING_TOUR_DETAILS, '*', "WHERE booking_destination_id=:booking_destination_id ", array(":booking_destination_id"=>$destination_val['id']));
								$booking_transfer_list = tools::find("all", TM_BOOKING_TRANSFER_DETAILS, '*', "WHERE booking_destination_id=:booking_destination_id ", array(":booking_destination_id"=>$destination_val['id']));
								$booking_destination_list[$destination_key]['booking_hotel_list']=$booking_hotel_list;
								$booking_destination_list[$destination_key]['booking_tour_list']=$booking_tour_list;
								$booking_destination_list[$destination_key]['booking_transfer_list']=$booking_transfer_list;
							endforeach;
						endif;
						$booking_list[$booking_key]['booking_destination_list']=$booking_destination_list;
					endforeach;
				endif;
				$return_data['status']="success";
				$return_data['results']=$booking_list;
				$return_data['msg']="Data received successfully.";
			else:
				$return_data['status']="error";
				$return_data['msg']="No record found.";
			endif;
		else:
			$return_data['status']="error";
			$return_data['msg']="Invalid supplier id.";
		endif;
	endif;
	echo json_encode($return_data);	
?>