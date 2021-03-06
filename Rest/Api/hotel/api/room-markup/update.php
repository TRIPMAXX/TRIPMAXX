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
			foreach($server_data['data']['agent_id_arr'] as $agent_key=>$agent_val):
				if(isset($_POST['id']))
					unset($_POST['id']);
				if(isset($server_data['data']['agent_markup_id_arr'][$agent_key]) && $server_data['data']['agent_markup_id_arr'][$agent_key]!=""):
					$_POST['id']=$server_data['data']['agent_markup_id_arr'][$agent_key];
				endif;
				if(isset($server_data['data']['agent_markup'][$agent_key]) && $server_data['data']['agent_markup'][$agent_key]!=""):
					$_POST['agent_id']=$server_data['data']['agent_id_arr'][$agent_key];
					$_POST['markup_price']=$server_data['data']['agent_markup'][$agent_key];
					$save_room_markup = tools::module_form_submission("", TM_ROOM_AGENT_MARKUP);
				elseif(isset($server_data['data']['agent_markup'][$agent_key]) && $server_data['data']['agent_markup'][$agent_key]=="" && isset($server_data['data']['agent_markup_id_arr'][$agent_key]) && $server_data['data']['agent_markup_id_arr'][$agent_key]!=""):
					tools::delete(TM_ROOM_AGENT_MARKUP, "WHERE id=:id", array(":id"=>$server_data['data']['agent_markup_id_arr'][$agent_key]));
				endif;
			endforeach;
			$return_data['status']="success";
			$return_data['msg'] = 'Room agent price markup has been saved successfully.';
		else:
			$return_data['status'] = 'error';
			$return_data['msg'] = 'Invalid room id.';
		endif;
	endif;
	echo json_encode($return_data);	
?>