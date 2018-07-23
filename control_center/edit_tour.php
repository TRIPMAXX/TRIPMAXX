<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
	$white_list_array = array('tour_title', 'tour_images', 'tour_type', 'tour_service', 'service_note', 'country', 'state', 'city', 'short_description', 'long_description', 'tour_start_time', 'tour_end_time', 'is_cancellation_policy_applied', 'cancellation_charge', 'cancellation_allowed_days', 'other_policy', 'is_guide_included', 'guide_language', 'status', 'token', 'id', 'btn_submit');
	$verify_token = "edit_tour";
	if(isset($_GET['tour_id']) && $_GET['tour_id']!=""):
		$autentication_data=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.TOUR_API_PATH."authorized.php"));
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
				curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.TOUR_API_PATH."country/read.php");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				$return_data = curl_exec($ch);
				curl_close($ch);
				$return_data_arr=json_decode($return_data, true);
				//print_r($return_data_arr);
				$country_data=array();
				if($return_data_arr['status']=="success"):
					$country_data=$return_data_arr['results'];
				//else:
				//	$_SESSION['SET_TYPE'] = 'error';
				//	$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
				endif;
				$post_data['data']['status']=1;
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
				if($return_data_arr['status']=="success"):
					$attribute_data=$return_data_arr['results'];
				
				//else:
				//	$_SESSION['SET_TYPE'] = 'error';
				//	$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
				endif;
				if(isset($_POST['btn_submit'])) {
					$_POST['id']=base64_decode($_GET['tour_id']);
					if(tools::verify_token($white_list_array, $_POST, $verify_token)) {
						$_POST['uploaded_files']=array();
						if(isset($_FILES["tour_images"])){
							foreach($_FILES["tour_images"]['name'] as $file_key=>$file_val):
								$extension = pathinfo($file_val, PATHINFO_EXTENSION);
								//$splited_name=explode(".", $file_val);
								//$extension = end($splited_name);
								$validation_array = array('jpg', 'jpeg', 'png', 'gif', 'bmp');
								if(in_array(strtolower($extension), $validation_array)) {
									$data = file_get_contents($_FILES["tour_images"]['tmp_name'][$file_key]);
									$base64 = 'data:image/' . $extension . ';base64,' . base64_encode($data);
									array_push($_POST['uploaded_files'], curl_file_create($base64, $_FILES["tour_images"]['type'][$file_key], $_FILES["tour_images"]['name'][$file_key]));
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
						curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.TOUR_API_PATH."tour/update.php");
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
						$return_data = curl_exec($ch);
						curl_close($ch);
						$return_data_arr=json_decode($return_data, true);
						//print_r($return_data_arr);
						if($return_data_arr['status']=="success")
						{
							$_SESSION['SET_TYPE'] = 'success';
							$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
							header("location:tours");
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
				curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.TOUR_API_PATH."tour/read.php");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				$return_data = curl_exec($ch);
				curl_close($ch);
				$return_data_arr=json_decode($return_data, true);
				$tour_data=array();
				if(!isset($return_data_arr['status'])):
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH']="Some error has been occure during execution.";
					header("location:tours");
					exit;
				elseif($return_data_arr['status']=="success"):
					$tour_data=$return_data_arr['results'];
				else:
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
					header("location:tours");
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
		header("location:tours");
		exit;
	endif;
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>EDIT TOUR</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	$(function(){
		$("#form_edit_tour").validationEngine();
		$("#country").change(function(){
			fetch_state($(this).val());
		});
		$("#state").change(function(){
			fetch_city($(this).val());
		});
		<?php 
		if((isset($_POST['country']) && $_POST['country']!="") || (isset($tour_data['country']) && $tour_data['country']!=""))
		{
		?>
			fetch_state(<?php echo(isset($_POST['country']) && $_POST['country']!="" ? $_POST['country'] : (isset($tour_data['country']) && $tour_data['country']!="" ? $tour_data['country'] : ""));?>);
		<?php
		}
		?>
		<?php 
		if((isset($_POST['state']) && $_POST['state']!="") || (isset($tour_data['state']) && $tour_data['state']!=""))
		{
		?>
			fetch_city(<?php echo(isset($_POST['state']) && $_POST['state']!="" ? $_POST['state'] : (isset($tour_data['state']) && $tour_data['state']!="" ? $tour_data['state'] : ""));?>);
		<?php
		}
		?>
	});
	function fetch_state(country_id)
	{
		$.ajax({
			url:"<?= DOMAIN_NAME_PATH_ADMIN."ajax_tour_state_fetch";?>",
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
			$("#state").val('<?php echo(isset($_POST['state']) && $_POST['state']!="" ? $_POST['state'] : (isset($tour_data['state']) && $tour_data['state']!="" ? $tour_data['state'] : ""));?>');
		});;
	}
	function fetch_city(state_id)
	{
		$.ajax({
			url:"<?= DOMAIN_NAME_PATH_ADMIN."ajax_tour_city_fetch";?>",
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
			$("#city").val('<?php echo(isset($_POST['city']) && $_POST['city']!="" ? $_POST['city'] : (isset($tour_data['city']) && $tour_data['city']!="" ? $tour_data['city'] : ""));?>');
		});
	}
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
               <h1>Edit Tour</h1>
               <ol class="breadcrumb">
                  <li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                  <li class="active">Edit Tour</li>
               </ol>
            </section>
            <section class="content">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
							<form name="form_edit_tour" id="form_edit_tour" method="POST" enctype="multipart/form-data">
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
													<option value = "<?php echo $country_val['id'];?>" <?php echo(isset($_POST['country']) && $_POST['country']==$country_val['id'] ? 'selected="selected"' : (isset($tour_data['country']) && $tour_data['country']==$country_val['id'] ? 'selected="selected"' : ""));?>><?php echo $country_val['name'];?></option>
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
											<label for="tour_title" class="control-label">Tour Title<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required, custom[onlyLetterNumber]]"  value="<?php echo(isset($_POST['tour_title']) && $_POST['tour_title']!='' ? $_POST['tour_title'] : (isset($tour_data['tour_title']) && $tour_data['tour_title']!='' ? $tour_data['tour_title'] : ""));?>" name="tour_title" id="tour_title" placeholder="Tour Title" tabindex = "4" />
										</div>
										<div class="form-group col-md-6">
											<label for="tour_type" class="control-label">Tour Type<font color="#FF0000">*</font></label>
											<select name = "tour_type" id="tour_type" class="form-control form_input1 select_bg validate[required]" tabindex = "5">
												<option value = "">Select Tour Type</option>
												<?php
												if(!empty($attribute_data)):
													foreach($attribute_data as $attribute_key=>$attribute_val):
												?>
													<option value = "<?php echo $attribute_val['id'];?>" <?php echo(isset($_POST['tour_type']) && $_POST['tour_type']==$attribute_val['id'] ? 'selected="selected"' : (isset($tour_data['tour_type']) && $tour_data['tour_type']==$attribute_val['id'] ? 'selected="selected"' : ""));?>><?php echo $attribute_val['attribute_name'];?></option>
												<?php
													endforeach;
												endif;
												?>
											</select>
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-4">
											<label for="tour_images" class="control-label">Tour Images</label>
											<input type="file" class="form-control"  value="" name="tour_images[]" id="tour_images" placeholder="Tour Images" tabindex = "8" multiple/>
											<br/>
											<font color = "red">SELECT MULTIPLE BY HOLDING CONTROL BUTTON.</font>
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-12">
											<?php
											if(isset($tour_data['tour_images']) && $tour_data['tour_images']!=""):
												$image_arr=explode(",", $tour_data['tour_images']);
												foreach($image_arr as $img_key=>$img_val):
													if($img_val!=""):
											?>
												<div style="display:inline-block;position:relative;">
													<img src = "<?php echo(TOUR_IMAGE_PATH.$img_val);?>" border = "0" alt = "" style="width:150px;height:100px;margin:1px;" onerror="this.remove;"/>
													<img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>delete.png" border = "0" alt = "" style="width:12px;height:18px;position:absolute;top:5px;right:10px;cursor:pointer;" onclick="remove_img($(this), '<?php echo $img_val;?>', <?php echo $tour_data['id'];?>)"/>
												</div>
											<?php
													endif;
												endforeach;
											endif;
											?>
										</div>
										<div class="form-group col-md-6">
											<label for="tour_start_time" class="control-label">Tour Start Time <font color="#FF0000">*</font></label>
											<input type="time" class="form-control validate[required]"  value="<?php echo(isset($_POST['tour_start_time']) && $_POST['tour_start_time']!='' ? $_POST['tour_start_time'] : (isset($tour_data['tour_start_time']) && $tour_data['tour_start_time']!='' ? $tour_data['tour_start_time'] : ""));?>" name="tour_start_time" id="tour_start_time" placeholder="Tour Start Time" tabindex = "9" />
										</div>
										<div class="form-group col-md-6">
											<label for="tour_end_time" class="control-label">Tour End Time <font color="#FF0000">*</font></label>
											<input type="time" class="form-control validate[required]"  value="<?php echo(isset($_POST['tour_end_time']) && $_POST['tour_end_time']!='' ? $_POST['tour_end_time'] : (isset($tour_data['tour_end_time']) && $tour_data['tour_end_time']!='' ? $tour_data['tour_end_time'] : ""));?>" name="tour_end_time" id="tour_end_time" placeholder="Tour End Time" tabindex = "10" />
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-12">
											<label for="short_description" class="control-label">Short Description</label>
											<textarea class="form-control" name="short_description" id="short_description" placeholder="Short Description" tabindex = "11"><?php echo(isset($_POST['short_description']) && $_POST['short_description']!='' ? $_POST['short_description'] : (isset($tour_data['short_description']) && $tour_data['short_description']!='' ? $tour_data['short_description'] : ""));?></textarea>
										</div>
										<div class="form-group col-md-12">
											<label for="long_description" class="control-label">Long Description</label>
											<textarea class="form-control ckeditor" name="long_description" id="long_description" placeholder="Long Description" tabindex = "12"><?php echo(isset($_POST['long_description']) && $_POST['long_description']!='' ? $_POST['long_description'] : (isset($tour_data['long_description']) && $tour_data['long_description']!='' ? $tour_data['long_description'] : ""));?></textarea>
										</div>
										<div class="form-group col-md-4">
											<label for="is_cancellation_policy_applied" class="control-label">Is Cancellation Policy Applied?<font color="#FF0000">*</font></label>
											<select name = "is_cancellation_policy_applied" id="is_cancellation_policy_applied" class="form-control form_input1 select_bg" tabindex="13">
												<option value = "1" <?php echo(isset($_POST['is_cancellation_policy_applied']) && $_POST['is_cancellation_policy_applied']==1 ? 'selected="selected"' : (isset($tour_data['is_cancellation_policy_applied']) && $tour_data['is_cancellation_policy_applied']==1 ? 'selected="selected"' : ""));?>>Yes</option>
												<option value = "0" <?php echo(isset($_POST['is_cancellation_policy_applied']) && $_POST['is_cancellation_policy_applied']==0 ? 'selected="selected"' : (isset($tour_data['is_cancellation_policy_applied']) && $tour_data['is_cancellation_policy_applied']==0 ? 'selected="selected"' : ""));?>>No</option>
											</select>
										</div>
										<div class="form-group col-md-4">
											<label for="cancellation_charge" class="control-label">Cancellation Charge</label>
											<input type="text" class="form-control"  value="<?php echo(isset($_POST['cancellation_charge']) && $_POST['cancellation_charge']!='' ? $_POST['cancellation_charge'] : (isset($tour_data['cancellation_charge']) && $tour_data['cancellation_charge']!='' ? $tour_data['cancellation_charge'] : ""));?>" name="cancellation_charge" id="cancellation_charge" placeholder="Cancellation Charge" tabindex = "14" />
										</div>
										<div class="form-group col-md-4">
											<label for="cancellation_allowed_days" class="control-label">Cancellation Allowed Days</label>
											<select name = "cancellation_allowed_days" id="cancellation_allowed_days" class="form-control form_input1 select_bg" tabindex = "15">
											<?php
											for($i=1;$i<=6;$i++):
											?>
												<option value = "<?= $i;?>" <?php echo(isset($_POST['cancellation_allowed_days']) && $_POST['cancellation_allowed_days']==$i ? 'selected="selected"' : (isset($tour_data['cancellation_allowed_days']) && $tour_data['cancellation_allowed_days']==$i ? 'selected="selected"' : ""));?>><?= $i;?> <?php echo($i > 1 ? "Days" : "Day");?></option>
											<?php
											endfor;
											?>
											</select>
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-12">
											<label for="other_policy" class="control-label">Other Policies</label>
											<textarea class="form-control ckeditor"  value="" name="other_policy" id="other_policy" placeholder="Other Policies" tabindex = "16"><?php echo(isset($_POST['other_policy']) && $_POST['other_policy']!='' ? $_POST['other_policy'] : (isset($tour_data['other_policy']) && $tour_data['other_policy']!='' ? $tour_data['other_policy'] : ""));?></textarea>
										</div>
										<div class="form-group col-md-4">
											<label for="is_guide_included" class="control-label">Guide Included<font color="#FF0000">*</font></label>
											<select name = "is_guide_included" id="is_guide_included" class="form-control form_input1 select_bg" tabindex="17">
												<option value = "1" <?php echo(isset($_POST['is_guide_included']) && $_POST['is_guide_included']==1 ? 'selected="selected"' : (isset($tour_data['is_guide_included']) && $tour_data['is_guide_included']==1 ? 'selected="selected"' : ""));?>>Yes</option>
												<option value = "0" <?php echo(isset($_POST['is_guide_included']) && $_POST['is_guide_included']==0 ? 'selected="selected"' : (isset($tour_data['is_guide_included']) && $tour_data['is_guide_included']==0 ? 'selected="selected"' : ""));?>>No</option>
											</select>
										</div>
										<div class="form-group col-md-4">
											<label for="guide_language" class="control-label">Guide Language</label>
											<input type="text" class="form-control"  value="<?php echo(isset($_POST['guide_language']) && $_POST['guide_language']!='' ? $_POST['guide_language'] : (isset($tour_data['guide_language']) && $tour_data['guide_language']!='' ? $tour_data['guide_language'] : ""));?>" name="guide_language" id="guide_language" placeholder="Guide Language" tabindex = "18" />
										</div>
										<div class="form-group col-md-4">
											<label for="status" class="control-label">Status<font color="#FF0000">*</font></label>
											<select class="form-control validate[required]" name="status" id="status" tabindex = "19">
												<option value = "1" <?php echo(isset($_POST['status']) && $_POST['status']==1 ? 'selected="selected"' : (isset($tour_data['status']) && $tour_data['status']==1 ? 'selected="selected"' : ""));?>>Active</option>
												<option value = "0" <?php echo(isset($_POST['status']) && $_POST['status']==0 ? 'selected="selected"' : (isset($tour_data['status']) && $tour_data['status']==0 ? 'selected="selected"' : ""));?>>Inactive</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-md-12 row">
									<div class="box-footer">
										<input type="hidden" name="token" value="<?php echo(tools::generateFormToken($verify_token)); ?>" />
										<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex = "20">UPDATE</button>
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