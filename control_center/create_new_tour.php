<?php
require_once('../loader.inc');
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>CREATE NEW TOUR</title>
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
               <h1>Create New Tour</h1>
               <ol class="breadcrumb">
                  <li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                  <li class="active">Create New Tour</li>
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
											<label for="inputName" class="control-label">Country<font color="#FF0000">*</font></label>
											<select name = "country" class="form-control form_input1 select_bg">
												<option value = "">Select Country</option>
												<option value = "India">India</option>
												<option value = "India">Australia</option>
											</select>
										</div>
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">City<font color="#FF0000">*</font></label>
											<select name = "country" class="form-control form_input1 select_bg">
												<option value = "">Select City</option>
												<option value = "India">Kolkata</option>
												<option value = "India">Durgapur</option>
											</select>
										</div>
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Tour Title<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="" name="hotel_name" id="hotel_name" placeholder="Tour Title" tabindex = "1" />
										</div>
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Tour Type<font color="#FF0000">*</font></label>
											<select name = "country" class="form-control form_input1 select_bg">
												<option value = "">Select Tour Type</option>
												<option value = "India">Ticket Only</option>
												<option value = "India">Full Tour Including Lunch</option>
											</select>
										</div>
										<div class="form-group col-md-4">
											<label for="inputName" class="control-label">Service Type<font color="#FF0000">*</font></label>
											<select name = "country" class="form-control form_input1 select_bg">
												<option value = "">Select Service Type</option>
												<option value = "India">Private</option>
												<option value = "India">Shared</option>
											</select>
										</div>
										<div class="form-group col-md-4">
											<label for="inputName" class="control-label">Service Note</label>
											<input type="text" class="form-control validate[optional]"  value="" name="hotel_images[]" placeholder="Service Note" tabindex = "2" />
										</div>
										<div class="form-group col-md-4">
											<label for="inputName" class="control-label">Tour Images<font color="#FF0000">*</font></label>
											<input type="file" class="form-control validate[required]"  value="" name="hotel_name" id="hotel_name" placeholder="Tour Images" tabindex = "1" multiple/>
											<br/>
											<font color = "red">SELECT MULTIPLE BY HOLDING CONTROL BUTTON.</font>
										</div>
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Tour Start Time</label>
											<input type="text" class="form-control validate[optional]"  value="" name="hotel_images[]" placeholder="Tour Start Time" tabindex = "2" />
										</div>
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Tour End Time</label>
											<input type="text" class="form-control validate[optional]"  value="" name="hotel_images[]" placeholder="Tour End Time" tabindex = "2" />
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
										<div class="form-group col-md-4">
											<label for="inputName" class="control-label">Guide Included</label>
											<select class="form-control validate[optional]"  tabindex = "5">
												<option value = "agent">Yes</option>
												<option value = "agent">No</option>
											</select>
										</div>
										<div class="form-group col-md-4">
											<label for="inputName" class="control-label">Guide Language</label>
											<input type="text" class="form-control validate[required]"  value="" name="postal_code" id="postal_code" placeholder="Guide Language" tabindex = "1" />
										</div>
										<div class="form-group col-md-4">
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