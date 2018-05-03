<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		$package_id=$server_data['data']['id'];
		$find_package = tools::find("first", TM_PACKAGES, '*', "WHERE id=:id", array(":id"=>$package_id));
		if(!empty($find_package)):
			$_POST=$server_data['data'];
			$_POST['id']=$find_package['id'];
			$check_flag=true;
			if(isset($server_data['data']['update_type']) && $server_data['data']['update_type']=="status"):
				if($find_package['status']==1):
					$_POST['status']=0;
				else:
					$_POST['status']=1;
				endif;
			else:
				if(tools::module_data_exists_check("package_title = '".tools::stripcleantohtml($_POST['package_title'])."' AND id <> ".$find_package['id']."", '', TM_PACKAGES)) {
					$check_flag=false;
					$return_data['status']="error";
					$return_data['msg'] = 'This tour title already exists.';		
				}
			endif;
			if($check_flag==true):
				$_POST['package_images']=$find_package['package_images'];
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
				if($save_tour_data = tools::module_form_submission("", TM_PACKAGES)):
					$find_updated_package = tools::find("first", TM_PACKAGES, '*', "WHERE id=:id", array(":id"=>$find_package['id']));
					$return_data['status'] = 'success';
					$return_data['msg'] = 'Tour has been updated successfully.';
					$return_data['results'] = $find_updated_package;
				else:
					$return_data['status'] = 'error';
					$return_data['msg'] = 'We are having some problem. Please try again later.';
				endif;
			endif;
		else:
			$return_data['status'] = 'error';
			$return_data['msg'] = 'Invalid package id.';
		endif;
	endif;
	echo json_encode($return_data);	
?>