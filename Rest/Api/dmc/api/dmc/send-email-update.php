<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		if(isset($server_data['data']) && isset($server_data['data']['booking_details_list']) && $server_data['data']['booking_details_list']!=""):
			$dmc_list = tools::find("first", TM_DMC, '*', "WHERE id=:id ", array(":id"=>1));
			$dmc_email_template = tools::find("first", TM_EMAIL_TEMPLATES, $value='id, template_title, template_subject, template_body, status', "WHERE id=:id AND status=:status ", array(':id'=>15, ':status'=>1));
			if(!empty($dmc_email_template)):
				$dmc_url_details="";
				$dmc_mail_Body=str_replace(array("[FIRST_NAME]", "[LAST_NAME]", "[DETAILS_URL]"), array($dmc_list['first_name'], $dmc_list['last_name'], $dmc_url_details), $dmc_email_template['template_body']);
				@tools::Send_HTML_Mail($dmc_list['email_address'], FROM_EMAIL, '', $dmc_email_template['template_subject'], $dmc_mail_Body);
			endif;
			$return_data['status']="success";
			$return_data['msg']="Data received successfully.";
		else:
			$return_data['status']="error";
			$return_data['msg']="DMC id missing.";
		endif;
	endif;
	echo json_encode($return_data);	
?>