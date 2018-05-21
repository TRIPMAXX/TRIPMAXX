<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['AGENT_SESSION_DATA']['id'], DOMAIN_NAME_PATH.'');
	$white_list_array = array('company_name', 'accounting_name', 'first_name', 'middle_name', 'last_name', 'email_address', 'designation', 'iata_status', 'nature_of_business', 'preferred_currency', 'country', 'state', 'city', 'zipcode', 'address', 'timezone', 'telephone', 'mobile_number', 'website', 'image', 'image_hidden', 'code', 'username', 'password', 'token', 'btn_submit', 'confirm_password', 'credit_balance');
	$verify_token = "create_new_agent";
	$country_data = tools::find("all", TM_COUNTRIES, '*', "WHERE 1", array());
	$currency_data = tools::find("all", TM_CURRENCIES, '*', "WHERE status=:status ORDER BY serial_number ASC ", array(':status'=>1));
	if(isset($_GET['agent_id']) && $_GET['agent_id']!=""):
		$find_sub_agent=tools::find("first", TM_AGENT, '*', "WHERE id=:id AND parent_id=:parent_id AND type=:type", array(':id'=>base64_decode($_GET['agent_id']), ':parent_id'=>$_SESSION['AGENT_SESSION_DATA']['id'], ':type'=>"A"));
		if(!empty($find_sub_agent)):
			//Do nothing
			//print_r($find_sub_agent);
		else:
			$_SESSION['SET_TYPE'] = 'error';
			$_SESSION['SET_FLASH'] = "Invalid agent id";
			header("location:".DOMAIN_NAME_PATH."sub_agent.php");
			exit;
		endif;
	else:
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = "Agent id missing";
		header("location:".DOMAIN_NAME_PATH."sub_agent.php");
		exit;
	endif;
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
			$uploaded_file_json_data='';
			if(isset($_FILES) && !empty($_FILES))
				$uploaded_file_json_data='{"uploaded_file_data":[{"form_field_name":"image","form_field_name_hidden":"image_hidden","file_path":"'.AGENT_IMAGES.'","width":"","height":"","file_type":"image"}]}';
			if(tools::module_data_exists_check("company_name = '".tools::stripcleantohtml($_POST['company_name'])."' AND id <> ".$find_sub_agent['id']."", '', TM_AGENT)) {
				$_SESSION['SET_TYPE']="error";
				$_SESSION['SET_FLASH'] = 'This company name already exists.';		
			}elseif(tools::module_data_exists_check("email_address = '".tools::stripcleantohtml($_POST['email_address'])."' AND id <> ".$find_sub_agent['id']."", '', TM_AGENT)) {
				$_SESSION['SET_TYPE']="error";
				$_SESSION['SET_FLASH'] = 'This email address already exists.';		
			}elseif(tools::module_data_exists_check("code = '".tools::stripcleantohtml($_POST['code'])."' AND id <> ".$find_sub_agent['id']."", '', TM_AGENT)) {
				$_SESSION['SET_TYPE']="error";
				$_SESSION['SET_FLASH'] = 'This code already exists.';		
			}elseif(tools::module_data_exists_check("username = '".tools::stripcleantohtml($_POST['username'])."' AND id <> ".$find_sub_agent['id']."", '', TM_AGENT)) {
				$_SESSION['SET_TYPE']="error";
				$_SESSION['SET_FLASH'] = 'This username already exists.';		
			} else {
				$_POST['id']=$find_sub_agent['id'];
				if($_POST['password']=="")
					unset($_POST['password']);
				if($save_agent = tools::module_form_submission($uploaded_file_json_data, TM_AGENT)) {
					if(isset($_POST['password']) && $_POST['password']!=""):
						$first_name=$_POST['first_name'];
						$last_name=$_POST['last_name']; 
						$username=$_POST['username'];
						$password=$_POST['password'];
						if(!empty($tm_agent_template)):
							$tm_mail_Body=str_replace(array("[FIRST_NAME]", "[LAST_NAME]", "[USERNAME]", "[PASSWORD]"), array($first_name, $last_name, $username, $password), $tm_agent_template['template_body']);
							//print_r($tm_mail_Body);exit;
							@tools::Send_SMTP_Mail($_POST['email_address'], FROM_EMAIL, '', $tm_agent_template['template_subject'], $tm_mail_Body);
						endif;
					endif;
					unset($_POST);
					$_SESSION['SET_TYPE']="success";
					$_SESSION['SET_FLASH'] = 'Agent has been updated successfully.';
					//header("location:".DOMAIN_NAME_PATH."sub_agent.php");
					//exit;
				} else {
					$_SESSION['SET_TYPE']="error";
					$_SESSION['SET_FLASH'] = 'We are having some probem. Please try again later.';
				}
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo DEFAULT_PAGE_TITLE ;?> Destination Management Company</title>	
		<?php require_once('meta.php');?>
		<script type="text/javascript">
		<!--
		$(function(){
			$("#form_edit_agent").validationEngine();
			$("#password").blur(function(){
				if($(this).val()!="")
				{
					$("#confirm_password").addClass("validate[required, equals[password]]");
				}
				else
				{
					$("#confirm_password").removeClass("validate[required, equals[password]]");
				}
			});
			$("#country").change(function(){
				fetch_state($(this).val());
			});
			$("#state").change(function(){
				fetch_city($(this).val());
			});
			<?php 
			if((isset($_POST['country']) && $_POST['country']!="") || (isset($find_sub_agent['country']) && $find_sub_agent['country']!=""))
			{
			?>
				fetch_state(<?php echo(isset($_POST['country']) && $_POST['country']!="" ? $_POST['country'] : (isset($find_sub_agent['country']) && $find_sub_agent['country']!="" ? $find_sub_agent['country'] : ""));?>);
			<?php
			}
			?>
			<?php 
			if((isset($_POST['state']) && $_POST['state']!="") || (isset($find_sub_agent['state']) && $find_sub_agent['state']!=""))
			{
			?>
				fetch_city(<?php echo(isset($_POST['state']) && $_POST['state']!="" ? $_POST['state'] : (isset($find_sub_agent['state']) && $find_sub_agent['state']!="" ? $find_sub_agent['state'] : ""));?>);
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
				$("#state").val('<?php echo(isset($_POST['state']) && $_POST['state']!="" ? $_POST['state'] : (isset($find_sub_agent['state']) && $find_sub_agent['state']!="" ? $find_sub_agent['state'] : ""));?>');
			});;
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
				$("#city").val('<?php echo(isset($_POST['city']) && $_POST['city']!="" ? $_POST['city'] : (isset($find_sub_agent['city']) && $find_sub_agent['city']!="" ? $find_sub_agent['city'] : ""));?>');
			});
		}
		//-->
		</script>
	</head>
	<body class="index-page">
		<!-- TOP HEADER -->
		<?php require_once('header.php');?>
		<!-- TOP HEADER -->
		<div class="main-cont">
			<div class="body-padding">
				<div class="banner_slider" style="background:url(img/banner4.jpg)no-repeat center center/cover;">
					<div class="banner_slider_text">
						Create Sub Agent
					</div>
				</div>
				<?php require_once('login_menu.php');?>
				<div id="" class="container">
					<section class="content">
						<form name="form_edit_agent" id="form_edit_agent" method="POST" enctype="multipart/form-data">
						<div class="row rows">
							<div class="col-md-12">
								<div id="notify_msg_div"></div>
								<div class="box box-primary">
									<div class="col-md-12 row rows">
										<div class="box-header">
										   <h3 class="box-title">Company Details</h3>
										</div>
										<div class="box-body">
											<div id="" class="row rows">
												<div class="form-group col-md-6">
													<label for="company_name" class="form-label1">Company Name <font color="#FF0000">*</font> :</label>
													<input type="text" class="form-control form_input1 validate[required, custom[onlyLetterNumber]]" id="company_name" name="company_name" placeholder="Company Name" value="<?php echo(isset($_POST['company_name']) && $_POST['company_name']!='' ? $_POST['company_name'] : (isset($find_sub_agent['company_name']) && $find_sub_agent['company_name']!='' ? $find_sub_agent['company_name'] : ""));?>" tabindex="1">
												</div>
												<div class="form-group col-md-6">
													<label for="accounting_name" class="form-label1">Company Accounting Name <font color="#FF0000">*</font> :</label>
													<input type="text" class="form-control form_input1 validate[required, custom[onlyLetterNumber]]" id="accounting_name" name="accounting_name" placeholder="Company Accounting Name" value="<?php echo(isset($_POST['accounting_name']) && $_POST['accounting_name']!='' ? $_POST['accounting_name'] : (isset($find_sub_agent['accounting_name']) && $find_sub_agent['accounting_name']!='' ? $find_sub_agent['accounting_name'] : ""));?>" tabindex="2">
												</div>
												<div class="clearfix"></div>
												<div class="form-group col-md-4">
													<label for="first_name" class="form-label1">First Name <font color="#FF0000">*</font> :</label>
													<input type="text" class="form-control form_input1 validate[required, custom[onlyLetterSp]]" id="first_name" name="first_name" placeholder="First Name" value="<?php echo(isset($_POST['first_name']) && $_POST['first_name']!='' ? $_POST['first_name'] : (isset($find_sub_agent['first_name']) && $find_sub_agent['first_name']!='' ? $find_sub_agent['first_name'] : ""));?>" tabindex="3">
												</div>
												<div class="form-group col-md-4">
													<label for="middle_name" class="form-label1">Middle Name :</label>
													<input type="text" class="form-control form_input1 validate[optional, custom[onlyLetterSp]]" id="middle_name" name="middle_name" placeholder="Middle Name" value="<?php echo(isset($_POST['middle_name']) && $_POST['middle_name']!='' ? $_POST['middle_name'] : (isset($find_sub_agent['middle_name']) && $find_sub_agent['middle_name']!='' ? $find_sub_agent['middle_name'] : ""));?>" tabindex="4">
												</div>
														
												<div class="form-group col-md-4">
													<label for="last_name" class="form-label1">Last Name <font color="#FF0000">*</font> :</label>
													<input type="text" class="form-control form_input1 validate[required, custom[onlyLetterSp]]" id="last_name" name="last_name" placeholder="Last Name" value="<?php echo(isset($_POST['last_name']) && $_POST['last_name']!='' ? $_POST['last_name'] : (isset($find_sub_agent['last_name']) && $find_sub_agent['last_name']!='' ? $find_sub_agent['last_name'] : ""));?>" tabindex="5">
												</div>
												<div class="clearfix"></div>
												<div class="form-group col-md-6">
													<label for="email_address" class="form-label1">Email <font color="#FF0000">*</font> :</label>
													<input type="email" class="form-control form_input1 validate[required, custom[email]]" id="email_address" name="email_address" placeholder="Email" value="<?php echo(isset($_POST['email_address']) && $_POST['email_address']!='' ? $_POST['email_address'] : (isset($find_sub_agent['email_address']) && $find_sub_agent['email_address']!='' ? $find_sub_agent['email_address'] : ""));?>" tabindex="6">
												</div>
												<div class="form-group col-md-6">
													<label for="designation" class="form-label1">Designation :</label>
													<input type="text" class="form-control form_input1" id="designation" name="designation" placeholder="Designation" value="<?php echo(isset($_POST['designation']) && $_POST['designation']!='' ? $_POST['designation'] : (isset($find_sub_agent['designation']) && $find_sub_agent['designation']!='' ? $find_sub_agent['designation'] : ""));?>" tabindex="7">
												</div>
												<div class="clearfix"></div>
												<div class="form-group col-md-6 radio_pad">
													<label for="iata_status" class="form-label1">IATA Status <font color="#FF0000">*</font> :</label>
													<select name="iata_status" id="iata_status" class="form-control form_input1 select_bg" tabindex="8">
														<option value="1" <?php echo(isset($_POST['iata_status']) && $_POST['iata_status']==1 ? 'selected="selected"' : (isset($find_sub_agent['iata_status']) && $find_sub_agent['iata_status']==1 ? 'selected="selected"' : ""));?>>Approve</option>
														<option value="0" <?php echo(isset($_POST['iata_status']) && $_POST['iata_status']==0 ? 'selected="selected"' : (isset($find_sub_agent['iata_status']) && $find_sub_agent['iata_status']==0 ? 'selected="selected"' : ""));?>>Not Approve</option>
													</select>
												</div>
												<div class="form-group col-md-6">
													<label for="nature_of_business" class="form-label1">Nature of Business:</label>
													<select name="nature_of_business" id="nature_of_business" class="form-control form_input1 select_bg" tabindex="9">
														<option value="">- Select -</option>
														<option value="Activity Supplier" <?php echo(isset($_POST['nature_of_business']) && $_POST['nature_of_business']=="Activity Supplier" ? 'selected="selected"' : (isset($find_sub_agent['nature_of_business']) && $find_sub_agent['nature_of_business']=="Activity Supplier" ? 'selected="selected"' : ""));?>>Activity Supplier</option>
														<option value="Hotel" <?php echo(isset($_POST['nature_of_business']) && $_POST['nature_of_business']=="Hotel" ? 'selected="selected"' : (isset($find_sub_agent['nature_of_business']) && $find_sub_agent['nature_of_business']=="Hotel" ? 'selected="selected"' : ""));?>>Hotel</option>
														<option value="Hotel Chain" <?php echo(isset($_POST['nature_of_business']) && $_POST['nature_of_business']=="Hotel Chain" ? 'selected="selected"' : (isset($find_sub_agent['nature_of_business']) && $find_sub_agent['nature_of_business']=="Hotel Chain" ? 'selected="selected"' : ""));?>>Hotel Chain</option>
														<option value="Resturent" <?php echo(isset($_POST['nature_of_business']) && $_POST['nature_of_business']=="Resturent" ? 'selected="selected"' : (isset($find_sub_agent['nature_of_business']) && $find_sub_agent['nature_of_business']=="Resturent" ? 'selected="selected"' : ""));?>>Resturent</option>
													</select>
												</div>
												<div class="clearfix"></div>
												<div class="form-group col-md-6">
													<label for="pwd" class="form-label1">Preferred Currency <font color="#FF0000">*</font> :</label>
													<select name="preferred_currency" class="form-control form_input1 select_bg validate[required]">
														<option value="" class="form-control form_input1">- Select Currency -</option>
													<?php
													if(!empty($currency_data)):
														foreach($currency_data as $currency_key=>$currency_val):
													?>
														<option value = "<?php echo $currency_val['id'];?>" <?php echo(isset($_POST['preferred_currency']) && $_POST['preferred_currency']==$currency_val['id'] ? 'selected="selected"' : (isset($find_sub_agent['preferred_currency']) && $find_sub_agent['preferred_currency']==$currency_val['id'] ? 'selected="selected"' : ""));?>><?php echo $currency_val['currency_name']." (".$currency_val['currency_code'].")";?></option>
													<?php
														endforeach;
													endif;
													?>
													</select>
												</div>
												<div class="form-group col-md-6">
													<label for="country" class="control-label">Country <font color="#FF0000">*</font> :</label>
													<select name = "country" id="country" class="form-control form_input1 select_bg validate[required]" tabindex = "11">
														<option value = "">Select Country</option>
														<?php
														if(!empty($country_data)):
															foreach($country_data as $country_key=>$country_val):
														?>
															<option value = "<?php echo $country_val['id'];?>" <?php echo(isset($_POST['country']) && $_POST['country']==$country_val['id'] ? 'selected="selected"' : (isset($find_sub_agent['country']) && $find_sub_agent['country']==$country_val['id'] ? 'selected="selected"' : ""));?>><?php echo $country_val['name'];?></option>
														<?php
															endforeach;
														endif;
														?>
													</select>
												</div>
												<div class="clearfix"></div>
												<div class="form-group col-md-3">
													<label for="state" class="control-label">State / Region <font color="#FF0000">*</font> :</label>
													<select name = "state" id="state" class="form-control form_input1 select_bg validate[required]" tabindex = "12">
														<option value = "">Select State / Region</option>
													</select>
												</div>
												<div class="form-group col-md-3">
													<label for="city" class="control-label">City <font color="#FF0000">*</font> :</label>
													<select name = "city" id="city" class="form-control form_input1 select_bg validate[required]" tabindex = "13">
														<option value = "">Select City</option>
													</select>
												</div>
												<div class="form-group col-md-6">
													<label for="zipcode" class="form-label1">Pincode/Zipcode/Postcode <font color="#FF0000">*</font> :</label>
													<input type="text" class="form-control form_input1 validate[required, custom[onlyLetterNumber]]" id="zipcode" name="zipcode" placeholder="Pincode/Zipcode/Postcode" value="<?php echo(isset($_POST['zipcode']) && $_POST['zipcode']!='' ? $_POST['zipcode'] : (isset($find_sub_agent['zipcode']) && $find_sub_agent['zipcode']!='' ? $find_sub_agent['zipcode'] : ""));?>" tabindex="14">
												</div>
												<div class="clearfix"></div>
												<div class="form-group col-md-12">
													<label for="address" class="form-label1">Address <font color="#FF0000">*</font> :</label>
													<textarea class="form-control form_input1 validate[required, custom[onlyLetterNumber]]" rows="5" id="address" name="address" tabindex="15"><?php echo(isset($_POST['address']) && $_POST['address']!='' ? $_POST['address'] : (isset($find_sub_agent['address']) && $find_sub_agent['address']!='' ? $find_sub_agent['address'] : ""));?></textarea>
												</div>
												<div class="form-group col-md-6">
													<label for="timezone" class="form-label1">Time Zone <font color="#FF0000">*</font> :</label>
													<?php
														$time_zone_arr=tools::generate_timezone_list();
													?>
													<select name="timezone" name="timezone" class="form-control form_input1 select_bg validate[required]" tabindex="16">
														<option value="" class="form-control form_input1">- Select Timezone -</option>
													<?php
													foreach($time_zone_arr as $time_key=>$time_val)
													{
													?>
														<option value="<?php echo $time_key;?>" <?php echo(isset($_POST['timezone']) && $_POST['timezone']==$time_key ? "selected='selected'" : (isset($find_sub_agent['timezone']) && $find_sub_agent['timezone']==$time_key ? "selected='selected'" : ''))?>><?php echo $time_val;?></option>
													<?php
													}
													?>
													</select>
												</div>
												<div class="form-group col-md-6">
													<label for="telephone" class="form-label1">Telephone <font color="#FF0000">*</font> :</label>
													<input type="text" class="form-control form_input1 validate[required, custom[phone]]" id="telephone" name="telephone" placeholder="Telephone" value="<?php echo(isset($_POST['telephone']) && $_POST['telephone']!='' ? $_POST['telephone'] : (isset($find_sub_agent['telephone']) && $find_sub_agent['telephone']!='' ? $find_sub_agent['telephone'] : ""));?>" tabindex="17">
												</div>
												<div class="clearfix"></div>
												<div class="form-group col-md-6">
													<label for="mobile_number" class="form-label1">Mobile Number <font color="#FF0000">*</font> :</label>
													<input type="text" class="form-control form_input1 validate[required, custom[phone]]" id="mobile_number" name="mobile_number" placeholder="Mobile Number" value="<?php echo(isset($_POST['mobile_number']) && $_POST['mobile_number']!='' ? $_POST['mobile_number'] : (isset($find_sub_agent['mobile_number']) && $find_sub_agent['mobile_number']!='' ? $find_sub_agent['mobile_number'] : ""));?>" tabindex="18">
												</div>
												<div class="form-group col-md-6">
													<label for="website" class="form-label1">Website :</label>
													<input type="text" class="form-control form_input1" id="website" name="website" placeholder="Website" value="<?php echo(isset($_POST['website']) && $_POST['website']!='' ? $_POST['website'] : (isset($find_sub_agent['website']) && $find_sub_agent['website']!='' ? $find_sub_agent['website'] : ""));?>" tabindex="19">
												</div>
												<div class="clearfix"></div>
												<div class="form-group col-md-6">
													<label for="website" class="form-label1">Your Logo :</label>
													<input type="file" class="form-control form_input1" id="image" name="image" placeholder="Your Logo" tabindex="20">
													<?php
													if(isset($find_sub_agent['image']) && $find_sub_agent['image']!="" && file_exists(AGENT_IMAGES.$find_sub_agent['image'])):
													?>
													<br/>
													<img src = "<?php echo(AGENT_IMAGE_PATH.$find_sub_agent['image']);?>" border = "0" alt = "" style="width:150px;margin:1px;" onerror="this.remove;"/>
													<input type="hidden" name="image_hidden" id="image_hidden" value="<?php echo $find_sub_agent['image']; ?>" />
													<?php
													endif;
													?>
												</div>
												<div class="form-group col-md-6">
													<label for="code" class="form-label1">Type The Code Shown <font color="#FF0000">*</font> :</label>
													<input type="text" class="form-control form_input1 validate[required]" id="code" name="code" placeholder="Type The Code Shown" value="<?php echo(isset($_POST['code']) && $_POST['code']!='' ? $_POST['code'] : (isset($find_sub_agent['code']) && $find_sub_agent['code']!='' ? $find_sub_agent['code'] : ""));?>" tabindex="21">
												</div>
												<div class="clearfix"></div>
											</div>
										</div>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="box box-primary">
									<div class="col-md-12 row rows">
										<div class="box-header">
										   <h3 class="box-title">Login Details</h3>
										</div>
										<div class="box-body">
											<div id="" class="row rows">
												<div class="form-group col-md-12">
													<label for="username" class="form-label1">Username <font color="#FF0000">*</font> :</label>
													<input type="text" class="form-control form_input1 validate[required]" id="username" name="username" placeholder="Username" value="<?php echo(isset($_POST['username']) && $_POST['username']!='' ? $_POST['username'] : (isset($find_sub_agent['username']) && $find_sub_agent['username']!='' ? $find_sub_agent['username'] : ""));?>" tabindex="22">
												</div>
												<div class="form-group col-md-6">
													<label for="password" class="form-label1">Password :</label>
													<input type="password" class="form-control form_input1" id="password" name="password" placeholder="Password" value="" tabindex="23">
												</div>
												<div class="form-group col-md-6">
													<label for="confirm_password" class="form-label1">Confirm Password :</label>
													<input type="password" class="form-control form_input1" id="confirm_password" name="confirm_password" placeholder="Confirm Password" value="" tabindex="24">
												</div>
												<div class="clearfix"></div>
											</div>
										</div>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="box-footer">
									<input type="hidden" name="token" value="<?php echo(tools::generateFormToken($verify_token)); ?>" />
									<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex="41">UPDATE</button>
								</div>
							</div>
						</div>
						</form>
					</section>
				</div>
			</div>
		</div>
		<!-- FOOTER -->
		<?php require_once('footer.php');?>
		<!-- FOOTER -->
	</body>
</html>