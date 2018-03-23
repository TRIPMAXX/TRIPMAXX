<?php
require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');	
	$data['status']="error";
	$data['msg']="Some data missing.";
	if(isset($_POST['supplier_id']) && $_POST['supplier_id']!=""):
		$autentication_data=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.SUPPLIER_API_PATH."authorized.php"));
		$data['msg']="Token is not verified.";
		if(isset($autentication_data->status)):
			if($autentication_data->status=="success"):
				$post_data['token']=array(
					"token"=>$autentication_data->results->token,
					"token_timeout"=>$autentication_data->results->token_timeout,
					"token_generation_time"=>$autentication_data->results->token_generation_time
				);
				$_POST['update_type']="status";
				$post_data['data']=$_POST;
				$post_data_str=json_encode($post_data);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.SUPPLIER_API_PATH."supplier/update.php");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				$return_data = curl_exec($ch);
				curl_close($ch);
				$return_data_arr=json_decode($return_data, true);
				$supplier_data=array();
				if(!isset($return_data_arr['status'])):
					$data['msg']="Some error has been occure during execution.";
				elseif($return_data_arr['status']=="success"):
					$data['status'] = 'success';
					$data['msg'] = $return_data_arr['msg'];
					$data['results'] = $return_data_arr['results'];
				else:
					$data['msg'] = $return_data_arr['msg'];
				endif;
			else:
				$data['msg'] = $autentication_data->msg;
			endif;
		else:
			$data['msg'] = "We are having some problem to authorize api.";
		endif;
	endif;
	echo json_encode($data);
?>