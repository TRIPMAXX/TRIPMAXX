<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && $server_data['token']['token']==TOKEN && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		$_POST=$server_data['data'];
		$uploaded_file_json_data="";
		if(tools::module_data_exists_check("email_address = '".tools::stripcleantohtml($_POST['email_address'])."'", '', TM_SUPPLIER)) {
			$return_data['status']="error";
			$return_data['msg'] = 'This email address already exists.';
		} else if(tools::module_data_exists_check("supplier_code = '".tools::stripcleantohtml($_POST['supplier_code'])."'", '', TM_SUPPLIER)) {
			$return_data['status']="error";
			$return_data['msg'] = 'This supplier code already exists.';
		} else {
			if($save_employee = tools::module_form_submission($uploaded_file_json_data, TM_SUPPLIER)) {
				$return_data['status']="success";
				$return_data['msg'] = 'Supplier has been created successfully.';
				$return_data['results'] = $save_employee;
			} else {
				$return_data['status']="error";
				$return_data['msg'] = 'We are having some probem. Please try again later.';
			}
		};
	endif;
	echo json_encode($return_data);	
?>