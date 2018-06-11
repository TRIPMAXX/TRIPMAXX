<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['AGENT_SESSION_DATA']['id'], DOMAIN_NAME_PATH.'');	
	$data['status']="error";
	$data['msg']="Some data missing.";
	if(isset($_POST)):
		$_SESSION['step_3']=array();
		if(!empty($_POST))
			$_SESSION['step_3']=$_POST['tour_offer_arr'];
			$_SESSION['step_3_all']=$_POST;
		$data['status']="success";
		$data['msg']="Saved to session.";
	endif;
	echo json_encode($data);
?>