<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
	$data['msg']="Some data missing";
	if(isset($_POST['cms_id']) && $_POST['cms_id']!=""):
		$cms_page = tools::find("first", TM_CMS, '*', "WHERE id=:id ", array(":id"=>$_POST['cms_id']));
		if(!empty($cms_page)):
			if($cms_page['status']==1):
				$status=0;
			else:
				$status=1;
			endif;
			if(tools::update(TM_CMS, "status=:status", "WHERE id=:id", array(":status"=>$status, ":id"=>$_POST['cms_id']))):
				$data['msg']="success";
				$data['success']="Status has been updated successfully.";
				$data['status']=$status;
			else:
				$data['msg']="We are having some problem in update. Please try later.";
			endif;
		else:
			$data['msg']="Invalid cms id.";
		endif;
	endif;
	echo json_encode($data);
?>