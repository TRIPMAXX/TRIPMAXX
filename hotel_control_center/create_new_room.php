<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA_HOTEL']['id'], DOMAIN_NAME_PATH_HOTEL.'login');
	$white_list_array = array('hotel_id', 'room_type', 'room_images', 'room_description', 'amenities', 'price', 'number_of_rooms', 'start_date', 'end_date', 'price_per_night', 'status', 'token', 'id', 'btn_submit', 'amenities_arr');
	$verify_token = "create_new_room";
	if(isset($_SESSION['SESSION_DATA_HOTEL']['id']) && $_SESSION['SESSION_DATA_HOTEL']['id']!=""):		
			$attribute_list = tools::find("all", TM_ATTRIBUTES, '*', "WHERE status=:status AND type=:type ".$ext_where." ORDER BY serial_number", array(":status"=>1, ":type"=>"Room"));
			$attribute_data=array();
			if(!empty($attribute_list)):
				$attribute_data=$attribute_list;			
			endif;
			
			if(isset($_POST['btn_submit'])) {
				$uploaded_file_json_data="";
				if(!isset($_POST['amenities_arr'])):
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH'] = "Please select amenities.";
				else:
					$_POST['amenities']=implode(",", $_POST['amenities_arr']);
					if(tools::verify_token($white_list_array, $_POST, $verify_token)) {
						$_POST['uploaded_files']=array();
						if(isset($_FILES["room_images"])){
							foreach($_FILES["room_images"]['name'] as $file_key=>$file_val):
								$extension = pathinfo($file_val, PATHINFO_EXTENSION);
								//$splited_name=explode(".", $file_val);
								//$extension = end($splited_name);
								$validation_array = array('jpg', 'jpeg', 'png', 'gif', 'bmp');
								if(in_array(strtolower($extension), $validation_array)) {
									$data = file_get_contents($_FILES["room_images"]['tmp_name'][$file_key]);
									$base64 = 'data:image/' . $extension . ';base64,' . base64_encode($data);
									array_push($_POST['uploaded_files'], curl_file_create($base64, $_FILES["room_images"]['type'][$file_key], $_FILES["room_images"]['name'][$file_key]));
								}
							endforeach;
						}
						$_POST['hotel_id']=$_SESSION['SESSION_DATA_HOTEL']['id'];
						
						if(tools::module_data_exists_check("room_type = '".tools::stripcleantohtml($_POST['room_type'])."' AND hotel_id='".tools::stripcleantohtml($_POST['hotel_id'])."'", '', TM_ROOMS)) 
						{
							$_SESSION['SET_TYPE']="error";
							$_SESSION['SET_FLASH'] = 'This room type already exists.';		
						}
						else 
						{ 
							$_POST['room_images']="";
							if(isset($_POST['uploaded_files']) && !empty($_POST['uploaded_files']))
							{
								foreach($_POST['uploaded_files'] as $file_key=>$file_val):									
									$random_number = tools::create_password(5);
									$extension = pathinfo($file_val->postname, PATHINFO_EXTENSION);
									$file_name = str_replace(" ", '' , $random_number."_".$file_val->postname);						
									$img = str_replace('data:image/'.$extension.';base64,', '', $file_val->name);
									$img = str_replace(' ', '+', $img); 
									$data_img_str = base64_decode($img); 
									
									file_put_contents(ROOM_IMAGES.$file_name, $data_img_str);									
									$_POST['room_images'].=($_POST['room_images']!="" ? "," : "").$file_name;
									
								endforeach;
							}
							
							if($save_hotel = tools::module_form_submission($uploaded_file_json_data, TM_ROOMS)) {
								$_SESSION['SET_TYPE']="success";
								$_SESSION['SET_FLASH'] = 'Room has been created successfully.';
								header("location:rooms");
								exit;
							} else {
								$return_data['status']="error";
								$_SESSION['SET_FLASH'] = 'We are having some probem. Please try again later.';
							}
						};
					};
				endif;
			};
	else:
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = 'Some data missing.';
		header("location:rooms");
		exit;
	endif;
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER_HOTEL);?>CREATE NEW ROOM</title>
	<?php require_once(HOTEL_CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	jQuery(document).ready(function(){
		jQuery("#form_create_room").validationEngine();
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
	$( function() {
		$( "#start_date1" ).datepicker();
		$( "#end_date1" ).datepicker();
		$( "#start_date2" ).datepicker();
		$( "#end_date2" ).datepicker();
		$( "#start_date3" ).datepicker();
		$( "#end_date3" ).datepicker();
		$( "#start_date4" ).datepicker();
		$( "#end_date4" ).datepicker();
		$( "#start_date5" ).datepicker();
		$( "#end_date5" ).datepicker();
	} );
	</script>
	<!-- JAVASCRIPT CODE -->
</head>
<body class="skin-purple">
	<div class="wrapper">
      
		<!-- TOP HEADER -->
		<?php require_once(HOTEL_CONTROL_CENTER_COMMON_FILE_PATH.'header.php');?>		
		<!-- TOP HEADER -->

		<!-- LEFT MENU -->
		<?php require_once(HOTEL_CONTROL_CENTER_COMMON_FILE_PATH.'menu.php');?>
		<!-- LEFT MENU -->  
		
		<!-- BODY -->
		<div class="content-wrapper">
            <section class="content-header">
               <h1>Create New Room For "<?php echo(isset($_SESSION['SESSION_DATA_HOTEL']['hotel_name']) && $_SESSION['SESSION_DATA_HOTEL']['hotel_name']!='' ? $_SESSION['SESSION_DATA_HOTEL']['hotel_name'] : "N/A");?>"</h1>
               <ol class="breadcrumb">
                  <li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                  <li class="active">Create New Room</li>
               </ol>
            </section>
            <section class="content">
				<form name="profile" name="form_create_room" id="form_create_room" method="POST" enctype="multipart/form-data">
					<div class="row">
						<div class="col-md-12">
							<div id="notify_msg_div"></div>
							<div class="box box-primary">
								<div class="col-md-12 row">
									<div class="box-body">
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Room Type<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['room_type']) && $_POST['room_type']!='' ? $_POST['room_type'] : "");?>" name="room_type" id="room_type" placeholder="Room Type" tabindex = "1" />
										</div>
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Room Images</label>
											<input type="file" class="form-control"  value="" name="room_images[]" id="room_images" placeholder="Hotel Image" tabindex = "2" multiple/>
											<br/>
											<font color = "red">SELECT MULTIPLE BY HOLDING CONTROL BUTTON.</font>
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-12">
											<label for="inputName" class="control-label">Room Description<font color="#FF0000">*</font></label>
											<textarea class="form-control ckeditor validate[required]" name="room_description" id="room_description" placeholder="Room Description" tabindex = "3"><?php echo(isset($_POST['room_description']) && $_POST['room_description']!='' ? $_POST['room_description'] : "");?></textarea>
										</div>
										<div class="form-group col-md-12">
											<label for="inputName" class="control-label">Available Facilities<font color="#FF0000">*</font></label>
											<br/>
											<?php
												if(isset($attribute_data) && !empty($attribute_data))
												{
													foreach($attribute_data as $attr_key=>$attr_val):
												?>
												<input type = "checkbox" id="maxcheck<?php echo $attr_key;?>" class=" validate[required]" name = "amenities_arr[]" value="<?= $attr_val['id'];?>" <?php echo(isset($_POST['amenities_arr']) && in_array($attr_val['id'], $_POST['amenities_arr']) ? 'checked="checked"' : "");?>>&nbsp;<?= $attr_val['attribute_name'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												<?php
													endforeach;
												}
												?>
										</div>
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Default Price<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['price']) && $_POST['price']!='' ? $_POST['price'] : "");?>" name="price" id="price" placeholder="Default Price" tabindex = "4" />
										</div>
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Default Number Of Rooms<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['number_of_rooms']) && $_POST['number_of_rooms']!='' ? $_POST['number_of_rooms'] : "");?>" name="number_of_rooms" id="number_of_rooms" placeholder="Default Number Of Rooms" tabindex = "5" />
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Status</label>
											<select class="form-control validate[optional]" name="status" id="status" tabindex = "6">
												<option value = "1" <?php echo(isset($_POST['status']) && $_POST['status']==1 ? 'selected="selected"' : "");?>>Active</option>
												<option value = "0" <?php echo(isset($_POST['status']) && $_POST['status']==0 ? 'selected="selected"' : "");?>>Inactive</option>
											</select>
										</div>
									</div>
									<div class="col-md-12 row">
										<div class="box-footer">
											<input type="hidden" name="token" value="<?php echo(tools::generateFormToken($verify_token)); ?>" />
											<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex = "7">CREATE</button>
										</div>
									</div>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
				</form>
				<div class="row" style="display:none;">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
							<div class="col-md-12 row">
								<div class="box-header">
								   <h3 class="box-title">Room Price Range</h3>
								</div>
								<div class="box-body">
									<div class="dataTables_wrapper form-inline" role="grid">
										<table class="table table-bordered table-striped dataTable">
											<thead>
												<tr role="row">
													<th>#</th>
													<th>Start Date</th>
													<th>End Date</th>
													<th>Room Price / Night $</th>
												</tr>
											</thead>
											<tbody aria-relevant="all" aria-live="polite" role="alert">
												<tr class="odd">
													<td class="  sorting_1">1</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="start_date1" id="start_date1" placeholder="Start Date" tabindex = "1" readonly />
													</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="end_date1" id="end_date1" placeholder="End Date" tabindex = "1" readonly />
													</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="hotel_name" id="hotel_name" placeholder="Room Price  / Night" tabindex = "1" />
													</td>
												</tr>
												<tr class="odd">
													<td class="  sorting_1">2</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="start_date2" id="start_date2" placeholder="Start Date" tabindex = "1" readonly />
													</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="end_date2" id="end_date2" placeholder="End Date" tabindex = "1" readonly />
													</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="hotel_name" id="hotel_name" placeholder="Room Price  / Night" tabindex = "1" />
													</td>
												</tr>
												<tr class="odd">
													<td class="  sorting_1">3</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="start_date3" id="start_date3" placeholder="Start Date" tabindex = "1" readonly />
													</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="end_date3" id="end_date3" placeholder="End Date" tabindex = "1" readonly />
													</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="hotel_name" id="hotel_name" placeholder="Room Price  / Night" tabindex = "1" />
													</td>
												</tr>
												<tr class="odd">
													<td class="  sorting_1">4</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="start_date4" id="start_date4" placeholder="Start Date" tabindex = "1" readonly />
													</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="end_date4" id="end_date4" placeholder="End Date" tabindex = "1" readonly />
													</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="hotel_name" id="hotel_name" placeholder="Room Price  / Night" tabindex = "1" />
													</td>
												</tr>
												<tr class="odd">
													<td class="  sorting_1">5</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="start_date5" id="start_date5" placeholder="Start Date" tabindex = "1" readonly />
													</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="end_date5" id="end_date5" placeholder="End Date" tabindex = "1" readonly />
													</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="hotel_name" id="hotel_name" placeholder="Room Price  / Night" tabindex = "1" />
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="col-md-12 row">
										<div class="box-footer">
											<input type="hidden" name="token" value="" />
											<input type = "hidden" name = "id" id = "id" value = "" />
											<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex = "4">UPDATE</button>
										</div>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>

				<div class="row" style="display:none;">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
							<div class="col-md-12 row">
								<div class="box-header">
								   <h3 class="box-title">Agent Markup</h3>
								</div>
								<div class="box-body">
									<div class="dataTables_wrapper form-inline" role="grid">
										<table class="table table-bordered table-striped dataTable">
											<thead>
												<tr role="row">
													<th>#</th>
													<th>Agent Name</th>
													<th>Agent Code</th>
													<th>Agent Markup (%)</th>
												</tr>
											</thead>
											<tbody aria-relevant="all" aria-live="polite" role="alert">
												<tr class="odd">
													<td class="  sorting_1">1</td>
													<td class=" ">
														Sandeep Sing
													</td>
													<td class=" ">
														012365
													</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="hotel_name" id="hotel_name" placeholder="Markup in %" tabindex = "1" />
													</td>
												</tr>
												<tr class="odd">
													<td class="  sorting_1">1</td>
													<td class=" ">
														Ajay Dey
													</td>
													<td class=" ">
														123698
													</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="hotel_name" id="hotel_name" placeholder="Markup in %" tabindex = "1" />
													</td>
												</tr>
												<tr class="odd">
													<td class="  sorting_1">1</td>
													<td class=" ">
														Pradipta Maitra
													</td>
													<td class=" ">
														236589
													</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="hotel_name" id="hotel_name" placeholder="Markup in %" tabindex = "1" />
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="col-md-12 row">
										<div class="box-footer">
											<input type="hidden" name="token" value="" />
											<input type = "hidden" name = "id" id = "id" value = "" />
											<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex = "4">UPDATE</button>
										</div>
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
		<?php require_once(HOTEL_CONTROL_CENTER_COMMON_FILE_PATH.'footer.php');?>
		<!-- FOOTER -->

	</div>
</body>
</html>