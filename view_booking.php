<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['AGENT_SESSION_DATA']['id'], DOMAIN_NAME_PATH.'');
	if(isset($_GET['booking_id']) && $_GET['booking_id']!=""):
		$autentication_data_booking=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.BOOKING_API_PATH."authorized.php"));
		if(isset($autentication_data_booking->status)):
			if($autentication_data_booking->status=="success"):
				$post_data_booking['token']=array(
					"token"=>$autentication_data_booking->results->token,
					"token_timeout"=>$autentication_data_booking->results->token_timeout,
					"token_generation_time"=>$autentication_data_booking->results->token_generation_time
				);
				if(isset($_GET['sub_agent_id']) && $_GET['sub_agent_id']!=""):
					$sub_agent_data = tools::find("first", TM_AGENT, "*", "WHERE id=:id AND parent_id=:parent_id", array(':id'=>base64_decode($_GET['sub_agent_id']), ':parent_id'=>$_SESSION['AGENT_SESSION_DATA']['id']));
					if(!empty($sub_agent_data)):
						$post_data_booking['data']['agent_id']=base64_decode($_GET['sub_agent_id']);
					else:
						$_SESSION['SET_TYPE'] = 'error';
						$_SESSION['SET_FLASH']="Invalid sub agent.";
						header("location:".DOMAIN_NAME_PATH."booking.php");
						exit;
					endif;
				else:
					$post_data_booking['data']['agent_id']=$_SESSION['AGENT_SESSION_DATA']['id'];
				endif;
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
				//print_r($return_data_booking);
				$return_data_arr_booking=json_decode($return_data_booking, true);
				if(!isset($return_data_arr_booking['status'])):
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH']="Some error has been occure during execution.";
				elseif($return_data_arr_booking['status']=="success"):
					$booking_details_list=$return_data_arr_booking['results'][0];
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
								$autentication_data_dmc=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.DMC_API_PATH."authorized.php"));
								if(isset($autentication_data_dmc->status)):
									if($autentication_data_dmc->status=="success"):
										$post_data_dmc['token']=array(
											"token"=>$autentication_data_dmc->results->token,
											"token_timeout"=>$autentication_data_dmc->results->token_timeout,
											"token_generation_time"=>$autentication_data_dmc->results->token_generation_time
										);
										$post_data_dmc['data']['email_template_id']=36;
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
											//$data['msg'] = $return_email_template_data_arr['msg'];
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
															$post_data_dmc['data']['email_template_id']=37;
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
																	$supplier_url_details='<a href="'.DOMAIN_NAME_PATH_SUPPLIER.'login?auto_login_id='.base64_encode(SECURITY_SALT.$return_data_arr_supplier['results']['id']."supplier".AUTO_LOGIN_SECURITY_KEY).'&booking_id='.base64_encode($booking_id).'" title="View Order">'.DOMAIN_NAME_PATH_SUPPLIER.'login?auto_login_id='.base64_encode(SECURITY_SALT.$return_data_arr_supplier['results']['id']."supplier".AUTO_LOGIN_SECURITY_KEY).'&booking_id='.base64_encode($booking_id).'</a>';
																	$supplier_mail_Body=str_replace(array("[FIRST_NAME]", "[LAST_NAME]", "[DETAILS_URL]"), array($return_data_arr_supplier['results']['first_name'], $return_data_arr_supplier['results']['last_name'], $supplier_url_details), $supplier_email_template['template_body']);
																	@tools::Send_SMTP_Mail($return_data_arr_supplier['results']['email_address'], FROM_EMAIL, '', $supplier_email_template['template_subject'], $supplier_mail_Body);
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
															$post_data_dmc['data']['email_template_id']=37;
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
														$post_data_dmc['data']['email_template_id']=38;
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
										$find_agent = tools::find("first", TM_AGENT, '*', "WHERE id=:id ", array(":id"=>$_SESSION['AGENT_SESSION_DATA']['id']));
										if($find_agent['type']=="A" && $find_agent['parent_id'] > 0):
											$find_gsm = tools::find("first", TM_AGENT, '*', "WHERE id=:id ", array(":id"=>$find_agent['parent_id']));
											$return_data['result_gsm'] = $find_agent;
										endif;
										if(isset($booking_details_list['booking_type']) && $booking_details_list['booking_type']=="agent" && isset($booking_details_list['agent_id']) && $booking_details_list['agent_id']!=""):
											if(!empty($find_agent)):
												$post_data_dmc['data']['email_template_id']=39;
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
													$gsm_email_template=$agent_email_template = $return_email_template_data_arr['email_template'];
													if($find_agent['type']=="A"):
														if(!empty($agent_email_template)):
															$agent_url_details='<a href="'.DOMAIN_NAME_PATH.'index.php?auto_login_id='.base64_encode(SECURITY_SALT.$find_agent['id']."agent".AUTO_LOGIN_SECURITY_KEY).'&booking_id='.base64_encode($booking_id).'" title="View Order">'.DOMAIN_NAME_PATH.'index.php?auto_login_id='.base64_encode(SECURITY_SALT.$find_agent['id']."agent".AUTO_LOGIN_SECURITY_KEY).'&booking_id='.base64_encode($booking_id).'</a>';
															$agent_mail_Body=str_replace(array("[FIRST_NAME]", "[LAST_NAME]", "[DETAILS_URL]"), array($find_agent['first_name'], $find_agent['last_name'], $agent_url_details), $agent_email_template['template_body']);
															@tools::Send_SMTP_Mail($find_agent['email_address'], FROM_EMAIL, '', $agent_email_template['template_subject'], $agent_mail_Body);
														endif;
														if(isset($find_gsm) && !empty($find_gsm)):
															if(!empty($gsm_email_template)):
																$gsm_url_details='<a href="'.DOMAIN_NAME_PATH.'index.php?auto_login_id='.base64_encode(SECURITY_SALT.$find_gsm['id']."agent".AUTO_LOGIN_SECURITY_KEY).'&booking_id='.base64_encode($booking_id).'&sub_agent_id='.base64_encode($find_agent['id']).'" title="View Order">'.DOMAIN_NAME_PATH.'index.php?auto_login_id='.base64_encode(SECURITY_SALT.$find_gsm['id']."agent".AUTO_LOGIN_SECURITY_KEY).'&booking_id='.base64_encode($booking_id).'&sub_agent_id='.base64_encode($find_agent['id']).'</a>';
																$gsm_mail_Body=str_replace(array("[FIRST_NAME]", "[LAST_NAME]", "[DETAILS_URL]"), array($find_gsm['first_name'], $find_gsm['last_name'], $gsm_url_details), $gsm_email_template['template_body']);
																@tools::Send_SMTP_Mail($find_gsm['email_address'], FROM_EMAIL, '', $gsm_email_template['template_subject'], $gsm_mail_Body);
															endif;
														endif;
													elseif($find_agent['type']=="G"):
														if(!empty($gsm_email_template)):
															$gsm_url_details='<a href="'.DOMAIN_NAME_PATH.'index.php?auto_login_id='.base64_encode(SECURITY_SALT.$find_agent['id']."agent".AUTO_LOGIN_SECURITY_KEY).'&booking_id='.base64_encode($booking_id).'" title="View Order">'.DOMAIN_NAME_PATH.'index.php?auto_login_id='.base64_encode(SECURITY_SALT.$find_agent['id']."agent".AUTO_LOGIN_SECURITY_KEY).'&booking_id='.base64_encode($booking_id).'</a>';
															$gsm_mail_Body=str_replace(array("[FIRST_NAME]", "[LAST_NAME]", "[DETAILS_URL]"), array($find_agent['first_name'], $find_agent['last_name'], $gsm_url_details), $gsm_email_template['template_body']);
															@tools::Send_SMTP_Mail($find_agent['email_address'], FROM_EMAIL, '', $gsm_email_template['template_subject'], $gsm_mail_Body);
														endif;
													endif;
													//$data['status'] = 'success';
													//$data['msg']="Data received successfully";
												else:
													//$data['status'] = 'error';
													//$data['msg'] = $return_email_template_data_arr['msg'];
												endif;
											else:
												//$data['status'] = 'error';
												//$data['msg'] = $return_data_arr['msg'];
											endif;
										endif;
										if(isset($booking_details_list['booking_type']) && $booking_details_list['booking_type']=="agent" && isset($booking_details_list['agent_id']) && $booking_details_list['agent_id']!="" && $_POST['booking_approval_status']==2):
											if(!empty($find_agent)):
												if($find_agent['payment_type']=="credit"):
													$closing_balance=$_POST['credit_balance']=$find_agent['credit_balance']+$booking_details_list['total_amount'];
													$_POST['id']=$find_agent['id'];
													if($save_prev_agent_data = tools::module_form_submission("", TM_AGENT)):
														unset($_POST);
														$_POST['agent_id']=$find_agent['id'];
														$_POST['amount']=$booking_details_list['total_amount'];
														$_POST['note']="Credit refund money for booking with quotation name:".$booking_details_list['quotation_name'];
														$_POST['debit_or_credit']="Credit";
														$_POST['closing_balance']=$closing_balance;
														$save_agent_credit=tools::module_form_submission("", TM_AGENT_ACCOUNTING);
														unset($_POST);
														$_POST['transaction_id']=tools::generate_transaction_id("TM-".$save_agent_credit);
														$_POST['id']=$save_agent_credit;
														$save_agent_accounting = tools::module_form_submission("", TM_AGENT_ACCOUNTING);
													else:
														//$return_data['status'] = 'error';
														//$return_data['msg'] = 'We are having some probem. Please try again later.';
													endif;
												endif;
												unset($_POST);
											endif;
										endif;
									else:
										$_SESSION['SET_TYPE'] = 'error';
										$_SESSION['SET_FLASH']="Some error has been occure during execution.";
									endif;
								else:
									$_SESSION['SET_TYPE'] = 'error';
									$_SESSION['SET_FLASH']="Some error has been occure during execution.";
								endif;
							endif;
							header("location:".DOMAIN_NAME_PATH.'view_booking.php?booking_id='.$_GET['booking_id']);
							exit;
						else:
							$_SESSION['SET_TYPE'] = 'error';
							$_SESSION['SET_FLASH'] = $return_data_arr_booking_update['msg'];
						endif;
					};
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
		header("location:booking.php".(isset($_GET['sub_agent_id']) && $_GET['sub_agent_id']!="" ? "?sub_agent_id=".$_GET['sub_agent_id'] : ""));
		exit;
	endif;
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo DEFAULT_PAGE_TITLE ;?> Destination Management Company</title>	
		<?php require_once('meta.php');?>
		<script type="text/javascript">
		<!--
			$(function(){
				$("#booking_approval_status").change(function(){
					$("#booking_approval_status_form").submit();
				});
			});
		//-->
		</script>
	</head>
	<body class="index-page">
		<!-- TOP HEADER -->
		<?php require_once('header.php');?>
		<!-- TOP HEADER -->
		<div class="main-cont">
			<div class="body-padding">
				<div class="banner_slider" style="background:url(img/banner4.jpg)no-repeat center center/cover;">
					<div class="banner_slider_text">
						View Booking
					</div>
				</div>
				<?php require_once('login_menu.php');?>
				<div id="" class="container">
					<div class="mp-offesr">
						<div class="wrapper-padding-a">
							<div class="offer-slider">
								<div class="row rows">
									<div class="col-md-12">
										<div class="box box-info">
											<div class="box-body">
												<div class="box-body no-padding">
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
														$service_arr=array();
														$hotel_html='';
														$tour_html='';
														$transfer_html='';
														$hotel_price=$tour_price=$transfer_price=0.00;
														$show_cancellation_dropdown=true;
														if($checkin_date <= time()):
															$show_cancellation_dropdown=false;
														endif;
														if(isset($booking_details_list['booking_destination_list']) && !empty($booking_details_list['booking_destination_list'])):
															foreach($booking_details_list['booking_destination_list'] as $dest_key=>$dest_val):
																if($destination_str!="")
																	$destination_str.=", ";
																$destination_str.=$dest_val['ci_name'];
																if(isset($dest_val['booking_hotel_list']) && !empty($dest_val['booking_hotel_list']) && !in_array("Hotel", $service_arr))
																	array_push($service_arr, "Hotel");
																if(isset($dest_val['booking_tour_list']) && !empty($dest_val['booking_tour_list']) && !in_array("Tour", $service_arr))
																	array_push($service_arr, "Tour");
																if(isset($dest_val['booking_transfer_list']) && !empty($dest_val['booking_transfer_list']) && !in_array("Transfer", $service_arr))
																	array_push($service_arr, "Transfer");
																if(isset($dest_val['booking_hotel_list']) && !empty($dest_val['booking_hotel_list'])):
																	foreach($dest_val['booking_hotel_list'] as $hotel_key=>$hotel_val):
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
																						if($return_data_arr_hotel['find_hotel']['is_cancellation_policy_applied']==0 || ($return_data_arr_hotel['find_hotel']['is_cancellation_policy_applied']==1 && time() >=strtotime("+".$return_data_arr_hotel['find_hotel']['cancellation_allowed_days']." days", strtotime($booking_details_list['creation_date']))))
																							$show_cancellation_dropdown=false;
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
																							<td style = "text-align:center;"><?php echo $dest_val['no_of_night'];?></td><td style = "text-align:center;">
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
																if(isset($dest_val['booking_tour_list']) && !empty($dest_val['booking_tour_list'])):
																	$prev_booking_date="";
																	foreach($dest_val['booking_tour_list'] as $tour_key=>$tour_val):
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
																						if($return_data_arr_tour['find_tour']['is_cancellation_policy_applied']==0 || ($return_data_arr_tour['find_tour']['is_cancellation_policy_applied']==1 && time() >=strtotime("+".$return_data_arr_tour['find_tour']['cancellation_allowed_days']." days", strtotime($booking_details_list['creation_date']))))
																							$show_cancellation_dropdown=false;
																						ob_start();
																						if($prev_booking_date=="" || $prev_booking_date!=$tour_val['booking_start_date']):
																							$prev_booking_date=$tour_val['booking_start_date'];
																					?>
																						<tr class="odd">
																							<td style = "text-align:left;padding-bottom: 0;" colspan="100%">
																								<h4 style="margin: 0;"><?php echo tools::module_date_format($tour_val['booking_start_date']);?></h4>
																							</td>
																						</tr>
																					<?php
																						endif;
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
																if(isset($dest_val['booking_transfer_list']) && !empty($dest_val['booking_transfer_list'])):
																	$prev_booking_date="";
																	foreach($dest_val['booking_transfer_list'] as $transfer_key=>$transfer_val):
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
																				//print_r($return_data_arr_transfer);
																				if(!isset($return_data_arr_transfer['status'])):
																					$data['status'] = 'error';
																					$data['msg']="Some error has been occure during execution.";
																				elseif($return_data_arr_transfer['status']=="success"):
																					if(isset($return_data_arr_transfer['find_transfer']) && isset($return_data_arr_transfer['find_offer'])):
																						$each_transfer_price=$transfer_val['price']+(($transfer_val['price']*$transfer_val['nationality_addon_percentage'])/100)+(($transfer_val['price']*$transfer_val['agent_markup_percentage'])/100);
																						$transfer_price=$transfer_price+$each_transfer_price;
																						if($return_data_arr_transfer['find_transfer']['is_cancellation_policy_applied']==0 || ($return_data_arr_transfer['find_transfer']['is_cancellation_policy_applied']==1 && time() >=strtotime("+".$return_data_arr_transfer['find_transfer']['cancellation_allowed_days']." days", strtotime($booking_details_list['creation_date']))))
																							$show_cancellation_dropdown=false;
																						ob_start();
																						if($prev_booking_date=="" || $prev_booking_date!=$transfer_val['booking_start_date']):
																							$prev_booking_date=$transfer_val['booking_start_date'];
																					?>
																						<tr class="odd">
																							<td style = "text-align:left;padding-bottom: 0;" colspan="100%">
																								<h4 style="margin: 0;"><?php echo tools::module_date_format($transfer_val['booking_start_date']);?></h4>
																							</td>
																						</tr>
																					<?php
																						endif;
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
																	<span style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" class="btn-warning">Unpaid</span>
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
																	if($show_cancellation_dropdown==true && isset($booking_details_list['status']) && isset($booking_details_list['status']) && $booking_details_list['status']==0):
																	?>
																		<form method="post" name="booking_approval_status_form" id="booking_approval_status_form" action="">
																			<select name="booking_approval_status" id="booking_approval_status" class="btn-warning">
																				<option value="0" class="btn-warning">Pending</option>
																				<option value="2" class="btn-danger">Reject</option>
																			</select>
																		</form>
																	<?php
																	else:
																		if(isset($booking_details_list['status']) && isset($booking_details_list['status']) && $booking_details_list['status']==0)
																		{
																	?>
																	<span style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" class="btn-warning">Pending</span>
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
																		};
																	endif;
																	?>
																</td>
															</tr>
														</tbody>
													</table>
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
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- FOOTER -->
		<?php require_once('footer.php');?>
		<!-- FOOTER -->
	</body>
</html>