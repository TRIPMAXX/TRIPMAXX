<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
	$data['msg']="Some data missing";
	if(isset($_POST['emp_id']) && $_POST['emp_id']!=""):
		$find_employee = tools::find("first", TM_DMC, '*', "WHERE id=:id ", array(":id"=>$_POST['emp_id']));
		if(!empty($find_employee)):
			if($find_employee['status']==1):
				$status=0;
			else:
				$status=1;
			endif;
			if(tools::update(TM_DMC, "status=:status", "WHERE id=:id", array(":status"=>$status, ":id"=>$_POST['emp_id']))):
				$data['msg']="success";
				$data['success']="Status has been updated successfully.";
				$data['status']=$status;
			else:
				$data['msg']="We are having some problem in update. Please try later.";
			endif;
		else:
			$data['msg']="Invalid employee id.";
		endif;
	endif;
	echo json_encode($data);
?>