<?php
require_once('loader.inc');
$white_list_array = array('email_address', 'password', 'remember_me', 'token', 'status', 'btn_login');
$verify_token = "login_supplier";
if(isset($_SESSION['SESSION_DATA_SUPPLIER']) && !empty($_SESSION['SESSION_DATA_SUPPLIER']))
{
	$_SESSION['SET_TYPE'] = 'error';
	$_SESSION['SET_FLASH'] = 'You are already logged in.';
	tools::module_redirect(DOMAIN_NAME_PATH_SUPPLIER.'bookings');
	exit;
}
if(isset($_GET['auto_login_id']) && $_GET['auto_login_id']!="")
{
	session_destroy();
	session_start();
	$auto_login_id=base64_decode($_GET['auto_login_id']);
	$supplier_id=str_replace(array(SECURITY_SALT, "supplier", AUTO_LOGIN_SECURITY_KEY), array("", "", ""), $auto_login_id);
	$object_control_center = new supplier_control_center();
	if($object_control_center->supplier_auto_login($supplier_id)) {
		if($_SESSION['SESSION_DATA_SUPPLIER']['status'] == 1) {
			if(isset($_GET['booking_id']) && $_GET['booking_id']!=""):
				tools::module_redirect(DOMAIN_NAME_PATH_SUPPLIER.'view_booking.php?booking_id='.$_GET['booking_id']);
			else:
				tools::module_redirect(DOMAIN_NAME_PATH_SUPPLIER.'bookings');
			endif;
		} else {
			unset($_SESSION['SESSION_DATA_SUPPLIER']);
			$_SESSION['SET_TYPE'] = 'error';
			$_SESSION['SET_FLASH'] = 'Your account is inactive. Please contact DMC.';
		}
	}else {
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = 'Invalid login details.';
	}
}
if(isset($_POST['btn_login']))
{ 
	$object_control_center = new supplier_control_center();
	if($object_control_center->supplier_control_center_login()) {
		if(tools::verify_token($white_list_array, $_POST, $verify_token)) {
			if(isset($_POST['remember_me']) && $_POST['remember_me'] == 1) {
				if(@$_COOKIE['tripmaxx_uname_supplier'] == '' && @$_COOKIE['tripmaxx_pwd'] == '') {
					$year = time() + 31536000;
					setcookie('tripmaxx_uname_supplier', $_POST['email_address'], $year);
					setcookie('tripmaxx_pwd_supplier',$_POST['password'], $year);
				}
			} else {
				$year = time() - 31536000;
				setcookie('tripmaxx_uname_supplier', '', $year);
				setcookie('tripmaxx_pwd_supplier','', $year);
				unset($_COOKIE['tripmaxx_uname_supplier']);
				unset($_COOKIE['tripmaxx_pwd_supplier']);
			}

			if($_SESSION['SESSION_DATA_SUPPLIER']['status'] == 1) {
				tools::module_redirect(DOMAIN_NAME_PATH_SUPPLIER.'bookings');
			} else {
				unset($_SESSION['SESSION_DATA_SUPPLIER']);
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
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER_SUPPLIER);?>SIGNIN</title>
	<?php require_once(SUPPLIER_CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
		$(document).ready(function() {
			$.backstretch(["<?PHP echo(SUPPLIER_CONTROL_CENTER_IMAGE_PATH);?>bg1.jpg", "<?PHP echo(SUPPLIER_CONTROL_CENTER_IMAGE_PATH);?>bg2.jpg", "<?PHP echo(SUPPLIER_CONTROL_CENTER_IMAGE_PATH);?>bg3.jpg"], {duration: 1500, fade: 750});
			jQuery("#form_signin").validationEngine();
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
				<b><img src="<?PHP echo(SUPPLIER_CONTROL_CENTER_IMAGE_PATH);?>logo.png"></b>
			</div>
			<p class="login-box-msg"><b>Please Sign in</b></p>
			<form name="form_signin" id="form_signin" method="POST" action="<?php echo(DOMAIN_NAME_PATH_SUPPLIER);?>login">
				<div class="form-group">
					<input type="text" name="email_address" id="email_address" class="form-control validate[required, custom[email]]"  placeholder="Email Address" value = "<?php echo((isset($_POST['email_address']) && $_POST['email_address']!='') ? $_POST['email_address'] : @$_COOKIE['tripmaxx_uname_supplier']);?>" tabindex="1" maxlength="100" autocomplete="off" />
				</div>
				<div class="form-group">
					<input type="password" class="form-control validate[required]" id="password" name="password" placeholder="Password" tabindex="2" maxlength="20" value = "<?php echo(@$_COOKIE['tripmaxx_pwd_supplier']);?>" autocomplete="off" />
				</div>
				<div class="row">
					<div class="col-xs-8">    
						<div class="checkbox">
							<label>
								<input type="checkbox" name="remember_me" id="remember_me" value="1" tabindex="3" <?php echo((isset($_COOKIE['tripmaxx_uname_supplier']) && $_COOKIE['tripmaxx_uname_supplier']!='') ? 'checked' : '');?> /> Remember Me
							</label>
						</div>                        
					</div>
					<div class="col-xs-4">
						<input type = "hidden" name = "status" id = "" value = "" />
						<input type = "hidden" name = "token" value = "<?php echo(tools::generateFormToken($verify_token)); ?>" />
						<button type="submit" id="btn_login" name="btn_login" class="btn btn-primary btn-block btn-flat" tabindex="4">Sign In</button>
					</div>
				</div>
			</form>
			<a href="<?php echo(DOMAIN_NAME_PATH_SUPPLIER);?>forgot_password" tabindex="5">I forgot my password</a><br/>
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