<?php
require_once('loader.inc');
tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
$white_list_array = array('currency_code', 'currency_name', 'hex_code', 'serial_number', 'status', 'token', 'id', 'btn_submit');
$verify_token = "create_new_currency";
if(isset($_POST['btn_submit'])) {
	$_POST['status']=1;
	if(tools::verify_token($white_list_array, $_POST, $verify_token)) {
		$uploaded_file_json_data="";
		if(tools::module_data_exists_check("currency_name = '".tools::stripcleantohtml($_POST['currency_name'])."'", '', TM_CURRENCIES)) {
			$_SESSION['SET_TYPE'] = 'error';
			$_SESSION['SET_FLASH'] = 'This currency name already exists.';
		} else {
			if($save_currency = tools::module_form_submission($uploaded_file_json_data, TM_CURRENCIES)) {
				$_SESSION['SET_TYPE'] = 'success';
				$_SESSION['SET_FLASH'] = 'Currency has been created successfully.';
				header("location:currencies");
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
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>CREATE NEW CURRENCY</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	jQuery(document).ready(function(){
		jQuery("#form_currency_add").validationEngine();
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
				<h1>Create New Currency</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Create New Currency</li>
				</ol>
			</section>
            <section class="content">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
							<div class="box box-primary">
								<form  name="form_currency_add" id="form_currency_add" method="POST">
									<div class="col-md-12 row">
										<div class="box-body">
											<div class="form-group col-md-6">
												<label for="currency_name" class="control-label">Currency Title<font color="#FF0000">*</font></label>
												<div class="input-icon right">
													<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['currency_name']) && $_POST['currency_name']!='' ? $_POST['currency_name'] : "");?>" name="currency_name" id="currency_name" placeholder="Currency Title" tabindex = "1" />
												</div>
											</div>
											<div class="form-group col-md-6">
												<label for="currency_code" class="control-label">Currency Short Code<font color="#FF0000">*</font></label>
												<div class="input-icon right">
													<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['currency_code']) && $_POST['currency_code']!='' ? $_POST['currency_code'] : "");?>" name="currency_code" id="currency_code" placeholder="Currency Short Code" tabindex = "2" />
												</div>
											</div>
											<div class="form-group col-md-6">
												<label for="hex_code" class="control-label">Hex/ASCII Code</label>
												<div class="input-icon right">
													<input type="text" class="form-control"  value="<?php echo(isset($_POST['hex_code']) && $_POST['hex_code']!='' ? $_POST['hex_code'] : "");?>" name="hex_code" id="hex_code" placeholder="Hex/ASCII Code" tabindex = "3" />
												</div>
											</div>
											<div class="form-group col-md-6">
												<label for="serial_number" class="control-label">Serial Number</label>
												<div class="input-icon right">
													<input type="text" class="form-control"  value="<?php echo(isset($_POST['serial_number']) && $_POST['serial_number']!='' ? $_POST['serial_number'] : "");?>" name="serial_number" id="serial_number" placeholder="Serial Number" tabindex = "4" />
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-12 row">
										<div class="box-footer">
											<input type="hidden" name="token" value="<?php echo(tools::generateFormToken($verify_token)); ?>" />
											<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex = "5">CREATE</button>
										</div>
									</div>
								</form>
								<div class="clearfix"></div>
							</div>
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