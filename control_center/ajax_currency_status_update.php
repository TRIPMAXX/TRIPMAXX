<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
	$data['msg']="Some data missing";
	if(isset($_POST['currency_id']) && $_POST['currency_id']!=""):
		$find_currency = tools::find("first", TM_CURRENCIES, '*', "WHERE id=:id ", array(":id"=>$_POST['currency_id']));
		if(!empty($find_currency)):
			if($find_currency['status']==1):
				$status=0;
			else:
				$status=1;
			endif;
			if(tools::update(TM_CURRENCIES, "status=:status", "WHERE id=:id", array(":status"=>$status, ":id"=>$_POST['currency_id']))):
				$data['msg']="success";
				$data['success']="Status has been updated successfully.";
				$data['status']=$status;
			else:
				$data['msg']="We are having some problem in update. Please try later.";
			endif;
		else:
			$data['msg']="Invalid currency id.";
		endif;
	endif;
	echo json_encode($data);
?>