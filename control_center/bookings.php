<?php
require_once('loader.inc');
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
	$( function() {
		$( "#checkin" ).datepicker();
		$( "#checkout" ).datepicker();
		$( "#checkin2" ).datepicker();
		$( "#checkout2" ).datepicker();
		$( "#checkin3" ).datepicker();
		$( "#checkout3" ).datepicker();
		$( "#checkout4" ).datepicker();
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
								<div class="tab-pane active" role="tabpanel" id="step1">
									<h3>Select Criteria For New Booking</h3>
									<form name="profile" name="form_create_slider" id="form_create_slider" method="POST" enctype="mulimedeia/form-data">
										<div class="col-md-12 row">
											<div class="box-body">
												<div class="form-group col-md-6">
													<label for="inputName" class="control-label">Booking ID</label>
													<input type="text" class="form-control validate[required]"  value="" name="checkout" id="checkout" placeholder="Booking Id" tabindex = "1" />
												</div>
												<div class="form-group col-md-6">
													<label for="inputName" class="control-label">Booking Date From</label>
													<input type="text" class="form-control validate[required]"  value="" name="checkin" id="checkin" placeholder="Booking Date From<" tabindex = "3"  />
												</div>
												<div class="form-group col-md-6">
													<label for="inputName" class="control-label">Booking Date To</label>
													<input type="text" class="form-control validate[required]"  value="" name="checkout" id="checkout2" placeholder="Booking Date To" tabindex = "4" />
												</div>
												<div class="form-group col-md-6">
													<label for="inputName" class="control-label">Service Date From</label>
													<input type="text" class="form-control validate[required]"  value="" name="checkin2" id="checkin2" placeholder="Service Date From" tabindex = "3"  />
												</div>
												<div class="form-group col-md-6">
													<label for="inputName" class="control-label">Service Date To</label>
													<input type="text" class="form-control validate[required]"  value="" name="checkout2" id="checkout3" placeholder="Service Date To" tabindex = "4" />
												</div>
												<div class="form-group col-md-6">
													<label for="inputName" class="control-label">Deadline Date From</label>
													<input type="text" class="form-control validate[required]"  value="" name="checkin3" id="checkin3" placeholder="Deadline Date From" tabindex = "3"  />
												</div>
												<div class="form-group col-md-6">
													<label for="inputName" class="control-label">Deadline Date To</label>
													<input type="text" class="form-control validate[required]"  value="" name="checkout3" id="checkout4" placeholder="Deadline Date To" tabindex = "4" />
												</div>
												<div class="form-group col-md-6">
													<label for="inputName" class="control-label">Reservation Status</label>
													<select name="country" class="form-control validate[required]" id="country">
														<option label="All" value="" selected="selected">All</option>
														<option label="On Request" value="on_request">On Request</option>
														<option label="Confirmed" value="confirmed">Confirmed</option>
														<option label="Vouchered" value="vouchered">Vouchered</option>
														<option label="Cancelled" value="cancelled">Cancelled</option>
														<option label="Rejected" value="rejected">Rejected</option>
														<option label="In Process Cancel" value="inprocess_cancel">In Process Cancel</option>
														<option label="Posted" value="Posted">Posted</option>
														<option label="Vouchered and Cancelled" value="vouchered_cancelled">Vouchered and Cancelled</option>
													</select>
												</div>
												<div class="form-group col-md-6">
													<label for="inputName" class="control-label">Show Pat Booking</label>
													<select name="country" class="form-control validate[required]" id="country">
														<option label="All" value="" selected="selected">All</option>
														<option label="Yes" value="Yes">Yes</option>
														<option label="No" value="No">No</option>
													</select>
												</div>
											</div>
										</div>
									</form>
									<ul class="list-inline pull-left">
										<li><button type="button" class="btn btn-primary next-step">Search</button></li>
									</ul>
								</div>
							</div>
							<div class="box-body">
								<div id="example1_wrapper" class="dataTables_wrapper form-inline" role="grid">
									<div id="no-more-tables">
										<table aria-describedby="example1_info" class="table table-bordered table-striped dataTable">
											<thead>
												<tr role="row">
													<th>#</th>
													<th>Booking Type</th>
													<th>Created By</th>
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
												<tr class="odd">
													<td class="  sorting_1">1</td>
													<td class=" ">Personal</td>
													<td class=" ">
														Sandeep Sing
														<br/>
														E: candeep@gmail.com
														P: +91 2365986989
													</td>
													<td class=" ">
														01/03/2018 - 04/03/2018
													</td>
													<td class=" ">1</td>
													<td class=" ">3</td>
													<td class=" ">Thailand, Bangkok</td>
													<td class=" ">Hotel, Transfer, Tour</td>
													<td class=" ">$8475.00</td>
													<td class=" "><a style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" id="status" data-id="" class="status_checks btn-warning">PENDING</a></td>
													<td class=" " data-title="Action">
														<a href = "javascript:void(0);" title = "View Booking Details"><i class="fa fa-eye fa-1x" ></i></a>&nbsp;&nbsp;
														<a href = "javascript:void(0);" title = "Generate Vouchers"><i class="fa fa-file-alt fa-1x" ></i></a>&nbsp;&nbsp;
														<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>edit_booking" title = "Edit Booking"><i class="fa fa-pencil-square-o fa-1x" ></i></a>&nbsp;&nbsp;
														<a href = "#"  title = "Delete Booking" onclick = ""><i class="fa fa fa-trash-o fa-1x"></i></a>
													</td>
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
		</div>
		<!-- BODY -->

		<!-- FOOTER -->
		<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'footer.php');?>
		<!-- FOOTER -->
	</div>
</body>
</html>