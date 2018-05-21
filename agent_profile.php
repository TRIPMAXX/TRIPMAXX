<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['AGENT_SESSION_DATA']['id'], DOMAIN_NAME_PATH.'');
	$white_list_array = array('company_name', 'accounting_name', 'first_name', 'middle_name', 'last_name', 'email_address', 'designation', 'iata_status', 'nature_of_business', 'preferred_currency', 'country', 'state', 'city', 'zipcode', 'address', 'timezone', 'telephone', 'mobile_number', 'website', 'image', 'image_hidden', 'code', 'username', 'password', 'token', 'btn_submit', 'confirm_password', 'credit_balance', 'id', 'pay_within_days', 'payment_type');
	$verify_token = "create_new_agent";
	
	//print_r($agent_data);exit;
	$agent_data = tools::find("first", TM_AGENT, "*", "WHERE id = :id", array(":id"=>$_SESSION['AGENT_SESSION_DATA']['id']));

	$country_data = tools::find("all", TM_COUNTRIES, '*', "WHERE 1", array());
	$currency_data = tools::find("all", TM_CURRENCIES, '*', "WHERE 1", array());
	$autentication_data_employee=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.DMC_API_PATH."authorized.php"));
	if(isset($autentication_data_employee->status)):
		if($autentication_data_employee->status=="success"):
			$post_data_employee['token']=array(
				"token"=>$autentication_data_employee->results->token,
				"token_timeout"=>$autentication_data_employee->results->token_timeout,
				"token_generation_time"=>$autentication_data_employee->results->token_generation_time
			);
			$post_data_employee_str=json_encode($post_data_employee);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.DMC_API_PATH."dmc/employee.php");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_employee_str);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$return_data_employee = curl_exec($ch);
			curl_close($ch);
			$return_data_employee_arr=json_decode($return_data_employee, true);
			$employee_data=array();
			if($return_data_employee_arr['status']=="success"):
				$employee_data=$return_data_employee_arr['results'];
			//else:
			//	$_SESSION['SET_TYPE'] = 'error';
			//	$_SESSION['SET_FLASH'] = $return_data_employee_arr['msg'];
			endif;
			// ***** EMAIL TEMPLATES ****** //
			$post_data_employee['data']['email_template_id']=21;
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
			// Settings //
			$post_data_employee['data']['setting_id']=1;
			$post_setting_str=json_encode($post_data_employee);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.DMC_API_PATH."settings/read.php");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_setting_str);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$return_data_setting = curl_exec($ch);
			curl_close($ch);
			//print_r($return_data_setting);exit;
			$return_data_setting_arr=json_decode($return_data_setting, true);
			$general_setting =array();
			if($return_data_setting_arr['status']=="success"):
				$general_setting =$return_data_setting_arr['results'];
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
			$uploaded_file_json_data='{"uploaded_file_data":[{"form_field_name":"image","form_field_name_hidden":"image_hidden","file_path":"'.AGENT_IMAGE_PATH.'","width":"310","height":"60","file_type":"image"}]}';
				if(tools::module_data_exists_check("company_name = '".tools::stripcleantohtml($_POST['company_name'])."' AND id <> ".$agent_data['id']."", '', TM_AGENT)) {
					$_SESSION['SET_TYPE']="error";
					$_SESSION['SET_FLASH'] = 'This company name already exists.';		
				}elseif(tools::module_data_exists_check("email_address = '".tools::stripcleantohtml($_POST['email_address'])."' AND id <> ".$agent_data['id']."", '', TM_AGENT)) {
					$_SESSION['SET_TYPE']="error";
					$_SESSION['SET_FLASH'] = 'This email address already exists.';		
				}elseif(tools::module_data_exists_check("code = '".tools::stripcleantohtml($_POST['code'])."' AND id <> ".$agent_data['id']."", '', TM_AGENT)) {
					$_SESSION['SET_TYPE']="error";
					$_SESSION['SET_FLASH'] = 'This code already exists.';		
				}elseif(tools::module_data_exists_check("username = '".tools::stripcleantohtml($_POST['username'])."' AND id <> ".$agent_data['id']."", '', TM_AGENT)) {
					$_SESSION['SET_TYPE']="error";
					$_SESSION['SET_FLASH'] = 'This username already exists.';		
				} 
				else 
				{
				if($save_agent = tools::module_form_submission($uploaded_file_json_data, TM_AGENT)) {
					$credit_balance=$_POST['credit_balance'];
					$first_name=$_POST['first_name'];
					$last_name=$_POST['last_name']; 
					$username=$_POST['username'];
					$password=$_POST['password'];
					//print_r($_POST);exit;
					unset($_POST);
					$_POST['agent_id']=$save_agent;
					$_POST['amount']=$credit_balance;
					$_POST['note']="Default Credit";
					$save_agent = tools::module_form_submission("", TM_AGENT_ACCOUNTING);
					if(!empty($tm_agent_template)):
						if($password != "")
						{
							$tm_mail_Body=str_replace(array("[FIRST_NAME]", "[LAST_NAME]", "[USERNAME]", "[PASSWORD]"), array($first_name, $last_name, $username, $password), $tm_agent_template['template_body']);
							//print_r($tm_mail_Body);exit;
							@tools::Send_SMTP_Mail($_POST['email_address'], FROM_EMAIL, '', $tm_agent_template['template_subject'], $tm_mail_Body);
						}
					endif;
					$_SESSION['SET_TYPE']="success";
					$_SESSION['SET_FLASH'] = 'Your has been profile updated successfully.';
					header("location:dashboard.php");
					exit;
				} else {
					$_SESSION['SET_TYPE']="error";
					$_SESSION['SET_FLASH'] = 'We are having some probem. Please try again later.';
				};
			};
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
		$("#agent_signe_up").validationEngine();
		// HIDE & SHOW
		$("#credit").change(function(){
			$("#pay_within_days").hide();
			$("#pay_within_days1").hide();
		});
		$("#cash").change(function(){
			$("#pay_within_days").show();
			$("#pay_within_days1").show();
		});
		// HIDE & SHOW
		$("#country").change(function(){
			fetch_state($(this).val());
		});
		$("#state").change(function(){
			fetch_city($(this).val());
		});
		<?php 
		if((isset($_POST['country']) && $_POST['country']!="") || (isset($agent_data['country']) && $agent_data['country']!=""))
		{
		?>
			fetch_state(<?php echo(isset($_POST['country']) && $_POST['country']!="" ? $_POST['country'] : (isset($agent_data['country']) && $agent_data['country']!="" ? $agent_data['country'] : ""));?>);
		<?php
		}
		?>
		<?php 
		if((isset($_POST['state']) && $_POST['state']!="") || (isset($agent_data['state']) && $agent_data['state']!=""))
		{
		?>
			fetch_city(<?php echo(isset($_POST['state']) && $_POST['state']!="" ? $_POST['state'] : (isset($agent_data['state']) && $agent_data['state']!="" ? $agent_data['state'] : ""));?>);
		<?php
		}
		?>
	});
	function fetch_state(country_id)
	{
		$.ajax({
			url:"<?= DOMAIN_NAME_PATH."ajax_state_fetch.php";?>",
			type:"post",
			data:{
				country_id:country_id
			},
			beforeSend:function(){
				$("#city").html('<option value = "">Select City</option>');
				$("#state").html('<option value = "">Select State / Region</option>');
			},
			dataType:"json",
			success:function(response){
				//console.log(response);
				if(response.status=="success")
				{
					if(response.results.length > 0)
					{
						$.each(response.results, function(state_key, state_val){
							$("#state").append('<option value = "'+state_val['id']+'">'+state_val['name']+'</option>');
						});
					}
				}
				else
				{
					//showError(response.msg);
				}
			},
			error:function(){
				//showError("We are having some problem. Please try later.");
			}
		}).done(function(){
			$("#state").val('<?php echo(isset($_POST['state']) && $_POST['state']!="" ? $_POST['state'] : (isset($agent_data['state']) && $agent_data['state']!="" ? $agent_data['state'] : ""));?>');
		});
	}
	function fetch_city(state_id)
	{
		$.ajax({
			url:"<?= DOMAIN_NAME_PATH."ajax_city_fetch.php";?>",
			type:"post",
			data:{
				state_id:state_id
			},
			beforeSend:function(){
				$("#city").html('<option value = "">Select City</option>');
			},
			dataType:"json",
			success:function(response){
				//console.log(response);
				if(response.status=="success")
				{
					if(response.results.length > 0)
					{
						$.each(response.results, function(city_key, city_val){
							$("#city").append('<option value = "'+city_val['id']+'">'+city_val['name']+'</option>');
						});
					}
				}
				else
				{
					//showError(response.msg);
				}
			},
			error:function(){
				//showError("We are having some problem. Please try later.");
			}
		}).done(function(){
			$("#city").val('<?php echo(isset($_POST['city']) && $_POST['city']!="" ? $_POST['city'] : (isset($agent_data['city']) && $agent_data['city']!="" ? $agent_data['city'] : ""));?>');
		});
	}
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
					Update Your Profile
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
							<div class="offer-slider-lbl">UPDATE PROFILE</div>
							<p>Please use the following form to update your profile details as required.</p>
						</div>
						<div id="" class="form_wrapper agent_form_wrapper">
							<form name="agent_signe_up" id="agent_signe_up" method="POST" enctype="multipart/form-data">
								
								<div id="notify_msg_div"></div>
								<div id="" class="row rows">
									<div id="" class="col-md-12">
										<h1>Company Details</h1>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<label for="pwd" class="form-label1">Company Name<span class=""> *</span> :</label>
										</div>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<input type="text" class="form-control form_input1 validate[required, custom[onlyLetterNumber]]" id="company_name" name="company_name" placeholder="Company Name" value="<?php echo(isset($_POST['company_name']) && $_POST['company_name']!='' ? $_POST['company_name'] : (isset($agent_data['company_name']) && $agent_data['company_name']!='' ? $agent_data['company_name'] : ""));?>" tabindex="1">
										</div>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<label for="pwd" class="form-label1">Company Accounting Name<span class=""> *</span> :</label>
										</div>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<input type="text" class="form-control form_input1 validate[required, custom[onlyLetterNumber]]" id="accounting_name" name="accounting_name" placeholder="Company Accounting Name" value="<?php echo(isset($_POST['accounting_name']) && $_POST['accounting_name']!='' ? $_POST['accounting_name'] : (isset($agent_data['accounting_name']) && $agent_data['accounting_name']!='' ? $agent_data['accounting_name'] : ""));?>" tabindex="2">
										</div>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<label for="pwd" class="form-label1">Contact Person<span class=""> *</span> :</label>
										</div>
									</div>
									<div id="" class="col-md-9">
										<div id="" class="row rows">
											<div id="" class="col-md-4">
												<div class="form-group fancy-form">
													<input type="text" class="form-control form_input1 validate[required, custom[onlyLetterSp]]" id="first_name" name="first_name" placeholder="First Name" value="<?php echo(isset($_POST['first_name']) && $_POST['first_name']!='' ? $_POST['first_name'] : (isset($agent_data['first_name']) && $agent_data['first_name']!='' ? $agent_data['first_name'] : ""));?>" tabindex="3">
												</div>
											</div>
											<div id="" class="col-md-4">
												<div class="form-group fancy-form">
													<input type="text" class="form-control form_input1 validate[optional, custom[onlyLetterSp]]" id="middle_name" name="middle_name" placeholder="Middle Name" value="<?php echo(isset($_POST['middle_name']) && $_POST['middle_name']!='' ? $_POST['middle_name'] : (isset($agent_data['middle_name']) && $agent_data['middle_name']!='' ? $agent_data['middle_name'] : ""));?>" tabindex="4">
												</div>
											</div>
											<div id="" class="col-md-4">
												<div class="form-group fancy-form">
													<input type="text" class="form-control form_input1 validate[required, custom[onlyLetterSp]]" id="last_name" name="last_name" placeholder="Last Name" value="<?php echo(isset($_POST['last_name']) && $_POST['last_name']!='' ? $_POST['last_name'] :(isset($agent_data['last_name']) && $agent_data['last_name']!='' ? $agent_data['last_name'] : ""));?>" tabindex="5">
												</div>
											</div>
										</div>
									</div>


									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<label for="pwd" class="form-label1">Email<span class=""> *</span> :</label>
										</div>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<input type="email" class="form-control form_input1 validate[required,custom[email]]" id="email_address" name="email_address" placeholder="Email" value="<?php echo(isset($_POST['email_address']) && $_POST['email_address']!='' ? $_POST['email_address'] : (isset($agent_data['email_address']) && $agent_data['email_address']!='' ? $agent_data['email_address'] : ""));?>" tabindex="6">
										</div>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<label for="pwd" class="form-label1">Designation :</label>
										</div>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<input type="text" class="form-control form_input1" id="designation" name="designation" placeholder="Designation" value="<?php echo(isset($_POST['designation']) && $_POST['designation']!='' ? $_POST['designation'] : (isset($agent_data['designation']) && $agent_data['designation']!='' ? $agent_data['designation'] : ""));?>" tabindex="7">
										</div>
									</div>


									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<label for="pwd" class="form-label1">IATA Status<span class=""> *</span> :</label>
										</div>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<select name="iata_status" id="iata_status" class="form-control form_input1 select_bg" tabindex="8" style="background:rgba(255,255,255) url('img/dropdown_arrow.png') no-repeat 98% center !important; background-size:30px !important">
												<option value="1" <?php echo(isset($_POST['iata_status']) && $_POST['iata_status']==1 ? 'selected="selected"' : (isset($agent_data['iata_status']) && $agent_data['iata_status']==1 ? 'selected="selected"':""));?>>Approve</option>
												<option value="0" <?php echo(isset($_POST['iata_status']) && $_POST['iata_status']==0 ? 'selected="selected"' : (isset($agent_data['iata_status']) && $agent_data['iata_status']==0 ? 'selected="selected"':""));?>>Not Approve</option>
											</select>
										</div>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<label for="pwd" class="form-label1">Nature of Business:</label>
										</div>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<select name="nature_of_business" id="nature_of_business" class="form-control form_input1 select_bg" tabindex="9" style="background:rgba(255,255,255) url('img/dropdown_arrow.png') no-repeat 98% center !important; background-size:30px !important">
												<option value="">- Select -</option>
												<option value="Activity Supplier" <?php echo(isset($_POST['nature_of_business']) && $_POST['nature_of_business']=="Activity Supplier" ? 'selected="selected"' : (isset($agent_data['nature_of_business']) && $agent_data['nature_of_business']=="Activity Supplier" ? 'selected="selected"':""));?>>Activity Supplier</option>
												<option value="Hotel" <?php echo(isset($_POST['nature_of_business']) && $_POST['nature_of_business']=="Hotel" ? 'selected="selected"' : (isset($agent_data['nature_of_business']) && $agent_data['nature_of_business']=="Hotel" ? 'selected="selected"':""));?>>Hotel</option>
												<option value="Hotel Chain" <?php echo(isset($_POST['nature_of_business']) && $_POST['nature_of_business']=="Hotel Chain" ? 'selected="selected"' : (isset($agent_data['nature_of_business']) && $agent_data['nature_of_business']=="Hotel Chain" ? 'selected="selected"':""));?>>Hotel Chain</option>
												<option value="Resturent" <?php echo(isset($_POST['nature_of_business']) && $_POST['nature_of_business']=="Resturent" ? 'selected="selected"' : (isset($agent_data['nature_of_business']) && $agent_data['nature_of_business']=="Resturent" ? 'selected="selected"':""));?>>Resturent</option>
											</select>
										</div>
									</div>


									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<label for="pwd" class="form-label1">Preferred Currency <span class="">*</span> :</label>
										</div>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<select name="preferred_currency" class="form-control form_input1 select_bg validate[required]" style="background:rgba(255,255,255) url('img/dropdown_arrow.png') no-repeat 98% center !important; background-size:30px !important">
												<option value="" class="form-control form_input1">- Select Currency -</option>
											<?php
											if(!empty($currency_data)):
												foreach($currency_data as $currency_key=>$currency_val):
											?>
												<option value = "<?php echo $currency_val['id'];?>" <?php echo(isset($_POST['preferred_currency']) && $_POST['preferred_currency']==$currency_val['id'] ? 'selected="selected"' : (isset($agent_data['preferred_currency']) && $agent_data['preferred_currency']==$currency_val['id'] ? 'selected="selected"':""));?>><?php echo $currency_val['currency_name']." (".$currency_val['currency_code'].")";?></option>
											<?php
												endforeach;
											endif;
											?>
											</select>
										</div>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<label for="pwd" class="form-label1">Country<span class="">*</span> :</label>
										</div>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<select name = "country" id="country" class="form-control form_input1 select_bg validate[required]" tabindex = "11" style="background:rgba(255,255,255) url('img/dropdown_arrow.png') no-repeat 98% center !important; background-size:30px !important">
												<option value = "">Select Country</option>
												<?php
												if(!empty($country_data)):
													foreach($country_data as $country_key=>$country_val):
												?>
													<option value = "<?php echo $country_val['id'];?>" <?php echo(isset($_POST['country']) && $_POST['country']==$country_val['id'] ? 'selected="selected"' : (isset($agent_data['country']) && $agent_data['country']!='' ? 'selected="selected"' : ""));?>><?php echo $country_val['name'];?></option>
												<?php
													endforeach;
												endif;
												?>
											</select>
										</div>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<label for="pwd" class="form-label1">State / Region <span class="">*</span> :</label>
										</div>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<select name = "state" id="state" class="form-control form_input1 select_bg validate[required]" tabindex = "12" style="background:rgba(255,255,255) url('img/dropdown_arrow.png') no-repeat 98% center !important; background-size:30px !important">
												<option value = "">Select State / Region</option>
											</select>
										</div>
									</div>


									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<label for="pwd" class="form-label1">City <span class="">*</span> :</label>
										</div>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<select name = "city" id="city" class="form-control form_input1 select_bg validate[required]" tabindex = "13" style="background:rgba(255,255,255) url('img/dropdown_arrow.png') no-repeat 98% center !important; background-size:30px !important">
												<option value = "">Select City</option>
											</select>
										</div>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<label for="pwd" class="form-label1">Address<span class="">*</span> :</label>
										</div>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<textarea class="form-control form_input1 validate[required, custom[onlyLetterNumber]]" rows="5" id="address" name="address" tabindex="15"><?php echo(isset($_POST['address']) && $_POST['address']!='' ? $_POST['address'] : (isset($agent_data['address']) && $agent_data['address']!='' ? $agent_data['address'] : ""));?></textarea>
										</div>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<label for="pwd" class="form-label1">Pincode/Zipcode/Postcode<span class="">*</span> :</label>
										</div>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<input type="text" class="form-control form_input1 validate[required, custom[zip]]" id="zipcode" name="zipcode" placeholder="Pincode/Zipcode/Postcode" value="<?php echo(isset($_POST['zipcode']) && $_POST['zipcode']!='' ? $_POST['zipcode'] : (isset($agent_data['zipcode']) && $agent_data['zipcode']!='' ? $agent_data['zipcode'] : ""));?>" tabindex="14">
										</div>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<label for="pwd" class="form-label1">Time Zone<span class="">*</span> :</label>
										</div>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<?php
												$time_zone_arr=tools::generate_timezone_list();
											?>
											<select name="timezone" name="timezone" class="form-control form_input1 select_bg validate[required]" tabindex="16" style="background:rgba(255,255,255) url('img/dropdown_arrow.png') no-repeat 98% center !important; background-size:30px !important">
												<option value="" class="form-control form_input1">- Select Timezone -</option>
											<?php
											foreach($time_zone_arr as $time_key=>$time_val)
											{
											?>
												<option value="<?php echo $time_key;?>" <?php echo(isset($_POST['timezone']) && $_POST['timezone']==$time_key ? "selected='selected'" : (isset($agent_data['timezone']) && $agent_data['timezone']!='' ? "selected='selected'" : ""))?>><?php echo $time_val;?></option>
											<?php
											}
											?>
											</select>
										</div>
									</div>


									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<label for="pwd" class="form-label1">Telephone<span class="">*</span> :</label>
										</div>
									</div>
									<div id="" class="col-md-3">
										<div id="" class="col-md-12">
											<label for="pwd" class="form-label1">This number will be used for contact purposes.</label>
										</div>
										<div class="form-group fancy-form">
											<input type="text" class="form-control form_input1 validate[required, custom[phone]]" id="telephone" name="telephone" placeholder="Telephone" value="<?php echo(isset($_POST['telephone']) && $_POST['telephone']!='' ? $_POST['telephone'] : (isset($agent_data['telephone']) && $agent_data['telephone']!='' ? $agent_data['telephone'] : ""));?>" tabindex="17">
										</div>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<label for="pwd" class="form-label1">Mobile Number<span class="">*</span> :</label>
										</div>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<input type="text" class="form-control form_input1 validate[required, custom[phone]]" id="mobile_number" name="mobile_number" placeholder="Mobile Number" value="<?php echo(isset($_POST['mobile_number']) && $_POST['mobile_number']!='' ? $_POST['mobile_number'] : (isset($agent_data['mobile_number']) && $agent_data['mobile_number']!='' ? $agent_data['mobile_number'] : ""));?>" tabindex="18">
										</div>
									</div>


									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<label for="pwd" class="form-label1">Website:</label>
										</div>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<input type="text" class="form-control form_input1" id="website" name="website" placeholder="Website" value="<?php echo(isset($_POST['website']) && $_POST['website']!='' ? $_POST['website'] : (isset($agent_data['website']) && $agent_data['website']!='' ? $agent_data['website'] : ""));?>" tabindex="19">
										</div>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<label for="pwd" class="form-label1">Your Logo:</label>
										</div>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<input type="file" class="" id="image" name="image" placeholder="Your Logo" tabindex="20">
											<input type="hidden" id="image_hidden" name="image_hidden">
											<?php
											if(isset($agent_data['image']) && $agent_data['image']!=""):
											?>
											<br/>
											<img src = "<?php echo("agent_control_center/assets/upload/agent/".$agent_data['image']);?>" border = "0" alt = "" style="width:150px;height:100px;margin:1px;" onerror="this.remove;"/>
											<?php
											endif;
											?>
										</div>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<label for="pwd" class="code_text1 form-label1">Type The Code Shown<span class="">*</span> :</label>
										</div>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group">
											<input type="text" class="form-control form_input1 validate[required]" id="code" name="code" placeholder="Type The Code Shown" value="<?php echo(isset($_POST['code']) && $_POST['code']!='' ? $_POST['code'] : (isset($agent_data['code']) && $agent_data['code']!='' ? $agent_data['code'] : ""));?>" tabindex="21">
										</div>
									</div>
									<div class="col-md-3">
										<label for="website" class="form-label1">Your Payment Type :</label>
									</div>
									<div class="form-group col-md-3">
										<div class="radio" style="margin: 0 0 15px 0;">
											<label class="form-label1"><input type="radio" name="payment_type" id="credit" value="credit" <?php echo(isset($agent_data['payment_type']) && $agent_data['payment_type']=='credit' ? "checked" : "");?>>Credit</label>&nbsp;&nbsp;&nbsp;
											<label><input type="radio" name="payment_type" id="cash" value="cash" <?php echo(isset($agent_data['payment_type']) && $agent_data['payment_type']=='cash' ? "checked" : "");?>>Cash</label>
										</div>
									</div>
									<div class="col-md-3 " id="pay_within_days" style="<?php echo(isset($agent_data['payment_type']) && $agent_data['payment_type']=='cash' ? "display:block;" : "display:none;");?>">
										<label for="pwd" class="form-label1">You Can Pay Within (Day)<font color="#FF0000">*</font> :</label>
									</div>
									<div class="form-group col-md-3" id="pay_within_days1" style="<?php echo(isset($agent_data['payment_type']) && $agent_data['payment_type']=='cash' ? "display:block;" : "display:none;");?>">
										<select name="pay_within_days" class="form-control form_input1 select_bg validate[required]"  style="width:100%;">
											<option value="" class="form-control form_input1">- Select Day -</option>
											<?php
											$payment_days=range(1,100);
											foreach($payment_days as $day_key=>$day_val):
											?>
												<option value = "<?php echo $day_val;?>" <?php echo(isset($_POST['pay_within_days']) && $_POST['pay_within_days']==$day_val ? 'selected="selected"' : (isset($agent_data['pay_within_days']) && $agent_data['pay_within_days']==$day_val ? 'selected="selected"' : ""));?>><?php echo $day_val;?></option>
											<?php
											endforeach;
											?>
										</select>
									</div>
									<div class="clearfix"></div>
								</div>
								<div id="" class="row rows">
									<div id="" class="col-md-12">
										<h1>Login Details</h1>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<label for="pwd" class="form-label1">Username <span class="">*</span> :</label>
										</div>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<input type="text" class="form-control form_input1 validate[required]" id="username" name="username" placeholder="Username" value="<?php echo(isset($_POST['username']) && $_POST['username']!='' ? $_POST['username'] : (isset($agent_data['username']) && $agent_data['username']!='' ? $agent_data['username'] : ""));?>" tabindex="22">
										</div>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<label for="pwd" class="form-label1">Password <span class="">*</span> :</label>
										</div>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<input type="password" class="form-control form_input1" id="password" name="password" placeholder="Password" value="" tabindex="23">
										</div>
									</div>


									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<label for="pwd" class="form-label1">Confirm Password <span class="">*</span> :</label>
										</div>
									</div>
									<div id="" class="col-md-3">
										<div class="form-group fancy-form">
											<input type="password" class="form-control form_input1 validate[optional, equals[password]]" id="confirm_password" name="confirm_password" placeholder="Confirm Password" value="" tabindex="24">
										</div>
									</div>
								</div>
								<div id="" class="btn_form">
									<div class="form-group">
										<input type="hidden" name="credit_balance" id="credit_balance" value="<?php echo $general_setting['default_credit_balance'];?>">
										<input type="hidden" name="token" value="<?php echo(tools::generateFormToken($verify_token)); ?>" />
										<input type="hidden" name="id" value="<?php echo $agent_data['id']; ?>" />
										<button type="submit" class="btn_top btn_styl_3 select_area_btn" name="btn_submit">UPDATE</button>
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
