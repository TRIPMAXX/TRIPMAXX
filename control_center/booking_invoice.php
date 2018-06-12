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
					//print_r($booking_details_list);exit;
				else:
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH'] = $return_data_arr_booking['msg'];
				endif;
				//
				
				$number_of_person=$number_of_adult=$number_of_child=0;
				$audlt_arr=json_decode($booking_details_list['adult'], true);
				foreach($audlt_arr as $adult_key=>$adult_val):
					if($adult_val!="")
						$number_of_adult=$number_of_adult+$adult_val;
				endforeach;
				$child_arr=json_decode($booking_details_list['child'], true);
				foreach($child_arr as $child_key=>$child_val):
					if(isset($child_val['child']) && $child_val['child']!="")
						$number_of_child=$number_of_child+$child_val['child'];
				endforeach;
				$number_of_person=$number_of_adult+$number_of_child;

				///////////////////////////////////
				$hotel_html='';
				$tour_html='';
				$transfer_html='';
				$hotel_price=$tour_price=$transfer_price=0.00;
				if(isset($booking_details_list['booking_destination_list']) && !empty($booking_details_list['booking_destination_list'])):
					foreach($booking_details_list['booking_destination_list'] as $desti_key=>$desti_val):
						if(isset($desti_val['booking_hotel_list']) && !empty($desti_val['booking_hotel_list'])):
							foreach($desti_val['booking_hotel_list'] as $hotel_key=>$hotel_val):
								$autentication_data_hotel=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."authorized.php"));
								if(isset($autentication_data_hotel->status)):
									if($autentication_data_hotel->status=="success"):
										$post_data_hotel['token']=array(
											"token"=>$autentication_data_hotel->results->token,
											"token_timeout"=>$autentication_data_hotel->results->token_timeout,
											"token_generation_time"=>$autentication_data_hotel->results->token_generation_time
										);
										$post_data_hotel['data']['hotel_id']=$hotel_val['hotel_id'];
										$post_data_hotel['data']['room_id']=$hotel_val['room_id'];
										$post_data_str_hotel=json_encode($post_data_hotel);
										$ch = curl_init();
										curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
										curl_setopt($ch, CURLOPT_HEADER, false);
										curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
										curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
										curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."hotel/find-booked-hotel.php");
										curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_hotel);
										curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
										$return_data_hotel = curl_exec($ch);
										curl_close($ch);
										$return_data_arr_hotel=json_decode($return_data_hotel, true);
										if(!isset($return_data_arr_hotel['status'])):
											$data['status'] = 'error';
											$data['msg']="Some error has been occure during execution.";
										elseif($return_data_arr_hotel['status']=="success"):
											if(isset($return_data_arr_hotel['find_hotel']) && isset($return_data_arr_hotel['find_room'])):
												ob_start();
											?>
												<tr class="odd">
													<td style = "text-align:left;"><?php echo $return_data_arr_hotel['find_hotel']['hotel_name'];?></td>
													<td style = "text-align:center;">
														<?= $return_data_arr_hotel['find_room']['room_type'];?>
														<br/>
														<font color="red"><?= $return_data_arr_hotel['find_room']['room_description'];?></font>
													</td>
													<td style = "text-align:center;"><?php echo tools::module_date_format($hotel_val['booking_start_date'], "Y-m-d");?></td>
													<td style = "text-align:center;"><?php echo tools::module_date_format($hotel_val['booking_end_date'], "Y-m-d");?></td>
													<td style = "text-align:center;"><?= $booking_details_list['number_of_rooms'];?></td>
													<td style = "text-align:center;"><?php echo $desti_val['no_of_night'];?></td>
													<td style = "text-align:center;">
														<?php 
														$agent_commision=($hotel_val['price'] * $hotel_val['agent_markup_percentage'])/100;
														echo $booking_details_list['currency_code'].number_format($hotel_val['price']+$agent_commision, 2, ".", ",");
														$hotel_price=$hotel_price+($hotel_val['price']+$agent_commision);
														?>
													</td>
												</tr>
											<?php
												$each_hotel_html=ob_get_clean();
												$hotel_html.=$each_hotel_html;
											endif;
										else:
											$data['status'] = 'error';
											$data['msg'] = $return_data_arr_hotel['msg'];
										endif;
									endif;
								else:
									$data['status'] = 'error';
									$data['msg'] = $autentication_data->msg;
								endif;
							endforeach;
						endif;
						if(isset($desti_val['booking_tour_list']) && !empty($desti_val['booking_tour_list'])):
							foreach($desti_val['booking_tour_list'] as $tour_key=>$tour_val):
								$autentication_data_tour=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.TOUR_API_PATH."authorized.php"));
								if(isset($autentication_data_tour->status)):
									if($autentication_data_tour->status=="success"):
										$post_data_tour['token']=array(
											"token"=>$autentication_data_tour->results->token,
											"token_timeout"=>$autentication_data_tour->results->token_timeout,
											"token_generation_time"=>$autentication_data_tour->results->token_generation_time
										);
										$post_data_tour['data']['tour_id']=$tour_val['tour_id'];
										$post_data_tour['data']['offer_id']=$tour_val['offer_id'];
										$post_data_str_tour=json_encode($post_data_tour);
										$ch = curl_init();
										curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
										curl_setopt($ch, CURLOPT_HEADER, false);
										curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
										curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
										curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.TOUR_API_PATH."tour/find-booked-tour.php");
										curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_tour);
										curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
										$return_data_tour = curl_exec($ch);
										curl_close($ch);
										$return_data_arr_tour=json_decode($return_data_tour, true);
										//print_r($return_data_arr_tour);
										if(!isset($return_data_arr_tour['status'])):
											$data['status'] = 'error';
											$data['msg']="Some error has been occure during execution.";
										elseif($return_data_arr_tour['status']=="success"):
											if(isset($return_data_arr_tour['find_tour']) && isset($return_data_arr_tour['find_offer'])):
												$each_tour_price=$tour_val['price']+(($tour_val['price']*$tour_val['nationality_addon_percentage'])/100)+(($tour_val['price']*$tour_val['agent_markup_percentage'])/100);
												$tour_price=$tour_price+$each_tour_price;
												ob_start();
											?>
												<tr class="odd">
													<td style = "text-align:left;">
														<?php echo $return_data_arr_tour['find_tour']['tour_title'];?> - <?php echo $return_data_arr_tour['find_offer']['offer_title'];?> - <?php echo $return_data_arr_tour['find_offer']['service_type'];?> ( Capacity:  <?php echo $return_data_arr_tour['find_offer']['offer_capacity'];?> )
														<br/>
														Price: <?php echo $booking_details_list['currency_code'].number_format($each_tour_price, 2,".",",");?>
														<?php
														if($tour_val['pickup_time']!=""):
														?>
														<br/>
														Form: <?php echo date("h:i A", strtotime($tour_val['pickup_time'].":00"));?>
														<?php
														endif;
														if($tour_val['dropoff_time']!=""):
														?>
														<br/>
														To: <?php echo date("h:i A", strtotime($tour_val['dropoff_time'].":00"));?>
														<?php
														endif;
														?>
													</td>
												</tr>
											<?php
												$each_tour_html=ob_get_clean();
												$tour_html.=$each_tour_html;
											endif;
										else:
											$data['status'] = 'error';
											$data['msg'] = $return_data_arr_hotel['msg'];
										endif;
									endif;
								else:
									$data['status'] = 'error';
									$data['msg'] = $autentication_data->msg;
								endif;
							endforeach;
						endif;
						if(isset($desti_val['booking_transfer_list']) && !empty($desti_val['booking_transfer_list'])):
							foreach($desti_val['booking_transfer_list'] as $transfer_key=>$transfer_val):
								$autentication_data_transfer=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.TRANSFER_API_PATH."authorized.php"));
								if(isset($autentication_data_transfer->status)):
									if($autentication_data_transfer->status=="success"):
										$post_data_transfer['token']=array(
											"token"=>$autentication_data_transfer->results->token,
											"token_timeout"=>$autentication_data_transfer->results->token_timeout,
											"token_generation_time"=>$autentication_data_transfer->results->token_generation_time
										);
										$post_data_transfer['data']['transfer_id']=$transfer_val['transfer_id'];
										$post_data_transfer['data']['offer_id']=$transfer_val['offer_id'];
										$post_data_str_transfer=json_encode($post_data_transfer);
										$ch = curl_init();
										curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
										curl_setopt($ch, CURLOPT_HEADER, false);
										curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
										curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
										curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.TRANSFER_API_PATH."transfer/find-booked-transfer.php");
										curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_transfer);
										curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
										$return_data_transfer = curl_exec($ch);
										curl_close($ch);
										$return_data_arr_transfer=json_decode($return_data_transfer, true);
										if(!isset($return_data_arr_transfer['status'])):
											$data['status'] = 'error';
											$data['msg']="Some error has been occure during execution.";
										elseif($return_data_arr_transfer['status']=="success"):
											if(isset($return_data_arr_transfer['find_transfer']) && isset($return_data_arr_transfer['find_offer'])):
												$each_transfer_price=$transfer_val['price']+(($transfer_val['price']*$transfer_val['nationality_addon_percentage'])/100)+(($transfer_val['price']*$transfer_val['agent_markup_percentage'])/100);
												$transfer_price=$transfer_price+$each_transfer_price;
												ob_start();
											?>
												<tr class="odd">
													<td style = "text-align:left;">
														<?php echo $return_data_arr_transfer['find_transfer']['transfer_title'];?> - <?php echo $return_data_arr_transfer['find_offer']['offer_title'];?> - <?php echo $return_data_arr_transfer['find_offer']['service_type'];?> ( Capacity:  <?php echo $return_data_arr_transfer['find_offer']['offer_capacity'];?> )
														<br/>
														Price: <?php echo $booking_details_list['currency_code'].number_format($each_transfer_price, 2,".",",");?>
														<?php
														if($return_data_arr_transfer['find_transfer']['allow_pickup_type']!=""):
														?>
														<br/>
														Pick Up/Drop off Type: <?php echo $return_data_arr_transfer['find_transfer']['allow_pickup_type'];?>
														<?php
														endif;
														if($transfer_val['pickup_time']!=""):
														?>
														<br/>
														Form: <?php echo date("h:i A", strtotime($transfer_val['pickup_time'].":00"));?>
														<?php
														endif;
														if($transfer_val['dropoff_time']!=""):
														?>
														<br/>
														To: <?php echo date("h:i A", strtotime($transfer_val['dropoff_time'].":00"));?>
														<?php
														endif;
														if($transfer_val['airport']!=""):
														?>
														<br/>
														Airport: <?php echo $transfer_val['airport'];?>
														<?php
														endif;
														if($transfer_val['flight_number_name']!=""):
														?>
														<br/>
														Flight Number and Name: <?php echo $transfer_val['flight_number_name'];?>
														<?php
														endif;
														?>
													</td>
												</tr>
											<?php
												$each_transfer_html=ob_get_clean();
												$transfer_html.=$each_transfer_html;
											endif;
										else:
											$data['status'] = 'error';
											$data['msg'] = $return_data_arr_hotel['msg'];
										endif;
									endif;
								else:
									$data['status'] = 'error';
									$data['msg'] = $autentication_data->msg;
								endif;
							endforeach;
						endif;
					endforeach;
				endif;
				// HTML FOR PDF \\
				$html_header='
					<div style="width: 100%;">
						<div style="width: 100%; border-bottom: 3px double #54b9f0; border-top: 2px solid #54b9f0;">
							<div style="width:25%;float:left;">
								<img src="assets/img/logo_small.png" border="0" alt="" style="width: 150px;float:left;">
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
				ob_start();
?>
					<div style="width: 100%;">
						<div style="width: 100%;">
							<h3>Complete Your Booking</h3>
								<div class="box-body">
									<table style="width:100%; margin-bottom:15px; /*border:1px solid black; border-collapse: separate;" border="1" cellspacing="0">
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
												<td style="text-align:center;"><?= $number_of_person?></td>
												<td style="text-align:center;"><?= tools::module_date_format($booking_details_list['creation_date'],"Y-m-d H:i:s")?></td>
												<td style="text-align:center;"><?=$booking_details_list["booking_destination_list"][0]["ci_name"]?></td>
												<td style="text-align:center;"><?=tools::module_date_format($booking_details_list['checkin_date'],"Y-m-d")?></td>
											</tr>
										</tbody>
									</table>
								</div>
								<?php if(isset($hotel_html) && $hotel_html!=""):?>
								<div class="box-body">
									<table style="width:100%; margin-bottom:15px; /*border:1px solid black; border-collapse: separate;" border="1" cellspacing="0">
										<thead>
											<tr role="row">
												<th style="text-align:left;">Hotel</th>
												<th style="text-align:center;">Room Type</th>
												<th style="text-align:center;">Check In</th>
												<th style="text-align:center;">Check Out</th>
												<th style="text-align:center;">Rooms</th>
												<th style="text-align:center;">Nights</th>
												<th style="text-align:center;">Price</th>
											</tr>
										</thead>
										<tbody aria-relevant="all" aria-live="polite" role="alert">
										<?php echo $hotel_html;?>
										</tbody>
									</table>
								</div>
								<?php endif;
							if(isset($transfer_html) && $transfer_html!=""):
							?>
							<table style="width:100%; margin-bottom:15px; /*border:1px solid black; border-collapse: separate;" border="1" cellspacing="0">
								<thead>
									<tr role="row">
										<th style = "text-align:left;">Transfers</th>
									</tr>
								</thead>
								<tbody aria-relevant="all" aria-live="polite" role="alert">
									<?php echo $transfer_html;?>
								</tbody>
							</table>
							<?php
							endif;
							?>
							<?php
							if(isset($tour_html) && $tour_html!=""):
							?>
							<table style="width:100%; margin-bottom:15px; /*border:1px solid black; border-collapse: separate;" border="1" cellspacing="0">
								<thead>
									<tr role="row">
										<th style = "text-align:left;">Tour Sites</th>
									</tr>
								</thead>
								<tbody aria-relevant="all" aria-live="polite" role="alert">
									<?php echo $tour_html;?>
								</tbody>
							</table>
							<?php
							endif;
							?>
							<table style="width:100%; margin-bottom:15px; /*border:1px solid black; border-collapse: separate;" border="1" cellspacing="0">
								<thead>
									<tr role="row">
										<th style = "text-align:center;" colspan = "3">Quotation</th>
									</tr>
								</thead>
								<tbody aria-relevant="all" aria-live="polite" role="alert">
									<tr class="odd">
										<td style = "text-align:left;font-weight:bold;">Total Cost for Hotel Accommodation</td>
										<td style = "text-align:center;font-weight:bold;" colspan = "2"><?php echo $booking_details_list['currency_code'].' '.number_format($hotel_price, 2,".",",");?></td>
									</tr>
									<?php
									if($tour_price!=0.00 || $transfer_price!=0.00):
									?>
										<tr class="odd">
											<td style = "text-align:left;font-weight:bold;">
												Add-on : Cost for other components Tours & Transfer
											</td>
											<td style = "text-align:center;font-weight:bold;">
												PER ADULT
												<br/>
												<?php echo $booking_details_list['currency_code'].' '.number_format($tour_price+$transfer_price, 2,".",",");?>
											</td>
											<td style = "text-align:center;font-weight:bold;">
												PER CHILD
												<br/>
												<?php echo $booking_details_list['currency_code'].' '.number_format(0, 2,".",",");?>
											</td>
										</tr>
									<?php
									endif;
									?>
									<tr class="odd">
										<td style = "text-align:left;font-weight:bold;">
											No of Guests
										</td>
										<td style = "text-align:center;font-weight:bold;">
											ADULT
											<br/>
											<?php echo $number_of_adult;?>
										</td>
										<td style = "text-align:center;font-weight:bold;">
											CHILD
											<br/>
											<?php echo $number_of_child;?>
										</td>
									</tr>
									<tr class="odd">
										<td style = "text-align:left;font-weight:bold;">Total Quantity</td>
										<td style = "text-align:center;font-weight:bold;color:red;" colspan = "2">
											<?php echo $booking_details_list['currency_code'].' '.number_format($hotel_price+(($tour_price+$transfer_price)*$number_of_adult), 2,".",",");?>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				<?php
				$html_body=ob_get_clean();
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