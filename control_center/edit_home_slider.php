<?php
require_once('loader.inc');
tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
if(isset($_GET['banner_id']) && $_GET['banner_id']!=""):
	$home_slider = tools::find("first", TM_HOME_SLIDER, '*', "WHERE id=:id ", array(":id"=>base64_decode($_GET['banner_id'])));
	if(!empty($home_slider)):
		$white_list_array = array('slider_image', 'slider_text', 'serial_number', 'status', 'token', 'prev_slider_image', 'id', 'btn_submit');
		$verify_token = "edit_slider";
		if(isset($_POST['btn_submit'])) {
			$_POST['id']=$home_slider['id'];
			if(tools::verify_token($white_list_array, $_POST, $verify_token)) {
				$uploaded_file_json_data="";
				if(isset($_FILES) && !empty($_FILES) && $_FILES['slider_image']['name']!="")
				{
					$file_arr['form_field_name']="slider_image";
					$file_arr['form_field_name_hidden']="prev_slider_image";
					$file_arr['file_path']=GENERAL_IMAGES;
					$file_arr['width']="";
					$file_arr['height']="";
					$file_arr['file_type']="image";
					$uploaded_file_data['uploaded_file_data']=array($file_arr);
					$uploaded_file_json_data=json_encode($uploaded_file_data);
				}
				if($save_cms_data = tools::module_form_submission($uploaded_file_json_data, TM_HOME_SLIDER)) {
					$_SESSION['SET_TYPE'] = 'success';
					$_SESSION['SET_FLASH'] = 'Home slider has been updated successfully.';
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
		};
	else:
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = 'Invalid home slider id.';
		header("location:home_sliders");
		exit;
	endif;
else:
	$_SESSION['SET_TYPE'] = 'error';
	$_SESSION['SET_FLASH'] = 'Some data missing.';
	header("location:home_sliders");
	exit;
endif;
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>EDIT HOME SLIDESHOW</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script>
	jQuery(document).ready(function(){
		jQuery("#form_edit_slider").validationEngine();
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
				<h1>Edit Home Slider</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Home Slider</li>
				</ol>
            </section>
            <section class="content">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
							<form name="profile" name="form_edit_slider" id="form_edit_slider" method="POST" enctype="multipart/form-data">
								<div class="col-md-12 row">
									<div class="box-body">
										<div class="form-group col-md-6">
											<label for="slider_image" class="control-label">Upload Image</label>
											<input type="file" class="form-control" name="slider_image" id="slider_image" placeholder="Upload Image" tabindex = "1" />
											<?php
											if($home_slider['slider_image']!="" && file_exists(GENERAL_IMAGES.$home_slider['slider_image'])):
											?>
											<input type="hidden" name="prev_slider_image" id="prev_slider_image" value="<?php echo $home_slider['slider_image'];?>">
											<img src="<?php echo DOMAIN_NAME_PATH_ADMIN.GENERAL_IMAGES.$home_slider['slider_image'];?>" style="width:200px;"/>
											<?php
											endif;
											?>
										</div>
										<div class="form-group col-md-6">
											<label for="serial_number" class="control-label">Serial Number</label>
											<div class="input-icon right">
												<input type="text" class="form-control"  value="<?php echo(isset($_POST['serial_number']) && $_POST['serial_number']!='' ? $_POST['serial_number'] : $home_slider['serial_number']);?>" name="serial_number" id="serial_number" placeholder="Serial Number" tabindex = "2" />
											</div>
										</div>
										<div class="form-group col-md-12">
											<label for="slider_text" class="control-label">Slider Text</label>
											<textarea class="form-control ckeditor" name="slider_text" id="slider_text" placeholder="Slider Text" tabindex = "3"><?php echo(isset($_POST['slider_text']) && $_POST['slider_text']!='' ? $_POST['slider_text'] : $home_slider['slider_text']);?></textarea>
										</div>
										<div class="form-group col-md-6">
											<label for="status" class="control-label">Status</label>
											<select class="form-control" tabindex = "4" name="status" id="status" >
												<option value = "1" <?php echo(isset($_POST['status']) && $_POST['status']==1 ? 'selected="selected"' : ($home_slider['status']==1 ? 'selected="selected"' : ""));?>>Active</option>
												<option value = "0" <?php echo(isset($_POST['status']) && $_POST['status']==0 ? 'selected="selected"' : ($home_slider['status']==0 ? 'selected="selected"' : ""));?>>Inactive</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-md-12 row">
									<div class="box-footer">
										<input type="hidden" name="token" value="<?php echo(tools::generateFormToken($verify_token)); ?>" />
										<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex = "5">Update</button>
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