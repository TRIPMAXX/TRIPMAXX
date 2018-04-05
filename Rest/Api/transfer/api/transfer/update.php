<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		$transfer_id=$server_data['data']['id'];
		$find_transfer = tools::find("first", TM_TRANSFER, '*', "WHERE id=:id", array(":id"=>$transfer_id));
		if(!empty($find_transfer)):
			$_POST=$server_data['data'];
			$_POST['id']=$find_transfer['id'];
			$check_flag=true;
			if(isset($server_data['data']['update_type']) && $server_data['data']['update_type']=="status"):
				if($find_transfer['status']==1):
					$_POST['status']=0;
				else:
					$_POST['status']=1;
				endif;
			else:
				if(tools::module_data_exists_check("transfer_title = '".tools::stripcleantohtml($_POST['transfer_title'])."' AND id <> ".$find_transfer['id']."", '', TM_TRANSFER)) {
					$check_flag=false;
					$return_data['status']="error";
					$return_data['msg'] = 'This transfer title already exists.';		
				}
			endif;
			if($check_flag==true):
				$_POST['transfer_images']=$find_transfer['transfer_images'];
				if(isset($_POST['uploaded_files']) && !empty($_POST['uploaded_files']))
				{
					foreach($_POST['uploaded_files'] as $file_key=>$file_val):
						$random_number = tools::create_password(5);
						$extension = pathinfo($file_val['postname'], PATHINFO_EXTENSION);
						$file_name = str_replace(" ", '' , $random_number."_".$file_val['postname']);
						$img = str_replace('data:image/'.$extension.';base64,', '', $file_val['name']);
						$img = str_replace(' ', '+', $img);
						$data_img_str = base64_decode($img);
						file_put_contents(TRANSFER_IMAGES.$file_name, $data_img_str);
						$_POST['transfer_images'].=($_POST['transfer_images']!="" ? "," : "").$file_name;
					endforeach;
				}
				if($save_transfer_data = tools::module_form_submission("", TM_TRANSFER)):
					$find_updated_transfer = tools::find("first", TM_TRANSFER, '*', "WHERE id=:id", array(":id"=>$find_transfer['id']));
					$return_data['status'] = 'success';
					$return_data['msg'] = 'Transfer has been updated successfully.';
					$return_data['results'] = $find_updated_transfer;
				else:
					$return_data['status'] = 'error';
					$return_data['msg'] = 'We are having some probem. Please try again later.';
				endif;
			endif;
		else:
			$return_data['status'] = 'error';
			$return_data['msg'] = 'Invalid transfer id.';
		endif;
	endif;
	echo json_encode($return_data);	
?>