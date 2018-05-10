<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		if(isset($server_data['data']['agent_id']) && $server_data['data']['agent_id']!="" && $server_data['data']['agent_id']>0):
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
		endif;
		$_POST=$server_data['data'];
		$uploaded_file_json_data="";
		if($save_hotel = tools::module_form_submission($uploaded_file_json_data, TM_BOOKINGS)) {
			$return_data['status']="success";
			$return_data['msg'] = 'Package booking has been created successfully.';
			$return_data['results'] = $save_hotel;
			$return_data['booking_type'] = $_POST['booking_type'];
			$return_data['booking_date'] = $_POST['booking_date'];
			$return_data['agent_id'] = $_POST['agent_id'];
		} else {
			$return_data['status']="error";
			$return_data['msg'] = 'We are having some probem. Please try again later.';
		}
	endif;
	echo json_encode($return_data);	
?>