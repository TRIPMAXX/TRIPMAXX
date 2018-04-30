<?php
require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA_HOTEL']['id'], DOMAIN_NAME_PATH_HOTEL.'login');	
	$data['status']="error";
	$data['msg']="Some data missing.";
	if(isset($_POST['room_id']) && $_POST['room_id']!=""):
		$find_attribute = tools::find("first", TM_ROOMS, '*', "WHERE id=:id", array(":id"=>$_POST['room_id']));
		if(!empty($find_attribute)):
			if($find_attribute['status']==1):
				$_POST['status']=0;
			else:
				$_POST['status']=1;
			endif;
			$_POST['id']=$find_attribute['id'];
			if(tools::module_form_submission("", TM_ROOMS)):								
				$data['status'] = 'success';
				$data['msg'] = 'Room has been updated successfully.';
			else:
				$data['status'] = 'error';
				$data['msg'] = 'We are having some probem. Please try again later.';
			endif;
			$find_updated_attribute = tools::find("first", TM_ROOMS, '*', "WHERE id=:id", array(":id"=>$find_attribute['id']));
			$data['results'] = $find_updated_attribute;
		endif;	
	endif;
	echo json_encode($data);
?>