<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		if(isset($server_data['data']['prev_agent_id']) && isset($server_data['data']['prev_total_price']) && isset($server_data['data']['prev_quotation_name'])):
			$prev_agent_id=$server_data['data']['prev_agent_id'];
			$find_prev_agent = tools::find("first", TM_AGENT, '*', "WHERE id=:id ", array(":id"=>$prev_agent_id));
			if(!empty($find_prev_agent)):
				$_POST['credit_balance']=$find_prev_agent['credit_balance']+$server_data['data']['prev_total_price'];
				$_POST['id']=$find_prev_agent['id'];
				if($save_prev_agent_data = tools::module_form_submission("", TM_AGENT)):
					unset($_POST);
					$_POST['agent_id']=$find_prev_agent['id'];
					$_POST['amount']=$server_data['data']['prev_total_price'];
					$_POST['note']="Credit refund money for booking with quotation name:".$server_data['data']['prev_quotation_name'];
					$_POST['debit_or_credit']="Credit";
					tools::module_form_submission("", TM_AGENT_ACCOUNTING);
					$return_data['status'] = 'success';
					$return_data['msg'] = 'Agent has been updated successfully.';
				else:
					$return_data['status'] = 'error';
					$return_data['msg'] = 'We are having some probem. Please try again later.';
				endif;
				unset($_POST);
			endif;
		endif;
		if(isset($server_data['data']['agent_id'])):
			$agent_id=$server_data['data']['agent_id'];
			$find_agent = tools::find("first", TM_AGENT, '*', "WHERE id=:id ", array(":id"=>$agent_id));
			if(!empty($find_agent)):
				$_POST['credit_balance']=$find_agent['credit_balance']-$server_data['data']['total_price'];
				$_POST['id']=$find_agent['id'];
				if($save_agent_data = tools::module_form_submission("", TM_AGENT)):
					unset($_POST);
					$_POST['agent_id']=$find_agent['id'];
					$_POST['amount']=$server_data['data']['total_price'];
					$_POST['note']="Debit money for booking with quotation name:".$server_data['data']['quotation_name'];
					$_POST['debit_or_credit']="Debit";
					tools::module_form_submission("", TM_AGENT_ACCOUNTING);
					$return_data['status'] = 'success';
					$return_data['msg'] = 'Agent has been updated successfully.';
				else:
					$return_data['status'] = 'error';
					$return_data['msg'] = 'We are having some probem. Please try again later.';
				endif;
			else:
				$return_data['status'] = 'error';
				$return_data['msg'] = 'Invalid agent id.';
			endif;
		endif;
	endif;
	echo json_encode($return_data);	
?>