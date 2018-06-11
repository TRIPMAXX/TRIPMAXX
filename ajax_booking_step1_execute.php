<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['AGENT_SESSION_DATA']['id'], DOMAIN_NAME_PATH.'');
	$data['status']="error";
	$data['msg']="Some data missing.";
	if(isset($_POST) && !empty($_POST)):
		$_SESSION['step_1']=$_POST;
		$_SESSION['step_1']['booking_type']="agent";
		$_SESSION['step_1']['agent_name']=$_SESSION['AGENT_SESSION_DATA']['id'];
		$data['status']="success";
		$data['msg']="Saved to session.";
	endif;
	echo json_encode($data);
?>