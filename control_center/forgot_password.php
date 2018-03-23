<?php
require_once('loader.inc');
require_once('core/microservices/email_template.php');

$white_list_array = array('email_address', 'token', 'btn_forgot_password');
$verify_token = "forgot_password";

$object_control_center = new control_center();

if(isset($_POST['btn_forgot_password'])) {
	if($user_data = tools::find("first", TM_DMC, $value='id, first_name, last_name, email_address, username, phone_number', "WHERE email_address = '".tools::stripcleantohtml($_POST['username'])."' OR username = '".tools::stripcleantohtml($_POST['username'])."'", array())) {
		$password = tools::create_password(5);
		$encrypted_password = tools::hash_password($password);
		tools::update(TM_DMC, 'password=:password', 'WHERE id=:id', array(':password'=>tools::stripcleantohtml($encrypted_password), ':id'=>$user_data['id']));
		$email_template = tools::find("first", TM_EMAIL_TEMPLATES, $value='template_subject, template_body', "WHERE id = 3", array());
		$email_body = tools::recurring_replace(array('[FIRST_NAME]', '[LAST_NAME]', '[USERNAME]', '[PASSWORD]'), array($user_data['first_name'], $user_data['last_name'], $user_data['username'], $password), $email_template['template_body']);
		@tools::Send_SMTP_Mail($user_data['email_address'], $general_settings['from_email_address'], '', $email_template['template_subject'], $email_body);
		$_SESSION['SET_TYPE'] = 'success';
		$_SESSION['SET_FLASH'] = 'We have emailed you temporary access details. Please check.';
	} else {
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = 'We do not have this email address registered with us. Please check.';
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>FORGOT PASSWORD</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
		$(document).ready(function() {
			$.backstretch(["<?PHP echo(CONTROL_CENTER_IMAGE_PATH);?>bg1.jpg", "<?PHP echo(CONTROL_CENTER_IMAGE_PATH);?>bg2.jpg", "<?PHP echo(CONTROL_CENTER_IMAGE_PATH);?>bg3.jpg"], {duration: 1500, fade: 750});
			jQuery("#form_forgot_password").validationEngine();
			jQuery("#username").focus();
		});
	</script>
	<!-- JAVASCRIPT CODE -->
</head>
<body>
	<div id="notify_msg_div"></div>
	<div class="login-box">
		<div class="login-box-body">
			
			<div class="login-logo">
				<b><img src="<?PHP echo(CONTROL_CENTER_IMAGE_PATH);?>logo.png"></b>
			</div>
			<p class="login-box-msg">Please enter your email address to receive your new password</p>
			<form name = "form_forgot_password" id = "form_forgot_password" method = "POST" action = "<?PHP echo(DOMAIN_NAME_PATH_ADMIN);?>forgot_password">
			<div class="form-group">
				<input type = "text" name = "username" id = "username" class = "form-control validate[required] custom[email]" placeholder = "Email"   data-errormessage-value-missing = "Email is required!" value = "" tabindex = "1" autocomplete = "off" />
			</div>
			<div class="row">
				<div class="col-xs-4">
					<input type = "hidden" name = "token" value = "<?php echo(tools::generateFormToken($verify_token)); ?>" />
					<button type = "submit" class = "btn btn-primary btn-block btn-flat" id = "btn_forgot_password" name = "btn_forgot_password" tabindex = "2" autocomplete = "off" />SUBMIT</button>
				</div>
			</div>
			</form>
			<a href="<?PHP echo(DOMAIN_NAME_PATH_ADMIN);?>login" tabindex = "3">Back to Login</a><br/>
			<div align = "center" style = "margin-top:10px;">
			<font style = "color:#111;font-weight:bold;">&copy <a href = "#" title = "" target = "_blank" style = "color:#111;">TRIPMAXX</a></font>
		</div>
		</div>
		
	</div>
</body>
</html>
<?php
if(isset($_SESSION['SET_FLASH']))
{
	if($_SESSION['SET_TYPE']=='error')
	{
		echo "<script type='text/javascript'>showError('".$_SESSION['SET_FLASH']."');</script>";
	}
	if($_SESSION['SET_TYPE']=='success')
	{
		echo "<script type='text/javascript'>showSuccess('".$_SESSION['SET_FLASH']."');</script>";
	}
}
unset($_SESSION['SET_FLASH']);
unset($_SESSION['SET_TYPE']);
$db=NULL;
?>