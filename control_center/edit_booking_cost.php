<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
	$white_list_array = array('id', 'booking_id', 'title', 'description', 'cost', 'status', 'token', 'btn_submit');
	$verify_token = "create_new_booking_cost";
	if(isset($_GET['package_id']) && $_GET['package_id']!=""):
		$autentication_data=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.PACKAGE_API_PATH."authorized.php"));
		if(isset($autentication_data->status)):
			if($autentication_data->status=="success"):
				$post_data['token']=array(
					"token"=>$autentication_data->results->token,
					"token_timeout"=>$autentication_data->results->token_timeout,
					"token_generation_time"=>$autentication_data->results->token_generation_time
				);
				$post_data['data']=$_GET;
				$post_data_str=json_encode($post_data);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.PACKAGE_API_PATH."cost/read.php");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				$return_data1 = curl_exec($ch);
				curl_close($ch);
				//print_r($return_data1);
				$return_data_arr1=json_decode($return_data1, true);
				$cost_data=array();
				if(!isset($return_data_arr1['status'])):
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH']="Some error has been occure during execution.";
					header("location:edit_package_booking?package_id=".$_GET['package_id']."&booking_id=".$_GET['booking_id']);
					exit;
				elseif($return_data_arr1['status']=="success"):
					$cost_data=$return_data_arr1['results'];
				else:
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
					header("location:edit_package_booking?package_id=".$_GET['package_id']."&booking_id=".$_GET['booking_id']);
					exit;
				endif;
				if(isset($_POST['btn_submit'])):
					if(tools::verify_token($white_list_array, $_POST, $verify_token)):
						$_POST['id']=$cost_data['id'];
						$_POST['booking_id']=$cost_data['booking_id'];
						$post_data['data']=$_POST;
						$post_data_str=json_encode($post_data);
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
						curl_setopt($ch, CURLOPT_HEADER, false);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
						curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
						curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
						curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.PACKAGE_API_PATH."cost/update.php");
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
						$return_data = curl_exec($ch);
						curl_close($ch);
						$return_data_arr=json_decode($return_data, true);
						if($return_data_arr['status']=="success"):
							$_SESSION['SET_TYPE'] = 'success';
							$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
							header("location:edit_package_booking?package_id=".$_GET['package_id']."&booking_id=".$_GET['booking_id']);
							exit;
						else:
							$_SESSION['SET_TYPE'] = 'error';
							$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
						endif;
					endif;
				endif;
			else:
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH'] = $autentication_data->msg;
			endif;
		else:
			$_SESSION['SET_TYPE'] = 'error';
			$_SESSION['SET_FLASH'] = "We are having some problem to authorize api.";
		endif;
	else:
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = 'Some data missing.';
		header("location:edit_package_booking?package_id=".$_GET['package_id']."&booking_id=".$_GET['booking_id']);
		exit;
	endif;
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>UPDATE BOOKING COST</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	$(function(){
		$("#form_create_package_booking_cost").validationEngine();
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
               <h1>Update Booking Cost</h1>
               <ol class="breadcrumb">
                  <li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                  <li class="active">Update Booking Cost</li>
               </ol>
            </section>
            <section class="content">
				<form name="form_create_package_booking_cost" id="form_create_package_booking_cost" method="POST" enctype="multipart/form-data">
					<div class="row">
						<div class="col-md-12">
							<div id="notify_msg_div"></div>
							<div class="box box-primary">
								<div class="col-md-12 row">
									<div class="box-body">
										<div class="form-group col-md-6">
											<label for="Title" class="control-label">Title<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['title']) && $_POST['title']!='' ? $_POST['title'] : (isset($cost_data['title']) && $cost_data['title']!='' ? $cost_data['title'] : ""));?>" name="title" id="title" placeholder="Title" tabindex = "1" />
										</div>
										<div class="form-group col-md-6">
											<label for="Cost" class="control-label">Cost<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['cost']) && $_POST['cost']!='' ? $_POST['cost'] : (isset($cost_data['cost']) && $cost_data['cost']!='' ? $cost_data['cost'] : ""));?>" name="cost" id="cost" placeholder="Cost" tabindex = "2" />
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-12">
											<label for="description" class="control-label">Description</label>
											<textarea class="form-control ckeditor" name="description" id="description" placeholder="Description" tabindex = "3"><?php echo(isset($_POST['description']) && $_POST['description']!='' ? $_POST['description'] : (isset($cost_data['description']) && $cost_data['description']!='' ? $cost_data['description'] : ""));?></textarea>
										</div>
										<div class="form-group col-md-12">
											<label for="status" class="control-label">Status<font color="#FF0000">*</font></label>
											<select class="form-control validate[required]" name="status" id="status" tabindex = "4">
												<option value = "1" <?php echo(isset($_POST['status']) && $_POST['status']==1 ? 'selected="selected"' : (isset($cost_data['status']) && $cost_data['status']==1 ? 'selected="selected"' : ""));?>>Active</option>
												<option value = "0" <?php echo(isset($_POST['status']) && $_POST['status']==0 ? 'selected="selected"' : (isset($cost_data['status']) && $cost_data['status']==0 ? 'selected="selected"' : ""));?>>Inactive</option>
											</select>
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="col-md-12 row">
										<div class="box-footer">
											<input type="hidden" name="token" value="<?php echo(tools::generateFormToken($verify_token)); ?>" />
											<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex = "5">UPDATE</button>
										</div>
									</div>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
				</form>
			</section>
		</div>
        <!-- BODY -->

        <!-- FOOTER -->
		<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'footer.php');?>
		<!-- FOOTER -->

	</div>
</body>
</html>