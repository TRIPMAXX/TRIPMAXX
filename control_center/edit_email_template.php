<?php
require_once('loader.inc');
require_once('core/microservices/email_template.php');
tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');

$white_list_array = array('template_title', 'status', 'template_subject','template_body', 'token', 'id', 'id_custom', 'btn_update');
$verify_token = "edit_email_template";

if(isset($_GET['id']) && $_GET['id']!='') {
	$email_template = tools::find("first", TM_EMAIL_TEMPLATES, $value='id, template_title, template_subject, template_body, status', "WHERE id=".base64_decode($_GET['id'])."", array());
} else {
	tools::module_redirect(DOMAIN_NAME_PATH_ADMIN.'email_templates');
}

if(isset($_POST['btn_update'])) {
	if(tools::verify_token($white_list_array, $_POST, $verify_token)) {
		if($update_profile = tools::module_form_submission('', TM_EMAIL_TEMPLATES)) {
			$_SESSION['SET_TYPE'] = 'success';
			$_SESSION['SET_FLASH'] = 'Email Template has been updated successfully.';
			tools::module_redirect(DOMAIN_NAME_PATH_ADMIN.'email_templates');
		} else {
			$_SESSION['SET_TYPE'] = 'error';
			$_SESSION['SET_FLASH'] = 'We could not update Email Template. Please try again later';
		}
	} else {
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = 'Access token mismatch. Please reload the page & try again.';
	}
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>EDIT EMAIL TEMPLATE</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script>
	jQuery(document).ready(function(){
		jQuery("#form_edit_email_template").validationEngine();
		jQuery("#template_title").focus();
	});
	</script>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	CKEDITOR.config.autoParagraph = false;
	CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
	CKEDITOR.config.shiftEnterMode = CKEDITOR.ENTER_BR;
	CKEDITOR.config.protectedSource.push(/<i[^>]*><\/i>/g);
	CKEDITOR.config.allowedContent = true;
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
               <h1>Edit Email Templates</h1>
               <ol class="breadcrumb">
                  <li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                  <li class="active">Edit Email Template</li>
               </ol>
            </section>
            <section class="content">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
							<form role = "form" name = "form_edit_email_template" id = "form_edit_email_template" method = "POST" action = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>edit_email_template?id=<?php echo($_GET['id']);?>">
							<div class="col-md-12 row">
								<div class="box-body">
									<div class="form-group col-md-6">
										<label for="inputName" class="control-label">Template Title&nbsp;<font color="#FF0000">*</font></label>
										<input type = "text" class = "form-control validate[required]"  value = "<?php echo($email_template['template_title']);?>" name = "template_title" id = "template_title" placeholder = "Template Title" tabindex = "1" />
									</div>
									<div class="form-group col-md-6">
										<label for="inputName" class="control-label">Status&nbsp;<font color="#FF0000">*</font></label>
										<select class = "form-control validate[required]"  name = "status" id = "status" tabindex = "2">
											<option value = "1" <?php echo($email_template['status'] == 1 ? 'selected' : '');?>>Active</option>
											<option value = "0" <?php echo($email_template['status'] == 0 ? 'selected' : '');?>>Inactive</option>
										</select>
									</div>
									<div class="form-group col-md-12">
										<label for="inputName" class="control-label">Subject Line&nbsp;<font color="#FF0000">*</font></label>
										<input type = "text" class = "form-control validate[required]"  value = "<?php echo($email_template['template_subject']);?>" name = "template_subject" id = "template_subject" placeholder = "Subject Line" tabindex = "3" />
									</div>
									<div class="form-group col-md-12">
										<label for="email">Email Body&nbsp;<font color="#FF0000">*</font></label>
										<textarea class = "form-control ckeditor validate[required]"  value = "" name = "template_body" id = "template_body" placeholder = "Email body" tabindex = "4"><?php echo($email_template['template_body']);?></textarea>
									</div>
								</div>
								<div class="col-md-12 row">
									<div class="box-footer">
										<input type = "hidden" name = "token" value = "<?php echo(tools::generateFormToken($verify_token)); ?>" />
										<input type = "hidden" name = "id" id = "id" value = "<?php echo($email_template['id']);?>" />
										<input type = "hidden" name = "id_custom" id = "id_custom" value = "" />
										<button type = "submit" id = "btn_update" name = "btn_update" class = "btn btn-primary" tabindex = "5">UPDATE</button>
									</div>
								</div>
							</div>
							</form>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
			</section>
		</div>

		<!-- FOOTER -->
		<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'footer.php');?>
		<!-- FOOTER -->
    </div>
</body>
</html>