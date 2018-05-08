<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		if(isset($server_data['data']) && isset($server_data['data']['emp_id']) && $server_data['data']['emp_id']!=""):
			$emp_list = tools::find("first", TM_DMC, '*', "WHERE id=:id AND account_type=:account_type AND status=:status ", array(":id"=>$server_data['data']['emp_id'], ":account_type"=>"E", ":status"=>1));
		else:
			$emp_list = tools::find("all", TM_DMC, '*', "WHERE account_type=:account_type AND status=:status ", array(":account_type"=>"E", ":status"=>1));
		endif;
		$return_data['status']="success";
		$return_data['results']=$emp_list;
		$return_data['msg']="Data received successfully.";
	endif;
	echo json_encode($return_data);	
?>