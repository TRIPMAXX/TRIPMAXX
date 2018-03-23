<?php
require_once('loader.inc');
tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
$white_list_array = array('slider_image', 'slider_text', 'serial_number', 'status', 'token', 'id', 'btn_submit');
$verify_token = "add_slider";
if(isset($_POST['btn_submit'])) {
	$_POST['status']=1;
	if(tools::verify_token($white_list_array, $_POST, $verify_token)) {
		$uploaded_file_json_data="";
		if(isset($_FILES) && !empty($_FILES) && $_FILES['slider_image']['name']!="")
		{
			$file_arr['form_field_name']="slider_image";
			$file_arr['form_field_name_hidden']="";
			$file_arr['file_path']=GENERAL_IMAGES;
			$file_arr['width']="";
			$file_arr['height']="";
			$file_arr['file_type']="image";
			$uploaded_file_data['uploaded_file_data']=array($file_arr);
			$uploaded_file_json_data=json_encode($uploaded_file_data);
		}
		if($save_banner_data = tools::module_form_submission($uploaded_file_json_data, TM_HOME_SLIDER)) {
			$_SESSION['SET_TYPE'] = 'success';
			$_SESSION['SET_FLASH'] = 'Home slider has been created successfully.';
			header("location:home_sliders");
			exit;
		} else {
			$_SESSION['SET_TYPE'] = 'error';
			$_SESSION['SET_FLASH'] = 'We are having some probem. Please try again later.';
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
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>CREATE NEW SLIDESHOW</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	jQuery(document).ready(function(){
		jQuery("#form_create_slider").validationEngine();
	});
	//-->
	</script>
	<script type="text/javascript">
	CKEDITOR.config.autoParagraph = false;
	CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
	CKEDITOR.config.shiftEnterMode = CKEDITOR.ENTER_BR;
	CKEDITOR.config.protectedSource.push(/<i[^>]*><\/i>/g);
	CKEDITOR.config.allowedContent = true;
	</script>
	<script>
	jQuery(document).ready(function(){
		jQuery("#profile").validationEngine();
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
		
		<!-- BODY -->
		<div class="content-wrapper">
            <section class="content-header">
               <h1>Create New Home Slider</h1>
               <ol class="breadcrumb">
                  <li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                  <li class="active">Create New Home Slider</li>
               </ol>
            </section>
            <section class="content">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
							<form name="profile" name="form_create_slider" id="form_create_slider" method="POST" enctype="multipart/form-data">
								<div class="col-md-12 row">
									<div class="box-body">
										<div class="form-group col-md-6">
											<label for="slider_image" class="control-label">Upload Image<font color="#FF0000">*</font></label>
											<input type="file" class="form-control validate[required]" name="slider_image" id="slider_image" placeholder="Upload Image" tabindex = "1" />
										</div>
										<div class="form-group col-md-6">
											<label for="serial_number" class="control-label">Serial Number</label>
											<div class="input-icon right">
												<input type="text" class="form-control"  value="<?php echo(isset($_POST['serial_number']) && $_POST['serial_number']!='' ? $_POST['serial_number'] : "");?>" name="serial_number" id="serial_number" placeholder="Serial Number" tabindex = "2" />
											</div>
										</div>
										<div class="form-group col-md-12">
											<label for="slider_text" class="control-label">Slider Text</label>
											<textarea class="form-control ckeditor" name="slider_text" id="slider_text" placeholder="Slider Text" tabindex = "3"><?php echo(isset($_POST['slider_text']) && $_POST['slider_text']!='' ? $_POST['slider_text'] : "");?></textarea>
										</div>
									</div>
								</div>
								<div class="col-md-12 row">
									<div class="box-footer">
										<input type="hidden" name="token" value="<?php echo(tools::generateFormToken($verify_token)); ?>" />
										<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex = "4">CREATE</button>
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