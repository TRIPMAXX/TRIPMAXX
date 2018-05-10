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
		$find_cost = tools::find("first", TM_COSTS, '*', "WHERE id=:id", array(":id"=>base64_decode($_GET['cost_id'])));
		if(!empty($find_cost)):
			if(tools::delete(TM_COSTS, "WHERE id=:id", array(":id"=>$find_cost['id']))):
				$return_data['status'] = 'success';
				$return_data['msg'] = 'Booking cost has been deleted successfully.';
				$return_data['results'] = array();
			else:
				$return_data['status'] = 'error';
				$return_data['msg'] = 'We are having some probem. Please try again later.';
			endif;
		else:
			$return_data['status'] = 'error';
			$return_data['msg'] = 'Invalid cost id.';
		endif;
	endif;
	echo json_encode($return_data);	
?>