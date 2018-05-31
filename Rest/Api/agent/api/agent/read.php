<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		if(isset($server_data['data']) && isset($server_data['data']['agent_id']) && $server_data['data']['agent_id']!=""):
			$agent_list = tools::find("first", TM_AGENT, '*', "WHERE id=:id AND type=:type", array(":id"=>base64_decode($server_data['data']['agent_id']), ":type"=>"A"));
		elseif(isset($server_data['data']) && isset($server_data['data']['gsa_id']) && $server_data['data']['gsa_id']!=""):
			$agent_list = tools::find("first", TM_AGENT." as a, ".TM_COUNTRIES." as co, ".TM_STATES." as s, ".TM_CITIES." as ci, ".TM_CURRENCIES." as cu", 'a.*, co.name as co_name, s.name as s_name, ci.name as ci_name, cu.currency_code as currency_code, cu.currency_name as currency_name', "WHERE a.country=co.id AND a.state=s.id AND a.city=ci.id AND a.preferred_currency=cu.id AND a.id=:id AND type=:type ", array(":id"=>base64_decode($server_data['data']['gsa_id']), ":type"=>"G"));
		elseif(isset($server_data['data']) && isset($server_data['data']['type']) && $server_data['data']['type']!=""):
			$agent_list = tools::find("all", TM_AGENT." as a, ".TM_COUNTRIES." as co, ".TM_STATES." as s, ".TM_CITIES." as ci, ".TM_CURRENCIES." as cu", 'a.*, co.name as co_name, s.name as s_name, ci.name as ci_name, cu.currency_code as currency_code, cu.currency_name as currency_name', "WHERE a.country=co.id AND a.state=s.id AND a.city=ci.id AND a.preferred_currency=cu.id AND type=:type ", array(":type"=>"G"));
		elseif(isset($server_data['data']) && isset($server_data['data']['status']) && $server_data['data']['status']==1):
			$agent_list = tools::find("all", TM_AGENT, 'id, type, first_name, middle_name, last_name, code', "WHERE status=:status ORDER BY first_name, middle_name, last_name", array(":status"=>1));
		elseif(isset($server_data['data']) && isset($server_data['data']['payment_type']) && $server_data['data']['payment_type']!="all"):
			$agent_list = tools::find("all", TM_AGENT, 'id, type, first_name, middle_name, last_name, code', "WHERE payment_type=:payment_type ORDER BY first_name, middle_name, last_name", array(":payment_type"=>$server_data['data']['payment_type']));
			// ************************** //
		elseif(isset($server_data['data']) && isset($server_data['data']['agent_type']) && isset($server_data['data']['agents'])):
			if($server_data['data']['agents']!="all"):
				$where_coulse = "WHERE id=:id ";
				$exicute = array(":id"=>$server_data['data']['agents']);
			elseif($server_data['data']['agent_type']!="all"):
				$where_coulse = "WHERE payment_type=:payment_type ";
				$exicute = array(":payment_type"=>$server_data['data']['agent_type']);
			else:
				$where_coulse = "WHERE 1 ";
				$exicute = array();
			endif;
			$agent_list = tools::find("first", TM_AGENT, 'GROUP_CONCAT(id) as agent_ids', $where_coulse, $exicute);
			// ************************** //
		else:
			$agent_list = tools::find("all", TM_AGENT." as a, ".TM_COUNTRIES." as co, ".TM_STATES." as s, ".TM_CITIES." as ci, ".TM_CURRENCIES." as cu", 'a.*, co.name as co_name, s.name as s_name, ci.name as ci_name, cu.currency_code as currency_code, cu.currency_name as currency_name', "WHERE a.country=co.id AND a.state=s.id AND a.city=ci.id AND a.preferred_currency=cu.id AND type=:type AND parent_id=:parent_id ", array(":type"=>"A", ':parent_id'=>(isset($server_data['data']['parent_id']) && $server_data['data']['parent_id']!="" ? $server_data['data']['parent_id'] : 0)));
		endif;
		$return_data['status']="success";
		$return_data['results']=$agent_list;
		$return_data['msg']="Data received successfully.";
	endif;
	echo json_encode($return_data);	
?>