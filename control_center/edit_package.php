<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
	$white_list_array = array('country', 'state', 'city', 'package_title', 'no_of_days', 'description', 'package_price', 'discounted_price', 'package_images', 'status', 'token', 'id', 'btn_submit');
	$verify_token = "edit_new_package";
	if(isset($_GET['package_id']) && $_GET['package_id']!=""):
		$autentication_data=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.PACKAGE_API_PATH."authorized.php"));
		if(isset($autentication_data->status)):
			if($autentication_data->status=="success"):
				$post_data['token']=array(
					"token"=>$autentication_data->results->token,
					"token_timeout"=>$autentication_data->results->token_timeout,
					"token_generation_time"=>$autentication_data->results->token_generation_time
				);
				$post_data_str=json_encode($post_data);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.PACKAGE_API_PATH."country/read.php");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				$return_data = curl_exec($ch);
				curl_close($ch);
				$return_data_arr=json_decode($return_data, true);
				//print_r($return_data);
				$country_data=array();
				if($return_data_arr['status']=="success"):
					$country_data=$return_data_arr['results'];
				//else:
				//	$_SESSION['SET_TYPE'] = 'error';
				//	$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
				endif;
				//$post_data_str=json_encode($post_data);
				if(isset($_POST['btn_submit'])) {
					$_POST['id']=base64_decode($_GET['package_id']);
					if(tools::verify_token($white_list_array, $_POST, $verify_token)) {
						$_POST['uploaded_files']=array();
						if(isset($_FILES["package_images"])){
							foreach($_FILES["package_images"]['name'] as $file_key=>$file_val):
								$extension = pathinfo($file_val, PATHINFO_EXTENSION);
								//$splited_name=explode(".", $file_val);
								//$extension = end($splited_name);
								$validation_array = array('jpg', 'jpeg', 'png', 'gif', 'bmp');
								if(in_array(strtolower($extension), $validation_array)) {
									$data = file_get_contents($_FILES["package_images"]['tmp_name'][$file_key]);
									$base64 = 'data:image/' . $extension . ';base64,' . base64_encode($data);
									array_push($_POST['uploaded_files'], curl_file_create($base64, $_FILES["package_images"]['type'][$file_key], $_FILES["package_images"]['name'][$file_key]));
								}
							endforeach;
						}
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
						curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.PACKAGE_API_PATH."package/update.php");
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
						$return_data = curl_exec($ch);
						curl_close($ch);
						$return_data_arr=json_decode($return_data, true);
						//print_r($return_data_arr);
						if($return_data_arr['status']=="success")
						{
							$_SESSION['SET_TYPE'] = 'success';
							$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
							header("location:packages");
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
				}
				$post_data['data']=$_GET;
				$post_data_str=json_encode($post_data);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.PACKAGE_API_PATH."package/read.php");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				$return_data = curl_exec($ch);
				curl_close($ch);
				$return_data_arr=json_decode($return_data, true);
				$package_data=array();
				if(!isset($return_data_arr['status'])):
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH']="Some error has been occure during execution.";
					header("location:packages");
					exit;
				elseif($return_data_arr['status']=="success"):
					$package_data=$return_data_arr['results'];
				else:
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
					header("location:packages");
					exit;
				endif;
				//print_r($return_data);
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
		header("location:packages");
		exit;
	endif;
	//print_r($country_data);
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
		$("#country").change(function(){
			fetch_state($(this).val());
		});
		$("#state").change(function(){
			fetch_city($(this).val());
		});
		<?php 
		if((isset($_POST['country']) && $_POST['country']!="") || (isset($package_data['country']) && $package_data['country']!=""))
		{
		?>
			fetch_state(<?php echo(isset($_POST['country']) && $_POST['country']!="" ? $_POST['country'] : (isset($package_data['country']) && $package_data['country']!="" ? $package_data['country'] : ""));?>);
		<?php
		}
		?>
		<?php 
		if((isset($_POST['state']) && $_POST['state']!="") || (isset($package_data['state']) && $package_data['state']!=""))
		{
		?>
			fetch_city(<?php echo(isset($_POST['state']) && $_POST['state']!="" ? $_POST['state'] : (isset($package_data['state']) && $package_data['state']!="" ? $package_data['state'] : ""));?>);
		<?php
		}
		?>
	});
	function fetch_state(country_id)
	{
		$.ajax({
			url:"<?= DOMAIN_NAME_PATH_ADMIN."ajax_state_fetch_for_package.php";?>",
			type:"post",
			data:{
				country_id:country_id
			},
			beforeSend:function(){
				$("#city").html('<option value = "">Select City</option>');
				$("#state").html('<option value = "">Select State / Region</option>');
			},
			dataType:"json",
			success:function(response){
				//console.log(response);
				if(response.status=="success")
				{
					if(response.results.length > 0)
					{
						$.each(response.results, function(state_key, state_val){
							$("#state").append('<option value = "'+state_val['id']+'">'+state_val['name']+'</option>');
						});
					}
				}
				else
				{
					//showError(response.msg);
				}
			},
			error:function(){
				//showError("We are having some problem. Please try later.");
			}
		}).done(function(){
			$("#state").val('<?php echo(isset($_POST['state']) && $_POST['state']!="" ? $_POST['state'] : (isset($package_data['state']) && $package_data['state']!="" ? $package_data['state'] : ""));?>');
		});;
	}
	function fetch_city(state_id)
	{
		$.ajax({
			url:"<?= DOMAIN_NAME_PATH_ADMIN."ajax_city_fetch_for_package.php";?>",
			type:"post",
			data:{
				state_id:state_id
			},
			beforeSend:function(){
				$("#city").html('<option value = "">Select City</option>');
			},
			dataType:"json",
			success:function(response){
				//console.log(response);
				if(response.status=="success")
				{
					if(response.results.length > 0)
					{
						$.each(response.results, function(city_key, city_val){
							$("#city").append('<option value = "'+city_val['id']+'">'+city_val['name']+'</option>');
						});
					}
				}
				else
				{
					//showError(response.msg);
				}
			},
			error:function(){
				//showError("We are having some problem. Please try later.");
			}
		}).done(function(){
			$("#city").val('<?php echo(isset($_POST['city']) && $_POST['city']!="" ? $_POST['city'] : (isset($package_data['city']) && $package_data['city']!="" ? $package_data['city'] : ""));?>');
		});
	}
	function remove_img(cur, image_name, package_id)
	{
		if(confirm("Are you sure you want to delete this image?"))
		{
			$.ajax({
				url:"<?= DOMAIN_NAME_PATH_ADMIN."ajax_package_image_delete";?>",
				type:"post",
				data:{
					package_id:package_id,
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
											<label for="country" class="control-label">Country<font color="#FF0000">*</font></label>
											<select name = "country" id="country" class="form-control form_input1 select_bg validate[required]" tabindex = "1">
												<option value = "">Select Country</option>
												<?php
												if(!empty($country_data)):
													foreach($country_data as $country_key=>$country_val):
												?>
													<option value = "<?php echo $country_val['id'];?>" <?php echo(isset($_POST['country']) && $_POST['country']==$country_val['id'] ? 'selected="selected"' : (isset($package_data['country']) && $package_data['country']==$country_val['id'] ? 'selected="selected"' : ""));?>><?php echo $country_val['name'];?></option>
												<?php
													endforeach;
												endif;
												?>
											</select>
										</div>
										<div class="form-group col-md-3">
											<label for="state" class="control-label">State / Region<font color="#FF0000">*</font></label>
											<select name = "state" id="state" class="form-control form_input1 select_bg validate[required]" tabindex = "2">
												<option value = "">Select State / Region</option>
											</select>
										</div>
										<div class="form-group col-md-3">
											<label for="city" class="control-label">City<font color="#FF0000">*</font></label>
											<select name = "city" id="city" class="form-control form_input1 select_bg validate[required]" tabindex = "3">
												<option value = "">Select City</option>
											</select>
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-6">
											<label for="package_title" class="control-label">Package Title<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['package_title']) && $_POST['package_title']!='' ? $_POST['package_title'] : (isset($package_data['package_title']) && $package_data['package_title']!='' ? $package_data['package_title'] : ""));?>" name="package_title" id="package_title" placeholder="Package Title" tabindex = "4" />
										</div>

										<div class="form-group col-md-6">
											<label for="package_type" class="control-label">No Of Days<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['no_of_days']) && $_POST['no_of_days']!='' ? $_POST['no_of_days'] : (isset($package_data['no_of_days']) && $package_data['no_of_days']!='' ? $package_data['no_of_days'] : ""));?>" name="no_of_days" id="no_of_days" placeholder="No Of Days" tabindex = "5" />
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-12">
											<label for="description" class="control-label">Description</label>
											<textarea class="form-control ckeditor" name="description" id="description" placeholder="Description" tabindex = "6"><?php echo(isset($_POST['description']) && $_POST['description']!='' ? $_POST['description'] : (isset($package_data['description']) && $package_data['description']!='' ? $package_data['description'] : ""));?></textarea>
										</div>
										<div class="form-group col-md-6">
											<label for="package_price" class="control-label">Package Price<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['package_price']) && $_POST['package_price']!='' ? $_POST['package_price'] : (isset($package_data['package_price']) && $package_data['package_price']!='' ? $package_data['package_price'] : ""));?>" name="package_price" id="package_price" placeholder="Package Price" tabindex = "7" />
										</div>
										<div class="form-group col-md-6">
											<label for="discounted_price" class="control-label">Discounted Price<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['discounted_price']) && $_POST['discounted_price']!='' ? $_POST['discounted_price'] : (isset($package_data['discounted_price']) && $package_data['discounted_price']!='' ? $package_data['discounted_price'] : ""));?>" name="discounted_price" id="discounted_price" placeholder="Discounted Price" tabindex = "8" />
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-6">
											<label for="package_images" class="control-label">Package Images<font color="#FF0000">*</font></label>
											<input type="file" class="form-control"  value="" name="package_images[]" id="package_images" placeholder="Package Images" tabindex = "9" multiple/>
											<br/>
											<font color = "red">SELECT MULTIPLE BY HOLDING CONTROL BUTTON.</font>
										</div>
										<div class="form-group col-md-6">
											<label for="status" class="control-label">Status<font color="#FF0000">*</font></label>
											<select class="form-control validate[required]" name="status" id="status" tabindex = "10">
												<option value = "1" <?php echo(isset($_POST['status']) && $_POST['status']==1 ? 'selected="selected"' : (isset($package_data['status']) && $package_data['status']==1 ? 'selected="selected"' : ""));?>>Active</option>
												<option value = "0" <?php echo(isset($_POST['status']) && $_POST['status']==0 ? 'selected="selected"' : (isset($package_data['status']) && $package_data['status']==0 ? 'selected="selected"' : ""));?>>Inactive</option>
											</select>
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-12">
											<?php
											if(isset($package_data['package_images']) && $package_data['package_images']!=""):
												$image_arr=explode(",", $package_data['package_images']);
												foreach($image_arr as $img_key=>$img_val):
													if($img_val!=""):
											?>
												<div style="display:inline-block;position:relative;">
													<img src = "<?php echo(PACKAGE_IMAGE_PATH.$img_val);?>" border = "0" alt = "" style="width:150px;height:100px;margin:1px;" onerror="this.remove;"/>
													<img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>delete.png" border = "0" alt = "" style="width:12px;height:18px;position:absolute;top:5px;right:10px;cursor:pointer;" onclick="remove_img($(this), '<?php echo $img_val;?>', <?php echo $package_data['id'];?>)"/>
												</div>
											<?php
													endif;
												endforeach;
											endif;
											?>
										</div>
									</div>
								</div>
								<div class="col-md-12 row">
									<div class="box-footer">
										<input type="hidden" name="token" value="<?php echo(tools::generateFormToken($verify_token)); ?>" />
										<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex = "11">UPDATE</button>
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