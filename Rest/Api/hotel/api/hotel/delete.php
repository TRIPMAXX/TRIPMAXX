<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		$_GET=$server_data['data'];
		$find_hotel = tools::find("first", TM_HOTELS, '*', "WHERE id=:id", array(":id"=>base64_decode($_GET['hotel_id'])));
		if(!empty($find_hotel)):
			$find_room = tools::find("all", TM_ROOMS, '*', "WHERE hotel_id=:hotel_id", array(":hotel_id"=>$find_hotel['id']));
			if(!empty($find_room)):
				foreach($find_room as $room_key=>$room_val):
					if(isset($room_val['room_images']) && $room_val['room_images']!=""):
						$image_arr=explode(",", $room_val['room_images']);
						foreach($image_arr as $img_key=>$img_val):
							if($img_val!="" && file_exists(ROOM_IMAGES.$img_val)):
								unlink(ROOM_IMAGES.$img_val);
							endif;
						endforeach;
					endif;
					tools::delete(TM_ROOM_PRICES, "WHERE room_id=:room_id", array(":room_id"=>$room_val['id']));
					tools::delete(TM_ROOM_AGENT_MARKUP, "WHERE room_id=:room_id", array(":room_id"=>$room_val['id']));
					tools::delete(TM_ROOMS, "WHERE id=:id", array(":id"=>$room_val['id']));
				endforeach;
			endif;
			if(isset($find_hotel['hotel_images']) && $find_hotel['hotel_images']!=""):
				$image_arr=explode(",", $find_hotel['hotel_images']);
				foreach($image_arr as $img_key=>$img_val):
					if($img_val!="" && file_exists(HOTEL_IMAGES.$img_val)):
						unlink(HOTEL_IMAGES.$img_val);
					endif;
				endforeach;
			endif;			
			if(tools::delete(TM_HOTELS, "WHERE id=:id", array(":id"=>$find_hotel['id']))):
				$return_data['status'] = 'success';
				$return_data['msg'] = 'Hotel has been deleted successfully.';
				$return_data['results'] = array();
			else:
				$return_data['status'] = 'error';
				$return_data['msg'] = 'We are having some probem. Please try again later.';
			endif;
		else:
			$return_data['status'] = 'error';
			$return_data['msg'] = 'Invalid hotel id.';
		endif;
	endif;
	echo json_encode($return_data);	
?>