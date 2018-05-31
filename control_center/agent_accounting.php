<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
	$white_list_array = array('agent_type', 'agents', 'booking_type', 'booking_status', 'date_from', 'date_to', 'token', 'btn_submit', 'export_flag');
	$verify_token = "search_for_agents_accounting";
	$autentication_data=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."authorized.php"));
	if(isset($autentication_data->status)):
		if($autentication_data->status=="success"):
			$post_data['token']=array(
				"token"=>$autentication_data->results->token,
				"token_timeout"=>$autentication_data->results->token_timeout,
				"token_generation_time"=>$autentication_data->results->token_generation_time
			);
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
			$agent_data=array();
			if(!isset($return_data_arr['status'])):
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH']="Some error has been occure during execution.";
			elseif($return_data_arr['status']=="success"):
				$agent_data=$return_data_arr['results'];
			else:
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
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
			if(isset($_POST) && !empty($_POST)):
					//print_r($_POST);exit;
				if(tools::verify_token($white_list_array, $_POST, $verify_token)):
					if(isset($autentication_data->status)):
						if($autentication_data->status=="success"):
							$post_data['token']=array(
								"token"=>$autentication_data->results->token,
								"token_timeout"=>$autentication_data->results->token_timeout,
								"token_generation_time"=>$autentication_data->results->token_generation_time
							);
							$post_data['data']=$_POST;
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
							//print_r($return_data);exit;
							$return_data_arr=json_decode($return_data, true);
							$agent_data_ids=array();
							if(!isset($return_data_arr['status'])):
								$_SESSION['SET_TYPE'] = 'error';
								$_SESSION['SET_FLASH']="Some error has been occure during execution.";
							elseif($return_data_arr['status']=="success"):
								$agent_data_ids=$return_data_arr['results'];
								//print_r($agent_data_ids);exit;
							else:
								$_SESSION['SET_TYPE'] = 'error';
								$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
							endif;
						else:
							$_SESSION['SET_TYPE'] = 'error';
							$_SESSION['SET_FLASH'] = $autentication_data->msg;
						endif;
					else:
						$_SESSION['SET_TYPE'] = 'error';
						$_SESSION['SET_FLASH'] = "We are having some problem to authorize api.";
					endif;
					$post_data_booking['data']=$_POST;
					$post_data_booking['data']['agent_ids']=$agent_data_ids['agent_ids'];
					//print_r($post_data_booking);exit;
					$post_data_str_booking=json_encode($post_data_booking);
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_HEADER, false);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
					curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.BOOKING_API_PATH."booking/agent-accounting.php");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_booking);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					$return_data_booking = curl_exec($ch);
					curl_close($ch);
					//print_r($return_data_booking);exit;
					$return_data_arr_booking=json_decode($return_data_booking, true);
					$booking_details_list=array();
					if(!isset($return_data_arr_booking['status'])):
						$data['status'] = 'error';
						$data['msg']="Some error has been occure during execution.";
					elseif($return_data_arr_booking['status']=="success"):
						$booking_details_list=$return_data_arr_booking['results'];
						//print_r($booking_details_list);exit;
					else:
						$data['status'] = 'error';
						$data['msg'] = $return_data_arr_booking['msg'];
					endif;


					/*if(isset($_POST['export_flag']) && $_POST['export_flag']!=""):
						// file name for download
						unset($_POST['export_flag']);
						$fileName = "hotel_accounting_export_data" . date('Ymd') . ".xls";
						
						// headers for download
						header("Content-Disposition: attachment; filename=\"$fileName\"");
						header("Content-Type: application/vnd.ms-excel");

						echo "# \t QUOTATION NAME \t BOOKING TYPE \t NUMBER OF PERSON \t NUMBER OF DAYS \t CREATED BY \t DESTINATION \t HOTEL NAME \t ROOM TYPE \t HOTEL CHECK-IN DATE \t HOTEL CHECK-OUT DATE \t HOTEL PRICE". "\n";
						foreach($booking_details_list as $k => $row) {
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
							$checkin_date = strtotime($row['checkin_date']);
							$checkout_date = strtotime($row['checkout_date']);
							$datediff = $checkout_date - $checkin_date;
							$created_by ='';

							if($row['booking_type']=="personal"):
								$autentication_data_dmc=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.DMC_API_PATH."authorized.php"));
								if(isset($autentication_data_dmc->status)):
									if($autentication_data_dmc->status=="success"):
										$post_data_dmc['token']=array(
											"token"=>$autentication_data_dmc->results->token,
											"token_timeout"=>$autentication_data_dmc->results->token_timeout,
											"token_generation_time"=>$autentication_data_dmc->results->token_generation_time
										);
										$post_data_dmc['data']['dmc_id']=$row['dmc_id'];
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
											$created_by = $return_data_arr_dmc['results']['first_name']." ".$return_data_arr_dmc['results']['last_name'].", E: ".$return_data_arr_dmc['results']['email_address'].($return_data_arr_dmc['results']['phone_number']!="" ? ", P: ".$return_data_arr_dmc['results']['phone_number'] : "");
										else:
											//$data['status'] = 'error';
											//$data['msg'] = $return_data_arr_agent['msg'];
										endif;
									endif;
								else:
									//$data['status'] = 'error';
									//$data['msg'] = $autentication_data->msg;
								endif;
							elseif($row['booking_type']=="agent"):
								$autentication_data_agent=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."authorized.php"));
								if(isset($autentication_data_agent->status)):
									if($autentication_data_agent->status=="success"):
										$post_data_agent['token']=array(
											"token"=>$autentication_data_agent->results->token,
											"token_timeout"=>$autentication_data_agent->results->token_timeout,
											"token_generation_time"=>$autentication_data_agent->results->token_generation_time
										);
										$post_data_agent['data']['agent_id']=$row['agent_id'];
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
											$created_by = $return_data_arr_agent['results']['first_name']." ".$return_data_arr_agent['results']['last_name'].", E: ".$return_data_arr_agent['results']['email_address'].($return_data_arr_agent['results']['telephone']!="" ? ", P: ".$return_data_arr_agent['results']['telephone'] : "");
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



							echo ($k+1)."\t".$row['quotation_name']."\t".$row['booking_type']."\t".$number_of_person."\t ".round($datediff / (60 * 60 * 24))."\t ".$created_by."\n";
							if($row['booking_destination_list'])
							{
								foreach($row['booking_destination_list'] as $b)
								{					
									if(isset($autentication_data->status)):
										if($autentication_data->status=="success"):
											$post_data['token']=array(
												"token"=>$autentication_data->results->token,
												"token_timeout"=>$autentication_data->results->token_timeout,
												"token_generation_time"=>$autentication_data->results->token_generation_time
											);
											$post_data['data']['hotel_id']=$b['booking_hotel_list'][0]['hotel_id'];
											$post_data['data']['room_id']=$b['booking_hotel_list'][0]['room_id'];
											$post_data_str=json_encode($post_data);
											$ch = curl_init();
											curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
											curl_setopt($ch, CURLOPT_HEADER, false);
											curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
											curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
											curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."hotel/find-booked-hotel.php");
											curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
											curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
											$return_data = curl_exec($ch);
											curl_close($ch);
											$return_data_arr=json_decode($return_data, true);
											$hotel_data_name=array();
											if(!isset($return_data_arr['status'])):
												$_SESSION['SET_TYPE'] = 'error';
												$_SESSION['SET_FLASH']="Some error has been occure during execution.";
											elseif($return_data_arr['status']=="success"):
												$hotel_data_name=$return_data_arr;
												//$hotel_name=$hotel_data_name['find_hotel']['hotel_name']." (".$hotel_data_name['find_room']['room_type'].")";
											else:
												$_SESSION['SET_TYPE'] = 'error';
												$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
											endif;
										else:
											$_SESSION['SET_TYPE'] = 'error';
											$_SESSION['SET_FLASH'] = $autentication_data->msg;
										endif;
									else:
										$_SESSION['SET_TYPE'] = 'error';
										$_SESSION['SET_FLASH'] = "We are having some problem to authorize api.";
									endif;
									echo "\t\t\t\t\t\t".$b['ci_name']."\t".$hotel_data_name['find_hotel']['hotel_name']."\t ".$hotel_data_name['find_room']['room_type']."\t".tools::module_date_format($b['booking_hotel_list'][0]['booking_start_date'])."\t".tools::module_date_format($b['booking_hotel_list'][0]['booking_end_date'])."\t".$b['booking_hotel_list'][0]['price']." (".$row['currency_code'].")"."\n";
								}
							}

						}
						exit;
					endif;*/

				endif;
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
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>ACCOUNTING DETAILS FOR AGENTS</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
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
		
		$("#agent_type").change(function(){
			fetch_agent($(this).val());
		});
		<?php 
		if(isset($_POST['agent_type']) && $_POST['agent_type']!="")
		{
		?>
			fetch_agent(<?php echo $_POST['agent_type'];?>);
		<?php
		}
		?>
	} );
	function fetch_agent(payment_type)
	{
		$.ajax({
			url:"<?= DOMAIN_NAME_PATH_ADMIN."ajax_agent_fetch.php";?>",
			type:"post",
			data:{
				payment_type:payment_type
			},
			beforeSend:function(){
				$("#agents").html('<option value = "">-Select Agents-</option>');
			},
			dataType:"json",
			success:function(response){
				//console.log(response);
				if(response.status=="success")
				{
					if(response.results.length > 0)
					{
						$("#agents").append('<option value = "all">All</option>');
						$.each(response.results, function(agent_key, agent_val){
							$("#agents").append('<option value = "'+agent_val['id']+'">'+agent_val['first_name']+(agent_val['middle_name']!="" ? " "+agent_val['middle_name'] : "")+" "+agent_val['last_name']+" - "+agent_val['code']+'</option>');
						});
					}
				}
				else
				{
					//showError(response.msg);
				}
			},
			error:function(){
				//showError("We are having some problem. Please try later.");
			}
		}).done(function(){
			$("#agents").val('<?php echo(isset($_POST['agents']) && $_POST['agents']!="" ? $_POST['agents'] : "");?>');
		});;
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
			<h1>ACCOUNTING DETAILS FOR AGENT(S)</h1>
			<ol class="breadcrumb">
				<li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">Agent(s) Accounting </li>
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
										<div class="form-group col-md-6">
											<label for="email"><font color="#FF0000">*</font>Agent Type</label>
											<select class="form-control validate[required]" name = "agent_type" id = "agent_type" tabindex = "1">
												<option value = "all" <?php echo(isset($_POST['agent_type']) && $_POST['agent_type']=="all" ? 'selected="selected"' : '');?>>All</option>
												<option value = "credit" <?php echo(isset($_POST['agent_type']) && $_POST['agent_type']=="credit" ? 'selected="selected"' : '');?>>Credit</option>
												<option value = "cash" <?php echo(isset($_POST['agent_type']) && $_POST['agent_type']=="cash" ? 'selected="selected"' : '');?>>Cash</option>
											</select>
										</div>
										<div class="form-group col-md-6">
											<label for="email"><font color="#FF0000">*</font>Select Agents</label>
											<select class="form-control validate[required]" name = "agents" id = "agents" tabindex = "2">
												<option value = "">-Select Agents-</option>
												<option value = "all"<?php echo(isset($_POST['agents']) && $_POST['agents']=="all" ? 'selected="selected"' : '');?>>All</option>
											<?php
											if(isset($agent_data) && !empty($agent_data)):
												foreach($agent_data as $agent_key=>$agent_val):
											?>
												<option value = "<?php echo $agent_val['id'];?>" <?php echo(isset($_POST['agents']) && $_POST['agents']== $agent_val['id'] ? 'selected="selected"' : '');?>><?php echo $agent_val['first_name'].($agent_val['middle_name']!="" ? " ".$agent_val['middle_name'] : "")." ".$agent_val['last_name']." - ".$agent_val['code'];?></option>
											<?php
												endforeach;
											endif;
											?>
											</select>
										</div>
										<div class="form-group col-md-6">
											<label for="email"><font color="#FF0000">*</font>Booking Type</label>
											<select class="form-control validate[required]" name = "booking_type" id = "booking_type" tabindex = "3">
												<option value = "A" <?php echo(isset($_POST['booking_type']) && $_POST['booking_type']=="A" ? 'selected="selected"' : '');?>>All</option>
												<option value = "R" <?php echo(isset($_POST['booking_type']) && $_POST['booking_type']=="R" ? 'selected="selected"' : '');?>>Ready Package Booking</option> 
												<option value = "C" <?php echo(isset($_POST['booking_type']) && $_POST['booking_type']=="C" ? 'selected="selected"' : '');?>>Custom Package Booking</option>
											</select>
										</div>
										<div class="form-group col-md-6">
											<label for="email"><font color="#FF0000">*</font>Booking Status</label>
											<select class="form-control validate[required]" name = "booking_status" id = "booking_status" tabindex = "4">
												<option value = "A" <?php echo(isset($_POST['booking_status']) && $_POST['booking_status']=="A" ? 'selected="selected"' : '');?>>All</option>
												<option value = "C" <?php echo(isset($_POST['booking_status']) && $_POST['booking_status']=="C" ? 'selected="selected"' : '');?>>Confirmed</option>
												<option value = "R" <?php echo(isset($_POST['booking_status']) && $_POST['booking_status']=="R" ? 'selected="selected"' : '');?>>Cancelled</option>
											</select>
										</div>
										<div class="form-group col-md-6">
											<label for="email">Date From</label>
											<input type="text" class="form-control"  value="<?php echo(isset($_POST['date_from']) && $_POST['date_from']!="" ? $_POST['date_from'] : '');?>" name="date_from" id="date_from" placeholder="Date From" tabindex = "5" />
										</div>
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Date To</label>
											<div class="input-icon right">
												<input type="text" class="form-control"  placeholder = "Date To" name="date_to" id="date_to" tabindex = "6" value="<?php echo(isset($_POST['date_to']) && $_POST['date_to']!="" ? $_POST['date_to'] : '');?>"/>
											</div>
										</div>
										<div class="form-group col-md-12">
											<input type="hidden" name="token" value="<?php echo(tools::generateFormToken($verify_token)); ?>" />
											<input type="hidden" name="export_flag" id="export_flag" value="<?php echo(isset($_POST['export_flag']) && $_POST['export_flag']!="" ? $_POST['export_flag'] : '');?>" />
											<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex = "7">SEARCH</button>		
											<?php
											if(isset($booking_details_list) && !empty($booking_details_list)):
											?>
											<button type="button" id="export_to_excel" class="btn btn-primary pull-right" tabindex = "8">EXPORT TO EXCEL</button>
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
		if(isset($booking_details_list) && !empty($booking_details_list)):
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
												<th>Booking Type</th>
												<th>Created By</th>
												<th>Check In & Check Out Date</th>
												<th>Number Of Person</th>
												<th>Number Of Days</th>
												<th>Destination</th>
												<th>Hotel Details</th>
												<th>Total Price</th>
												<th>Status</th>
											</tr>
										</thead>
										<tbody aria-relevant="all" aria-live="polite" role="alert">
											<?php
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
													$hotel_name="";
													foreach($book_val['booking_destination_list'] as $dest_key=>$dest_val):
														if($destination_str!="")
															$destination_str.=", ";
														$destination_str.=$dest_val['ci_name'];
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
													<td class=" "><?php echo round($datediff / (60 * 60 * 24));?></td>
													<td class=" "><?php echo $destination_str;?></td>
													<td class=" ">
													<?php echo $hotel_name;?>
													</td>
													<td class=" "><?php echo $book_val['currency_code'].number_format($book_val['total_amount'], 2, ".", ",");?></td>
													<td class=" ">
														<a style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" data-id="" class="status_checks <?= $book_val['status']==1 ? "btn-success" : "btn-warning";?>"><?= $book_val['status']==1 ? "Completed" : "Pending";?></a>
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
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'footer.php');?>
	<!-- FOOTER -->
</div>
</body>
</html>