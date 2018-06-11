<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['AGENT_SESSION_DATA']['id'], DOMAIN_NAME_PATH.'');
	$data['status']="error";
	$data['msg']="Some data missing.";
	if(isset($_POST)):
		$_SESSION['step_4']=array();
		if(!empty($_POST))
			$_SESSION['step_4']=$_POST['transfer_offer_arr'];
			$_SESSION['step_4_all']=$_POST;
		$data['status']="success";
		$data['msg']="Saved to session.";
	endif;
	echo json_encode($data);
?>