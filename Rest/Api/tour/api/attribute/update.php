<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		$attribute_id=$server_data['data']['id'];
		$find_attribute = tools::find("first", TM_ATTRIBUTES, '*', "WHERE id=:id", array(":id"=>$attribute_id));
		if(!empty($find_attribute)):
			$_POST=$server_data['data'];
			$_POST['id']=$find_attribute['id'];
			$check_flag=true;
			if(isset($server_data['data']['update_type']) && $server_data['data']['update_type']=="status"):
				if($find_attribute['status']==1):
					$_POST['status']=0;
				else:
					$_POST['status']=1;
				endif;
			else:
				if(tools::module_data_exists_check("attribute_name = '".tools::stripcleantohtml($_POST['attribute_name'])."' AND id <> ".$find_attribute['id']."", '', TM_ATTRIBUTES)) {
					$check_flag=false;
					$return_data['status']="error";
					$return_data['msg'] = 'This attribute name already exists.';		
				}
			endif;
			if($check_flag==true):
				if($save_attribute_data = tools::module_form_submission("", TM_ATTRIBUTES)):
					$find_updated_attribute = tools::find("first", TM_ATTRIBUTES, '*', "WHERE id=:id", array(":id"=>$find_attribute['id']));
					$return_data['status'] = 'success';
					$return_data['msg'] = 'Attribute has been updated successfully.';
					$return_data['results'] = $find_updated_attribute;
				else:
					$return_data['status'] = 'error';
					$return_data['msg'] = 'We are having some probem. Please try again later.';
				endif;
			endif;
		else:
			$return_data['status'] = 'error';
			$return_data['msg'] = 'Invalid attribute id.';
		endif;
	endif;
	echo json_encode($return_data);	
?>