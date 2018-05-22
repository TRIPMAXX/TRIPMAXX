<?php
	include_once('../../init.php');
	$booking_list = tools::find("all", TM_BOOKING_MASTERS." as b, ".TM_CURRENCIES." as cu", 'b.*, cu.currency_code as currency_code, cu.currency_name as currency_name', "WHERE b.invoice_currency=cu.id AND :all AND b.is_deleted = :is_deleted AND payment_type=:payment_type AND payment_status=:payment_status AND pay_within_days>:pay_within_days AND is_emailed=:is_emailed AND ABS(DATEDIFF(DATE(NOW()), DATE(`last_updated`)))=(pay_within_days+1)", array(":all"=>1, ":is_deleted"=>"N", ":payment_type"=>"cash", ":payment_status"=>"U", ':pay_within_days'=>0, ':is_emailed'=>0));
	if(!empty($booking_list)):
		foreach($booking_list as $booking_key=>$booking_val):
			$_POST['id']=$booking_val['id'];
			$_POST['is_deleted']="Y";
			if($save_booking = tools::module_form_submission("", TM_BOOKING_MASTERS)):
				$return_data['status']="success";
				echo $return_data['msg']="Booking data deleted successfully.";
			else:
				$return_data['status']="error";
				$return_data['msg']="We are having some problem. Please try later.";
			endif;
		endforeach;
	endif;
?>