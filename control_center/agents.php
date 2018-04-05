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
				header("location:agents");
				exit;
			endif;
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
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>LISTS OF AGENTS</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	$(function() {
		$('#example').DataTable();
	});
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
			<h1>Lists Of Agents</h1>
			<ol class="breadcrumb">
				<li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">Lists Of Agents </li>
			</ol>
		</section>
		<section class="content">
			<div class="row">
				<section class="col-lg-12 connectedSortable">
					<div id="notify_msg_div"></div>
					<div class="box">
						<div class="box-body">
							<div id="" class="col-md-12">
								<div id="" class="row">
									<div id="" class="col-md-8"></div>
									<div id="" class="col-md-4">
										<div id="" class="row">
											<a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>create_new_agent"><button class="status_checks btn btn-success btn-md" type="submit" style="float:right; margin-bottom:10px;" value="" onclick="" >CREATE NEW AGENT</button></a>
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
												<th>Logo</th>
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
												<td class=" ">
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
													<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>accounting" title = "Accounting"><i class="fa fa-usd fa-1x" ></i></a>&nbsp;&nbsp;
													<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>edit_agent?agent_id=<?php echo base64_encode($agent_val['id']);?>" title = "Edit Agent"><i class="fa fa-pencil-square-o fa-1x" ></i></a>&nbsp;&nbsp;
													<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>agents?agent_id=<?php echo base64_encode($agent_val['id']);?>"  title = "Delete Agent" onclick = "confirm('Are you sure you want to delete this item?') ? '' : event.preventDefault()"><i class="fa fa fa-trash-o fa-1x"></i></a>
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