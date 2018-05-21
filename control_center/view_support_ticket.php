<?php
require_once('loader.inc');
require_once('core/microservices/email_template.php');
tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
	$white_list_array = array('token', 'btn_submit');
	$verify_token = "create_new_support_ticket";

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
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>VIEW SUPPORT TICKETS</title>
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
				<h1>View Support Ticket</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">View Support Ticket</li>
				</ol>
			</section>
            <section class="content">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
							<div class="col-md-12 row">
								<div class="box-body">
									<div id="" class="row rows">
										<div class="col-md-3">
											<label for="account_type" class="form-label1">Ticket No :</label>
										</div>
										<div class="col-md-3">
											TM-12345678
										</div>
										<div class="col-md-3">
											<label for="account_type" class="form-label1">Priority :</label>
										</div>
										<div class="col-md-3">
											Low
										</div>
										<div class="clearfix"></div>
										<div class="col-md-3">
											<label for="account_type" class="form-label1">Post Date :</label>
										</div>
										<div class="col-md-3">
											21/05/2018
										</div>
										<div class="col-md-3">
											<label for="account_type" class="form-label1">Account Type :</label>
										</div>
										<div class="col-md-3">
											Hotel
										</div>
										<div class="clearfix"></div>
										<div class="col-md-3">
											<label for="account_type" class="form-label1">Account Name :</label>
										</div>
										<div class="col-md-3">
											ITC(SB)
										</div>
										<div class="col-md-3">
											<label for="account_type" class="form-label1">Heading :</label>
										</div>
										<div class="col-md-3">
											ITC(SB) Ticket 1
										</div>
										<div class="clearfix"></div>
										<div class="col-md-3">
											<label for="account_type" class="form-label1">Description :</label>
										</div>
										<div class="col-md-9">
											Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
										</div>
										<div class="clearfix"></div><br>
										<div class="form-group col-md-3">
											<label for="Attachments" class="control-label">Attachments :</label>
										</div>
										<div class="col-md-9">
										</div>
										<div class="clearfix"></div>
									</div>
								</div>

							</div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
			</section>
            <section class="content">
				<form name="form_new_agent" id="form_new_agent" method="POST" enctype="multipart/form-data">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
							<div class="col-md-12 row">
								<div class="box-body">
									<div id="" class="row rows">
										<div class="form-group col-md-12">
											<label for="Reply" class="form-label1">Reply<font color="#FF0000">*</font> :</label>
											<textarea class = "form-control validate[required]" name = "reply" id = "reply" placeholder = "Reply" tabindex = "4"><?php echo(isset($_POST['reply']) && $_POST['reply']!="" ? $_POST['reply'] : "");?></textarea>
										</div>
										<div class="form-group col-md-6">
											<label for="Attachments" class="control-label">Attachments <font color="#FF0000">*</font> :</label>
											<input type="file" class="validate[required]"  value="" name="attachments[]" id="attachments" placeholder="Attachments" tabindex = "6" multiple/>
											<br/>
											<font color = "red">SELECT MULTIPLE BY HOLDING CONTROL BUTTON.</font>
										</div>
										<div class="form-group col-md-6 radio_pad">
											<label for="status" class="form-label1">Status<font color="#FF0000">*</font> :</label>
											<select name="status" id="status" class="form-control form_input1 select_bg" tabindex="5">
												<option value="P" <?php echo(isset($_POST['status']) && $_POST['status']=="P" ? 'selected="selected"' : "");?>>Pending</option>
												<option value="C" <?php echo(isset($_POST['status']) && $_POST['status']=="C" ? 'selected="selected"' : "");?>>Complete</option>
											</select>
										</div>
										<div class="clearfix"></div>
									</div>
								</div>
								<div class="box-footer">
									<input type="hidden" name="token" value="<?php echo(tools::generateFormToken($verify_token)); ?>" />
									<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex="7">CREATE</button>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
				</form>
			</section>
		</div>
		<!-- BODY --> 

		<!-- FOOTER -->
		<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'footer.php');?>
		<!-- FOOTER -->

	</div>
</body>
</html>