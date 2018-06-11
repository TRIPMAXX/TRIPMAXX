<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
	if(isset($_GET['booking_id']) && $_GET['booking_id']!=""):
		$autentication_data_booking=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.BOOKING_API_PATH."authorized.php"));
		if(isset($autentication_data_booking->status)):
			if($autentication_data_booking->status=="success"):
				$post_data_booking['token']=array(
					"token"=>$autentication_data_booking->results->token,
					"token_timeout"=>$autentication_data_booking->results->token_timeout,
					"token_generation_time"=>$autentication_data_booking->results->token_generation_time
				);
				$post_data_booking['data']['booking_id']=base64_decode($_GET['booking_id']);
				$post_data_str_booking=json_encode($post_data_booking);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.BOOKING_API_PATH."booking/read.php");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_booking);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				$return_data_booking = curl_exec($ch);
				curl_close($ch);
				$return_data_arr_booking=json_decode($return_data_booking, true);
				if(!isset($return_data_arr_booking['status'])):
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH']="Some error has been occure during execution.";
				elseif($return_data_arr_booking['status']=="success"):
					$booking_details_list=$return_data_arr_booking['results'][0];
					if(isset($_POST) && isset($_POST['other_supplier_id']) && $_POST['other_supplier_id']!="")
					{
						$post_data_booking['data']['supplier_id']=$_POST['other_supplier_id'];
						$post_data_str_booking=json_encode($post_data_booking);
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
						curl_setopt($ch, CURLOPT_HEADER, false);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
						curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
						curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.BOOKING_API_PATH."booking/update-booking-supplier.php");
						curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_booking);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
						$return_data_update_booking = curl_exec($ch);
						curl_close($ch);
						$return_data_arr_update_booking=json_decode($return_data_update_booking, true);
						if(!isset($return_data_arr_update_booking['status'])):
							$_SESSION['SET_TYPE'] = 'error';
							$_SESSION['SET_FLASH']="Some error has been occure during execution.";
						elseif($return_data_arr_update_booking['status']=="success"):
							$dmc_email_template = tools::find("first", TM_EMAIL_TEMPLATES, $value='id, template_title, template_subject, template_body, status', "WHERE id=:id AND status=:status ", array(':id'=>23, ':status'=>1));
							if(!empty($dmc_email_template)):
								$dmc_url_details='<a href="'.DOMAIN_NAME_PATH_ADMIN.'login?auto_login_id='.base64_encode(SECURITY_SALT.$_SESSION['SESSION_DATA']['id']."dmc".AUTO_LOGIN_SECURITY_KEY).'&booking_id='.base64_encode($booking_id).'" title="View Order">'.DOMAIN_NAME_PATH_ADMIN.'login?auto_login_id='.base64_encode(SECURITY_SALT.$_SESSION['SESSION_DATA']['id']."dmc".AUTO_LOGIN_SECURITY_KEY).'&booking_id='.base64_encode($booking_id).'</a>';
								$dmc_mail_Body=str_replace(array("[FIRST_NAME]", "[LAST_NAME]", "[DETAILS_URL]"), array($_SESSION['SESSION_DATA']['first_name'], $_SESSION['SESSION_DATA']['last_name'], $dmc_url_details), $dmc_email_template['template_body']);
								@tools::Send_SMTP_Mail($_SESSION['SESSION_DATA']['email_address'], FROM_EMAIL, '', $dmc_email_template['template_subject'], $dmc_mail_Body);
							endif;
							if(isset($booking_details_list['booking_type']) && $booking_details_list['booking_type']=="agent" && isset($booking_details_list['agent_id']) && $booking_details_list['agent_id']!=""):
								$autentication_data_agent=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."authorized.php"));
								if(isset($autentication_data_agent->status)):
									if($autentication_data_agent->status=="success"):
										$post_data_agent['token']=array(
											"token"=>$autentication_data_agent->results->token,
											"token_timeout"=>$autentication_data_agent->results->token_timeout,
											"token_generation_time"=>$autentication_data_agent->results->token_generation_time
										);
										$post_data_agent['data']['agent_id']=$booking_details_list['agent_id'];
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
										$return_data_agent_arr=json_decode($return_data_agent, true);
										$tour_data=array();
										if(!isset($return_data_agent_arr['status'])):
											//$data['status'] = 'error';
											//$data['msg']="Some error has been occure during execution.";
										elseif($return_data_agent_arr['status']=="success"):
											if($return_data_agent_arr['result']['type']=="A"):
												$agent_email_template = tools::find("first", TM_EMAIL_TEMPLATES, $value='id, template_title, template_subject, template_body, status', "WHERE id=:id AND status=:status ", array(':id'=>24, ':status'=>1));
												if(!empty($agent_email_template)):
													$agent_url_details="";
													$agent_mail_Body=str_replace(array("[FIRST_NAME]", "[LAST_NAME]", "[DETAILS_URL]"), array($return_data_agent_arr['result']['first_name'], $return_data_agent_arr['result']['last_name'], $agent_url_details), $agent_email_template['template_body']);
													@tools::Send_SMTP_Mail($return_data_agent_arr['result']['email_address'], FROM_EMAIL, '', $agent_email_template['template_subject'], $agent_mail_Body);
												endif;
												if(isset($return_data_agent_arr['result_gsm']) && !empty($return_data_agent_arr['result_gsm'])):
													$gsm_email_template = tools::find("first", TM_EMAIL_TEMPLATES, $value='id, template_title, template_subject, template_body, status', "WHERE id=:id AND status=:status ", array(':id'=>25, ':status'=>1));
													if(!empty($gsm_email_template)):
														$gsm_url_details="";
														$gsm_mail_Body=str_replace(array("[FIRST_NAME]", "[LAST_NAME]", "[DETAILS_URL]"), array($return_data_agent_arr['result_gsm']['first_name'], $return_data_agent_arr['result_gsm']['last_name'], $gsm_url_details), $gsm_email_template['template_body']);
														@tools::Send_SMTP_Mail($return_data_agent_arr['result_gsm']['email_address'], FROM_EMAIL, '', $gsm_email_template['template_subject'], $gsm_mail_Body);
													endif;
												endif;
											elseif($return_data_agent_arr['result']['type']=="G"):
												$gsm_email_template = tools::find("first", TM_EMAIL_TEMPLATES, $value='id, template_title, template_subject, template_body, status', "WHERE id=:id AND status=:status ", array(':id'=>24, ':status'=>1));
												if(!empty($gsm_email_template)):
													$gsm_url_details="";
													$gsm_mail_Body=str_replace(array("[FIRST_NAME]", "[LAST_NAME]", "[DETAILS_URL]"), array($return_data_agent_arr['result']['first_name'], $return_data_agent_arr['result']['last_name'], $gsm_url_details), $gsm_email_template['template_body']);
													@tools::Send_SMTP_Mail($return_data_agent_arr['result']['email_address'], FROM_EMAIL, '', $gsm_email_template['template_subject'], $gsm_mail_Body);
												endif;
											endif;
											//$data['status'] = 'success';
											//$data['msg']="Data received successfully";
										else:
											//$data['status'] = 'error';
											//$data['msg'] = $return_data_arr['msg'];
										endif;
									endif;
								endif;
							endif;
							$autentication_data_supplier=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.SUPPLIER_API_PATH."authorized.php"));
							if(isset($autentication_data_supplier->status)):
								if($autentication_data_supplier->status=="success"):
									$post_data_supplier['token']=array(
										"token"=>$autentication_data_supplier->results->token,
										"token_timeout"=>$autentication_data_supplier->results->token_timeout,
										"token_generation_time"=>$autentication_data_supplier->results->token_generation_time
									);
									$post_data_supplier['data']['supplier_id']=base64_encode($_POST['other_supplier_id']);
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
									$return_data_arr_supplier=json_decode($return_data_supplier, true);
									if(!isset($return_data_arr_supplier['status'])):
										//$data['status'] = 'error';
										//$data['msg']="Some error has been occure during execution.";
									elseif($return_data_arr_supplier['status']=="success"):
										if(!empty($return_data_arr_supplier['results'])):
											$supplier_email_template = tools::find("first", TM_EMAIL_TEMPLATES, $value='id, template_title, template_subject, template_body, status', "WHERE id=:id AND status=:status ", array(':id'=>13, ':status'=>1));
											if(!empty($supplier_email_template)):
												$supplier_url_details='<a href="'.DOMAIN_NAME_PATH_SUPPLIER.'login?auto_login_id='.base64_encode(SECURITY_SALT.$return_data_arr_supplier['results']['id']."supplier".AUTO_LOGIN_SECURITY_KEY).'&booking_id='.base64_encode($booking_details_list['id']).'" title="View Order">'.DOMAIN_NAME_PATH_SUPPLIER.'login?auto_login_id='.base64_encode(SECURITY_SALT.$return_data_arr_supplier['results']['id']."supplier".AUTO_LOGIN_SECURITY_KEY).'&booking_id='.base64_encode($booking_details_list['id']).'</a>';
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
								//$data['msg'] = $autentication_data_supplier->msg;
							endif;
							$_SESSION['SET_TYPE'] = 'success';
							$_SESSION['SET_FLASH']="Booking supplier updated successfully.";
							header("location:view_booking?booking_id=".$_GET['booking_id']);
							exit;
						else:
							$_SESSION['SET_TYPE'] = 'error';
							$_SESSION['SET_FLASH'] = $return_data_arr_update_booking['msg'];
						endif;
					};
					if(isset($_POST) && isset($_POST['booking_approval_status']) && $_POST['booking_approval_status']!="")
					{
						$post_data_booking['data']['status']=$_POST['booking_approval_status'];
						$post_data_booking['data']['id']=$booking_details_list['id'];
						$post_data_str_booking=json_encode($post_data_booking);
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
						curl_setopt($ch, CURLOPT_HEADER, false);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
						curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
						curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.BOOKING_API_PATH."booking/update.php");
						curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_booking);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
						$return_data_booking = curl_exec($ch);
						curl_close($ch);
						$return_data_arr_booking_update=json_decode($return_data_booking, true);
						if(!isset($return_data_arr_booking_update['status'])):
							$_SESSION['SET_TYPE'] = 'error';
							$_SESSION['SET_FLASH']="Some error has been occure during execution.";
						elseif($return_data_arr_booking_update['status']=="success"):
							$_SESSION['SET_TYPE'] = 'success';
							$_SESSION['SET_FLASH']=$return_data_arr_booking_update['msg'];
							$booking_id=$booking_details_list['id'];
							if($_POST['booking_approval_status']==1 || $_POST['booking_approval_status']==2):
								if($_POST['booking_approval_status']==1):
								elseif($_POST['booking_approval_status']==2):
								endif;
								$dmc_email_template = tools::find("first", TM_EMAIL_TEMPLATES, $value='id, template_title, template_subject, template_body, status', "WHERE id=:id AND status=:status ", array(':id'=>36, ':status'=>1));
								if(!empty($dmc_email_template)):
									$dmc_url_details='<a href="'.DOMAIN_NAME_PATH_ADMIN.'login?auto_login_id='.base64_encode(SECURITY_SALT.$_SESSION['SESSION_DATA']['id']."dmc".AUTO_LOGIN_SECURITY_KEY).'&booking_id='.base64_encode($booking_id).'" title="View Order">'.DOMAIN_NAME_PATH_ADMIN.'login?auto_login_id='.base64_encode(SECURITY_SALT.$_SESSION['SESSION_DATA']['id']."dmc".AUTO_LOGIN_SECURITY_KEY).'&booking_id='.base64_encode($booking_id).'</a>';
									$dmc_mail_Body=str_replace(array("[FIRST_NAME]", "[LAST_NAME]", "[DETAILS_URL]"), array($_SESSION['SESSION_DATA']['first_name'], $_SESSION['SESSION_DATA']['last_name'], $dmc_url_details), $dmc_email_template['template_body']);
									@tools::Send_SMTP_Mail($_SESSION['SESSION_DATA']['email_address'], FROM_EMAIL, '', $dmc_email_template['template_subject'], $dmc_mail_Body);
								endif;
								if(isset($booking_details_list) && isset($booking_details_list['booking_supplier_list']) && !empty($booking_details_list['booking_supplier_list'])):
									$autentication_data_supplier=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.SUPPLIER_API_PATH."authorized.php"));
									if(isset($autentication_data_supplier->status)):
										if($autentication_data_supplier->status=="success"):
											$post_data_supplier['token']=array(
												"token"=>$autentication_data_supplier->results->token,
												"token_timeout"=>$autentication_data_supplier->results->token_timeout,
												"token_generation_time"=>$autentication_data_supplier->results->token_generation_time
											);
											$post_data_supplier['data']['supplier_id']=base64_encode($booking_details_list['booking_supplier_list'][0]['supplier_id']);
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
											$return_data_arr_supplier=json_decode($return_data_supplier, true);
											if(!isset($return_data_arr_supplier['status'])):
												//$data['status'] = 'error';
												//$data['msg']="Some error has been occure during execution.";
											elseif($return_data_arr_supplier['status']=="success"):
												if(!empty($return_data_arr_supplier['results'])):
													$supplier_email_template = tools::find("first", TM_EMAIL_TEMPLATES, $value='id, template_title, template_subject, template_body, status', "WHERE id=:id AND status=:status ", array(':id'=>37, ':status'=>1));
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
													$supplier_email_template = tools::find("first", TM_EMAIL_TEMPLATES, $value='id, template_title, template_subject, template_body, status', "WHERE id=:id AND status=:status ", array(':id'=>37, ':status'=>1));
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
								$return_hotel_ids=array();
								if(isset($booking_details_list) && isset($booking_details_list['booking_destination_list']) && !empty($booking_details_list['booking_destination_list'])):
									foreach($booking_details_list['booking_destination_list'] as $hotel_key=>$hotel_val):
										array_push($return_hotel_ids, $hotel_val['booking_hotel_list'][0]['hotel_id']);
									endforeach;
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
												$hotel_email_template = tools::find("first", TM_EMAIL_TEMPLATES, $value='id, template_title, template_subject, template_body, status', "WHERE id=:id AND status=:status ", array(':id'=>38, ':status'=>1));
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
								$autentication_data=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."authorized.php"));
								if(isset($autentication_data->status)):
									if($autentication_data->status=="success"):
										$post_data['token']=array(
											"token"=>$autentication_data->results->token,
											"token_timeout"=>$autentication_data->results->token_timeout,
											"token_generation_time"=>$autentication_data->results->token_generation_time
										);
										if(isset($booking_details_list['booking_type']) && $booking_details_list['booking_type']=="agent" && isset($booking_details_list['agent_id']) && $booking_details_list['agent_id']!=""):
											$post_data['data']['agent_id']=$booking_details_list['agent_id'];
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
													$agent_email_template = tools::find("first", TM_EMAIL_TEMPLATES, $value='id, template_title, template_subject, template_body, status', "WHERE id=:id AND status=:status ", array(':id'=>39, ':status'=>1));
													if(!empty($agent_email_template)):
														$agent_url_details="";
														$agent_mail_Body=str_replace(array("[FIRST_NAME]", "[LAST_NAME]", "[DETAILS_URL]"), array($return_data_arr['results']['first_name'], $return_data_arr['results']['last_name'], $agent_url_details), $agent_email_template['template_body']);
														@tools::Send_SMTP_Mail($return_data_arr['results']['email_address'], FROM_EMAIL, '', $agent_email_template['template_subject'], $agent_mail_Body);
													endif;
													if(isset($return_data_arr['result_gsm']) && !empty($return_data_arr['result_gsm'])):
														$gsm_email_template = tools::find("first", TM_EMAIL_TEMPLATES, $value='id, template_title, template_subject, template_body, status', "WHERE id=:id AND status=:status ", array(':id'=>39, ':status'=>1));
														if(!empty($gsm_email_template)):
															$gsm_url_details="";
															$gsm_mail_Body=str_replace(array("[FIRST_NAME]", "[LAST_NAME]", "[DETAILS_URL]"), array($return_data_arr['result_gsm']['first_name'], $return_data_arr['result_gsm']['last_name'], $gsm_url_details), $gsm_email_template['template_body']);
															@tools::Send_SMTP_Mail($return_data_arr['result_gsm']['email_address'], FROM_EMAIL, '', $gsm_email_template['template_subject'], $gsm_mail_Body);
														endif;
													endif;
												elseif($return_data_arr['results']['type']=="G"):
													$gsm_email_template = tools::find("first", TM_EMAIL_TEMPLATES, $value='id, template_title, template_subject, template_body, status', "WHERE id=:id AND status=:status ", array(':id'=>39, ':status'=>1));
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
										if(isset($booking_details_list['booking_type']) && $booking_details_list['booking_type']=="agent" && isset($booking_details_list['agent_id']) && $booking_details_list['agent_id']!="" && $_POST['booking_approval_status']==2):
											$post_data['data']=array();
											$post_data['data']['prev_agent_id']=$booking_details_list['agent_id'];
											$post_data['data']['prev_total_price']=$booking_details_list['total_amount'];
											$post_data['data']['prev_quotation_name']=$booking_details_list['quotation_name'];
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
									endif;
									else:
										//$data['status'] = 'error';
										//$data['msg'] = $autentication_data->msg;
									endif;
								endif;
							header("location:".DOMAIN_NAME_PATH_ADMIN.'view_booking?booking_id='.$_GET['booking_id']);
							exit;
						else:
							$_SESSION['SET_TYPE'] = 'error';
							$_SESSION['SET_FLASH'] = $return_data_arr_booking_update['msg'];
						endif;
					};
					if(isset($_POST) && isset($_POST['booking_payment_status']) && $_POST['booking_payment_status']!="")
					{
						$post_data_booking['data']['payment_status']=$_POST['booking_payment_status'];
						$post_data_booking['data']['payment_date']=date("Y-m-d H:i:s");
						$post_data_booking['data']['id']=$booking_details_list['id'];
						$post_data_str_booking=json_encode($post_data_booking);
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
						curl_setopt($ch, CURLOPT_HEADER, false);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
						curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
						curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.BOOKING_API_PATH."booking/update.php");
						curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_booking);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
						$return_data_booking = curl_exec($ch);
						curl_close($ch);
						$return_data_arr_booking_update=json_decode($return_data_booking, true);
						if(!isset($return_data_arr_booking_update['status'])):
							$_SESSION['SET_TYPE'] = 'error';
							$_SESSION['SET_FLASH']="Some error has been occure during execution.";
						elseif($return_data_arr_booking_update['status']=="success"):
							$_SESSION['SET_TYPE'] = 'success';
							$_SESSION['SET_FLASH']="Payment status updated successfully.";
							header("location:".DOMAIN_NAME_PATH_ADMIN.'view_booking?booking_id='.$_GET['booking_id']);
							exit;
						else:
							$_SESSION['SET_TYPE'] = 'error';
							$_SESSION['SET_FLASH'] = $return_data_arr_booking_update['msg'];
						endif;
					};
					//print_r($booking_details_list);
				else:
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH'] = $return_data_arr_booking['msg'];
				endif;
			endif;
		else:
			$_SESSION['SET_TYPE'] = 'error';
			$_SESSION['SET_FLASH'] = $autentication_data->msg;
		endif;
	else:
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = 'Some data missing.';
		header("location:bookings");
		exit;
	endif;
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>VIEW BOOKING</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<script type="text/javascript">
	<!--
		$(function(){
			$("#booking_approval_status").change(function(){
				$("#booking_approval_status_form").submit();
			});
			$("#booking_payment_status").change(function(){
				$("#booking_payment_status_form").submit();
			});
		});
	//-->
	</script>
</head>
<body class="skin-purple">
	<div class="wrapper">
		<!-- TOP HEADER -->
		<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'header.php');?>		
		<!-- TOP HEADER -->

		<!-- LEFT MENU -->
		<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'menu.php');?>
		<!-- LEFT MENU -->
		
		<!-- BODY -->
		<div class="content-wrapper">
			<section class="content-header">
				<h1>View Booking</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">View Booking</li>
				</ol>
			</section>
			<section class="content">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<?php
						if(isset($booking_details_list) && !empty($booking_details_list)):
							$number_of_person=$number_of_adult=$number_of_child=0;
							$audlt_arr=json_decode($booking_details_list['adult'], true);
							foreach($audlt_arr as $adult_key=>$adult_val):
								if($adult_val!="")
									$number_of_adult=$number_of_adult+$adult_val;
							endforeach;
							$child_arr=json_decode($booking_details_list['child'], true);
							foreach($child_arr as $child_key=>$child_val):
								if(isset($child_val['child']) && $child_val['child']!="")
									$number_of_child=$number_of_child+$child_val['child'];
							endforeach;
							$number_of_person=$number_of_adult+$number_of_child;
							$checkin_date = strtotime($booking_details_list['checkin_date']);
							$checkout_date = strtotime($booking_details_list['checkout_date']);
							$datediff = $checkout_date - $checkin_date;
							$destination_str="";
							$service_arr=array("Hotel");
							foreach($booking_details_list['booking_destination_list'] as $dest_key=>$dest_val):
								if($destination_str!="")
									$destination_str.=", ";
								$destination_str.=$dest_val['ci_name'];
								if(isset($dest_val['booking_tour_list']) && !empty($dest_val['booking_tour_list']) && !in_array("Tour", $service_arr))
									array_push($service_arr, "Tour");
								if(isset($dest_val['booking_transfer_list']) && !empty($dest_val['booking_transfer_list']) && !in_array("Transfer", $service_arr))
									array_push($service_arr, "Transfer");
							endforeach;
						?>
						<div class="box box-primary">
							<table aria-describedby="example1_info" id="example" class="table table-bordered table-striped dataTable">
								<thead>
									<tr role="row">
										<th style = "text-align:center;">Pax</th>
										<th style = "text-align:center;">Quote Date</th>
										<th style = "text-align:center;">Destination</th>
										<th style = "text-align:center;">Booking Date</th>
										<th style = "text-align:center;">Payment Type</th>
										<th style = "text-align:center;">Payment Status</th>
										<th style = "text-align:center;">Payment Date</th>
										<th style = "text-align:center;">Approval</th>
									</tr>
								</thead>
								<tbody aria-relevant="all" aria-live="polite" role="alert">
									<tr class="odd">
										<td style = "text-align:center;"><?php echo $number_of_person;?></td>
										<td style = "text-align:center;"><?php echo tools::module_date_format($booking_details_list['creation_date'], "Y-m-d H:i:s");?></td>
										<td style = "text-align:center;"><?php echo $destination_str;?></td>
										<td style = "text-align:center;"><?php echo tools::module_date_format($booking_details_list['checkin_date'])." - ".tools::module_date_format($booking_details_list['checkout_date']);?></td>
										<td style = "text-align:center;"><?php echo $booking_details_list['payment_type'];?></td>
										<td style = "text-align:center;">
											<?php
											if(isset($booking_details_list['payment_status']) && $booking_details_list['payment_status']=="U")
											{
											?>
											<form method="post" name="booking_payment_status_form" id="booking_payment_status_form" action="">
												<select name="booking_payment_status" id="booking_payment_status" class="btn-warning">
													<option value="U" class="btn-warning">Unpaid</option>
													<option value="P" class="btn-success">Paid</option>
												</select>
											</form>
											<?php
											}
											elseif(isset($booking_details_list['payment_status']) && $booking_details_list['payment_status']=="P")
											{
											?>
											<span style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" class="btn-success">Paid</span>
											<?php
											}
											else
											{
												echo "N/A";
											}
											?>
										</td>
										<td style = "text-align:center;"><?php echo($booking_details_list['payment_date']!="" ? tools::module_date_format($booking_details_list['payment_date'], "Y-m-d H:i:s") : "N/A");?></td>
										<td style = "text-align:center;">
											<?php
											if(isset($booking_details_list['status']) && isset($booking_details_list['status']) && $booking_details_list['status']==0)
											{
											?>
											<form method="post" name="booking_approval_status_form" id="booking_approval_status_form" action="">
												<select name="booking_approval_status" id="booking_approval_status" class="btn-warning">
													<option value="0" class="btn-warning">Pending</option>
													<option value="1" class="btn-success">Accept</option>
													<option value="2" class="btn-danger">Reject</option>
												</select>
											</form>
											<?php
											}
											elseif(isset($booking_details_list) && isset($booking_details_list['status']) && $booking_details_list['status']==1)
											{
											?>
											<span style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" class="btn-success">Accepted</span>
											<?php
											}
											elseif(isset($booking_details_list) && isset($booking_details_list['status']) && $booking_details_list['status']==2)
											{
											?>
											<span style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" class="btn-danger">Rejected</span>
											<?php
											}
											?>
										</td>
									</tr>
								</tbody>
							</table>
							<?php
							$hotel_html='';
							$tour_html='';
							$transfer_html='';
							$hotel_price=$tour_price=$transfer_price=0.00;
							if(isset($booking_details_list['booking_destination_list']) && !empty($booking_details_list['booking_destination_list'])):
								foreach($booking_details_list['booking_destination_list'] as $desti_key=>$desti_val):
									if(isset($desti_val['booking_hotel_list']) && !empty($desti_val['booking_hotel_list'])):
										foreach($desti_val['booking_hotel_list'] as $hotel_key=>$hotel_val):
											$autentication_data_hotel=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."authorized.php"));
											if(isset($autentication_data_hotel->status)):
												if($autentication_data_hotel->status=="success"):
													$post_data_hotel['token']=array(
														"token"=>$autentication_data_hotel->results->token,
														"token_timeout"=>$autentication_data_hotel->results->token_timeout,
														"token_generation_time"=>$autentication_data_hotel->results->token_generation_time
													);
													$post_data_hotel['data']['hotel_id']=$hotel_val['hotel_id'];
													$post_data_hotel['data']['room_id']=$hotel_val['room_id'];
													$post_data_str_hotel=json_encode($post_data_hotel);
													$ch = curl_init();
													curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
													curl_setopt($ch, CURLOPT_HEADER, false);
													curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
													curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
													curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."hotel/find-booked-hotel.php");
													curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_hotel);
													curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
													$return_data_hotel = curl_exec($ch);
													curl_close($ch);
													$return_data_arr_hotel=json_decode($return_data_hotel, true);
													if(!isset($return_data_arr_hotel['status'])):
														$data['status'] = 'error';
														$data['msg']="Some error has been occure during execution.";
													elseif($return_data_arr_hotel['status']=="success"):
														if(isset($return_data_arr_hotel['find_hotel']) && isset($return_data_arr_hotel['find_room'])):
															ob_start();
														?>
															<tr class="odd">
																<td style = "text-align:left;"><?php echo $return_data_arr_hotel['find_hotel']['hotel_name'];?></td>
																<td style = "text-align:center;">
																	<?= $return_data_arr_hotel['find_room']['room_type'];?>
																	<br/>
																	<font color="red"><?= $return_data_arr_hotel['find_room']['room_description'];?></font>
																</td>
																<td style = "text-align:center;"><?php echo tools::module_date_format($hotel_val['booking_start_date'], "Y-m-d");?></td>
																<td style = "text-align:center;"><?php echo tools::module_date_format($hotel_val['booking_end_date'], "Y-m-d");?></td>
																<td style = "text-align:center;"><?= $booking_details_list['number_of_rooms'];?></td>
																<td style = "text-align:center;"><?php echo $desti_val['no_of_night'];?></td><td style = "text-align:center;">
																	<?php 
																	$agent_commision=($hotel_val['price'] * $hotel_val['agent_markup_percentage'])/100;
																	echo $booking_details_list['currency_code'].number_format($hotel_val['price']+$agent_commision, 2, ".", ",");
																	$hotel_price=$hotel_price+($hotel_val['price']+$agent_commision);
																	?>
																</td>
																<td style = "text-align:center;">
																	<?php
																	if($hotel_val['status']==0):
																		echo '<span style="padding: 3px;" class="btn-warning">Pending</span>';
																	elseif($hotel_val['status']==1):
																		echo '<span style="padding: 3px;" class="btn-success">Action</span>';
																	elseif($hotel_val['status']==2):
																		echo '<span style="padding: 3px;" class="btn-danger">Reject</span>';
																	else:
																		echo 'N/A';
																	endif;
																	?>
																</td>
															</tr>
														<?php
															$each_hotel_html=ob_get_clean();
															$hotel_html.=$each_hotel_html;
														endif;
													else:
														$data['status'] = 'error';
														$data['msg'] = $return_data_arr_hotel['msg'];
													endif;
												endif;
											else:
												$data['status'] = 'error';
												$data['msg'] = $autentication_data->msg;
											endif;
										endforeach;
									endif;
									if(isset($desti_val['booking_tour_list']) && !empty($desti_val['booking_tour_list'])):
										foreach($desti_val['booking_tour_list'] as $tour_key=>$tour_val):
											$autentication_data_tour=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.TOUR_API_PATH."authorized.php"));
											if(isset($autentication_data_tour->status)):
												if($autentication_data_tour->status=="success"):
													$post_data_tour['token']=array(
														"token"=>$autentication_data_tour->results->token,
														"token_timeout"=>$autentication_data_tour->results->token_timeout,
														"token_generation_time"=>$autentication_data_tour->results->token_generation_time
													);
													$post_data_tour['data']['tour_id']=$tour_val['tour_id'];
													$post_data_tour['data']['offer_id']=$tour_val['offer_id'];
													$post_data_str_tour=json_encode($post_data_tour);
													$ch = curl_init();
													curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
													curl_setopt($ch, CURLOPT_HEADER, false);
													curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
													curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
													curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.TOUR_API_PATH."tour/find-booked-tour.php");
													curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_tour);
													curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
													$return_data_tour = curl_exec($ch);
													curl_close($ch);
													$return_data_arr_tour=json_decode($return_data_tour, true);
													//print_r($return_data_arr_tour);
													if(!isset($return_data_arr_tour['status'])):
														$data['status'] = 'error';
														$data['msg']="Some error has been occure during execution.";
													elseif($return_data_arr_tour['status']=="success"):
														if(isset($return_data_arr_tour['find_tour']) && isset($return_data_arr_tour['find_offer'])):
															$each_tour_price=$tour_val['price']+(($tour_val['price']*$tour_val['nationality_addon_percentage'])/100)+(($tour_val['price']*$tour_val['agent_markup_percentage'])/100);
															$tour_price=$tour_price+$each_tour_price;
															ob_start();
														?>
															<tr class="odd">
																<td style = "text-align:left;">
																	<?php echo $return_data_arr_tour['find_tour']['tour_title'];?> - <?php echo $return_data_arr_tour['find_offer']['offer_title'];?> - <?php echo $return_data_arr_tour['find_offer']['service_type'];?> ( Capacity:  <?php echo $return_data_arr_tour['find_offer']['offer_capacity'];?> )
																	<br/>
																	Price: <?php echo $booking_details_list['currency_code'].number_format($each_tour_price, 2,".",",");?>
																	<?php
																	if($tour_val['pickup_time']!=""):
																	?>
																	<br/>
																	From: <?php echo date("h:i A", strtotime($tour_val['pickup_time'].":00"));?>
																	<?php
																	endif;
																	if($tour_val['dropoff_time']!=""):
																	?>
																	<br/>
																	To: <?php echo date("h:i A", strtotime($tour_val['dropoff_time'].":00"));?>
																	<?php
																	endif;
																	?>
																</td>
															</tr>
														<?php
															$each_tour_html=ob_get_clean();
															$tour_html.=$each_tour_html;
														endif;
													else:
														$data['status'] = 'error';
														$data['msg'] = $return_data_arr_hotel['msg'];
													endif;
												endif;
											else:
												$data['status'] = 'error';
												$data['msg'] = $autentication_data->msg;
											endif;
										endforeach;
									endif;
									if(isset($desti_val['booking_transfer_list']) && !empty($desti_val['booking_transfer_list'])):
										foreach($desti_val['booking_transfer_list'] as $transfer_key=>$transfer_val):
											$autentication_data_transfer=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.TRANSFER_API_PATH."authorized.php"));
											if(isset($autentication_data_transfer->status)):
												if($autentication_data_transfer->status=="success"):
													$post_data_transfer['token']=array(
														"token"=>$autentication_data_transfer->results->token,
														"token_timeout"=>$autentication_data_transfer->results->token_timeout,
														"token_generation_time"=>$autentication_data_transfer->results->token_generation_time
													);
													$post_data_transfer['data']['transfer_id']=$transfer_val['transfer_id'];
													$post_data_transfer['data']['offer_id']=$transfer_val['offer_id'];
													$post_data_str_transfer=json_encode($post_data_transfer);
													$ch = curl_init();
													curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
													curl_setopt($ch, CURLOPT_HEADER, false);
													curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
													curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
													curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.TRANSFER_API_PATH."transfer/find-booked-transfer.php");
													curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_transfer);
													curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
													$return_data_transfer = curl_exec($ch);
													curl_close($ch);
													$return_data_arr_transfer=json_decode($return_data_transfer, true);
													if(!isset($return_data_arr_transfer['status'])):
														$data['status'] = 'error';
														$data['msg']="Some error has been occure during execution.";
													elseif($return_data_arr_transfer['status']=="success"):
														if(isset($return_data_arr_transfer['find_transfer']) && isset($return_data_arr_transfer['find_offer'])):
															$each_transfer_price=$transfer_val['price']+(($transfer_val['price']*$transfer_val['nationality_addon_percentage'])/100)+(($transfer_val['price']*$transfer_val['agent_markup_percentage'])/100);
															$transfer_price=$transfer_price+$each_transfer_price;
															ob_start();
														?>
															<tr class="odd">
																<td style = "text-align:left;">
																	<?php echo $return_data_arr_transfer['find_transfer']['transfer_title'];?> - <?php echo $return_data_arr_transfer['find_offer']['offer_title'];?> - <?php echo $return_data_arr_transfer['find_offer']['service_type'];?> ( Capacity:  <?php echo $return_data_arr_transfer['find_offer']['offer_capacity'];?> )
																	<br/>
																	Price: <?php echo $booking_details_list['currency_code'].number_format($each_transfer_price, 2,".",",");?>
																	<?php
																	if($return_data_arr_transfer['find_transfer']['allow_pickup_type']!=""):
																	?>
																	<br/>
																	Pick Up/Drop off Type: <?php echo $return_data_arr_transfer['find_transfer']['allow_pickup_type'];?>
																	<?php
																	endif;
																	if($transfer_val['pickup_time']!=""):
																	?>
																	<br/>
																	From: <?php echo date("h:i A", strtotime($transfer_val['pickup_time'].":00"));?>
																	<?php
																	endif;
																	if($transfer_val['dropoff_time']!=""):
																	?>
																	<br/>
																	To: <?php echo date("h:i A", strtotime($transfer_val['dropoff_time'].":00"));?>
																	<?php
																	endif;
																	if($transfer_val['airport']!=""):
																	?>
																	<br/>
																	Airport: <?php echo $transfer_val['airport'];?>
																	<?php
																	endif;
																	if($transfer_val['flight_number_name']!=""):
																	?>
																	<br/>
																	Flight Number and Name: <?php echo $transfer_val['flight_number_name'];?>
																	<?php
																	endif;
																	?>
																</td>
															</tr>
														<?php
															$each_transfer_html=ob_get_clean();
															$transfer_html.=$each_transfer_html;
														endif;
													else:
														$data['status'] = 'error';
														$data['msg'] = $return_data_arr_hotel['msg'];
													endif;
												endif;
											else:
												$data['status'] = 'error';
												$data['msg'] = $autentication_data->msg;
											endif;
										endforeach;
									endif;
								endforeach;
							endif;
							?>
							<?php
							if(isset($hotel_html) && $hotel_html!=""):
							?>
							<table aria-describedby="example1_info" id="example" class="table table-bordered table-striped dataTable">
								<thead>
									<tr role="row">
										<th style = "text-align:left;">Hotel</th>
										<th style = "text-align:center;">Room Type</th>
										<th style = "text-align:center;">Check In</th>
										<th style = "text-align:center;">Check Out</th>
										<th style = "text-align:center;">Rooms</th>
										<th style = "text-align:center;">Nights</th>
										<th style = "text-align:center;">Price Per Night</th>
										<th style = "text-align:center;">Approval Status</th>
									</tr>
								</thead>
								<tbody aria-relevant="all" aria-live="polite" role="alert">
									<?php echo $hotel_html;?>
								</tbody>
							</table>
							<?php
							endif;
							?>
							<?php
							if(((isset($tour_html) && $tour_html!="") || (isset($transfer_html) && $transfer_html!="")) && isset($booking_details_list['booking_supplier_list']) && !empty($booking_details_list['booking_supplier_list'])):
								$is_rejected=false;
								$not_in_supplier_ids=array();
							?>
							<table aria-describedby="example1_info" id="example" class="table table-bordered table-striped dataTable">
								<thead>
									<tr role="row">
										<th>Supplier Name</th>
										<th>Company Name</th>
										<th>Email</th>
										<th>Approval Status</th>
										<th>Modification Date</th>
									</tr>
								</thead>
								<tbody aria-relevant="all" aria-live="polite" role="alert">
							<?php
								foreach($booking_details_list['booking_supplier_list'] as $supplier_key=>$supplier_val):
									$is_rejected=false;
									$autentication_data_supplier=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.SUPPLIER_API_PATH."authorized.php"));
									if(isset($autentication_data_supplier->status)):
										if($autentication_data_supplier->status=="success"):
											$post_data_supplier['token']=array(
												"token"=>$autentication_data_supplier->results->token,
												"token_timeout"=>$autentication_data_supplier->results->token_timeout,
												"token_generation_time"=>$autentication_data_supplier->results->token_generation_time
											);
											$post_data_supplier['data']['supplier_id']=base64_encode($supplier_val['supplier_id']);
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
											$return_data_arr_supplier=json_decode($return_data_supplier, true);
											if(!isset($return_data_arr_supplier['status'])):
												//$data['status'] = 'error';
												//$data['msg']="Some error has been occure during execution.";
											elseif($return_data_arr_supplier['status']=="success"):
												$supplier_return_data=$return_data_arr_supplier['results'];
											else:
												//$data['status'] = 'error';
												//$data['msg'] = $return_data_arr_supplier['msg'];
											endif;
										endif;
									else:
										//$data['status'] = 'error';
										//$data['msg'] = $autentication_data_supplier->msg;
									endif;
							?>
									<tr>
										<td><?php echo $supplier_return_data['first_name']." ".$supplier_return_data['last_name'];?></td>
										<td><?php echo $supplier_return_data['company_name'];?></td>
										<td><?php echo $supplier_return_data['email_address'];?></td>
										<td>
											<?php
											if($supplier_val['status']==0):
												echo '<span style="padding: 3px;" class="btn-warning">Pending</span>';
											elseif($supplier_val['status']==1):
												echo '<span style="padding: 3px;" class="btn-success">Acccepted</span>';
											elseif($supplier_val['status']==2):
												echo '<span style="padding: 3px;" class="btn-danger">Rejected</span>';
											else:
												echo 'N/A';
											endif;
											?>
										</td>
										<td><?php echo tools::module_date_format($supplier_val['last_updated'], "Y-m-d H:i:s");?></td>
									</tr>
							<?php
									array_push($not_in_supplier_ids, $supplier_return_data['id']);
									if($supplier_val['status']==2):
										$is_rejected=true;
									endif;
								endforeach;
							?>
								</tbody>
							</table>
							<?php
								if($is_rejected==true):
									$not_in_supplier_ids_str=implode(",", $not_in_supplier_ids);
									$autentication_data_other_supplier=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.SUPPLIER_API_PATH."authorized.php"));
									if(isset($autentication_data_other_supplier->status)):
										if($autentication_data_other_supplier->status=="success"):
											$post_data_other_supplier['token']=array(
												"token"=>$autentication_data_other_supplier->results->token,
												"token_timeout"=>$autentication_data_other_supplier->results->token_timeout,
												"token_generation_time"=>$autentication_data_other_supplier->results->token_generation_time
											);
											$post_data_other_supplier['data']['not_in_supplier_ids_str']=$not_in_supplier_ids_str;
											$post_data_str_other_supplier=json_encode($post_data_other_supplier);
											$ch = curl_init();
											curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
											curl_setopt($ch, CURLOPT_HEADER, false);
											curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
											curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
											curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.SUPPLIER_API_PATH."supplier/read.php");
											curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_other_supplier);
											curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
											$return_data_other_supplier = curl_exec($ch);
											curl_close($ch);
											$return_data_arr_other_supplier=json_decode($return_data_other_supplier, true);
											if(!isset($return_data_arr_other_supplier['status'])):
												//$data['status'] = 'error';
												//$data['msg']="Some error has been occure during execution.";
											elseif($return_data_arr_other_supplier['status']=="success"):
												$other_supplier_list=$return_data_arr_other_supplier['results'];
											else:
												//$data['status'] = 'error';
												//$data['msg'] = $return_data_arr_other_supplier['msg'];
											endif;
										endif;
									else:
										//$data['status'] = 'error';
										//$data['msg'] = $autentication_data_other_supplier->msg;
									endif;
							?>
							<div class="" style="margin:10px 0px;border: 1px solid #000;padding: 10px 0px;">
								<form class="form-inline " id="other_supplier_form" name="other_supplier_form" action="" method="post">
									<div class="col-md-3 form-group">
										<label for="email">Select Other Supplier:</label>
									</div>
									<div class="col-md-3 form-group">
										<select class="form-control" name="other_supplier_id" id="other_supplier_id">
										<?php
										foreach($other_supplier_list as $other_key=>$other_val):
										?>
											<option value="<?php echo $other_val['id'];?>"><?php echo $other_val['first_name']." ".$other_val['last_name']." (".$other_val['supplier_code'].")";?></option>
										<?php
										endforeach;
										?>
										</select>
									</div>
									<button type="submit" class="btn btn-primary">Submit</button>
								</form>
							</div>
							<?php
								endif;
							endif;
							?>
							<?php
							if(isset($transfer_html) && $transfer_html!=""):
							?>
							<table aria-describedby="example1_info" id="example" class="table table-bordered table-striped dataTable">
								<thead>
									<tr role="row">
										<th style = "text-align:left;">Transfers</th>
									</tr>
								</thead>
								<tbody aria-relevant="all" aria-live="polite" role="alert">
									<?php echo $transfer_html;?>
								</tbody>
							</table>
							<?php
							endif;
							?>
							<?php
							if(isset($tour_html) && $tour_html!=""):
							?>
							<table aria-describedby="example1_info" id="example" class="table table-bordered table-striped dataTable">
								<thead>
									<tr role="row">
										<th style = "text-align:left;">Tour Sites</th>
									</tr>
								</thead>
								<tbody aria-relevant="all" aria-live="polite" role="alert">
									<?php echo $tour_html;?>
								</tbody>
							</table>
							<?php
							endif;
							?>
							<table aria-describedby="example1_info" id="example" class="table table-bordered table-striped dataTable">
								<thead>
									<tr role="row">
										<th style = "text-align:center;" colspan = "3">Quotation</th>
									</tr>
								</thead>
								<tbody aria-relevant="all" aria-live="polite" role="alert">
									<tr class="odd">
										<td style = "text-align:left;font-weight:bold;">Total Cost for Hotel Accommodation</td>
										<td style = "text-align:center;font-weight:bold;" colspan = "2"><?php echo $booking_details_list['currency_code'].number_format($hotel_price, 2,".",",");?></td>
									</tr>
									<?php
									if($tour_price!=0.00 || $transfer_price!=0.00):
									?>
										<tr class="odd">
											<td style = "text-align:left;font-weight:bold;">
												Add-on : Cost for other components Tours & Transfer
											</td>
											<td style = "text-align:center;font-weight:bold;">
												PER ADULT
												<br/>
												<?php echo $booking_details_list['currency_code'].number_format($tour_price+$transfer_price, 2,".",",");?>
											</td>
											<td style = "text-align:center;font-weight:bold;">
												PER CHILD
												<br/>
												<?php echo $booking_details_list['currency_code'].number_format(0, 2,".",",");?>
											</td>
										</tr>
									<?php
									endif;
									?>
									<tr class="odd">
										<td style = "text-align:left;font-weight:bold;">
											No of Guests
										</td>
										<td style = "text-align:center;font-weight:bold;">
											ADULT
											<br/>
											<?php echo $number_of_adult;?>
										</td>
										<td style = "text-align:center;font-weight:bold;">
											CHILD
											<br/>
											<?php echo $number_of_child;?>
										</td>
									</tr>
									<tr class="odd">
										<td style = "text-align:left;font-weight:bold;">Total Quantity</td>
										<td style = "text-align:center;font-weight:bold;color:red;" colspan = "2">
											<?php echo $booking_details_list['currency_code'].number_format($hotel_price+(($tour_price+$transfer_price)*$number_of_adult), 2,".",",");?>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<?php
						else:
						?>
						<div class="box box-primary text-center">
							<div style="padding:20px;">
								No record found
							</div>
						</div>
						<?php
						endif;
						?>
					</div>
				</div>
			</section>
		</div>
		<!-- BODY -->

        <!-- FOOTER -->
		<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'footer.php');?>
		<!-- FOOTER -->

	</div>
</body>
</html>