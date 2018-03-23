<?php
require_once('../loader.inc');
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
			jQuery("#email_address").focus();
		});
	</script>
	<!-- JAVASCRIPT CODE -->
</head>
<body>
	<div class="login-box">
		<div class="login-box-body">
			<div id="notify_msg_div"></div>
			<div class="login-logo">
				<b><img src="<?PHP echo(CONTROL_CENTER_IMAGE_PATH);?>logo.png"></b>
			</div>
			<p class="login-box-msg">Please enter your email address to receive your new password</p>
			<form name="form_forgot_password" id="form_forgot_password"method="post">
				<div class="form-group">
					<input type="text" name="email_address" id="email_address" class="form-control validate[required] custom[email]" placeholder="Email"   data-errormessage-value-missing="Email is required!" value="" tabindex = "1" autocomplete="off" />
				</div>
				<div class="row">
					<div class="col-xs-4">
						<button type="submit" class="btn btn-primary btn-block btn-flat" id="btn_login" name="btn_login" tabindex = "2" autocomplete="off" />SUBMIT</button>
					</div>
				</div>
			</form>
			<a href="<?PHP echo(DOMAIN_NAME_PATH_HOTEL);?>login" tabindex = "3">Back to Login</a><br/>
			<div align = "center" style = "margin-top:10px;">
			<font style = "color:#111;font-weight:bold;">&copy <a href = "#" title = "" target = "_blank" style = "color:#111;">TRIPMAXX</a></font>
		</div>
		</div>
		
	</div>
</body>
</html>
<script>
jQuery(document).ready(function(){
	jQuery("#forgot_pass").validationEngine();
});

</script>
