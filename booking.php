<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['AGENT_SESSION_DATA']['id'], DOMAIN_NAME_PATH.'');
	if(isset($_GET['msg']) && $_GET['msg']=="b_success")
	{
		$_SESSION['SET_TYPE'] = 'success';
		$_SESSION['SET_FLASH'] = "Booking has been saved successfully.";
		header("location:booking.php".(isset($_GET['sub_agent_id']) && $_GET['sub_agent_id']!='' ? '?sub_agent_id='.$_GET['sub_agent_id'] : ''));
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
				$booking_details_list=$return_data_arr_booking['results'];
			else:
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH'] = $return_data_arr_booking['msg'];
			endif;
		endif;
	else:
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = $autentication_data->msg;
	endif;
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo DEFAULT_PAGE_TITLE ;?> Destination Management Company</title>	
		<?php require_once('meta.php');?>
		<link rel="stylesheet" href="<?php echo(DOMAIN_NAME_PATH);?>css/jquery.dataTables.min.css" />
		<script src="<?php echo(DOMAIN_NAME_PATH);?>js/jquery.dataTables.min.js"></script>
		<script type="text/javascript">
		<!--
			$(function() {
				$('#example').DataTable();
			});
		//-->
		</script>
	</head>
	<body class="index-page">
		<!-- TOP HEADER -->
		<?php require_once('header.php');?>
		<!-- TOP HEADER -->
		<div class="main-cont">
			<div class="body-padding">
				<div class="banner_slider" style="background:url(img/banner4.jpg)no-repeat center center/cover;">
					<div class="banner_slider_text">
						Booking List
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
													<div id="no-more-tables">
														<table aria-describedby="example1_info" id="example" class="table table-bordered table-striped dataTable">
															<thead>
																<tr role="row">
																	<th>#</th>
																	<th>Booking Type</th>
																	<!-- <th>Created By</th> -->
																	<th>Check In & Check Out Date</th>
																	<th>Number Of Person</th>
																	<th>Number Of Days</th>
																	<th>Destination</th>
																	<th>Service Include</th>
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
																	<td class=" "><?php echo $book_val['booking_type'];?></td>
																	<!-- <td class=" " style="word-break:break-all;">
																	<?php
																	if($book_val['booking_type']=="personal"):
																		$find_dmc=tools::find("first", TM_DMC, '*', "WHERE id=:id ", array(":id"=>$book_val['dmc_id']));	
																		echo $find_dmc['first_name']." ".$find_dmc['last_name'];
																		echo "<br/>";
																		echo "E: ".$find_dmc['email_address'];
																		echo "<br/>";
																		echo ($find_dmc['phone_number']!="" ? "P: ".$find_dmc['phone_number'] : "");
																	elseif($book_val['booking_type']=="agent"):
																		echo $find_agent_data['first_name']." ".$find_agent_data['last_name'];
																		echo "<br/>";
																		echo "E: ".$find_agent_data['email_address'];
																		echo "<br/>";
																		echo ($find_agent_data['telephone']!="" ? "P: ".$find_agent_data['telephone'] : "");
																	endif;
																	?>
																	</td> -->
																	<td class=" ">
																		<?php echo tools::module_date_format($book_val['checkin_date'])." - ".tools::module_date_format($book_val['checkout_date']);?>
																	</td>
																	<td class=" "><?php echo $number_of_person;?></td>
																	<td class=" "><?php echo round($datediff / (60 * 60 * 24));;?></td>
																	<td class=" "><?php echo $destination_str;?></td>
																	<td class=" "><?php echo implode(", ", $service_arr);?></td>
																	<td class=" "><?php echo $book_val['currency_code'].number_format($book_val['total_amount'], 2, ".", ",");?></td>
																	<td class=" ">
																		<a style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" data-id="" class="status_checks <?= $book_val['status']==1 ? "btn-success" : "btn-warning";?>" onclick="change_status(<?= $book_val['id'];?>, $(this))"><?= $book_val['status']==1 ? "Completed" : "Pending";?></a>
																	</td>
																	<td class="text-center" data-title="Action">
																		<a href = "<?php echo(DOMAIN_NAME_PATH);?>view_booking.php?booking_id=<?php echo base64_encode($book_val['id']);?><?php echo(isset($_GET['sub_agent_id']) && $_GET['sub_agent_id']!="" ? "&sub_agent_id=".$_GET['sub_agent_id'] : "");?>" title = "View Booking Details"><i class="fa fa-eye fa-1x" ></i></a>&nbsp;&nbsp;
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