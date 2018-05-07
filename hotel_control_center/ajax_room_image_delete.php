<?php
require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA_HOTEL']['id'], DOMAIN_NAME_PATH_HOTEL.'login');
	$data['status']="error";
	$data['msg']="Some data missing.";
	if(isset($_POST['room_id']) && $_POST['room_id']!="" && isset($_POST['image_name']) && $_POST['image_name']!=""):
		$_POST['image_name']=$_POST['image_name'];
		$_POST['id']=$_POST['room_id'];
		unset($_POST['room_id']);
		$find_room = tools::find("first", TM_ROOMS, '*', "WHERE id=:id", array(":id"=>$_POST['id']));
		if(!empty($find_room)):
			$image_arr=explode(",", $find_room['room_images']);
			$value_index=array_search($_POST['image_name'], $image_arr, TRUE);
			if($value_index===false):
				$data['status'] = 'error';
				$data['msg'] = 'Invalid image name.';
			else:
				unset($image_arr[$value_index]);
				$_POST['room_images']=implode(",", $image_arr);
				if($_POST['image_name']!="" && file_exists(ROOM_IMAGES.$_POST['image_name'])):
					unlink(ROOM_IMAGES.$_POST['image_name']);
				endif;
				if($save_room_data = tools::module_form_submission("", TM_ROOMS)):
					$data['status'] = 'success';
					$data['msg'] = 'Room image has been deleted successfully.';
				else:
					$data['status'] = 'error';
					$data['msg'] = 'We are having some probem. Please try again later.';
				endif;
			endif;
		else:
			$data['status'] = 'error';
			$data['msg'] = 'Invalid room id.';
		endif;
	endif;
	echo json_encode($data);
?>