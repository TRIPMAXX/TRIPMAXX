<?php
require_once('loader.inc');
tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
$white_list_array = array('website_logo', 'punch_line', 'contact_person_name', 'contact_email_address', 'contact_phone_number', 'contact_address', 'default_currency', 'maintenance_mode', 'google_map_api', 'google_analytics_api', 'from_email_address', 'default_page_title', 'default_meta_keyword', 'default_meta_description', 'prev_website_logo', 'token', 'id', 'btn_submit');
$verify_token = "general_settings";
if(isset($_POST['btn_submit'])) {
	$_POST['id']=1;
	if(tools::verify_token($white_list_array, $_POST, $verify_token)) {
		$uploaded_file_json_data="";
		if(isset($_FILES) && !empty($_FILES) && $_FILES['website_logo']['name']!="")
		{
			$file_arr['form_field_name']="website_logo";
			$file_arr['form_field_name_hidden']="prev_website_logo";
			$file_arr['file_path']=GENERAL_IMAGES;
			$file_arr['width']="";
			$file_arr['height']="";
			$file_arr['file_type']="image";
			$uploaded_file_data['uploaded_file_data']=array($file_arr);
			$uploaded_file_json_data=json_encode($uploaded_file_data);
		}
		if($save_general_settings_data = tools::module_form_submission($uploaded_file_json_data, TM_SETTINGS)) {
			$_SESSION['SET_TYPE'] = 'success';
			$_SESSION['SET_FLASH'] = 'General settings has been updated successfully.';
			header("location:general_settings");
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
$general_setting = tools::find("first", TM_SETTINGS, '*', "WHERE id=:id ", array(":id"=>1));
$currency_details = tools::find("all", TM_CURRENCIES, '*', "WHERE status=:status ORDER BY serial_number ASC", array(":status"=>1));
?>
<!DOCTYPE html>
<html>
  <head>
    <title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>SETTINGS</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script>
	jQuery(document).ready(function(){
		jQuery("#general_settings_form").validationEngine();
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
				<h1>Edit General Settings</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard/"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Edit General Settings</li>
				</ol>
			</section>
            <section class="content">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
							<form role="form" name="general_settings_form" id="general_settings_form" method="post" enctype="multipart/form-data">
								<div class="col-md-12 row">
									<div class="box-body">
										<div class="form-group col-md-6">
											<label for="website_logo" class="control-label">Website Logo</label>
											<div class="input-icon right">
												<input type="file" class="form-control"  value="" name="website_logo" id="website_logo" placeholder="Website Logo" tabindex = "1" />
												<br/>
												<?php
												if($general_setting['website_logo']!="" && file_exists(GENERAL_IMAGES.$general_setting['website_logo'])):
												?>
												<input type="hidden" name="prev_website_logo" id="prev_website_logo" value="<?php echo $general_setting['website_logo'];?>">
												<img src="<?php echo DOMAIN_NAME_PATH_ADMIN.GENERAL_IMAGES.$general_setting['website_logo'];?>" style="width:100px;"/>
												<?php
												endif;
												?>
											</div>
										</div>
										<div class="form-group col-md-6">
											<label for="punch_line" class="control-label">Punch Line</label>
											<div class="input-icon right">
												<input type="text" class="form-control"  value="<?php echo(isset($_POST['punch_line']) && $_POST['punch_line']!='' ? $_POST['punch_line'] : $general_setting['punch_line']);?>" name="punch_line" id="punch_line" placeholder="Punch Line" tabindex = "2" />
											</div>
										</div>
										<div class="form-group col-md-6">
											<label for="contact_person_name" class="control-label">Contact Person Name<font color="#FF0000">*</font></label>
											<div class="input-icon right">
												<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['contact_person_name']) && $_POST['contact_person_name']!='' ? $_POST['contact_person_name'] : $general_setting['contact_person_name']);?>" name="contact_person_name" id="contact_person_name" placeholder="Contact Person Name" tabindex = "3" />
											</div>
										</div>
										<div class="form-group col-md-6">
											<label for="contact_email_address" class="control-label">Contact Email Address</label>
											<div class="input-icon right">
												<input type="text" class="form-control"  value="<?php echo(isset($_POST['contact_email_address']) && $_POST['contact_email_address']!='' ? $_POST['contact_email_address'] : $general_setting['contact_email_address']);?>" name="contact_email_address" id="contact_email_address" placeholder="Contact Email Address" tabindex = "4" />
											</div>
										</div>
										<div class="form-group col-md-6">
											<label for="contact_phone_number" class="control-label">Contact Phone Number<font color="#FF0000">*</font></label>
											<div class="input-icon right">
												<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['contact_phone_number']) && $_POST['contact_phone_number']!='' ? $_POST['contact_phone_number'] : $general_setting['contact_phone_number']);?>" name="contact_phone_number" id="contact_phone_number" placeholder="Contact Phone Number" tabindex = "5" />
											</div>
										</div>
										<div class="form-group col-md-6">
											<label for="contact_address" class="control-label">Contact Address</label>
											<div class="input-icon right">
												<input type="text" class="form-control" value="<?php echo(isset($_POST['contact_address']) && $_POST['contact_address']!='' ? $_POST['contact_address'] : $general_setting['contact_address']);?>" name="contact_address" id="contact_address" placeholder="Contact Address" tabindex = "6" />
											</div>
										</div>
										<div class="form-group col-md-6">
											<label for="default_currency" class="control-label">Default Currency<font color="#FF0000">*</font></label>
											<div class="input-icon right">
												<select class="form-control validate[optional]" name="default_currency" id="default_currency" tabindex = "7">
												<?php
												if(!empty($currency_details)):
													foreach($currency_details as $curr_key=>$curr_val):
												?>
													<option value = "<?= $curr_val['id'];?>" <?php echo(isset($_POST['default_currency']) && $_POST['default_currency']==$curr_val['id'] ? 'selected="selected"' : ($general_setting['default_currency']==$curr_val['id'] ? 'selected="selected"' : ""));?>><?= $curr_val['currency_name']." (".$curr_val['currency_code'].")";?></option>
												<?php
													endforeach;
												endif;
												?>
												</select>
											</div>
										</div>
										<div class="form-group col-md-6">
											<label for="maintenance_mode" class="control-label">Maintenance Mode</label>
											<div class="input-icon right">
												<select class="form-control" tabindex = "8" name="maintenance_mode" id="maintenance_mode">
													<option value = "1" <?php echo(isset($_POST['maintenance_mode']) && $_POST['maintenance_mode']==1 ? 'selected="selected"' : ($general_setting['maintenance_mode']==1 ? 'selected="selected"' : ""));?>>True</option>
													<option value = "0" <?php echo(isset($_POST['maintenance_mode']) && $_POST['maintenance_mode']==0 ? 'selected="selected"' : ($general_setting['maintenance_mode']==0 ? 'selected="selected"' : ""));?>>False</option>
												</select>
											</div>
										</div>
										<div class="form-group col-md-6">
											<label for="google_map_api" class="control-label">Google Map API Details</label>
											<div class="input-icon right">
												<textarea class="form-control" name="google_map_api" id="google_map_api" placeholder="Google Map Apidetails" tabindex = "9"><?php echo(isset($_POST['google_map_api']) && $_POST['google_map_api']!='' ? $_POST['google_map_api'] : $general_setting['google_map_api']);?></textarea>
											</div>
										</div>
										<div class="form-group col-md-6">
											<label for="google_analytics_api" class="control-label">Google Analitics API Details</label>
											<div class="input-icon right">
												<textarea class="form-control" name="google_analytics_api" id="google_analytics_api" placeholder="Google Analitics Api details" tabindex = "10"><?php echo(isset($_POST['google_analytics_api']) && $_POST['google_analytics_api']!='' ? $_POST['google_analytics_api'] : $general_setting['google_analytics_api']);?></textarea>
											</div>
										</div>
										<div class="form-group col-md-6">
											<label for="from_email_address" class="control-label">From Email Address</label>
											<div class="input-icon right">
												<input type="text" class="form-control"  value="<?php echo(isset($_POST['from_email_address']) && $_POST['from_email_address']!='' ? $_POST['from_email_address'] : $general_setting['from_email_address']);?>" name="from_email_address" id="from_email_address" placeholder="Form Email Address" tabindex = "11" />
											</div>
										</div>
										<div class="form-group col-md-6">
											<label for="default_page_title" class="control-label">Default Page Meta Title</label>
											<div class="input-icon right">
												<input type="text" class="form-control"  value="<?php echo(isset($_POST['default_page_title']) && $_POST['default_page_title']!='' ? $_POST['default_page_title'] : $general_setting['default_page_title']);?>" name="default_page_title" id="default_page_title" placeholder="Default Page Meta Title" tabindex = "12" />
											</div>
										</div>
										<div class="form-group col-md-6">
											<label for="default_meta_keyword" class="control-label">Default Page Meta Keyword</label>
											<div class="input-icon right">
												<textarea class="form-control" name="default_meta_keyword" id="default_meta_keyword" placeholder="Default Page Meta Keyword" tabindex = "13"><?php echo(isset($_POST['default_meta_keyword']) && $_POST['default_meta_keyword']!='' ? $_POST['default_meta_keyword'] : $general_setting['default_meta_keyword']);?></textarea>
											</div>
										</div>
										<div class="form-group col-md-6">
											<label for="default_meta_description" class="control-label">Default Page Meta Description</label>
											<div class="input-icon right">
												<textarea class="form-control" name="default_meta_description" id="default_meta_description" placeholder="Default Page Meta Description" tabindex = "14"><?php echo(isset($_POST['default_meta_description']) && $_POST['default_meta_description']!='' ? $_POST['default_meta_description'] : $general_setting['default_meta_description']);?></textarea>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-12 row">
									<div class="box-footer">
										<input type="hidden" name="token" value="<?php echo(tools::generateFormToken($verify_token)); ?>" />
										<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex = "15">UPDATE</button>
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