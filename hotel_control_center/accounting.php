<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA_HOTEL']['id'], DOMAIN_NAME_PATH_HOTEL.'login');
	$white_list_array = array('hotels', 'booking_status', 'date_from', 'date_to', 'token', 'btn_submit', 'export_flag');
	$verify_token = "search_for_hotel_accounting";
	$autentication_data_booking=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.BOOKING_API_PATH."authorized.php"));
	if(isset($autentication_data_booking->status)):
		if($autentication_data_booking->status=="success"):
			$post_data_booking['token']=array(
				"token"=>$autentication_data_booking->results->token,
				"token_timeout"=>$autentication_data_booking->results->token_timeout,
				"token_generation_time"=>$autentication_data_booking->results->token_generation_time
			);
			if(isset($_POST)):
					//print_r($_POST);exit;
				if(tools::verify_token($white_list_array, $_POST, $verify_token)):
					//print_r($_POST);exit;
					$post_data_booking['data']=$_POST;
					$post_data_str_booking=json_encode($post_data_booking);
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_HEADER, false);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
					curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.BOOKING_API_PATH."booking/hottel-accounting.php");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_booking);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					$booking_return_data = curl_exec($ch);
					//print_r($booking_return_data);exit;
					curl_close($ch);
					$booking_return_data_arr=json_decode($booking_return_data, true);
					$booking_hotel_data=array();
					if(!isset($booking_return_data_arr['status'])):
						$_SESSION['SET_TYPE'] = 'error';
						$_SESSION['SET_FLASH']="Some error has been occure during execution.";
					elseif($booking_return_data_arr['status']=="success"):
						$booking_hotel_data=$booking_return_data_arr['results'];
						//print_r($booking_hotel_data);exit;
					else:
						$_SESSION['SET_TYPE'] = 'error';
						$_SESSION['SET_FLASH'] = $booking_return_data_arr['msg'];
					endif;

					if(isset($_POST['export_flag']) && $_POST['export_flag']!=""):
						// file name for download
						unset($_POST['export_flag']);
						$fileName = "hotel_accounting_export_data" . date('Ymd') . ".xls";
						
						// headers for download
						header("Content-Disposition: attachment; filename=\"$fileName\"");
						header("Content-Type: application/vnd.ms-excel");

						echo "# \t CHECK IN & CHECK OUT DATE \t NUMBER OF NIGHTS \t NUMBER OF PERSON \t NUMBER OF ROOMS \t ROOMS DETAILS \t HOTEL PRICE". "\n";
						foreach($booking_hotel_data as $k => $row) {
							$number_of_person=$number_of_adult=$number_of_child=0;
							$audlt_arr=json_decode($row['adult'], true);
							foreach($audlt_arr as $adult_key=>$adult_val):
								if($adult_val!="")
									$number_of_adult=$number_of_adult+$adult_val;
							endforeach;
							$child_arr=json_decode($row['child'], true);
							foreach($child_arr as $child_key=>$child_val):
								if(isset($child_val['child']) && $child_val['child']!="")
									$number_of_child=$number_of_child+$child_val['child'];
							endforeach;
							$number_of_person=$number_of_adult+$number_of_child;
							$datediff='';
							$hotel_status='';

							if($row['booking_destination_list']):
								foreach($row['booking_destination_list'] as $b):
									if($b['booking_hotel_list'][0]['hotel_id']==$_SESSION['SESSION_DATA_HOTEL']['id']):
										$find_room = tools::find("first", TM_ROOMS, '*', "WHERE id=:id AND hotel_id=:hotel_id ", array(":id"=>$b['booking_hotel_list'][0]['room_id'], ":hotel_id"=>$b['booking_hotel_list'][0]['hotel_id']));
										
										$checkin_date = $b['booking_hotel_list'][0]['booking_start_date'];
										$checkout_date = $b['booking_hotel_list'][0]['booking_end_date'];
										$datediff = strtotime($checkout_date) - strtotime($checkin_date);
										$hotel_status=$b['booking_hotel_list'][0]['status'];
										
										echo ($k+1)."\t".tools::module_date_format($b['booking_hotel_list'][0]['booking_start_date'])." & ".tools::module_date_format($b['booking_hotel_list'][0]['booking_end_date'])."\t".round($datediff / (60 * 60 * 24))."\t".$number_of_person."\t ".$row['number_of_rooms']."\t".$find_room['room_type']."\t ".$b['booking_hotel_list'][0]['price']." (".$row['currency_code'].")"."\n";
									endif;
								endforeach;
							endif;

						}
						exit;
					endif;
				endif;
			endif;
		endif;
	else:
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = $autentication_data_booking->msg;
	endif;
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER_HOTEL);?>ACCOUNTING DETAILS FOR AGENTS</title>
	<?php require_once(HOTEL_CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	$(document).ready(function() {
		$('#example').DataTable();
		$("#export_to_excel").click(function(){
			$("#export_flag").val("true");
			$("#agent_accounting").submit();
		});
		$("#btn_submit").click(function(){
			$("#export_flag").val("");
		});
	} );
	</script>
	<script>
	$( function() {
		$("#agent_accounting").validationEngine();
		$("#date_from").datepicker({
			dateFormat: 'dd/mm/yy',
			//minDate:0,
			onSelect:function(selectedDate){
				$("#date_to").datepicker( "option", "minDate", selectedDate);
			}
		});
		$("#date_to").datepicker({
			dateFormat: 'dd/mm/yy',
			//minDate:0,
			onSelect:function(selectedDate){
				$("#date_from").datepicker( "option", "maxDate", selectedDate);
			}
		});
	} );
	</script>
	<!-- JAVASCRIPT CODE -->
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
			<h1>ACCOUNTING DETAILS FOR HOTEL(S)</h1>
			<ol class="breadcrumb">
				<li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">Hotel(s) Accounting </li>
			</ol>
		</section>
		<section class="content">
			<div class="row">
				<section class="col-lg-12 connectedSortable">
					<div id="notify_msg_div"></div>
					<div class="box">
						<div class="box-body">
							<form name = "agent_accounting" id = "agent_accounting" method = "POST" action = "">
								<div class="col-md-12 row">
									<div class="box-body">
										<div class="form-group col-md-4">
											<label for="email"><font color="#FF0000">*</font>Booking Status</label>
											<select class="form-control" name = "booking_status" id = "booking_status" tabindex = "2">
												<option value = "A" <?php echo(isset($_POST['booking_status']) && $_POST['booking_status']=="A" ? 'selected="selected"' : '');?>>All</option>
												<option value = "1" <?php echo(isset($_POST['booking_status']) && $_POST['booking_status']=="1" ? 'selected="selected"' : '');?>>Confirmed</option>
												<option value = "2" <?php echo(isset($_POST['booking_status']) && $_POST['booking_status']=="2" ? 'selected="selected"' : '');?>>Cancelled</option>
											</select>
										</div>
										<div class="form-group col-md-4">
											<label for="email">Date From</label>
											<input type="text" class="form-control"  value="<?php echo(isset($_POST['date_from']) && $_POST['date_from']!="" ? $_POST['date_from'] : '');?>" name="date_from" id="date_from" placeholder="Date From" tabindex = "3" />
										</div>
										<div class="form-group col-md-4">
											<label for="inputName" class="control-label">Date To</label>
											<div class="input-icon right">
												<input type="text" class="form-control"  placeholder = "Date To" name="date_to" id="date_to" tabindex = "4" value="<?php echo(isset($_POST['date_to']) && $_POST['date_to']!="" ? $_POST['date_to'] : '');?>"/>
											</div>
										</div>
										<div class="form-group col-md-12">
											<input type="hidden" name="token" value="<?php echo(tools::generateFormToken($verify_token)); ?>" />
											<input type="hidden" name="export_flag" id="export_flag" value="<?php echo(isset($_POST['export_flag']) && $_POST['export_flag']!="" ? $_POST['export_flag'] : '');?>" />
											<input type="hidden" name="hotels" id="hotels" value="<?php echo(isset($_SESSION['SESSION_DATA_HOTEL']['id']) && $_SESSION['SESSION_DATA_HOTEL']['id']!="" ? $_SESSION['SESSION_DATA_HOTEL']['id'] : '');?>" />
											<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex = "5">SEARCH</button>		
											<?php
											if(isset($booking_hotel_data) && !empty($booking_hotel_data)):
											?>
											<button type="button" id="export_to_excel" class="btn btn-primary pull-right" tabindex = "6">EXPORT TO EXCEL</button>
											<?php
											endif;
											?>
										</div>
									</div>
								</div>
							</form>
							<div id="example1_wrapper" class="dataTables_wrapper form-inline" role="grid">
								<div id="no-more-tables">
									<table class="table table-bordered table-striped dataTable">
										<tbody aria-relevant="all" aria-live="polite" role="alert">
											<tr>
												<td style = "text-align:center;font-weight:bold;">Please use the above form to generate your preferred report!</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
		</section>
		
		<?php
		if(isset($booking_hotel_data) && !empty($booking_hotel_data)):
		?>
		<section class="content">
			<div class="row">
				<section class="col-lg-12 connectedSortable">
					<div id="notify_msg_div"></div>
					<div class="box">
						<div class="box-body">
							<div id="example1_wrapper" class="dataTables_wrapper form-inline" role="grid">
								<div id="no-more-tables">
									<table aria-describedby="example1_info" class="table table-bordered table-striped dataTable" id="example">
										<thead>
											<tr role="row">
												<th>#</th>
												<th>Check In & Check Out Date</th>
												<th>Number Of Nights</th>
												<th>Number Of Person</th>
												<th>Number Of Rooms</th>
												<th>Rooms Details</th>
												<th>View</th>
												<th>Status</th>
											</tr>
										</thead>
										<tbody aria-relevant="all" aria-live="polite" role="alert">
											<?php
												foreach($booking_hotel_data as $book_key=>$book_val):
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
													$datediff='';
													$hotel_status='';
													foreach($book_val['booking_destination_list'] as $dest_key=>$dest_val):
														if($dest_val['booking_hotel_list'][0]['hotel_id']==$_SESSION['SESSION_DATA_HOTEL']['id']):
															$find_room = tools::find("first", TM_ROOMS, '*', "WHERE id=:id AND hotel_id=:hotel_id ", array(":id"=>$dest_val['booking_hotel_list'][0]['room_id'], ":hotel_id"=>$dest_val['booking_hotel_list'][0]['hotel_id']));
															
															$checkin_date = $dest_val['booking_hotel_list'][0]['booking_start_date'];
															$checkout_date = $dest_val['booking_hotel_list'][0]['booking_end_date'];
															$datediff = strtotime($checkout_date) - strtotime($checkin_date);
															$hotel_status=$dest_val['booking_hotel_list'][0]['status'];
														endif;
													endforeach;
											?>
												<tr class="odd">
													<td class="  sorting_1"><?php echo $book_key+1;?></td>
													<td class=" ">
														<?php echo tools::module_date_format($checkin_date)." - ".tools::module_date_format($checkout_date);?>
													</td>
													<td class=" "><?php echo round($datediff / (60 * 60 * 24));?></td>
													<td class=" "><?php echo $number_of_person;?></td>
													<td class=" "><?php echo $book_val['number_of_rooms'];?></td>
													<td class=" "><?=$find_room['room_type'];?></td>
													<td class=" ">
														<a href = "<?php echo(DOMAIN_NAME_PATH_HOTEL);?>view_booking?booking_id=<?php echo base64_encode($book_val['id']);?>" title = "View Booking Details"><i class="fa fa-eye fa-1x" ></i></a>&nbsp;&nbsp;
													</td>
													<td class=" ">
														<a style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" data-id="" class="status_checks <?= $hotel_status==1 ? "btn-success" : "btn-warning";?>"><?= $hotel_status==1 ? "Completed" : "Pending";?></a>
													</td>
												</tr>
											<?php
												endforeach;
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
		<?php
		endif;
		?>
	</div>
	<!-- BODY -->

	<!-- FOOTER -->
	<?php require_once(HOTEL_CONTROL_CENTER_COMMON_FILE_PATH.'footer.php');?>
	<!-- FOOTER -->
</div>
</body>
</html>