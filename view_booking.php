<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['AGENT_SESSION_DATA']['id'], DOMAIN_NAME_PATH.'');
	if(isset($_GET['booking_id']) && $_GET['booking_id']!=""):
		$autentication_data_booking=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.BOOKING_API_PATH."authorized.php"));
		if(isset($autentication_data_booking->status)):
			if($autentication_data_booking->status=="success"):
				$post_data_booking['token']=array(
					"token"=>$autentication_data_booking->results->token,
					"token_timeout"=>$autentication_data_booking->results->token_timeout,
					"token_generation_time"=>$autentication_data_booking->results->token_generation_time
				);
				if(isset($_GET['sub_agent_id']) && $_GET['sub_agent_id']!=""):
					$sub_agent_data = tools::find("first", TM_AGENT, "*", "WHERE id=:id AND parent_id=:parent_id", array(':id'=>base64_decode($_GET['sub_agent_id']), ':parent_id'=>$_SESSION['AGENT_SESSION_DATA']['id']));
					if(!empty($sub_agent_data)):
						$post_data_booking['data']['agent_id']=base64_decode($_GET['sub_agent_id']);
					else:
						$_SESSION['SET_TYPE'] = 'error';
						$_SESSION['SET_FLASH']="Invalid sub agent.";
						header("location:".DOMAIN_NAME_PATH."booking.php");
						exit;
					endif;
				else:
					$post_data_booking['data']['agent_id']=$_SESSION['AGENT_SESSION_DATA']['id'];
				endif;
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
				//print_r($return_data_booking);
				$return_data_arr_booking=json_decode($return_data_booking, true);
				if(!isset($return_data_arr_booking['status'])):
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH']="Some error has been occure during execution.";
				elseif($return_data_arr_booking['status']=="success"):
					$booking_details_list=$return_data_arr_booking['results'][0];
				else:
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH'] = $return_data_arr_booking['msg'];
				endif;
			endif;
		else:
			$_SESSION['SET_TYPE'] = 'error';
			$_SESSION['SET_FLASH'] = $autentication_data->msg;
		endif;
	else:
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = 'Some data missing.';
		header("location:booking.php".(isset($_GET['sub_agent_id']) && $_GET['sub_agent_id']!="" ? "?sub_agent_id=".$_GET['sub_agent_id'] : ""));
		exit;
	endif;
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo DEFAULT_PAGE_TITLE ;?> Destination Management Company</title>	
		<?php require_once('meta.php');?>
	</head>
	<body class="index-page">
		<!-- TOP HEADER -->
		<?php require_once('header.php');?>
		<!-- TOP HEADER -->
		<div class="main-cont">
			<div class="body-padding">
				<div class="banner_slider" style="background:url(img/banner4.jpg)no-repeat center center/cover;">
					<div class="banner_slider_text">
						View Booking
					</div>
				</div>
				<?php require_once('login_menu.php');?>
				<div id="" class="container">
					<div class="mp-offesr">
						<div class="wrapper-padding-a">
							<div class="offer-slider">
								<div class="row rows">
									<div class="col-md-12">
										<div class="box box-info">
											<div class="box-body">
												<div class="box-body no-padding">
													<?php
													if(isset($booking_details_list) && !empty($booking_details_list)):
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
														$checkin_date = strtotime($booking_details_list['checkin_date']);
														$checkout_date = strtotime($booking_details_list['checkout_date']);
														$datediff = $checkout_date - $checkin_date;
														$destination_str="";
														$service_arr=array("Hotel");
														foreach($booking_details_list['booking_destination_list'] as $dest_key=>$dest_val):
															if($destination_str!="")
																$destination_str.=", ";
															$destination_str.=$dest_val['ci_name'];
															if(isset($dest_val['booking_tour_list']) && !empty($dest_val['booking_tour_list']) && !in_array("Tour", $service_arr))
																array_push($service_arr, "Tour");
															if(isset($dest_val['booking_transfer_list']) && !empty($dest_val['booking_transfer_list']) && !in_array("Transfer", $service_arr))
																array_push($service_arr, "Transfer");
														endforeach;
													?>
													<table aria-describedby="example1_info" id="example" class="table table-bordered table-striped dataTable">
														<thead>
															<tr role="row">
																<th style = "text-align:center;">Pax</th>
																<th style = "text-align:center;">Quote Date</th>
																<th style = "text-align:center;">Destination</th>
																<th style = "text-align:center;">Booking Date</th>
																<th style = "text-align:center;">Payment Type</th>
																<th style = "text-align:center;">Payment Status</th>
																<th style = "text-align:center;">Payment Date</th>
																<th style = "text-align:center;">Approval</th>
															</tr>
														</thead>
														<tbody aria-relevant="all" aria-live="polite" role="alert">
															<tr class="odd">
																<td style = "text-align:center;"><?php echo $number_of_person;?></td>
																<td style = "text-align:center;"><?php echo tools::module_date_format($booking_details_list['creation_date'], "Y-m-d H:i:s");?></td>
																<td style = "text-align:center;"><?php echo $destination_str;?></td>
																<td style = "text-align:center;"><?php echo tools::module_date_format($booking_details_list['checkin_date'])." - ".tools::module_date_format($booking_details_list['checkout_date']);?></td>
																<td style = "text-align:center;"><?php echo $booking_details_list['payment_type'];?></td>
																<td style = "text-align:center;">
																	<?php
																	if(isset($booking_details_list['payment_status']) && $booking_details_list['payment_status']=="U")
																	{
																	?>
																	<span style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" class="btn-warning">Unpaid</span>
																	<?php
																	}
																	elseif(isset($booking_details_list['payment_status']) && $booking_details_list['payment_status']=="P")
																	{
																	?>
																	<span style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" class="btn-success">Paid</span>
																	<?php
																	}
																	else
																	{
																		echo "N/A";
																	}
																	?>
																</td>
																<td style = "text-align:center;"><?php echo($booking_details_list['payment_date']!="" ? tools::module_date_format($booking_details_list['payment_date'], "Y-m-d H:i:s") : "N/A");?></td>
																<td style = "text-align:center;">
																	<?php
																	if(isset($booking_details_list['status']) && isset($booking_details_list['status']) && $booking_details_list['status']==0)
																	{
																	?>
																	<span style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" class="btn-warning">Pending</span>
																	<?php
																	}
																	elseif(isset($booking_details_list) && isset($booking_details_list['status']) && $booking_details_list['status']==1)
																	{
																	?>
																	<span style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" class="btn-success">Accepted</span>
																	<?php
																	}
																	elseif(isset($booking_details_list) && isset($booking_details_list['status']) && $booking_details_list['status']==2)
																	{
																	?>
																	<span style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" class="btn-danger">Rejected</span>
																	<?php
																	}
																	?>
																</td>
															</tr>
														</tbody>
													</table>
													<?php
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
																						<td style = "text-align:center;"><?php echo $desti_val['no_of_night'];?></td><td style = "text-align:center;">
																							<?php 
																							$agent_commision=($hotel_val['price'] * $hotel_val['agent_markup_percentage'])/100;
																							echo $booking_details_list['currency_code'].number_format($hotel_val['price']+$agent_commision, 2, ".", ",");
																							$hotel_price=$hotel_price+($hotel_val['price']+$agent_commision);
																							?>
																						</td>
																						<td style = "text-align:center;">
																							<?php
																							if($hotel_val['status']==0):
																								echo '<span style="padding: 3px;" class="btn-warning">Pending</span>';
																							elseif($hotel_val['status']==1):
																								echo '<span style="padding: 3px;" class="btn-success">Action</span>';
																							elseif($hotel_val['status']==2):
																								echo '<span style="padding: 3px;" class="btn-danger">Reject</span>';
																							else:
																								echo 'N/A';
																							endif;
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
																				?>
																					<tr class="odd">
																						<td style = "text-align:left;padding-bottom: 0;" colspan="100%">
																							<h4 style="margin: 0;"><?php echo tools::module_date_format($tour_val['booking_start_date']);?></h4>
																						</td>
																					</tr>
																				<?php
																					endif;
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
																							From: <?php echo date("h:i A", strtotime($tour_val['pickup_time'].":00"));?>
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
																				?>
																					<tr class="odd">
																						<td style = "text-align:left;padding-bottom: 0;" colspan="100%">
																							<h4 style="margin: 0;"><?php echo tools::module_date_format($transfer_val['booking_start_date']);?></h4>
																						</td>
																					</tr>
																				<?php
																					endif;
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
																							From: <?php echo date("h:i A", strtotime($transfer_val['pickup_time'].":00"));?>
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
													?>
													<?php
													if(isset($hotel_html) && $hotel_html!=""):
													?>
													<table aria-describedby="example1_info" id="example" class="table table-bordered table-striped dataTable">
														<thead>
															<tr role="row">
																<th style = "text-align:left;">Hotel</th>
																<th style = "text-align:center;">Room Type</th>
																<th style = "text-align:center;">Check In</th>
																<th style = "text-align:center;">Check Out</th>
																<th style = "text-align:center;">Rooms</th>
																<th style = "text-align:center;">Nights</th>
																<th style = "text-align:center;">Price Per Night</th>
																<th style = "text-align:center;">Approval Status</th>
															</tr>
														</thead>
														<tbody aria-relevant="all" aria-live="polite" role="alert">
															<?php echo $hotel_html;?>
														</tbody>
													</table>
													<?php
													endif;
													?>
													<?php
													if(((isset($tour_html) && $tour_html!="") || (isset($transfer_html) && $transfer_html!="")) && isset($booking_details_list['booking_supplier_list']) && !empty($booking_details_list['booking_supplier_list'])):
														$is_rejected=false;
														$not_in_supplier_ids=array();
													?>
													<table aria-describedby="example1_info" id="example" class="table table-bordered table-striped dataTable">
														<thead>
															<tr role="row">
																<th>Supplier Name</th>
																<th>Company Name</th>
																<th>Email</th>
																<th>Approval Status</th>
																<th>Modification Date</th>
															</tr>
														</thead>
														<tbody aria-relevant="all" aria-live="polite" role="alert">
													<?php
														foreach($booking_details_list['booking_supplier_list'] as $supplier_key=>$supplier_val):
															$is_rejected=false;
															$autentication_data_supplier=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.SUPPLIER_API_PATH."authorized.php"));
															if(isset($autentication_data_supplier->status)):
																if($autentication_data_supplier->status=="success"):
																	$post_data_supplier['token']=array(
																		"token"=>$autentication_data_supplier->results->token,
																		"token_timeout"=>$autentication_data_supplier->results->token_timeout,
																		"token_generation_time"=>$autentication_data_supplier->results->token_generation_time
																	);
																	$post_data_supplier['data']['supplier_id']=base64_encode($supplier_val['supplier_id']);
																	$post_data_str_supplier=json_encode($post_data_supplier);
																	$ch = curl_init();
																	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
																	curl_setopt($ch, CURLOPT_HEADER, false);
																	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
																	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
																	curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.SUPPLIER_API_PATH."supplier/read.php");
																	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_supplier);
																	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
																	$return_data_supplier = curl_exec($ch);
																	curl_close($ch);
																	$return_data_arr_supplier=json_decode($return_data_supplier, true);
																	if(!isset($return_data_arr_supplier['status'])):
																		//$data['status'] = 'error';
																		//$data['msg']="Some error has been occure during execution.";
																	elseif($return_data_arr_supplier['status']=="success"):
																		$supplier_return_data=$return_data_arr_supplier['results'];
																	else:
																		//$data['status'] = 'error';
																		//$data['msg'] = $return_data_arr_supplier['msg'];
																	endif;
																endif;
															else:
																//$data['status'] = 'error';
																//$data['msg'] = $autentication_data_supplier->msg;
															endif;
													?>
															<tr>
																<td><?php echo $supplier_return_data['first_name']." ".$supplier_return_data['last_name'];?></td>
																<td><?php echo $supplier_return_data['company_name'];?></td>
																<td><?php echo $supplier_return_data['email_address'];?></td>
																<td>
																	<?php
																	if($supplier_val['status']==0):
																		echo '<span style="padding: 3px;" class="btn-warning">Pending</span>';
																	elseif($supplier_val['status']==1):
																		echo '<span style="padding: 3px;" class="btn-success">Acccepted</span>';
																	elseif($supplier_val['status']==2):
																		echo '<span style="padding: 3px;" class="btn-danger">Rejected</span>';
																	else:
																		echo 'N/A';
																	endif;
																	?>
																</td>
																<td><?php echo tools::module_date_format($supplier_val['last_updated'], "Y-m-d H:i:s");?></td>
															</tr>
													<?php
															array_push($not_in_supplier_ids, $supplier_return_data['id']);
															if($supplier_val['status']==2):
																$is_rejected=true;
															endif;
														endforeach;
													?>
														</tbody>
													</table>
													<?php
													endif;
													?>
													<?php
													if(isset($transfer_html) && $transfer_html!=""):
													?>
													<table aria-describedby="example1_info" id="example" class="table table-bordered table-striped dataTable">
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
													<table aria-describedby="example1_info" id="example" class="table table-bordered table-striped dataTable">
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
													<table aria-describedby="example1_info" id="example" class="table table-bordered table-striped dataTable">
														<thead>
															<tr role="row">
																<th style = "text-align:center;" colspan = "3">Quotation</th>
															</tr>
														</thead>
														<tbody aria-relevant="all" aria-live="polite" role="alert">
															<tr class="odd">
																<td style = "text-align:left;font-weight:bold;">Total Cost for Hotel Accommodation</td>
																<td style = "text-align:center;font-weight:bold;" colspan = "2"><?php echo $booking_details_list['currency_code'].number_format($hotel_price, 2,".",",");?></td>
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
																		<?php echo $booking_details_list['currency_code'].number_format($tour_price+$transfer_price, 2,".",",");?>
																	</td>
																	<td style = "text-align:center;font-weight:bold;">
																		PER CHILD
																		<br/>
																		<?php echo $booking_details_list['currency_code'].number_format(0, 2,".",",");?>
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
																	<?php echo $booking_details_list['currency_code'].number_format($hotel_price+(($tour_price+$transfer_price)*$number_of_adult), 2,".",",");?>
																</td>
															</tr>
														</tbody>
													</table>
													<?php
													else:
													?>
													<div class="box box-primary text-center">
														<div style="padding:20px;">
															No record found
														</div>
													</div>
													<?php
													endif;
													?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- FOOTER -->
		<?php require_once('footer.php');?>
		<!-- FOOTER -->
	</body>
</html>