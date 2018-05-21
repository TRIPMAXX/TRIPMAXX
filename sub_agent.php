<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['AGENT_SESSION_DATA']['id'], DOMAIN_NAME_PATH.'');
	if(isset($_GET['del_agent_id']) && $_GET['del_agent_id']!=""):
	endif;
	$agent_data = tools::find("all", TM_AGENT, '*', "WHERE type=:type AND parent_id=:parent_id ", array(":type"=>"A", ':parent_id'=>$_SESSION['AGENT_SESSION_DATA']['id']));
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo DEFAULT_PAGE_TITLE ;?> Destination Management Company</title>	
		<?php require_once('meta.php');?>
		<script type="text/javascript" src="js/twitterfeed.js"></script> 
		<script src="js/jquery.canvasjs.min.js"></script>
		<link rel="stylesheet" href="<?php echo(DOMAIN_NAME_PATH);?>css/jquery.dataTables.min.css" />
		<script src="<?php echo(DOMAIN_NAME_PATH);?>js/jquery.dataTables.min.js"></script>
		<script type="text/javascript">
		<!--
			$(function() {
				$('#example').DataTable();
			});
			function change_status(agent_id, cur)
			{
				$.ajax({
					url:"<?= DOMAIN_NAME_PATH."ajax_agent_status_update.php";?>",
					type:"post",
					data:{
						agent_id:agent_id
					},
					beforeSend:function(){
						//cur.removeClass("btn-success").removeClass("btn-danger");
						//cur.text("");
						cur.hide();
					},
					dataType:"json",
					success:function(response){
						//console.log(response);
						cur.show();
						if(response.status=="success")
						{
							showSuccess(response.msg);
							cur.removeClass("btn-success").removeClass("btn-danger");
							if(response.results.status==1)
							{
								cur.addClass("btn-success");
								cur.text("Active");
							}
							else
							{
								cur.addClass("btn-danger");
								cur.text("Inactive");
							}
						}
						else
						{
							showError(response.msg);
						}
					},
					error:function(){
						cur.show();
						showError("We are having some problem. Please try later.");
					}
				});
			}
		//-->
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
	</head>
	<body class="index-page">
		<!-- TOP HEADER -->
		<?php require_once('header.php');?>
		<!-- TOP HEADER -->
		<div class="main-cont">
			<div class="body-padding">
				<div class="banner_slider" style="background:url(img/banner4.jpg)no-repeat center center/cover;">
					<div class="banner_slider_text">
						Sub Agents
					</div>
				</div>
				<?php require_once('login_menu.php');?>
				<?php
				$gsa_data=$find_agent_data;
				?>
				<div id="" class="container">
					<section class="content">
						<div class="row rows">
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
						<div class="row rows">
							<div class="col-md-12">
								<div id="notify_msg_div"></div>
							</div>
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
						<div class="row rows">
							<div class="col-md-12">
								<div class="box box-primary">
									<div class="col-md-12 row rows">
										<div class="box-header">
										   <h3 class="box-title">Details</h3>
										</div>
										<div class="box-body">
											<div id="" class="row rows">
												<div class="form-group col-md-4">
													<label for="pwd" class="form-label1">Company Name :</label>
													<br/>
													<?php echo(isset($gsa_data['company_name']) && $gsa_data['company_name']!='' ? $gsa_data['company_name'] : "N/A");?>
												</div>
												<div class="form-group col-md-4">
													<label for="pwd" class="form-label1">Company Accounting Name :</label>
													<br/>
													<?php echo(isset($gsa_data['accounting_name']) && $gsa_data['accounting_name']!='' ? $gsa_data['accounting_name'] : "N/A");?>
												</div>
												
												<div class="form-group col-md-4">
													<label for="pwd" class="form-label1">First Name :</label>
													<br/>
													<?php echo(isset($gsa_data['first_name']) && $gsa_data['first_name']!='' ? $gsa_data['first_name'] : "N/A");?>
												</div>
												<div class="form-group col-md-4">
													<label for="pwd" class="form-label1">Middle Name :</label>
													<br/>
													<?php echo(isset($gsa_data['middle_name']) && $gsa_data['middle_name']!='' ? $gsa_data['middle_name'] : "N/A");?>
												</div>
														
												<div class="form-group col-md-4">
													<label for="pwd" class="form-label1">Last Name :</label>
													<br/>
													<?php echo(isset($gsa_data['last_name']) && $gsa_data['last_name']!='' ? $gsa_data['last_name'] : "N/A");?>
												</div>
												<div class="form-group col-md-4">
													<label for="pwd" class="form-label1">Email :</label>
													<br/>
													<?php echo(isset($gsa_data['email_address']) && $gsa_data['email_address']!='' ? $gsa_data['email_address'] : "N/A");?>
												</div>
												<div class="form-group col-md-4">
													<label for="pwd" class="form-label1">Designation :</label>
													<br/>
													<?php echo(isset($gsa_data['designation']) && $gsa_data['designation']!='' ? $gsa_data['designation'] : "N/A");?>
												</div>
												<div class="form-group col-md-4">
													<label for="pwd" class="form-label1">IATA Status :</label>
													<br/>
													<?php echo(isset($gsa_data['iata_status']) && $gsa_data['iata_status']==1 ? "Approve" : "Not Approve");?>
												</div>
												<div class="form-group col-md-4">
													<label for="pwd" class="form-label1">Nature of Business:</label>
													<br/>
													<?php echo(isset($gsa_data['nature_of_business']) && $gsa_data['nature_of_business']!='' ? $gsa_data['nature_of_business'] : "N/A");?>
												</div>
												<div class="form-group col-md-4">
													<label for="pwd" class="form-label1">Preferred Currency :</label>
													<br/>
													<?php echo(isset($gsa_data['currency_code']) && $gsa_data['currency_code']!='' ? $gsa_data['currency_code'] : "N/A");?>
												</div>
												<div class="form-group col-md-4">
													<label for="pwd" class="form-label1">Country :</label>
													<br/>
													<?php echo(isset($gsa_data['co_name']) && $gsa_data['co_name']!='' ? $gsa_data['co_name'] : "N/A");?>
												</div>
												<div class="form-group col-md-4">
													<label for="pwd" class="form-label1">State :</label>
													<br/>
													<?php echo(isset($gsa_data['s_name']) && $gsa_data['s_name']!='' ? $gsa_data['s_name'] : "N/A");?>
												</div>
												<div class="form-group col-md-4">
													<label for="pwd" class="form-label1">City :</label>
													<br/>
													<?php echo(isset($gsa_data['ci_name']) && $gsa_data['ci_name']!='' ? $gsa_data['ci_name'] : "N/A");?>
												</div>
												<div class="form-group col-md-4">
													<label for="pwd" class="form-label1">Pincode/Zipcode/Postcode :</label>
													<br/>
													<?php echo(isset($gsa_data['zipcode']) && $gsa_data['zipcode']!='' ? $gsa_data['zipcode'] : "N/A");?>
												</div>
												<div class="form-group col-md-4">
													<label for="pwd" class="form-label1">Time Zone :</label>
													<br/>
													<?php echo(isset($gsa_data['timezone']) && $gsa_data['timezone']!='' ? "GMT".$gsa_data['timezone'] : "N/A");?>
												</div>
												<div class="form-group col-md-12">
													<label for="pwd" class="form-label1">Address :</label>
													<br/>
													<?php echo(isset($gsa_data['address']) && $gsa_data['address']!='' ? nl2br($gsa_data['address']) : "N/A");?>
												</div>
												
												<div class="form-group col-md-4">
													<label for="pwd" class="form-label1">Telephone* :</label>
													<br/>
													<?php echo(isset($gsa_data['telephone']) && $gsa_data['telephone']!='' ? $gsa_data['telephone'] : "N/A");?>
												</div>
												<div class="form-group col-md-4">
													<label for="pwd" class="form-label1">Mobile Number :</label>
													<br/>
													<?php echo(isset($gsa_data['mobile_number']) && $gsa_data['mobile_number']!='' ? $gsa_data['mobile_number'] : "N/A");?>
												</div>
												<div class="form-group col-md-4">
													<label for="pwd" class="form-label1">Website:</label>
													<br/>
													<?php echo(isset($gsa_data['website']) && $gsa_data['website']!='' ? $gsa_data['website'] : "N/A");?>
												</div>
												<div class="form-group col-md-4">
													<label for="pwd" class="form-label1">Your Logo:</label>
													<br/>
													<?php
													if($gsa_data['image']!=""):
													?>
														<img src = "<?php echo(AGENT_IMAGE_PATH.$gsa_data['image']);?>" border = "0" alt = "" width = "80" height = "80" onerror="this.remove;"/>
													<?php
													else:
														echo "N/A";
													endif;
													?>
												</div>
												<div class="form-group col-md-4">
													<label for="pwd" class="code_text1 form-label1">Agent Code :</label>
													<br/>
													<?php echo(isset($gsa_data['code']) && $gsa_data['code']!='' ? $gsa_data['code'] : "N/A");?>
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
						<div class="row rows">
							<section class="col-lg-12">
								<div class="box">
									<div class="box-body">
										<div id="" class="col-md-12">
											<div id="" class="row rows">
												<div id="" class="col-md-8"></div>
												<div id="" class="col-md-4">
													<div id="" class="row rows">
														<a href="<?php echo(DOMAIN_NAME_PATH);?>create_new_agent.php"><button class="status_checks btn btn-success btn-md" type="button" style="float:right; margin-bottom:10px;" value="">CREATE NEW SUB AGENT</button></a>
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
													<?php
													if(!empty($agent_data)):
														foreach($agent_data as $agent_key=>$agent_val):
													?>
														<tr class="odd">
															<td class="  sorting_1"><?= $agent_key+1;?></td>
															<td class=" "><?= $agent_val['first_name']." ".($agent_val['middle_name']!="" ? $agent_val['middle_name']." " : "").$agent_val['last_name'];?></td>
															<td class=" "><?= $agent_val['code'];?></td>
															<td class=" "><?= $agent_val['email_address'];?></td>
															<td class=" "><?= $agent_val['telephone'];?></td>
															<td class=" "><?= $agent_val['company_name'];?></td>
															<td class=" ">
																<b>ACTIVE BOOKING: 5
																<br/>
																COMPLETE BOOKING: 20
																<br/>
																CANCELLED BOOKING: 4
																<br/>
																TOTAL EARNING: $2000.00</b>
															</td>
															<td class=" ">
																<a style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" data-id="" class="status_checks <?= $agent_val['status']==1 ? "btn-success" : "btn-danger";?>" onclick="change_status(<?= $agent_val['id'];?>, $(this))"><?= $agent_val['status']==1 ? "Active" : "Inactive";?></a>
															</td>
															<td class=" " data-title="Action">
																<a href = "<?php echo(DOMAIN_NAME_PATH);?>create_new_booking.php" title = "Create New Bookings"><i class="fa fa-plus-square fa-1x" ></i></a>&nbsp;&nbsp;
																<a href = "<?php echo(DOMAIN_NAME_PATH);?>booking.php?sub_agent_id=<?php echo base64_encode($agent_val['id']);?>" title = "Lists Of Bookings"><i class="fa fa-plane fa-1x" ></i></a>&nbsp;&nbsp;
																<?php if(isset($agent_val['payment_type']) && $agent_val['payment_type']!='cash'){;?>
																<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>accounting?agent_id=<?php echo base64_encode($agent_val['id']);?>" title = "Accounting"><i class="fa fa-usd fa-1x" ></i></a>&nbsp;&nbsp;
																<?php };?>
																<a href = "<?php echo(DOMAIN_NAME_PATH);?>edit_agent.php?agent_id=<?php echo base64_encode($agent_val['id']);?>" title = "Edit Agent"><i class="fa fa-pencil-square-o fa-1x" ></i></a>&nbsp;&nbsp;
															</td>
														</tr>
													<?php
														endforeach;
													else:
													?>
														<tr align="center">
															<td colspan="100%">No record found</td>
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
			</div>
		</div>
		<!-- FOOTER -->
		<?php require_once('footer.php');?>
		<!-- FOOTER -->
	</body>
</html>