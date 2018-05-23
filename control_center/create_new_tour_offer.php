<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
	$white_list_array = array('tour_id', 'offer_title', 'offer_capacity', 'service_type', 'price_per_person', 'status', 'token', 'id', 'btn_submit', 'amenities_arr');
	$verify_token = "create_new_tour_offer";
	if(isset($_GET['tour_id']) && $_GET['tour_id']!=""):
		$autentication_data=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.TOUR_API_PATH."authorized.php"));
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
					if(isset($_POST['btn_submit'])) {
						if(tools::verify_token($white_list_array, $_POST, $verify_token)) {
							$_POST['tour_id']=$tour_data['id'];
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
							curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.TOUR_API_PATH."offer/create.php");
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
							$return_data = curl_exec($ch);
							curl_close($ch);
							$return_data_arr=json_decode($return_data, true);
							if($return_data_arr['status']=="success")
							{
								$_SESSION['SET_TYPE'] = 'success';
								$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
								header("location:tour_offers?tour_id=".base64_encode($tour_data['id']));
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
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>CREATE NEW TOUR OFFER</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	$(function(){
		$("#form_create_tour_offer").validationEngine();
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
               <h1>Create New Tour Offer For "<?php echo(isset($tour_data['tour_title']) && $tour_data['tour_title']!='' ? $tour_data['tour_title'] : "N/A");?>"</h1>
               <ol class="breadcrumb">
                  <li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                  <li class="active">Create New Tour Offer</li>
               </ol>
            </section>
            <section class="content">
				<form name="form_create_tour_offer" id="form_create_tour_offer" method="POST" enctype="multipart/form-data">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
							<div class="col-md-12 row">
								<div class="box-body">
									<div class="form-group col-md-4">
										<label for="offer_title" class="control-label">Offer Title<font color="#FF0000">*</font></label>
										<input type="text" class="form-control validate[required, custom[onlyLetterNumber]]"  value="<?php echo(isset($_POST['offer_title']) && $_POST['offer_title']!='' ? $_POST['offer_title'] : "");?>" name="offer_title" id="offer_title" placeholder="Offer Title" tabindex = "1" />
									</div>
									<div class="form-group col-md-4">
										<label for="offer_capacity" class="control-label">Capacity<font color="#FF0000">*</font></label>
										<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['offer_capacity']) && $_POST['offer_capacity']!='' ? $_POST['offer_capacity'] : "");?>" name="offer_capacity" id="offer_capacity" placeholder="Capacity" tabindex = "2" />
									</div>
									<div class="form-group col-md-4">
										<label for="service_type" class="control-label">Service Type<font color="#FF0000">*</font></label>
										<select name = "service_type" id="service_type" class="form-control form_input1 select_bg validate[required]" tabindex="3">
											<option value = "">Select Service Type</option>
											<option value = "Private" <?php echo(isset($_POST['service_type']) && $_POST['service_type']=="Private" ? 'selected="selected"' : "");?>>Private</option>
											<option value = "Shared" <?php echo(isset($_POST['service_type']) && $_POST['service_type']=="Shared" ? 'selected="selected"' : "");?>>Shared</option>
										</select>
									</div>
									<div class="clearfix"></div>
									<div class="form-group col-md-6">
										<label for="price_per_person" class="control-label">Default Price<font color="#FF0000">*</font></label>
										<input type="text" class="form-control validate[required, custom[number]]"  value="<?php echo(isset($_POST['price_per_person']) && $_POST['price_per_person']!='' ? $_POST['price_per_person'] : "");?>" name="price_per_person" id="price_per_person" placeholder="Default Price" tabindex = "4" />
									</div>
									<div class="form-group col-md-6">
										<label for="status" class="control-label">Status<font color="#FF0000">*</font></label>
										<select class="form-control validate[required]" name="status" id="status" tabindex = "5">
											<option value = "1" <?php echo(isset($_POST['status']) && $_POST['status']==1 ? 'selected="selected"' : "");?>>Active</option>
											<option value = "0" <?php echo(isset($_POST['status']) && $_POST['status']==0 ? 'selected="selected"' : "");?>>Inactive</option>
										</select>
									</div>
								</div>
								<div class="col-md-12 row">
									<div class="box-footer">
										<input type="hidden" name="token" value="<?php echo(tools::generateFormToken($verify_token)); ?>" />
										<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex = "6">CREATE</button>
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