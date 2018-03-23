<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
	$data['msg']="Some data missing";
	if(isset($_POST['banner_id']) && $_POST['banner_id']!=""):
		$find_banner = tools::find("first", TM_HOME_SLIDER, '*', "WHERE id=:id ", array(":id"=>$_POST['banner_id']));
		if(!empty($find_banner)):
			if($find_banner['status']==1):
				$status=0;
			else:
				$status=1;
			endif;
			if(tools::update(TM_HOME_SLIDER, "status=:status", "WHERE id=:id", array(":status"=>$status, ":id"=>$_POST['banner_id']))):
				$data['msg']="success";
				$data['success']="Status has been updated successfully.";
				$data['status']=$status;
			else:
				$data['msg']="We are having some problem in update. Please try later.";
			endif;
		else:
			$data['msg']="Invalid home slider id.";
		endif;
	endif;
	echo json_encode($data);
?>