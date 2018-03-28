<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && $server_data['token']['token']==TOKEN && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		$room_id=$server_data['data']['id'];
		$find_room = tools::find("first", TM_ROOMS, '*', "WHERE id=:id AND hotel_id=:hotel_id ", array(":id"=>$room_id, ":hotel_id"=>$server_data['data']['hotel_id']));
		if(!empty($find_room)):
			$_POST=$server_data['data'];
			$_POST['id']=$find_room['id'];
			$check_flag=true;
			if(isset($server_data['data']['update_type']) && $server_data['data']['update_type']=="status"):
				if($find_room['status']==1):
					$_POST['status']=0;
				else:
					$_POST['status']=1;
				endif;
			else:
				if(tools::module_data_exists_check("room_type = '".tools::stripcleantohtml($_POST['room_type'])."' AND id <> ".$find_room['id']."", '', TM_ROOMS)) {
					$check_flag=false;
					$return_data['status']="error";
					$return_data['msg'] = 'This room type already exists.';		
				}
			endif;
			if($check_flag==true):
				$_POST['room_images']=$find_room['room_images'];
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
				if($save_hotel_data = tools::module_form_submission("", TM_ROOMS)):
					$find_updated_room = tools::find("first", TM_ROOMS, '*', "WHERE id=:id", array(":id"=>$find_room['id']));
					$return_data['status'] = 'success';
					$return_data['msg'] = 'Room has been updated successfully.';
					$return_data['results'] = $find_updated_room;
				else:
					$return_data['status'] = 'error';
					$return_data['msg'] = 'We are having some probem. Please try again later.';
				endif;
			endif;
		else:
			$return_data['status'] = 'error';
			$return_data['msg'] = 'Invalid room id.';
		endif;
	endif;
	echo json_encode($return_data);	
?>