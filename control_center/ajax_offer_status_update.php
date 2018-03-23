<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
	$data['msg']="Some data missing";
	if(isset($_POST['offer_id']) && $_POST['offer_id']!=""):
		$promotional_offer = tools::find("first", TM_PROMOTIONAL_OFFERS, '*', "WHERE id=:id ", array(":id"=>$_POST['offer_id']));
		if(!empty($promotional_offer)):
			if($promotional_offer['status']==1):
				$status=0;
			else:
				$status=1;
			endif;
			if(tools::update(TM_PROMOTIONAL_OFFERS, "status=:status", "WHERE id=:id", array(":status"=>$status, ":id"=>$_POST['offer_id']))):
				$data['msg']="success";
				$data['success']="Status has been updated successfully.";
				$data['status']=$status;
			else:
				$data['msg']="We are having some problem in update. Please try later.";
			endif;
		else:
			$data['msg']="Invalid promotional offer id.";
		endif;
	endif;
	echo json_encode($data);
?>