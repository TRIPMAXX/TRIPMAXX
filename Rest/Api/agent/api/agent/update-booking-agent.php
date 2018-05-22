<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		if($return_data_arr['results']['payment_type']=="cash"):
		$find_agent = tools::find("first", TM_AGENT, '*', "WHERE id=:id ", array(":id"=>$agent_id));
		if(!empty($find_agent)):
			if($find_agent['payment_type']=="credit"):
				$closing_balance=$_POST['credit_balance']=$find_agent['credit_balance']-$server_data['data']['total_price'];
				$_POST['id']=$find_agent['id'];
				if($save_agent_data = tools::module_form_submission("", TM_AGENT)):
					unset($_POST);
					$_POST['agent_id']=$find_agent['id'];
					$_POST['amount']=$server_data['data']['total_price'];
					$_POST['note']="Debit money for booking with quotation name:".$server_data['data']['quotation_name'];
					$_POST['debit_or_credit']="Debit";
					$_POST['closing_balance']=$closing_balance;
					tools::module_form_submission("", TM_AGENT_ACCOUNTING);
					$return_data['status'] = 'success';
					$return_data['result'] = $find_agent;
					if($find_agent['type']=="A" && $find_agent['parent_id'] > 0):
						$find_gsm = tools::find("first", TM_AGENT, '*', "WHERE id=:id ", array(":id"=>$find_agent['parent_id']));
						$return_data['result_gsm'] = $find_agent;
					endif;
					$return_data['msg'] = 'Agent has been updated successfully.';
				else:
					$return_data['status'] = 'error';
					$return_data['msg'] = 'We are having some probem. Please try again later.';
				endif;
			else:
				$return_data['status'] = 'success';
				$return_data['msg'] = 'Paying with cash.';
				$return_data['result'] = $find_agent;
				if($find_agent['type']=="A" && $find_agent['parent_id'] > 0):
					$find_gsm = tools::find("first", TM_AGENT, '*', "WHERE id=:id ", array(":id"=>$find_agent['parent_id']));
					$return_data['result_gsm'] = $find_agent;
				endif;
			endif;
		else:
			$return_data['status'] = 'error';
			$return_data['msg'] = 'Invalid agent id.';
		endif;
	endif;
	echo json_encode($return_data);	
?>