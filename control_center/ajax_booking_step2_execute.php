<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');	
	$data['status']="error";
	$data['msg']="Some data missing.";
	if(isset($_POST)):
		$_SESSION['step_2']=array();
		if(!empty($_POST))
			$_SESSION['step_2']=$_POST['hotel_room_arr'];
		$data['status']="success";
		$data['msg']="Saved to session.";
	endif;
	echo json_encode($data);
?>