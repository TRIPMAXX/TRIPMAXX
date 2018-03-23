<?php
require_once('loader.inc');
tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
if(isset($_GET['emp_id']) && $_GET['emp_id']!=""):
	$find_employee = tools::find("first", TM_DMC, '*', "WHERE id=:id ", array(":id"=>base64_decode($_GET['emp_id'])));
	if(!empty($find_employee)):
		$white_list_array = array('first_name', 'last_name', 'email_address', 'username', 'password', 'phone_number', 'account_type', 'creation_date', 'last_updated', 'status', 'token', 'id', 'btn_submit', 'confirm_password');
		$verify_token = "edit_employee";
		if(isset($_POST['btn_submit'])) {
			$_POST['id']=$find_employee['id'];
			$_POST['account_type']="E";
			if(tools::verify_token($white_list_array, $_POST, $verify_token)) {
				$uploaded_file_json_data="";				
				if(tools::module_data_exists_check("email_address = '".tools::stripcleantohtml($_POST['email_address'])."' AND id <> ".$find_employee['id']."", '', TM_DMC)) {
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH'] = 'This email address already exists.';
				} else if(tools::module_data_exists_check("username = '".tools::stripcleantohtml($_POST['username'])."' AND id <> ".$find_employee['id']."", '', TM_DMC)) {
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH'] = 'This username already exists.';
				} else {
					if($save_employee = tools::module_form_submission($uploaded_file_json_data, TM_DMC)) {
						$_SESSION['SET_TYPE'] = 'success';
						$_SESSION['SET_FLASH'] = 'Employee has been created successfully.';
						header("location:employees");
						exit;
					} else {
						$_SESSION['SET_TYPE'] = 'error';
						$_SESSION['SET_FLASH'] = 'We are having some probem. Please try again later.';
					}
				}
			} else {
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH'] = 'Access token mismatch. Please reload the page & try again.';
			}
		};
	else:
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = 'Invalid employee id.';
		header("location:employees");
		exit;
	endif;
else:
	$_SESSION['SET_TYPE'] = 'error';
	$_SESSION['SET_FLASH'] = 'Some data missing.';
	header("location:employees");
	exit;
endif;
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>EDIT EMPLOYEE</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script>
	jQuery(document).ready(function(){
		jQuery("#edit_employee_from").validationEngine();
	});
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

		<div class="content-wrapper">
			<section class="content-header">
				<h1>Edit Employee</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Edit Employee</li>
				</ol>
			</section>
			<section class="content">
				<form role="form" name="edit_employee_from" id="edit_employee_from"method="post" enctype="multipart/form-data">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
							<div class="col-md-12 row">
								<div class="box-header">
								   <h3 class="box-title">Employee Details</h3>
								</div>
								<div class="box-body">
									<div class="form-group col-md-6">
										<label for="first_name"><font color="#FF0000">*</font>First Name</label>
										<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['first_name']) && $_POST['first_name']!='' ? $_POST['first_name'] : $find_employee['first_name']);?>" name="first_name" id="first_name" placeholder="First Name"  tabindex = "1" />
									</div>
									<div class="form-group col-md-6">
										<label for="last_name"><font color="#FF0000">*</font>Last Name</label>
										<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['last_name']) && $_POST['last_name']!='' ? $_POST['last_name'] : $find_employee['last_name']);?>" name="last_name" id="last_name" placeholder="Last Name"  tabindex = "2" />
									</div>
									<div class="form-group col-md-6">
										<label for="email_address"><font color="#FF0000">*</font>Email</label>
										<input type="text" class="form-control validate[required] custom[email]"  value="<?php echo(isset($_POST['email_address']) && $_POST['email_address']!='' ? $_POST['email_address'] : $find_employee['email_address']);?>" name="email_address" id="email_address" placeholder="Email Address" tabindex = "3" />
									</div>
									<div class="form-group col-md-6">
										<label for="username"><font color="#FF0000">*</font>Username</label>
										<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['username']) && $_POST['username']!='' ? $_POST['username'] : $find_employee['username']);?>" name="username" id="username" placeholder="Username" tabindex = "4" />
									</div>
									<div class="form-group col-md-6">
										<label for="inputName" class="control-label">Password</label>
										<div class="input-icon right">
											<input type="password" class="form-control validate[optional]"  placeholder = "Password" name="password" id="password" tabindex = "5" />
										</div>
										<div style = "margin-bottom:10px;color:#ff0000">Note: Leave blank if you do not wish to change existing password.</div>
									</div>
									<div class="form-group col-md-6">
										<label for="inputName" class="control-label">Confirm Password</label>
										<div class="input-icon right">
											<input type="password" class="form-control validate[optional, equals[password]]"  placeholder = "Confirm Password" name="confirm_password" id="confirm_password" tabindex = "6" />
										</div>
										<div style = "margin-bottom:10px;color:#ff0000">Note: Leave blank if you do not wish to change existing password.</div>
									</div>
								</div>
							</div>
							<!-- <div class="col-md-12 row">
								<div class="box-footer">
									<input type="hidden" name="token" value="" />
									<input type = "hidden" name = "id" id = "id" value = "" />
									<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex = "4">Edit</button>
								</div>
							</div> -->
							<div class="clearfix"></div>
						</div>
					</div>
				</div>

				<!-- <div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
							<div class="col-md-12 row">
								<div class="box-header">
								   <h3 class="box-title">Access Permission</h3>
								</div>
								<div class="box-body">
									<div class="form-group col-md-12">
										<input type = "checkbox" name = "seelct_all" />&nbsp;<b>Select All</b>
									</div>
									<div class="form-group col-md-3">
										<label for="inputName" class="control-label">DASHBOARD</label>
										<br/>
										<input type = "checkbox" name = "seelct_all" />&nbsp;Allow Access
									</div>
									<div class="form-group col-md-3">
										<label for="inputName" class="control-label">BOOKING</label>
										<br/>
										<input type = "checkbox" name = "seelct_all" />&nbsp;Create&nbsp;<input type = "checkbox" name = "seelct_all" />&nbsp;Edit&nbsp;<input type = "checkbox" name = "seelct_all" />&nbsp;Delete
									</div>
									<div class="form-group col-md-3">
										<label for="inputName" class="control-label">PACKAGE</label>
										<br/>
										<input type = "checkbox" name = "seelct_all" />&nbsp;Create&nbsp;<input type = "checkbox" name = "seelct_all" />&nbsp;Edit&nbsp;<input type = "checkbox" name = "seelct_all" />&nbsp;Delete
									</div>
									<div class="form-group col-md-3">
										<label for="inputName" class="control-label">TOUR</label>
										<br/>
										<input type = "checkbox" name = "seelct_all" />&nbsp;Create&nbsp;<input type = "checkbox" name = "seelct_all" />&nbsp;Edit&nbsp;<input type = "checkbox" name = "seelct_all" />&nbsp;Delete
									</div>

									<div class="form-group col-md-3">
										<label for="inputName" class="control-label">TRAVEL</label>
										<br/>
										<input type = "checkbox" name = "seelct_all" />&nbsp;Create&nbsp;<input type = "checkbox" name = "seelct_all" />&nbsp;Edit&nbsp;<input type = "checkbox" name = "seelct_all" />&nbsp;Delete
									</div>
									<div class="form-group col-md-9">
										<label for="inputName" class="control-label">HOTEL</label>
										<br/>
										<input type = "checkbox" name = "seelct_all" />&nbsp;Create Hotel Attribute&nbsp;<input type = "checkbox" name = "seelct_all" />&nbsp;Edit Hotel Attribute&nbsp;<input type = "checkbox" name = "seelct_all" />&nbsp;Delete Hotel Attribute&nbsp;<input type = "checkbox" name = "seelct_all" />&nbsp;Create Hotel&nbsp;<input type = "checkbox" name = "seelct_all" />&nbsp;Edit Hotel&nbsp;<input type = "checkbox" name = "seelct_all" />&nbsp;Delete Hotel&nbsp;<input type = "checkbox" name = "seelct_all" />&nbsp;Create Hotel Room&nbsp;<input type = "checkbox" name = "seelct_all" />&nbsp;Edit Hotel Rooms&nbsp;<input type = "checkbox" name = "seelct_all" />&nbsp;Delete Hotel Rooms
									</div>

									<div class="form-group col-md-3">
										<label for="inputName" class="control-label">EMPLOYEE</label>
										<br/>
										<input type = "checkbox" name = "seelct_all" />&nbsp;Create&nbsp;<input type = "checkbox" name = "seelct_all" />&nbsp;Edit&nbsp;<input type = "checkbox" name = "seelct_all" />&nbsp;Delete
									</div>
									<div class="form-group col-md-9">
										<label for="inputName" class="control-label">GSA</label>
										<br/>
										<input type = "checkbox" name = "seelct_all" />&nbsp;Create&nbsp;<input type = "checkbox" name = "seelct_all" />&nbsp;Edit&nbsp;<input type = "checkbox" name = "seelct_all" />&nbsp;Delete&nbsp;<input type = "checkbox" name = "seelct_all" />&nbsp;Manage Sub Agent&nbsp;<input type = "checkbox" name = "seelct_all" />&nbsp;Manage Booking&nbsp;<input type = "checkbox" name = "seelct_all" />&nbsp;Manage Earning
									</div>

									<div class="form-group col-md-6">
										<label for="inputName" class="control-label">AGENT</label>
										<br/>
										<input type = "checkbox" name = "seelct_all" />&nbsp;Create&nbsp;<input type = "checkbox" name = "seelct_all" />&nbsp;Edit&nbsp;<input type = "checkbox" name = "seelct_all" />&nbsp;Delete&nbsp;<input type = "checkbox" name = "seelct_all" />&nbsp;Manage Booking&nbsp;<input type = "checkbox" name = "seelct_all" />&nbsp;Manage Earning
									</div>
									<div class="form-group col-md-3">
										<label for="inputName" class="control-label">SUPPLIER</label>
										<br/>
										<input type = "checkbox" name = "seelct_all" />&nbsp;Create&nbsp;<input type = "checkbox" name = "seelct_all" />&nbsp;Edit&nbsp;<input type = "checkbox" name = "seelct_all" />&nbsp;Delete
									</div>
									<div class="form-group col-md-3">
										<label for="inputName" class="control-label">SETTINGS</label>
										<br/>
										<input type = "checkbox" name = "seelct_all" />&nbsp;General Settings&nbsp;<input type = "checkbox" name = "seelct_all" />&nbsp;Manage Currencies&nbsp;<input type = "checkbox" name = "seelct_all" />&nbsp;Manage Home Slider
									</div>

									<div class="form-group col-md-3">
										<label for="inputName" class="control-label">CMS</label>
										<br/>
										<input type = "checkbox" name = "seelct_all" />&nbsp;Edit&nbsp;<input type = "checkbox" name = "seelct_all" />&nbsp;Delete
									</div>
									<div class="form-group col-md-3">
										<label for="inputName" class="control-label">EMAIL TEMPLATE</label>
										<br/>
										<input type = "checkbox" name = "seelct_all" />&nbsp;Edit&nbsp;<input type = "checkbox" name = "seelct_all" />&nbsp;Delete
									</div>
									<div class="form-group col-md-3">
										<label for="inputName" class="control-label">REPORT</label>
										<br/>
										<input type = "checkbox" name = "seelct_all" />&nbsp;Allow Access
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div> -->
				<div class="box-footer">
					<input type="hidden" name="token" value="<?php echo(tools::generateFormToken($verify_token)); ?>" />
					<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex = "7">UPDATE</button>
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