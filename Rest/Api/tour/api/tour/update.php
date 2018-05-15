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
			$check_flag=true;
			if(isset($server_data['data']['update_type']) && $server_data['data']['update_type']=="status"):
				if($find_tour['status']==1):
					$_POST['status']=0;
				else:
					$_POST['status']=1;
				endif;
			else:
				if(tools::module_data_exists_check("tour_title = '".tools::stripcleantohtml($_POST['tour_title'])."' AND id <> ".$find_tour['id']."", '', TM_TOURS)) {
					$check_flag=false;
					$return_data['status']="error";
					$return_data['msg'] = 'This tour title already exists.';		
				}
			endif;
			if($check_flag==true):
				$_POST['tour_images']=$find_tour['tour_images'];
				if(isset($_POST['uploaded_files']) && !empty($_POST['uploaded_files']))
				{
					foreach($_POST['uploaded_files'] as $file_key=>$file_val):
						$random_number = tools::create_password(5);
						$extension = pathinfo($file_val['postname'], PATHINFO_EXTENSION);
						$file_name = str_replace(" ", '' , $random_number."_".$file_val['postname']);
						$img = str_replace('data:image/'.$extension.';base64,', '', $file_val['name']);
						$img = str_replace(' ', '+', $img);
						$data_img_str = base64_decode($img);
						file_put_contents(TOUR_IMAGES.$file_name, $data_img_str);
						$_POST['tour_images'].=($_POST['tour_images']!="" ? "," : "").$file_name;
						$filepath=TOUR_IMAGES.$file_name;
						$thumbpath=TOUR_IMAGES."thumb/".$file_name;
						$thumbnail_width=250;
						$thumbnail_height=150;
						tools::createThumbnail($filepath, $thumbpath, $thumbnail_width, $thumbnail_height);
					endforeach;
				}
				if($save_tour_data = tools::module_form_submission("", TM_TOURS)):
					$find_updated_tour = tools::find("first", TM_TOURS, '*', "WHERE id=:id", array(":id"=>$find_tour['id']));
					$return_data['status'] = 'success';
					$return_data['msg'] = 'Tour has been updated successfully.';
					$return_data['results'] = $find_updated_tour;
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