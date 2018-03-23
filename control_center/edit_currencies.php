<?php
require_once('loader.inc');
tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
if(isset($_GET['currency_id']) && $_GET['currency_id']!=""):
	$find_currency = tools::find("first", TM_CURRENCIES, '*', "WHERE id=:id ", array(":id"=>base64_decode($_GET['currency_id'])));
	if(!empty($find_currency)):
		$white_list_array = array('currency_code', 'currency_name', 'hex_code', 'serial_number', 'status', 'token', 'id', 'btn_submit');
		$verify_token = "edit_currency";
		if(isset($_POST['btn_submit'])) {
			$_POST['id']=$find_currency['id'];
			if(tools::verify_token($white_list_array, $_POST, $verify_token)) {
				$uploaded_file_json_data="";				
				if(tools::module_data_exists_check("currency_name = '".tools::stripcleantohtml($_POST['currency_name'])."' AND id <> ".$find_currency['id']."", '', TM_CURRENCIES)) {
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH'] = 'This page heading already exists.';
				} else {
					if($save_cms_data = tools::module_form_submission($uploaded_file_json_data, TM_CURRENCIES)) {
						$_SESSION['SET_TYPE'] = 'success';
						$_SESSION['SET_FLASH'] = 'Currency has been updated successfully.';
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
		};
	else:
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = 'Invalid cms id.';
		header("location:cms");
		exit;
	endif;
else:
	$_SESSION['SET_TYPE'] = 'error';
	$_SESSION['SET_FLASH'] = 'Some data missing.';
	header("location:cms");
	exit;
endif;
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>EDIT CURRENCIES</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script>
	jQuery(document).ready(function(){
		jQuery("#form_currency_add").validationEngine();
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
               <h1>Edit Currencies</h1>
               <ol class="breadcrumb">
                  <li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                  <li class="active">Edit Currencies</li>
               </ol>
            </section>
            <section class="content">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
							<form role="form" name="form_currency_edit" id="form_currency_edit" method="post" enctype="mulimedeia/form-data">
								<div class="col-md-12 row">
									<div class="box-body">
										<div class="form-group col-md-6">
											<label for="currency_name" class="control-label">Currency Title<font color="#FF0000">*</font></label>
											<div class="input-icon right">
												<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['currency_name']) && $_POST['currency_name']!='' ? $_POST['currency_name'] : $find_currency['currency_name']);?>" name="currency_name" id="currency_name" placeholder="Currency Title" tabindex = "1" />
											</div>
										</div>
										<div class="form-group col-md-6">
											<label for="currency_code" class="control-label">Currency Short Code<font color="#FF0000">*</font></label>
											<div class="input-icon right">
												<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['currency_code']) && $_POST['currency_code']!='' ? $_POST['currency_code'] : $find_currency['currency_code']);?>" name="currency_code" id="currency_code" placeholder="Currency Short Code" tabindex = "2" />
											</div>
										</div>
										<div class="form-group col-md-6">
											<label for="hex_code" class="control-label">Hex/ASCII Code</label>
											<div class="input-icon right">
												<input type="text" class="form-control"  value="<?php echo(isset($_POST['hex_code']) && $_POST['hex_code']!='' ? $_POST['hex_code'] : $find_currency['hex_code']);?>" name="hex_code" id="hex_code" placeholder="Hex/ASCII Code" tabindex = "3" />
											</div>
										</div>
										<div class="form-group col-md-6">
											<label for="serial_number" class="control-label">Serial Number</label>
											<div class="input-icon right">
												<input type="text" class="form-control"  value="<?php echo(isset($_POST['serial_number']) && $_POST['serial_number']!='' ? $_POST['serial_number'] : $find_currency['serial_number']);?>" name="serial_number" id="serial_number" placeholder="Serial Number" tabindex = "4" />
											</div>
										</div>
										<div class="form-group col-md-6">
											<label for="status" class="control-label">Status</label>
											<select class="form-control" tabindex = "8" name="status" id="status" >
												<option value = "1" <?php echo(isset($_POST['status']) && $_POST['status']==1 ? 'selected="selected"' : ($find_currency['status']==1 ? 'selected="selected"' : ""));?>>Active</option>
												<option value = "0" <?php echo(isset($_POST['status']) && $_POST['status']==0 ? 'selected="selected"' : ($find_currency['status']==0 ? 'selected="selected"' : ""));?>>Inactive</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-md-12 row">
									<div class="box-footer">
										<input type="hidden" name="token" value="<?php echo(tools::generateFormToken($verify_token)); ?>" />
										<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex = "4">UPDATE</button>
									</div>
								</div>
							</form>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
			</section>
		</div>
		
		<!-- FOOTER -->
		<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'footer.php');?>
		<!-- FOOTER -->
	</div>
</body>
</html>