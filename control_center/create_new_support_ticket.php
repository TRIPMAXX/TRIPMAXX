<?php 
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
	$white_list_array = array('account_type', 'choose_account1', 'choose_account2', 'choose_account3', 'heading', 'description', 'priority', 'attachments', 'token', 'btn_submit');
	$verify_token = "create_new_support_ticket";
	$autentication_data=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."authorized.php"));
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
			curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."agent/read.php");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$return_data = curl_exec($ch);
			curl_close($ch);
			$return_data_arr=json_decode($return_data, true);
			$agent_list=array();
			if(!isset($return_data_arr['status'])):
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH']="Some error has been occure during execution.";
			elseif($return_data_arr['status']=="success"):
				$agent_list=$return_data_arr['results'];
			else:
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
			endif;
		else:
			$_SESSION['SET_TYPE'] = 'error';
			$_SESSION['SET_FLASH'] = $autentication_data->msg;
		endif;
	else:
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = "We are having some problem to authorize api.";
	endif;


	$hotel_autentication_data=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."authorized.php"));
	if(isset($hotel_autentication_data->status)):
		if($hotel_autentication_data->status=="success"):
			$hotel_post_data['token']=array(
				"token"=>$hotel_autentication_data->results->token,
				"token_timeout"=>$hotel_autentication_data->results->token_timeout,
				"token_generation_time"=>$hotel_autentication_data->results->token_generation_time
			);
			$hotel_post_data_str=json_encode($hotel_post_data);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."hotel/read.php");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $hotel_post_data_str);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$hotel_return_data = curl_exec($ch);
			curl_close($ch);
			$hotel_return_data_arr=json_decode($hotel_return_data, true);
			//print_r($hotel_return_data_arr);
			$hotel_data=array();
			if(!isset($hotel_return_data_arr['status'])):
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH']="Some error has been occure during execution.";
			elseif($hotel_return_data_arr['status']=="success"):
				$hotel_data=$hotel_return_data_arr['results'];
			else:
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH'] = $hotel_return_data_arr['msg'];
			endif;
		else:
			$_SESSION['SET_TYPE'] = 'error';
			$_SESSION['SET_FLASH'] = $hotel_autentication_data->msg;
		endif;
	else:
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = "We are having some problem to authorize api.";
	endif;
	$supplier_autentication_data=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.SUPPLIER_API_PATH."authorized.php"));
	if(isset($supplier_autentication_data->status)):
		if($supplier_autentication_data->status=="success"):
			$supplier_post_data['token']=array(
				"token"=>$supplier_autentication_data->results->token,
				"token_timeout"=>$supplier_autentication_data->results->token_timeout,
				"token_generation_time"=>$supplier_autentication_data->results->token_generation_time
			);
			$supplier_post_data_str=json_encode($supplier_post_data);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.SUPPLIER_API_PATH."supplier/read.php");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $supplier_post_data_str);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$supplier_return_data = curl_exec($ch);
			curl_close($ch);
			$supplier_return_data_arr=json_decode($supplier_return_data, true);
			$supplier_data=array();
			if(!isset($supplier_return_data_arr['status'])):
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH']="Some error has been occure during execution.";
			elseif($supplier_return_data_arr['status']=="success"):
				$supplier_data=$supplier_return_data_arr['results'];
			else:
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH'] = $supplier_return_data_arr['msg'];
			endif;
		else:
			$_SESSION['SET_TYPE'] = 'error';
			$_SESSION['SET_FLASH'] = $supplier_autentication_data->msg;
		endif;
	else:
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = "We are having some problem to authorize api.";
	endif;


	if(isset($_POST['btn_submit'])):
		if(tools::verify_token($white_list_array, $_POST, $verify_token)) {
			$uploaded_file_json_data='';
			$_POST['attachments']="";
			$_POST['ticket_id']="TM-".tools::create_password(8);
			if(tools::module_data_exists_check("heading= '".tools::stripcleantohtml($_POST['heading'])."'", '', TM_SUPPORT_TICKETS)) {
				$_SESSION['SET_TYPE']="error";
				$_SESSION['SET_FLASH'] = 'This Support ticket heading already exists.';		
			} else {
				if(is_array($_FILES["attachments"]['name'])):
					foreach($_FILES["attachments"]['name'] as $file_key => $file_val):
						if($file_val!='') {
							$position_of_dot = strrpos($file_val,'.');
							$extension = substr($file_val, $position_of_dot+1);
							$validation_array = array('exc', 'dmf', '.zip', 'tar.gz', 'rar');
							if(!in_array($extension, $validation_array)) {
								$flag_check = "VALID";
							} else {
								$flag_check = "INVALID";
								return $flag_check;
							}

							if($flag_check == "VALID") {
								$random_number = tools::create_password(5);
								$file_name = str_replace(" ",'',$random_number."_".$file_val);
								move_uploaded_file($_FILES["attachments"]['tmp_name'][$file_key], SUPPORT_TICKET_IMAGE.$file_name);
								$_POST['attachments'].=($_POST['attachments']!="" ? "," : "").$file_name;
							}
						}
					endforeach;
				endif;
				for($i=1;$i<=3;$i++){
					if(isset($_POST['choose_account'.$i]) && $_POST['choose_account'.$i]!="")
					{
						$post_val=explode("#||#",$_POST['choose_account'.$i]);
						$_POST['account_name']=$post_val[0];
						$_POST['account_email']=$post_val[1];
						$_POST['account_phone']=$post_val[2];
					}
				}
				//print_r($_POST);exit;
				if($save_hotel = tools::module_form_submission($uploaded_file_json_data, TM_SUPPORT_TICKETS)) {
					$_SESSION['SET_TYPE']="success";
					$_SESSION['SET_FLASH'] = 'Support ticket has been created successfully.';
					header("location:support_tickets");
					exit;
				} else {
					$_SESSION['SET_TYPE']="error";
					$_SESSION['SET_FLASH'] = 'We are having some probem. Please try again later.';
				}
			};
		};
	endif;
?>
<!DOCTYPE html>
<html>
  <head>
    <title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>CREATE NEW SUPPORT TICKET</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	$(function(){
		$("#form_new_support_ticket").validationEngine();
		// HIDE & SHOW
		$("#account_type").change(function(){
			var value= $(this).val();
			if(value=="A")
			{
				$('.hotel').hide();
				$('.supplier').hide();
				$('.agent').show();
			}
			else if(value=="H")
			{
				$('.agent').hide();
				$('.supplier').hide();
				$('.hotel').show();
			}
			else if(value=="S")
			{
				$('.agent').hide();
				$('.hotel').hide();
				$('.supplier').show();
			}
		});
		// HIDE & SHOW
	});
	</script>
	<!-- JAVASCRIPT CODE -->
	<!-- CSS CODE -->
	<style type="text/css">
		.hotel,.supplier{
			display:none;
		}
	</style>
	<!-- CSS CODE -->
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
				<h1>Create New Support Ticket</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Create New Support Ticket</li>
				</ol>
			</section>
            <section class="content">
				<form name="form_new_support_ticket" id="form_new_support_ticket" method="POST" enctype="multipart/form-data">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
							<div class="col-md-12 row">
								<div class="box-body">
									<div id="" class="row rows">
										<div class="form-group col-md-6 radio_pad">
											<label for="account_type" class="form-label1">Account Type<font color="#FF0000">*</font> :</label>
											<select name="account_type" id="account_type" class="form-control form_input1 select_bg validate[required]" tabindex="1">
												<option value="A" <?php echo(isset($_POST['account_type']) && $_POST['account_type']=="A" ? 'selected="selected"' : "");?>>Agent</option>
												<option value="H" <?php echo(isset($_POST['account_type']) && $_POST['account_type']=="H" ? 'selected="selected"' : "");?>>Hotel</option>
												<option value="S" <?php echo(isset($_POST['account_type']) && $_POST['account_type']=="S" ? 'selected="selected"' : "");?>>Supplier</option>
											</select>
										</div>
										<div class="form-group col-md-6 agent">
											<label for="choose_account" class="form-label1">Choose Account<font color="#FF0000">*</font> :</label>
											<select name="choose_account1" id="choose_account1" class="form-control form_input1 select_bg" tabindex="2" style="width:100%;">
												<option value = "">Choose A Agent</option>
											<?php
											if(isset($agent_list) && !empty($agent_list)):
												foreach($agent_list as $agent_key=>$agent_val):
												$agent_value=$agent_val['first_name'].($agent_val['middle_name']!="" ? " ".$agent_val['middle_name'] : "")." ".$agent_val['last_name']."#||#".$agent_val['email_address']."#||#".$agent_val['telephone'];
											?>
												<option value = "<?php echo $agent_value;?>" <?php echo(isset($_POST['choose_account1']) && $_POST['choose_account1']==$agent_value ? 'selected="selected"' : '');?>><?php echo $agent_val['first_name'].($agent_val['middle_name']!="" ? " ".$agent_val['middle_name'] : "")." ".$agent_val['last_name']." - ".$agent_val['code'];?></option>
											<?php
												endforeach;
											endif;
											?>
											</select>
										</div>
										<div class="form-group col-md-6 hotel">
											<label for="choose_account" class="form-label1">Choose Account<font color="#FF0000">*</font> :</label>
											<select name="choose_account2" id="choose_account2" class="form-control form_input1 select_bg" tabindex="2" style="width:100%;">
												<option value = "">Choose A Hotel</option>
											<?php
											if(isset($hotel_data) && !empty($hotel_data)):
												foreach($hotel_data as $hotel_key => $hotel_val):
												$hotel_value=$hotel_val['hotel_name']."#||#".$hotel_val['email_address']."#||#".$hotel_val['phone_number'];
											?>
												<option value = "<?php echo $hotel_value;?>" <?php echo(isset($_POST['choose_account2']) && $_POST['choose_account2']==$hotel_value ? 'selected="selected"' : '');?>><?php echo $hotel_val['hotel_name'];?></option>
											<?php
												endforeach;
											endif;
											?>
											</select>
										</div>
										<div class="form-group col-md-6 supplier">
											<label for="choose_account" class="form-label1">Choose Account<font color="#FF0000">*</font> :</label>
											<select name="choose_account3" id="choose_account3" class="form-control form_input1 select_bg" tabindex="2" style="width:100%;">
												<option value = "">Choose A Supplier</option>
											<?php
											if(isset($supplier_data) && !empty($supplier_data)):
												foreach($supplier_data as $supplier_key=>$supplier_val):
												$supplier_value=$supplier_val['first_name']." ".$supplier_val['last_name']."#||#".$supplier_val['email_address']."#||#".$supplier_val['phone_number'];
											?>
												<option value = "<?php echo $supplier_value;?>" <?php echo(isset($_POST['choose_account']) && $_POST['choose_account']==$supplier_value ? 'selected="selected"' : '');?>><?php echo $supplier_val['first_name']." ".$supplier_val['last_name']." - ".$supplier_val['supplier_code'];?></option>
											<?php
												endforeach;
											endif;
											?>
											</select>
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-12">
											<label for="Heading" class="form-label1">Heading<font color="#FF0000">*</font> :</label>
											<input type="text" class = "form-control ckeditor  validate[required, custom[onlyLetterNumber]]"  value = "<?php echo(isset($_POST['heading']) && $_POST['heading']!="" ? $_POST['heading'] : "");?>" name = "heading" id = "heading" placeholder = "Heading" tabindex = "3" >
										</div>
										<div class="form-group col-md-12">
											<label for="Description" class="form-label1">Description<font color="#FF0000">*</font> :</label>
											<textarea class = "form-control ckeditor validate[required]" name = "description" id = "description" placeholder = "Description" tabindex = "4"><?php echo(isset($_POST['description']) && $_POST['description']!="" ? $_POST['description'] : "");?></textarea>
										</div>
										<div class="form-group col-md-6 radio_pad">
											<label for="priority" class="form-label1">Priority<font color="#FF0000">*</font> :</label>
											<select name="priority" id="priority" class="form-control form_input1 select_bg" tabindex="5">
												<option value="H" <?php echo(isset($_POST['priority']) && $_POST['priority']=="H" ? 'selected="selected"' : "");?>>High</option>
												<option value="M" <?php echo(isset($_POST['priority']) && $_POST['priority']=="M" ? 'selected="selected"' : "");?>>Medium</option>
												<option value="L" <?php echo(isset($_POST['priority']) && $_POST['priority']=="L" ? 'selected="selected"' : "");?>>Low</option>
											</select>
										</div>
										<div class="form-group col-md-6">
											<label for="Attachments" class="control-label">Attachments <font color="#FF0000">*</font> :</label>
											<input type="file" class="validate[required]"  value="" name="attachments[]" id="attachments" placeholder="Attachments" tabindex = "6" multiple/>
											<br/>
											<font color = "red">SELECT MULTIPLE BY HOLDING CONTROL BUTTON.</font>
										</div>
										<div class="clearfix"></div>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="box-footer">
							<input type="hidden" name="token" value="<?php echo(tools::generateFormToken($verify_token)); ?>" />
							<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex="7">CREATE</button>
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