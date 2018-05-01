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
			$find_agent = tools::find("first", TM_AGENT, '*', "WHERE id=:id ", array(":id"=>$server_data['data']['booking_details_list']['agent_id']));
			$autentication_data_dmc=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.DMC_API_PATH."authorized.php"));
			if(isset($autentication_data_dmc->status)):
				if($autentication_data_dmc->status=="success"):
					$post_data_dmc['token']=array(
						"token"=>$autentication_data_dmc->results->token,
						"token_timeout"=>$autentication_data_dmc->results->token_timeout,
						"token_generation_time"=>$autentication_data_dmc->results->token_generation_time
					);
					$post_data_str_dmc=json_encode($post_data_dmc);
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_HEADER, false);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
					curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.DMC_API_PATH."email-templates/booking-update-email.php");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_dmc);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					$return_data_dmc = curl_exec($ch);
					curl_close($ch);
					$return_data_arr_dmc=json_decode($return_data_dmc, true);
					if(!isset($return_data_arr_dmc['status'])):
						//$_SESSION['SET_TYPE'] = 'error';
						//$_SESSION['SET_FLASH']="Some error has been occure during execution.";
					elseif($return_data_arr_dmc['status']=="success"):
						$email_template=$return_data_arr_dmc['email_template'];
					else:
						//$_SESSION['SET_TYPE'] = 'error';
						//$_SESSION['SET_FLASH'] = $return_data_arr_dmc['msg'];
					endif;
				endif;
			endif;
			if($find_agent['type']=="A"):
				if(!empty($email_template)):
					$agent_url_details="";
					$agent_mail_Body=str_replace(array("[FIRST_NAME]", "[LAST_NAME]", "[DETAILS_URL]"), array($find_agent['first_name'], $find_agent['last_name'], $agent_url_details), $email_template['template_body']);
					@tools::Send_HTML_Mail($find_agent['email_address'], FROM_EMAIL, '', $email_template['template_subject'], $agent_mail_Body);
				endif;
				if(isset($find_agent['parent_id']) && $find_agent['parent_id'] > 0):
					$find_gsm = tools::find("first", TM_AGENT, '*', "WHERE id=:id ", array(":id"=>$find_agent['parent_id']));
					if(!empty($email_template)):
						$gsm_url_details="";
						$gsm_mail_Body=str_replace(array("[FIRST_NAME]", "[LAST_NAME]", "[DETAILS_URL]"), array($find_gsm['first_name'], $find_gsm['last_name'], $gsm_url_details), $email_template['template_body']);
						@tools::Send_HTML_Mail($find_gsm['email_address'], FROM_EMAIL, '', $email_template['template_subject'], $gsm_mail_Body);
					endif;
				endif;
			elseif($find_agent['type']=="G"):
				if(!empty($email_template)):
					$gsm_url_details="";
					$gsm_mail_Body=str_replace(array("[FIRST_NAME]", "[LAST_NAME]", "[DETAILS_URL]"), array($find_agent['first_name'], $find_agent['last_name'], $gsm_url_details), $email_template['template_body']);
					@tools::Send_HTML_Mail($find_agent['email_address'], FROM_EMAIL, '', $email_template['template_subject'], $gsm_mail_Body);
				endif;
			endif;
			$return_data['status']="success";
			$return_data['msg']="Email send successfully.";
		else:
			$return_data['status']="error";
			$return_data['msg']="DMC id missing.";
		endif;
	endif;
	echo json_encode($return_data);	
?>