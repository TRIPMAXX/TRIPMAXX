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
				$hotel_html=array();
				$hotel_number=$transfer_day=$tour_day=0;
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
											<div style="width: 100%;">
												<h3 style="background:#93302c; text-align:center; color:white; font-size: 20px;">ACCOMMODATION VOUCHER</h3>
												<h4 style="background:#a5a5a5;font-size: 16px;margin: -10px auto 8px;padding: 0px 10px;color:white;">HOTEL INFORMATION</h4>
												<div class="box-body" style="margin:0 15px 15px;"  >
													<table style="width:100%; border-collapse: separate; font-size:12px;" cellspacing="0">
														<tbody aria-relevant="all" aria-live="polite" role="alert">
															<tr class="odd">
																<td style="width:150px;"><strong>Hotel Name </strong></td>
																<td style = "text-align:left;"><?php echo $return_data_arr_hotel['find_hotel']['hotel_name'];?></td>
															</tr>
															<tr class="odd">
																<td><strong>Hotel Address</strong></td>
																<td style = "text-align:left;"><?php echo $return_data_arr_hotel['find_hotel']['hotel_address'];?></td>
															</tr>
															<tr class="odd">
																<td><strong>Tel</strong></td>
																<td style = "text-align:left;"><?php echo $return_data_arr_hotel['find_hotel']['phone_number']!=""?$return_data_arr_hotel['find_hotel']['phone_number']:"n/a";?></td>
															</tr>
														</tbody>
													</table>
												</div>
												<h4 style="background:#a5a5a5;font-size: 16px;margin: -10px auto 8px;padding: 0px 10px;color:white;">BOOKING DETAILS </h4>
												<div class="box-body" style="margin:0 15px 15px;" >
													<table style="width:100%; border-collapse: separate; font-size:12px;" cellspacing="0">
														<tbody aria-relevant="all" aria-live="polite" role="alert">
															<tr class="odd">
																<td style="width:150px;"><strong>Room Type</strong></td>
																<td style = "text-align:left;"><?= $return_data_arr_hotel['find_room']['room_type'];?>
																<br/>
																<font color="red"><?= $return_data_arr_hotel['find_room']['room_description'];?></font>
																</td>
															</tr>
															<tr class="odd">
																<td><strong>Check In</strong></td>
																<td style = "text-align:left;"><?php echo tools::module_date_format($hotel_val['booking_start_date'], "Y-m-d");?></td>
															</tr>
															<tr class="odd">
																<td><strong>Check Out</strong></td>
																<td style = "text-align:left;"><?php echo tools::module_date_format($hotel_val['booking_end_date'], "Y-m-d");?></td>
															</tr>
															<tr class="odd">
																<td><strong>Number of Night(s)</strong></td>
																<td style = "text-align:left;"><?=$desti_val['no_of_night'];?></td>
															</tr>
															<tr class="odd">
																<td><strong>Number of Rooms(s)</strong></td>
																<td style = "text-align:left;"><?= $booking_details_list['number_of_rooms'];?></td>
															</tr>
															<tr class="odd">
																<td><strong>Number of Adult(s)</strong></td>
																<td style = "text-align:left;"><?=$number_of_adult;?></td>
															</tr>
															<tr class="odd">
																<td><strong>Number of Child(ren)</strong></td>
																<td style = "text-align:left;"><?=$number_of_child>0?$number_of_child:'-';?></td>
															</tr>
														</tbody>
													</table>
												</div>
												<h4 style="font-size: 16px;margin-bottom: 5px"><em>IMPORTANT NOTE TO HOTEL : </em></h4>
												<p style="margin:0 15px 15px;" >This is a prepaid booking. Please do not collect any payment from the guest. Please contact our customer service center at <u><em>+91 33 4032 8888</em></u> if you have any question or doubt.</p>
												<h4 style="font-size: 16px;margin-bottom: 5px;">Remark :</h4>
												<p style="margin:0 15px 15px;" >TRIPMAXX<br> Checkin Time:- <?=$return_data_arr_hotel['find_hotel']['checkin_time']?> hrs</p>
											</div>
											<?php
												$each_hotel_html=ob_get_clean();
												$hotel_html[$hotel_number]=$each_hotel_html;
												$hotel_number++;
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
							$prev_booking_date="";
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
												if($prev_booking_date=="" || $prev_booking_date!=$tour_val['booking_start_date']):
													$prev_booking_date=$tour_val['booking_start_date'];
													$tour_day++;
											?>
											<h4 style="background:#a5a5a5;font-size: 16px;margin: -10px auto 8px;padding: 0px 10px;color:white;">DAY <?php echo $tour_day.' - '.tools::module_date_format($tour_val['booking_start_date']);?></h4>
											<?php
												endif;
											?>
											<div class="box-body" style="margin:0 15px 15px;"  >
												<table style="width:100%; border-collapse: separate; font-size:12px;" cellspacing="0">
													<tbody aria-relevant="all" aria-live="polite" role="alert">
														<tr class="odd">
															<td style = "text-align:left;font-weight:bold;width:200px;">Title</td>
															<td style = "text-align:left;"><?php echo $return_data_arr_tour['find_tour']['tour_title'];?></td>
														</tr>
														<tr class="odd">
															<td style = "text-align:left;font-weight:bold">Offer Title</td>
															<td style = "text-align:left;"><?php echo $return_data_arr_tour['find_offer']['offer_title'];?></td>
														</tr>
														<tr class="odd">
															<td style = "text-align:left;font-weight:bold">Service Type</td>
															<td style = "text-align:left;"><?php echo $return_data_arr_tour['find_offer']['service_type'];?></td>
														</tr>
														<tr class="odd">
															<td style = "text-align:left;font-weight:bold">Capacity</td>
															<td style = "text-align:left;"><?php echo $return_data_arr_tour['find_offer']['offer_capacity'];?></td>
														</tr>
														<?php 
														if($tour_val['pickup_time']!=""):
														?>
														<tr class="odd">
															<td style = "text-align:left;font-weight:bold">Form:</td>
															<td style = "text-align:left;"><?php echo date("h:i A", strtotime($tour_val['pickup_time'].":00"));?></td>
														</tr>
														<?php 
														endif;
														if($tour_val['dropoff_time']!=""):
														?>
														<tr class="odd">
															<td style = "text-align:left;font-weight:bold">Form:</td>
															<td style = "text-align:left;"><?php echo date("h:i A", strtotime($tour_val['dropoff_time'].":00"));?></td>
														</tr>
														<?php 
														endif;
														?>
													</tbody>
												</table>
											</div>
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
							$prev_booking_date="";
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
												
												if($prev_booking_date=="" || $prev_booking_date!=$transfer_val['booking_start_date']):
													$prev_booking_date=$transfer_val['booking_start_date'];
													$transfer_day++;
											?>
											<h4 style="background:#a5a5a5;font-size: 16px;margin: -10px auto 8px;padding: 0px 10px;color:white;">DAY <?php echo $transfer_day.' - '.tools::module_date_format($transfer_val['booking_start_date']);?></h4>
											<?php
												endif;
											?>
											<div class="box-body" style="margin:0 15px 15px;"  >
												<table style="width:100%; border-collapse: separate; font-size:12px;" cellspacing="0">
													<tbody aria-relevant="all" aria-live="polite" role="alert">
														<tr class="odd">
															<td style = "text-align:left;font-weight:bold;width:200px;">Title</td>
															<td style = "text-align:left;"><?php echo $return_data_arr_transfer['find_transfer']['transfer_title'];?></td>
														</tr>
														<tr class="odd">
															<td style = "text-align:left;font-weight:bold">Offer Title</td>
															<td style = "text-align:left;"><?php echo $return_data_arr_transfer['find_offer']['offer_title'];?></td>
														</tr>
														<tr class="odd">
															<td style = "text-align:left;font-weight:bold">Service Type</td>
															<td style = "text-align:left;"><?php echo $return_data_arr_transfer['find_offer']['service_type'];?></td>
														</tr>
														<tr class="odd">
															<td style = "text-align:left;font-weight:bold">Capacity</td>
															<td style = "text-align:left;"><?php echo $return_data_arr_transfer['find_offer']['offer_capacity'];?></td>
														</tr>
														<tr class="odd">
															<td style = "text-align:left;font-weight:bold">Pick Up/Drop off Type:</td>
															<td style = "text-align:left;"><?php echo ($return_data_arr_transfer['find_transfer']['allow_pickup_type']!=""?$return_data_arr_transfer['find_transfer']['allow_pickup_type']:"-");?></td>
														</tr>
														<?php 
														if($transfer_val['pickup_time']!=""):
														?>
														<tr class="odd">
															<td style = "text-align:left;font-weight:bold">Form:</td>
															<td style = "text-align:left;"><?php echo date("h:i A", strtotime($transfer_val['pickup_time'].":00"));?></td>
														</tr>
														<?php 
														endif;
														if($transfer_val['dropoff_time']!=""):
														?>
														<tr class="odd">
															<td style = "text-align:left;font-weight:bold">Form:</td>
															<td style = "text-align:left;"><?php echo date("h:i A", strtotime($transfer_val['dropoff_time'].":00"));?></td>
														</tr>
														<?php
														endif;
														if($transfer_val['airport']!=""):
														?>
														<tr class="odd">
															<td style = "text-align:left;font-weight:bold">Airport:</td>
															<td style = "text-align:left;"><?php echo $transfer_val['airport'];?></td>
														</tr>
														<?php
														endif;
														if($transfer_val['flight_number_name']!=""):
														?>
														<tr class="odd">
															<td style = "text-align:left;font-weight:bold">Flight Number and Name:</td>
															<td style = "text-align:left;"><?php echo $transfer_val['flight_number_name'];?></td>
														</tr>
														<?php
														endif;
														?>
													</tbody>
												</table>
											</div>
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
				$general_setting = tools::find("first", TM_SETTINGS, '*', "WHERE id=:id ", array(":id"=>1));
				ob_start();
				?>
					<div style="width: 100%;">
						<div style="width: 100%; border-bottom: 3px double #54b9f0; border-top: 2px solid #54b9f0;">
							<div style="width:25%;float:left;">
							<?php
							if($general_setting['website_logo']!="" && file_exists(GENERAL_IMAGES.$general_setting['website_logo'])):
							?>
								<img src="<?php echo DOMAIN_NAME_PATH_ADMIN.GENERAL_IMAGES.$general_setting['website_logo'];?>"  border="0" alt="" style="width: 150px;float:left;"/>
							<?php
							else:
							?>
								<img src="assets/img/logo_small.png" border="0" alt="" style="width: 150px;float:left;">
							<?php
							endif;
							?>
							</div>
							<div style="width:75%;float:left;">
								<table style="width: 100%;">
									<tr>
										<td colspan="100%" style="font-size:20px;"><img src="assets/img/location-pin-512.png" border="0" alt="" style="width: 14px;"> <?=$general_setting['contact_address']?></td>
									</tr>
									<tr>
										<td style="font-size:20px;width: 350px;"><img src="assets/img/Mail_email_envelope_letter.png" border="0" alt="" style="width: 14px;margin-top:7px;"> <?=$general_setting['contact_email_address']?></td>
										<td style="font-size:20px;width: 200px;">&nbsp;&nbsp;&nbsp;<img src="assets/img/phone.png" border="0" alt="" style="width: 14px;margin-top:3px;">  <?=$general_setting['contact_phone_number']?></td>
									</tr>
								</table>
							</div>
							<div style="clear:both;"></div>
						</div>
					</div>
				<?php
				$html_header=ob_get_clean();
				$html_footer='
					<div style="width: 100%;">
						<div style="width: 100%; border-top: 3px double #54b9f0; border-bottom: 2px solid #54b9f0;">
							<h6 style="margin:5px 0">&copy; COPYRIGHT 2017 TRIPMAXX. ALL RIGHTS RESERVED</h6>
						</div>
					</div>';
				$tour_html_body='
					<div style="width: 100%;">
						<h3 style="background:#93302c; text-align:center; color:white; font-size: 20px;">TOUR VOUCHER</h3>
				';
				$tour_html_body.=$tour_html;
				$tour_html_body.='
					</div>
				';
				$transfer_html_body='
					<div style="width: 100%;">
						<h3 style="background:#93302c; text-align:center; color:white; font-size: 20px;">TRANSFER VOUCHER</h3>
				';
				$transfer_html_body.=$transfer_html;
				$transfer_html_body.='
					</div>
				';
				/*print $html_header;
				print $html_footer;
				exit;*/

				include("assets/mpdf/mpdf.php");
				$mpdf=new mPDF('c','A4','','','15','15','28','18'); 
				$mpdf->SetHeader($html_header);
				$mpdf->SetFooter($html_footer);
				if(isset($hotel_html) && !empty($hotel_html)):
					foreach($hotel_html as $h_key => $html_body):
						if($h_key>0):
							$mpdf->AddPage('','','1','i','on');
						endif;
						$mpdf->WriteHTML($html_body);
					endforeach;
				endif;

				$mpdf->AddPage('','','1','i','on');
				$mpdf->WriteHTML($transfer_html_body);

				$mpdf->AddPage('','','1','i','on');
				$mpdf->WriteHTML($tour_html_body);

				$mpdf->Output();
				$mpdf->Output('booking_voucher.pdf','D');

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