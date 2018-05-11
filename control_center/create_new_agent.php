<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
	$white_list_array = array('employee_id', 'company_name', 'accounting_name', 'first_name', 'middle_name', 'last_name', 'email_address', 'designation', 'iata_status', 'nature_of_business', 'preferred_currency', 'country', 'state', 'city', 'zipcode', 'address', 'timezone', 'telephone', 'mobile_number', 'website', 'image', 'code', 'username', 'password', 'account_department_employee_id', 'account_department_name', 'account_department_email', 'account_department_number', 'reservation_department_employee_id', 'reservation_department_name', 'reservation_department_email', 'reservation_department_number', 'management_department_employee_id', 'management_department_name', 'management_department_email', 'management_department_number', 'hotel_price', 'tour_price', 'transfer_price', 'package_price', 'status', 'token', 'id', 'btn_submit', 'confirm_password', 'credit_balance');
	$verify_token = "create_new_agent";
	$general_setting = tools::find("first", TM_SETTINGS, 'default_credit_balance', "WHERE id=:id ", array(":id"=>1));
	$autentication_data=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."authorized.php"));
	if(isset($autentication_data->status)):
		if($autentication_data->status=="success"):
			$post_data['token']=array(
				"token"=>$autentication_data->results->token,
				"token_timeout"=>$autentication_data->results->token_timeout,
				"token_generation_time"=>$autentication_data->results->token_generation_time
			);
			$post_data_str=json_encode($post_data);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."country/read.php");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$return_data = curl_exec($ch);
			curl_close($ch);
			$return_data_arr=json_decode($return_data, true);
			$country_data=array();
			if($return_data_arr['status']=="success"):
				$country_data=$return_data_arr['results'];
			//else:
			//	$_SESSION['SET_TYPE'] = 'error';
			//	$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
			endif;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."currency/read.php");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$return_data = curl_exec($ch);
			curl_close($ch);
			$return_data_arr=json_decode($return_data, true);
			$currency_data=array();
			if($return_data_arr['status']=="success"):
				$currency_data=$return_data_arr['results'];
			//else:
			//	$_SESSION['SET_TYPE'] = 'error';
			//	$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
			endif;
			if(isset($_POST['btn_submit'])) {
				if(tools::verify_token($white_list_array, $_POST, $verify_token)) {
					$_POST['uploaded_files']=array();
					if(isset($_FILES["image"])){
						$extension = pathinfo($_FILES["image"]['name'], PATHINFO_EXTENSION);
						$validation_array = array('jpg', 'jpeg', 'png', 'gif', 'bmp');
						if(in_array(strtolower($extension), $validation_array)) {
							$data = file_get_contents($_FILES["image"]['tmp_name']);
							$base64 = 'data:image/' . $extension . ';base64,' . base64_encode($data);
							$_POST['uploaded_files']=curl_file_create($base64, $_FILES["image"]['type'], $_FILES["image"]['name']);
						}
					}
					$post_data['data']=$_POST;
					if(isset($_GET['gse_id']) && $_GET['gse_id']!="")
						$post_data['data']['parent_id']=base64_decode($_GET['gse_id']);
					$post_data_str=json_encode($post_data);
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_HEADER, false);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
					curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
					curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."agent/create.php");
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					$return_data = curl_exec($ch);
					curl_close($ch);
					//print_r($return_data);
					$return_data_arr=json_decode($return_data, true);
					//print_r($return_data_arr);
					if($return_data_arr['status']=="success")
					{
						$tm_agent_template = tools::find("first", TM_EMAIL_TEMPLATES, $value='id, template_title, template_subject, template_body, status', "WHERE id=:id AND status=:status ", array(':id'=>18, ':status'=>1));
						if(!empty($tm_agent_template)):
							$tm_mail_Body=str_replace(array("[FIRST_NAME]", "[LAST_NAME]", "[USERNAME]", "[PASSWORD]"), array($_POST['first_name'], $_POST['last_name'], $_POST['username'], $_POST['password']), $tm_agent_template['template_body']);
							//print_r($tm_mail_Body);exit;
							@tools::Send_SMTP_Mail($_POST['email_address'], FROM_EMAIL, '', $tm_agent_template['template_subject'], $tm_mail_Body);
						endif;
						$_SESSION['SET_TYPE'] = 'success';
						$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
						if(isset($_GET['gse_id']) && $_GET['gse_id']!=""):
							header("location:sub_agents?gsa_id=".$_GET['gse_id']);
						else:
							header("location:agents");
						endif;
						exit;
					}
					else
					{
						$_SESSION['SET_TYPE'] = 'error';
						$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
					}
				}
			};
		else:
			$_SESSION['SET_TYPE'] = 'error';
			$_SESSION['SET_FLASH'] = $autentication_data->msg;
		endif;
	else:
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = "We are having some problem to authorize api.";
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
		else:
			//$_SESSION['SET_TYPE'] = 'error';
			//$_SESSION['SET_FLASH'] = $autentication_data_employee->msg;
		endif;
	else:
		//$_SESSION['SET_TYPE'] = 'error';
		//$_SESSION['SET_FLASH'] = "We are having some problem to authorize api.";
	endif;
?>
<!DOCTYPE html>
<html>
  <head>
    <title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>CREATE NEW AGENT</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	$(function(){
		$("#form_new_agent").validationEngine();
		$("#country").change(function(){
			fetch_state($(this).val());
		});
		$("#state").change(function(){
			fetch_city($(this).val());
		});
		<?php 
		if(isset($_POST['country']) && $_POST['country']!="")
		{
		?>
			fetch_state(<?php echo $_POST['country'];?>);
		<?php
		}
		?>
		<?php 
		if(isset($_POST['state']) && $_POST['state']!="")
		{
		?>
			fetch_city(<?php echo $_POST['state'];?>);
		<?php
		}
		?>
		$("#account_department_employee_id").change(function(){
			$("#account_department_name").val($('#account_department_employee_id option:selected').attr("data-name"));
			$("#account_department_email").val($('#account_department_employee_id option:selected').attr("data-email"));
			$("#account_department_number").val($('#account_department_employee_id option:selected').attr("data-phone"));
		});
		$("#reservation_department_employee_id").change(function(){
			$("#reservation_department_name").val($('#reservation_department_employee_id option:selected').attr("data-name"));
			$("#reservation_department_email").val($('#reservation_department_employee_id option:selected').attr("data-email"));
			$("#reservation_department_number").val($('#reservation_department_employee_id option:selected').attr("data-phone"));
		});
		$("#management_department_employee_id").change(function(){
			$("#management_department_name").val($('#management_department_employee_id option:selected').attr("data-name"));
			$("#management_department_email").val($('#management_department_employee_id option:selected').attr("data-email"));
			$("#management_department_number").val($('#management_department_employee_id option:selected').attr("data-phone"));
		});
	});
	function fetch_state(country_id)
	{
		$.ajax({
			url:"<?= DOMAIN_NAME_PATH_ADMIN."ajax_state_fetch";?>",
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
			$("#state").val('<?php echo(isset($_POST['state']) && $_POST['state']!="" ? $_POST['state'] : "");?>');
		});;
	}
	function fetch_city(state_id)
	{
		$.ajax({
			url:"<?= DOMAIN_NAME_PATH_ADMIN."ajax_city_fetch";?>",
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
			$("#city").val('<?php echo(isset($_POST['city']) && $_POST['city']!="" ? $_POST['city'] : "");?>');
		});
	}
	//-->
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
		
		<!-- BODY -->   
		<div class="content-wrapper">
			<section class="content-header">
				<h1>Create New Agent</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Create New Agent</li>
				</ol>
			</section>
            <section class="content">
				<form name="form_new_agent" id="form_new_agent" method="POST" enctype="multipart/form-data">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
							<div class="col-md-12 row">
								<div class="box-header">
								   <h3 class="box-title">Company Details</h3>
								</div>
								<div class="box-body">
									<div id="" class="row rows">
										<div class="form-group col-md-6">
											<label for="company_name" class="form-label1">Company Name <font color="#FF0000">*</font> :</label>
											<input type="text" class="form-control form_input1 validate[required]" id="company_name" name="company_name" placeholder="Company Name" value="<?php echo(isset($_POST['company_name']) && $_POST['company_name']!='' ? $_POST['company_name'] : "");?>" tabindex="1">
										</div>
										<div class="form-group col-md-6">
											<label for="accounting_name" class="form-label1">Company Accounting Name <font color="#FF0000">*</font> :</label>
											<input type="text" class="form-control form_input1 validate[required]" id="accounting_name" name="accounting_name" placeholder="Company Accounting Name" value="<?php echo(isset($_POST['accounting_name']) && $_POST['accounting_name']!='' ? $_POST['accounting_name'] : "");?>" tabindex="2">
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-4">
											<label for="first_name" class="form-label1">First Name <font color="#FF0000">*</font> :</label>
											<input type="text" class="form-control form_input1 validate[required]" id="first_name" name="first_name" placeholder="First Name" value="<?php echo(isset($_POST['first_name']) && $_POST['first_name']!='' ? $_POST['first_name'] : "");?>" tabindex="3">
										</div>
										<div class="form-group col-md-4">
											<label for="middle_name" class="form-label1">Middle Name :</label>
											<input type="text" class="form-control form_input1" id="middle_name" name="middle_name" placeholder="Middle Name" value="<?php echo(isset($_POST['middle_name']) && $_POST['middle_name']!='' ? $_POST['middle_name'] : "");?>" tabindex="4">
										</div>
												
										<div class="form-group col-md-4">
											<label for="last_name" class="form-label1">Last Name <font color="#FF0000">*</font> :</label>
											<input type="text" class="form-control form_input1 validate[required]" id="last_name" name="last_name" placeholder="Last Name" value="<?php echo(isset($_POST['last_name']) && $_POST['last_name']!='' ? $_POST['last_name'] : "");?>" tabindex="5">
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-6">
											<label for="email_address" class="form-label1">Email <font color="#FF0000">*</font> :</label>
											<input type="email" class="form-control form_input1 validate[required]" id="email_address" name="email_address" placeholder="Email" value="<?php echo(isset($_POST['email_address']) && $_POST['email_address']!='' ? $_POST['email_address'] : "");?>" tabindex="6">
										</div>
										<div class="form-group col-md-6">
											<label for="designation" class="form-label1">Designation :</label>
											<input type="text" class="form-control form_input1" id="designation" name="designation" placeholder="Designation" value="<?php echo(isset($_POST['designation']) && $_POST['designation']!='' ? $_POST['designation'] : "");?>" tabindex="7">
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-6 radio_pad">
											<label for="iata_status" class="form-label1">IATA Status <font color="#FF0000">*</font> :</label>
											<select name="iata_status" id="iata_status" class="form-control form_input1 select_bg" tabindex="8">
												<option value="1" <?php echo(isset($_POST['iata_status']) && $_POST['iata_status']==1 ? 'selected="selected"' : "");?>>Approve</option>
												<option value="0" <?php echo(isset($_POST['iata_status']) && $_POST['iata_status']==0 ? 'selected="selected"' : "");?>>Not Approve</option>
											</select>
										</div>
										<div class="form-group col-md-6">
											<label for="nature_of_business" class="form-label1">Nature of Business:</label>
											<select name="nature_of_business" id="nature_of_business" class="form-control form_input1 select_bg" tabindex="9">
												<option value="">- Select -</option>
												<option value="Activity Supplier" <?php echo(isset($_POST['nature_of_business']) && $_POST['nature_of_business']=="Activity Supplier" ? 'selected="selected"' : "");?>>Activity Supplier</option>
												<option value="Hotel" <?php echo(isset($_POST['nature_of_business']) && $_POST['nature_of_business']=="Hotel" ? 'selected="selected"' : "");?>>Hotel</option>
												<option value="Hotel Chain" <?php echo(isset($_POST['nature_of_business']) && $_POST['nature_of_business']=="Hotel Chain" ? 'selected="selected"' : "");?>>Hotel Chain</option>
												<option value="Resturent" <?php echo(isset($_POST['nature_of_business']) && $_POST['nature_of_business']=="Resturent" ? 'selected="selected"' : "");?>>Resturent</option>
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
												<option value = "<?php echo $currency_val['id'];?>" <?php echo(isset($_POST['preferred_currency']) && $_POST['preferred_currency']==$currency_val['id'] ? 'selected="selected"' : "");?>><?php echo $currency_val['currency_name']." (".$currency_val['currency_code'].")";?></option>
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
													<option value = "<?php echo $country_val['id'];?>" <?php echo(isset($_POST['country']) && $_POST['country']==$country_val['id'] ? 'selected="selected"' : "");?>><?php echo $country_val['name'];?></option>
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
											<input type="text" class="form-control form_input1 validate[required]" id="zipcode" name="zipcode" placeholder="Pincode/Zipcode/Postcode" value="<?php echo(isset($_POST['zipcode']) && $_POST['zipcode']!='' ? $_POST['zipcode'] : "");?>" tabindex="14">
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-12">
											<label for="address" class="form-label1">Address <font color="#FF0000">*</font> :</label>
											<textarea class="form-control form_input1 validate[required]" rows="5" id="address" name="address" tabindex="15"><?php echo(isset($_POST['address']) && $_POST['address']!='' ? $_POST['address'] : "");?></textarea>
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
												<option value="<?php echo $time_key;?>" <?php echo(isset($_POST['timezone']) && $_POST['timezone']==$time_key ? "selected='selected'" : '')?>><?php echo $time_val;?></option>
											<?php
											}
											?>
											</select>
										</div>
										<div class="form-group col-md-6">
											<label for="telephone" class="form-label1">Telephone <font color="#FF0000">*</font> :</label>
											<input type="text" class="form-control form_input1 validate[required]" id="telephone" name="telephone" placeholder="Telephone" value="<?php echo(isset($_POST['telephone']) && $_POST['telephone']!='' ? $_POST['telephone'] : "");?>" tabindex="17">
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-6">
											<label for="mobile_number" class="form-label1">Mobile Number <font color="#FF0000">*</font> :</label>
											<input type="text" class="form-control form_input1 validate[required]" id="mobile_number" name="mobile_number" placeholder="Mobile Number" value="<?php echo(isset($_POST['mobile_number']) && $_POST['mobile_number']!='' ? $_POST['mobile_number'] : "");?>" tabindex="18">
										</div>
										<div class="form-group col-md-6">
											<label for="website" class="form-label1">Website :</label>
											<input type="text" class="form-control form_input1" id="website" name="website" placeholder="Website" value="<?php echo(isset($_POST['website']) && $_POST['website']!='' ? $_POST['website'] : "");?>" tabindex="19">
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-6">
											<label for="website" class="form-label1">Your Logo :</label>
											<input type="file" class="form-control form_input1" id="image" name="image" placeholder="Your Logo" tabindex="20">
										</div>
										<div class="form-group col-md-6">
											<label for="code" class="form-label1">Type The Code Shown <font color="#FF0000">*</font> :</label>
											<input type="text" class="form-control form_input1 validate[required]" id="code" name="code" placeholder="Type The Code Shown" value="<?php echo(isset($_POST['code']) && $_POST['code']!='' ? $_POST['code'] : "");?>" tabindex="21">
										</div>
										<div class="clearfix"></div>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="box box-primary">
							<div class="col-md-12 row">
								<div class="box-header">
								   <h3 class="box-title">Login Details</h3>
								</div>
								<div class="box-body">
									<div id="" class="row rows">
										<div class="form-group col-md-12">
											<label for="username" class="form-label1">Username <font color="#FF0000">*</font> :</label>
											<input type="text" class="form-control form_input1 validate[required]" id="username" name="username" placeholder="Username" value="<?php echo(isset($_POST['username']) && $_POST['username']!='' ? $_POST['username'] : "");?>" tabindex="22">
										</div>
										<div class="form-group col-md-6">
											<label for="password" class="form-label1">Password <font color="#FF0000">*</font> :</label>
											<input type="password" class="form-control form_input1 validate[required]" id="password" name="password" placeholder="Password" value="" tabindex="23">
										</div>
										<div class="form-group col-md-6">
											<label for="confirm_password" class="form-label1">Confirm Password <font color="#FF0000">*</font> :</label>
											<input type="password" class="form-control form_input1 validate[required, equals[password]]" id="confirm_password" name="confirm_password" placeholder="Confirm Password" value="" tabindex="24">
										</div>
										<div class="clearfix"></div>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>

						<div class="box box-primary">
							<div class="col-md-12 row">
								<div class="box-header">
								   <h3 class="box-title">Access Details</h3>
								</div>
								<div class="box-body">
									<div id="" class="row rows">
										<div id="" class="col-md-4">
											<div class="form-group fancy-form">
												<label for="pwd" class="form-label1">Account Department <font color="#FF0000">*</font> :</label>
											</div>
										</div>
										<div id="" class="col-md-8">
											<div class="row rows">
												<div id="" class="col-md-3">
													<div class="form-group fancy-form">
														<select name="account_department_employee_id" id="account_department_employee_id" class="form-control form_input1 validate[required]" tabindex="25">
															<option value="" data-name="" data-email="" data-phone="">Select</option>
														<?php
														if(isset($employee_data)):
															foreach($employee_data as $emp_key=>$emp_val):
														?>
															<option value="<?php echo $emp_val['id'];?>" <?php echo(isset($_POST['account_department_employee_id']) && $_POST['account_department_employee_id']==$emp_val['id'] ? 'selected="selected"' : "");?> data-name="<?php echo $emp_val['first_name']." ".$emp_val['last_name'];?>" data-email="<?php echo $emp_val['email_address'];?>" data-phone="<?php echo $emp_val['phone_number'];?>"><?php echo $emp_val['first_name']." ".$emp_val['last_name']." - ".$emp_val['email_address']." - ".$emp_val['phone_number'];?></option>
														<?php
															endforeach;
														endif;
														?>
														</select>
													</div>
												</div>
												<div id="" class="col-md-3">
													<div class="form-group fancy-form">
														<input type="text" class="form-control form_input1 validate[required]" id="account_department_name" name="account_department_name" placeholder="Name" value="<?php echo(isset($_POST['account_department_name']) && $_POST['account_department_name']!='' ? $_POST['account_department_name'] : "");?>" tabindex="26" readonly>
													</div>
												</div>
												<div id="" class="col-md-3">
													<div class="form-group fancy-form">
														<input type="email" class="form-control form_input1 validate[required, custom[email]]" id="account_department_email" name="account_department_email" placeholder="Email" value="<?php echo(isset($_POST['account_department_email']) && $_POST['account_department_email']!='' ? $_POST['account_department_email'] : "");?>" tabindex="27" readonly>
													</div>
												</div>
												<div id="" class="col-md-3">
													<div class="form-group fancy-form">
														<input type="text" class="form-control form_input1 validate[required]" id="account_department_number" name="account_department_number" placeholder="Contact Number" value="<?php echo(isset($_POST['account_department_number']) && $_POST['account_department_number']!='' ? $_POST['account_department_number'] : "");?>" tabindex="28" readonly>
													</div>
												</div>
											</div>
										</div>

										<div id="" class="col-md-4">
											<div class="form-group fancy-form">
												<label for="pwd" class="form-label1">Reservations/Operations Department <font color="#FF0000">*</font> :</label>
											</div>
										</div>
										<div id="" class="col-md-8">
											<div class="row rows">
												<div id="" class="col-md-3">
													<div class="form-group fancy-form">
														<select name="reservation_department_employee_id" id="reservation_department_employee_id" class="form-control form_input1 validate[required]" tabindex="29">
															<option value="" data-name="" data-email="" data-phone="">Select</option>
														<?php
														if(isset($employee_data)):
															foreach($employee_data as $emp_key=>$emp_val):
														?>
															<option value="<?php echo $emp_val['id'];?>" <?php echo(isset($_POST['reservation_department_employee_id']) && $_POST['reservation_department_employee_id']==$emp_val['id'] ? 'selected="selected"' : "");?> data-name="<?php echo $emp_val['first_name']." ".$emp_val['last_name'];?>" data-email="<?php echo $emp_val['email_address'];?>" data-phone="<?php echo $emp_val['phone_number'];?>"><?php echo $emp_val['first_name']." ".$emp_val['last_name']." - ".$emp_val['email_address']." - ".$emp_val['phone_number'];?></option>
														<?php
															endforeach;
														endif;
														?>
														</select>
													</div>
												</div>
												<div id="" class="col-md-3">
													<div class="form-group fancy-form">
														<input type="text" class="form-control form_input1 validate[required]" id="reservation_department_name" name="reservation_department_name" placeholder="Name" value="<?php echo(isset($_POST['reservation_department_name']) && $_POST['reservation_department_name']!='' ? $_POST['reservation_department_name'] : "");?>" tabindex="30" readonly>
													</div>
												</div>
												<div id="" class="col-md-3">
													<div class="form-group fancy-form">
														<input type="email" class="form-control form_input1 validate[required, custom[email]]" id="reservation_department_email" name="reservation_department_email" placeholder="Email" value="<?php echo(isset($_POST['reservation_department_email']) && $_POST['reservation_department_email']!='' ? $_POST['reservation_department_email'] : "");?>" tabindex="31" readonly>
													</div>
												</div>
												<div id="" class="col-md-3">
													<div class="form-group fancy-form">
														<input type="text" class="form-control form_input1 validate[required]" id="reservation_department_number" name="reservation_department_number" placeholder="Contact Number" value="<?php echo(isset($_POST['reservation_department_number']) && $_POST['reservation_department_number']!='' ? $_POST['reservation_department_number'] : "");?>" tabindex="32" readonly>
													</div>
												</div>
											</div>
										</div>
										<div id="" class="col-md-12">
											<div id="" class="row rows">
												<div id="" class="col-md-4">
													<div class="form-group fancy-form">
														<label for="pwd" class="form-label1">Management Department <font color="#FF0000">*</font> :</label>
													</div>
												</div>
												<div id="" class="col-md-8">
													<div class="row rows">
														<div id="" class="col-md-3">
															<div class="form-group fancy-form">
																<select name="management_department_employee_id" id="management_department_employee_id" class="form-control form_input1 validate[required]" tabindex="33">
																	<option value="" data-name="" data-email="" data-phone="">Select</option>
																<?php
																if(isset($employee_data)):
																	foreach($employee_data as $emp_key=>$emp_val):
																?>
																	<option value="<?php echo $emp_val['id'];?>" <?php echo(isset($_POST['management_department_employee_id']) && $_POST['management_department_employee_id']==$emp_val['id'] ? 'selected="selected"' : "");?> data-name="<?php echo $emp_val['first_name']." ".$emp_val['last_name'];?>" data-email="<?php echo $emp_val['email_address'];?>" data-phone="<?php echo $emp_val['phone_number'];?>"><?php echo $emp_val['first_name']." ".$emp_val['last_name']." - ".$emp_val['email_address']." - ".$emp_val['phone_number'];?></option>
																<?php
																	endforeach;
																endif;
																?>
																</select>
															</div>
														</div>
														<div id="" class="col-md-3">
															<div class="form-group fancy-form">
																<input type="text" class="form-control form_input1 validate[required]" id="management_department_name" name="management_department_name" placeholder="Name" value="<?php echo(isset($_POST['management_department_name']) && $_POST['management_department_name']!='' ? $_POST['management_department_name'] : "");?>" tabindex="34" readonly>
															</div>
														</div>
														<div id="" class="col-md-3">
															<div class="form-group fancy-form">
																<input type="email" class="form-control form_input1 validate[required, custom[email]]" id="management_department_email" name="management_department_email" placeholder="Email" value="<?php echo(isset($_POST['management_department_email']) && $_POST['management_department_email']!='' ? $_POST['management_department_email'] : "");?>" tabindex="35" readonly>
															</div>
														</div>
														<div id="" class="col-md-3">
															<div class="form-group fancy-form">
																<input type="text" class="form-control form_input1 validate[required]" id="management_department_number" name="management_department_number" placeholder="Contact Number" value="<?php echo(isset($_POST['management_department_number']) && $_POST['management_department_number']!='' ? $_POST['management_department_number'] : "");?>" tabindex="36" readonly>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>

						<div class="box box-primary">
							<div class="col-md-12 row">
								<div class="box-header">
								   <h3 class="box-title">Price Markup (%)</h3>
								</div>
								<div class="box-body">
									<div id="" class="row rows">
										<div class="form-group col-md-3">
											<label for="hotel_price" class="form-label1">Hotel :</label>
											<input type="text" class="form-control form_input1" id="hotel_price" name="hotel_price" placeholder="Hotel" value="<?php echo(isset($_POST['hotel_price']) && $_POST['hotel_price']!='' ? $_POST['hotel_price'] : "0.00");?>" tabindex="37">
										</div>
										<div class="form-group col-md-3">
											<label for="tour_price" class="form-label1">Tour :</label>
											<input type="text" class="form-control form_input1" id="tour_price" name="tour_price" placeholder="Tour" value="<?php echo(isset($_POST['tour_price']) && $_POST['tour_price']!='' ? $_POST['tour_price'] : "0.00");?>" tabindex="38">
										</div>
										<div class="form-group col-md-3">
											<label for="transfer_price" class="form-label1">Transfer :</label>
											<input type="text" class="form-control form_input1" id="transfer_price" name="transfer_price" placeholder="Transfer" value="<?php echo(isset($_POST['transfer_price']) && $_POST['transfer_price']!='' ? $_POST['transfer_price'] : "0.00");?>" tabindex="39">
										</div>
										<div class="form-group col-md-3">
											<label for="package_price" class="form-label1">Package :</label>
											<input type="text" class="form-control form_input1" id="package_price" name="package_price" placeholder="Package" value="<?php echo(isset($_POST['package_price']) && $_POST['package_price']!='' ? $_POST['package_price'] : "0.00");?>" tabindex="40">
										</div>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>

						<div class="box-footer">
							<input type="hidden" name="credit_balance" id="credit_balance" value="<?php echo $general_setting['default_credit_balance'];?>">
							<input type="hidden" name="token" value="<?php echo(tools::generateFormToken($verify_token)); ?>" />
							<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex="41">CREATE</button>
						</div>
					</div>
				</div>
				</form>
			</section>
		</div>
		<!-- BODY --> 

		<!-- FOOTER -->
		<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'footer.php');?>
		<!-- FOOTER -->

	</div>
</body>
</html>