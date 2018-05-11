<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');	
	$data['status']="error";
	$return_data['msg']="Token is not verified.";
	//$_POST['total_price']=2036;
	//print_r($_SESSION);exit;
	if(isset($_POST) && !empty($_POST)):
		$price_check_flag=true;
		if(isset($_SESSION['step_1']['booking_type']) && $_SESSION['step_1']['booking_type']=="agent" && isset($_SESSION['step_1']['agent_name']) && $_SESSION['step_1']['agent_name']!=""):
			$price_check_flag=false;
			$autentication_data=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."authorized.php"));
			if(isset($autentication_data->status)):
				if($autentication_data->status=="success"):
					$post_data['token']=array(
						"token"=>$autentication_data->results->token,
						"token_timeout"=>$autentication_data->results->token_timeout,
						"token_generation_time"=>$autentication_data->results->token_generation_time
					);
					$post_data['data']['agent_id']=$_SESSION['step_1']['agent_name'];
					$post_data_str=json_encode($post_data);
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_HEADER, false);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
					curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."agent/booking-agent.php");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					$return_data = curl_exec($ch);
					curl_close($ch);
					$return_data_arr=json_decode($return_data, true);
					//print_r($return_data);
					$tour_data=array();
					if(!isset($return_data_arr['status'])):
						$data['status'] = 'error';
						$data['msg']="Some error has been occure during execution.";
					elseif($return_data_arr['status']=="success"):
						//$data['status'] = 'success';
						//$data['msg']="Data received successfully";
						if($return_data_arr['results']['credit_balance'] > $_POST['total_price']):
							$price_check_flag=true;
						else:
							$data['status'] = 'error';
							$data['msg']="You do not have enough credit balance";
						endif;
					else:
						$data['status'] = 'error';
						$data['msg'] = $return_data_arr['msg'];
					endif;
				endif;
			else:
				$data['status'] = 'error';
				$data['msg'] = $autentication_data->msg;
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
					$post_data_booking['data']['booking_details_list']=$_POST['booking_details_list'];
					$post_data_booking['data']['total_price']=$_POST['total_price'];
					$post_data_str_booking=json_encode($post_data_booking);
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_HEADER, false);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
					curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.BOOKING_API_PATH."booking/booking-update.php");
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
						$dmc_email_template = tools::find("first", TM_EMAIL_TEMPLATES, $value='id, template_title, template_subject, template_body, status', "WHERE id=:id AND status=:status ", array(':id'=>34, ':status'=>1));
						if(!empty($dmc_email_template)):
							$dmc_url_details='<a href="'.DOMAIN_NAME_PATH_ADMIN.'login?auto_login_id='.base64_encode(SECURITY_SALT.$_SESSION['SESSION_DATA']['id']."dmc".AUTO_LOGIN_SECURITY_KEY).'&booking_id='.base64_encode($booking_id).'" title="View Order">'.DOMAIN_NAME_PATH_ADMIN.'login?auto_login_id='.base64_encode(SECURITY_SALT.$_SESSION['SESSION_DATA']['id']."dmc".AUTO_LOGIN_SECURITY_KEY).'&booking_id='.base64_encode($booking_id).'</a>';
							$dmc_mail_Body=str_replace(array("[FIRST_NAME]", "[LAST_NAME]", "[DETAILS_URL]"), array($_SESSION['SESSION_DATA']['first_name'], $_SESSION['SESSION_DATA']['last_name'], $dmc_url_details), $dmc_email_template['template_body']);
							@tools::Send_SMTP_Mail($_SESSION['SESSION_DATA']['email_address'], FROM_EMAIL, '', $dmc_email_template['template_subject'], $dmc_mail_Body);
						endif;
						//print_r($_POST['booking_details_list']);
						if(isset($_POST['booking_details_list']) && isset($_POST['booking_details_list']['booking_supplier_list']) && !empty($_POST['booking_details_list']['booking_supplier_list'])):
							$autentication_data_supplier=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.SUPPLIER_API_PATH."authorized.php"));
							if(isset($autentication_data_supplier->status)):
								if($autentication_data_supplier->status=="success"):
									$post_data_supplier['token']=array(
										"token"=>$autentication_data_supplier->results->token,
										"token_timeout"=>$autentication_data_supplier->results->token_timeout,
										"token_generation_time"=>$autentication_data_supplier->results->token_generation_time
									);
									$post_data_supplier['data']['supplier_id']=base64_encode($_POST['booking_details_list']['booking_supplier_list'][0]['supplier_id']);
									$post_data_str_supplier=json_encode($post_data_supplier);
									$ch = curl_init();
									curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
									curl_setopt($ch, CURLOPT_HEADER, false);
									curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
									curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
									curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.SUPPLIER_API_PATH."supplier/read.php");
									curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_supplier);
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
									$return_data_supplier = curl_exec($ch);
									curl_close($ch);
									//print_r($return_data_supplier);
									$return_data_arr_supplier=json_decode($return_data_supplier, true);
									if(!isset($return_data_arr_supplier['status'])):
										//$data['status'] = 'error';
										//$data['msg']="Some error has been occure during execution.";
									elseif($return_data_arr_supplier['status']=="success"):
										if(!empty($return_data_arr_supplier['results'])):
											$supplier_email_template = tools::find("first", TM_EMAIL_TEMPLATES, $value='id, template_title, template_subject, template_body, status', "WHERE id=:id AND status=:status ", array(':id'=>35, ':status'=>1));
											if(!empty($supplier_email_template)):
												$supplier_url_details='<a href="'.DOMAIN_NAME_PATH_SUPPLIER.'login?auto_login_id='.base64_encode(SECURITY_SALT.$return_data_arr_supplier['results']['id']."supplier".AUTO_LOGIN_SECURITY_KEY).'&booking_id='.base64_encode($booking_id).'" title="View Order">'.DOMAIN_NAME_PATH_SUPPLIER.'login?auto_login_id='.base64_encode(SECURITY_SALT.$return_data_arr_supplier['results']['id']."supplier".AUTO_LOGIN_SECURITY_KEY).'&booking_id='.base64_encode($booking_id).'</a>';
												$supplier_mail_Body=str_replace(array("[FIRST_NAME]", "[LAST_NAME]", "[DETAILS_URL]"), array($return_data_arr_supplier['results']['first_name'], $return_data_arr_supplier['results']['last_name'], $supplier_url_details), $supplier_email_template['template_body']);
												@tools::Send_SMTP_Mail($return_data_arr_supplier['results']['email_address'], FROM_EMAIL, '', $supplier_email_template['template_subject'], $supplier_mail_Body);
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
						else:
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
											$supplier_email_template = tools::find("first", TM_EMAIL_TEMPLATES, $value='id, template_title, template_subject, template_body, status', "WHERE id=:id AND status=:status ", array(':id'=>35, ':status'=>1));
											if(!empty($supplier_email_template)):
												$supplier_url_details='<a href="'.DOMAIN_NAME_PATH_SUPPLIER.'login?auto_login_id='.base64_encode(SECURITY_SALT.$return_data_arr_supplier['results'][0]['id']."supplier".AUTO_LOGIN_SECURITY_KEY).'&booking_id='.base64_encode($booking_id).'" title="View Order">'.DOMAIN_NAME_PATH_SUPPLIER.'login?auto_login_id='.base64_encode(SECURITY_SALT.$return_data_arr_supplier['results'][0]['id']."supplier".AUTO_LOGIN_SECURITY_KEY).'&booking_id='.base64_encode($booking_id).'</a>';
												$supplier_mail_Body=str_replace(array("[FIRST_NAME]", "[LAST_NAME]", "[DETAILS_URL]"), array($return_data_arr_supplier['results'][0]['first_name'], $return_data_arr_supplier['results'][0]['last_name'], $supplier_url_details), $supplier_email_template['template_body']);
												@tools::Send_SMTP_Mail($return_data_arr_supplier['results'][0]['email_address'], FROM_EMAIL, '', $supplier_email_template['template_subject'], $supplier_mail_Body);
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
										$hotel_email_template = tools::find("first", TM_EMAIL_TEMPLATES, $value='id, template_title, template_subject, template_body, status', "WHERE id=:id AND status=:status ", array(':id'=>33, ':status'=>1));
										if(!empty($hotel_email_template)):
											foreach($return_data_arr_hotel['results'] as $hotel_key=>$hotel_val):
												$hotel_url_details='<a href="'.DOMAIN_NAME_PATH_HOTEL.'login?auto_login_id='.base64_encode(SECURITY_SALT.$hotel_val['id']."hotel".AUTO_LOGIN_SECURITY_KEY).'&booking_id='.base64_encode($booking_id).'" title="View Order">'.DOMAIN_NAME_PATH_HOTEL.'login?auto_login_id='.base64_encode(SECURITY_SALT.$hotel_val['id']."hotel".AUTO_LOGIN_SECURITY_KEY).'&booking_id='.base64_encode($booking_id).'</a>';
												$hotel_mail_Body=str_replace(array("[HOTEL_NAME]", "[DETAILS_URL]"), array($hotel_val['hotel_name'], $hotel_url_details), $hotel_email_template['template_body']);
												@tools::Send_SMTP_Mail($hotel_val['email_address'], FROM_EMAIL, '', $hotel_email_template['template_subject'], $hotel_mail_Body);
											endforeach;
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
							$post_data['data']['agent_id']=$_SESSION['step_1']['agent_name'];
							$post_data_str=json_encode($post_data);
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
							curl_setopt($ch, CURLOPT_HEADER, false);
							curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
							curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
							curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."agent/booking-agent.php");
							curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
							$return_data = curl_exec($ch);
							curl_close($ch);
							//print_r($return_data);
							$return_data_arr=json_decode($return_data, true);
							$tour_data=array();
							if(!isset($return_data_arr['status'])):
								//$data['status'] = 'error';
								//$data['msg']="Some error has been occure during execution.";
							elseif($return_data_arr['status']=="success"):
								if($return_data_arr['results']['type']=="A"):
									$agent_email_template = tools::find("first", TM_EMAIL_TEMPLATES, $value='id, template_title, template_subject, template_body, status', "WHERE id=:id AND status=:status ", array(':id'=>32, ':status'=>1));
									if(!empty($agent_email_template)):
										$agent_url_details="";
										$agent_mail_Body=str_replace(array("[FIRST_NAME]", "[LAST_NAME]", "[DETAILS_URL]"), array($return_data_arr['results']['first_name'], $return_data_arr['results']['last_name'], $agent_url_details), $agent_email_template['template_body']);
										@tools::Send_SMTP_Mail($return_data_arr['results']['email_address'], FROM_EMAIL, '', $agent_email_template['template_subject'], $agent_mail_Body);
									endif;
									if(isset($return_data_arr['result_gsm']) && !empty($return_data_arr['result_gsm'])):
										$gsm_email_template = tools::find("first", TM_EMAIL_TEMPLATES, $value='id, template_title, template_subject, template_body, status', "WHERE id=:id AND status=:status ", array(':id'=>32, ':status'=>1));
										if(!empty($gsm_email_template)):
											$gsm_url_details="";
											$gsm_mail_Body=str_replace(array("[FIRST_NAME]", "[LAST_NAME]", "[DETAILS_URL]"), array($return_data_arr['result_gsm']['first_name'], $return_data_arr['result_gsm']['last_name'], $gsm_url_details), $gsm_email_template['template_body']);
											@tools::Send_SMTP_Mail($return_data_arr['result_gsm']['email_address'], FROM_EMAIL, '', $gsm_email_template['template_subject'], $gsm_mail_Body);
										endif;
									endif;
								elseif($return_data_arr['results']['type']=="G"):
									$gsm_email_template = tools::find("first", TM_EMAIL_TEMPLATES, $value='id, template_title, template_subject, template_body, status', "WHERE id=:id AND status=:status ", array(':id'=>32, ':status'=>1));
									if(!empty($gsm_email_template)):
										$gsm_url_details="";
										$gsm_mail_Body=str_replace(array("[FIRST_NAME]", "[LAST_NAME]", "[DETAILS_URL]"), array($return_data_arr['results']['first_name'], $return_data_arr['results']['last_name'], $gsm_url_details), $gsm_email_template['template_body']);
										@tools::Send_SMTP_Mail($return_data_arr['results']['email_address'], FROM_EMAIL, '', $gsm_email_template['template_subject'], $gsm_mail_Body);
									endif;
								endif;
								//$data['status'] = 'success';
								//$data['msg']="Data received successfully";
							else:
								//$data['status'] = 'error';
								//$data['msg'] = $return_data_arr['msg'];
							endif;
						endif;
						if((isset($_SESSION['step_1']['booking_type']) && $_SESSION['step_1']['booking_type']=="agent") || $_POST['booking_details_list']['booking_type']=="agent"):
							$post_data['data']=array();
							if($_SESSION['step_1']['booking_type']=="agent"):
								$post_data['data']['agent_id']=$_SESSION['step_1']['agent_name'];
								$post_data['data']['total_price']=$_POST['total_price'];
								$post_data['data']['quotation_name']="";
								if(isset($server_data['data']['step_5']['quotation_name'])):
									$post_data['data']['quotation_name']=$server_data['data']['step_5']['quotation_name'];
								elseif(isset($_POST['booking_details_list']['quotation_name'])):
									$post_data['data']['quotation_name']=$_POST['booking_details_list']['quotation_name'];
								endif;
							endif;
							if($_POST['booking_details_list']['booking_type']=="agent"):
								$post_data['data']['prev_agent_id']=$_POST['booking_details_list']['agent_id'];
								$post_data['data']['prev_total_price']=$_POST['booking_details_list']['total_amount'];
								$post_data['data']['prev_quotation_name']=$_POST['booking_details_list']['quotation_name'];
							endif;
							$post_data_str=json_encode($post_data);
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
							curl_setopt($ch, CURLOPT_HEADER, false);
							curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
							curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
							curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."agent/update-booking-agent-credit.php");
							curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
							$return_data = curl_exec($ch);
							curl_close($ch);
							//print_r($return_data);
							$return_data_arr=json_decode($return_data, true);
							$tour_data=array();
							if(!isset($return_data_arr['status'])):
								//$data['status'] = 'error';
								//$data['msg']="Some error has been occure during execution.";
							elseif($return_data_arr['status']=="success"):
								//$data['status'] = 'success';
								//$data['msg']="Data received successfully";
							else:
								//$data['status'] = 'error';
								//$data['msg'] = $return_data_arr['msg'];
							endif;
						endif;
						$data['status'] = 'success';
						$data['msg']="Booking has been updated successfully.";
					else:
						$data['status'] = 'error';
						$data['msg'] = $return_data_arr_booking['msg'];
					endif;
				endif;
			else:
				$data['status'] = 'error';
				$data['msg'] = $autentication_data->msg;
			endif;
			//$data['status']="success";
			//$data['msg']="Booking has been saved successfully.";
		endif;
	endif;
	echo json_encode($data);
?>