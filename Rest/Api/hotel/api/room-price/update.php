<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		$room_id=$server_data['data']['room_id'];
		$find_room = tools::find("first", TM_ROOMS, '*', "WHERE id=:id ", array(":id"=>$room_id));
		if(!empty($find_room)):
			$_POST['room_id']=$server_data['data']['room_id'];
			for($i=1;$i<=5;$i++):
				if(isset($server_data['data']['price_id'.$i]) && $server_data['data']['price_id'.$i]!=""):
					$_POST['id']=$server_data['data']['price_id'.$i];
				endif;
				$_POST['start_date']=date("Y-m-d", strtotime($server_data['data']['start_date'.$i]));
				$_POST['end_date']=date("Y-m-d", strtotime($server_data['data']['end_date'.$i]));
				$_POST['price_per_night']=$server_data['data']['price_per_night'.$i];
				$save_room_prices = tools::module_form_submission("", TM_ROOM_PRICES);
			endfor;
			$return_data['status']="success";
			$return_data['msg'] = 'Room price has been saved successfully.';
		else:
			$return_data['status'] = 'error';
			$return_data['msg'] = 'Invalid room id.';
		endif;
	endif;
	echo json_encode($return_data);	
?>