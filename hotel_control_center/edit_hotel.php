<?php
require_once('../loader.inc');
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>EDIT HOTEL</title>
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
               <h1>Edit Hotel</h1>
               <ol class="breadcrumb">
                  <li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                  <li class="active">Edit Hotel</li>
               </ol>
            </section>
            <section class="content">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
							<form name="profile" name="form_create_slider" id="form_create_slider" method="POST" enctype="mulimedeia/form-data">
								<div class="col-md-12 row">
									<div class="box-body">
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Hotel Name<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="" name="hotel_name" id="hotel_name" placeholder="Hotel Name" tabindex = "1" />
										</div>
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Hotel Images</label>
											<input type="file" class="form-control validate[optional]"  value="" name="hotel_images[]" placeholder="Hotel Image" tabindex = "2" multiple/>
										</div>
										<div class="form-group col-md-4">
											<label for="inputName" class="control-label">Email Address<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="" name="hotel_name" id="hotel_name" placeholder="Email Address" tabindex = "1" />
										</div>
										<div class="form-group col-md-4">
											<label for="inputName" class="control-label">Password</label>
											<input type="password" class="form-control validate[optional]"  value="" name="hotel_images[]" placeholder="Account Password" tabindex = "2" multiple/>
										</div>
										<div class="form-group col-md-4">
											<label for="inputName" class="control-label">Confirm Password</label>
											<input type="password" class="form-control validate[optional]"  value="" name="hotel_images[]" placeholder="Confirm Account Password" tabindex = "2" multiple/>
										</div>
										<div class="form-group col-md-12">
											<label for="inputName" class="control-label">Hotel Address<font color="#FF0000">*</font></label>
											<textarea class="form-control validate[required]"  value="" name="hotel_address" id="hotel_address" placeholder="Hotel Address" tabindex = "3"></textarea>
										</div>
										<div class="form-group col-md-3">
											<label for="inputName" class="control-label">Country<font color="#FF0000">*</font></label>
											<select name = "country" class="form-control form_input1 select_bg">
												<option value = "">Select Country</option>
												<option value = "India">India</option>
												<option value = "India">Australia</option>
											</select>
										</div>
										<div class="form-group col-md-3">
											<label for="inputName" class="control-label">State / Region<font color="#FF0000">*</font></label>
											<select name = "country" class="form-control form_input1 select_bg">
												<option value = "">Select State / Region</option>
												<option value = "India">Asham</option>
												<option value = "India">West Bengal</option>
											</select>
										</div>
										<div class="form-group col-md-3">
											<label for="inputName" class="control-label">City<font color="#FF0000">*</font></label>
											<select name = "country" class="form-control form_input1 select_bg">
												<option value = "">Select City</option>
												<option value = "India">Kolkata</option>
												<option value = "India">Durgapur</option>
											</select>
										</div>
										<div class="form-group col-md-3">
											<label for="inputName" class="control-label">Postal Code<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="" name="postal_code" id="postal_code" placeholder="Postal Code" tabindex = "1" />
										</div>
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Phone Number<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="" name="phone_number" id="phone_number" placeholder="Phone Number" tabindex = "1" />
										</div>
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Altername Phone Number</label>
											<input type="text" class="form-control validate[required]"  value="" name="phone_number" id="phone_number" placeholder="Altername Phone Number" tabindex = "1" />
										</div>
										<div class="form-group col-md-12">
											<label for="inputName" class="control-label">Short Description</label>
											<textarea class="form-control validate[optional]"  value="" name="phone_number" id="phone_number" placeholder="Short Description" tabindex = "1"></textarea>
										</div>
										<div class="form-group col-md-12">
											<label for="inputName" class="control-label">Long Description</label>
											<textarea class="form-control ckeditor validate[required]"  value="" name="email_body" id="email_body" placeholder="Long Description" tabindex = "2"></textarea>
										</div>
										<div class="form-group col-md-4">
											<label for="inputName" class="control-label">Checkin Time<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="" name="postal_code" id="postal_code" placeholder="Checkin Time" tabindex = "1" />
										</div>
										<div class="form-group col-md-4">
											<label for="inputName" class="control-label">Checkout Time<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="" name="postal_code" id="postal_code" placeholder="Checkout Time" tabindex = "1" />
										</div>
										<div class="form-group col-md-4">
											<label for="inputName" class="control-label">Star Rating<font color="#FF0000">*</font></label>
											<select name = "country" class="form-control form_input1 select_bg">
												<option value = "1">1 Star</option>
												<option value = "2">2 Star</option>
												<option value = "3">3 Star</option>
												<option value = "4">4 Star</option>
												<option value = "5">5 Star</option>
											</select>
										</div>
										<div class="form-group col-md-4">
											<label for="inputName" class="control-label">Is Cancellation Policy Applied?<font color="#FF0000">*</font></label>
											<select name = "country" class="form-control form_input1 select_bg">
												<option value = "Yes">Yes</option>
												<option value = "No">No</option>
											</select>
										</div>
										<div class="form-group col-md-4">
											<label for="inputName" class="control-label">Cancellation Charge</label>
											<input type="text" class="form-control validate[required]"  value="" name="postal_code" id="postal_code" placeholder="Cancellation Charge" tabindex = "1" />
										</div>
										<div class="form-group col-md-4">
											<label for="inputName" class="control-label">Cancellation Allowed Days</label>
											<select name = "country" class="form-control form_input1 select_bg">
												<option value = "1">1 Day</option>
												<option value = "2">2 Days</option>
												<option value = "3">3 Days</option>
												<option value = "4">4 Days</option>
												<option value = "5">5 Days</option>
												<option value = "6">6 Days</option>
											</select>
										</div>
										<div class="form-group col-md-12">
											<label for="inputName" class="control-label">Other Policies</label>
											<textarea class="form-control ckeditor validate[required]"  value="" name="email_body" id="email_body" placeholder="Long Description" tabindex = "2"></textarea>
										</div>
										<div class="form-group col-md-12">
											<label for="inputName" class="control-label">Available Amenities<font color="#FF0000">*</font></label>
											<br/>
											<input type = "checkbox" name = "">&nbsp;Swimming Pool&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;WiFi&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Restaurant&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Bar&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Gym&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Gaming&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Spa&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Spa&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Spa&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Spa&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Spa&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Spa&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Spa&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Spa&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Spa&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Spa&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Spa&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Spa&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Spa&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Spa&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Spa&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Spa&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Spa&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Spa&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Spa&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Spa&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Spa
										</div>
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Status</label>
											<select class="form-control validate[optional]"  tabindex = "5">
												<option value = "agent">Active</option>
												<option value = "agent">Inactive</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-md-12 row">
									<div class="box-footer">
										<input type="hidden" name="token" value="" />
										<input type = "hidden" name = "id" id = "id" value = "" />
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
        <!-- BODY -->

        <!-- FOOTER -->
		<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'footer.php');?>
		<!-- FOOTER -->

	</div>
</body>
</html>