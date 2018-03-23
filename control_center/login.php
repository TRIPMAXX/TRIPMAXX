<?php
require_once('loader.inc');

$white_list_array = array('username', 'password', 'remember_me', 'token', 'status', 'btn_login');
$verify_token = "edit_email_template";

if(isset($_POST['btn_login']))
{
	$object_control_center = new control_center();
	if($object_control_center->control_center_login()) {
		if(tools::verify_token($white_list_array, $_POST, $verify_token)) {
			if(isset($_POST['remember_me']) && $_POST['remember_me'] == 1) {
				if(@$_COOKIE['tripmaxx_uname'] == '' && @$_COOKIE['tripmaxx_pwd'] == '') {
					$year = time() + 31536000;
					setcookie('tripmaxx_uname', $_POST['username'], $year);
					setcookie('tripmaxx_pwd',$_POST['password'], $year);
				}
			} else {
				$year = time() - 31536000;
				setcookie('tripmaxx_uname', '', $year);
				setcookie('tripmaxx_pwd','', $year);
				unset($_COOKIE['tripmaxx_uname']);
				unset($_COOKIE['tripmaxx_pwd']);
			}

			if($_SESSION['SESSION_DATA']['status'] == 1) {
				tools::module_redirect(DOMAIN_NAME_PATH_ADMIN.'dashboard');
			} else {
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH'] = 'Your account is inactive. Please contact DMC.';
			}
			
		} else {
			$_SESSION['SET_TYPE'] = 'error';
			$_SESSION['SET_FLASH'] = 'Access token mismatch. Please reload the page & try again.';
		}
	} else {
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = 'Invalid Username or Password.';
	}
}
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
			jQuery("#form_signin").validationEngine();
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
			<p class="login-box-msg"><b>Please Sign in</b></p>
			<form name = "form_signin" id = "form_signin" method = "POST" action = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>login">
			<div class="form-group">
				<input type = "text" name = "username" id = "username" class = "form-control validate[required]"  placeholder = "Username / Email Address" value = "<?php echo((isset($_POST['username']) && $_POST['username']!='') ? $_POST['username'] : @$_COOKIE['tripmaxx_uname']);?>" tabindex="1" maxlength = "100" autocomplete = "off" />
			</div>
			<div class="form-group">
				<input type = "password" class = "form-control validate[required]" id = "password" name = "password" placeholder = "Password" tabindex = "2" maxlength = "20" value = "<?php echo(@$_COOKIE['tripmaxx_pwd']);?>" autocomplete = "off" />
			</div>
			<div class="row">
				<div class="col-xs-8">    
					<div class="checkbox">
						<label>
							<input type = "checkbox" name = "remember_me" id = "remember_me" value = "1" <?php echo((isset($_COOKIE['tripmaxx_uname']) && $_COOKIE['tripmaxx_uname']!='') ? 'checked' : '');?> tabindex = "3" /> Remember Me
						</label>
					</div>                        
				</div>
				<div class="col-xs-4">
					<input type = "hidden" name = "token" value = "<?php echo(tools::generateFormToken($verify_token)); ?>" />
					<input type = "hidden" name = "status" id = "" value = "" />
					<button type = "submit" id = "btn_login" name = "btn_login" class = "btn btn-primary btn-block btn-flat" tabindex = "4">Sign In</button>
				</div>
			</div>
			</form>
			<a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>forgot_password" tabindex="5">I forgot my password</a><br/>
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