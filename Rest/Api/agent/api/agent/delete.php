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
		$find_agent = tools::find("first", TM_AGENT, '*', "WHERE id=:id", array(":id"=>base64_decode($_GET['agent_id'])));
		if(!empty($find_agent)):
			if($find_agent['type']=="G"):
				$find_sub_agent = tools::find("all", TM_AGENT, '*', "WHERE parent_id=:parent_id", array(":parent_id"=>$find_agent['id']));
				if(!empty($find_sub_agent)):
					foreach($find_sub_agent as $sub_agent_key=>$sub_agent_val):
						if(isset($sub_agent_val['image']) && $sub_agent_val['image']!=""):
							if($sub_agent_val['image']!="" && file_exists(AGENT_IMAGES.$sub_agent_val['image']))
								unlink(AGENT_IMAGES.$sub_agent_val['image']);
						endif;
						tools::delete(TM_AGENT_ACCOUNTING, "WHERE agent_id=:agent_id", array(":agent_id"=>$sub_agent_val['id']));
						tools::delete(TM_AGENT, "WHERE id=:id", array(":id"=>$sub_agent_val['id']));
					endforeach;
				endif;
			endif;
			if(isset($find_agent['image']) && $find_agent['image']!=""):
				if($find_agent['image']!="" && file_exists(AGENT_IMAGES.$find_agent['image']))
					unlink(AGENT_IMAGES.$find_agent['image']);
			endif;
			tools::delete(TM_AGENT_ACCOUNTING, "WHERE agent_id=:agent_id", array(":agent_id"=>$find_agent['id']));
			if(tools::delete(TM_AGENT, "WHERE id=:id", array(":id"=>$find_agent['id']))):
				$return_data['status'] = 'success';
				$return_data['msg'] = 'Agent has been deleted successfully.';
				if($find_agent['type']=="G")
					$return_data['msg'] = 'GSA has been deleted successfully.';
				$return_data['results'] = array();
			else:
				$return_data['status'] = 'error';
				$return_data['msg'] = 'We are having some probem. Please try again later.';
			endif;
		else:
			$return_data['status'] = 'error';
			$return_data['msg'] = 'Invalid agent id.';
		endif;
	endif;
	echo json_encode($return_data);	
?>