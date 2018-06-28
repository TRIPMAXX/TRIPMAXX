<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['AGENT_SESSION_DATA']['id'], DOMAIN_NAME_PATH.'');
	$data['status']="error";
	$data['msg']="Token is not verified.";
	//$_POST['total_price']=2036;
	//print_r($_SESSION);exit;
	if(isset($_POST) && !empty($_POST)):
		$price_check_flag=false;
		if(isset($_SESSION['step_1']['booking_type']) && $_SESSION['step_1']['booking_type']=="agent" && isset($_SESSION['step_1']['agent_name']) && $_SESSION['step_1']['agent_name']!=""):
			$price_check_flag=false;
			$find_agent = tools::find("first", TM_AGENT, '*', "WHERE id=:id", array(":id"=>$_SESSION['step_1']['agent_name']));
			if($find_agent['type']=="A" && $find_agent['parent_id'] > 0):
				$find_gsm = tools::find("first", TM_AGENT, '*', "WHERE id=:id ", array(":id"=>$find_agent['parent_id']));
			endif;
			if($find_agent['credit_balance'] > $_POST['total_price'] && $find_agent['payment_type']=="credit"):
				$price_check_flag=true;
			elseif($find_agent['payment_type']=="cash"):
				$price_check_flag=true;
			else:
				$data['status'] = 'error';
				$data['msg']="You do not have enough credit balance";
			endif;
		endif;
		if($price_check_flag==true):
			$autentication_data_booking=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.BOOKING_API_PATH."authorized.php"));
			if(isset($autentication_data_booking->status)):
				if($autentication_data_booking->status=="success"):
					$post_data_booking['token']=array(
						"token"=>$autentication_data_booking->results->token,
						"token_timeout"=>$autentication_data_booking->results->token_timeout,
						"token_generation_time"=>$autentication_data_booking->results->token_generation_time
					);
					$post_data_booking['data']=$_SESSION;
					$post_data_booking['data']['total_price']=$_POST['total_price'];
					if(isset($return_data_arr) && $return_data_arr['results']['payment_type']=="cash"):
						$post_data_booking['data']['payment_type']="cash";
						$post_data_booking['data']['payment_status']="U";
						$post_data_booking['data']['payment_date']="";
						$post_data_booking['data']['pay_within_days']=$return_data_arr['results']['pay_within_days'];
					else:
						$post_data_booking['data']['payment_type']="credit";
						$post_data_booking['data']['payment_status']="P";
						$post_data_booking['data']['payment_date']=date("Y-m-d H:i:s");
						$post_data_booking['data']['pay_within_days']=0;
					endif;
					$post_data_str_booking=json_encode($post_data_booking);
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_HEADER, false);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
					curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.BOOKING_API_PATH."booking/create.php");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_booking);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					$return_data_booking = curl_exec($ch);
					curl_close($ch);
					//print_r($return_data_booking);
					$return_data_arr_booking=json_decode($return_data_booking, true);
					//print_r($return_data_arr_booking);
					$tour_data=array();
					if(!isset($return_data_arr_booking['status'])):
						$data['status'] = 'error';
						$data['msg']="Some error has been occure during execution.";
					elseif($return_data_arr_booking['status']=="success"):
						$return_hotel_ids=$return_data_arr_booking['return_hotel_ids'];
						$booking_id=$return_data_arr_booking['booking_id'];
						$autentication_data_dmc=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.DMC_API_PATH."authorized.php"));
						if(isset($autentication_data_dmc->status)):
							if($autentication_data_dmc->status=="success"):
								$post_data_dmc['token']=array(
									"token"=>$autentication_data_dmc->results->token,
									"token_timeout"=>$autentication_data_dmc->results->token_timeout,
									"token_generation_time"=>$autentication_data_dmc->results->token_generation_time
								);
								$post_data_dmc['data']['email_template_id']=11;
								$post_data_dmc['data']['booking_details_list']['id']=$booking_id;
								$post_dmc_data_str=json_encode($post_data_dmc);
								$ch = curl_init();
								curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
								curl_setopt($ch, CURLOPT_HEADER, false);
								curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
								curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
								curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.DMC_API_PATH."dmc/send-email-update.php");
								curl_setopt($ch, CURLOPT_POSTFIELDS, $post_dmc_data_str);
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
								$return_data_dmc = curl_exec($ch);
								curl_close($ch);
								$return_dmc_data_arr=json_decode($return_data_dmc, true);
								if(!isset($return_dmc_data_arr['status'])):
									//$data['status'] = 'error';
									//$data['msg']="Some error has been occure during execution.";
								elseif($return_dmc_data_arr['status']=="success"):
									//$data['status'] = 'success';
									//$data['msg']="Data received successfully";
								else:
									//$data['status'] = 'error';
									//$data['msg'] = $return_dmc_data_arr['msg'];
								endif;
							endif;
						else:
							//$data['status'] = 'error';
							//$data['msg'] = $autentication_data_dmc->msg;
						endif;									
						$autentication_data_supplier=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.SUPPLIER_API_PATH."authorized.php"));
						if(isset($autentication_data_supplier->status)):
							if($autentication_data_supplier->status=="success"):
								$post_data_supplier['token']=array(
									"token"=>$autentication_data_supplier->results->token,
									"token_timeout"=>$autentication_data_supplier->results->token_timeout,
									"token_generation_time"=>$autentication_data_supplier->results->token_generation_time
								);
								$post_data_supplier['data']['booking_id']=$booking_id;
								$post_data_str_supplier=json_encode($post_data_supplier);
								$ch = curl_init();
								curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
								curl_setopt($ch, CURLOPT_HEADER, false);
								curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
								curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
								curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.SUPPLIER_API_PATH."supplier/priority-supplier.php");
								curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_supplier);
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
								$return_data_supplier = curl_exec($ch);
								curl_close($ch);
								$return_data_arr_supplier=json_decode($return_data_supplier, true);
								if(!isset($return_data_arr_supplier['status'])):
									//$data['status'] = 'error';
									//$data['msg']="Some error has been occure during execution.";
								elseif($return_data_arr_supplier['status']=="success"):
									if(!empty($return_data_arr_supplier['results'])):
										$post_data_dmc['token']=array(
											"token"=>$autentication_data_dmc->results->token,
											"token_timeout"=>$autentication_data_dmc->results->token_timeout,
											"token_generation_time"=>$autentication_data_dmc->results->token_generation_time
										);
										$post_data_dmc['data']['email_template_id']=13;
										$post_dmc_data_str=json_encode($post_data_dmc);
										$ch = curl_init();
										curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
										curl_setopt($ch, CURLOPT_HEADER, false);
										curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
										curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
										curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.DMC_API_PATH."email-templates/booking-update-email.php");
										curl_setopt($ch, CURLOPT_POSTFIELDS, $post_dmc_data_str);
										curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
										$return_data_email_template = curl_exec($ch);
										curl_close($ch);
										$return_email_template_data_arr=json_decode($return_data_email_template, true);
										if(!isset($return_email_template_data_arr['status'])):
											//$data['status'] = 'error';
											//$data['msg']="Some error has been occure during execution.";
										elseif($return_email_template_data_arr['status']=="success"):
											//$data['status'] = 'success';
											//$data['msg']="Data received successfully";
											$supplier_email_template = $return_email_template_data_arr['email_template'];
											if(!empty($supplier_email_template)):
												$supplier_url_details='<a href="'.DOMAIN_NAME_PATH_SUPPLIER.'login?auto_login_id='.base64_encode(SECURITY_SALT.$return_data_arr_supplier['results'][0]['id']."supplier".AUTO_LOGIN_SECURITY_KEY).'&booking_id='.base64_encode($booking_id).'" title="View Order">'.DOMAIN_NAME_PATH_SUPPLIER.'login?auto_login_id='.base64_encode(SECURITY_SALT.$return_data_arr_supplier['results'][0]['id']."supplier".AUTO_LOGIN_SECURITY_KEY).'&booking_id='.base64_encode($booking_id).'</a>';
												$supplier_mail_Body=str_replace(array("[FIRST_NAME]", "[LAST_NAME]", "[DETAILS_URL]"), array($return_data_arr_supplier['results'][0]['first_name'], $return_data_arr_supplier['results'][0]['last_name'], $supplier_url_details), $supplier_email_template['template_body']);
												@tools::Send_SMTP_Mail($return_data_arr_supplier['results'][0]['email_address'], FROM_EMAIL, '', $supplier_email_template['template_subject'], $supplier_mail_Body);
											endif;
										else:
											//$data['status'] = 'error';
											//$data['msg'] = $return_email_template_data_arr['msg'];
										endif;
									endif;
									//$data['status'] = 'success';
									//$data['msg']="Data received successfully";
								else:
									//$data['status'] = 'error';
									//$data['msg'] = $return_data_arr_supplier['msg'];
								endif;
							endif;
						else:
							//$data['status'] = 'error';
							//$data['msg'] = $autentication_data->msg;
						endif;
						$autentication_data_hotel=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."authorized.php"));
						if(isset($autentication_data_hotel->status)):
							if($autentication_data_hotel->status=="success"):
								//$return_hotel_ids=array(2, 4);
								$post_data_hotel['token']=array(
									"token"=>$autentication_data_hotel->results->token,
									"token_timeout"=>$autentication_data_hotel->results->token_timeout,
									"token_generation_time"=>$autentication_data_hotel->results->token_generation_time
								);
								$post_data_hotel['data']['hotel_ids']=$return_hotel_ids;
								$post_data_str_hotel=json_encode($post_data_hotel);
								$ch = curl_init();
								curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
								curl_setopt($ch, CURLOPT_HEADER, false);
								curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
								curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
								curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."hotel/find-hotel.php");
								curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_hotel);
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
								$return_data_hotel = curl_exec($ch);
								curl_close($ch);
								$return_data_arr_hotel=json_decode($return_data_hotel, true);
								//print_r($return_data_arr_hotel);
								if(!isset($return_data_arr_hotel['status'])):
									//$data['status'] = 'error';
									//$data['msg']="Some error has been occure during execution.";
								elseif($return_data_arr_hotel['status']=="success"):
									if(!empty($return_data_arr_hotel['results'])):
										$post_data_dmc['token']=array(
											"token"=>$autentication_data_dmc->results->token,
											"token_timeout"=>$autentication_data_dmc->results->token_timeout,
											"token_generation_time"=>$autentication_data_dmc->results->token_generation_time
										);
										$post_data_dmc['data']['email_template_id']=10;
										$post_dmc_data_str=json_encode($post_data_dmc);
										$ch = curl_init();
										curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
										curl_setopt($ch, CURLOPT_HEADER, false);
										curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
										curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
										curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.DMC_API_PATH."email-templates/booking-update-email.php");
										curl_setopt($ch, CURLOPT_POSTFIELDS, $post_dmc_data_str);
										curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
										$return_data_email_template = curl_exec($ch);
										curl_close($ch);
										$return_email_template_data_arr=json_decode($return_data_email_template, true);
										if(!isset($return_email_template_data_arr['status'])):
											//$data['status'] = 'error';
											//$data['msg']="Some error has been occure during execution.";
										elseif($return_email_template_data_arr['status']=="success"):
											//$data['status'] = 'success';
											//$data['msg']="Data received successfully";
											$hotel_email_template = $return_email_template_data_arr['email_template'];
											if(!empty($hotel_email_template)):
												foreach($return_data_arr_hotel['results'] as $hotel_key=>$hotel_val):
													$hotel_url_details='<a href="'.DOMAIN_NAME_PATH_HOTEL.'login?auto_login_id='.base64_encode(SECURITY_SALT.$hotel_val['id']."hotel".AUTO_LOGIN_SECURITY_KEY).'&booking_id='.base64_encode($booking_id).'" title="View Order">'.DOMAIN_NAME_PATH_HOTEL.'login?auto_login_id='.base64_encode(SECURITY_SALT.$hotel_val['id']."hotel".AUTO_LOGIN_SECURITY_KEY).'&booking_id='.base64_encode($booking_id).'</a>';
													$hotel_mail_Body=str_replace(array("[HOTEL_NAME]", "[DETAILS_URL]"), array($hotel_val['hotel_name'], $hotel_url_details), $hotel_email_template['template_body']);
													@tools::Send_SMTP_Mail($hotel_val['email_address'], FROM_EMAIL, '', $hotel_email_template['template_subject'], $hotel_mail_Body);
												endforeach;
											endif;
										else:
											//$data['status'] = 'error';
											//$data['msg'] = $return_email_template_data_arr['msg'];
										endif;
									endif;
									//$data['status'] = 'success';
									//$data['msg']="Data received successfully";
								else:
									//$data['status'] = 'error';
									//$data['msg'] = $return_data_arr_hotel['msg'];
								endif;
							endif;
						else:
							//$data['status'] = 'error';
							//$data['msg'] = $autentication_data->msg;
						endif;
						if(isset($_SESSION['step_1']['booking_type']) && $_SESSION['step_1']['booking_type']=="agent" && isset($_SESSION['step_1']['agent_name']) && $_SESSION['step_1']['agent_name']!=""):
							if($find_agent['payment_type']=="credit"):
								$closing_balance=$_POST['credit_balance']=$find_agent['credit_balance']-$_POST['total_price'];
								$_POST['id']=$find_agent['id'];
								if($save_agent_data = tools::module_form_submission("", TM_AGENT)):
									$total_price=$_POST['total_price'];
									unset($_POST);
									$_POST['agent_id']=$find_agent['id'];
									$_POST['amount']=$total_price;
									$_POST['note']="Debit money for booking with quotation name:".$_SESSION['step_5']['quotation_name'];
									$_POST['debit_or_credit']="Debit";
									$_POST['closing_balance']=$closing_balance;
									tools::module_form_submission("", TM_AGENT_ACCOUNTING);
									$data['status'] = 'success';
									$data['msg'] = 'Agent has been updated successfully.';
								else:
									$data['status'] = 'error';
									$data['msg'] = 'We are having some probem. Please try again later.';
								endif;
								$email_temp_id=12;
								$cash_payment_str="";
							else:
								$data['status'] = 'success';
								$data['msg'] = 'Paying with cash.';
								$email_temp_id=40;
								$cash_payment_str="Cash payment needed to be done within ".$find_agent['pay_within_days'].($find_agent['pay_within_days'] > 1 ? " days" : " day");
							endif;
							$post_data_dmc['token']=array(
								"token"=>$autentication_data_dmc->results->token,
								"token_timeout"=>$autentication_data_dmc->results->token_timeout,
								"token_generation_time"=>$autentication_data_dmc->results->token_generation_time
							);
							$post_data_dmc['data']['email_template_id']=$email_temp_id;
							$post_dmc_data_str=json_encode($post_data_dmc);
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
							curl_setopt($ch, CURLOPT_HEADER, false);
							curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
							curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
							curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.DMC_API_PATH."email-templates/booking-update-email.php");
							curl_setopt($ch, CURLOPT_POSTFIELDS, $post_dmc_data_str);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
							$return_data_email_template = curl_exec($ch);
							curl_close($ch);
							$return_email_template_data_arr=json_decode($return_data_email_template, true);
							if(!isset($return_email_template_data_arr['status'])):
								//$data['status'] = 'error';
								//$data['msg']="Some error has been occure during execution.";
							elseif($return_email_template_data_arr['status']=="success"):
								//$data['status'] = 'success';
								//$data['msg']="Data received successfully";
								if($find_agent['type']=="A"):
									$agent_email_template = $return_email_template_data_arr['email_template'];
									if(!empty($agent_email_template)):
										$agent_url_details='<a href="'.DOMAIN_NAME_PATH.'index.php?auto_login_id='.base64_encode(SECURITY_SALT.$find_agent['id']."agent".AUTO_LOGIN_SECURITY_KEY).'&booking_id='.base64_encode($booking_id).'" title="View Order">'.DOMAIN_NAME_PATH.'index.php?auto_login_id='.base64_encode(SECURITY_SALT.$find_agent['id']."agent".AUTO_LOGIN_SECURITY_KEY).'&booking_id='.base64_encode($booking_id).'</a>';
										$agent_mail_Body=str_replace(array("[FIRST_NAME]", "[LAST_NAME]", "[DETAILS_URL]", "[CASH_PAYMENT]"), array($find_agent['first_name'], $find_agent['last_name'], $agent_url_details, $cash_payment_str), $agent_email_template['template_body']);
										@tools::Send_SMTP_Mail($find_agent['email_address'], FROM_EMAIL, '', $agent_email_template['template_subject'], $agent_mail_Body);
									endif;
									if(isset($find_gsm) && !empty($find_gsm)):
										$gsm_email_template = $return_email_template_data_arr['email_template'];
										if(!empty($gsm_email_template)):
											$gsm_url_details='<a href="'.DOMAIN_NAME_PATH.'index.php?auto_login_id='.base64_encode(SECURITY_SALT.$find_gsm['id']."agent".AUTO_LOGIN_SECURITY_KEY).'&booking_id='.base64_encode($booking_id).'&sub_agent_id='.base64_encode($find_agent['id']).'" title="View Order">'.DOMAIN_NAME_PATH.'index.php?auto_login_id='.base64_encode(SECURITY_SALT.$find_gsm['id']."agent".AUTO_LOGIN_SECURITY_KEY).'&booking_id='.base64_encode($booking_id).'&sub_agent_id='.base64_encode($find_agent['id']).'</a>';
											$gsm_mail_Body=str_replace(array("[FIRST_NAME]", "[LAST_NAME]", "[DETAILS_URL]", "[CASH_PAYMENT]"), array($find_gsm['first_name'], $find_gsm['last_name'], $gsm_url_details, $cash_payment_str), $gsm_email_template['template_body']);
											@tools::Send_SMTP_Mail($find_gsm['email_address'], FROM_EMAIL, '', $gsm_email_template['template_subject'], $gsm_mail_Body);
										endif;
									endif;
								elseif($find_agent['type']=="G"):
									$gsm_email_template = $return_email_template_data_arr['email_template'];
									if(!empty($gsm_email_template)):
										$gsm_url_details='<a href="'.DOMAIN_NAME_PATH.'index.php?auto_login_id='.base64_encode(SECURITY_SALT.$find_agent['id']."agent".AUTO_LOGIN_SECURITY_KEY).'&booking_id='.base64_encode($booking_id).'" title="View Order">'.DOMAIN_NAME_PATH.'index.php?auto_login_id='.base64_encode(SECURITY_SALT.$find_agent['id']."agent".AUTO_LOGIN_SECURITY_KEY).'&booking_id='.base64_encode($booking_id).'</a>';
										$gsm_mail_Body=str_replace(array("[FIRST_NAME]", "[LAST_NAME]", "[DETAILS_URL]", "[CASH_PAYMENT]"), array($find_agent['first_name'], $find_agent['last_name'], $gsm_url_details, $cash_payment_str), $gsm_email_template['template_body']);
										@tools::Send_SMTP_Mail($find_agent['email_address'], FROM_EMAIL, '', $gsm_email_template['template_subject'], $gsm_mail_Body);
									endif;
								endif;
							else:
								//$data['status'] = 'error';
								//$data['msg'] = $return_email_template_data_arr['msg'];
							endif;
						endif;
						$data['status'] = 'success';
						$data['msg']="Booking has been saved successfully.";
					else:
						$data['status'] = 'error';
						$data['msg'] = $return_data_arr_booking['msg'];
					endif;
				endif;
			else:
				$data['status'] = 'error';
				$data['msg'] = $autentication_data_booking->msg;
			endif;
			//$data['status']="success";
			//$data['msg']="Booking has been saved successfully.";
		endif;
	endif;
	echo json_encode($data);
?>