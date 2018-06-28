<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
	unset($_SESSION['step_1']);
	unset($_SESSION['step_2']);
	unset($_SESSION['step_3']);
	unset($_SESSION['step_4']);
	unset($_SESSION['step_5']);
	if(isset($_GET['msg']) && $_GET['msg']=="b_success")
	{
		$_SESSION['SET_TYPE'] = 'success';
		$_SESSION['SET_FLASH'] = "Booking has been saved successfully.";
		header("location:bookings");
		exit;
	}
	$autentication_data_booking=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.BOOKING_API_PATH."authorized.php"));
	if(isset($autentication_data_booking->status)):
		if($autentication_data_booking->status=="success"):
			$post_data_booking['token']=array(
				"token"=>$autentication_data_booking->results->token,
				"token_timeout"=>$autentication_data_booking->results->token_timeout,
				"token_generation_time"=>$autentication_data_booking->results->token_generation_time
			);
			if(isset($_GET['del_booking_id']) && $_GET['del_booking_id']!=""):
				$post_data_booking['data']['id']=base64_decode($_GET['del_booking_id']);
				$post_data_booking['data']['is_deleted']="Y";
				$post_data_str=json_encode($post_data_booking);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.BOOKING_API_PATH."booking/update.php");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				$return_data = curl_exec($ch);
				curl_close($ch);
				$return_data_arr=json_decode($return_data, true);
				if(!isset($return_data_arr['status'])):
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH']="Some error has been occure during execution.";
				elseif($return_data_arr['status']=="success"):
					$_SESSION['SET_TYPE'] = 'success';
					$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
				else:
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
				endif;
				header("location:bookings");
				exit;
			endif;
			if(isset($_GET['agent_id']) && $_GET['agent_id']!=""):
				$post_data_booking['data']['agent_id']=base64_decode($_GET['agent_id']);
			endif;
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
				$data['status'] = 'error';
				$data['msg']="Some error has been occure during execution.";
			elseif($return_data_arr_booking['status']=="success"):
				$booking_details_list=$return_data_arr_booking['results'];
			else:
				$data['status'] = 'error';
				$data['msg'] = $return_data_arr_booking['msg'];
			endif;
		endif;
	else:
		$data['status'] = 'error';
		$data['msg'] = $autentication_data->msg;
	endif;
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>LIST(S) OF BOOKINGS</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	$(document).ready(function() {
		$('#example').DataTable();
	} );
	</script>
</head>
<body class="skin-purple">
	<div class="wrapper">
		<!-- TOP HEADER -->
		<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'header.php');?>		
		<!-- TOP HEADER -->

		<!-- LEFT MENU -->
		<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'menu.php');?>
		<!-- LEFT MENU -->
		
		<!-- BODY -->
		<div class="content-wrapper">
			<section class="content-header">
				<h1>Lists Of Bookings</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Lists Of Bookings</li>
				</ol>
			</section>
			<section class="content">
				<div class="row">
					<section class="col-lg-12 connectedSortable">
						<div id="notify_msg_div"></div>
						<div class="box">
							<div class="box-body">
								<div id="example1_wrapper" class="dataTables_wrapper form-inline" role="grid">
									<div id="no-more-tables">
										<table aria-describedby="example1_info" id="example" class="table table-bordered table-striped dataTable">
											<thead>
												<tr role="row">
													<th>#</th>
													<th>Quotation Name</th>
													<th>Created By</th>
													<th>Check In & Check Out Date</th>
													<th>Number Of Person</th>
													<th>Number Of Days</th>
													<th>Destination</th>
													<th>Service Include</th>
													<th>Payment Type</th>
													<th>Total Quotation</th>
													<th>Status</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody aria-relevant="all" aria-live="polite" role="alert">
											<?php
											if(isset($booking_details_list) && !empty($booking_details_list)):
												foreach($booking_details_list as $book_key=>$book_val):
													$number_of_person=$number_of_adult=$number_of_child=0;
													$audlt_arr=json_decode($book_val['adult'], true);
													foreach($audlt_arr as $adult_key=>$adult_val):
														if($adult_val!="")
															$number_of_adult=$number_of_adult+$adult_val;
													endforeach;
													$child_arr=json_decode($book_val['child'], true);
													foreach($child_arr as $child_key=>$child_val):
														if(isset($child_val['child']) && $child_val['child']!="")
															$number_of_child=$number_of_child+$child_val['child'];
													endforeach;
													$number_of_person=$number_of_adult+$number_of_child;
													$checkin_date = strtotime($book_val['checkin_date']);
													$checkout_date = strtotime($book_val['checkout_date']);
													$datediff = $checkout_date - $checkin_date;
													$destination_str="";
													$service_arr=array("Hotel");
													foreach($book_val['booking_destination_list'] as $dest_key=>$dest_val):
														if($destination_str!="")
															$destination_str.=", ";
														$destination_str.=$dest_val['ci_name'];
														if(isset($dest_val['booking_tour_list']) && !empty($dest_val['booking_tour_list']) && !in_array("Tour", $service_arr))
															array_push($service_arr, "Tour");
														if(isset($dest_val['booking_transfer_list']) && !empty($dest_val['booking_transfer_list']) && !in_array("Transfer", $service_arr))
															array_push($service_arr, "Transfer");
													endforeach;
											?>
												<tr class="odd">
													<td class="  sorting_1"><?php echo $book_key+1;?></td>
													<td class=" "><?php echo $book_val['quotation_name'];?></td>
													<td class=" " style="word-break:break-all;">
													<?php
													echo "Booking Type: ".$book_val['booking_type']."<br/>";
													if($book_val['booking_type']=="personal"):
														$find_dmc=tools::find("first", TM_DMC, '*', "WHERE id=:id ", array(":id"=>$book_val['dmc_id']));	
														echo $find_dmc['first_name']." ".$find_dmc['last_name'];
														echo "<br/>";
														echo "E: ".$find_dmc['email_address'];
														echo "<br/>";
														echo ($find_dmc['phone_number']!="" ? "P: ".$find_dmc['phone_number'] : "");
													elseif($book_val['booking_type']=="agent"):
														$autentication_data_agent=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."authorized.php"));
														if(isset($autentication_data_agent->status)):
															if($autentication_data_agent->status=="success"):
																$post_data_agent['token']=array(
																	"token"=>$autentication_data_agent->results->token,
																	"token_timeout"=>$autentication_data_agent->results->token_timeout,
																	"token_generation_time"=>$autentication_data_agent->results->token_generation_time
																);
																$post_data_agent['data']['agent_id']=$book_val['agent_id'];
																$post_data_str_agent=json_encode($post_data_agent);
																$ch = curl_init();
																curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
																curl_setopt($ch, CURLOPT_HEADER, false);
																curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
																curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
																curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."agent/booking-agent.php");
																curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_agent);
																curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
																$return_data_agent = curl_exec($ch);
																curl_close($ch);
																$return_data_arr_agent=json_decode($return_data_agent, true);
																if(!isset($return_data_arr_agent['status'])):
																	//$data['status'] = 'error';
																	//$data['msg']="Some error has been occure during execution.";
																elseif($return_data_arr_agent['status']=="success"):
																	echo $return_data_arr_agent['results']['first_name']." ".$return_data_arr_agent['results']['last_name'];
																	echo "<br/>";
																	echo "E: ".$return_data_arr_agent['results']['email_address'];
																	echo "<br/>";
																	echo ($return_data_arr_agent['results']['telephone']!="" ? "P: ".$return_data_arr_agent['results']['telephone'] : "");
																else:
																	//$data['status'] = 'error';
																	//$data['msg'] = $return_data_arr_agent['msg'];
																endif;
															endif;
														else:
															//$data['status'] = 'error';
															//$data['msg'] = $autentication_data->msg;
														endif;
													endif;
													?>
													</td>
													<td class=" ">
														<?php echo tools::module_date_format($book_val['checkin_date'])." - ".tools::module_date_format($book_val['checkout_date']);?>
													</td>
													<td class=" "><?php echo $number_of_person;?></td>
													<td class=" "><?php echo round($datediff / (60 * 60 * 24));;?></td>
													<td class=" "><?php echo $destination_str;?></td>
													<td class=" "><?php echo implode(", ", $service_arr);?></td>
													<td class=" "><?php echo $book_val['payment_type'];?></td>
													<td class=" "><?php echo $book_val['currency_code'].number_format($book_val['total_amount'], 2, ".", ",");?></td>
													<td class=" ">
														<a style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" data-id="" class="status_checks <?= $book_val['status']==1 ? "btn-success" : ($book_val['status']==2 ? "btn-danger" : "btn-warning");?>"><?= $book_val['status']==1 ? "Completed" : ($book_val['status']==2 ? "Rejected" : "Pending");?></a>
													</td>
													<td class=" " data-title="Action">
														<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>view_booking?booking_id=<?php echo base64_encode($book_val['id']);?>" title = "View Booking Details"><i class="fa fa-eye fa-1x" ></i></a>&nbsp;&nbsp;
														<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>booking_voucher?booking_id=<?php echo base64_encode($book_val['id']);?>" title = "Generate Vouchers" target="_blank"><i class="fa fa-file-pdf-o fa-1x" ></i></a>&nbsp;&nbsp;
														<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>booking_invoice?booking_id=<?php echo base64_encode($book_val['id']);?>" title = "Generate Invoice" target="_blank"><i class="fa fa-file-word-o fa-1x" ></i></a>&nbsp;&nbsp;
														<a href = "<?php 
														echo(DOMAIN_NAME_PATH_ADMIN);?>edit_booking?booking_id=<?php echo base64_encode($book_val['id']);?>" title = "Edit Booking"><i class="fa fa-pencil-square-o fa-1x" ></i></a>&nbsp;&nbsp;
														<!-- <a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>bookings?del_booking_id=<?php echo base64_encode($book_val['id']);?>"  title = "Delete Booking" onclick = "confirm('Are you sure you want to delete this item?') ? '' : event.preventDefault()"><i class="fa fa fa-trash-o fa-1x"></i></a> -->
													</td>
												</tr>
											<?php
												endforeach;
											else:
											?>
												<tr>
													<td colspan="100%" class="text-center">No record found</td>
												</tr>
											<?php
											endif;
											?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>
			</section>
		</div>
		<!-- BODY -->
		<!-- FOOTER -->
		<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'footer.php');?>
		<!-- FOOTER -->
	</div>
</body>
</html>