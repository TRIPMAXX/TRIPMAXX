<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA_HOTEL']['id'], DOMAIN_NAME_PATH_HOTEL.'login');	
	$data['status']="error";
	$data['msg']="Some data missing.";
	if(isset($_POST['country_id']) && $_POST['country_id']!=""):		
		$return_data_arr = tools::find("all", TM_STATES, '*', "WHERE country_id=:country_id ORDER BY name ASC ", array(":country_id"=>$_POST['country_id']));
		if(!empty($return_data_arr)):
			$data['status'] = 'success';
			$data['msg'] = '';
			$data['results'] = $return_data_arr;
		else:
			$data['msg'] = '';
		endif;			
	endif;
	echo json_encode($data);
?>