<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
	$white_list_array = array('attribute_name', 'serial_number', 'type', 'status', 'token', 'id', 'btn_submit');
	$verify_token = "edit_attribute";
	if(isset($_GET['attribute_id']) && $_GET['attribute_id']!=""):
		$autentication_data=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.TOUR_API_PATH."authorized.php"));
		if(isset($autentication_data->status)):
			if($autentication_data->status=="success"):
				$post_data['token']=array(
					"token"=>$autentication_data->results->token,
					"token_timeout"=>$autentication_data->results->token_timeout,
					"token_generation_time"=>$autentication_data->results->token_generation_time
				);
				if(isset($_POST['btn_submit'])) {
					$_POST['id']=base64_decode($_GET['attribute_id']);
					if(tools::verify_token($white_list_array, $_POST, $verify_token)) {
						$post_data['data']=$_POST;
						$post_data_str=json_encode($post_data);
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
						curl_setopt($ch, CURLOPT_HEADER, false);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
						curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
						curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.TOUR_API_PATH."attribute/update.php");
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
						$return_data = curl_exec($ch);
						curl_close($ch);
						$return_data_arr=json_decode($return_data, true);
						$supplier_save_data=array();
						if($return_data_arr['status']=="success")
						{
							$_SESSION['SET_TYPE'] = 'success';
							$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
							header("location:tour_attributes");
							exit;
						}
						else
						{
							$_SESSION['SET_TYPE'] = 'error';
							$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
						}
					} else {
						$_SESSION['SET_TYPE'] = 'error';
						$_SESSION['SET_FLASH'] = 'Access token mismatch. Please reload the page & try again.';
					}
				};
				$post_data['data']=$_GET;
				$post_data_str=json_encode($post_data);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.TOUR_API_PATH."attribute/read.php");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				$return_data = curl_exec($ch);
				curl_close($ch);
				$return_data_arr=json_decode($return_data, true);
				$attribute_data=array();
				if(!isset($return_data_arr['status'])):
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH']="Some error has been occure during execution.";
					header("location:tour_attributes");
					exit;
				elseif($return_data_arr['status']=="success"):
					$attribute_data=$return_data_arr['results'];
				else:
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
					header("location:tour_attributes");
					exit;
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
		header("location:tour_attributes");
		exit;
	endif;
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>EDIT TOUR ATTRIBUTE</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	$(function(){
		$("#edit_tour_attribute_from").validationEngine();
	});
	//-->
	</script>
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
               <h1>Edit Tour Attribute</h1>
               <ol class="breadcrumb">
                  <li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                  <li class="active">Edit Tour Attribute</li>
               </ol>
            </section>
            <section class="content">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
							<form name="edit_tour_attribute_from" id="edit_tour_attribute_from" method="POST" enctype="multipart/form-data">
								<div class="col-md-12 row">
									<div class="box-body">
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Attribute Name<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required, custom[onlyLetterNumber]]"  value="<?php echo(isset($_POST['attribute_name']) && $_POST['attribute_name']!='' ? $_POST['attribute_name'] : (isset($attribute_data['attribute_name']) && $attribute_data['attribute_name']!='' ? $attribute_data['attribute_name'] : ""));?>" name="attribute_name" id="attribute_name" placeholder="Attribute Name" tabindex = "1" />
										</div>
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Serial Number<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required, custom[integer]]"  value="<?php echo(isset($_POST['serial_number']) && $_POST['serial_number']!='' ? $_POST['serial_number'] : (isset($attribute_data['serial_number']) && $attribute_data['serial_number']!='' ? $attribute_data['serial_number'] : ""));?>" name="serial_number" id="serial_number" placeholder="Serial Number" tabindex = "2" />
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Status</label>
											<select class="form-control validate[optional]" name="status" id="status" tabindex = "4">
												<option value = "1" <?php echo(isset($_POST['status']) && $_POST['status']==1 ? 'selected="selected"' : (isset($attribute_data['status']) && $attribute_data['status']==1 ? 'selected="selected"' : ""));?>>Active</option>
												<option value = "0" <?php echo(isset($_POST['status']) && $_POST['status']==0 ? 'selected="selected"' : (isset($attribute_data['status']) && $attribute_data['status']==0 ? 'selected="selected"' : ""));?>>Inactive</option>
											</select>
										</div>
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
		<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'footer.php');?>
		<!-- FOOTER -->

	</div>
</body>
</html>