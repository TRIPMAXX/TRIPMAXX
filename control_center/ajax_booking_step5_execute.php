<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');	
	$data['status']="error";
	$return_data['msg']="Token is not verified.";
	$_POST['total_price']=2036;
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
						
						$data['status'] = 'success';
						$data['msg']="Booking has been saved successfully.";
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