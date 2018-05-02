<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
	$white_list_array = array('package_title', 'no_of_days', 'description', 'package_price', 'discounted_price', 'package_images', 'status', 'token', 'id', 'btn_submit');
	$verify_token = "edit_new_package";
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>EDIT PACKAGE</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	$(function(){
		$("#form_edit_package").validationEngine();
	});
	function remove_img(cur, image_name, tour_id)
	{
		if(confirm("Are you sure you want to delete this image?"))
		{
			$.ajax({
				url:"<?= DOMAIN_NAME_PATH_ADMIN."ajax_tour_image_delete";?>",
				type:"post",
				data:{
					tour_id:tour_id,
					image_name:image_name
				},
				beforeSend:function(){
					cur.parent('div').hide();
				},
				dataType:"json",
				success:function(response){
					//console.log(response);
					if(response.status=="success")
					{
						showSuccess(response.msg);
						cur.parent('div').remove();
					}
					else
					{
						showError(response.msg);
						cur.parent('div').show();
					}
				},
				error:function(){
					showError("We are having some problem. Please try later.");
					cur.parent('div').show();
				}
			})
		}
	}
	//-->
	</script>
	<script type="text/javascript">
	CKEDITOR.config.autoParagraph = false;
	CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
	CKEDITOR.config.shiftEnterMode = CKEDITOR.ENTER_BR;
	CKEDITOR.config.protectedSource.push(/<i[^>]*><\/i>/g);
	CKEDITOR.config.allowedContent = true;
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
               <h1>Edit Package</h1>
               <ol class="breadcrumb">
                  <li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                  <li class="active">Edit Package</li>
               </ol>
            </section>
            <section class="content">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
							<form name="form_edit_package" id="form_edit_package" method="POST" enctype="multipart/form-data">
								<div class="col-md-12 row">
									<div class="box-body">
										<div class="form-group col-md-6">
											<label for="package_title" class="control-label">Package Title<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['package_title']) && $_POST['package_title']!='' ? $_POST['package_title'] : "");?>" name="package_title" id="package_title" placeholder="Package Title" tabindex = "1" />
										</div>

										<div class="form-group col-md-6">
											<label for="tour_type" class="control-label">No Of Days<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['no_of_days']) && $_POST['no_of_days']!='' ? $_POST['no_of_days'] : "");?>" name="no_of_days" id="no_of_days" placeholder="No Of Days" tabindex = "2" />
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-12">
											<label for="description" class="control-label">Description</label>
											<textarea class="form-control ckeditor" name="description" id="description" placeholder="Description" tabindex = "3"><?php echo(isset($_POST['description']) && $_POST['description']!='' ? $_POST['description'] : "");?></textarea>
										</div>
										<div class="form-group col-md-6">
											<label for="package_price" class="control-label">Package Price<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['package_price']) && $_POST['package_price']!='' ? $_POST['package_price'] : "");?>" name="package_price" id="package_price" placeholder="Package Price" tabindex = "4" />
										</div>
										<div class="form-group col-md-6">
											<label for="discounted_price" class="control-label">Discounted Price<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['discounted_price']) && $_POST['discounted_price']!='' ? $_POST['discounted_price'] : "");?>" name="discounted_price" id="discounted_price" placeholder="Discounted Price" tabindex = "5" />
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-6">
											<label for="tour_images" class="control-label">Package Images<font color="#FF0000">*</font></label>
											<input type="file" class="form-control validate[required]"  value="" name="package_images[]" id="tour_images" placeholder="Package Images" tabindex = "6" multiple/>
											<br/>
											<font color = "red">SELECT MULTIPLE BY HOLDING CONTROL BUTTON.</font>
										</div>
										<div class="form-group col-md-6">
											<label for="status" class="control-label">Status<font color="#FF0000">*</font></label>
											<select class="form-control validate[required]" name="status" id="status" tabindex = "7">
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
										<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex = "8">UPDATE</button>
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