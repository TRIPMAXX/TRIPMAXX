<?php
require_once('../loader.inc');
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>LISTS OF TOUR ATTRIBUTES</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	$(document).ready(function() {
		$('#example').DataTable();
	} );
	//-->
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
		
		<div class="content-wrapper">
			<section class="content-header">
			<h1>Lists Of Tour Attributes</h1>
			<ol class="breadcrumb">
				<li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">Lists Of Tour Attributes </li>
			</ol>
		</section>
		<section class="content">
			<div class="row">
				<section class="col-lg-12 connectedSortable">
					<div id="notify_msg_div"></div>
					<div class="box">
						<div class="box-body">
							<div id="example1_wrapper" class="dataTables_wrapper form-inline" role="grid">
								<div id="no-more-tables">
									<table aria-describedby="example1_info" id="example" class="table table-bordered table-striped dataTable">
										<thead>
											<tr role="row">
												<th>#</th>
												<th>Tour Type Title</th>
												<th>Status</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody aria-relevant="all" aria-live="polite" role="alert">
											<tr class="odd">
												<td class="  sorting_1">1</td>
												<td class=" ">Ticket Only</td>
												<td class=" "><a style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" id="status" data-id="" class="status_checks btn-success">Active</a></td>
												<td class=" " data-title="Action">
													<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>edit_tour_attribute" title = "Edit Attribute"><i class="fa fa-pencil-square-o fa-1x" ></i></a>&nbsp;&nbsp;
													<a href = "#"  title = "Delete Attribute" onclick = ""><i class="fa fa fa-trash-o fa-1x"></i></a>
												</td>
											</tr>
											<tr class="odd">
												<td class="  sorting_1">2</td>
												<td class=" ">Full Tour including Lunch</td>
												<td class=" "><a style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" id="status" data-id="" class="status_checks btn-success">Active</a></td>
												<td class=" " data-title="Action">
													<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>edit_tour_attribute" title = "Edit Attribute"><i class="fa fa-pencil-square-o fa-1x" ></i></a>&nbsp;&nbsp;
													<a href = "#"  title = "Delete Attribute" onclick = ""><i class="fa fa fa-trash-o fa-1x"></i></a>								
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
		<!-- BODY -->

	</div>
    
	<!-- FOOTER -->
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'footer.php');?>
	<!-- FOOTER -->

</div>
</body>
</html>