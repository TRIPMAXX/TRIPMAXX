<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
	$white_list_array = array('transfer_id', 'offer_title', 'offer_capacity', 'service_type', 'price_per_person', 'status', 'token', 'id', 'btn_submit', 'amenities_arr');
	$verify_token = "create_new_transfer_offer";
	$white_list_array_1 = array('offer_id', 'price_id1', 'price_id2', 'price_id3', 'price_id4', 'price_id5', 'start_date1', 'end_date1', 'price_per_person1', 'start_date2', 'end_date2', 'price_per_person2', 'start_date3', 'end_date3', 'price_per_person3', 'start_date4', 'end_date4', 'price_per_person4', 'start_date5', 'end_date5', 'price_per_person5', 'token', 'id', 'btn_submit_price');
	$verify_token_1 = "edit_offer_price";
	$white_list_array_2 = array('country_id', 'nationality', 'addon_price', 'token', 'id', 'btn_submit_price');
	$verify_token_2 = "edit_offer_addon_price";
	$white_list_array_3 = array('offer_id', 'agent_id_arr', 'agent_markup', 'agent_markup_id_arr', 'token', 'id', 'btn_submit_markup');
	$verify_token_3 = "edit_agent_markup";
	if(isset($_GET['transfer_id']) && $_GET['transfer_id']!=""):
		$autentication_data=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.TRANSFER_API_PATH."authorized.php"));
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
				curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.TRANSFER_API_PATH."country/read.php");
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
				$post_data['data']=$_GET;
				$post_data_str=json_encode($post_data);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.TRANSFER_API_PATH."transfer/read.php");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				$return_data = curl_exec($ch);
				curl_close($ch);
				$return_data_arr=json_decode($return_data, true);
				$transfer_data=array();
				if(!isset($return_data_arr['status'])):
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH']="Some error has been occure during execution.";
					header("location:transfers");
					exit;
				elseif($return_data_arr['status']=="success"):
					$transfer_data=$return_data_arr['results'];
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_HEADER, false);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
					curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.TRANSFER_API_PATH."offer/read.php");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					$return_data = curl_exec($ch);
					curl_close($ch);
					$return_data_arr=json_decode($return_data, true);
					$offer_data=array();
					if(!isset($return_data_arr['status'])):
						$_SESSION['SET_TYPE'] = 'error';
						$_SESSION['SET_FLASH']="Some error has been occure during execution.";
					elseif($return_data_arr['status']=="success"):
						$offer_data=$return_data_arr['results'];
						$post_data['data']=array("offer_id"=>base64_encode($offer_data['id']));
						$post_data_str=json_encode($post_data);
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
						curl_setopt($ch, CURLOPT_HEADER, false);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
						curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
						curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.TRANSFER_API_PATH."offer-price/read.php");
						curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
						$return_data = curl_exec($ch);
						curl_close($ch);
						$return_data_arr=json_decode($return_data, true);
						$offer_price_data=array();
						if(!isset($return_data_arr['status'])):
							//$_SESSION['SET_TYPE'] = 'error';
							//$_SESSION['SET_FLASH']="Some error has been occure during execution.";
						elseif($return_data_arr['status']=="success"):
							$offer_price_data=$return_data_arr['results'];
						else:
							//$_SESSION['SET_TYPE'] = 'error';
							//$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
						endif;
						$post_data_str=json_encode($post_data);
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
						curl_setopt($ch, CURLOPT_HEADER, false);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
						curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
						curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.TRANSFER_API_PATH."offer-addon-price/read.php");
						curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
						$return_data = curl_exec($ch);
						curl_close($ch);
						$return_data_arr=json_decode($return_data, true);
						$offer_addon_price_data=array();
						if(!isset($return_data_arr['status'])):
							//$_SESSION['SET_TYPE'] = 'error';
							//$_SESSION['SET_FLASH']="Some error has been occure during execution.";
						elseif($return_data_arr['status']=="success"):
							$offer_addon_price_data=$return_data_arr['results'];
						else:
							//$_SESSION['SET_TYPE'] = 'error';
							//$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
						endif;
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
						curl_setopt($ch, CURLOPT_HEADER, false);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
						curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
						curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.TRANSFER_API_PATH."offer-markup/read.php");
						curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
						$return_data = curl_exec($ch);
						curl_close($ch);
						$return_data_arr=json_decode($return_data, true);
						//print_r($return_data_arr);
						$offer_markup_data=array();
						if(!isset($return_data_arr['status'])):
							//$_SESSION['SET_TYPE'] = 'error';
							//$_SESSION['SET_FLASH']="Some error has been occure during execution.";
						elseif($return_data_arr['status']=="success"):
							$offer_markup_data=$return_data_arr['results'];
						else:
							//$_SESSION['SET_TYPE'] = 'error';
							//$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
						endif;
						if(isset($_POST['btn_submit'])) {
							$_POST['id']=$offer_data['id'];
							if(tools::verify_token($white_list_array, $_POST, $verify_token)) {
								$_POST['transfer_id']=$transfer_data['id'];
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
								curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.TRANSFER_API_PATH."offer/update.php");
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
								$return_data = curl_exec($ch);
								curl_close($ch);
								$return_data_arr=json_decode($return_data, true);
								if($return_data_arr['status']=="success")
								{
									$_SESSION['SET_TYPE'] = 'success';
									$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
									header("location:transfer_offers?transfer_id=".base64_encode($transfer_data['id']));
									exit;
								}
								else
								{
									$_SESSION['SET_TYPE'] = 'error';
									$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
								}
							};
						};
						if(isset($_POST['btn_submit_price'])) {
							$_POST['offer_id']=$offer_data['id'];
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
								curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.TRANSFER_API_PATH."offer-price/update.php");
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
								$return_data = curl_exec($ch);
								curl_close($ch);
								$return_data_arr=json_decode($return_data, true);
								if($return_data_arr['status']=="success")
								{
									$_SESSION['SET_TYPE'] = 'success';
									$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
									header("location:transfer_offers?transfer_id=".base64_encode($transfer_data['id']));
									exit;
								}
								else
								{
									$_SESSION['SET_TYPE'] = 'error';
									$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
								}
							}
						};
						if(isset($_POST['btn_submit_addon_price'])) {
							$_POST['offer_id']=$offer_data['id'];
							//if(tools::verify_token($white_list_array_2, $_POST, $verify_token_2)) {
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
								curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.TRANSFER_API_PATH."offer-addon-price/update.php");
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
								$return_data = curl_exec($ch);
								curl_close($ch);
								$return_data_arr=json_decode($return_data, true);
								if($return_data_arr['status']=="success")
								{
									$_SESSION['SET_TYPE'] = 'success';
									$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
									header("location:transfer_offers?transfer_id=".base64_encode($transfer_data['id']));
									exit;
								}
								else
								{
									$_SESSION['SET_TYPE'] = 'error';
									$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
								}
							//}
						};
						if(isset($_POST['btn_submit_markup'])):
							$_POST['offer_id']=base64_decode($_GET['offer_id']);
							if(tools::verify_token($white_list_array_3, $_POST, $verify_token_3)) {
								//$post_data_new['token']=$post_data['token'];
								$post_data['data']=$_POST;
								$post_data_str=json_encode($post_data);
								$ch = curl_init();
								curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
								curl_setopt($ch, CURLOPT_HEADER, false);
								curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
								curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
								curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.TRANSFER_API_PATH."offer-markup/update.php");
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
									header("location:transfer_offers?transfer_id=".base64_encode($transfer_data['id']));
									exit;
								}
								else
								{
									$_SESSION['SET_TYPE'] = 'error';
									$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
								}
							};
						endif;
					else:
						$_SESSION['SET_TYPE'] = 'error';
						$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
					endif;
				else:
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
					header("location:transfers");
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
		$_SESSION['SET_FLASH'] = 'Some data missing.';
		header("location:transfers");
		exit;
	endif;
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>EDIT TRANSFER OFFER</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	$(function(){
		$("#form_edit_transfer_offer, #offer_price_from").validationEngine();
		$( "#start_date1, #end_date1, #start_date2, #end_date2, #start_date3, #end_date3, #start_date4, #end_date4, #start_date5, #end_date5" ).datepicker({
			 dateFormat: 'dd/mm/yy',
		});
	});
	//-->
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
               <h1>Edit Transfer Offer For "<?php echo(isset($transfer_data['transfer_title']) && $transfer_data['transfer_title']!='' ? $transfer_data['transfer_title'] : "N/A");?>"</h1>
               <ol class="breadcrumb">
                  <li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                  <li class="active">Edit Transfer Offer</li>
               </ol>
            </section>
            <section class="content">
				<form name="form_edit_transfer_offer" id="form_edit_transfer_offer" method="POST" enctype="multipart/form-data">
					<div class="row">
						<div class="col-md-12">
							<div id="notify_msg_div"></div>
							<div class="box box-primary">
								<div class="col-md-12 row">
									<div class="box-body">
										<div class="form-group col-md-4">
											<label for="offer_title" class="control-label">Offer Title<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required, custom[onlyLetterSp]]"  value="<?php echo(isset($_POST['offer_title']) && $_POST['offer_title']!='' ? $_POST['offer_title'] : (isset($offer_data['offer_title']) && $offer_data['offer_title']!='' ? $offer_data['offer_title'] : ""));?>" name="offer_title" id="offer_title" placeholder="Offer Title" tabindex = "1" />
										</div>
										<div class="form-group col-md-4">
											<label for="offer_capacity" class="control-label">Capacity<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['offer_capacity']) && $_POST['offer_capacity']!='' ? $_POST['offer_capacity'] : (isset($offer_data['offer_capacity']) && $offer_data['offer_capacity']!='' ? $offer_data['offer_capacity'] : ""));?>" name="offer_capacity" id="offer_capacity" placeholder="Capacity" tabindex = "2" />
										</div>
										<div class="form-group col-md-4">
											<label for="service_type" class="control-label">Service Type<font color="#FF0000">*</font></label>
											<select name = "service_type" id="service_type" class="form-control form_input1 select_bg validate[required]" tabindex="3">
												<option value = "">Select Service Type</option>
												<option value = "Private" <?php echo(isset($_POST['service_type']) && $_POST['service_type']=="Private" ? 'selected="selected"' : (isset($offer_data['service_type']) && $offer_data['service_type']=="Private" ? 'selected="selected"' : ""));?>>Private</option>
												<option value = "Shared" <?php echo(isset($_POST['service_type']) && $_POST['service_type']=="Shared" ? 'selected="selected"' : (isset($offer_data['service_type']) && $offer_data['service_type']=="Shared" ? 'selected="selected"' : ""));?>>Shared</option>
											</select>
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-6">
											<label for="price_per_person" class="control-label">Default Price<font color="#FF0000">*</font></label>
											<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['price_per_person']) && $_POST['price_per_person']!='' ? $_POST['price_per_person'] : (isset($offer_data['price_per_person']) && $offer_data['price_per_person']!='' ? $offer_data['price_per_person'] : ""));?>" name="price_per_person" id="price_per_person" placeholder="Default Price" tabindex = "4" />
										</div>
										<div class="form-group col-md-6">
											<label for="status" class="control-label">Status<font color="#FF0000">*</font></label>
											<select class="form-control validate[required]" name="status" id="status" tabindex = "5">
												<option value = "1" <?php echo(isset($_POST['status']) && $_POST['status']==1 ? 'selected="selected"' : (isset($offer_data['status']) && $offer_data['status']==1 ? 'selected="selected"' : ""));?>>Active</option>
												<option value = "0" <?php echo(isset($_POST['status']) && $_POST['status']==0 ? 'selected="selected"' : (isset($offer_data['status']) && $offer_data['status']==0 ? 'selected="selected"' : ""));?>>Inactive</option>
											</select>
										</div>
									</div>
									<div class="col-md-12 row">
										<div class="box-footer">
											<input type="hidden" name="token" value="<?php echo(tools::generateFormToken($verify_token)); ?>" />
											<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex = "6">UPDATE</button>
										</div>
									</div>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
				</form>
				<form name="offer_price_from" id="offer_price_from" method="post">
					<div class="row">
						<div class="col-md-12">
							<div id="notify_msg_div"></div>
							<div class="box box-primary">
								<div class="col-md-12 row">
									<div class="box-header">
									   <h3 class="box-title">Offer Price Range</h3>
									</div>
									<div class="box-body">
										<div class="dataTables_wrapper form-inline" role="grid">
											<table class="table table-bordered table-striped dataTable">
												<thead>
													<tr role="row">
														<th>#</th>
														<th>Start Date</th>
														<th>End Date</th>
														<th>Price / Person</th>
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
																if(isset($offer_price_data[$arr_index]) && $offer_price_data[$arr_index]['id']!='')
																{
															?>
															<input type="hidden" class=""  value="<?php echo(isset($offer_price_data[$arr_index]) && $offer_price_data[$arr_index]['id']!='' ? $offer_price_data[$arr_index]['id'] : "");?>" name="price_id<?php echo $i;?>" id="price_id<?php echo $i;?>" readonly />
															<?php
																}
															?>
														</td>
														<td class=" ">
															<input type="text" class="form-control datepicker"  value="<?php echo(isset($_POST['start_date'.$i]) && $_POST['start_date'.$i]!='' ? $_POST['start_date'.$i] : (isset($offer_price_data[$arr_index]) && $offer_price_data[$arr_index]['start_date']!='' ? tools::module_date_format($offer_price_data[$arr_index]['start_date']) : ""));?>" name="start_date<?php echo $i;?>" id="start_date<?php echo $i;?>" placeholder="Start Date" readonly />
														</td>
														<td class=" ">
															<input type="text" class="form-control datepicker"  value="<?php echo(isset($_POST['end_date'.$i]) && $_POST['end_date'.$i]!='' ? $_POST['end_date'.$i] : (isset($offer_price_data[$arr_index]) && $offer_price_data[$arr_index]['end_date']!='' ? tools::module_date_format($offer_price_data[$arr_index]['end_date']) : ""));?>" name="end_date<?php echo $i;?>" id="end_date<?php echo $i;?>" placeholder="End Date" readonly />
														</td>
														<td class=" ">
															<input type="text" class="form-control "  value="<?php echo(isset($_POST['price_per_person'.$i]) && $_POST['price_per_person'.$i]!='' ? $_POST['price_per_person'.$i] : (isset($offer_price_data[$arr_index]) && $offer_price_data[$arr_index]['price_per_person']!='' ? $offer_price_data[$arr_index]['price_per_person'] : ""));?>" name="price_per_person<?php echo $i;?>" id="price_per_person<?php echo $i;?>" placeholder="Price / Person"/>
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
				<form name="offer_markup_from" id="offer_markup_from" method="post">
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
															if(isset($offer_markup_data) && !empty($offer_markup_data)):
																foreach($offer_markup_data as $markup_key=>$markup_val):
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
												<input type="hidden" name="token" value="<?php echo(tools::generateFormToken($verify_token_3)); ?>" />
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
				<form name="offer_addon_price_from" id="offer_addon_price_from" method="post">
					<div class="row">
						<div class="col-md-12">
							<div id="notify_msg_div"></div>
							<div class="box box-primary">
								<div class="col-md-12 row">
									<div class="box-header">
									   <h3 class="box-title">Nationality Wise Addon Price</h3>
									</div>
									<div class="box-body">
										<div class="dataTables_wrapper form-inline" role="grid">
											<table class="table table-bordered table-striped dataTable">
												<thead>
													<tr role="row">
														<th>#</th>
														<th>Nationality</th>
														<th>Addon Price</th>
													</tr>
												</thead>
												<tbody aria-relevant="all" aria-live="polite" role="alert">
												<?php
												//print_r($offer_addon_price_data);
												if(!empty($country_data)):
													foreach($country_data as $country_key=>$country_val):
														
														$key = array_search($country_val['id'], array_column($offer_addon_price_data, 'country_id'));
												?>
													<tr class="odd">
														<td class="  sorting_1"><?= $country_key+1;?></td>
														<td class=" "><?= $country_val['name'];?></td>
														<td class=" ">
															<?php 
																if(isset($key) && $key!==false && isset($offer_addon_price_data[$key]) && isset($offer_addon_price_data[$key]['id']) && $offer_addon_price_data[$key]['id']!="")
																{
															?>
															<input type="hidden" class=""  value="<?php echo $offer_addon_price_data[$key]['id'];?>" name="addon_price_id[<?= $country_val['id'];?>]" readonly />
															<?php
																}
															?>
															<input type="hidden" name="country_id[<?= $country_val['id'];?>]" value="<?= $country_val['id'];?>">
															<input type="hidden" name="nationality[<?= $country_val['id'];?>]" value="<?= $country_val['name'];?>">
															<input type="text" class="form-control" name="addon_price[<?= $country_val['id'];?>]" placeholder="Addon Price" value="<?php echo(isset($_POST['addon_price'][$country_key]) && $_POST['addon_price']!='' ? $_POST['addon_price'][$country_key] : (isset($key) && $key!==false && isset($offer_addon_price_data[$key]) && isset($offer_addon_price_data[$key]['addon_price']) && $offer_addon_price_data[$key]['addon_price']!="" ? $offer_addon_price_data[$key]['addon_price'] : ""));?>"/>
														</td>
													</tr>
												<?php
													endforeach;
												endif;
												?>
												</tbody>
											</table>
										</div>
										<div class="col-md-12 row">
											<div class="box-footer">
												<input type="hidden" name="token" value="<?php echo(tools::generateFormToken($verify_token_2)); ?>" />
												<button type="submit" id="btn_submit_addon_price" name="btn_submit_addon_price" class="btn btn-primary">UPDATE</button>
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