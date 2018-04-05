<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
	$white_list_array = array('hotel_name', 'hotel_images', 'email_address', 'password', 'confirm_password', 'hotel_address', 'country', 'state', 'city', 'postal_code', 'phone_number', 'alternate_phone_number', 'short_description', 'long_description', 'checkin_time', 'checkout_time', 'rating', 'is_cancellation_policy_applied', 'cancellation_charge', 'cancellation_allowed_days', 'other_policy', 'amenities', 'amenities_arr', 'status', 'token', 'id', 'btn_submit');
	$verify_token = "edit_hotel";
	if(isset($_GET['hotel_id']) && $_GET['hotel_id']!=""):
		$autentication_data=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."authorized.php"));
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
			curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."country/read.php");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$return_data = curl_exec($ch);
			curl_close($ch);
			$return_data_arr=json_decode($return_data, true);
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
			curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."attribute/read.php");
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
				$_POST['id']=base64_decode($_GET['hotel_id']);
				if(tools::verify_token($white_list_array, $_POST, $verify_token)) {
					$_POST['uploaded_files']=array();
					if(isset($_FILES["hotel_images"])){
						foreach($_FILES["hotel_images"]['name'] as $file_key=>$file_val):
							$extension = pathinfo($file_val, PATHINFO_EXTENSION);
							//$splited_name=explode(".", $file_val);
							//$extension = end($splited_name);
							$validation_array = array('jpg', 'jpeg', 'png', 'gif', 'bmp');
							if(in_array(strtolower($extension), $validation_array)) {
								$data = file_get_contents($_FILES["hotel_images"]['tmp_name'][$file_key]);
								$base64 = 'data:image/' . $extension . ';base64,' . base64_encode($data);
								array_push($_POST['uploaded_files'], curl_file_create($base64, $_FILES["hotel_images"]['type'][$file_key], $_FILES["hotel_images"]['name'][$file_key]));
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
					curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."hotel/update.php");
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					$return_data = curl_exec($ch);
					curl_close($ch);
					$return_data_arr=json_decode($return_data, true);
					//print_r($return_data_arr);
					if($return_data_arr['status']=="success")
					{
						$_SESSION['SET_TYPE'] = 'success';
						$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
						header("location:hotels");
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
			curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."hotel/read.php");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$return_data = curl_exec($ch);
			curl_close($ch);
			$return_data_arr=json_decode($return_data, true);
			$hotel_data=array();
			if(!isset($return_data_arr['status'])):
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH']="Some error has been occure during execution.";
				header("location:hotels");
				exit;
			elseif($return_data_arr['status']=="success"):
				$hotel_data=$return_data_arr['results'];
			else:
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
				header("location:hotels");
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
	header("location:hotels");
	exit;
endif;
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>EDIT HOTEL</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	$(function(){
		$("#form_edit_hotel").validationEngine();
		$("#country").change(function(){
			fetch_state($(this).val());
		});
		$("#state").change(function(){
			fetch_city($(this).val());
		});
		<?php 
		if((isset($_POST['country']) && $_POST['country']!="") || (isset($hotel_data['country']) && $hotel_data['country']!=""))
		{
		?>
			fetch_state(<?php echo(isset($_POST['country']) && $_POST['country']!="" ? $_POST['country'] : (isset($hotel_data['country']) && $hotel_data['country']!="" ? $hotel_data['country'] : ""));?>);
		<?php
		}
		?>
		<?php 
		if((isset($_POST['state']) && $_POST['state']!="") || (isset($hotel_data['state']) && $hotel_data['state']!=""))
		{
		?>
			fetch_city(<?php echo(isset($_POST['state']) && $_POST['state']!="" ? $_POST['state'] : (isset($hotel_data['state']) && $hotel_data['state']!="" ? $hotel_data['state'] : ""));?>);
		<?php
		}
		?>
	});
	function fetch_state(country_id)
	{
		$.ajax({
			url:"<?= DOMAIN_NAME_PATH_ADMIN."ajax_state_fetch";?>",
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
			$("#state").val('<?php echo(isset($_POST['state']) && $_POST['state']!="" ? $_POST['state'] : (isset($hotel_data['state']) && $hotel_data['state']!="" ? $hotel_data['state'] : ""));?>');
		});;
	}
	function fetch_city(state_id)
	{
		$.ajax({
			url:"<?= DOMAIN_NAME_PATH_ADMIN."ajax_city_fetch";?>",
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
			$("#city").val('<?php echo(isset($_POST['city']) && $_POST['city']!="" ? $_POST['city'] : (isset($hotel_data['city']) && $hotel_data['city']!="" ? $hotel_data['city'] : ""));?>');
		});
	}
	function remove_img(cur, image_name, hotel_id)
	{
		if(confirm("Are you sure you want to delete this image?"))
		{
			$.ajax({
				url:"<?= DOMAIN_NAME_PATH_ADMIN."ajax_hotel_image_delete";?>",
				type:"post",
				data:{
					hotel_id:hotel_id,
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
							<form name="profile" name="form_edit_hotel" id="form_edit_hotel" method="POST" enctype="multipart/form-data">
								<div class="col-md-12 row">
									<div class="box-body">
										<div class="form-group col-md-6">
											<label for="hotel_name" class="control-label">Hotel Name<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['hotel_name']) && $_POST['hotel_name']!='' ? $_POST['hotel_name'] : (isset($hotel_data['hotel_name']) && $hotel_data['hotel_name']!='' ? $hotel_data['hotel_name'] : ""));?>" name="hotel_name" id="hotel_name" placeholder="Hotel Name" tabindex = "1" />
										</div>
										<div class="form-group col-md-6">
											<label for="hotel_images" class="control-label">Hotel Images</label>
											<input type="file" class="form-control"  value="" name="hotel_images[]" id="hotel_images" placeholder="Hotel Image" tabindex = "2" multiple/>
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-12">
											<?php
											if(isset($hotel_data['hotel_images']) && $hotel_data['hotel_images']!=""):
												$image_arr=explode(",", $hotel_data['hotel_images']);
												foreach($image_arr as $img_key=>$img_val):
													if($img_val!=""):
											?>
												<div style="display:inline-block;position:relative;">
													<img src = "<?php echo(HOTEL_IMAGE_PATH.$img_val);?>" border = "0" alt = "" style="width:150px;height:100px;margin:1px;" onerror="this.remove;"/>
													<img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>delete.png" border = "0" alt = "" style="width:12px;height:18px;position:absolute;top:5px;right:10px;cursor:pointer;" onclick="remove_img($(this), '<?php echo $img_val;?>', <?php echo $hotel_data['id'];?>)"/>
												</div>
											<?php
													endif;
												endforeach;
											endif;
											?>
										</div>
										<div class="form-group col-md-4">
											<label for="email_address" class="control-label">Email Address<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['email_address']) && $_POST['email_address']!='' ? $_POST['email_address'] : (isset($hotel_data['email_address']) && $hotel_data['email_address']!='' ? $hotel_data['email_address'] : ""));?>" name="email_address" id="email_address" placeholder="Email Address" tabindex = "3" />
										</div>
										<div class="form-group col-md-4">
											<label for="inputName" class="control-label">Password</label>
											<input type="password" class="form-control"  value="" name="password" id="password" placeholder="Password" tabindex = "4"/>
										</div>
										<div class="form-group col-md-4">
											<label for="confirm_password" class="control-label">Confirm Password</label>
											<input type="password" class="form-control validate[equals[password]]"  value="" name="confirm_password" id="confirm_password" placeholder="Confirm Password" tabindex = "5"/>
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-12">
											<label for="inputName" class="control-label">Hotel Address<font color="#FF0000">*</font></label>
											<textarea class="form-control validate[required]"  value="" name="hotel_address" id="hotel_address" placeholder="Hotel Address" tabindex = "6"><?php echo(isset($_POST['hotel_address']) && $_POST['hotel_address']!='' ? $_POST['hotel_address'] : (isset($hotel_data['hotel_address']) && $hotel_data['hotel_address']!='' ? $hotel_data['hotel_address'] : ""));?></textarea>
										</div>
										<div class="form-group col-md-3">
											<label for="country" class="control-label">Country<font color="#FF0000">*</font></label>
											<select name = "country" id="country" class="form-control form_input1 select_bg validate[required]" tabindex = "7">
												<option value = "">Select Country</option>
												<?php
												if(!empty($country_data)):
													foreach($country_data as $country_key=>$country_val):
												?>
													<option value = "<?php echo $country_val['id'];?>" <?php echo(isset($_POST['country']) && $_POST['country']==$country_val['id'] ? 'selected="selected"' : (isset($hotel_data['country']) && $hotel_data['country']==$country_val['id'] ? 'selected="selected"' : ""));?>><?php echo $country_val['name'];?></option>
												<?php
													endforeach;
												endif;
												?>
											</select>
										</div>
										<div class="form-group col-md-3">
											<label for="state" class="control-label">State / Region<font color="#FF0000">*</font></label>
											<select name = "state" id="state" class="form-control form_input1 select_bg validate[required]" tabindex = "8">
												<option value = "">Select State / Region</option>
											</select>
										</div>
										<div class="form-group col-md-3">
											<label for="city" class="control-label">City<font color="#FF0000">*</font></label>
											<select name = "city" id="city" class="form-control form_input1 select_bg validate[required]" tabindex = "9">
												<option value = "">Select City</option>
											</select>
										</div>
										<div class="form-group col-md-3">
											<label for="postal_code" class="control-label">Postal Code<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['postal_code']) && $_POST['postal_code']!='' ? $_POST['postal_code'] : (isset($hotel_data['postal_code']) && $hotel_data['postal_code']!='' ? $hotel_data['postal_code'] : ""));?>" name="postal_code" id="postal_code" placeholder="Postal Code" tabindex = "10" />
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-6">
											<label for="phone_number" class="control-label">Phone Number<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['phone_number']) && $_POST['phone_number']!='' ? $_POST['phone_number'] : (isset($hotel_data['phone_number']) && $hotel_data['phone_number']!='' ? $hotel_data['phone_number'] : ""));?>" name="phone_number" id="phone_number" placeholder="Phone Number" tabindex = "11" />
										</div>
										<div class="form-group col-md-6">
											<label for="alternate_phone_number" class="control-label">Altername Phone Number</label>
											<input type="text" class="form-control"  value="<?php echo(isset($_POST['alternate_phone_number']) && $_POST['alternate_phone_number']!='' ? $_POST['alternate_phone_number'] : (isset($hotel_data['alternate_phone_number']) && $hotel_data['alternate_phone_number']!='' ? $hotel_data['alternate_phone_number'] : ""));?>" name="alternate_phone_number" id="alternate_phone_number" placeholder="Altername Phone Number" tabindex = "12" />
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-12">
											<label for="short_description" class="control-label">Short Description</label>
											<textarea class="form-control validate[optional]"  value="" name="short_description" id="short_description" placeholder="Short Description" tabindex = "13"><?php echo(isset($_POST['short_description']) && $_POST['short_description']!='' ? $_POST['short_description'] : (isset($hotel_data['short_description']) && $hotel_data['short_description']!='' ? $hotel_data['short_description'] : ""));?></textarea>
										</div>
										<div class="form-group col-md-12">
											<label for="long_description" class="control-label">Long Description</label>
											<textarea class="form-control ckeditor validate[required]"  value="" name="long_description" id="long_description" placeholder="Long Description" tabindex = "14"><?php echo(isset($_POST['long_description']) && $_POST['long_description']!='' ? $_POST['long_description'] : (isset($hotel_data['long_description']) && $hotel_data['long_description']!='' ? $hotel_data['long_description'] : ""));?></textarea>
										</div>
										<div class="form-group col-md-4">
											<label for="checkin_time" class="control-label">Checkin Time (24 hr format - hh:mm)<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['checkin_time']) && $_POST['checkin_time']!='' ? $_POST['checkin_time'] : (isset($hotel_data['checkin_time']) && $hotel_data['checkin_time']!='' ? $hotel_data['checkin_time'] : ""));?>" name="checkin_time" id="checkin_time" placeholder="Checkin Time" tabindex = "15" />
										</div>
										<div class="form-group col-md-4">
											<label for="checkout_time" class="control-label">Checkout Time(24 hr format - hh:mm)<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['checkout_time']) && $_POST['checkout_time']!='' ? $_POST['checkout_time'] : (isset($hotel_data['checkout_time']) && $hotel_data['checkout_time']!='' ? $hotel_data['checkout_time'] : ""));?>" name="checkout_time" id="checkout_time" placeholder="Checkout Time" tabindex = "16" />
										</div>
										<div class="form-group col-md-4">
											<label for="rating" class="control-label">Star Rating<font color="#FF0000">*</font></label>
											<select name = "rating" id="rating" class="form-control form_input1 select_bg" tabindex="17">
											<?php
											for($i=1;$i<=5;$i++):
											?>
												<option value = "<?= $i;?>" <?php echo(isset($_POST['rating']) && $_POST['rating']==$i ? 'selected="selected"' : (isset($hotel_data['rating']) && $hotel_data['rating']==$i ? 'selected="selected"' : ""));?>><?= $i;?> Star</option>
											<?php
											endfor;
											?>
											</select>
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-4">
											<label for="is_cancellation_policy_applied" class="control-label">Is Cancellation Policy Applied?<font color="#FF0000">*</font></label>
											<select name = "is_cancellation_policy_applied" id="is_cancellation_policy_applied" class="form-control form_input1 select_bg" tabindex="18">
												<option value = "1" <?php echo(isset($_POST['is_cancellation_policy_applied']) && $_POST['is_cancellation_policy_applied']==1 ? 'selected="selected"' : (isset($hotel_data['is_cancellation_policy_applied']) && $hotel_data['is_cancellation_policy_applied']==1 ? 'selected="selected"' : ""));?>>Yes</option>
												<option value = "0" <?php echo(isset($_POST['is_cancellation_policy_applied']) && $_POST['is_cancellation_policy_applied']==0 ? 'selected="selected"' : (isset($hotel_data['is_cancellation_policy_applied']) && $hotel_data['is_cancellation_policy_applied']==0 ? 'selected="selected"' : ""));?>>No</option>
											</select>
										</div>
										<div class="form-group col-md-4">
											<label for="cancellation_charge" class="control-label">Cancellation Charge</label>
											<input type="text" class="form-control"  value="<?php echo(isset($_POST['cancellation_charge']) && $_POST['cancellation_charge']!='' ? $_POST['cancellation_charge'] : (isset($hotel_data['cancellation_charge']) && $hotel_data['cancellation_charge']!='' ? $hotel_data['cancellation_charge'] : ""));?>" name="cancellation_charge" id="cancellation_charge" placeholder="Cancellation Charge" tabindex = "19" />
										</div>
										<div class="form-group col-md-4">
											<label for="cancellation_allowed_days" class="control-label">Cancellation Allowed Days</label>
											<select name = "cancellation_allowed_days" id="cancellation_allowed_days" class="form-control form_input1 select_bg" tabindex = "20">
											<?php
											for($i=1;$i<=6;$i++):
											?>
												<option value = "<?= $i;?>" <?php echo(isset($_POST['cancellation_allowed_days']) && $_POST['cancellation_allowed_days']==$i ? 'selected="selected"' : (isset($hotel_data['cancellation_allowed_days']) && $hotel_data['cancellation_allowed_days']==$i ? 'selected="selected"' : ""));?>><?= $i;?> <?php echo($i > 1 ? "Days" : "Day");?></option>
											<?php
											endfor;
											?>
											</select>
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-12">
											<label for="other_policy" class="control-label">Other Policies</label>
											<textarea class="form-control ckeditor"  value="" name="other_policy" id="other_policy" placeholder="Other Policies" tabindex = "21"><?php echo(isset($_POST['other_policy']) && $_POST['other_policy']!='' ? $_POST['other_policy'] : (isset($hotel_data['other_policy']) && $hotel_data['other_policy']!='' ? $hotel_data['other_policy'] : ""));?></textarea>
										</div>
										<div class="form-group col-md-12">
											<label for="inputName" class="control-label">Available Amenities<font color="#FF0000">*</font></label>
											<br/>
											<?php
											if(isset($attribute_data) && !empty($attribute_data))
											{
												foreach($attribute_data as $attr_key=>$attr_val):
													if(isset($hotel_data['amenities']) && $hotel_data['amenities']!="")
													{
														$amenities_arr=explode(",", $hotel_data['amenities']);
													}
											?>
											<input type = "checkbox" id="maxcheck<?php echo $attr_key;?>" class=" validate[required]" name = "amenities_arr[]" value="<?= $attr_val['id'];?>" <?php echo(isset($_POST['amenities_arr']) && in_array($attr_val['id'], $_POST['amenities_arr']) ? 'checked="checked"' : (isset($amenities_arr) && in_array($attr_val['id'], $amenities_arr) ? 'checked="checked"' : ""));?>>&nbsp;<?= $attr_val['attribute_name'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<?php
												endforeach;
											}
											?>
										</div>
										<div class="form-group col-md-6">
											<label for="status" class="control-label">Status<font color="#FF0000">*</font></label>
											<select class="form-control validate[required]" name="status" id="status" tabindex = "22">
												<option value = "1" <?php echo(isset($_POST['status']) && $_POST['status']==1 ? 'selected="selected"' : (isset($hotel_data['status']) && $hotel_data['status']==1 ? 'selected="selected"' : ""));?>>Active</option>
												<option value = "0" <?php echo(isset($_POST['status']) && $_POST['status']==0 ? 'selected="selected"' : (isset($hotel_data['status']) && $hotel_data['status']==0 ? 'selected="selected"' : ""));?>>Inactive</option>
											</select>
										</div>
										<div class="clearfix"></div>
									</div>
								</div>
								<div class="col-md-12 row">
									<div class="box-footer">
										<input type="hidden" name="token" value="<?php echo(tools::generateFormToken($verify_token)); ?>" />
										<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex = "23">UPDATE</button>
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