<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		$agent_id=$server_data['data']['id'];
		$find_accounting = tools::find("first", TM_AGENT_ACCOUNTING, '*', "WHERE id=:id ", array(":id"=>$agent_id));
		if(!empty($find_accounting)):
			$_POST=$server_data['data'];
			$_POST['id']=$find_accounting['id'];
			if($save_agent_credit = tools::module_form_submission("", TM_AGENT_ACCOUNTING)):
				$return_data['status'] = 'success';
				$return_data['msg'] = 'Agent credit has been updated successfully.';
				$return_data['results'] = $save_agent_credit;
			else:
				$return_data['status'] = 'error';
				$return_data['msg'] = 'We are having some probem. Please try again later.';
			endif;
		else:
			$return_data['status'] = 'error';
			$return_data['msg'] = 'Invalid agent credit id.';
		endif;
	endif;
	echo json_encode($return_data);	
?>