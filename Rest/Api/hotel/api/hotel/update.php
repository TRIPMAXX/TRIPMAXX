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
			$check_flag=true;
			if(isset($server_data['data']['update_type']) && $server_data['data']['update_type']=="status"):
				if($find_hotel['status']==1):
					$_POST['status']=0;
				else:
					$_POST['status']=1;
				endif;
			else:
				if(tools::module_data_exists_check("hotel_name = '".tools::stripcleantohtml($_POST['hotel_name'])."' AND id <> ".$find_hotel['id']."", '', TM_HOTELS)) {
					$check_flag=false;
					$return_data['status']="error";
					$return_data['msg'] = 'This hotel name already exists.';		
				}elseif(tools::module_data_exists_check("email_address = '".tools::stripcleantohtml($_POST['email_address'])."' AND id <> ".$find_hotel['id']."", '', TM_HOTELS)) {
					$check_flag=false;
					$return_data['status']="error";
					$return_data['msg'] = 'This email address already exists.';		
				}
			endif;
			if($check_flag==true):
				$_POST['hotel_images']=$find_hotel['hotel_images'];
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
						file_put_contents(HOTEL_IMAGES.$file_name, $data_img_str);
						//move_uploaded_file($file_val['name'], HOTEL_IMAGES.$file_name);
						$_POST['hotel_images'].=($_POST['hotel_images']!="" ? "," : "").$file_name;
					endforeach;
				}
				if($save_hotel_data = tools::module_form_submission("", TM_HOTELS)):
					$find_updated_hotel = tools::find("first", TM_HOTELS, '*', "WHERE id=:id", array(":id"=>$find_hotel['id']));
					$return_data['status'] = 'success';
					$return_data['msg'] = 'Hotel has been updated successfully.';
					$return_data['results'] = $find_updated_hotel;
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