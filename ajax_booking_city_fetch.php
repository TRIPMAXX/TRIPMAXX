<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['AGENT_SESSION_DATA']['id'], DOMAIN_NAME_PATH.'');
	$data['status']="error";
	$data['msg']="Some data missing.";
	if(isset($_POST['country_id']) && $_POST['country_id']!=""):
		$state_list = tools::find("first", TM_STATES, 'GROUP_CONCAT(id) as state_ids', "WHERE country_id=:country_id ORDER BY name ASC ", array(":country_id"=>$_POST['country_id']));
		if(!empty($state_list)):
			$city_list = tools::find("all", TM_CITIES, '*', "WHERE state_id IN (".$state_list['state_ids'].") ORDER BY name ASC ", array());
			if(!empty($city_list)):
				$data['status'] = 'success';
				$data['msg'] = "Data received Successfully";
				$data['results'] = $city_list;
			else:
				$data['msg']="Some error has been occure during execution.";
			endif;
		endif;
	endif;
	echo json_encode($data);
?>