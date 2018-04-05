<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		$offer_id=$server_data['data']['id'];
		$find_offer = tools::find("first", TM_OFFERS, '*', "WHERE id=:id AND transfer_id=:transfer_id ", array(":id"=>$offer_id, ":transfer_id"=>$server_data['data']['transfer_id']));
		if(!empty($find_offer)):
			$_POST=$server_data['data'];
			$_POST['id']=$find_offer['id'];
			$check_flag=true;
			if(isset($server_data['data']['update_type']) && $server_data['data']['update_type']=="status"):
				if($find_offer['status']==1):
					$_POST['status']=0;
				else:
					$_POST['status']=1;
				endif;
			else:
				if(tools::module_data_exists_check("offer_title = '".tools::stripcleantohtml($_POST['offer_title'])."'  AND transfer_id='".tools::stripcleantohtml($_POST['transfer_id'])."' AND id <> ".$find_offer['id']."", '', TM_OFFERS)) {
					$check_flag=false;
					$return_data['status']="error";
					$return_data['msg'] = 'This offer title already exists.';		
				}
			endif;
			if($check_flag==true):
				if($save_transfer_offer_data = tools::module_form_submission("", TM_OFFERS)):
					$find_updated_transfer_offer = tools::find("first", TM_OFFERS, '*', "WHERE id=:id", array(":id"=>$find_offer['id']));
					$return_data['status'] = 'success';
					$return_data['msg'] = 'Transfer offer has been updated successfully.';
					$return_data['results'] = $find_updated_transfer_offer;
				else:
					$return_data['status'] = 'error';
					$return_data['msg'] = 'We are having some probem. Please try again later.';
				endif;
			endif;
		else:
			$return_data['status'] = 'error';
			$return_data['msg'] = 'Invalid transfer offer id.';
		endif;
	endif;
	echo json_encode($return_data);	
?>