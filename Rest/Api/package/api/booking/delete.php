<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		$_GET=$server_data['data'];
		$find_offer = tools::find("first", TM_OFFERS, '*', "WHERE id=:id", array(":id"=>base64_decode($_GET['offer_id'])));
		if(!empty($find_offer)):
			tools::delete(TM_OFFER_PRICES, "WHERE offer_id=:offer_id", array(":offer_id"=>$find_offer['id']));
			tools::delete(TM_OFFER_AGENT_MARKUP, "WHERE offer_id=:offer_id", array(":offer_id"=>$find_offer['id']));
			tools::delete(TM_OFFER_ADDON_PRICES, "WHERE offer_id=:offer_id", array(":offer_id"=>$find_offer['id']));
			if(tools::delete(TM_OFFERS, "WHERE id=:id", array(":id"=>$find_offer['id']))):
				$return_data['status'] = 'success';
				$return_data['msg'] = 'Offer has been deleted successfully.';
				$return_data['results'] = array();
			else:
				$return_data['status'] = 'error';
				$return_data['msg'] = 'We are having some probem. Please try again later.';
			endif;
		else:
			$return_data['status'] = 'error';
			$return_data['msg'] = 'Invalid offer id.';
		endif;
	endif;
	echo json_encode($return_data);	
?>