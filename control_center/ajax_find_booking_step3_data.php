<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');	
	$data['status']="error";
	$data['msg']="Some data missing.";
	$autentication_data=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.TOUR_API_PATH."authorized.php"));
	if(isset($autentication_data->status)):
		if($autentication_data->status=="success"):
			$post_data['token']=array(
				"token"=>$autentication_data->results->token,
				"token_timeout"=>$autentication_data->results->token_timeout,
				"token_generation_time"=>$autentication_data->results->token_generation_time
			);
			if(isset($_POST) && !empty($_POST)):
				$find_threshold_booking_time = tools::find("first", TM_SETTINGS, '*', "WHERE id=:id ", array(":id"=>1));
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
					$post_data['data']['booking_tour_date']=$_POST['booking_tour_date'];
					$post_data['data']['tour_type']=$_POST['tour_type'];
					$post_data['data']['pick_time']=$_POST['pick_time'];
					$post_data['data']['selected_service_type']=$_POST['selected_service_type'];
					$post_data['data']['search_counter']=$_POST['search_counter'];
					$post_data['data']['threshold_booking_time']=$find_threshold_booking_time;
					if(isset($_POST['booking_details_list']) && $_POST['booking_details_list']!=""):
						//$post_data['data']['booking_details_list']=$_POST['booking_details_list'];
					endif;
					$post_data_str=json_encode($post_data);
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_HEADER, false);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
					curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.TOUR_API_PATH."tour/booking-tour.php");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					$return_data = curl_exec($ch);
					curl_close($ch);
					$return_data_arr=json_decode($return_data, true);
					$tour_data=array();
					if(!isset($return_data_arr['status'])):
						$data['status'] = 'error';
						$data['msg']="Some error has been occure during execution.";
					elseif($return_data_arr['status']=="success"):
						$data['status'] = 'success';
						$data['msg']="Data received successfully";
						$data['tour_data']=$return_data_arr['country_city_rcd_html'];
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