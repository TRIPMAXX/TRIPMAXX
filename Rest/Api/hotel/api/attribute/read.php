<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && $server_data['token']['token']==TOKEN && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		if(isset($server_data['data']) && isset($server_data['data']['multiple_attribute_ids']) && $server_data['data']['multiple_attribute_ids']!=""):
			$attribute_list = tools::find("all", TM_ATTRIBUTES, '*', "WHERE id IN (".$server_data['data']['multiple_attribute_ids'].") ", array());
		elseif(isset($server_data['data']) && isset($server_data['data']['attribute_id']) && $server_data['data']['attribute_id']!=""):
			$attribute_list = tools::find("first", TM_ATTRIBUTES, '*', "WHERE id=:id ", array(":id"=>base64_decode($server_data['data']['attribute_id'])));
		elseif(isset($server_data['data']) && isset($server_data['data']['type']) && $server_data['data']['type']!=""):
			$ext_where=" AND (type IN ('Both', '".$server_data['data']['type']."')) ";
			$attribute_list = tools::find("all", TM_ATTRIBUTES, '*', "WHERE status=:status ".$ext_where." ORDER BY serial_number", array(":status"=>1));
		else:
			$attribute_list = tools::find("all", TM_ATTRIBUTES, '*', "WHERE :all ", array(":all"=>1));
		endif;
		$return_data['status']="success";
		$return_data['results']=$attribute_list;
		$return_data['msg']="Data received successfully.";
	endif;
	echo json_encode($return_data);	
?>