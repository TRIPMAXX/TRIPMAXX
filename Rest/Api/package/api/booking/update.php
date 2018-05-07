<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		$offer_id=$server_data['data']['id'];
		$find_offer = tools::find("first", TM_BOOKINGS, '*', "WHERE id=:id AND package_id=:package_id ", array(":id"=>$offer_id, ":package_id"=>$server_data['data']['package_id']));
		if(!empty($find_offer)):
			$_POST=$server_data['data'];
			$_POST['id']=$find_offer['id'];
			$check_flag=true;
			if(isset($server_data['data']['update_type']) && $server_data['data']['update_type']=="status"):
				if($find_offer['status']==1):
					$_POST['status']=0;
				else:
					$_POST['status']=1;
				endif;
			endif;
			if($check_flag==true):
				if($save_tour_offer_data = tools::module_form_submission("", TM_BOOKINGS)):
					$find_updated_tour_offer = tools::find("first", TM_BOOKINGS, '*', "WHERE id=:id", array(":id"=>$find_offer['id']));
					$return_data['status'] = 'success';
					$return_data['msg'] = 'Booking offer has been updated successfully.';
					$return_data['results'] = $find_updated_tour_offer;
				else:
					$return_data['status'] = 'error';
					$return_data['msg'] = 'We are having some probem. Please try again later.';
				endif;
			endif;
		else:
			$return_data['status'] = 'error';
			$return_data['msg'] = 'Invalid booking offer id.';
		endif;
	endif;
	echo json_encode($return_data);	
?>