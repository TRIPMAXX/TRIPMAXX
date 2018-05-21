<?php
require_once('loader.inc');
require_once('core/microservices/email_template.php');
tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');

/*if(isset($_GET['id']) && $_GET['id']!='') {
	if(tools::delete(TM_EMAIL_TEMPLATES, 'WHERE id=:id', array(':id'=>base64_decode($_GET['id'])))) {
		$_SESSION['SET_TYPE'] = 'success';
		$_SESSION['SET_FLASH'] = 'Email Template has been deleted successfully';
		tools::module_redirect(DOMAIN_NAME_PATH_ADMIN.'email_templates');
	} else {
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = 'Invalid Data';
	}
}
$email_templates = tools::find("all", TM_EMAIL_TEMPLATES, $value='id, template_title, template_subject, template_body, status', "WHERE 1 ORDER BY template_title ASC", array());*/
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>LISTS OF SUPPORT TICKETS</title>
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
				<h1>Lists Of Support Tickets</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Lists Of Support Tickets </li>
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
													<th>Ticket No</th>
													<th>Account Type</th>
													<th>Name</th>
													<th>Priority</th>
													<th>Posting Date</th>
													<th>Status</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody aria-relevant="all" aria-live="polite" role="alert">
												<tr class="odd">
													<td class="  sorting_1">1</td>
													<td class=" ">TM-12345678</td>
													<td class=" ">Hotel</td>
													<td class=" ">ITC(SB)</td>
													<td class=" ">High</td>
													<td class=" ">21/05/2018</td>
													<td class=" ">
														<a style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" data-id="" class="status_checks btn-warning">Pending</a>
													</td>
													<td class=" " data-title="Action">
														<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>view_support_ticket?id=" title = "Edit Email Template"><i class="fa fa-eye fa-1x" ></i></a>&nbsp;&nbsp;
														<!-- <a href = "#"  title = "Delete Email Templates" onclick = "delete_email_template('<?php echo(base64_encode($email_template['id']));?>');"><i class="fa fa fa-trash-o fa-1x"></i></a> -->
													</td>
												</tr>
												<tr class="odd">
													<td class="  sorting_1">1</td>
													<td class=" ">TM-12345678</td>
													<td class=" ">Hotel</td>
													<td class=" ">ITC(SB)</td>
													<td class=" ">High</td>
													<td class=" ">21/05/2018</td>
													<td class=" ">
														<a style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" data-id="" class="status_checks btn-success">Completed</a>
													</td>
													<td class=" " data-title="Action">
														<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>view_support_ticket?id=" title = "Edit Email Template"><i class="fa fa-eye fa-1x" ></i></a>&nbsp;&nbsp;
														<!-- <a href = "#"  title = "Delete Email Templates" onclick = "delete_email_template('<?php echo(base64_encode($email_template['id']));?>');"><i class="fa fa fa-trash-o fa-1x"></i></a> -->
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