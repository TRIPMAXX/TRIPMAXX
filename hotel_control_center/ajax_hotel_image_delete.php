<?php
require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA_HOTEL']['id'], DOMAIN_NAME_PATH_HOTEL.'login');	
	$data['status']="error";
	$data['msg']="Some data missing.";
	if(isset($_POST['hotel_id']) && $_POST['hotel_id']!="" && isset($_POST['image_name']) && $_POST['image_name']!=""):
		$_POST['image_name']=$_POST['image_name'];
		$_POST['id']=$_POST['hotel_id'];
		unset($_POST['hotel_id']);
		$find_hotel = tools::find("first", TM_HOTELS, '*', "WHERE id=:id", array(":id"=>$_POST['id']));
		if(!empty($find_hotel)):
			$image_arr=explode(",", $find_hotel['hotel_images']);
			$value_index=array_search($_POST['image_name'], $image_arr, TRUE);
			if($value_index===false):
				$data['status'] = 'error';
				$data['msg'] = 'Invalid image name.';
			else:
				unset($image_arr[$value_index]);
				$_POST['hotel_images']=implode(",", $image_arr);
				if($_POST['image_name']!="" && file_exists(HOTEL_IMAGES.$_POST['image_name'])):
					unlink(HOTEL_IMAGES.$_POST['image_name']);
				endif;
				if($save_hotel_data = tools::module_form_submission("", TM_HOTELS)):
					$data['status'] = 'success';
					$data['msg'] = 'Hotel image has been deleted successfully.';
				else:
					$data['status'] = 'error';
					$data['msg'] = 'We are having some probem. Please try again later.';
				endif;
			endif;
		else:
			$data['status'] = 'error';
			$data['msg'] = 'Invalid hotel id.';
		endif;
	endif;
	echo json_encode($data);
?>