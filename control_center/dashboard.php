<?php
require_once('loader.inc');
tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>DASHBOARD</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script>
		window.onload = function () {
			// Construct options first and then pass it as a parameter
			var options1 = {
				animationEnabled: true,
				title: {
					text: "Agent Sales Chart"
				},
				data: [{
					type: "column", //change it to line, area, bar, pie, etc
					legendText: "",
					showInLegend: false,
					dataPoints: [
						{ label: "Agent 1", y: 10 },
						{ label: "Agent 2", y: 6 },
						{ label: "Agent 3", y: 14 },
						{ label: "Agent 4", y: 18 },
						{ label: "Agent 5", y: 12 },
						{ label: "Agent 6", y: 19 },
						{ label: "Agent 7", y: 30 },
						{ label: "Agent 8", y: 40 },
						{ label: "Agent 9", y: 55 },
						{ label: "Agent 10", y: 5 }
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
				<h1>Dashboard</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Dashboard</li>
				</ol>
			</section>
			<section class="content">
				<div class="row">
					<div class="col-lg-3 col-xs-6">
						<div class="small-box bg-aqua">
							<div class="inner">
								<h3>100</h3>
								<p>NEW BOOKING</p>
							</div>
							<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>

					<div class="col-lg-3 col-xs-6">
						<div class="small-box bg-yellow">
							<div class="inner">
								<h3>200</h3>
								<p>PENDING BOOKING</p>
							</div>
							<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>

					<div class="col-lg-3 col-xs-6">
						<div class="small-box bg-green">
							<div class="inner">
								<h3>300</h3>
								<p>COMPLETE BOOKING</p>
							</div>
							<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					
					<div class="col-lg-3 col-xs-6">
						<div class="small-box bg-red">
							<div class="inner">
								<h3>20</h3>
								<p>NEW AGENT REQUEST</p>
							</div>
							<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
				</div>
				<!-- <div class = "row">
					<section class="col-lg-12 connectedSortable">
						<div class="box box-info">
							<div class="box-header">
								<i class="fa fa-cart-plus"></i>
								<h3 class="box-title">LATEST BOOKING REQUEST</h3>
								
							</div>
							<div class="box-body">
								<div class="box-body no-padding">
									<div id="no-more-tables">
                                    <table class="table table-condensed">
										<thead>
                                       <tr>
                                          <th style="width: 10px">#</th>
                                          <th>Agent</th>
                                          <th>Booking Details</th>
                                          <th>Booking Start Date</th>
                                          <th>Status</th>
                                          <th style="width: 40px">Action</th>
                                       </tr>
									   </thead>
                                       <tr>
                                          <td data-title="#">1</td>
                                          <td data-title="Customer">
                                             Santosh Jain
											 <br/>
											 <font color = "green"><b>CODE: 025368</b></font>
                                          </td>
                                          <td data-title="Order Details">
                                           Thiland Combo Package
										   <br/>
										   <font color = "red"><b>$5000.00</b></font>
                                          </td>
                                          <td data-title="Distance">
                                             07/03/2018
                                          </td>
                                         
                                          <td data-title="Status">
											<span class="btn-warning" style="display: inline-block; padding: 10px 10px;font-weight: bold;">PENDING</span>
                                          </td>
										 
                                          <td data-title="Action">
                                             <a href = "#" title = "View"><i class="fa fa-eye"></i></a>
                                          </td>
                                       </tr>
                                    </table>
                                 </div>
								</div>
							</div>
						</div>
					</section>
				</div> -->
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
					<section class="col-lg-12 connectedSortable">
						<div class="box box-info">
							<div class="box-header">
								<i class="fa fa-cart-plus"></i>
								<h3 class="box-title">LATEST BOOKING REQUEST</h3>
								
							</div>
							<div class="box-body">
								<div class="box-body no-padding">
									<div id="no-more-tables">
                                    <table class="table table-condensed">
										<thead>
                                       <tr>
                                          <th style="width: 10px">#</th>
                                          <th>Agent</th>
                                          <th>Booking Details</th>
                                          <th>Booking Start Date</th>
                                          <th>Status</th>
                                          <th style="width: 40px">Action</th>
                                       </tr>
									   </thead>
                                       <tr>
                                          <td data-title="#">1</td>
                                          <td data-title="Customer">
                                             Santosh Jain
											 <br/>
											 <font color = "green"><b>CODE: 025368</b></font>
                                          </td>
                                          <td data-title="Order Details">
                                           Thailand Combo Package
										   <br/>
										   <font color = "red"><b>$5000.00</b></font>
                                          </td>
                                          <td data-title="Distance">
                                             07/03/2018
                                          </td>
                                         
                                          <td data-title="Status">
											<span class="btn-warning" style="display: inline-block; padding: 10px 10px;font-weight: bold;">PENDING</span>
                                          </td>
										 
                                          <td data-title="Action">
                                             <a href = "#" title = "View"><i class="fa fa-eye"></i></a>
                                          </td>
                                       </tr>
                                    </table>
                                 </div>
								</div>
							</div>
						</div>
					</section>
					<section class="col-lg-12 connectedSortable">
						<div class="box box-info">
							<div class="box-header">
								<i class="fa fa-users"></i>
								<h3 class="box-title">PENDING AGENT APPROVAL</h3>
								
							</div>
							<div class="box-body">
								<div class="box-body no-padding">
									<div id="no-more-tables">
                                    <table class="table table-condensed">
										<thead>
                                       <tr>
                                          <th style="width: 10px">#</th>
                                          <th>Agent Name</th>
                                          <th>Contact Details</th>
                                          <th>Creation Date</th>
                                          <th>Status</th>
                                          <th style="width: 40px">Action</th>
                                       </tr>
									   </thead>
                                       <tr>
                                          <td data-title="#">1</td>
                                          <td data-title="Customer">
                                             Santosh Jain
                                          </td>
                                          <td data-title="Order Details">
                                           santosh@gmail.com
										   <br/>
										   <font color = "green"><b>+91-1234567890</b></font>
                                          </td>
                                          <td data-title="Distance">
                                             07/03/2018
                                          </td>
                                         
                                          <td data-title="Status">
											<span class="btn-warning" style="display: inline-block; padding: 10px 10px;font-weight: bold;">PENDING</span>
                                          </td>
										 
                                          <td data-title="Action">
                                             <a href = "#" title = "View"><i class="fa fa-eye"></i></a>
                                          </td>
                                       </tr>
                                    </table>
                                 </div>
								</div>
							</div>
						</div>
					</section>
					<section class="col-lg-12 connectedSortable">
						<div class="box box-info">
							<div class="box-header">
								<i class="fa fa-users"></i>
								<h3 class="box-title">PENDING SUPPLIER APPROVAL</h3>
								
							</div>
							<div class="box-body">
								<div class="box-body no-padding">
									<div id="no-more-tables">
                                    <table class="table table-condensed">
										<thead>
                                       <tr>
                                          <th style="width: 10px">#</th>
                                          <th>Supplier Name</th>
                                          <th>Contact Details</th>
                                          <th>Creation Date</th>
                                          <th>Status</th>
                                          <th style="width: 40px">Action</th>
                                       </tr>
									   </thead>
                                       <tr>
                                          <td data-title="#">1</td>
                                          <td data-title="Customer">
                                             Santosh Jain
                                          </td>
                                          <td data-title="Order Details">
                                           santosh@gmail.com
										   <br/>
										   <font color = "green"><b>+91-1234567890</b></font>
                                          </td>
                                          <td data-title="Distance">
                                             07/03/2018
                                          </td>
                                         
                                          <td data-title="Status">
											<span class="btn-warning" style="display: inline-block; padding: 10px 10px;font-weight: bold;">PENDING</span>
                                          </td>
										 
                                          <td data-title="Action">
                                             <a href = "#" title = "View"><i class="fa fa-eye"></i></a>
                                          </td>
                                       </tr>
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