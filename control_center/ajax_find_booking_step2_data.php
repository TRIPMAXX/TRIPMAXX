<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');	
	$data['status']="error";
	$data['msg']="Some data missing.";
	$autentication_data=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."authorized.php"));
	if(isset($autentication_data->status)):
		if($autentication_data->status=="success"):
			$post_data['token']=array(
				"token"=>$autentication_data->results->token,
				"token_timeout"=>$autentication_data->results->token_timeout,
				"token_generation_time"=>$autentication_data->results->token_generation_time
			);
			if(isset($_POST) && !empty($_POST)):
				$offset=0;
				if(isset($_POST['page']) && $_POST['page']!="")
					$offset=($_POST['page']-1)*RECORD_PER_PAGE;
				if(isset($_POST['type']) && $_POST['type']==1):
					if(isset($_SESSION['step_1']) && isset($_SESSION['step_1']['country']) && !empty($_SESSION['step_1']['country'])):
						foreach($_SESSION['step_1']['country'] as $country_key=>$country_val):
						endforeach;
				endif;
			endif;
		else:
			$data['status'] = 'error';
			$data['msg'] = $autentication_data->msg;
		endif;
	else:
		$data['status'] = 'error';
		$data['msg'] = "We are having some problem to authorize api.";
	endif;
	echo json_encode($data);
?>
0-10 = 1-1 * 10
10-10 = 2-1 *10
20-10 = 3-1 *10
30-10 = 4-1*10