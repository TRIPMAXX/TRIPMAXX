<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		$hotel_id=$server_data['data']['id'];
		$find_hotel = tools::find("first", TM_HOTELS, '*', "WHERE id=:id", array(":id"=>$hotel_id));
		if(!empty($find_hotel)):
			$_POST=$server_data['data'];
			$_POST['id']=$find_hotel['id'];
			$image_arr=explode(",", $find_hotel['hotel_images']);
			$value_index=array_search($_POST['image_name'], $image_arr, TRUE);
			if($value_index===false):
				$return_data['status'] = 'error';
				$return_data['msg'] = 'Invalid image name.';
			else:
				unset($image_arr[$value_index]);
				$_POST['hotel_images']=implode(",", $image_arr);
				if($_POST['image_name']!="" && file_exists(HOTEL_IMAGES.$_POST['image_name'])):
					unlink(HOTEL_IMAGES.$_POST['image_name']);
				endif;
				if($_POST['image_name']!="" && file_exists(HOTEL_IMAGES."thumb/".$_POST['image_name'])):
					unlink(HOTEL_IMAGES."thumb/".$_POST['image_name']);
				endif;
				if($save_hotel_data = tools::module_form_submission("", TM_HOTELS)):
					$return_data['status'] = 'success';
					$return_data['msg'] = 'Hotel image has been deleted successfully.';
				else:
					$return_data['status'] = 'error';
					$return_data['msg'] = 'We are having some probem. Please try again later.';
				endif;
			endif;
		else:
			$return_data['status'] = 'error';
			$return_data['msg'] = 'Invalid hotel id.';
		endif;
	endif;
	echo json_encode($return_data);	
?>