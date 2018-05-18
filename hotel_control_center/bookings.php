<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA_HOTEL']['id'], DOMAIN_NAME_PATH_HOTEL.'login');

	$autentication_data_booking=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.BOOKING_API_PATH."authorized.php"));
	if(isset($autentication_data_booking->status)):
		if($autentication_data_booking->status=="success"):
			$post_data_booking['token']=array(
				"token"=>$autentication_data_booking->results->token,
				"token_timeout"=>$autentication_data_booking->results->token_timeout,
				"token_generation_time"=>$autentication_data_booking->results->token_generation_time
			);
			$post_data_booking['data']['hotel_id']=$_SESSION['SESSION_DATA_HOTEL']['id'];
			$post_data_str_booking=json_encode($post_data_booking);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.BOOKING_API_PATH."booking/hotel-data.php");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_booking);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$return_data_booking = curl_exec($ch);
			curl_close($ch);
			//print_r($return_data_booking);
			$return_data_arr_booking=json_decode($return_data_booking, true);
			if(!isset($return_data_arr_booking['status'])):
				$data['status'] = 'error';
				$data['msg']="Some error has been occure during execution.";
			elseif($return_data_arr_booking['status']=="success"):
				$booking_details_list=$return_data_arr_booking['results'];
				//print_r($booking_details_list);
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
	<title><?php echo(DOMAIN_NAME_PATH_HOTEL);?>LIST(S) OF BOOKINGS</title>
	<?php require_once(HOTEL_CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	/*$(document).ready(function() {
		$('#example').DataTable();
	} );*/
	/* Custom filtering function which will search data in column four between two values */
$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) {
        var min = parseInt( $('#min').val(), 10 );
        var max = parseInt( $('#max').val(), 10 );
        var age = parseFloat( data[3] ) || 0; // use data for the age column
 
        if ( ( isNaN( min ) && isNaN( max ) ) ||
             ( isNaN( min ) && age <= max ) ||
             ( min <= age   && isNaN( max ) ) ||
             ( min <= age   && age <= max ) )
        {
            return true;
        }
        return false;
    }
);
 
$(document).ready(function() {
    var table = $('#example').DataTable();
     
    // Event listener to the two range filtering inputs to redraw on input
    $('#min, #max').keyup( function() {
        table.draw();
    } );
} );
 $(function() {	   
	$("#min").datepicker({
		dateFormat: 'dd/mm/yy',
		//minDate:0,
		onSelect:function(selectedDate){
			$("#max").datepicker( "option", "minDate", selectedDate);
			var table = $(#example).DataTable();
			table.search( $(this).val() ).draw();}
		}
	});
	$("#max").datepicker({
		dateFormat: 'dd/mm/yy',
		//minDate:0,
		onSelect:function(selectedDate){
			$("#min").datepicker( "option", "maxDate", selectedDate);
			var table = $(#example).DataTable();
			table.search( $(this).val() ).draw();}
		}
	});
 });
	</script>
</head>
<body class="skin-purple">
	<div class="wrapper">
		<!-- TOP HEADER -->
		<?php require_once(HOTEL_CONTROL_CENTER_COMMON_FILE_PATH.'header.php');?>		
		<!-- TOP HEADER -->

		<!-- LEFT MENU -->
		<?php require_once(HOTEL_CONTROL_CENTER_COMMON_FILE_PATH.'menu.php');?>
		<!-- LEFT MENU -->
		
		<!-- BODY -->
		<div class="content-wrapper">
			<section class="content-header">
				<h1>Lists Of Bookings</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo(DOMAIN_NAME_PATH_HOTEL);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
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
										<div id="" class="box-body">
											<div class="col-md-2"></div>
											<div class="form-group col-md-2">
												<input id="min" name="min" type="text" class="form-control" placeholder="Start Date" style="width: 100%;">
											</div>
											<div class="form-group col-md-2">
												<input id="max" name="max" type="text" class="form-control" placeholder="End Date" style="width: 100%;" >
											</div>
											<div class="clearfix"></div>
										</div>
										<table aria-describedby="example1_info" class="table table-bordered table-striped dataTable" id="example">
											<thead>
												<tr role="row">
													<th>#</th>
													<th>Booking Type</th>
													<th>Created By</th>
													<th>Check In & Check Out Date</th>
													<th>Number Of Person</th>
													<th>Number Of Days</th>
													<th>Destination</th>
													<!-- <th>Room Type</th> -->
													<th>Total Price</th>
													<!-- <th>Status</th> -->
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
															/*if(isset($dest_val['booking_tour_list']) && !empty($dest_val['booking_tour_list']) && !in_array("Tour", $service_arr))
																array_push($service_arr, "Tour");
															if(isset($dest_val['booking_transfer_list']) && !empty($dest_val['booking_transfer_list']) && !in_array("Transfer", $service_arr))
																array_push($service_arr, "Transfer");*/
														endforeach;
												?>
													<tr class="odd">
														<td class="  sorting_1"><?php echo $book_key+1;?></td>
														<td class=" "><?php echo $book_val['booking_type'];?></td>
														<td class=" " style="word-break:break-all;">
														<?php
														if($book_val['booking_type']=="personal"):
															$autentication_data_dmc=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.DMC_API_PATH."authorized.php"));
															if(isset($autentication_data_dmc->status)):
																if($autentication_data_dmc->status=="success"):
																	$post_data_dmc['token']=array(
																		"token"=>$autentication_data_dmc->results->token,
																		"token_timeout"=>$autentication_data_dmc->results->token_timeout,
																		"token_generation_time"=>$autentication_data_dmc->results->token_generation_time
																	);
																	$post_data_dmc['data']['dmc_id']=$book_val['dmc_id'];
																	$post_data_str_dmc=json_encode($post_data_dmc);
																	$ch = curl_init();
																	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
																	curl_setopt($ch, CURLOPT_HEADER, false);
																	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
																	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
																	curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.DMC_API_PATH."dmc/read.php");
																	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_dmc);
																	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
																	$return_data_dmc = curl_exec($ch);
																	curl_close($ch);
																	//print_r($return_data_dmc);
																	$return_data_arr_dmc=json_decode($return_data_dmc, true);
																	if(!isset($return_data_arr_dmc['status'])):
																		//$data['status'] = 'error';
																		//$data['msg']="Some error has been occure during execution.";
																	elseif($return_data_arr_dmc['status']=="success"):
																		echo $return_data_arr_dmc['results']['first_name']." ".$return_data_arr_dmc['results']['last_name'];
																		echo "<br/>";
																		echo "E: ".$return_data_arr_dmc['results']['email_address'];
																		echo "<br/>";
																		echo ($return_data_arr_dmc['results']['phone_number']!="" ? "P: ".$return_data_arr_dmc['results']['phone_number'] : "");
																	else:
																		//$data['status'] = 'error';
																		//$data['msg'] = $return_data_arr_agent['msg'];
																	endif;
																endif;
															else:
																//$data['status'] = 'error';
																//$data['msg'] = $autentication_data->msg;
															endif;
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
														<!-- <td class=" "><?php echo implode(", ", $service_arr);?></td> -->
														<td class=" "><?php echo $book_val['currency_code'].number_format($book_val['total_amount'], 2, ".", ",");?></td>
														<!-- <td class=" ">
															<a style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" data-id="" class="status_checks <?= $book_val['status']==1 ? "btn-success" : "btn-warning";?>"><?= $book_val['status']==1 ? "Completed" : "Pending";?></a>
														</td> -->
														<td class=" " data-title="Action">
															<a href = "<?php echo(DOMAIN_NAME_PATH_HOTEL);?>view_booking?booking_id=<?php echo base64_encode($book_val['id']);?>" title = "View Booking Details"><i class="fa fa-eye fa-1x" ></i></a>&nbsp;&nbsp;
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
		<?php require_once(HOTEL_CONTROL_CENTER_COMMON_FILE_PATH.'footer.php');?>
		<!-- FOOTER -->
	</div>
</body>
</html>