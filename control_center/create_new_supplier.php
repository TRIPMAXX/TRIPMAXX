<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');	
	$white_list_array = array('first_name', 'last_name', 'company_name', 'type_of_business', 'email_address', 'password', 'phone_number', 'address', 'country', 'supplier_code', 'creation_date', 'last_updated', 'status', 'token', 'id', 'btn_submit', 'confirm_password');
	$verify_token = "create_new_supllier";
	$autentication_data=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.SUPPLIER_API_PATH."authorized.php"));
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
			curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.SUPPLIER_API_PATH."country/read.php");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$return_data = curl_exec($ch);
			curl_close($ch);
			$return_data_arr=json_decode($return_data, true);
			$country_data=array();
			if($return_data_arr['status']=="success"):
				$country_data=$return_data_arr['results'];
			else:
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
			endif;

			if(isset($_POST['btn_submit'])) {
				$_POST['status']=1;
				if(tools::verify_token($white_list_array, $_POST, $verify_token)) {
					$post_data['data']=$_POST;
					$post_data_str=json_encode($post_data);
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_HEADER, false);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
					curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.SUPPLIER_API_PATH."supplier/create.php");
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					$return_data = curl_exec($ch);
					curl_close($ch);
					$return_data_arr=json_decode($return_data, true);
					$supplier_save_data=array();
					if($return_data_arr['status']=="success")
					{
						$_SESSION['SET_TYPE'] = 'success';
						$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
						header("location:supplier");
						exit;
					}
					else
					{
						$_SESSION['SET_TYPE'] = 'error';
						$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
					}
				} else {
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH'] = 'Access token mismatch. Please reload the page & try again.';
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
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>CREATE NEW SUPPLIER</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	jQuery(document).ready(function(){
		jQuery("#form_create_supplier").validationEngine();
	});
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
				<h1>Create New Supplier</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Create New Supplier</li>
				</ol>
			</section>
            <section class="content">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
							<form role="form" name="form_create_supplier" id="form_create_supplier" method="POST">
								<div class="col-md-12 row">
									<div class="box-body">
										<div class="form-group col-md-6">
											<label for="first_name">First Name <font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['first_name']) && $_POST['first_name']!='' ? $_POST['first_name'] : "");?>" name="first_name" id="first_name" placeholder="First Name" tabindex = "1" />
										</div>
										<div class="form-group col-md-6">
											<label for="last_name">Last Name <font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['last_name']) && $_POST['last_name']!='' ? $_POST['last_name'] : "");?>" name="last_name" id="last_name" placeholder="Last Name" tabindex = "2" />
										</div>
										<div class="form-group col-md-6">
											<label for="company_name" class="control-label">Company Name</label>
											<input type="text" class="form-control validate[optional]" placeholder = "Company Name" name="company_name" id="company_name" tabindex = "3" value="<?php echo(isset($_POST['company_name']) && $_POST['company_name']!='' ? $_POST['company_name'] : "");?>"/>
										</div>
										<div class="form-group col-md-6">
											<label for="type_of_business" class="control-label">Type Of Business <font color="#FF0000">*</font></label>
											<select class="form-control validate[required]" name="type_of_business" id="type_of_business" tabindex = "4">
												<option value = "">Type Of Business</option>
												<option value = "Ground (Transport)" <?php echo(isset($_POST['type_of_business']) && $_POST['type_of_business']=='Ground (Transport)' ? 'selected="selected"' : "");?>>Ground (Transport)</option>
												<option value = "Hotel" <?php echo(isset($_POST['type_of_business']) && $_POST['type_of_business']=='Hotel' ? 'selected="selected"' : "");?>>Hotel</option>
												<option value = "Restaurent" <?php echo(isset($_POST['type_of_business']) && $_POST['type_of_business']=='Restaurent' ? 'selected="selected"' : "");?>>Restaurent</option>
											</select>
										</div>
										<div class="form-group col-md-6">
											<label for="email_address" class="control-label">Email Address <font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required,custom[email]]"  placeholder = "Email Address" name="email_address" id="email_address" tabindex = "5" value="<?php echo(isset($_POST['email_address']) && $_POST['email_address']!='' ? $_POST['email_address'] : "");?>"/>
										</div>
										<div class="form-group col-md-6">
											<label for="phone_number" class="control-label">Contact Phone Number <font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required, custom[phone]]"  placeholder = "Contact Phone Number" name="phone_number" id="phone_number" tabindex = "6" value="<?php echo(isset($_POST['phone_number']) && $_POST['phone_number']!='' ? $_POST['phone_number'] : "");?>"/>
										</div>
										<div class="form-group col-md-6">
											<label for="password" class="control-label">Account Password <font color="#FF0000">*</font></label>
											<input type="password" class="form-control validate[required]"  placeholder = "Account Password" name="password" id="password" tabindex = "7" value=""/>
										</div>
										<div class="form-group col-md-6">
											<label for="confirm_password" class="control-label">Confirm Account Password <font color="#FF0000">*</font></label>
											<input type="password" class="form-control validate[required, equals[password]]"  placeholder = "Confirm Account Password" name="confirm_password" id="confirm_password" tabindex = "8"  value=""/>
										</div>
										<div class="form-group col-md-12">
											<label for="address" class="control-label">Contact Address <font color="#FF0000">*</font></label>
											<textarea class="form-control validate[required]"  placeholder = "Contact Address" name="address" id="address" tabindex = "9"><?php echo(isset($_POST['address']) && $_POST['address']!='' ? $_POST['address'] : "");?></textarea>
										</div>
										<div class="form-group col-md-6">
											<label for="country" class="control-label">Operating Country <font color="#FF0000">*</font></label>
											<select class="form-control validate[required]" name="country" id="country" tabindex = "10">
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
										<div class="form-group col-md-6">
											<label for="supplier_code" class="control-label">Supplier Account Code <font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  placeholder = "Supplier Account Code" name="supplier_code" id="supplier_code" tabindex = "11" value="<?php echo(isset($_POST['supplier_code']) && $_POST['supplier_code']!='' ? $_POST['supplier_code'] : "");?>"/>
										</div>
									</div>
								</div>
								<div class="col-md-12 row">
									<div class="box-footer">
										<input type="hidden" name="token" value="<?php echo(tools::generateFormToken($verify_token)); ?>" />
										<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex = "13">CREATE</button>
									</div>
								</div>
							</form>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
			</section>
        </div>
		<!-- BODY -->
         
		<!-- FOOTER -->
		<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'footer.php');?>
		<!-- FOOTER -->

</div>
</body>
</html>