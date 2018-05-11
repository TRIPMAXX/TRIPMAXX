<?php
	require_once('loader.inc');
	$data['status']="error";
	$data['msg']="Some data missing.";
	if(isset($_POST['state_id']) && $_POST['state_id']!=""):
		$city_list = tools::find("all", TM_CITIES, '*', "WHERE state_id=:state_id ORDER BY name ASC ", array(":state_id"=>$_POST['state_id']));
		$data['status']="success";
		$data['msg']="Data received successfully.";
		$data['results']=$city_list;
	endif;
	echo json_encode($data);
?>