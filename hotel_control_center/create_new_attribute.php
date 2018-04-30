<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA_HOTEL']['id'], DOMAIN_NAME_PATH_HOTEL.'login');
	$white_list_array = array('attribute_name', 'serial_number', 'type', 'status', 'token', 'id', 'btn_submit');
	$verify_token = "create_new_attribute_hotel";
	$uploaded_file_json_data="";
	if(isset($_POST['btn_submit'])) {
		if(tools::verify_token($white_list_array, $_POST, $verify_token)) 
		{
			if(tools::module_data_exists_check("attribute_name = '".tools::stripcleantohtml($_POST['attribute_name'])."'", '', TM_ATTRIBUTES)) {
				$_SESSION['SET_TYPE']="error";
				$_SESSION['SET_FLASH'] = 'This attribute name already exists.';		
			} 
			else 
			{
				if($save_attribute = tools::module_form_submission($uploaded_file_json_data, TM_ATTRIBUTES)) {
					$_SESSION['SET_TYPE']="success";
					$_SESSION['SET_FLASH'] = 'Attribute has been created successfully.';
					header("location:attributes");
					exit;
				} else {
					$_SESSION['SET_TYPE']="error";
					$_SESSION['SET_FLASH'] = 'We are having some probem. Please try again later.';
				}
			}			
		} 
		else 
		{
			$_SESSION['SET_TYPE'] = 'error';
			$_SESSION['SET_FLASH'] = 'Access token mismatch. Please reload the page & try again.';
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER_HOTEL);?>CREATE NEW HOTEL ATTRIBUTE</title>
	<?php require_once(HOTEL_CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	jQuery(document).ready(function(){
		jQuery("#new_attributes_form").validationEngine();
	});
	//-->
	</script>
	<!-- JAVASCRIPT CODE -->
</head>
<body class="skin-purple">
	<div class="wrapper">
      
		<!-- TOP HEADER -->
		<?php require_once(HOTEL_CONTROL_CENTER_COMMON_FILE_PATH.'header.php');?>		
		<!-- TOP HEADER -->

		<!-- LEFT MENU -->
		<?php require_once(HOTEL_CONTROL_CENTER_COMMON_FILE_PATH.'menu.php');?>
		<!-- LEFT MENU -->  
		
		<!-- BODY -->
		<div class="content-wrapper">
            <section class="content-header">
               <h1>Create New Hotel Attribute</h1>
               <ol class="breadcrumb">
                  <li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                  <li class="active">Create New Hotel Attribute</li>
               </ol>
            </section>
            <section class="content">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
							<form name="profile" name="new_attributes_form" id="new_attributes_form" method="POST" enctype="multipart/form-data">
								<div class="col-md-12 row">
									<div class="box-body">
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Attribute Name<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['attribute_name']) && $_POST['attribute_name']!='' ? $_POST['attribute_name'] : "");?>" name="attribute_name" id="attribute_name" placeholder="Attribute Name" tabindex = "1" />
										</div>
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Serial Number<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['serial_number']) && $_POST['serial_number']!='' ? $_POST['serial_number'] : "");?>" name="serial_number" id="serial_number" placeholder="Serial Number" tabindex = "2" />
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Attribute Type</label>
											<select class="form-control " name="type" id="type" tabindex = "3">
												<option value = "Both" <?php echo(isset($_POST['type']) && $_POST['type']=='Both' ? 'selected="selected"' : "");?>>Both</option>
												<option value = "Hotel" <?php echo(isset($_POST['type']) && $_POST['type']=='Hotel' ? 'selected="selected"' : "");?>>Hotel</option>
												<option value = "Room" <?php echo(isset($_POST['type']) && $_POST['type']=='Room' ? 'selected="selected"' : "");?>>Room</option>
											</select>
										</div>
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Status</label>
											<select class="form-control validate[optional]" name="status" id="status" tabindex = "4">
												<option value = "1" <?php echo(isset($_POST['status']) && $_POST['status']==1 ? 'selected="selected"' : "");?>>Active</option>
												<option value = "0" <?php echo(isset($_POST['status']) && $_POST['status']==0 ? 'selected="selected"' : "");?>>Inactive</option>
											</select>
										</div>
										<div class="clearfix"></div>
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
			</section>
		</div>
        <!-- BODY -->

        <!-- FOOTER -->
		<?php require_once(HOTEL_CONTROL_CENTER_COMMON_FILE_PATH.'footer.php');?>
		<!-- FOOTER -->

	</div>
</body>
</html>