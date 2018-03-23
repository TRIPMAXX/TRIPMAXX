<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && $server_data['token']['token']==TOKEN && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		$supplier_id=$server_data['data']['id'];
		$find_supplier = tools::find("first", TM_SUPPLIER, '*', "WHERE id=:id", array(":id"=>$supplier_id));
		if(!empty($find_supplier)):
			$_POST=$server_data['data'];
			$_POST['id']=$find_supplier['id'];
			$check_flag=true;
			if(isset($server_data['data']['update_type']) && $server_data['data']['update_type']=="status"):
				if($find_supplier['status']==1):
					$_POST['status']=0;
				else:
					$_POST['status']=1;
				endif;
			else:
				if(tools::module_data_exists_check("email_address = '".tools::stripcleantohtml($_POST['email_address'])."' AND id <> ".$find_supplier['id']."", '', TM_SUPPLIER)) {
					$check_flag=false;
					$return_data['status']="error";
					$return_data['msg'] = 'This email address already exists.';
				} else if(tools::module_data_exists_check("supplier_code = '".tools::stripcleantohtml($_POST['supplier_code'])."' AND id <> ".$find_supplier['id']."", '', TM_SUPPLIER)) {
					$check_flag=false;
					$return_data['status']="error";
					$return_data['msg'] = 'This supplier code already exists.';
				}
			endif;
			if($check_flag==true):
				if($save_supplier_data = tools::module_form_submission("", TM_SUPPLIER)):
					$find_updated_supplier = tools::find("first", TM_SUPPLIER, '*', "WHERE id=:id", array(":id"=>$find_supplier['id']));
					$return_data['status'] = 'success';
					$return_data['msg'] = 'Supplier has been updated successfully.';
					$return_data['results'] = $find_updated_supplier;
				else:
					$return_data['status'] = 'error';
					$return_data['msg'] = 'We are having some probem. Please try again later.';
				endif;
			endif;
		else:
			$return_data['status'] = 'error';
			$return_data['msg'] = 'Invalid supplier id.';
		endif;
	endif;
	echo json_encode($return_data);	
?>