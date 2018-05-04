<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
	$data['msg']="Some data missing";
	if(isset($_POST['currency_id']) && $_POST['currency_id']!=""):
		$find_currency = tools::find("first", TM_CURRENCIES, '*', "WHERE id=:id ", array(":id"=>$_POST['currency_id']));
		if(!empty($find_currency)):
			if($find_currency['status']==1):
				$status=0;
			else:
				$status=1;
			endif;
			if(tools::update(TM_CURRENCIES, "status=:status", "WHERE id=:id", array(":status"=>$status, ":id"=>$_POST['currency_id']))):
				$autentication_data_agent_currency=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."authorized.php"));
				if(isset($autentication_data_agent_currency->status)):
					if($autentication_data_agent_currency->status=="success"):
						$post_data_agent_currency['token']=array(
							"token"=>$autentication_data_agent_currency->results->token,
							"token_timeout"=>$autentication_data_agent_currency->results->token_timeout,
							"token_generation_time"=>$autentication_data_agent_currency->results->token_generation_time
						);
						$post_data_agent_currency['data']['id']=$_POST['currency_id'];
						$post_data_agent_currency['data']['status']=$status;
						$post_data_agent_currency_str=json_encode($post_data_agent_currency);
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
						curl_setopt($ch, CURLOPT_HEADER, false);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
						curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
						curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."currency/update.php");
						curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_agent_currency_str);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
						$return_data_agent_currency = curl_exec($ch);
						curl_close($ch);
						//print_r($return_data_agent_currency);
						$return_data_agent_currency_arr=json_decode($return_data_agent_currency, true);
						if(!isset($return_data_agent_currency_arr['status'])):
							//$data['msg']="Some error has been occure during execution.";
						elseif($return_data_agent_currency_arr['status']=="success"):
							//$data['status'] = 'success';
							//$data['msg'] = $return_data_agent_currency_arr['msg'];
						else:
							//$data['msg'] = $return_data_agent_currency_arr['msg'];
						endif;
					else:
						//$data['msg'] = $autentication_data_agent_currency->msg;
					endif;
				else:
					//$data['msg'] = "We are having some problem to authorize api.";
				endif;
				$autentication_data_booking_currency=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.BOOKING_API_PATH."authorized.php"));
				if(isset($autentication_data_booking_currency->status)):
					if($autentication_data_booking_currency->status=="success"):
						$post_data_booking_currency['token']=array(
							"token"=>$autentication_data_booking_currency->results->token,
							"token_timeout"=>$autentication_data_booking_currency->results->token_timeout,
							"token_generation_time"=>$autentication_data_booking_currency->results->token_generation_time
						);
						$post_data_booking_currency['data']['id']=$_POST['currency_id'];
						$post_data_booking_currency['data']['status']=$status;
						$post_data_booking_currency_str=json_encode($post_data_booking_currency);
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
						curl_setopt($ch, CURLOPT_HEADER, false);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
						curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
						curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.BOOKING_API_PATH."currency/update.php");
						curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_booking_currency_str);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
						$return_data_booking_currency = curl_exec($ch);
						curl_close($ch);
						//print_r($return_data_booking_currency);
						$return_data_booking_currency_arr=json_decode($return_data_booking_currency, true);
						if(!isset($return_data_booking_currency_arr['status'])):
							//$data['msg']="Some error has been occure during execution.";
						elseif($return_data_booking_currency_arr['status']=="success"):
							//$data['status'] = 'success';
							//$data['msg'] = $return_data_booking_currency_arr['msg'];
						else:
							//$data['msg'] = $return_data_booking_currency_arr['msg'];
						endif;
					else:
						//$data['msg'] = $autentication_data_booking_currency->msg;
					endif;
				else:
					//$data['msg'] = "We are having some problem to authorize api.";
				endif;
				$autentication_data_hotel_currency=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."authorized.php"));
				if(isset($autentication_data_hotel_currency->status)):
					if($autentication_data_hotel_currency->status=="success"):
						$post_data_hotel_currency['token']=array(
							"token"=>$autentication_data_hotel_currency->results->token,
							"token_timeout"=>$autentication_data_hotel_currency->results->token_timeout,
							"token_generation_time"=>$autentication_data_hotel_currency->results->token_generation_time
						);
						$post_data_hotel_currency['data']['id']=$_POST['currency_id'];
						$post_data_hotel_currency['data']['status']=$status;
						$post_data_hotel_currency_str=json_encode($post_data_hotel_currency);
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
						curl_setopt($ch, CURLOPT_HEADER, false);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
						curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
						curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."currency/update.php");
						curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_hotel_currency_str);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
						$return_data_hotel_currency = curl_exec($ch);
						curl_close($ch);
						//print_r($return_data_hotel_currency);
						$return_data_hotel_currency_arr=json_decode($return_data_hotel_currency, true);
						if(!isset($return_data_hotel_currency_arr['status'])):
							//$data['msg']="Some error has been occure during execution.";
						elseif($return_data_hotel_currency_arr['status']=="success"):
							//$data['status'] = 'success';
							//$data['msg'] = $return_data_hotel_currency_arr['msg'];
						else:
							//$data['msg'] = $return_data_hotel_currency_arr['msg'];
						endif;
					else:
						//$data['msg'] = $autentication_data_hotel_currency->msg;
					endif;
				else:
					//$data['msg'] = "We are having some problem to authorize api.";
				endif;
				$autentication_data_tour_currency=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.TOUR_API_PATH."authorized.php"));
				if(isset($autentication_data_tour_currency->status)):
					if($autentication_data_tour_currency->status=="success"):
						$post_data_tour_currency['token']=array(
							"token"=>$autentication_data_tour_currency->results->token,
							"token_timeout"=>$autentication_data_tour_currency->results->token_timeout,
							"token_generation_time"=>$autentication_data_tour_currency->results->token_generation_time
						);
						$post_data_tour_currency['data']['id']=$_POST['currency_id'];
						$post_data_tour_currency['data']['status']=$status;
						$post_data_tour_currency_str=json_encode($post_data_tour_currency);
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
						curl_setopt($ch, CURLOPT_HEADER, false);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
						curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
						curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.TOUR_API_PATH."currency/update.php");
						curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_tour_currency_str);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
						$return_data_tour_currency = curl_exec($ch);
						curl_close($ch);
						//print_r($return_data_tour_currency);
						$return_data_tour_currency_arr=json_decode($return_data_tour_currency, true);
						if(!isset($return_data_tour_currency_arr['status'])):
							//$data['msg']="Some error has been occure during execution.";
						elseif($return_data_tour_currency_arr['status']=="success"):
							//$data['status'] = 'success';
							//$data['msg'] = $return_data_tour_currency_arr['msg'];
						else:
							//$data['msg'] = $return_data_tour_currency_arr['msg'];
						endif;
					else:
						//$data['msg'] = $autentication_data_tour_currency->msg;
					endif;
				else:
					//$data['msg'] = "We are having some problem to authorize api.";
				endif;
				$autentication_data_transfer_currency=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.TRANSFER_API_PATH."authorized.php"));
				if(isset($autentication_data_transfer_currency->status)):
					if($autentication_data_transfer_currency->status=="success"):
						$post_data_transfer_currency['token']=array(
							"token"=>$autentication_data_transfer_currency->results->token,
							"token_timeout"=>$autentication_data_transfer_currency->results->token_timeout,
							"token_generation_time"=>$autentication_data_transfer_currency->results->token_generation_time
						);
						$post_data_transfer_currency['data']['id']=$_POST['currency_id'];
						$post_data_transfer_currency['data']['status']=$status;
						$post_data_transfer_currency_str=json_encode($post_data_transfer_currency);
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
						curl_setopt($ch, CURLOPT_HEADER, false);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
						curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
						curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.TRANSFER_API_PATH."currency/update.php");
						curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_transfer_currency_str);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
						$return_data_transfer_currency = curl_exec($ch);
						curl_close($ch);
						//print_r($return_data_transfer_currency);
						$return_data_transfer_currency_arr=json_decode($return_data_transfer_currency, true);
						if(!isset($return_data_transfer_currency_arr['status'])):
							//$data['msg']="Some error has been occure during execution.";
						elseif($return_data_transfer_currency_arr['status']=="success"):
							//$data['status'] = 'success';
							//$data['msg'] = $return_data_transfer_currency_arr['msg'];
						else:
							//$data['msg'] = $return_data_transfer_currency_arr['msg'];
						endif;
					else:
						//$data['msg'] = $autentication_data_transfer_currency->msg;
					endif;
				else:
					//$data['msg'] = "We are having some problem to authorize api.";
				endif;
				$data['msg']="success";
				$data['success']="Status has been updated successfully.";
				$data['status']=$status;
			else:
				$data['msg']="We are having some problem in update. Please try later.";
			endif;
		else:
			$data['msg']="Invalid currency id.";
		endif;
	endif;
	echo json_encode($data);
?>