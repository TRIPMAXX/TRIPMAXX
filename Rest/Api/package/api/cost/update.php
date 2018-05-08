<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		$cost_id=$server_data['data']['id'];
		$find_cost = tools::find("first", TM_COSTS, '*', "WHERE id=:id AND booking_id=:booking_id ", array(":id"=>$cost_id, ":booking_id"=>$server_data['data']['booking_id']));
		if(!empty($find_cost)):
			$_POST=$server_data['data'];
			$_POST['id']=$find_cost['id'];
			$check_flag=true;
			if(isset($server_data['data']['update_type']) && $server_data['data']['update_type']=="status"):
				if($find_cost['status']==1):
					$_POST['status']=0;
				else:
					$_POST['status']=1;
				endif;
			endif;
			if($check_flag==true):
				if($save_tour_cost_data = tools::module_form_submission("", TM_COSTS)):
					$find_updated_tour_cost = tools::find("first", TM_COSTS, '*', "WHERE id=:id", array(":id"=>$find_cost['id']));
					$return_data['status'] = 'success';
					$return_data['msg'] = 'Booking cost has been updated successfully.';
					$return_data['results'] = $find_updated_tour_cost;
				else:
					$return_data['status'] = 'error';
					$return_data['msg'] = 'We are having some probem. Please try again later.';
				endif;
			endif;
		else:
			$return_data['status'] = 'error';
			$return_data['msg'] = 'Invalid booking cost id.';
		endif;
	endif;
	echo json_encode($return_data);	
?>