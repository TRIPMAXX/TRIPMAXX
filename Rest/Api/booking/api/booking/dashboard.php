<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		if(isset($server_data['data']) && isset($server_data['data']['agent_id']) && $server_data['data']['agent_id']!=""):
			//$booking_list = tools::find("all", TM_BOOKING_MASTERS." as b, ".TM_CURRENCIES." as cu", 'b.*, cu.currency_code as currency_code, cu.currency_name as currency_name', "WHERE b.invoice_currency=cu.id AND b.agent_id=:agent_id AND b.is_deleted = :is_deleted ", array(":agent_id"=>$server_data['data']['agent_id'], ":is_deleted"=>"N"));
		else:
			$booking_count['new_booking']=tools::module_counter("COUNT", "id", "status='0' AND is_deleted = 'N' AND creation_date LIKE '%".date('Y-m-d')."%' ", TM_BOOKING_MASTERS);
			$booking_count['pending_booking']=tools::module_counter("COUNT", "id", "status='0' AND is_deleted = 'N' ", TM_BOOKING_MASTERS);
			$booking_count['complete_booking']=tools::module_counter("COUNT", "id", "status !='0' AND is_deleted = 'N' ", TM_BOOKING_MASTERS);
			//$booking_count['booking_per_agent']=tools::module_counter("COUNT", "agent_id", "agent_id > 0 ", TM_BOOKING_MASTERS);
			$booking_count['booking_per_agent']=tools::find("all", TM_BOOKING_MASTERS, "COUNT('agent_id') AS count_val, agent_id", "WHERE agent_id > 0 GROUP BY agent_id", array());
			$booking_count['booking_per_year']=tools::find("all", TM_BOOKING_MASTERS, "COUNT('id') AS count_val, year(checkin_date) as year", "WHERE booking_type='agent' GROUP BY year(checkin_date) ORDER BY year(checkin_date)", array());
		endif;

		$return_data['status']="success";
		$return_data['results']=$booking_count;
		$return_data['msg']="Data received successfully.";
	endif;
	echo json_encode($return_data);	
?>