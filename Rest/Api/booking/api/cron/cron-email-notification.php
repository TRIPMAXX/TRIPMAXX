<?php
	include_once('../../init.php');
	$booking_list = tools::find("all", TM_BOOKING_MASTERS." as b, ".TM_CURRENCIES." as cu", 'b.*, cu.currency_code as currency_code, cu.currency_name as currency_name', "WHERE b.invoice_currency=cu.id AND :all AND b.is_deleted = :is_deleted AND payment_type=:payment_type AND payment_status=:payment_status AND pay_within_days>:pay_within_days AND is_emailed=:is_emailed AND ABS(DATEDIFF(DATE(NOW()), DATE(`last_updated`)))=(pay_within_days-1)", array(":all"=>1, ":is_deleted"=>"N", ":payment_type"=>"cash", ":payment_status"=>"U", ':pay_within_days'=>0, ':is_emailed'=>0));
	if(!empty($booking_list)):
		foreach($booking_list as $booking_key=>$booking_val):
			$autentication_data_agent=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."authorized.php"));
			if(isset($autentication_data_agent->status)):
				if($autentication_data_agent->status=="success"):
					$post_data_agent['token']=array(
						"token"=>$autentication_data_agent->results->token,
						"token_timeout"=>$autentication_data_agent->results->token_timeout,
						"token_generation_time"=>$autentication_data_agent->results->token_generation_time
					);
					$post_data_agent['data']['agent_id']=$booking_val['agent_id'];
					$post_data_str_agent=json_encode($post_data_agent);
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_HEADER, false);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
					curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."agent/booking-agent.php");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_agent);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					$return_data_agent = curl_exec($ch);
					curl_close($ch);
					//print_r($return_data_agent);
					$return_data_arr_agent=json_decode($return_data_agent, true);
					if(!isset($return_data_arr_agent['status'])):
						//$data['status'] = 'error';
						//$data['msg']="Some error has been occure during execution.";
					elseif($return_data_arr_agent['status']=="success"):
						//$data['status'] = 'success';
						//$data['msg']="Data received successfully";
						if(!empty($return_data_arr_agent['results'])):
							$autentication_data_dmc=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.DMC_API_PATH."authorized.php"));
							if(isset($autentication_data_dmc->status)):
								if($autentication_data_dmc->status=="success"):
									$post_data_dmc['token']=array(
										"token"=>$autentication_data_dmc->results->token,
										"token_timeout"=>$autentication_data_dmc->results->token_timeout,
										"token_generation_time"=>$autentication_data_dmc->results->token_generation_time
									);
									$post_data_dmc['data']['email_template_id']=42;
									$post_data_dmc_str=json_encode($post_data_dmc);
									$ch = curl_init();
									curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
									curl_setopt($ch, CURLOPT_HEADER, false);
									curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
									curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
									curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.DMC_API_PATH."email-templates/booking-update-email.php");
									curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_dmc_str);
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
									$return_data_dmc = curl_exec($ch);
									curl_close($ch);
									$return_data_dmc_arr=json_decode($return_data_dmc, true);
									//print_r($return_data_dmc_arr);
									if($return_data_dmc_arr['status']=="success"):
										$agent_url_details="";
										$cash_payment_str="Cash payment needed to be done within 1 day";
										$email_template=$return_data_dmc_arr['email_template'];
										$email_body = str_replace(array('[FIRST_NAME]', '[LAST_NAME]', "[DETAILS_URL]", "[CASH_PAYMENT]"), array($return_data_arr_agent['results']['first_name'], $return_data_arr_agent['results']['last_name'], $agent_url_details, $cash_payment_str), $email_template['template_body']);
										@tools::Send_SMTP_Mail($return_data_arr_agent['results']['email_address'], FROM_EMAIL, '', $email_template['template_subject'], $email_body);
									//else:
									//	$_SESSION['SET_TYPE'] = 'error';
									//	$_SESSION['SET_FLASH'] = $return_data_dmc_arr['msg'];
									endif;
								endif;
							endif;
						endif;
					else:
						//$data['status'] = 'error';
						//$data['msg'] = $return_data_arr_agent['msg'];
					endif;
				endif;
			else:
				//$data['status'] = 'error';
				//$data['msg'] = $autentication_data_agent->msg;
			endif;
		endforeach;
	endif;
?>