<?php
require_once('loader.inc');
tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
	$autentication_data=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."authorized.php"));
	if(isset($autentication_data->status)):
		if($autentication_data->status=="success"):
			$post_data['token']=array(
				"token"=>$autentication_data->results->token,
				"token_timeout"=>$autentication_data->results->token_timeout,
				"token_generation_time"=>$autentication_data->results->token_generation_time
			);
			$post_data['data']['agent_count']='Y';
			$post_data['data']['status']=1;
			$post_data_str=json_encode($post_data);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."agent/read.php");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$return_data = curl_exec($ch);
			curl_close($ch);
			$return_data_arr=json_decode($return_data, true);
			//print_r($return_data);exit;
			$agent_data=array();
			if(!isset($return_data_arr['status'])):
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH']="Some error has been occure during execution.";
			elseif($return_data_arr['status']=="success"):
				$agent_data_count=$return_data_arr['results']['no_of_agents'];
				unset($return_data_arr['results']['no_of_agents']);
				$agent_data=$return_data_arr['results'];
			else:
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
			endif;
			///////////////////////////
			$post_data['data']['status']=2;
			$post_data_str=json_encode($post_data);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."agent/read.php");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$return_data_inactive = curl_exec($ch);
			curl_close($ch);
			$return_data_arr_inactive=json_decode($return_data_inactive, true);
			//print_r($return_data);exit;
			$agent_data_lists=array();
			if(!isset($return_data_arr_inactive['status'])):
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH']="Some error has been occure during execution.";
			elseif($return_data_arr_inactive['status']=="success"):
				unset($return_data_arr_inactive['results']['no_of_agents']);
				$agent_data_lists=$return_data_arr_inactive['results'];
				//print_r($agent_data_lists);exit;
			else:
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH'] = $return_data_arr_inactive['msg'];
			endif;
		else:
			$_SESSION['SET_TYPE'] = 'error';
			$_SESSION['SET_FLASH'] = $autentication_data->msg;
		endif;
	else:
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = "We are having some problem to authorize api.";
	endif;

	$autentication_data_booking=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.BOOKING_API_PATH."authorized.php"));
	if(isset($autentication_data_booking->status)):
		if($autentication_data_booking->status=="success"):
			$post_data_booking['token']=array(
				"token"=>$autentication_data_booking->results->token,
				"token_timeout"=>$autentication_data_booking->results->token_timeout,
				"token_generation_time"=>$autentication_data_booking->results->token_generation_time
			);
			//print_r($post_data_booking);exit;
			$post_data_str_booking=json_encode($post_data_booking);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.BOOKING_API_PATH."booking/dashboard.php");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_booking);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$return_data_booking = curl_exec($ch);
			curl_close($ch);
			//print_r($return_data_booking);exit;
			$booking_details_list=array();
			$return_data_arr_booking=json_decode($return_data_booking, true);
			if(!isset($return_data_arr_booking['status'])):
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH']="Some error has been occure during execution.";
			elseif($return_data_arr_booking['status']=="success"):
				$booking_details_list=$return_data_arr_booking['results'];
				//print_r($booking_details_list);exit;
			else:
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH'] = $return_data_arr_booking['msg'];
			endif;
			$post_data_booking['data']['latest']="Y";
			$post_data_str_booking=json_encode($post_data_booking);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.BOOKING_API_PATH."booking/read.php");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_booking);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$return_data_latest_booking = curl_exec($ch);
			curl_close($ch);
			//print_r($return_data_latest_booking);exit;
			$latest_booking_details_list=array();
			$return_data_arr_latest_booking=json_decode($return_data_latest_booking, true);
			if(!isset($return_data_arr_booking['status'])):
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH']="Some error has been occure during execution.";
			elseif($return_data_arr_latest_booking['status']=="success"):
				$latest_booking_details_list=$return_data_arr_latest_booking['results'];
				//print_r($latest_booking_details_list);exit;
			else:
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH'] = $return_data_arr_latest_booking['msg'];
			endif;
		endif;
	else:
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = $autentication_data->msg;
	endif;
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
						<?php 
						if(!empty($agent_data)):
							foreach($agent_data as $agent_val):
								//print_r($agent_val);
								$flag=false;
								if(!empty($booking_details_list['booking_per_agent'])):
									foreach($booking_details_list['booking_per_agent'] as $booking_per_agent):
										if($agent_val['id']==$booking_per_agent['agent_id']):
											$flag=true;
						?>
						{ label: "<?php echo $agent_val['first_name'].($agent_val['middle_name']!=''?' '.$agent_val['middle_name']:'')." " .$agent_val['last_name']?>", y: <?php echo $booking_per_agent['count_val']?> },
						<?php
										endif;
									endforeach;
								endif;
								if($flag==false):
						?>
						{ label: "<?php echo $agent_val['first_name'].($agent_val['middle_name']!=''?' '.$agent_val['middle_name']:'')." " .$agent_val['last_name']?>", y: 0 },
						<?php
								endif;
							endforeach;
						endif;
						?>
						/*{ label: "Agent 1", y: 10 },
						{ label: "Agent 2", y: 6 },
						{ label: "Agent 3", y: 14 },
						{ label: "Agent 4", y: 18 },
						{ label: "Agent 5", y: 12 },
						{ label: "Agent 6", y: 19 },
						{ label: "Agent 7", y: 30 },
						{ label: "Agent 8", y: 40 },
						{ label: "Agent 9", y: 55 },
						{ label: "Agent 10", y: 5 }*/
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
						
						<?php 
						if(!empty($booking_details_list['booking_per_year'])):
							foreach($booking_details_list['booking_per_year'] as $booking_per_year):
						?>
							{ label: "<?php echo $booking_per_year['year']?>", y: <?php echo $booking_per_year['count_val']?> },
						<?php
							endforeach;
						endif;
						?>
						/*{ label: "2017", y: 8 },
						{ label: "2017", y: 10 },
						{ label: "2018", y: 25 }*/
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
	function change_status(agent_id, cur)
	{
		$.ajax({
			url:"<?= DOMAIN_NAME_PATH_ADMIN."ajax_agent_status_update";?>",
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
								<h3><?=(isset($booking_details_list['new_booking']) && $booking_details_list['new_booking']!=''?$booking_details_list['new_booking']:0)?></h3>
								<p>NEW BOOKING</p>
							</div>
							<a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>bookings" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>

					<div class="col-lg-3 col-xs-6">
						<div class="small-box bg-yellow">
							<div class="inner">
								<h3><?=(isset($booking_details_list['pending_booking']) && $booking_details_list['pending_booking']!=''?$booking_details_list['pending_booking']:0)?></h3>
								<p>PENDING BOOKING</p>
							</div>
							<a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>bookings" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>

					<div class="col-lg-3 col-xs-6">
						<div class="small-box bg-green">
							<div class="inner">
								<h3><?=(isset($booking_details_list['complete_booking']) && $booking_details_list['complete_booking']!=''?$booking_details_list['complete_booking']:0)?></h3>
								<p>COMPLETE BOOKING</p>
							</div>
							<a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>bookings" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					
					<div class="col-lg-3 col-xs-6">
						<div class="small-box bg-red">
							<div class="inner">
								<h3><?=(isset($agent_data_count) && $agent_data_count!=''?$agent_data_count:0)?></h3>
								<p>ACTIVE AGENT</p>
							</div>
							<a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>agents" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
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
					<?php
					if(isset($latest_booking_details_list) && !empty($latest_booking_details_list)):
					?>
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
											<?php
												foreach($latest_booking_details_list as $book_key=>$book_val):
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
													<td class=" " style="word-break:break-all;">
													<?php
													if($book_val['booking_type']=="agent"):
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
													<td class=" "><?php echo $destination_str;?><br><?php echo implode(", ", $service_arr);?></td>
													<td class=" ">
														<?php echo tools::module_date_format($book_val['checkin_date']);?>
													</td>
													<td class=" ">
														<a style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" data-id="" class="status_checks <?= $book_val['status']==1 ? "btn-success" : ($book_val['status']==2 ? "btn-danger" : "btn-warning");?>"><?= $book_val['status']==1 ? "Completed" : ($book_val['status']==2 ? "Rejected" : "Pending");?></a>
													</td>
													<td class=" " data-title="Action">
														<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>view_booking?booking_id=<?php echo base64_encode($book_val['id']);?>" title = "View Booking Details"><i class="fa fa-eye fa-1x" ></i></a>&nbsp;&nbsp;
													</td>
												</tr>
											<?php
												endforeach;
											?>
                                    </table>
                                 </div>
								</div>
							</div>
						</div>
					</section>
					<?php
					endif;
					if(!empty($agent_data_lists)):
					?>
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
										<?php
											foreach($agent_data_lists as $a_key=>$agent_list):
										?>
											<tr class="odd">
												<td class="  sorting_1"><?= $a_key+1;?></td>
												<td class=" "><?= $agent_list['first_name']." ".($agent_list['middle_name']!="" ? $agent_list['middle_name']." " : "").$agent_list['last_name'];?></td>
												<td class=" "><?= $agent_list['email_address'];?><br><?= $agent_list['telephone'];?></td>
												<td class=" "><?php echo tools::module_date_format($agent_list['creation_date'],"Y-m-d H:i:s");?></td>
												<td class=" ">
													<a style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" data-id="" class="status_checks <?= $agent_list['status']==1 ? "btn-success" : "btn-danger";?>" onclick="change_status(<?= $agent_list['id'];?>, $(this))"><?= $agent_list['status']==1 ? "Active" : "Inactive";?></a>
												</td>
												<td class=" " data-title="Action">
													<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>bookings?agent_id=<?php echo base64_encode($agent_list['id']);?>" title = "Lists Of Bookings"><i class="fa fa-plane fa-1x" ></i></a>&nbsp;&nbsp;
													<?php if(isset($agent_list['payment_type']) && $agent_list['payment_type']!='cash'){;?>
													<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>accounting?agent_id=<?php echo base64_encode($agent_list['id']);?>" title = "Accounting"><i class="fa fa-usd fa-1x" ></i></a>&nbsp;&nbsp;
													<?php };?>
												</td>
											</tr>
										<?php
											endforeach;
										?>
                                    </table>
                                 </div>
								</div>
							</div>
						</div>
					</section>
					<?php
					endif;
					?>
					<!-- <section class="col-lg-12 connectedSortable">
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
					</section> -->
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