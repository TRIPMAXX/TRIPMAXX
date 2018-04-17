<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
	$white_list_array = array('hotel_id', 'room_type', 'room_images', 'room_description', 'amenities', 'price', 'number_of_rooms', 'start_date', 'end_date', 'price_per_night', 'status', 'token', 'id', 'btn_submit', 'amenities_arr');
	$verify_token = "edit_room";
	$white_list_array_1 = array('room_id', 'price_id1', 'price_id2', 'price_id3', 'price_id4', 'price_id5', 'start_date1', 'end_date1', 'price_per_night1', 'start_date2', 'end_date2', 'price_per_night2', 'start_date3', 'end_date3', 'price_per_night3', 'start_date4', 'end_date4', 'price_per_night4', 'start_date5', 'end_date5', 'price_per_night5', 'token', 'id', 'btn_submit_price');
	$verify_token_1 = "edit_room_price";
	$white_list_array_2 = array('room_id', 'agent_id_arr', 'agent_markup', 'agent_markup_id_arr', 'token', 'id', 'btn_submit_markup');
	$verify_token_2 = "edit_agent_markup";
	if(isset($_GET['hotel_id']) && $_GET['hotel_id']!="" && isset($_GET['room_id']) && $_GET['room_id']!=""):
		$autentication_data=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."authorized.php"));
		if(isset($autentication_data->status)):
			if($autentication_data->status=="success"):
			$post_data['token']=array(
				"token"=>$autentication_data->results->token,
				"token_timeout"=>$autentication_data->results->token_timeout,
				"token_generation_time"=>$autentication_data->results->token_generation_time
			);
			$post_data['data']['type']="Room";
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
				if(isset($_POST['btn_submit'])) {
					$_POST['id']=base64_decode($_GET['room_id']);
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
							$_POST['hotel_id']=$hotel_data['id'];
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
							curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."room/update.php");
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
							$return_data = curl_exec($ch);
							curl_close($ch);
							$return_data_arr=json_decode($return_data, true);
							if($return_data_arr['status']=="success")
							{
								$_SESSION['SET_TYPE'] = 'success';
								$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
								header("location:rooms?hotel_id=".base64_encode($hotel_data['id']));
								exit;
							}
							else
							{
								$_SESSION['SET_TYPE'] = 'error';
								$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
							}
						};
					endif;
				};
				if(isset($_POST['btn_submit_price'])) {
					$_POST['room_id']=base64_decode($_GET['room_id']);
					if(tools::verify_token($white_list_array_1, $_POST, $verify_token_1)) {
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
						curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."room-price/update.php");
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
						$return_data = curl_exec($ch);
						curl_close($ch);
						$return_data_arr=json_decode($return_data, true);
						if($return_data_arr['status']=="success")
						{
							$_SESSION['SET_TYPE'] = 'success';
							$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
							header("location:rooms?hotel_id=".base64_encode($hotel_data['id']));
							exit;
						}
						else
						{
							$_SESSION['SET_TYPE'] = 'error';
							$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
						}
					}
				}
				if(isset($_POST['btn_submit_markup'])):
					$_POST['room_id']=base64_decode($_GET['room_id']);
					if(tools::verify_token($white_list_array_2, $_POST, $verify_token_2)) {
						//$post_data_new['token']=$post_data['token'];
						$post_data['data']=$_POST;
						$post_data_str=json_encode($post_data);
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
						curl_setopt($ch, CURLOPT_HEADER, false);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
						curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
						curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."room-markup/update.php");
						curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
						$return_data = curl_exec($ch);
						curl_close($ch);
						$return_data_arr=json_decode($return_data, true);
						//print_r($return_data_arr);
						if($return_data_arr['status']=="success")
						{
							$_SESSION['SET_TYPE'] = 'success';
							$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
							header("location:rooms?hotel_id=".base64_encode($hotel_data['id']));
							exit;
						}
						else
						{
							$_SESSION['SET_TYPE'] = 'error';
							$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
						}
					};
				endif;
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."room/read.php");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				$return_data = curl_exec($ch);
				curl_close($ch);
				$return_data_arr=json_decode($return_data, true);
				$room_data=array();
				if(!isset($return_data_arr['status'])):
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH']="Some error has been occure during execution.";
				elseif($return_data_arr['status']=="success"):
					$room_data=$return_data_arr['results'];
					$post_data['data']=array("room_id"=>base64_encode($room_data['id']));
					$post_data_str=json_encode($post_data);
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_HEADER, false);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
					curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."room-price/read.php");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					$return_data = curl_exec($ch);
					curl_close($ch);
					$return_data_arr=json_decode($return_data, true);
					$room_price_data=array();
					if(!isset($return_data_arr['status'])):
						//$_SESSION['SET_TYPE'] = 'error';
						//$_SESSION['SET_FLASH']="Some error has been occure during execution.";
					elseif($return_data_arr['status']=="success"):
						$room_price_data=$return_data_arr['results'];
					else:
						//$_SESSION['SET_TYPE'] = 'error';
						//$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
					endif;
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_HEADER, false);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
					curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."room-markup/read.php");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					$return_data = curl_exec($ch);
					curl_close($ch);
					$return_data_arr=json_decode($return_data, true);
					$room_markup_data=array();
					if(!isset($return_data_arr['status'])):
						//$_SESSION['SET_TYPE'] = 'error';
						//$_SESSION['SET_FLASH']="Some error has been occure during execution.";
					elseif($return_data_arr['status']=="success"):
						$room_markup_data=$return_data_arr['results'];
					else:
						//$_SESSION['SET_TYPE'] = 'error';
						//$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
					endif;
				else:
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
				endif;
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
		$autentication_agent_data=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."authorized.php"));
		if(isset($autentication_agent_data->status)):
			if($autentication_agent_data->status=="success"):
				$post_agent_data['token']=array(
					"token"=>$autentication_agent_data->results->token,
					"token_timeout"=>$autentication_agent_data->results->token_timeout,
					"token_generation_time"=>$autentication_agent_data->results->token_generation_time
				);
				$post_agent_data['data']['status']=1;
				$post_agent_data_str=json_encode($post_agent_data);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."agent/read.php");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_agent_data_str);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				$return_data = curl_exec($ch);
				curl_close($ch);
				$return_data_arr=json_decode($return_data, true);
				$agent_data=array();
				if($return_data_arr['status']=="success"):
					$agent_data=$return_data_arr['results'];
				
				//else:
				//	$_SESSION['SET_TYPE'] = 'error';
				//	$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
				endif;
			else:
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH'] = $autentication_data->msg;
			endif;
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
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>EDIT ROOM</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	$(function(){
		$("#edit_room, #room_price_from").validationEngine();
	});
	function remove_img(cur, image_name, room_id)
	{
		if(confirm("Are you sure you want to delete this image?"))
		{
			$.ajax({
				url:"<?= DOMAIN_NAME_PATH_ADMIN."ajax_room_image_delete";?>",
				type:"post",
				data:{
					room_id:room_id,
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
	<script>

	$( function() {
		$( "#start_date1, #end_date1, #start_date2, #end_date2, #start_date3, #end_date3, #start_date4, #end_date4, #start_date5, #end_date5" ).datepicker({
			 dateFormat: 'dd/mm/yy',
		});
	} );
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
               <h1>Edit Room For "<?php echo(isset($hotel_data['hotel_name']) && $hotel_data['hotel_name']!='' ? $hotel_data['hotel_name'] : "N/A");?>"</h1>
               <ol class="breadcrumb">
                  <li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                  <li class="active">Edit Room</li>
               </ol>
            </section>
            <section class="content">
				<form name="profile" name="edit_room" id="edit_room" method="POST" enctype="multipart/form-data">
					<div class="row">
						<div class="col-md-12">
							<div id="notify_msg_div"></div>
							<div class="box box-primary">
								<div class="col-md-12 row">
									<div class="box-body">
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Room Type<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['room_type']) && $_POST['room_type']!='' ? $_POST['room_type'] : (isset($room_data['room_type']) && $room_data['room_type']!='' ? $room_data['room_type'] : ""));?>" name="room_type" id="room_type" placeholder="Room Type" tabindex = "1" />
										</div>
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Room Images</label>
											<input type="file" class="form-control"  value="" name="room_images[]" id="room_images" placeholder="Hotel Image" tabindex = "2" multiple/>
											<br/>
											<font color = "red">SELECT MULTIPLE BY HOLDING CONTROL BUTTON.</font>
										</div>
										<div class="clearfix"></div>
										<?php
										if(isset($room_data['room_images']) && $room_data['room_images']!=""):
										?>
										<div class="form-group col-md-12">
										<?php
											$image_arr=explode(",", $room_data['room_images']);
											foreach($image_arr as $img_key=>$img_val):
												if($img_val!=""):
										?>
											<div style="display:inline-block;position:relative;">
												<img src = "<?php echo(ROOM_IMAGE_PATH.$img_val);?>" border = "0" alt = "" style="width:150px;height:100px;margin:1px;" onerror="this.remove;"/>
												<img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>delete.png" border = "0" alt = "" style="width:12px;height:18px;position:absolute;top:5px;right:10px;cursor:pointer;" onclick="remove_img($(this), '<?php echo $img_val;?>', <?php echo $room_data['id'];?>)"/>
											</div>
										<?php
												endif;
											endforeach;
										?>
										</div>
										<?php
										endif;
										?>
										<div class="form-group col-md-12">
											<label for="inputName" class="control-label">Room Description<font color="#FF0000">*</font></label>
											<textarea class="form-control ckeditor validate[required]" name="room_description" id="room_description" placeholder="Room Description" tabindex = "3"><?php echo(isset($_POST['room_description']) && $_POST['room_description']!='' ? $_POST['room_description'] : (isset($room_data['room_description']) && $room_data['room_description']!='' ? $room_data['room_description'] : ""));?></textarea>
										</div>
										<div class="form-group col-md-12">
											<label for="inputName" class="control-label">Available Facilities<font color="#FF0000">*</font></label>
											<br/>
											<?php
												if(isset($attribute_data) && !empty($attribute_data))
												{
													foreach($attribute_data as $attr_key=>$attr_val):
														if(isset($room_data['amenities']) && $room_data['amenities']!="")
														{
															$amenities_arr=explode(",", $room_data['amenities']);
														}
												?>
												<input type = "checkbox" id="maxcheck<?php echo $attr_key;?>" class=" validate[required]" name = "amenities_arr[]" value="<?= $attr_val['id'];?>" <?php echo(isset($_POST['amenities_arr']) && in_array($attr_val['id'], $_POST['amenities_arr']) ? 'checked="checked"' : (isset($amenities_arr) && in_array($attr_val['id'], $amenities_arr) ? 'checked="checked"' : ""));?>>&nbsp;<?= $attr_val['attribute_name'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												<?php
													endforeach;
												}
												?>
										</div>
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Default Price<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['price']) && $_POST['price']!='' ? $_POST['price'] : (isset($room_data['price']) && $room_data['price']!='' ? $room_data['price'] : ""));?>" name="price" id="price" placeholder="Default Price" tabindex = "4" />
										</div>
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Default Number Of Rooms<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['number_of_rooms']) && $_POST['number_of_rooms']!='' ? $_POST['number_of_rooms'] : (isset($room_data['number_of_rooms']) && $room_data['number_of_rooms']!='' ? $room_data['number_of_rooms'] : ""));?>" name="number_of_rooms" id="number_of_rooms" placeholder="Default Number Of Rooms" tabindex = "5" />
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Status</label>
											<select class="form-control validate[optional]" name="status" id="status" tabindex = "6">
												<option value = "1" <?php echo(isset($_POST['status']) && $_POST['status']==1 ? 'selected="selected"' : (isset($room_data['status']) && $room_data['status']==1 ? 'selected="selected"' : ""));?>>Active</option>
												<option value = "0" <?php echo(isset($_POST['status']) && $_POST['status']==0 ? 'selected="selected"' : (isset($room_data['status']) && $room_data['status']==0 ? 'selected="selected"' : ""));?>>Inactive</option>
											</select>
										</div>
									</div>
									<div class="col-md-12 row">
										<div class="box-footer">
											<input type="hidden" name="token" value="<?php echo(tools::generateFormToken($verify_token)); ?>" />
											<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex = "7">UPDATE</button>
										</div>
									</div>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
				</form>
				<form name="room_price_from" id="room_price_from" method="post">
					<div class="row">
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
														<th>Room Price / Night</th>
													</tr>
												</thead>
												<tbody aria-relevant="all" aria-live="polite" role="alert">
												<?php
												for($i=1;$i<=5;$i++):
													$arr_index=$i-1;
												?>
													<tr class="odd">
														<td class="  sorting_1">
															<?php 
																echo $i;
																if(isset($room_price_data[$arr_index]) && $room_price_data[$arr_index]['id']!='')
																{
															?>
															<input type="hidden" class=""  value="<?php echo(isset($room_price_data[$arr_index]) && $room_price_data[$arr_index]['id']!='' ? $room_price_data[$arr_index]['id'] : "");?>" name="price_id<?php echo $i;?>" id="price_id<?php echo $i;?>" readonly />
															<?php
																}
															?>
														</td>
														<td class=" ">
															<input type="text" class="form-control datepicker"  value="<?php echo(isset($_POST['start_date'.$i]) && $_POST['start_date'.$i]!='' ? $_POST['start_date'.$i] : (isset($room_price_data[$arr_index]) && $room_price_data[$arr_index]['start_date']!='' ? tools::module_date_format($room_price_data[$arr_index]['start_date']) : ""));?>" name="start_date<?php echo $i;?>" id="start_date<?php echo $i;?>" placeholder="Start Date" readonly />
														</td>
														<td class=" ">
															<input type="text" class="form-control datepicker"  value="<?php echo(isset($_POST['end_date'.$i]) && $_POST['end_date'.$i]!='' ? $_POST['end_date'.$i] : (isset($room_price_data[$arr_index]) && $room_price_data[$arr_index]['end_date']!='' ? tools::module_date_format($room_price_data[$arr_index]['end_date']) : ""));?>" name="end_date<?php echo $i;?>" id="end_date<?php echo $i;?>" placeholder="End Date" readonly />
														</td>
														<td class=" ">
															<input type="text" class="form-control"  value="<?php echo(isset($_POST['price_per_night'.$i]) && $_POST['price_per_night'.$i]!='' ? $_POST['price_per_night'.$i] : (isset($room_price_data[$arr_index]) && $room_price_data[$arr_index]['price_per_night']!='' ? $room_price_data[$arr_index]['price_per_night'] : ""));?>" name="price_per_night<?php echo $i;?>" id="price_per_night<?php echo $i;?>" placeholder="Room Price  / Night"/>
														</td>
													</tr>
												<?php
												endfor;
												?>
												</tbody>
											</table>
										</div>
										<div class="col-md-12 row">
											<div class="box-footer">
												<input type="hidden" name="token" value="<?php echo(tools::generateFormToken($verify_token_1)); ?>" />
												<button type="submit" id="btn_submit_price" name="btn_submit_price" class="btn btn-primary">UPDATE</button>
											</div>
										</div>
									</div>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
				</form>
				<form name="room_markup_from" id="room_markup_from" method="post">
					<div class="row">
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
												<?php
												if(isset($agent_data) && !empty($agent_data)):
													foreach($agent_data as $agent_key=>$agent_val):
												?>
													<tr class="odd">
														<td class="  sorting_1"><?= $agent_key+1;?></td>
														<td class=" ">
															<?= $agent_val['first_name']." ".($agent_val['middle_name']!="" ? $agent_val['middle_name']." " : "").$agent_val['last_name'];?>
														</td>
														<td class=" ">
															<?= $agent_val['code'];?>
														</td>
														<td class=" ">
															<?php
															$flag_agent=false;$agent_markup_val="";
															if(isset($room_markup_data) && !empty($room_markup_data)):
																foreach($room_markup_data as $markup_key=>$markup_val):
																	if($agent_val['id']==$markup_val['agent_id']):
																		$flag_agent=true;
																		$agent_markup_val=$markup_val['markup_price'];
																		break;
																	endif;
																endforeach;
															endif;
															if($flag_agent==true):
															?>
															<input type="hidden" value="<?= $markup_val['id'];?>" name="agent_markup_id_arr[]" tabindex = "1" />
															<?php
															endif;
															?>
															<input type="hidden" value="<?= $agent_val['id'];?>" name="agent_id_arr[]" tabindex = "1" />
															<input type="text" class="form-control"  value="<?php echo($flag_agent==true ? $agent_markup_val : "");?>" name="agent_markup[]" placeholder="Markup in %" tabindex = "1" />
														</td>
													</tr>
												<?php
													endforeach;
												else:
												?>
													<tr align="center">
														<td colspan="100%">No record found</td>
													</tr>
												<?php
												endif;
												?>
												</tbody>
											</table>
										</div>
										<div class="col-md-12 row">
											<div class="box-footer">
												<input type="hidden" name="token" value="<?php echo(tools::generateFormToken($verify_token_2)); ?>" />
												<button type="submit" id="btn_submit_markup" name="btn_submit_markup" class="btn btn-primary">UPDATE</button>
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
		<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'footer.php');?>
		<!-- FOOTER -->

	</div>
</body>
</html>