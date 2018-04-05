<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		$tour_id=$server_data['data']['id'];
		$find_tour = tools::find("first", TM_TOURS, '*', "WHERE id=:id", array(":id"=>$tour_id));
		if(!empty($find_tour)):
			$_POST=$server_data['data'];
			$_POST['id']=$find_tour['id'];
			$image_arr=explode(",", $find_tour['tour_images']);
			$value_index=array_search($_POST['image_name'], $image_arr, TRUE);
			if($value_index===false):
				$return_data['status'] = 'error';
				$return_data['msg'] = 'Invalid image name.';
			else:
				unset($image_arr[$value_index]);
				$_POST['tour_images']=implode(",", $image_arr);
				if($_POST['image_name']!="" && file_exists(TOUR_IMAGES.$_POST['image_name'])):
					unlink(TOUR_IMAGES.$_POST['image_name']);
				endif;
				if($save_tour_data = tools::module_form_submission("", TM_TOURS)):
					$return_data['status'] = 'success';
					$return_data['msg'] = 'Tour image has been deleted successfully.';
				else:
					$return_data['status'] = 'error';
					$return_data['msg'] = 'We are having some probem. Please try again later.';
				endif;
			endif;
		else:
			$return_data['status'] = 'error';
			$return_data['msg'] = 'Invalid tour id.';
		endif;
	endif;
	echo json_encode($return_data);	
?>