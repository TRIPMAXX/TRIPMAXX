<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		if(isset($server_data['data']) && isset($server_data['data']['response_id']) && $server_data['data']['response_id']!=""):
			$ticket_list = tools::find("first", TM_SUPPORT_TICKET_REPLIES, '*', "WHERE id=:id ", array(":id"=>base64_decode($server_data['data']['response_id'])));
		elseif(isset($server_data['data']) && isset($server_data['data']['support_ticket_id']) && $server_data['data']['support_ticket_id']!=""):
			$ticket_list = tools::find("all", TM_SUPPORT_TICKET_REPLIES, '*', "WHERE support_ticket_id=:support_ticket_id", array(":support_ticket_id"=>$server_data['data']['support_ticket_id']));
		else:
			$ticket_list = tools::find("all", TM_SUPPORT_TICKET_REPLIES, '*', "WHERE :all ", array(":all"=>1));
		endif;
		$return_data['status']="success";
		$return_data['results']=$ticket_list;
		$return_data['msg']="Data received successfully.";
	endif;
	echo json_encode($return_data);	
?>