<?php
	require_once('loader.inc');
	$data['status']="error";
	$data['msg']="Some data missing.";
	$data['results']=array();
	if(isset($_POST['country_id']) && $_POST['country_id']!=""):
		$state_list = tools::find("all", TM_STATES, '*', "WHERE country_id=:country_id ORDER BY name ASC ", array(":country_id"=>$server_data['data']['country_id']));
		$data['status']="success";
		$data['msg']="Data received successfully.";
		$data['results']=$state_list;
	endif;
	echo json_encode($data);
?>