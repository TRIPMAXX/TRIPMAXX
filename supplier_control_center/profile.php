<?php
require_once('loader.inc');
require_once('core/microservices/email_template.php');

tools::module_validation_check(@$_SESSION['SESSION_DATA_SUPPLIER']['id'], DOMAIN_NAME_PATH_SUPPLIER.'login');

$white_list_array = array('first_name', 'last_name', 'company_name', 'type_of_business', 'email_address', 'password', 'phone_number', 'address', 'country', 'supplier_code', 'creation_date', 'last_updated', 'status', 'token', 'id', 'btn_submit', 'confirm_password', 'supplier_priority');

$verify_token = "profile";

if(isset($_POST['btn_submit'])) {
	if(tools::verify_token($white_list_array, $_POST, $verify_token)) {
		if(tools::module_data_exists_check("email_address = '".tools::stripcleantohtml($_POST['email_address'])."' AND id<>".@$_SESSION['SESSION_DATA_SUPPLIER']['id']."", '', TM_SUPPLIER)) {
			$_SESSION['SET_TYPE'] = 'error';
			$_SESSION['SET_FLASH'] = 'This email address already exists.';
		} elseif(tools::module_data_exists_check("supplier_code = '".tools::stripcleantohtml($_POST['supplier_code'])."' AND id<>".@$_SESSION['SESSION_DATA_SUPPLIER']['id']."", '', TM_SUPPLIER)) {
			$_SESSION['SET_TYPE'] = 'error';
			$_SESSION['SET_FLASH'] = 'This supplier code already exists.';
		} else  {
			if($update_profile = tools::module_form_submission('', TM_SUPPLIER)) {
				$_SESSION['SET_TYPE'] = 'success';
				$_SESSION['SET_FLASH'] = 'Profile has been updated successfully.';
			} else {
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH'] = 'We are having some probem. Please try again later.';
			}
		}
	} else {
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = 'Access token mismatch. Please reload the page & try again.';
	}
}
$profile = tools::find("first", TM_SUPPLIER, '*', "WHERE id = :id", array(':id'=>@$_SESSION['SESSION_DATA_SUPPLIER']['id']));
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER_SUPPLIER);?>MANAGE PROFILE</title>
	<?php require_once(SUPPLIER_CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script>
	jQuery(document).ready(function(){
		jQuery("#form_profile").validationEngine();
	});
	</script>
	<!-- JAVASCRIPT CODE -->
</head>
<body class="skin-purple">
	<div class="wrapper">
		
		<!-- TOP HEADER -->
		<?php require_once(SUPPLIER_CONTROL_CENTER_COMMON_FILE_PATH.'header.php');?>		
		<!-- TOP HEADER -->

		<!-- LEFT MENU -->
		<?php require_once(SUPPLIER_CONTROL_CENTER_COMMON_FILE_PATH.'menu.php');?>
		<!-- LEFT MENU -->    

		<div class="content-wrapper">
			<section class="content-header">
				<h1>Edit Profile</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo(DOMAIN_NAME_PATH_SUPPLIER);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Edit Profile </li>
				</ol>
			</section>
			<section class="content">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
						<form role="form" name="form_profile" id="form_profile" method="POST" action = "<?php echo(DOMAIN_NAME_PATH_SUPPLIER);?>profile">
						<div class="col-md-12 row">
							<div class="box-body">
								<div class="form-group col-md-6">
									<label for="first_name">First Name&nbsp;<font color="#FF0000">*</font></label>
									<input type = "text" class = "form-control validate[required]"  value = "<?php echo((isset($_POST['first_name']) && $_POST['first_name']!='' ? $_POST['first_name'] : $profile['first_name']));?>" name = "first_name" id = "first_name" placeholder = "First Name" data-errormessage-value-missing="First Name is required!" tabindex = "1" />
								</div>
								<div class="form-group col-md-6">
									<label for="last_name">Last Name&nbsp;<font color="#FF0000">*</font></label>
									<input type = "text" class = "form-control validate[required]"  value = "<?php echo((isset($_POST['last_name']) && $_POST['last_name']!='' ? $_POST['last_name'] : $profile['last_name']));?>" name = "last_name" id = "last_name" placeholder = "Last Name" data-errormessage-value-missing="Last Name is required!" tabindex = "2" />
								</div>
								<div class="clearfix"></div>
								<div class="form-group col-md-6">
									<label for="company_name" class="control-label">Company Name</label>
									<input type="text" class="form-control validate[optional]" placeholder = "Company Name" name="company_name" id="company_name" tabindex = "3" value="<?php echo(isset($_POST['company_name']) && $_POST['company_name']!='' ? $_POST['company_name'] : (isset($profile['company_name']) && $profile['company_name']!='' ? $profile['company_name'] : ""));?>"/>
								</div>
								<div class="form-group col-md-6">
									<label for="type_of_business" class="control-label">Type Of Business <font color="#FF0000">*</font></label>
									<select class="form-control validate[required]" name="type_of_business" id="type_of_business" tabindex = "4">
										<option value = "">Type Of Business</option>
										<option value = "Ground (Transport)" <?php echo(isset($_POST['type_of_business']) && $_POST['type_of_business']=='Ground (Transport)' ? 'selected="selected"' : (isset($profile['type_of_business']) && $profile['type_of_business']=='Ground (Transport)' ? 'selected="selected"' : ""));?>>Ground (Transport)</option>
										<option value = "Hotel" <?php echo(isset($_POST['type_of_business']) && $_POST['type_of_business']=='Hotel' ? 'selected="selected"' : (isset($profile['type_of_business']) && $profile['type_of_business']=='Hotel' ? 'selected="selected"' : ""));?>>Hotel</option>
										<option value = "Restaurent" <?php echo(isset($_POST['type_of_business']) && $_POST['type_of_business']=='Restaurent' ? 'selected="selected"' : (isset($profile['type_of_business']) && $profile['type_of_business']=='Restaurent' ? 'selected="selected"' : ""));?>>Restaurent</option>
									</select>
								</div>
								<div class="clearfix"></div>
								<div class="form-group col-md-6">
									<label for="email_address">Email Address <font color="#FF0000">*</font></label>
									<input type = "text" class = "form-control validate[required] custom[email]"  value = "<?php echo((isset($_POST['email_address']) && $_POST['email_address']!='' ? $_POST['email_address'] : $profile['email_address']));?>" name = "email_address" id = "email_address" placeholder = "Email Address" data-errormessage-value-missing = "Email Address is required!" tabindex = "5" />
								</div>
								<div class="form-group col-md-6">
									<label for="phone_number" class="control-label">Contact Phone Number <font color="#FF0000">*</font></label>
									<input type="text" class="form-control validate[required, custom[phone]]"  placeholder = "Contact Phone Number" name="phone_number" id="phone_number" tabindex = "6" value="<?php echo(isset($_POST['phone_number']) && $_POST['phone_number']!='' ? $_POST['phone_number'] : (isset($profile['phone_number']) && $profile['phone_number']!='' ? $profile['phone_number'] : ""));?>"/>
								</div>
								<div class="clearfix"></div>
								<div class="form-group col-md-6">
									<label for="password" class="control-label">Password</label>
									<div class="input-icon right">
										<input type = "password" class = "form-control "  placeholder = "Password" name = "password" id = "password" tabindex = "7" />
									</div>
									<div style = "margin-bottom:10px;color:#ff0000">Note: Leave blank if you do not wish to change existing password.</div>
								</div>
								<div class="form-group col-md-6">
									<label for="confirm_password" class="control-label">Confirm Password</label>
									<div class="input-icon right">
										<input type = "password" class = "form-control validate[equals[password]]"  placeholder = "Confirm Password" name = "confirm_password" id = "confirm_password" tabindex = "8" />
									</div>
									<div style = "margin-bottom:10px;color:#ff0000">Note: Leave blank if you do not wish to change existing password.</div>
								</div>
								<div class="clearfix"></div>
								<div class="form-group col-md-12">
									<label for="address" class="control-label">Contact Address <font color="#FF0000">*</font></label>
									<textarea class="form-control validate[required]"  placeholder = "Contact Address" name="address" id="address" tabindex = "9"><?php echo(isset($_POST['address']) && $_POST['address']!='' ? $_POST['address'] : (isset($profile['address']) && $profile['address']!='' ? $profile['address'] : ""));?></textarea>
								</div>
								<div class="form-group col-md-6">
									<label for="country" class="control-label">Operating Country <font color="#FF0000">*</font></label>
									<select class="form-control validate[required]" name="country" id="country" tabindex = "10">
										<?php
										$country_data = tools::find("all", TM_COUNTRIES, '*', "WHERE :all ", array(":all"=>1));
										if(!empty($country_data)):
											foreach($country_data as $country_key=>$country_val):
										?>
											<option value = "<?php echo $country_val['id'];?>" <?php echo(isset($_POST['country']) && $_POST['country']==$country_val['id'] ? 'selected="selected"' : (isset($profile['country']) && $profile['country']==$country_val['id'] ? 'selected="selected"' : ""));?>><?php echo $country_val['name'];?></option>
										<?php
											endforeach;
										endif;
										?>
									</select>
								</div>
								<div class="form-group col-md-6">
									<label for="supplier_code" class="control-label">Supplier Account Code <font color="#FF0000">*</font></label>
									<input type="text" class="form-control validate[required]"  placeholder = "Supplier Account Code" name="supplier_code" id="supplier_code" tabindex = "11" value="<?php echo(isset($_POST['supplier_code']) && $_POST['supplier_code']!='' ? $_POST['supplier_code'] : (isset($profile['supplier_code']) && $profile['supplier_code']!='' ? $profile['supplier_code'] : ""));?>"/>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
						<div class="col-md-12 row">
							<div class="box-footer">
								<input type = "hidden" name = "token" value = "<?php echo(tools::generateFormToken($verify_token)); ?>" />
								<input type = "hidden" name = "id" id = "id" value = "<?php echo($profile['id']);?>" />
								<button type = "submit" id = "btn_submit" name = "btn_submit" class = "btn btn-primary" tabindex = "12">UPDATE</button>
							</div>
						</div>
						</form>
						<div class="clearfix"></div>
					</div>
				</div>
			</section>
		</div>
		<!-- BODY -->

		<!-- FOOTER -->
		<?php require_once(SUPPLIER_CONTROL_CENTER_COMMON_FILE_PATH.'footer.php');?>
		<!-- FOOTER -->
	</div>
</body>
</html>