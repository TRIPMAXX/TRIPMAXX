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
		$find_transfer = tools::find("first", TM_TRANSFER, '*', "WHERE id=:id", array(":id"=>base64_decode($_GET['transfer_id'])));
		if(!empty($find_transfer)):
			$find_offer = tools::find("all", TM_OFFERS, '*', "WHERE transfer_id=:transfer_id", array(":transfer_id"=>$find_transfer['id']));
			if(!empty($find_offer)):
				foreach($find_offer as $offer_key=>$offer_val):
					tools::delete(TM_OFFER_PRICES, "WHERE offer_id=:offer_id", array(":offer_id"=>$offer_val['id']));
					tools::delete(TM_OFFER_ADDON_PRICES, "WHERE offer_id=:offer_id", array(":offer_id"=>$offer_val['id']));
					tools::delete(TM_OFFERS, "WHERE id=:id", array(":id"=>$offer_val['id']));
				endforeach;
			endif;
			if(isset($find_transfer['transfer_images']) && $find_transfer['transfer_images']!=""):
				$image_arr=explode(",", $find_transfer['transfer_images']);
				foreach($image_arr as $img_key=>$img_val):
					if($img_val!="" && file_exists(TRANSFER_IMAGES.$img_val)):
						unlink(TRANSFER_IMAGES.$img_val);
					endif;
				endforeach;
			endif;			
			if(tools::delete(TM_TRANSFER, "WHERE id=:id", array(":id"=>$find_transfer['id']))):
				$return_data['status'] = 'success';
				$return_data['msg'] = 'Transfer has been deleted successfully.';
				$return_data['results'] = array();
			else:
				$return_data['status'] = 'error';
				$return_data['msg'] = 'We are having some probem. Please try again later.';
			endif;
		else:
			$return_data['status'] = 'error';
			$return_data['msg'] = 'Invalid transfer id.';
		endif;
	endif;
	echo json_encode($return_data);	
?>