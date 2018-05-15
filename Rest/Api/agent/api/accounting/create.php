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
		if($save_agent_credit = tools::module_form_submission($uploaded_file_json_data, TM_AGENT_ACCOUNTING)) {
			$added_amount=$_POST['amount'];
			$agent_id=$_POST['agent_id'];
			unset($_POST);
			$_POST['transaction_id']=tools::generate_transaction_id("TM-".$save_agent_credit);
			$_POST['id']=$save_agent_credit;
			$save_agent_accounting = tools::module_form_submission("", TM_AGENT_ACCOUNTING);
			$_POST['agent_id']=$agent_id;
			$find_agent = tools::find("first", TM_AGENT, '*', "WHERE id=:id", array(":id"=>$_POST['agent_id']));
			unset($_POST);
			$_POST['id']=$find_agent['id'];
			$_POST['credit_balance']=$find_agent['credit_balance']+$added_amount;
			$update_agent = tools::module_form_submission("", TM_AGENT);
			$return_data['status']="success";
			$return_data['msg'] = 'Agent credit has been created successfully.';
			$return_data['results'] = $save_agent_credit;
		} else {
			$return_data['status']="error";
			$return_data['msg'] = 'We are having some probem. Please try again later.';
		};
	endif;
	echo json_encode($return_data);	
?>