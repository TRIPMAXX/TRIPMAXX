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
		$find_tour = tools::find("first", TM_TOURS, '*', "WHERE id=:id", array(":id"=>base64_decode($_GET['tour_id'])));
		if(!empty($find_tour)):
			$find_offer = tools::find("all", TM_OFFERS, '*', "WHERE tour_id=:tour_id", array(":tour_id"=>$find_tour['id']));
			if(!empty($find_offer)):
				foreach($find_offer as $offer_key=>$offer_val):
					tools::delete(TM_OFFER_PRICES, "WHERE offer_id=:offer_id", array(":offer_id"=>$offer_val['id']));
					tools::delete(TM_OFFER_AGENT_MARKUP, "WHERE offer_id=:offer_id", array(":offer_id"=>$offer_val['id']));
					tools::delete(TM_OFFER_ADDON_PRICES, "WHERE offer_id=:offer_id", array(":offer_id"=>$offer_val['id']));
					tools::delete(TM_OFFERS, "WHERE id=:id", array(":id"=>$offer_val['id']));
				endforeach;
			endif;
			if(isset($find_tour['tour_images']) && $find_tour['tour_images']!=""):
				$image_arr=explode(",", $find_tour['tour_images']);
				foreach($image_arr as $img_key=>$img_val):
					if($img_val!="" && file_exists(TOUR_IMAGES.$img_val)):
						unlink(TOUR_IMAGES.$img_val);
					endif;
					if($img_val!="" && file_exists(TOUR_IMAGES."thumb/".$img_val)):
						unlink(TOUR_IMAGES."thumb/".$img_val);
					endif;
				endforeach;
			endif;
			if(tools::delete(TM_TOURS, "WHERE id=:id", array(":id"=>$find_tour['id']))):
				$return_data['status'] = 'success';
				$return_data['msg'] = 'Tour has been deleted successfully.';
				$return_data['results'] = array();
			else:
				$return_data['status'] = 'error';
				$return_data['msg'] = 'We are having some probem. Please try again later.';
			endif;
		else:
			$return_data['status'] = 'error';
			$return_data['msg'] = 'Invalid tour id.';
		endif;
	endif;
	echo json_encode($return_data);	
?>