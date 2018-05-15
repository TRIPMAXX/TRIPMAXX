<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['AGENT_SESSION_DATA']['id'], DOMAIN_NAME_PATH.'');
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo DEFAULT_PAGE_TITLE ;?> Destination Management Company</title>	
		<?php require_once('meta.php');?>
		<script type="text/javascript" src="js/twitterfeed.js"></script> 
		<script src="js/jquery.canvasjs.min.js"></script>
		<!-- JAVASCRIPT CODE -->
		<script type="text/javascript">
		<!--
		$(function(){
			$("#agent_login").validationEngine();
		});
		</script>
		<script>
		$(document).ready(function(){
			$('.date-inpt').datepicker();
			$('.custom-select').customSelect();
			$(function() {
				$(document.body).on('appear', '.fly-in', function(e, $affected) {
					$(this).addClass("appeared");
				});
				$('.fly-in').appear({force_process: true});
			});

			$(".owl-slider").owlCarousel({
				loop:true,
				margin:28,
				responsiveClass:true,
				responsive:{
			0:{
				items:1,
				nav:true
			},
			620:{
				items:2,
				nav:true
			},
			900:{
				items:3,
				nav:false
			},
			1120:{
				items:4,
				nav:true,
				loop:false
			}
		}
			});
			$slideHover();
		});
		</script>
		<script>
			window.onload = function () {
				// Construct options first and then pass it as a parameter
				var options1 = {
					animationEnabled: true,
					title: {
						text: "Monthly Sales Chart"
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
	<body class="index-page">
		<!-- TOP HEADER -->
		<?php require_once('header.php');?>
		<!-- TOP HEADER -->
		<div class="main-cont">
			<div class="body-padding">
				<div class="banner_slider" style="background:url(img/banner4.jpg)no-repeat center center/cover;">
					<div class="banner_slider_text">
						DASHBOARD
					</div>
				</div>
				<?php require_once('login_menu.php');?>
				<div id="" class="container">
					<div class="mp-offesr">
						<div class="wrapper-padding-a">
							<div class="offer-slider">
								<div class="row rows">
									<div class="col-md-6">
										<div class="box box-info">
											<div id="resizable1" style="height: 370px;border:1px solid gray;">
												<div id="chartContainer1" style="height: 100%; width: 100%;"></div>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="box box-info">
											<div id="resizable2" style="height: 370px;border:1px solid gray;">
												<div id="chartContainer2" style="height: 100%; width: 100%;"></div>
											</div>
										</div>
									</div>
								</div>
								<div class="row rows">
									<div class="col-md-12">
											<div class="bopoking_hedaing">
												<h1>Booking History</h1>
											</div>
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
												  <th>Customer Details</th>
												  <th>Check In & Check Out Date</th>
												 <th>Destination</th>
												  <th>Service Include</th>
												  <th>Total Quotation</th>
												  <th>Status</th>
												  <th>Action</th>
												  
											   </tr>
											   </thead>
											   <tr>
												  <td data-title="#">1</td>
												 <td data-title="Order Details">
													Sandeep Sing <br>
													E: candeep@gmail.com <br>
													P: +91 2365986989
												  </td>
												  <td data-title="Distance">
													 01/03/2018 - 04/03/2018
												  </td>
												  <td data-title="Status">
													Thailand, Bangkok for 3 Days 1 Person
												  </td>
												  <td data-title="Status">
													Hotel, Transfer, Tour
												  </td>
												  
												   <td data-title="Status">
													$8475.00
												  </td>
												  <td data-title="Status">
													<span class="btn-warning" style="display: inline-block; padding: 10px 10px;font-weight: bold;">PENDING</span>
												  </td>
												 
												  <td class=" " data-title="Action">
																<a href = "view_booking_details.html" title = "View Booking Details"><i class="fa fa-eye fa-1x" ></i></a>&nbsp;&nbsp;
																<a href = "javascript:void(0);" title = "Generate Vouchers"><i class="fa fa-file fa-1x" ></i></a>&nbsp;&nbsp;
																<a href = "edit_booking_new.html" title = "Edit Booking"><i class="fa fa-pencil-square-o fa-1x" ></i></a>&nbsp;&nbsp;
																<a href = "#"  title = "Delete Booking" onclick = ""><i class="fa fa fa-trash-o fa-1x"></i></a>
															</td>
											   </tr>
											</table>
										 </div>
										</div>
									</div>
										</div>
									</div><br>
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