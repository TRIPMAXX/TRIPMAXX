<?php
require_once('../loader.inc');
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>SIGNIN</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
		$(document).ready(function() {
			$.backstretch(["<?PHP echo(CONTROL_CENTER_IMAGE_PATH);?>bg1.jpg", "<?PHP echo(CONTROL_CENTER_IMAGE_PATH);?>bg2.jpg", "<?PHP echo(CONTROL_CENTER_IMAGE_PATH);?>bg3.jpg"], {duration: 1500, fade: 750});
			jQuery("#log_in").validationEngine();
			jQuery("#username").focus();
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
			<p class="login-box-msg"><b>Please Sign in</b></p>
			<form name="form_signin" id="form_signin" method="POST" action="<?php echo(DOMAIN_NAME_PATH_HOTEL);?>dashboard">
				<div class="form-group">
					<input type="text" name="username" id="username" class="form-control validate[required]"  placeholder="Username" value = "" tabindex="1" maxlength="100" autocomplete="off" />
				</div>
				<div class="form-group">
					<input type="password" class="form-control validate[required]" id="password" name="password" placeholder="Password" tabindex="2" maxlength="20" value = "" autocomplete="off" />
				</div>
				<div class="row">
					<div class="col-xs-8">    
						<div class="checkbox">
							<label>
								<input type="checkbox" name="remember_me" id="remember_me" value="1" tabindex="3" /> Remember Me
							</label>
						</div>                        
					</div>
					<div class="col-xs-4">
						<button type="submit" id="btn_login" name="btn_login" class="btn btn-primary btn-block btn-flat" tabindex="4">Sign In</button>
					</div>
				</div>
			</form>
			<a href="<?php echo(DOMAIN_NAME_PATH_HOTEL);?>forgot_password" tabindex="5">I forgot my password</a><br/>
			<div align = "center" style = "margin-top:10px;">
				<font style = "color:#111;font-weight:bold;">&copy <a href = "#" title = "" target = "_blank" style = "color:#111;">TRIPMAXX</a></font>
			</div>
		</div>
	</div>
</body>
</html>