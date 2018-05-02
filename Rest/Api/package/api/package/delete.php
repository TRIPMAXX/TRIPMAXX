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
		$find_package = tools::find("first", TM_PACKAGES, '*', "WHERE id=:id", array(":id"=>base64_decode($_GET['package_id'])));
		if(!empty($find_package)):
			if(isset($find_package['package_images']) && $find_package['package_images']!=""):
				$image_arr=explode(",", $find_package['package_images']);
				foreach($image_arr as $img_key=>$img_val):
					if($img_val!="" && file_exists(PACKAGE_IMAGES.$img_val)):
						unlink(PACKAGE_IMAGES.$img_val);
					endif;
				endforeach;
			endif;
			if(tools::delete(TM_PACKAGES, "WHERE id=:id", array(":id"=>$find_package['id']))):
				$return_data['status'] = 'success';
				$return_data['msg'] = 'Package has been deleted successfully.';
				$return_data['results'] = array();
			else:
				$return_data['status'] = 'error';
				$return_data['msg'] = 'We are having some probem. Please try again later.';
			endif;
		else:
			$return_data['status'] = 'error';
			$return_data['msg'] = 'Invalid package id.';
		endif;
	endif;
	echo json_encode($return_data);	
?>