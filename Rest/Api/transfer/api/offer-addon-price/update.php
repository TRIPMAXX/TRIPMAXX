<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		$offer_id=$server_data['data']['offer_id'];
		$find_offer = tools::find("first", TM_OFFERS, '*', "WHERE id=:id ", array(":id"=>$offer_id));
		if(!empty($find_offer)):
			$_POST['offer_id']=$server_data['data']['offer_id'];
			foreach($server_data['data']['addon_price'] as $addon_price_key=>$addon_price_val):
				if($addon_price_val!=""):
					if(isset($_POST['id']))
						unset($_POST['id']);
					if(isset($server_data['data']['addon_price_id'][$addon_price_key]) && $server_data['data']['addon_price_id'][$addon_price_key]!=""):
						$_POST['id']=$server_data['data']['addon_price_id'][$addon_price_key];
					endif;
					$_POST['nationality']=$server_data['data']['nationality'][$addon_price_key];
					$_POST['country_id']=$server_data['data']['country_id'][$addon_price_key];
					$_POST['addon_price']=$server_data['data']['addon_price'][$addon_price_key];
					$save_offer_prices = tools::module_form_submission("", TM_OFFER_ADDON_PRICES);
				endif;
			endforeach;
			$return_data['status']="success";
			$return_data['msg'] = 'Transfer offer addon price has been saved successfully.';
		else:
			$return_data['status'] = 'error';
			$return_data['msg'] = 'Invalid transfer offer id.';
		endif;
	endif;
	echo json_encode($return_data);	
?>