<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
	if(isset($_GET['booking_id']) && $_GET['booking_id']!=""):
		$autentication_data_booking=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.BOOKING_API_PATH."authorized.php"));
		if(isset($autentication_data_booking->status)):
			if($autentication_data_booking->status=="success"):
				$post_data_booking['token']=array(
					"token"=>$autentication_data_booking->results->token,
					"token_timeout"=>$autentication_data_booking->results->token_timeout,
					"token_generation_time"=>$autentication_data_booking->results->token_generation_time
				);
				$post_data_booking['data']['booking_id']=base64_decode($_GET['booking_id']);
				$post_data_str_booking=json_encode($post_data_booking);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.BOOKING_API_PATH."booking/read.php");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_booking);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				$return_data_booking = curl_exec($ch);
				curl_close($ch);
				$return_data_arr_booking=json_decode($return_data_booking, true);
				if(!isset($return_data_arr_booking['status'])):
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH']="Some error has been occure during execution.";
				elseif($return_data_arr_booking['status']=="success"):
					$booking_details_list=$return_data_arr_booking['results'][0];
					//print_r($booking_details_list);
				else:
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH'] = $return_data_arr_booking['msg'];
				endif;

				// HTML \\
				$html_header='
					<div style="width: 100%;">
						<div style="width: 100%; border-bottom: 3px double #54b9f0; border-top: 2px solid #54b9f0;">
							<div style="width:25%;float:left;">
								<img src="assets/img/logo.png" border="0" alt="" style="width: 150px;">
							</div>
							<div style="width:75%;float:left;">
								<table>
									<tr>
										<td colspan="100%" style="font-size:13px;"><img src="assets/img/location-pin-512.png" border="0" alt="" style="width: 12px;"> 2, Ganesh Chandra Avenue, Commerce House, 1st floor, Kolkata 700013. India.</td>
									</tr>
									<tr>
										<td style="font-size:13px;width:50%;"><img src="assets/img/Mail_email_envelope_letter.png" border="0" alt="" style="width: 12px;margin-top:7px;"> travel@tripmaxx.in</td>
										<td style="font-size:13px;width:50%;">&nbsp;&nbsp;&nbsp;<!-- <img src="assets/img/phone.png" border="0" alt="" style="width: 12px;margin-top:3px;"> --> +91 33 4032 8888</td>
									</tr>
								</table>
							</div>
							<div style="clear:both;"></div>
						</div>
					</div>';
				$html_body='
					<div style="width: 100%;">
						<div style="width: 100%;">
							<h3>Complete Your Booking</h3>
							
										<div class="box-body">
											<table style="width:100%; margin-bottom:15px; /*border:1px solid black;" border="1" cellspacing="0">
												<thead>
													<tr role="row">
														<th style="text-align:center;">Pax</th>
														<th style="text-align:center;">Quote Date</th>
														<th style="text-align:center;">Destination</th>
														<th style="text-align:center;">Booking Date</th>
													</tr>
												</thead>
												<tbody aria-relevant="all" aria-live="polite" role="alert">
													<tr class="odd">
														<td style="text-align:center;">1</td>
														<td style="text-align:center;">07/06/2018</td>
														<td style="text-align:center;">Bangkok</td>
														<td style="text-align:center;">05/06/2018</td>
													</tr>
												</tbody>
											</table>
										</div>
										<div class="box-body">
											<table style="width:100%; margin-bottom:15px; /*border:1px solid black;" border="1" cellspacing="0">
												<thead>
													<tr role="row">
														<th style="text-align:left;">Hotel Type</th>
														<th style="text-align:left;">Hotel</th>
														<th style="text-align:center;">Room Type</th>
														<th style="text-align:center;">Check In</th>
														<th style="text-align:center;">Check Out</th>
														<th style="text-align:center;">Rooms</th>
														<th style="text-align:center;">Nights</th>
													</tr>
												</thead>
												<tbody aria-relevant="all" aria-live="polite" role="alert">
													<tr class="odd">
														<td style="text-align:left;">
															Honeymoon					
														</td>
														<td style="text-align:left;">SUNNY RESIDENCE</td>
														<td style="text-align:center;">
															Double + Child W/O Bed Room Superior Room Only						<br>
															<font color="red"></font>
														</td>
														<td style="text-align:center;">05/06/2018</td>
														<td style="text-align:center;">12/06/2018</td>
														<td style="text-align:center;">10</td>
														<td style="text-align:center;">7</td>
													</tr>
												</tbody>
											</table>
										</div>
										<div class="box-body">
											<table style="width:100%; margin-bottom:15px; /*border:1px solid black;" border="1" cellspacing="0">
												<thead>
													<tr role="row">
														<th style="text-align:left;">Transfers</th>
													</tr>
												</thead>
												<tbody aria-relevant="all" aria-live="polite" role="alert">
													<tr class="odd">
														<td style="text-align:left;padding-bottom: 0;" colspan="100%">
															<h4 style="margin: 0;">05/06/2018</h4>
														</td>
													</tr>
													<tr class="odd">
														<td style="text-align:left;">
															Bangkok Suvarnabhumi Airport - Bangkok City Hotel Service - Private Standard Van with Luggage - Private ( Capacity:  4 )
															<br>
															Price: INR2,568.24												<br>
															Pick Up/Drop off Type: Arrival												<br>
															Pick Up Time: 10:00 AM												<br>
															Drop off Time: 12:00 PM												<br>
															Airport: Buri Ram Airport											
														</td>
													</tr>
												</tbody>
											</table>
										</div>
										<div class="box-body">
											<table style="width:100%; margin-bottom:15px; /*border:1px solid black;" border="1" cellspacing="0">
												<thead>
													<tr role="row">
														<th style="text-align:left;">Tour Sites</th>
													</tr>
												</thead>
												<tbody aria-relevant="all" aria-live="polite" role="alert">
													<tr class="odd">
														<td style="text-align:left;padding-bottom: 0;">
															<h4 style="margin: 0;">05/06/2018</h4>
														</td>
													</tr>
													<tr class="odd">
														<td style="text-align:left;">
															Art In Paradise with return transfer - Private Tour - Private ( Capacity:  4 )
															<br>
															Price: INR2,878.40												<br>
															Pick Up Time: 05:00 PM												<br>
															Drop off Time: 08:00 PM											
														</td>
													</tr>
												</tbody>
											</table>
										</div>
										<div class="box-body">
											<table style="width:100%; margin-bottom:15px; /*border:1px solid black;" border="1" cellspacing="0">
												<thead>
													<tr role="row">
														<th style="text-align:center;" colspan="3">Quotation</th>
													</tr>
												</thead>
												<tbody aria-relevant="all" aria-live="polite" role="alert">
													<tr class="odd">
														<td style="text-align:left;font-weight:bold;">Total Cost for Hotel Accommodation</td>
														<td style="text-align:center;font-weight:bold;" colspan="2">INR6,742.55</td>
													</tr>
													<tr class="odd">
														<td style="text-align:left;font-weight:bold;">Add-on : Cost for other components Tours &amp; Transfer</td>
														<td style="text-align:center;font-weight:bold;"><br>INR5,446.64</td>
														<td style="text-align:center;font-weight:bold;"><br>INR0.00</td>
													</tr>
													<tr class="odd">
														<td style="text-align:left;font-weight:bold;">No of Guests</td>
														<td style="text-align:center;font-weight:bold;">ADULT<br>1</td>
														<td style="text-align:center;font-weight:bold;">CHILD<br>0</td>
													</tr>
													<tr class="odd">
														<td style="text-align:left;font-weight:bold;">Total Quantity</td>
														<td style="text-align:center;font-weight:bold;color:red;" colspan="2">INR12,189.19</td>
													</tr>
												</tbody>
											</table>
										</div>
						</div>
						<div style="width: 100%;">
						</div>
					</div>';
				$html_footer='
					<div style="width: 100%;">
						<div style="width: 100%; border-top: 3px double #54b9f0; border-bottom: 2px solid #54b9f0;">
							<h6 style="margin:5px 0">&copy; COPYRIGHT 2017 TRIPMAXX. ALL RIGHTS RESERVED</h6>
						</div>
					</div>';
				/*print $html_header;
				print $html_body;
				print $html_footer;
				exit;*/

				include("assets/mpdf/mpdf.php");
				$mpdf=new mPDF('c','A4','','','15','15','28','18'); 
				$mpdf->SetHeader($html_header);
				$mpdf->SetFooter($html_footer);
				$mpdf->WriteHTML($html_body);
				$mpdf->Output();
				$mpdf->Output('booking_invoice.pdf','D');

				exit;




			endif;
		else:
			$_SESSION['SET_TYPE'] = 'error';
			$_SESSION['SET_FLASH'] = $autentication_data->msg;
		endif;
	else:
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = 'Some data missing.';
		header("location:bookings");
		exit;
	endif;
?>