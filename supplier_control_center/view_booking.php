<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA_SUPPLIER']['id'], DOMAIN_NAME_PATH_SUPPLIER.'login');
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
				$post_data_booking['data']['supplier_id']=$_SESSION['SESSION_DATA_SUPPLIER']['id'];
				$post_data_str_booking=json_encode($post_data_booking);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.BOOKING_API_PATH."booking/supplier-data.php");
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
				else:
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH'] = $return_data_arr_booking['msg'];
				endif;
				if(isset($_POST) && !empty($_POST))
				{
					$post_data_booking['data']['status']=$_POST['booking_supplier_approval_status'];
					$post_data_booking['data']['id']=$booking_details_list['booking_supplier_approval_status']['id'];
					$post_data_str_booking=json_encode($post_data_booking);
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_HEADER, false);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
					curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.BOOKING_API_PATH."booking/update-booking-supplier.php");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_booking);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					$return_data_booking = curl_exec($ch);
					curl_close($ch);
					$return_data_arr_booking_update=json_decode($return_data_booking, true);
					if(!isset($return_data_arr_booking_update['status'])):
						$_SESSION['SET_TYPE'] = 'error';
						$_SESSION['SET_FLASH']="Some error has been occure during execution.";
					elseif($return_data_arr_booking_update['status']=="success"):
						$autentication_data_dmc=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.DMC_API_PATH."authorized.php"));
						if(isset($autentication_data_dmc->status)):
							if($autentication_data_dmc->status=="success"):
								$post_data_dmc['token']=array(
									"token"=>$autentication_data_dmc->results->token,
									"token_timeout"=>$autentication_data_dmc->results->token_timeout,
									"token_generation_time"=>$autentication_data_dmc->results->token_generation_time
								);
								$post_data_dmc['data']['booking_details_list']=$booking_details_list;
								$post_data_dmc['data']['email_template_id']=15;
								$post_data_str_dmc=json_encode($post_data_dmc);
								$ch = curl_init();
								curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
								curl_setopt($ch, CURLOPT_HEADER, false);
								curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
								curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
								curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.DMC_API_PATH."dmc/send-email-update.php");
								curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_dmc);
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
								$return_data_dmc = curl_exec($ch);
								curl_close($ch);
								$return_data_arr_dmc=json_decode($return_data_dmc, true);
								if(!isset($return_data_arr_dmc['status'])):
									//$_SESSION['SET_TYPE'] = 'error';
									//$_SESSION['SET_FLASH']="Some error has been occure during execution.";
								elseif($return_data_arr_dmc['status']=="success"):
									//$booking_details_list=$return_data_arr_dmc['results'][0];
								else:
									//$_SESSION['SET_TYPE'] = 'error';
									//$_SESSION['SET_FLASH'] = $return_data_arr_dmc['msg'];
								endif;
							endif;
						endif;
						if($booking_details_list['booking_type']=="agent"):
							$autentication_data_agent=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."authorized.php"));
							if(isset($autentication_data_agent->status)):
								if($autentication_data_agent->status=="success"):
									$post_data_agent['token']=array(
										"token"=>$autentication_data_agent->results->token,
										"token_timeout"=>$autentication_data_agent->results->token_timeout,
										"token_generation_time"=>$autentication_data_agent->results->token_generation_time
									);
									$post_data_agent['data']['booking_details_list']=$booking_details_list;
									$post_data_agent['data']['email_template_id']=15;
									$post_data_str_agent=json_encode($post_data_agent);
									$ch = curl_init();
									curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
									curl_setopt($ch, CURLOPT_HEADER, false);
									curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
									curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
									curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."agent/send-email-update.php");
									curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_agent);
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
									$return_data_agent = curl_exec($ch);
									curl_close($ch);
									//print_r($return_data_agent);exit;
									$return_data_arr_agent=json_decode($return_data_agent, true);
									if(!isset($return_data_arr_agent['status'])):
										//$_SESSION['SET_TYPE'] = 'error';
										//$_SESSION['SET_FLASH']="Some error has been occure during execution.";
									elseif($return_data_arr_agent['status']=="success"):
										//$booking_details_list=$return_data_arr_agent['results'][0];
									else:
										//$_SESSION['SET_TYPE'] = 'error';
										//$_SESSION['SET_FLASH'] = $return_data_arr_agent['msg'];
									endif;
								endif;
							endif;
						endif;
						$_SESSION['SET_TYPE'] = 'success';
						$_SESSION['SET_FLASH']=$return_data_arr_booking_update['msg'];
						header("location:".DOMAIN_NAME_PATH_SUPPLIER.'view_booking?booking_id='.$_GET['booking_id']);
						exit;
					else:
						$_SESSION['SET_TYPE'] = 'error';
						$_SESSION['SET_FLASH'] = $return_data_arr_booking_update['msg'];
					endif;
				}
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
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER_SUPPLIER);?>VIEW BOOKING</title>
	<?php require_once(SUPPLIER_CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<script type="text/javascript">
	<!--
		$(function(){
			$("#booking_supplier_approval_status").change(function(){
				$("#booking_supplier_approval_status_form").submit();
			});
		});
	//-->
	</script>
</head>
<body class="skin-purple">
	<div class="wrapper">
		<!-- TOP HEADER -->
		<?php require_once(SUPPLIER_CONTROL_CENTER_COMMON_FILE_PATH.'header.php');?>		
		<!-- TOP HEADER -->

		<!-- LEFT MENU -->
		<?php require_once(SUPPLIER_CONTROL_CENTER_COMMON_FILE_PATH.'menu.php');?>
		<!-- LEFT MENU -->
		
		<!-- BODY -->
		<div class="content-wrapper">
			<section class="content-header">
				<h1>View Booking</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo(DOMAIN_NAME_PATH_SUPPLIER);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">View Booking</li>
				</ol>
			</section>
			<section class="content">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
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
						<div class="box box-primary">
							<table aria-describedby="example1_info" id="example" class="table table-bordered table-striped dataTable">
								<thead>
									<tr role="row">
										<th style = "text-align:center;">Pax</th>
										<th style = "text-align:center;">Quote Date</th>
										<th style = "text-align:center;">Destination</th>
										<th style = "text-align:center;">Booking Date</th>
										<th style = "text-align:center;">Approval</th>
									</tr>
								</thead>
								<tbody aria-relevant="all" aria-live="polite" role="alert">
									<tr class="odd">
										<td style = "text-align:center;"><?php echo $number_of_person;?></td>
										<td style = "text-align:center;"><?php echo tools::module_date_format($booking_details_list['creation_date'], "Y-m-d H:i:s");?></td>
										<td style = "text-align:center;"><?php echo $destination_str;?></td>
										<td style = "text-align:center;"><?php echo tools::module_date_format($booking_details_list['checkin_date'])." - ".tools::module_date_format($booking_details_list['checkout_date']);?></td>
										<td style = "text-align:center;">
											<?php
											if(isset($booking_details_list['booking_supplier_approval_status']) && isset($booking_details_list['booking_supplier_approval_status']['status']) && $booking_details_list['booking_supplier_approval_status']['status']==0)
											{
											?>
											<form method="post" name="booking_supplier_approval_status_form" id="booking_supplier_approval_status_form" action="">
												<select name="booking_supplier_approval_status" id="booking_supplier_approval_status" class="btn-warning">
													<option value="0" class="btn-warning">Pending</option>
													<option value="1" class="btn-success">Accept</option>
													<option value="2" class="btn-danger">Reject</option>
												</select>
											</form>
											<?php
											}
											elseif(isset($booking_details_list['booking_supplier_approval_status']) && isset($booking_details_list['booking_supplier_approval_status']['status']) && $booking_details_list['booking_supplier_approval_status']['status']==1)
											{
											?>
											<span style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" class="btn-success">Accepted</span>
											<?php
											}
											elseif(isset($booking_details_list['booking_supplier_approval_status']) && isset($booking_details_list['booking_supplier_approval_status']['status']) && $booking_details_list['booking_supplier_approval_status']['status']==2)
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
															$tour_price=$tour_price+$tour_val['price']+$tour_val['nationality_addon_percentage']+$tour_val['agent_markup_percentage'];
															ob_start();
														?>
															<tr class="odd">
																<td style = "text-align:left;"><?php echo $return_data_arr_tour['find_tour']['tour_title'];?> - <?php echo $return_data_arr_tour['find_tour']['tour_service'];?> - <?php echo $return_data_arr_tour['find_offer']['offer_title'];?> ( Capacity:  <?php echo $return_data_arr_tour['find_offer']['offer_capacity'];?> )</td>
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
															$transfer_price=$transfer_price+$transfer_val['price']+$transfer_val['nationality_addon_percentage']+$transfer_val['agent_markup_percentage'];
															ob_start();
														?>
															<tr class="odd">
																<td style = "text-align:left;">
																	<?php echo $return_data_arr_transfer['find_transfer']['transfer_title'];?> - <?php echo $return_data_arr_transfer['find_transfer']['transfer_service'];?> - <?php echo $return_data_arr_transfer['find_offer']['offer_title'];?> ( Capacity:  <?php echo $return_data_arr_transfer['find_offer']['offer_capacity'];?> )
																	<?php
																	if($return_data_arr_transfer['find_transfer']['allow_pickup_type']!=""):
																	?>
																	<br/>
																	Pick Up: <?php echo $return_data_arr_transfer['find_transfer']['allow_pickup_type'];?>
																	<?php
																	endif;
																	if($return_data_arr_transfer['find_transfer']['allow_dropoff_type']!=""):
																	?>
																	<br/>
																	Drop off: <?php echo $return_data_arr_transfer['find_transfer']['allow_dropoff_type'];?>
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
							/*if(isset($hotel_html) && $hotel_html!=""):
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
									</tr>
								</thead>
								<tbody aria-relevant="all" aria-live="polite" role="alert">
									<?php echo $hotel_html;?>
								</tbody>
							</table>
							<?php
							endif;*/
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
							<!-- <table aria-describedby="example1_info" id="example" class="table table-bordered table-striped dataTable">
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
											<?php echo $number_of_adult;?>
										</td>
										<td style = "text-align:center;font-weight:bold;">
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
							</table> -->
						</div>
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
			</section>
		</div>
		<!-- BODY -->

        <!-- FOOTER -->
		<?php require_once(SUPPLIER_CONTROL_CENTER_COMMON_FILE_PATH.'footer.php');?>
		<!-- FOOTER -->

	</div>
</body>
</html>