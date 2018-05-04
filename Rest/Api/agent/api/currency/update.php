<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		$_POST=$server_data['data'];
		$uploaded_file_json_data="";
		if(tools::module_data_exists_check("currency_name = '".tools::stripcleantohtml($_POST['currency_name'])."' AND id <> ".$_POST['id']."", '', TM_CURRENCIES)):
			$return_data['status'] = 'error';
			$return_data['msg'] = 'This currency name already exists.';
		else:
			if($save_currency = tools::module_form_submission($uploaded_file_json_data, TM_CURRENCIES)):
				$return_data['status'] = 'success';
				$return_data['msg'] = 'Currency has been updated successfully.';
			else:
				$return_data['status'] = 'error';
				$return_data['msg'] = 'We are having some probem. Please try again later.';
			endif;
		endif;
	endif;
	echo json_encode($return_data);	
?>