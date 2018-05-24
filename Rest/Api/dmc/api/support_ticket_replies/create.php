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
		$support_ticket_val['id']=$_POST['support_ticket_id'];
		$uploaded_file_json_data="";
		$_POST['attachments']="";
		if(isset($_POST['uploaded_files']) && !empty($_POST['uploaded_files']))
		{
			foreach($_POST['uploaded_files'] as $file_key=>$file_val):
				$random_number = tools::create_password(5);
				$extension = pathinfo($file_val['postname'], PATHINFO_EXTENSION);
				$file_name = str_replace(" ", '' , $random_number."_".$file_val['postname']);
				$img = str_replace('data:image/'.$extension.';base64,', '', $file_val['name']);
				$img = str_replace(' ', '+', $img);
				$data_img_str = base64_decode($img);
				file_put_contents(SUPPORT_TICKET_REPLY_IMAGE.$file_name, $data_img_str);
				$_POST['attachments'].=($_POST['attachments']!="" ? "," : "").$file_name;
			endforeach;
		}
		if($save_hotel = tools::module_form_submission($uploaded_file_json_data, TM_SUPPORT_TICKET_REPLIES)) {
			if(isset($_POST['status']) && $_POST['status']=="C"):
				unset($_POST);
				$_POST['id']=$support_ticket_val['id'];
				$_POST['status']="C";
				$update_ticket = tools::module_form_submission($uploaded_file_json_data, TM_SUPPORT_TICKETS);
			endif;
			$return_data['status']="success";
			$return_data['msg'] = 'Support ticket response has been created successfully.';
			$return_data['results'] = $save_hotel;
		} else {
			$return_data['status']="error";
			$return_data['msg'] = 'We are having some probem. Please try again later.';
		}
	endif;
	echo json_encode($return_data);	
?>