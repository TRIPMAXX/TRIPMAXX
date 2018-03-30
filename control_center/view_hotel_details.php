<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
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
			curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."setting/currency.php");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$return_data = curl_exec($ch);
			curl_close($ch);
			$return_data_arr=json_decode($return_data, true);
			$currency_data=array();
			if(isset($return_data_arr['status']) && $return_data_arr['status']=="success"):
				$currency_data=$return_data_arr['results'];
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
				$attribute_post_data['token']=$post_data['token'];
				$attribute_post_data_str=json_encode($attribute_post_data);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."attribute/read.php");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $attribute_post_data_str);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				$return_data = curl_exec($ch);
				curl_close($ch);
				$return_data_arr=json_decode($return_data, true);
				$attribute_data=array();
				if(isset($return_data_arr['status']) && $return_data_arr['status']=="success"):
					$attribute_data=$return_data_arr['results'];
				endif;
				$room_post_data['token']=$post_data['token'];
				$room_post_data['data']=array("hotel_id"=>base64_encode($hotel_data['id']));
				$room_post_data_str=json_encode($room_post_data);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."room/read.php");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $room_post_data_str);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				$return_data = curl_exec($ch);
				curl_close($ch);
				$return_data_arr=json_decode($return_data, true);
				$room_data=array();
				if(isset($return_data_arr['status']) && $return_data_arr['status']=="success"):
					$room_data=$return_data_arr['results'];
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
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>VIEW HOTEL DETAILS</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<script src="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>assets/raty/jquery.raty.js" type="text/javascript"></script>
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
            
            <section class="content">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
							<div class="col-md-12 row">
								<div class="box-header">
								   <h3 class="box-title">Hotel Details</h3>
								</div>
								<div class="box-body">
									<div class="form-group col-md-6">
										<label for="inputName" class="control-label">Hotel Name</label>
										<br/>
										<?php echo(isset($hotel_data['hotel_name']) && $hotel_data['hotel_name']!="" ? $hotel_data['hotel_name'] : "N/A");?>
									</div>
									<div class="form-group col-md-4">
										<label for="inputName" class="control-label">Email Address</label>
										<br/>
										<?php echo(isset($hotel_data['email_address']) && $hotel_data['email_address']!="" ? $hotel_data['email_address'] : "N/A");?>
									</div>
									<div class="form-group col-md-12">
										<?php
										if(isset($hotel_data['hotel_images']) && $hotel_data['hotel_images']!=""):
											$image_arr=explode(",", $hotel_data['hotel_images']);
											foreach($image_arr as $img_key=>$img_val):
												if($img_val!=""):
										?>
												<img src = "<?php echo(HOTEL_IMAGE_PATH.$img_val);?>" border = "0" alt = "" style="width:150px;height:100px;margin:1px;" onerror="this.remove;"/>
										<?php
												endif;
											endforeach;
										endif;
										?>
									</div>
									<div class="form-group col-md-4">
										<label for="inputName" class="control-label">Hotel Address</label>
										<br/>
										<?php echo(isset($hotel_data['hotel_address']) && $hotel_data['hotel_address']!="" ? $hotel_data['hotel_address'] : "N/A");?>
									</div>
									<div class="form-group col-md-4">
										<label for="inputName" class="control-label">Country</label>
										<br/>
										<?php echo(isset($hotel_data['co_name']) && $hotel_data['co_name']!="" ? $hotel_data['co_name'] : "N/A");?>
									</div>
									<div class="form-group col-md-4">
										<label for="inputName" class="control-label">State / Region</label>
										<br/>
										<?php echo(isset($hotel_data['s_name']) && $hotel_data['s_name']!="" ? $hotel_data['s_name'] : "N/A");?>
									</div>
									<div class="form-group col-md-3">
										<label for="inputName" class="control-label">City</label>
										<br/>
										<?php echo(isset($hotel_data['ci_name']) && $hotel_data['ci_name']!="" ? $hotel_data['ci_name'] : "N/A");?>
									</div>
									<div class="form-group col-md-3">
										<label for="inputName" class="control-label">Postal Code</label>
										<br/>
										<?php echo(isset($hotel_data['postal_code']) && $hotel_data['postal_code']!="" ? $hotel_data['postal_code'] : "N/A");?>
									</div>
									<div class="form-group col-md-3">
										<label for="inputName" class="control-label">Phone Number</label>
										<br/>
										<?php echo(isset($hotel_data['phone_number']) && $hotel_data['phone_number']!="" ? $hotel_data['phone_number'] : "N/A");?>
									</div>
									<div class="form-group col-md-3">
										<label for="inputName" class="control-label">Altername Phone Number</label>
										<br/>
										<?php echo(isset($hotel_data['alternate_phone_number']) && $hotel_data['alternate_phone_number']!="" ? $hotel_data['alternate_phone_number'] : "N/A");?>
									</div>
									<div class="form-group col-md-12">
										<label for="inputName" class="control-label">Short Description</label>
										<br/>
										<?php echo(isset($hotel_data['short_description']) && $hotel_data['short_description']!="" ? nl2br($hotel_data['short_description']) : "N/A");?>
									</div>
									<div class="form-group col-md-12">
										<label for="inputName" class="control-label">Long Description</label>
										<br/>
										<?php echo(isset($hotel_data['long_description']) && $hotel_data['long_description']!="" ? $hotel_data['long_description'] : "N/A");?>
									</div>
									<div class="form-group col-md-4">
										<label for="inputName" class="control-label">Checkin Time (24 hour format)</label>
										<br/>
										<?php echo(isset($hotel_data['checkin_time']) && $hotel_data['checkin_time']!="" ? $hotel_data['checkin_time'] : "N/A");?>
									</div>
									<div class="form-group col-md-4">
										<label for="inputName" class="control-label">Checkout Time (24 hour format)</label>
										<br/>
										<?php echo(isset($hotel_data['checkout_time']) && $hotel_data['checkout_time']!="" ? $hotel_data['checkout_time'] : "N/A");?>
									</div>
									<div class="form-group col-md-4">
										<label for="inputName" class="control-label">Star Rating</label>
										<br/>
										<script type="text/javascript">
										<!--
											$(function(){
												$('#raty_div').raty({ 
													readOnly: true, 
													path: '<?php echo(DOMAIN_NAME_PATH_ADMIN);?>assets/raty/images',
													score: <?php echo(isset($hotel_data['rating']) && $hotel_data['rating']!="" ? $hotel_data['rating'] : "0");?>
												});
											});
										//-->
										</script>
										<div id="raty_div" style="display:inline-block;"></div>&nbsp;&nbsp;(<?php echo(isset($hotel_data['rating']) && $hotel_data['rating']!="" ? $hotel_data['rating'] : "0");?> Star)
									</div>
									<div class="form-group col-md-4">
										<label for="inputName" class="control-label">Is Cancellation Policy Applied?</label>
										<br/>
										<?php echo(isset($hotel_data['is_cancellation_policy_applied']) && $hotel_data['is_cancellation_policy_applied']==1 ? "Yes" : "No");?>
									</div>
									<div class="form-group col-md-4">
										<label for="inputName" class="control-label">Cancellation Charge</label>
										<br/>
										<?php echo(isset($hotel_data['cancellation_charge']) && $hotel_data['cancellation_charge']!="" && $hotel_data['cancellation_charge']!="0.00" ? $currency_data['currency_code']."&nbsp;".$hotel_data['cancellation_charge'] : "N/A");?>
									</div>
									<div class="form-group col-md-4">
										<label for="inputName" class="control-label">Cancellation Allowed Days</label>
										<br/>
										<?php echo(isset($hotel_data['cancellation_allowed_days']) && $hotel_data['cancellation_allowed_days']!="" ? $hotel_data['cancellation_allowed_days'] : "N/A");?>
									</div>
									<div class="form-group col-md-12">
										<label for="inputName" class="control-label">Other Policies</label>
										<br/>
										<?php echo(isset($hotel_data['other_policy']) && $hotel_data['other_policy']!="" ? $hotel_data['other_policy'] : "N/A");?>
									</div>
									<div class="form-group col-md-12">
										<label for="inputName" class="control-label">Available Amenities</label>
										<br/>
										<?php
										$amenities_data="";
										if(isset($attribute_data) && !empty($attribute_data) && isset($hotel_data['amenities']) && $hotel_data['amenities']!=""):
											$amenities_arr=explode(",", $hotel_data['amenities']);
											//print_r($amenities_arr);
											foreach($attribute_data as $attr_key=>$attr_val):
												if(in_array($attr_val['id'], $amenities_arr, TRUE)):
													$amenities_data.=($amenities_data!="" ? ", ": "").ucwords($attr_val['attribute_name']);
												endif;
											endforeach;
										endif;
										echo $amenities_data;
										?>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
			</section>

			<section class="content">
				<div class="row">
					<section class="col-lg-12 connectedSortable">
						<div id="notify_msg_div"></div>
						<div class="box">
							<div class="box-header">
							   <h3 class="box-title">Lists Of Rooms</h3>
							</div>
							<div class="box-body">
								<div id="example1_wrapper" class="dataTables_wrapper form-inline" role="grid">
									<div id="no-more-tables">
										<table aria-describedby="example1_info" id="example" class="table table-bordered table-striped dataTable">
											<thead>
												<tr role="row">
													<th>#</th>
													<th>Room Image</th>
													<th>Room Type</th>
													<th>Number Of Rooms</th>
													<th>Price / Night ($)</th>
													<th>Facilities</th>
												</tr>
											</thead>
											<tbody aria-relevant="all" aria-live="polite" role="alert">
											<?php
											if(!empty($room_data)):
												foreach($room_data as $room_key=>$room_val):
											?>
												<tr class="odd">
													<td class="  sorting_1"><?= $room_key+1;?></td>
													<td class=" ">
													<?php
													if($room_val['room_images']!=""):
														$image_arr=explode(",", $room_val['room_images']);
														//if($image_arr[0]!="" && file_exists(HOTEL_IMAGE_PATH.$image_arr[0])):
													?>
														<img src = "<?php echo(ROOM_IMAGE_PATH.$image_arr[0]);?>" border = "0" alt = "" width = "250" height = "150" onerror="this.remove;"/>
													<?php
														/*else:
															echo "N/A";
														endif;*/
													else:
														echo "N/A";
													endif;
													?>
													</td>
													<td class=" "><?= $room_val['room_type'];?></td>
													<td class=" "><?= $room_val['number_of_rooms'];?></td>
													<td class=" "><?= $currency_data['currency_code'];?>&nbsp;<?= $room_val['price'];?></td>
													<td class=" ">
														<?php
														$facilities_data="";
														if(isset($attribute_data) && !empty($attribute_data) && isset($room_val['amenities']) && $room_val['amenities']!=""):
															$amenities_arr=explode(",", $room_val['amenities']);
															//print_r($amenities_arr);
															foreach($attribute_data as $attr_key=>$attr_val):
																if(in_array($attr_val['id'], $amenities_arr, TRUE)):
																	$facilities_data.=($facilities_data!="" ? ", ": "").ucwords($attr_val['attribute_name']);
																endif;
															endforeach;
														endif;
														echo $facilities_data;
														?>
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
								</div>
							</div>
						</div>
					</section>
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