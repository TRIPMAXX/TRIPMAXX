<?php
	require_once('loader.inc');
	if(isset($_SESSION['SESSION_DATA_HOTEL']) && !empty($_SESSION['SESSION_DATA_HOTEL']))
	{
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = 'You are already logged in.';
		tools::module_redirect(DOMAIN_NAME_PATH_HOTEL.'dashboard');
		exit;
	}
	$white_list_array = array('email_address', 'token', 'btn_forgot_password');
	$verify_token = "forgot_password";
	if(isset($_POST['btn_forgot_password'])) {
		if($user_data = tools::find("first", TM_HOTELS, '*', "WHERE email_address = :email_address", array(':email_address'=>tools::stripcleantohtml($_POST['email_address'])))) {
			$password = tools::create_password(5);
			$encrypted_password = tools::hash_password($password);
			tools::update(TM_HOTELS, 'password=:password', 'WHERE id=:id', array(':password'=>tools::stripcleantohtml($encrypted_password), ':id'=>$user_data['id']));
			$autentication_data=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.DMC_API_PATH."authorized.php"));
			if(isset($autentication_data->status)):
				if($autentication_data->status=="success"):
					$post_data['token']=array(
						"token"=>$autentication_data->results->token,
						"token_timeout"=>$autentication_data->results->token_timeout,
						"token_generation_time"=>$autentication_data->results->token_generation_time
					);
					$post_data['data']['email_template_id']=26;
					$post_data_str=json_encode($post_data);
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_HEADER, false);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
					curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.DMC_API_PATH."email-templates/booking-update-email.php");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					$return_data = curl_exec($ch);
					curl_close($ch);
					$return_data_arr=json_decode($return_data, true);
					//print_r($return_data_arr);
					if($return_data_arr['status']=="success"):
						$email_template=$return_data_arr['email_template'];
						$email_body = str_replace(array('[HOTEL_NAME]', '[EMAIL]', '[PASSWORD]'), array($user_data['hotel_name'], $user_data['email_address'], $password), $email_template['template_body']);
						@tools::Send_SMTP_Mail($user_data['email_address'], FROM_EMAIL, '', $email_template['template_subject'], $email_body);
					//else:
					//	$_SESSION['SET_TYPE'] = 'error';
					//	$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
					endif;
				endif;
			endif;
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
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER_HOTEL);?>FORGOT PASSWORD</title>
	<?php require_once(HOTEL_CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
		$(document).ready(function() {
			$.backstretch(["<?PHP echo(HOTEL_CONTROL_CENTER_IMAGE_PATH);?>bg1.jpg", "<?PHP echo(HOTEL_CONTROL_CENTER_IMAGE_PATH);?>bg2.jpg", "<?PHP echo(HOTEL_CONTROL_CENTER_IMAGE_PATH);?>bg3.jpg"], {duration: 1500, fade: 750});
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
				<b><img src="<?PHP echo(HOTEL_CONTROL_CENTER_IMAGE_PATH);?>logo.png"></b>
			</div>
			<p class="login-box-msg">Please enter your email address to receive your new password</p>
			<form name="form_forgot_password" id="form_forgot_password"method="post">
				<div class="form-group">
					<input type="text" name="email_address" id="email_address" class="form-control validate[required] custom[email]" placeholder="Email"   data-errormessage-value-missing="Email is required!" value="" tabindex = "1" autocomplete="off" />
				</div>
				<div class="row">
					<div class="col-xs-4">
						<input type = "hidden" name = "token" value = "<?php echo(tools::generateFormToken($verify_token)); ?>" />
						<button type = "submit" class = "btn btn-primary btn-block btn-flat" id = "btn_forgot_password" name = "btn_forgot_password" tabindex = "2" autocomplete = "off" />SUBMIT</button>
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