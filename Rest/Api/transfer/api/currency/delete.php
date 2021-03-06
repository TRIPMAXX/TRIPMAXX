<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		$_GET=$server_data['data'];
		$find_currency = tools::find("first", TM_CURRENCIES, '*', "WHERE id=:id ", array(":id"=>base64_decode($_GET['currency_id'])));
		if(!empty($find_currency)):
			if(tools::delete(TM_CURRENCIES, "WHERE id=:id", array(":id"=>$find_currency['id']))):
				$return_data['status'] = 'success';
				$return_data['msg'] = 'Currency has been deleted successfully.';
			else:
				$return_data['status'] = 'error';
				$return_data['msg'] = 'We are having some probem. Please try again later.';
			endif;
		else:
			$return_data['status'] = 'error';
			$return_data['msg'] = 'Invalid currency id.';
		endif;
	endif;
	echo json_encode($return_data);	
?>