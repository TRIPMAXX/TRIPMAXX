<?php
	require_once('loader.inc');
	$white_list_array = array('email_address', 'token', 'btn_submit');
	$verify_token = "agent_forgot_password";
	$autentication_data_employee=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.DMC_API_PATH."authorized.php"));
	if(isset($autentication_data_employee->status)):
		if($autentication_data_employee->status=="success"):
			$post_data_employee['token']=array(
				"token"=>$autentication_data_employee->results->token,
				"token_timeout"=>$autentication_data_employee->results->token_timeout,
				"token_generation_time"=>$autentication_data_employee->results->token_generation_time
			);
			// ***** EMAIL TEMPLATES ****** //
			$post_data_employee['data']['email_template_id']=18;
			$post_data_email_template_str=json_encode($post_data_employee);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.DMC_API_PATH."email-templates/booking-update-email.php");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_email_template_str);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$return_data_email_template = curl_exec($ch);
			curl_close($ch);
			$tm_agent_template_arr=json_decode($return_data_email_template, true);
			$tm_agent_template=array();
			if($tm_agent_template_arr['status']=="success"):
				$tm_agent_template=$tm_agent_template_arr['email_template'];
			//else:
			//	$_SESSION['SET_TYPE'] = 'error';
			//	$_SESSION['SET_FLASH'] = $return_data_employee_arr['msg'];
			endif;
		else:
			//$_SESSION['SET_TYPE'] = 'error';
			//$_SESSION['SET_FLASH'] = $autentication_data_employee->msg;
		endif;
	else:
		//$_SESSION['SET_TYPE'] = 'error';
		//$_SESSION['SET_FLASH'] = "We are having some problem to authorize api.";
	endif;
	if(isset($_POST['btn_submit'])) {
		if(tools::verify_token($white_list_array, $_POST, $verify_token)) {
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
	};
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo DEFAULT_PAGE_TITLE ;?> Destination Management Company</title>
<?php require_once('meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	$(function(){
		$("#agent_forgot_password").validationEngine();
	});
	//-->
	</script>
	<!-- JAVASCRIPT CODE -->
</head>
<body class="index-page">
	<!-- TOP HEADER -->
	<?php require_once('header.php');?>		
	<!-- TOP HEADER -->
	<div class="main-cont">
		<div class="body-padding">
			<div class="banner_slider" style="background:url(img/Travel-Images-For-Desktop.jpg)no-repeat center center/cover;">
				<div class="banner_slider_text">
					AGENT FORGOT PASSWORD
				</div>
			</div>
		</div>
	</div>
	<section class="all_form_wrapper">
		<div id="" class="container">
			<div id="" class="row rows">
				<div id="" class="col-md-12">
					<div id="" class="form_full_width">
						<div id="" class="form_text_wrapper agent_form_text_wrapper">
							<div class="offer-slider-lbl">AGENT FORGOT PASSWORD</div>
						</div>
						<div id="" class="form_wrapper agent_form_wrapper">
							<form name="agent_forgot_password" id="agent_forgot_password" method="POST">
								
								<div id="notify_msg_div"></div>
								<div id="" class="row rows">
									<div id="" class="col-md-12">
										<h1>Please enter your email address to receive your new password</h1>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<label for="pwd" class="form-label1">Please enter your email address to receive your new password :</label>
											<input type="email" class="form-control form_input1 validate[required]" id="email_address" name="email_address" placeholder="Email" value="<?php echo(isset($_POST['email_address']) && $_POST['email_address']!='' ? $_POST['email_address'] : "");?>" tabindex="6">
										</div>
									</div>
								</div>
								<div id="" class="btn_form">
									<div class="form-group">
										<input type="hidden" name="token" value="<?php echo(tools::generateFormToken($verify_token)); ?>" />
										<button type="submit" class="btn_top btn_styl_3 select_area_btn" name="btn_submit">SUBMIT</button>
									</div>
								</div>
							</form> 
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- FOOTER -->
	<?php require_once('footer.php');?>
	<!-- FOOTER -->
</body>
</html>
