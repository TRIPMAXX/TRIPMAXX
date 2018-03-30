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
		if(tools::module_data_exists_check("room_type = '".tools::stripcleantohtml($_POST['room_type'])."' AND hotel_id='".tools::stripcleantohtml($_POST['hotel_id'])."'", '', TM_ROOMS)) {
			$return_data['status']="error";
			$return_data['msg'] = 'This room type already exists.';		
		}else {
			$_POST['room_images']="";
			if(isset($_POST['uploaded_files']) && !empty($_POST['uploaded_files']))
			{
				foreach($_POST['uploaded_files'] as $file_key=>$file_val):
					$random_number = tools::create_password(5);
					$extension = pathinfo($file_val['postname'], PATHINFO_EXTENSION);
					$file_name = str_replace(" ", '' , $random_number."_".$file_val['postname']);
					//echo $file_val['name']."<br/>";
					//echo HOTEL_IMAGES.$file_name."<br/>";
					$img = str_replace('data:image/'.$extension.';base64,', '', $file_val['name']);
					$img = str_replace(' ', '+', $img);
					$data_img_str = base64_decode($img);
					file_put_contents(ROOM_IMAGES.$file_name, $data_img_str);
					//move_uploaded_file($file_val['name'], HOTEL_IMAGES.$file_name);
					$_POST['room_images'].=($_POST['room_images']!="" ? "," : "").$file_name;
				endforeach;
			}
			if($save_hotel = tools::module_form_submission($uploaded_file_json_data, TM_ROOMS)) {
				$return_data['status']="success";
				$return_data['msg'] = 'Room has been created successfully.';
				$return_data['results'] = $save_hotel;
			} else {
				$return_data['status']="error";
				$return_data['msg'] = 'We are having some probem. Please try again later.';
			}
		};
	endif;
	echo json_encode($return_data);	
?>