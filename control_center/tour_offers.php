<?php
require_once('../loader.inc');
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>LIST(S) OF TOUR OFFERS</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	$(document).ready(function() {
		$('#example').DataTable();
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
				<h1>Lists Of Tour Offers For "Tour Name"</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Lists Of Tour Offers</li>
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
												<a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>create_new_tour_offer"><button class="status_checks btn btn-success btn-md" type="submit" style="float:right; margin-bottom:10px;" value="" onclick="" >CREATE NEW TOUR OFFER</button></a>
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
													<th>Offer Title</th>
													<th>Service Type</th>
													<th>Capacity</th>
													<th>Default Price / Person</th>
													<th>Status</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody aria-relevant="all" aria-live="polite" role="alert">
												<tr class="odd">
													<td class="  sorting_1">1</td>
													<td class=" ">Per Person (1-15 Pax)</td>
													<td class=" ">Share</td>
													<td class=" ">1-15</td>
													<td class=" ">$10.00</td>
													<td class=" "><a style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" id="status" data-id="" class="status_checks btn-success">Active</a></td>
													<td class=" " data-title="Action">
														<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>edit_tour_offer" title = "Edit Tour Offer"><i class="fa fa-pencil-square-o fa-1x" ></i></a>&nbsp;&nbsp;
														<a href = "#"  title = "Delete Tour Offer" onclick = ""><i class="fa fa fa-trash-o fa-1x"></i></a>
													</td>
												</tr>
												<tr class="odd">
													<td class="  sorting_1">1</td>
													<td class=" ">Per Person (1-15 Pax)</td>
													<td class=" ">Share</td>
													<td class=" ">1-15</td>
													<td class=" ">$10.00</td>
													<td class=" "><a style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" id="status" data-id="" class="status_checks btn-success">Active</a></td>
													<td class=" " data-title="Action">
														<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>edit_tour_offer" title = "Edit Tour Offer"><i class="fa fa-pencil-square-o fa-1x" ></i></a>&nbsp;&nbsp;
														<a href = "#"  title = "Delete Tour Offer" onclick = ""><i class="fa fa fa-trash-o fa-1x"></i></a>
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