<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
	$white_list_array = array('package_id', 'booking_date', 'booking_type', 'dmc_id', 'agent_id', 'status', 'token', 'btn_submit');
	$verify_token = "create_new_package_booking";
	if(isset($_GET['package_id']) && $_GET['package_id']!=""):
		$autentication_data=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.PACKAGE_API_PATH."authorized.php"));
		$autentication_data1=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."authorized.php"));
		if(isset($autentication_data1->status)):
			if($autentication_data1->status=="success"):
				$post_data1['token']=array(
					"token"=>$autentication_data1->results->token,
					"token_timeout"=>$autentication_data1->results->token_timeout,
					"token_generation_time"=>$autentication_data1->results->token_generation_time
				);
				$post_data1['data']['status']=1;
				$post_data_str1=json_encode($post_data1);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."agent/read.php");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str1);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				$return_data1 = curl_exec($ch);
				curl_close($ch);
				//print_r($return_data1);
				$return_data_arr1=json_decode($return_data1, true);
				$agent_list=array();
				if($return_data_arr1['status']=="success"):
					$agent_list=$return_data_arr1['results'];
				//else:
				//	$_SESSION['SET_TYPE'] = 'error';
				//	$_SESSION['SET_FLASH'] = $return_data_arr1['msg'];
				endif;
			else:
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH'] = $autentication_data1->msg;
			endif;
		else:
			$_SESSION['SET_TYPE'] = 'error';
			$_SESSION['SET_FLASH'] = "We are having some problem to authorize api.";
		endif;
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
					if(isset($_POST['btn_submit'])) {
						if(tools::verify_token($white_list_array, $_POST, $verify_token)) {
							$_POST['package_id']=$package_data['id'];
							if(isset($_POST['booking_type']) && $_POST['booking_type']=='personal'):
								$_POST['dmc_id']=$_SESSION['SESSION_DATA']['id'];
								$_POST['agent_id']='';
							else:
								$_POST['dmc_id']='';
							endif;
							$date_obj=date_create_from_format("d/m/Y", $_POST['booking_date']);
							$_POST['booking_date']=date_format($date_obj,"Y-m-d");
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
							curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.PACKAGE_API_PATH."booking/create.php");
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
							$return_data = curl_exec($ch);
							curl_close($ch);
							$return_data_arr=json_decode($return_data, true);
							//print_r($return_data_arr);exit;
							if($return_data_arr['status']=="success")
							{
								$dmc_email_template = tools::find("first", TM_EMAIL_TEMPLATES, $value='id, template_title, template_subject, template_body, status', "WHERE id=:id AND status=:status ", array(':id'=>30, ':status'=>1));
								if(!empty($dmc_email_template)):
									$dmc_booking_details="
										<b>Package Title :</b>".$package_data['package_title']."<br>
										<b>Country :</b>".$package_data['co_name']."<br>
										<b>City :</b>".$package_data['ci_name']."<br>
										<b>No Of Days :</b>".$package_data['no_of_days']."<br>
										<b>Package Price :</b>".$package_data['package_price']."<br>
										<b>Discounted Price :</b>".$package_data['discounted_price']."<br>
										<b>Booking Date :</b>".tools::module_date_format($return_data_arr['booking_date'],"Y-m-d")."<br>";
									$dmc_mail_Body=str_replace(array("[FIRST_NAME]", "[LAST_NAME]", "[DETAILS]"), array($_SESSION['SESSION_DATA']['first_name'], $_SESSION['SESSION_DATA']['last_name'], $dmc_booking_details), $dmc_email_template['template_body']);
									@tools::Send_SMTP_Mail($_SESSION['SESSION_DATA']['email_address'], FROM_EMAIL, '', $dmc_email_template['template_subject'], $dmc_mail_Body);
								endif;
								if(isset($return_data_arr['booking_type']) && $return_data_arr['booking_type']=='agent'):	
									if(isset($autentication_data1->status)):
										if($autentication_data1->status=="success"):
											$post_data1['token']=array(
												"token"=>$autentication_data1->results->token,
												"token_timeout"=>$autentication_data1->results->token_timeout,
												"token_generation_time"=>$autentication_data1->results->token_generation_time
											);
											$post_data1['data']['agent_id']=$return_data_arr['agent_id'];
											$post_data_str1=json_encode($post_data1);
											$ch = curl_init();
											curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
											curl_setopt($ch, CURLOPT_HEADER, false);
											curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
											curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
											curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."agent/booking-agent.php");
											curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str1);
											curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
											$return_data1 = curl_exec($ch);
											curl_close($ch);
											$return_data_arr1=json_decode($return_data1, true);
											//print_r($return_data_arr1);
											if(!isset($return_data_arr1['status'])):
												//$data['status'] = 'error';
												//$data['msg']="Some error has been occure during execution.";
											elseif($return_data_arr1['status']=="success"):
												if($return_data_arr1['results']['type']=="A"):
													//print_r($return_data_arr1);
													$agent_email_template = tools::find("first", TM_EMAIL_TEMPLATES, $value='id, template_title, template_subject, template_body, status', "WHERE id=:id AND status=:status ", array(':id'=>30, ':status'=>1));
													if(!empty($agent_email_template)):
														$agent_booking_details="
															<b>Package Title :</b>".$package_data['package_title']."<br>
															<b>Country :</b>".$package_data['co_name']."<br>
															<b>City :</b>".$package_data['ci_name']."<br>
															<b>No Of Days :</b>".$package_data['no_of_days']."<br>
															<b>Package Price :</b>".$package_data['package_price']."<br>
															<b>Discounted Price :</b>".$package_data['discounted_price']."<br>
															<b>Booking Date :</b>".tools::module_date_format($return_data_arr['booking_date'],"Y-m-d")."<br>";
														$agent_mail_Body=str_replace(array("[FIRST_NAME]", "[LAST_NAME]", "[DETAILS]"), array($return_data_arr1['results']['first_name'], $return_data_arr1['results']['last_name'], $agent_booking_details), $agent_email_template['template_body']);
														@tools::Send_SMTP_Mail($return_data_arr1['results']['email_address'], FROM_EMAIL, '', $agent_email_template['template_subject'], $agent_mail_Body);
													endif;
													if(isset($return_data_arr1['result_gsm']) && !empty($return_data_arr1['result_gsm'])):
														$gsm_email_template = tools::find("first", TM_EMAIL_TEMPLATES, $value='id, template_title, template_subject, template_body, status', "WHERE id=:id AND status=:status ", array(':id'=>30, ':status'=>1));
														if(!empty($gsm_email_template)):
															$gsm_booking_details="
																<b>Package Title :</b>".$package_data['package_title']."<br>
																<b>Country :</b>".$package_data['co_name']."<br>
																<b>City :</b>".$package_data['ci_name']."<br>
																<b>No Of Days :</b>".$package_data['no_of_days']."<br>
																<b>Package Price :</b>".$package_data['package_price']."<br>
																<b>Discounted Price :</b>".$package_data['discounted_price']."<br>
																<b>Booking Date :</b>".tools::module_date_format($return_data_arr['booking_date'],"Y-m-d")."<br>";
															$gsm_mail_Body=str_replace(array("[FIRST_NAME]", "[LAST_NAME]", "[DETAILS]"), array($return_data_arr1['result_gsm']['first_name'], $return_data_arr1['result_gsm']['last_name'], $gsm_booking_details), $gsm_email_template['template_body']);
															@tools::Send_SMTP_Mail($return_data_arr1['result_gsm']['email_address'], FROM_EMAIL, '', $gsm_email_template['template_subject'], $gsm_mail_Body);
														endif;
													endif;
												elseif($return_data_arr1['results']['type']=="G"):
													$gsm_email_template = tools::find("first", TM_EMAIL_TEMPLATES, $value='id, template_title, template_subject, template_body, status', "WHERE id=:id AND status=:status ", array(':id'=>30, ':status'=>1));
													if(!empty($gsm_email_template)):
														$gsm_booking_details="
															<b>Package Title :</b>".$package_data['package_title']."<br>
															<b>Country :</b>".$package_data['co_name']."<br>
															<b>City :</b>".$package_data['ci_name']."<br>
															<b>No Of Days :</b>".$package_data['no_of_days']."<br>
															<b>Package Price :</b>".$package_data['package_price']."<br>
															<b>Discounted Price :</b>".$package_data['discounted_price']."<br>
															<b>Booking Date :</b>".tools::module_date_format($return_data_arr['booking_date'],"Y-m-d")."<br>";
														$gsm_mail_Body=str_replace(array("[FIRST_NAME]", "[LAST_NAME]", "[DETAILS]"), array($return_data_arr1['results']['first_name'], $return_data_arr1['results']['last_name'], $gsm_booking_details), $gsm_email_template['template_body']);
														@tools::Send_SMTP_Mail($return_data_arr1['results']['email_address'], FROM_EMAIL, '', $gsm_email_template['template_subject'], $gsm_mail_Body);
													endif;
												endif;
												//$data['status'] = 'success';
												//$data['msg']="Data received successfully";
												
											else:
												//$data['status'] = 'error';
												//$data['msg'] = $return_data_arr1['msg'];
											endif;
										else:
											$_SESSION['SET_TYPE'] = 'error';
											$_SESSION['SET_FLASH'] = $autentication_data1->msg;
										endif;
									else:
										$_SESSION['SET_TYPE'] = 'error';
										$_SESSION['SET_FLASH'] = "We are having some problem to authorize api.";
									endif;
								endif;
								$_SESSION['SET_TYPE'] = 'success';
								$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
								header("location:package_bookings?package_id=".base64_encode($package_data['id']));
								exit;
							}
							else
							{
								$_SESSION['SET_TYPE'] = 'error';
								$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
							}
						};
					};
				else:
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
					header("location:packages");
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
		header("location:packages");
		exit;
	endif;
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>CREATE NEW BOOKING</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	$(function(){
		$("#form_create_package_booking").validationEngine();
		$("#booking_date").datepicker({
			dateFormat: 'dd/mm/yy',
			minDate:0
		});
	});
	function manage_booking_type(val) {
		if(val == "agent") {
			document.getElementById('agent_name').disabled = false;
		}
		else
		{
			document.getElementById('agent_name').disabled = true;
			$("#agent_name").val("");
		}
	}
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
               <h1>Create New Package Booking For "<?php echo(isset($package_data['package_title']) && $package_data['package_title']!='' ? $package_data['package_title'] : "N/A");?>"</h1>
               <ol class="breadcrumb">
                  <li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                  <li class="active">Create New Package Booking</li>
               </ol>
            </section>
			<section class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="box box-primary">
							<div>
								<div class="col-md-12 row">
									<div class="box-body">
										<div id="example1_wrapper" class="dataTables_wrapper form-inline" role="grid">
											<h3>Package Details</h3>
											<div id="no-more-tables">
												<table aria-describedby="example1_info" id="example" class="table table-bordered table-striped dataTable">
													<thead>
														<tr role="row">
															<th>Package Title</th>
															<th>Country</th>
															<th>City</th>
															<th>No Of Days</th>
															<th>Package Price</th>
															<th>Discounted Price</th>
														</tr>
													</thead>
													<tbody aria-relevant="all" aria-live="polite" role="alert">
														<tr class="odd">
															<td class=" "><?= $package_data['package_title'];?></td>
															<td class=" "><?= $package_data['co_name'];?></td>
															<td class=" "><?= $package_data['ci_name'];?></td>
															<td class=" "><?= $package_data['no_of_days'];?></td>
															<td class=" "><?= $package_data['package_price'];?></td>
															<td class=" "><?= $package_data['discounted_price'];?></td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-12">
											<h3 class="control-label">Package Images</h3>
											<?php
											if(isset($package_data['package_images']) && $package_data['package_images']!=""):
												$image_arr=explode(",", $package_data['package_images']);
												foreach($image_arr as $img_key=>$img_val):
													if($img_val!=""):
											?>
												<div style="display:inline-block;position:relative;">
													<img src = "<?php echo(PACKAGE_IMAGE_PATH.$img_val);?>" border = "0" alt = "" style="width:150px;height:100px;margin:1px;" onerror="this.remove;"/>
												</div>
											<?php
													endif;
												endforeach;
											endif;
											?>
										</div>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
			</section>
			<style type="text/css">
				.box{margin-bottom:0px;}
				.content-header h3{margin:0px;}
				.style123{padding: 0px 15px 0 15px;}
			</style>
            <section class="content-header style123" >
				<h3>Create Package Booking</h3>
            </section>
            <section class="content">
				<form name="form_create_package_booking" id="form_create_package_booking" method="POST" enctype="multipart/form-data">
					<div class="row">
						<div class="col-md-12">
							<div id="notify_msg_div"></div>
							<div class="box box-primary">
								<div class="col-md-12 row">
									<div class="box-body">
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Select Booking Type<font color="#FF0000">*</font></label>
											<select name = "booking_type" id = "booking_type" class="form-control validate[required]"  tabindex = "1" onchange = "manage_booking_type(this.value);">
												<option value = "personal" <?php echo(isset($_POST['booking_type']) && $_POST['booking_type']=="personal" ? "selected='selected'" : "");?>>Personal Booking</option>
												<option value = "agent" <?php echo(isset($_POST['booking_type']) && $_POST['booking_type']=="agent" ? "selected='selected'" :"");?>>Agent Booking</option>
											</select>
										</div>
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Select Agent<font color="#FF0000">*</font></label>
											<select name = "agent_id" id = "agent_name" class="form-control validate[required]"  tabindex = "2" <?php echo(isset($_POST['agent_id']) && $_POST['agent_id']!="" ? '' : 'disabled');?>>
												<option value = "">Select Agent</option>
											<?php
											if(isset($agent_list) && !empty($agent_list)):
												foreach($agent_list as $agent_key=>$agent_val):
											?>
												<option  value = "<?php echo $agent_val['id'];?>" <?php echo(isset($_POST['agent_id']) && $_POST['agent_id']==$agent_val['id'] ? 'selected="selected"' : '');?>><?php echo $agent_val['first_name'].($agent_val['middle_name']!="" ? " ".$agent_val['middle_name'] : "")." ".$agent_val['last_name']." - ".$agent_val['code'];?></option>
											<?php
												endforeach;
											endif;
											?>
											</select>
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Booking date<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['booking_date']) && $_POST['booking_date']!='' ? date('d/m/Y',strtotime($_POST['booking_date'])) : "");?>" name="booking_date" id="booking_date" placeholder="Booking date" tabindex = "3" />
										</div>
										<div class="form-group col-md-6">
											<label for="status" class="control-label">Status<font color="#FF0000">*</font></label>
											<select class="form-control validate[required]" name="status" id="status" tabindex = "4">
												<option value = "1" <?php echo(isset($_POST['status']) && $_POST['status']==1 ? 'selected="selected"' : "");?>>Active</option>
												<option value = "0" <?php echo(isset($_POST['status']) && $_POST['status']==0 ? 'selected="selected"' : "");?>>Inactive</option>
											</select>
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="col-md-12 row">
										<div class="box-footer">
											<input type="hidden" name="token" value="<?php echo(tools::generateFormToken($verify_token)); ?>" />
											<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex = "5">CREATE</button>
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