<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
	
		/*if(isset($server_data['data']['agent_id']) && $server_data['data']['agent_id']!="" && $server_data['data']['agent_id']>0):
			$autentication_data1=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."authorized.php"));
			if(isset($autentication_data1->status)):
				if($autentication_data1->status=="success"):
					$post_data1['token']=array(
						"token"=>$autentication_data1->results->token,
						"token_timeout"=>$autentication_data1->results->token_timeout,
						"token_generation_time"=>$autentication_data1->results->token_generation_time
					);
					$post_data1['data']['agent_id']=base64_encode($server_data['data']['agent_id']);
					$post_data_str1=json_encode($post_data1);
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_HEADER, false);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
					curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."agent/read.php");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str1);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					$return_data1 = curl_exec($ch);
					curl_close($ch);
					//print_r($return_data1);
					$return_data_arr1=json_decode($return_data1, true);
					$agent_data=array();
					if($return_data_arr1['status']=="success"):
						$agent_data=$return_data_arr1['results'];
						$server_data['data']['agent_commission']=$agent_data['package_price'];
					//else:
					//	$_SESSION['SET_TYPE'] = 'error';
					//	$_SESSION['SET_FLASH'] = $return_data_arr1['msg'];
					endif;
				else:
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH'] = $autentication_data1->msg;
				endif;
			else:
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH'] = "We are having some problem to authorize api.";
			endif;
		endif;*/
		$offer_id=$server_data['data']['id'];
		$find_offer = tools::find("first", TM_BOOKINGS, '*', "WHERE id=:id AND package_id=:package_id ", array(":id"=>$offer_id, ":package_id"=>$server_data['data']['package_id']));
		if(!empty($find_offer)):
			$_POST=$server_data['data'];
			$_POST['id']=$find_offer['id'];
			$check_flag=true;
			if(isset($server_data['data']['update_type']) && $server_data['data']['update_type']=="status"):
				if($find_offer['status']==1):
					$_POST['status']=0;
				else:
					$_POST['status']=1;
				endif;
			endif;
			if($check_flag==true):
				if($save_tour_offer_data = tools::module_form_submission("", TM_BOOKINGS)):
					$find_updated_tour_offer = tools::find("first", TM_BOOKINGS, '*', "WHERE id=:id", array(":id"=>$find_offer['id']));
					$return_data['status'] = 'success';
					$return_data['msg'] = 'Booking has been updated successfully.';
					$return_data['results'] = $find_updated_tour_offer;
				else:
					$return_data['status'] = 'error';
					$return_data['msg'] = 'We are having some probem. Please try again later.';
				endif;
			endif;
		else:
			$return_data['status'] = 'error';
			$return_data['msg'] = 'Invalid booking id.';
		endif;
	endif;
	echo json_encode($return_data);	
?>