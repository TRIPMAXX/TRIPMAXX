<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['AGENT_SESSION_DATA']['id'], DOMAIN_NAME_PATH.'');
	$data['status']="error";
	$data['msg']="Some data missing.";
	/*$_POST['page']=1;
	$_POST['type']=1;
	$_POST['sort_order']="";
	$_POST['city_id']="";
	$_POST['country_id']="";
	$_POST['search_val']="";*/
	$autentication_data=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.TRANSFER_API_PATH."authorized.php"));
	if(isset($autentication_data->status)):
		if($autentication_data->status=="success"):
			$post_data['token']=array(
				"token"=>$autentication_data->results->token,
				"token_timeout"=>$autentication_data->results->token_timeout,
				"token_generation_time"=>$autentication_data->results->token_generation_time
			);
			if(isset($_POST) && !empty($_POST)):
				$autentication_dmc_data=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.DMC_API_PATH."authorized.php"));
				if(isset($autentication_dmc_data->status)):
					if($autentication_dmc_data->status=="success"):
						$post_dmc_data['token']=array(
							"token"=>$autentication_dmc_data->results->token,
							"token_timeout"=>$autentication_dmc_data->results->token_timeout,
							"token_generation_time"=>$autentication_dmc_data->results->token_generation_time
						);
						$post_dmc_data['data']['setting_id']=1;
						$post_dmc_data_str=json_encode($post_dmc_data);
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
						curl_setopt($ch, CURLOPT_HEADER, false);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
						curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
						curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.DMC_API_PATH."settings/read.php");
						curl_setopt($ch, CURLOPT_POSTFIELDS, $post_dmc_data_str);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
						$return_dmc_data = curl_exec($ch);
						curl_close($ch);
						$return_dmc_data_arr=json_decode($return_dmc_data, true);
						if(!isset($return_dmc_data_arr['status'])):
							//$data['status'] = 'error';
							//$data['msg']="Some error has been occure during execution.";
						elseif($return_dmc_data_arr['status']=="success"):
							//$data['status'] = 'success';
							//$data['msg']="Data received successfully";
							$find_threshold_booking_time = $return_dmc_data_arr['results'];
						else:
							//$data['status'] = 'error';
							//$data['msg'] = $return_dmc_data_arr['msg'];
						endif;
					endif;
				endif;
				$offset=0;
				if(isset($_POST['page']) && $_POST['page']!="")
					$offset=($_POST['page']-1)*RECORD_PER_PAGE;
				if(isset($_SESSION['step_1']) && isset($_SESSION['step_1']['country']) && !empty($_SESSION['step_1']['country'])):
					$post_data['data']=$_SESSION['step_1'];
					$post_data['data']['offset']=$offset;
					$post_data['data']['record_per_page']=RECORD_PER_PAGE;
					$post_data['data']['type']=$_POST['type'];
					$post_data['data']['sort_order']=$_POST['sort_order'];
					$post_data['data']['city_id']=$_POST['city_id'];
					$post_data['data']['country_id']=$_POST['country_id'];
					$post_data['data']['search_val']=$_POST['search_val'];
					$post_data['data']['booking_transfer_date']=$_POST['booking_transfer_date'];
					$post_data['data']['pickup_dropoff_type']=$_POST['pickup_dropoff_type'];
					$post_data['data']['selected_airport']=$_POST['selected_airport'];
					$post_data['data']['arr_dept_flight_number']=$_POST['arr_dept_flight_number'];
					$post_data['data']['arr_dept_time']=$_POST['arr_dept_time'];
					$post_data['data']['selected_service_type']=$_POST['selected_service_type'];
					$post_data['data']['search_counter']=$_POST['search_counter'];
					$post_data['data']['threshold_booking_time']=$find_threshold_booking_time;
					if(isset($_POST['booking_details_list']) && $_POST['booking_details_list']!=""):
						$post_data['data']['booking_details_list']=$_POST['booking_details_list'];
					endif;
					$post_data_str=json_encode($post_data);
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_HEADER, false);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
					curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.TRANSFER_API_PATH."transfer/booking-transfer.php");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					$return_data = curl_exec($ch);
					curl_close($ch);
					//print_r($return_data);
					$return_data_arr=json_decode($return_data, true);
					$transfer_data=array();
					if(!isset($return_data_arr['status'])):
						$data['status'] = 'error';
						$data['msg']="Some error has been occure during execution.";
					elseif($return_data_arr['status']=="success"):
						$data['status'] = 'success';
						$data['msg']="Data received successfully";
						$data['transfer_data']=$return_data_arr['country_city_rcd_html'];
						$data['city_tab_html']=$return_data_arr['city_tab_html'];
						$data['heading_count_rcd']=$return_data_arr['heading_count_rcd'];
						$data['post_data']=$return_data_arr['post_data'];
						$data['post_data']['find_threshold_booking_time']=$find_threshold_booking_time;
					else:
						$data['status'] = 'error';
						$data['msg'] = $return_data_arr['msg'];
					endif;
				endif;
			endif;
		else:
			$data['status'] = 'error';
			$data['msg'] = $autentication_data->msg;
		endif;
	else:
		$data['status'] = 'error';
		$data['msg'] = "We are having some problem to authorize api.";
	endif;
	echo json_encode($data);
?>