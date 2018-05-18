<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
	$white_list_array = array('country', 'currency', 'package_title', 'no_of_days', 'description', 'pax', 'price', 'package_images', 'status', 'token', 'btn_submit');
	$verify_token = "create_new_package";
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
			//print_r($return_data_arr);
			$country_data=array();
			if($return_data_arr['status']=="success"):
				$country_data=$return_data_arr['results'];
			//else:
			//	$_SESSION['SET_TYPE'] = 'error';
			//	$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
			endif;		
			if(isset($_POST['btn_submit'])) {
				//print_r($_POST);exit;
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
					//print_r($post_data);
					$post_data_str=json_encode($post_data);
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_HEADER, false);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
					curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
					curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.PACKAGE_API_PATH."package/create.php");
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					$return_data = curl_exec($ch);
					curl_close($ch);
					//print_r($return_data);
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
				};
			};
		endif;
	endif;
	$currency_list = tools::find("all", TM_CURRENCIES, '*', "WHERE status=:status ORDER BY serial_number ASC", array(":status"=>1));
	//print_r($country_data);
	$currency_val="";
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>CREATE NEW PACKAGE</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	$(function(){
		$("#form_create_package").validationEngine();
	});
	
	function manage_currency_val($this) {
		var val = $this.find(':selected').attr('data-value');
		$(".currency_code").html("("+val+")");
		$(".add-row").attr("data-attr_currency", "("+val+")");
	}
		$(document).ready(function(){
			$(".add-row").click(function(){
				var new_row_key=$(this).attr("data-attr_key");
				var currency_code=$(this).attr("data-attr_currency");
				$(this).attr("data-attr_key", eval(new_row_key)+1);
				var markup = '';
				markup+='<div class="appended_row">';
					markup+='<div class="form-group col-md-6">';
						markup+='<input type="checkbox" name="record"/>&nbsp;&nbsp;<label for="pax" class="control-label">Pax<font color="#FF0000">*</font></label>';
						markup+='<input type="text" class="form-control  validate[required]"  value="" name="pax['+new_row_key+']" id="pax'+new_row_key+'" placeholder="Pax" tabindex = "7" />';
					markup+='</div>';
					markup+='<div class="form-group col-md-6">';
						markup+='<label for="price" class="control-label">Price <span class="currency_code">'+currency_code+'</span><font color="#FF0000">*</font></label>';
						markup+='<input type="text" class="form-control validate[required]"  value="" name="price['+new_row_key+']" id="price'+new_row_key+'" placeholder="Price" tabindex = "8" />';
					markup+='</div>';
					markup+='<div class="clearfix"></div>';
				markup+='</div>';
				//alert(new_row_key);
				$("#sample").append(markup);
			});
			
			// Find and remove selected table rows
			$(".delete-row").click(function(){
				if($("#sample").find('input[name="record"]:checked').length > 0)
				{
					$("#sample").find('input[name="record"]:checked').each(function(){
						$(this).parents("div.appended_row").remove();
					});
				}
				else
				{
					showError("Please select checkbox to delete row.");
				}
				match_night();
			});
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
               <h1>Create New Package</h1>
               <ol class="breadcrumb">
                  <li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                  <li class="active">Create New Package</li>
               </ol>
            </section>
            <section class="content">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
							<form name="form_create_package" id="form_create_package" method="POST" enctype="multipart/form-data">
								<div class="col-md-12 row">
									<div class="box-body">
										<div class="form-group col-md-6">
											<label for="country" class="control-label">Country<font color="#FF0000">*</font></label>
											<select name = "country" id="country" class="validate[required]  form-control form_input1 select_bg " tabindex = "1">
												<option value = "">Select Country</option>
												<?php
												if(!empty($country_data)):
													foreach($country_data as $country_key=>$country_val):
												?>
													<option value = "<?php echo $country_val['id'];?>" <?php echo(isset($_POST['country']) && $_POST['country']==$country_val['id'] ? 'selected="selected"' : "");?>><?php echo $country_val['name'];?></option>
												<?php
													endforeach;
												endif;
												?>
											</select>
										</div>
										<div class="form-group col-md-6">
											<label for="package_title" class="control-label">Package Title<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required, custom[onlyLetterNumber]]"  value="<?php echo(isset($_POST['package_title']) && $_POST['package_title']!='' ? $_POST['package_title'] : "");?>" name="package_title" id="package_title" placeholder="Package Title" tabindex = "4" />
										</div>
										<div class="clearfix"></div>

										<div class="form-group col-md-6">
											<label for="tour_type" class="control-label">No Of Days<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required, custom[onlyNumberSp]]"  value="<?php echo(isset($_POST['no_of_days']) && $_POST['no_of_days']!='' ? $_POST['no_of_days'] : "");?>" name="no_of_days" id="no_of_days" placeholder="No Of Days" tabindex = "5" />
										</div>
										<div class="form-group col-md-6">
											<label for="currency" class="control-label">Currency<font color="#FF0000">*</font></label>
											<select class="form-control validate[required] " name="currency" id="currency" onchange = "manage_currency_val($(this));">
												<option value="" data-value="">Select</option>
											<?php
											if(!empty($currency_list)):
												foreach($currency_list as $currency_key=>$currency_val):
											?>
												<option data-value="<?=$currency_val['currency_code']?>" value = "<?php echo $currency_val['id'];?>" <?php echo(isset($_POST['currency']) && $_POST['currency']==$currency_val['id'] ? 'selected="selected"' : "");?>><?php echo $currency_val['currency_name']." (".$currency_val['currency_code'].")";?></option>
											<?php
												endforeach;
											endif;
											?>
											</select>
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-12">
											<label for="description" class="control-label">Description</label>
											<textarea class="form-control ckeditor" name="description" id="description" placeholder="Description" tabindex = "6"><?php echo(isset($_POST['description']) && $_POST['description']!='' ? $_POST['description'] : "");?></textarea>
										</div>
									</div>
									<div class="box-body" id="sample">
										<?php if(!empty($_POST['pax'])):
											foreach($_POST['pax'] as $pax_key => $pax_val):
										?>
										<div class="form-group col-md-6">
											<label for="pax" class="control-label">Pax<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['pax'][$pax_key]) && $_POST['pax'][$pax_key]!='' ? $_POST['pax'][$pax_key] : "");?>" name="pax[<?=$pax_key?>]" id="pax<?=$pax_key?>" placeholder="Pax" tabindex = "7" />
										</div>
										<div class="form-group col-md-6">
											<label for="price" class="control-label">Price <span class="currency_code"></span><font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['price'][$pax_key]) && $_POST['price'][$pax_key]!='' ? $_POST['price'][$pax_key] : "");?>" name="price[<?=$pax_key?>]" id="price<?=$pax_key?>" placeholder="Price" tabindex = "8" />
										</div>
										<div class="clearfix"></div>
										<?php 
											endforeach;
										else:
										$pax_key=0;
										?>
										<div class="form-group col-md-6">
											<label for="pax" class="control-label">Pax<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['pax'][$pax_key]) && $_POST['pax'][$pax_key]!='' ? $_POST['pax'][$pax_key] : "");?>" name="pax[<?=$pax_key?>]" id="pax<?=$pax_key?>" placeholder="Pax" tabindex = "7" />
										</div>
										<div class="form-group col-md-6">
											<label for="price" class="control-label">Price <span class="currency_code"></span><font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['price'][$pax_key]) && $_POST['price'][$pax_key]!='' ? $_POST['price'][$pax_key] : "");?>" name="price[<?=$pax_key?>]" id="price<?=$pax_key?>" placeholder="Price" tabindex = "8" />
										</div>
										<div class="clearfix"></div>
										<?php 
										endif;
										$new_index=$pax_key+1;
										?>
									</div>
									<div class="box-body">
										<div class="form-group col-md-12">
											<a href = "javascript:void(0);" class="add-row" data-attr_key="<?=$new_index?>"><img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>plus-icon.png" border = "0" alt = "" /></a>&nbsp;&nbsp;<b>ADD ANOTHER PRICE</b>&nbsp;&nbsp;<a href = "javascript:void(0);" class="delete-row"><img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>minus-icon.png" border = "0" alt = "" /></a>
										</div>
									</div>
									<div class="box-body">
										<div class="form-group col-md-6">
											<label for="package_images" class="control-label">Package Images<font color="#FF0000">*</font></label>
											<input type="file" class="form-control validate[required]"  value="" name="package_images[]" id="package_images" placeholder="Package Images" tabindex = "9" multiple/>
											<br/>
											<font color = "red">SELECT MULTIPLE BY HOLDING CONTROL BUTTON.</font>
										</div>
										<div class="form-group col-md-6">
											<label for="status" class="control-label">Status<font color="#FF0000">*</font></label>
											<select class="form-control validate[required]" name="status" id="status" tabindex = "10">
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
										<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex = "11">CREATE</button>
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