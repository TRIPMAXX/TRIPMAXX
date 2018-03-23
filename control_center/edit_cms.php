<?php
require_once('loader.inc');
tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
if(isset($_GET['cms_id']) && $_GET['cms_id']!=""):
	$cms_pages = tools::find("first", TM_CMS, '*', "WHERE id=:id ", array(":id"=>base64_decode($_GET['cms_id'])));
	if(!empty($cms_pages)):
		$white_list_array = array('page_heading', 'page_title', 'page_slug', 'page_description', 'page_banner_image', 'page_meta_title', 'page_meta_keyword', 'page_meta_description', 'status', 'prev_page_banner_image', 'token', 'id', 'btn_submit');
		$verify_token = "edit_cms";
		if(isset($_POST['btn_submit'])) {
			$_POST['id']=$cms_pages['id'];
			$_POST['page_slug']=tools::slugify($_POST['page_heading']);
			if(tools::verify_token($white_list_array, $_POST, $verify_token)) {
				$uploaded_file_json_data="";
				if(isset($_FILES) && !empty($_FILES) && $_FILES['page_banner_image']['name']!="")
				{
					$file_arr['form_field_name']="page_banner_image";
					$file_arr['form_field_name_hidden']="prev_page_banner_image";
					$file_arr['file_path']=CMS_BANNER;
					$file_arr['width']="";
					$file_arr['height']="";
					$file_arr['file_type']="image";
					$uploaded_file_data['uploaded_file_data']=array($file_arr);
					$uploaded_file_json_data=json_encode($uploaded_file_data);
				}
				if(tools::module_data_exists_check("page_heading = '".tools::stripcleantohtml($_POST['page_heading'])."' AND id <> ".$cms_pages['id']."", '', TM_CMS)) {
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH'] = 'This page heading already exists.';
				} else {
					if($save_cms_data = tools::module_form_submission($uploaded_file_json_data, TM_CMS)) {
						$_SESSION['SET_TYPE'] = 'success';
						$_SESSION['SET_FLASH'] = 'CMS has been updated successfully.';
						header("location:cms");
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
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>EDIT CMS</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	CKEDITOR.config.autoParagraph = false;
	CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
	CKEDITOR.config.shiftEnterMode = CKEDITOR.ENTER_BR;
	CKEDITOR.config.protectedSource.push(/<i[^>]*><\/i>/g);
	CKEDITOR.config.allowedContent = true;
	</script>
	<script>
	jQuery(document).ready(function(){
		jQuery("#edit_cms").validationEngine();
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
				<h1>Edit CMS</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Edit Promotional Offers</li>
				</ol>
			</section>
			<section class="content">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
							<form role="form" name="edit_cms" id="edit_cms"method="post" enctype="multipart/form-data">
								<div class="col-md-12 row">
									<div class="box-body">
										<div class="form-group col-md-6">
											<label for="page_heading" class="control-label">Page Heading<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['page_heading']) && $_POST['page_heading']!='' ? $_POST['page_heading'] : $cms_pages['page_heading']);?>" name="page_heading" id="page_heading" placeholder="Page Heading" tabindex = "1" />
										</div>
										<div class="form-group col-md-6">
											<label for="page_title" class="control-label">Page Title<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['page_title']) && $_POST['page_title']!='' ? $_POST['page_title'] : $cms_pages['page_title']);?>" name="page_title" id="page_title" placeholder="Page Title" tabindex = "2" />
										</div>
										<div class="form-group col-md-12">
											<label for="page_description">Page Description</label>
											<textarea class="form-control ckeditor" name="page_description" id="page_description" placeholder="Page Description" tabindex = "3"><?php echo(isset($_POST['page_description']) && $_POST['page_description']!='' ? $_POST['page_description'] : $cms_pages['page_description']);?></textarea>
										</div>
										<div class="form-group col-md-12">
											<label for="page_banner_image" class="control-label">Banner Image</label>
											<input type="file" class="form-control"  value="" name="page_banner_image" id="page_banner_image" placeholder="Banner Image" tabindex = "4" />
											<?php
											if($cms_pages['page_banner_image']!="" && file_exists(CMS_BANNER.$cms_pages['page_banner_image'])):
											?>
											<input type="hidden" name="prev_page_banner_image" id="prev_page_banner_image" value="<?php echo $cms_pages['page_banner_image'];?>">
											<img src="<?php echo DOMAIN_NAME_PATH_ADMIN.CMS_BANNER.$cms_pages['page_banner_image'];?>" style="width:100px;"/>
											<?php
											endif;
											?>
										</div>
										<div class="form-group col-md-12">
											<label for="page_meta_title" class="control-label">Page Meta Title</label>
											<input type="text" class="form-control"  value="<?php echo(isset($_POST['page_meta_title']) && $_POST['page_meta_title']!='' ? $_POST['page_meta_title'] : $cms_pages['page_meta_title']);?>" name="page_meta_title" id="page_meta_title" placeholder="Page Meta Title" tabindex = "5" />
										</div>
										<div class="form-group col-md-6">
											<label for="page_meta_keyword" class="control-label">Page Meta Keyword</label>
											<textarea class="form-control" name="page_meta_keyword" id="page_meta_keyword" placeholder="Page Meta Keyword" tabindex = "6"><?php echo(isset($_POST['page_meta_keyword']) && $_POST['page_meta_keyword']!='' ? $_POST['page_meta_keyword'] : $cms_pages['page_meta_keyword']);?></textarea>
										</div>
										<div class="form-group col-md-6">
											<label for="page_meta_description" class="control-label">Page Meta Description</label>
											<textarea class="form-control" name="page_meta_description" id="page_meta_description" placeholder="Page Meta Description" tabindex = "7"><?php echo(isset($_POST['page_meta_description']) && $_POST['page_meta_description']!='' ? $_POST['page_meta_description'] : $cms_pages['page_meta_description']);?></textarea>
										</div>
										<div class="form-group col-md-6">
											<label for="status" class="control-label">Status</label>
											<select class="form-control" tabindex = "8" name="status" id="status" >
												<option value = "1" <?php echo(isset($_POST['status']) && $_POST['status']==1 ? 'selected="selected"' : ($cms_pages['status']==1 ? 'selected="selected"' : ""));?>>Active</option>
												<option value = "0" <?php echo(isset($_POST['status']) && $_POST['status']==0 ? 'selected="selected"' : ($cms_pages['status']==0 ? 'selected="selected"' : ""));?>>Inactive</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-md-12 row">
									<div class="box-footer">
										<input type="hidden" name="token" value="<?php echo(tools::generateFormToken($verify_token)); ?>" />
										<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex = "9">UPDATE</button>
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