<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		$_POST=$server_data['data'];
		$uploaded_file_json_data="";
		if(tools::module_data_exists_check("package_title = '".tools::stripcleantohtml($_POST['package_title'])."'", '', TM_PACKAGES)) {
			$return_data['status']="error";
			$return_data['msg'] = 'This package title already exists.';		
		} else {
			$_POST['package_images']="";
			if(isset($_POST['uploaded_files']) && !empty($_POST['uploaded_files']))
			{
				foreach($_POST['uploaded_files'] as $file_key=>$file_val):
					$random_number = tools::create_password(5);
					$extension = pathinfo($file_val['postname'], PATHINFO_EXTENSION);
					$file_name = str_replace(" ", '' , $random_number."_".$file_val['postname']);
					$img = str_replace('data:image/'.$extension.';base64,', '', $file_val['name']);
					$img = str_replace(' ', '+', $img);
					$data_img_str = base64_decode($img);
					file_put_contents(PACKAGE_IMAGES.$file_name, $data_img_str);
					$_POST['package_images'].=($_POST['package_images']!="" ? "," : "").$file_name;
				endforeach;
			}
			if($save_hotel = tools::module_form_submission($uploaded_file_json_data, TM_PACKAGES)) {
				$return_data['status']="success";
				$return_data['msg'] = 'Package has been created successfully.';
				$return_data['results'] = $save_hotel;
			} else {
				$return_data['status']="error";
				$return_data['msg'] = 'We are having some probem. Please try again later.';
			}
		};
	endif;
	echo json_encode($return_data);	
?>