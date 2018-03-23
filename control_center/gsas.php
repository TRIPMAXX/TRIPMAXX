<?php
require_once('../loader.inc');
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>LISTS OF AGENTS</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	$(document).ready(function() {
		$('#example').DataTable();
	} );
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
			<h1>Lists Of GSA</h1>
			<ol class="breadcrumb">
				<li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">Lists Of GSA </li>
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
											<a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>create_new_gsa"><button class="status_checks btn btn-success btn-md" type="submit" style="float:right; margin-bottom:10px;" value="" onclick="" >CREATE NEW GSA</button></a>
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
											<tr class="odd">
												<td class="  sorting_1">1</td>
												<td class=" "><img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>agent_logo/agent_logo1.jpg" border = "0" alt = "" width = "80" height = "80" /></td>
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
													<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>sub_agents" title = "Lists Of Sub Agents"><i class="fa fa-align-justify fa-1x" ></i></a>&nbsp;&nbsp;
													<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>bookings" title = "Lists Of Bookings"><i class="fa fa-plane fa-1x" ></i></a>&nbsp;&nbsp;
													<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>accounting" title = "Accounting"><i class="fa fa-usd fa-1x" ></i></a>&nbsp;&nbsp;
													<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>edit_gsa" title = "Edit Account"><i class="fa fa-pencil-square-o fa-1x" ></i></a>&nbsp;&nbsp;
													<a href = "#"  title = "Delete Agent" onclick = ""><i class="fa fa fa-trash-o fa-1x"></i></a>
												</td>
											</tr>
											<tr class="odd">
												<td class="  sorting_1">2</td>
												<td class=" "><img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>agent_logo/agent_logo2.gif" border = "0" alt = "" width = "80" height = "80" /></td>
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
													<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>sub_agents" title = "Lists Of Sub Agents"><i class="fa fa-align-justify fa-1x" ></i></a>&nbsp;&nbsp;
													<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>bookings" title = "Lists Of Bookings"><i class="fa fa-plane fa-1x" ></i></a>&nbsp;&nbsp;
													<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>accounting/'" title = "Accounting"><i class="fa fa-usd fa-1x" ></i></a>&nbsp;&nbsp;
													<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>edit_gsa" title = "Edit Agent"><i class="fa fa-pencil-square-o fa-1x" ></i></a>&nbsp;&nbsp;
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