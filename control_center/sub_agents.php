<?php
require_once('../loader.inc');
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>LISTS OF SUB AGENTS</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	$(document).ready(function() {
		$('#example').DataTable();
	} );
	</script>
	<script>
		window.onload = function () {
			// Construct options first and then pass it as a parameter
			var options1 = {
				animationEnabled: true,
				title: {
					text: "Sub Agent Sales Chart"
				},
				data: [{
					type: "column", //change it to line, area, bar, pie, etc
					legendText: "",
					showInLegend: false,
					dataPoints: [
						{ label: "Sub Agent 1", y: 10 },
						{ label: "Sub Agent 2", y: 6 },
						{ label: "Sub Agent 3", y: 14 },
						{ label: "Sub Agent 4", y: 18 },
						{ label: "Sub Agent 5", y: 12 },
						{ label: "Sub Agent 6", y: 19 },
						{ label: "Sub Agent 7", y: 30 },
						{ label: "Sub Agent 8", y: 40 },
						{ label: "Sub Agent 9", y: 55 },
						{ label: "Sub Agent 10", y: 5 }
						]
					}]
			};

			var options2 = {
				animationEnabled: true,
				title: {
					text: "Progress Chart"
				},
				data: [{
					type: "pie", //change it to line, area, bar, pie, etc
					legendText: "",
					showInLegend: false,
					dataPoints: [
						{ label: "2017", y: 8 },
						{ label: "2017", y: 10 },
						{ label: "2018", y: 25 }
						]
					}]
			};

			$("#resizable1").resizable({
				create: function (event, ui) {
					//Create chart.
					$("#chartContainer1").CanvasJSChart(options1);
				},
				resize: function (event, ui) {
					//Update chart size according to its container size.
					$("#chartContainer1").CanvasJSChart().render();
				}
			});

			$("#resizable2").resizable({
				create: function (event, ui) {
					//Create chart.
					$("#chartContainer2").CanvasJSChart(options2);
				},
				resize: function (event, ui) {
					//Update chart size according to its container size.
					$("#chartContainer2").CanvasJSChart().render();
				}
			});

		}
	</script>
	<!-- JAVASCRIPT CODE -->
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
			<h1>Sandy Smith Details</h1>
			<ol class="breadcrumb">
				<li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">Lists Of Sub Agents </li>
			</ol>
		</section>
		<section class="content">
			<div class="row">
				<div class="col-lg-3 col-xs-6">
					<div class="small-box bg-aqua">
						<div class="inner">
							<h3>20</h3>
							<p>BOOKING BY OWN</p>
						</div>
						<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
				<div class="col-lg-3 col-xs-6">
					<div class="small-box bg-yellow">
						<div class="inner">
							<h3>$1000</h3>
							<p>OWN ORDER AMOUNT</p>
						</div>
						<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
				<div class="col-lg-3 col-xs-6">
					<div class="small-box bg-aqua">
						<div class="inner">
							<h3>25</h3>
							<p>BOOKING BY SUB AGENTS</p>
						</div>
						<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
				<div class="col-lg-3 col-xs-6">
					<div class="small-box bg-yellow">
						<div class="inner">
							<h3>$2500</h3>
							<p>SUB AGENTS ORDER AMOUNT</p>
						</div>
						<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
			</div>
			<div class="row">
				<section class="col-lg-6 connectedSortable">
					<div class="box box-info">
						<div id="resizable1" style="height: 370px;border:1px solid gray;">
							<div id="chartContainer1" style="height: 100%; width: 100%;"></div>
						</div>
					</div>
				</section>
				<section class="col-lg-6 connectedSortable">
					<div class="box box-info">
						<div id="resizable2" style="height: 370px;border:1px solid gray;">
							<div id="chartContainer2" style="height: 100%; width: 100%;"></div>
						</div>
					</div>
				</section>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div id="notify_msg_div"></div>
					<div class="box box-primary">
						<div class="col-md-12 row">
							<div class="box-header">
							   <h3 class="box-title">Details</h3>
							</div>
							<div class="box-body">
								<div id="" class="row rows">
									<div class="form-group col-md-4">
										<label for="pwd" class="form-label1">Company Name <span class=""> *</span> :</label>
										<br/>
										Sample Company name
									</div>
									<div class="form-group col-md-4">
										<label for="pwd" class="form-label1">Company Accounting Name<span class=""> *</span> :</label>
										<br/>
										Sample Company Accounting Name
									</div>
									
									<div class="form-group col-md-4">
										<label for="pwd" class="form-label1">First Name<span class=""> *</span> :</label>
										<br/>
										Sandy
									</div>
									<div class="form-group col-md-4">
										<label for="pwd" class="form-label1">Middle Name<span class=""> *</span> :</label>
										<br/>
										N/A
									</div>
											
									<div class="form-group col-md-4">
										<label for="pwd" class="form-label1">Last Name<span class=""> *</span> :</label>
										<br/>
										Smith
									</div>
									<div class="form-group col-md-4">
										<label for="pwd" class="form-label1">Email<span class=""> *</span> :</label>
										<br/>
										sandy@gmail.com
									</div>
									<div class="form-group col-md-4">
										<label for="pwd" class="form-label1">Designation :</label>
										<br/>
										Owner
									</div>
									<div class="form-group col-md-4">
										<label for="pwd" class="form-label1">IATA Status<span class=""> *</span> :</label>
										<br/>
										Approve
									</div>
									<div class="form-group col-md-4">
										<label for="pwd" class="form-label1">Nature of Business:</label>
										<br/>
										Hotel
									</div>
									<div class="form-group col-md-4">
										<label for="pwd" class="form-label1">Preferred Currency <span class="">*</span> :</label>
										<br/>
										Dollar
									</div>
									<div class="form-group col-md-4">
										<label for="pwd" class="form-label1">Country<span class="">*</span> :</label>
										<br/>
										India
									</div>
									<div class="form-group col-md-4">
										<label for="pwd" class="form-label1">City <span class="">*</span> :</label>
										<br/>
										West Bengal
									</div>
									<div class="form-group col-md-4">
										<label for="pwd" class="form-label1">Pincode/Zipcode/Postcode<span class="">*</span> :</label>
										<br/>
										700076
									</div>
									<div class="form-group col-md-8">
										<label for="pwd" class="form-label1">Address<span class="">*</span> :</label>
										<br/>
										123 ABC Street
									</div>
									<div class="form-group col-md-4">
										<label for="pwd" class="form-label1">Time Zone<span class="">*</span> :</label>
										<br/>
										IST
									</div>
									<div class="form-group col-md-4">
										<label for="pwd" class="form-label1">Telephone* :</label>
										<br/>
										+91-2536989698
									</div>
									<div class="form-group col-md-4">
										<label for="pwd" class="form-label1">Mobile Number<span class="">*</span> :</label>
										<br/>
										+91-1234567890
									</div>
									<div class="form-group col-md-4">
										<label for="pwd" class="form-label1">Website:</label>
										<br/>
										N/A
									</div>
									<div class="form-group col-md-4">
										<label for="pwd" class="form-label1">Your Logo:</label>
										<br/>
										N/A
										
									</div>
									<div class="form-group col-md-4">
										<label for="pwd" class="code_text1 form-label1">Agent Code<span class="">*</span> :</label>
										<br/>
										9123689
									</div>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
		</section>
		
		<section class="content">
			<div class="row">
				<section class="col-lg-12 connectedSortable">
					<div id="notify_msg_div"></div>
					<div class="box">
						<div class="box-header">
						   <h3 class="box-title">Lists Of Sub Agents Of "Sandy Smith"</h3>
						</div>
						<div class="box-body">
							<div id="" class="col-md-12">
									<div id="" class="row">
										<div id="" class="col-md-8"></div>
										<div id="" class="col-md-4">
											<div id="" class="row">
												<a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>create_new_agent"><button class="status_checks btn btn-success btn-md" type="submit" style="float:right; margin-bottom:10px;" value="" onclick="" >CREATE NEW SUB AGENT</button></a>
											</div>
										</div>
									</div>
								</div>
							<div id="example1_wrapper" class="dataTables_wrapper form-inline" role="grid">
								<div id="no-more-tables">
									<table aria-describedby="example1_info" id="example" class="table table-bordered table-striped dataTable">
										<thead>
											<tr role="row">
												<th>#</th>
												<th>Name</th>
												<th>Code</th>
												<th>Email</th>
												<th>Phone Number</th>
												<th>Company Name</th>
												<th>Performance</th>
												<th>Status</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody aria-relevant="all" aria-live="polite" role="alert">
											<tr class="odd">
												<td class="  sorting_1">1</td>
												<td class=" ">Sandy Smith</td>
												<td class=" ">023569</td>
												<td class=" ">sandy@gmail.com</td>
												<td class=" ">11-1234-4568</td>
												<td class=" ">Booking International</td>
												<td class=" ">
													<b>ACTIVE BOOKING: 5
													<br/>
													COMPLETE BOOKING: 20
													<br/>
													CANCELLED BOOKING: 4
													<br/>
													TOTAL EARNING: $2000.00</b>
												</td>
												<td class=" "><a style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" id="status" data-id="" class="status_checks btn-success">Active</a></td>
												<td class=" " data-title="Action">
													<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>create_new_booking" title = "Create New Bookings"><i class="fa fa-plus-square fa-1x" ></i></a>&nbsp;&nbsp;
													<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>bookings" title = "Lists Of Bookings"><i class="fa fa-plane fa-1x" ></i></a>&nbsp;&nbsp;
													<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>accounting" title = "Accounting"><i class="fa fa-usd fa-1x" ></i></a>&nbsp;&nbsp;
													<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>edit_agent" title = "Edit Agent"><i class="fa fa-pencil-square-o fa-1x" ></i></a>&nbsp;&nbsp;
													<a href = "#"  title = "Delete Agent" onclick = ""><i class="fa fa fa-trash-o fa-1x"></i></a>
												</td>
											</tr>
											<tr class="odd">
												<td class="  sorting_1">2</td>
												<td class=" ">John Smith</td>
												<td class=" ">369856</td>
												<td class=" ">johny@gmail.com</td>
												<td class=" ">11-1234-6933</td>
												<td class=" ">Confort Booking Services</td>
												<td class=" ">
													<b>ACTIVE BOOKING: 5
													<br/>
													COMPLETE BOOKING: 20
													<br/>
													CANCELLED BOOKING: 4
													<br/>
													TOTAL EARNING: $2000.00</b>
												</td>
												<td class=" "><a style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" id="status" data-id="" class="status_checks btn-success">Active</a></td>
												<td class=" " data-title="Action">
													<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>create_new_booking" title = "Create New Bookings"><i class="fa fa-plus-square fa-1x" ></i></a>&nbsp;&nbsp;
													<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>bookings" title = "Lists Of Bookings"><i class="fa fa-plane fa-1x" ></i></a>&nbsp;&nbsp;
													<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>accounting" title = "Accounting"><i class="fa fa-usd fa-1x" ></i></a>&nbsp;&nbsp;
													<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>edit_agent" title = "Edit Agent"><i class="fa fa-pencil-square-o fa-1x" ></i></a>&nbsp;&nbsp;
													<a href = "#"  title = "Delete Agent" onclick = ""><i class="fa fa fa-trash-o fa-1x"></i></a>
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