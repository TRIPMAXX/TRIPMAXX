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
		$find_accounting = tools::find("first", TM_AGENT_ACCOUNTING, '*', "WHERE id=:id", array(":id"=>base64_decode($_GET['accounting_id'])));
		if(!empty($find_accounting)):
			$find_agent = tools::find("first", TM_AGENT, '*', "WHERE id=:id", array(":id"=>$find_accounting['agent_id']));
			if(tools::delete(TM_AGENT_ACCOUNTING, "WHERE id=:id", array(":id"=>$find_accounting['id']))):
				$_POST['id']=$find_agent['id'];
				$_POST['credit_balance']=$find_agent['credit_balance']-$find_accounting['amount'];
				$update_agent = tools::module_form_submission("", TM_AGENT);
				$return_data['status'] = 'success';
				$return_data['msg'] = 'Agent credit has been deleted successfully.';
				$return_data['results'] = array();
			else:
				$return_data['status'] = 'error';
				$return_data['msg'] = 'We are having some probem. Please try again later.';
			endif;
		else:
			$return_data['status'] = 'error';
			$return_data['msg'] = 'Invalid accounting id.';
		endif;
	endif;
	echo json_encode($return_data);	
?>