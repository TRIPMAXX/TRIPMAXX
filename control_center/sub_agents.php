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
			if(isset($_GET['agent_id']) && $_GET['agent_id']!=""):
				$post_data['data']=$_GET;
				$post_data_str=json_encode($post_data);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."agent/delete.php");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				$return_data = curl_exec($ch);
				curl_close($ch);
				$return_data_arr=json_decode($return_data, true);
				if(!isset($return_data_arr['status'])):
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH']="Some error has been occure during execution.";
				elseif($return_data_arr['status']=="success"):
					$_SESSION['SET_TYPE'] = 'success';
					$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
				else:
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
				endif;
				header("location:sub_agents?gsa_id=".$_GET['gse_id']);
				exit;
			endif;
			$post_data['data']=$_GET;
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
			$gsa_data=array();
			if(!isset($return_data_arr['status'])):
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH']="Some error has been occure during execution.";
				header("location:gsas");
				exit;
			elseif($return_data_arr['status']=="success"):
				$gsa_data=$return_data_arr['results'];
				$post_data['data']=array('parent_id'=>$gsa_data['id']);
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
				$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
				header("location:gsas");
				exit;
			endif;
		else:
			$_SESSION['SET_TYPE'] = 'error';
			$_SESSION['SET_FLASH'] = $autentication_data->msg;
		endif;
	else:
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = "We are having some problem to authorize api.";
	endif;
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>LISTS OF SUB AGENTS</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	$(document).ready(function() {
		$('#example').DataTable();
	} );
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
			<h1><?= $gsa_data['first_name']." ".($gsa_data['middle_name']!="" ? $gsa_data['middle_name']." " : "").$gsa_data['last_name'];?> Details</h1>
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
			<div class="row">
				<div class="col-md-12">
					<div class="box box-primary">
						<div class="col-md-12 row">
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
			<div class="row">
				<section class="col-lg-12 connectedSortable">
					<div class="box">
						<div class="box-header">
						   <h3 class="box-title">Lists Of Sub Agents Of "<?= $gsa_data['first_name']." ".($gsa_data['middle_name']!="" ? $gsa_data['middle_name']." " : "").$gsa_data['last_name'];?>"</h3>
						</div>
						<div class="box-body">
							<div id="" class="col-md-12">
									<div id="" class="row">
										<div id="" class="col-md-8"></div>
										<div id="" class="col-md-4">
											<div id="" class="row">
												<a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>create_new_agent?gse_id=<?php echo base64_encode($gsa_data['id']);?>"><button class="status_checks btn btn-success btn-md" type="button" style="float:right; margin-bottom:10px;" value="">CREATE NEW SUB AGENT</button></a>
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
												<td class=" " style="display:none;">
												<?php
												if($agent_val['image']!=""):
												?>
													<img src = "<?php echo(AGENT_IMAGE_PATH.$agent_val['image']);?>" border = "0" alt = "" width = "80" height = "80" onerror="this.remove;"/>
												<?php
												else:
													echo "N/A";
												endif;
												?>
												</td>
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
													<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>create_new_booking" title = "Create New Bookings"><i class="fa fa-plus-square fa-1x" ></i></a>&nbsp;&nbsp;
													<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>bookings" title = "Lists Of Bookings"><i class="fa fa-plane fa-1x" ></i></a>&nbsp;&nbsp;
													<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>accounting?agent_id=<?php echo base64_encode($agent_val['id']);?>" title = "Accounting"><i class="fa fa-usd fa-1x" ></i></a>&nbsp;&nbsp;
													<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>edit_agent?agent_id=<?php echo base64_encode($agent_val['id']);?>&gse_id=<?php echo base64_encode($gsa_data['id']);?>" title = "Edit Agent"><i class="fa fa-pencil-square-o fa-1x" ></i></a>&nbsp;&nbsp;
													<!-- <a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>sub_agents?agent_id=<?php echo base64_encode($agent_val['id']);?>&gse_id=<?php echo base64_encode($gsa_data['id']);?>"  title = "Delete Agent" onclick = "confirm('Are you sure you want to delete this item?') ? '' : event.preventDefault()"><i class="fa fa fa-trash-o fa-1x"></i></a> -->
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
	<!-- BODY --> 
	<!-- FOOTER -->
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'footer.php');?>
	<!-- FOOTER -->
      </div>
   </body>
</html>