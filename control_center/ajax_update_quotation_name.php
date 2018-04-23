<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');	
	$data['status']="error";
	if(isset($_POST) && !empty($_POST)):
		$_SESSION['step_5']['quotation_name']=$_POST['quotation_name'];
		$data['status']="success";
		$data['msg']="Quotation name saved successfully.";
	endif;
	echo json_encode($data);
?>