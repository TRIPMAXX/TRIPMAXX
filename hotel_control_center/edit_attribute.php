<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA_HOTEL']['id'], DOMAIN_NAME_PATH_HOTEL.'login');
	$white_list_array = array('attribute_name', 'serial_number', 'type', 'status', 'token', 'id', 'btn_submit');
	$verify_token = "edit_attribute";
	if(isset($_GET['attribute_id']) && $_GET['attribute_id']!=""):		
				if(isset($_POST['btn_submit'])) {
					$_POST['id']=base64_decode($_GET['attribute_id']);
					if(tools::verify_token($white_list_array, $_POST, $verify_token)) {
						$find_attribute = tools::find("first", TM_ATTRIBUTES, '*', "WHERE id=:id", array(":id"=>$_POST['id']));
						if(!empty($find_attribute))
						{
							if(tools::module_data_exists_check("attribute_name = '".tools::stripcleantohtml($_POST['attribute_name'])."' AND id <> ".$find_attribute['id']."", '', TM_ATTRIBUTES)):
								$_SESSION['SET_TYPE']="error";
								$_SESSION['SET_FLASH'] = 'This attribute name already exists.';		
							elseif($save_attribute_data = tools::module_form_submission("", TM_ATTRIBUTES)):
								$_SESSION['SET_TYPE'] = 'success';
								$_SESSION['SET_FLASH'] = 'Attribute has been updated successfully.';
								header("location:attributes");
								exit;
							else:
								$_SESSION['SET_TYPE'] = 'error';
								$_SESSION['SET_FLASH'] = 'We are having some probem. Please try again later.';
							endif;
						}
						else
						{
							$_SESSION['SET_TYPE'] = 'error';
							$_SESSION['SET_FLASH'] = 'Some error occurs. Please reload the page & try again.';							
						}
					} 
					else 
					{
						$_SESSION['SET_TYPE'] = 'error';
						$_SESSION['SET_FLASH'] = 'Access token mismatch. Please reload the page & try again.';
					}
				};
				$attribute_list = tools::find("first", TM_ATTRIBUTES, '*', "WHERE id=:id ", array(":id"=>base64_decode($_GET['attribute_id'])));
				
				if(isset($attribute_list) && empty($attribute_list)):
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH']="Some error has been occure during execution.";
					header("location:attributes");
					exit;
				elseif(isset($attribute_list) && !empty($attribute_list)):
					$attribute_data=$attribute_list;
				else:
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH'] = 'Some error has been occure during execution.';
					header("location:attributes");
					exit;
				endif;		
		
	endif;
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER_HOTEL);?>EDIT HOTEL ATTRIBUTE</title>
	<?php require_once(HOTEL_CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	jQuery(document).ready(function(){
		jQuery("#edit_attribute_from").validationEngine();
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
               <h1>Edit Hotel Attribute</h1>
               <ol class="breadcrumb">
                  <li><a href="<?php echo(DOMAIN_NAME_PATH_HOTEL);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                  <li class="active">Edit Hotel Attribute</li>
               </ol>
            </section>
            <section class="content">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
							<form name="edit_attribute_from" id="edit_attribute_from" method="POST" enctype="multipart/form-data">
								<div class="col-md-12 row">
									<div class="box-body">
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Attribute Name<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['attribute_name']) && $_POST['attribute_name']!='' ? $_POST['attribute_name'] : (isset($attribute_data['attribute_name']) && $attribute_data['attribute_name']!='' ? $attribute_data['attribute_name'] : ""));?>" name="attribute_name" id="attribute_name" placeholder="Attribute Name" tabindex = "1" />
										</div>
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Serial Number<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['serial_number']) && $_POST['serial_number']!='' ? $_POST['serial_number'] : (isset($attribute_data['serial_number']) && $attribute_data['serial_number']!='' ? $attribute_data['serial_number'] : ""));?>" name="serial_number" id="serial_number" placeholder="Serial Number" tabindex = "2" />
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Attribute Type</label>
											<select class="form-control " name="type" id="type" tabindex = "3">
												<option value = "Both" <?php echo(isset($_POST['type']) && $_POST['type']=='Both' ? 'selected="selected"' : (isset($attribute_data['type']) && $attribute_data['type']=='Both' ? 'selected="selected"' : ""));?>>Both</option>
												<option value = "Hotel" <?php echo(isset($_POST['type']) && $_POST['type']=='Hotel' ? 'selected="selected"' : (isset($attribute_data['type']) && $attribute_data['type']=='Hotel' ? 'selected="selected"' : ""));?>>Hotel</option>
												<option value = "Room" <?php echo(isset($_POST['type']) && $_POST['type']=='Room' ? 'selected="selected"' : (isset($attribute_data['type']) && $attribute_data['type']=='Room' ? 'selected="selected"' : ""));?>>Room</option>
											</select>
										</div>
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Status</label>
											<select class="form-control validate[optional]" name="status" id="status" tabindex = "4">
												<option value = "1" <?php echo(isset($_POST['status']) && $_POST['status']==1 ? 'selected="selected"' : (isset($attribute_data['status']) && $attribute_data['status']==1 ? 'selected="selected"' : ""));?>>Active</option>
												<option value = "0" <?php echo(isset($_POST['status']) && $_POST['status']==0 ? 'selected="selected"' : (isset($attribute_data['status']) && $attribute_data['status']==0 ? 'selected="selected"' : ""));?>>Inactive</option>
											</select>
										</div>
										<div class="clearfix"></div>
									</div>
								</div>
								<div class="col-md-12 row">
									<div class="box-footer">
										<input type="hidden" name="token" value="<?php echo(tools::generateFormToken($verify_token)); ?>" />
										<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex = "5">UPDATE</button>
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