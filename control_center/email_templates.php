<?php
require_once('loader.inc');
require_once('core/microservices/email_template.php');
tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');

if(isset($_GET['id']) && $_GET['id']!='') {
	if(tools::delete(TM_EMAIL_TEMPLATES, 'WHERE id=:id', array(':id'=>base64_decode($_GET['id'])))) {
		$_SESSION['SET_TYPE'] = 'success';
		$_SESSION['SET_FLASH'] = 'Email Template has been deleted successfully';
		tools::module_redirect(DOMAIN_NAME_PATH_ADMIN.'email_templates');
	} else {
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = 'Invalid Data';
	}
}
$email_templates = tools::find("all", TM_EMAIL_TEMPLATES, $value='id, template_title, template_subject, template_body, status', "WHERE 1 ORDER BY template_title ASC", array());
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>LISTS OF EMAIL TEMPLATES</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	$(document).ready(function() {
		$('#example').DataTable();
	} );

	function delete_email_template(id) {
		if(confirm('Are your sure you would like to delete it?')) {
			window.location.href = '<?php echo(DOMAIN_NAME_PATH_ADMIN);?>email_templates?id='+id;
		}
	}
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
				<h1>Lists Of Email Templates</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Lists Of Email Templates </li>
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
													<th>Template Title</th>
													<th>Subject Line</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody aria-relevant="all" aria-live="polite" role="alert">
												<?php
												if($email_templates) {
													$sl_number = 1;
													foreach($email_templates AS $email_template) {
												?>
												<tr class="odd">
													<td class="  sorting_1"><?php echo($sl_number);?></td>
													<td class=" "><?php echo($email_template['template_title']);?></td>
													<td class=" "><?php echo($email_template['template_subject']);?></td>
													<td class=" " data-title="Action">
														<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>edit_email_template?id=<?php echo(base64_encode($email_template['id']));?>" title = "Edit Email Template"><i class="fa fa-pencil-square-o fa-1x" ></i></a>&nbsp;&nbsp;
														<a href = "#"  title = "Delete Email Templates" onclick = "delete_email_template('<?php echo(base64_encode($email_template['id']));?>');"><i class="fa fa fa-trash-o fa-1x"></i></a>
													</td>
												</tr>
												<?php
													$sl_number++;
													}
												}
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