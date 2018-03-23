<?php
require_once('loader.inc');
require_once('core/microservices/email_template.php');

tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');

$white_list_array = array('first_name', 'last_name', 'email_address', 'username', 'password', 'confirm_password', 'token', 'phone_number', 'status', 'id', 'id_custom', 'btn_update');
$verify_token = "profile";

if(isset($_POST['btn_update'])) {
	if(tools::verify_token($white_list_array, $_POST, $verify_token)) {
		if(tools::module_data_exists_check("(email_address = '".tools::stripcleantohtml($_POST['email_address'])."' OR username = '".tools::stripcleantohtml($_POST['username'])."') AND id<>".@$_SESSION['SESSION_DATA']['id']."", '', TM_DMC)) {
			$_SESSION['SET_TYPE'] = 'error';
			$_SESSION['SET_FLASH'] = 'This Email Address Or Username already exists.';
		} else {
			if($update_profile = tools::module_form_submission('', TM_DMC)) {
				if(isset($_POST['password']) && $_POST['password']!='') {
					$email_template = tools::find("first", TM_EMAIL_TEMPLATES, $value='template_subject, template_body', "WHERE id = 9", array());
					$email_body = tools::recurring_replace(array('[FIRST_NAME]', '[LAST_NAME]', '[USERNAME]', '[PASSWORD]'), array($_POST['first_name'], $_POST['last_name'], $_POST['username'], $_POST['password']), $email_template['template_body']);
					@tools::Send_SMTP_Mail($_POST['email_address'], $general_settings['from_email_address'], '', $email_template['template_subject'], $email_body);
				}
				$_SESSION['SET_TYPE'] = 'success';
				$_SESSION['SET_FLASH'] = 'Profile has been updated successfully.';
			} else {
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH'] = 'We are having some probem. Please try again later.';
			}
		}
	} else {
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = 'Access token mismatch. Please reload the page & try again.';
	}
}
$profile = tools::find("first", TM_DMC, $value='id, first_name, last_name, email_address, username, phone_number, creation_date, last_updated, status', "WHERE id = ".@$_SESSION['SESSION_DATA']['id']."", array());
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>MANAGE PROFILE</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script>
	jQuery(document).ready(function(){
		jQuery("#form_profile").validationEngine();
		jQuery("#first_name").focus();
	});
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

		<div class="content-wrapper">
			<section class="content-header">
				<h1>Edit Profile</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Edit Profile </li>
				</ol>
			</section>
			<section class="content">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
						<form role="form" name="form_profile" id="form_profile" method="POST" action = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>profile">
						<div class="col-md-12 row">
							<div class="box-body">
								<div class="form-group col-md-6">
									<label for="first_name">First Name&nbsp;<font color="#FF0000">*</font></label>
									<input type = "text" class = "form-control validate[required]"  value = "<?php echo((isset($_POST['first_name']) && $_POST['first_name']!='' ? $_POST['first_name'] : $profile['first_name']));?>" name = "first_name" id = "first_name" placeholder = "First Name" data-errormessage-value-missing="First Name is required!" tabindex = "1" />
								</div>
								<div class="form-group col-md-6">
									<label for="last_name">Last Name&nbsp;<font color="#FF0000">*</font></label>
									<input type = "text" class = "form-control validate[required]"  value = "<?php echo((isset($_POST['last_name']) && $_POST['last_name']!='' ? $_POST['last_name'] : $profile['last_name']));?>" name = "last_name" id = "last_name" placeholder = "Last Name" data-errormessage-value-missing="Last Name is required!" tabindex = "2" />
								</div>
								<div class="form-group col-md-6">
									<label for="email_address"><font color="#FF0000">*</font>Email Address</label>
									<input type = "text" class = "form-control validate[required] custom[email]"  value = "<?php echo((isset($_POST['email_address']) && $_POST['email_address']!='' ? $_POST['email_address'] : $profile['email_address']));?>" name = "email_address" id = "email_address" placeholder = "Email Address" data-errormessage-value-missing = "Email Address is required!" tabindex = "3" />
								</div>
								<div class="form-group col-md-6">
									<label for="username"><font color="#FF0000">*</font>Username</label>
									<input type = "text" class = "form-control validate[required]"  value = "<?php echo((isset($_POST['username']) && $_POST['username']!='' ? $_POST['username'] : $profile['username']));?>" name = "username" id = "username" placeholder = "Enter Username" data-errormessage-value-missing = "Username is required!" tabindex = "4" />
								</div>
								<div class="form-group col-md-6">
									<label for="password" class="control-label">Password</label>
									<div class="input-icon right">
										<input type = "password" class = "form-control "  placeholder = "Password" name = "password" id = "password" tabindex = "5" />
									</div>
									<div style = "margin-bottom:10px;color:#ff0000">Note: Leave blank if you do not wish to change existing password.</div>
								</div>
								<div class="form-group col-md-6">
									<label for="confirm_password" class="control-label">Confirm Password</label>
									<div class="input-icon right">
										<input type = "password" class = "form-control validate[equals[password]]"  placeholder = "Confirm Password" name = "confirm_password" id = "confirm_password" tabindex = "6" />
									</div>
									<div style = "margin-bottom:10px;color:#ff0000">Note: Leave blank if you do not wish to change existing password.</div>
								</div>
							</div>
						</div>
						<div class="col-md-12 row">
							<div class="box-footer">
								<input type = "hidden" name = "token" value = "<?php echo(tools::generateFormToken($verify_token)); ?>" />
								<input type = "hidden" name = "phone_number" value = "" />
								<input type = "hidden" name = "status" value = "1" />
								<input type = "hidden" name = "id" id = "id" value = "<?php echo($profile['id']);?>" />
								<input type = "hidden" name = "id_custom" id = "id_custom" value = "" />
								<button type = "submit" id = "btn_update" name = "btn_update" class = "btn btn-primary" tabindex = "7">UPDATE</button>
							</div>
						</div>
						</form>
						<div class="clearfix"></div>
					</div>
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