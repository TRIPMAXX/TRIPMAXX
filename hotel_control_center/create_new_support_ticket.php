<?php 
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA_HOTEL']['id'], DOMAIN_NAME_PATH_HOTEL.'login');
	$white_list_array = array('heading', 'description', 'priority', 'attachments', 'token', 'btn_submit');
	$verify_token = "create_new_support_ticket";	$autentication_data=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.DMC_API_PATH."authorized.php"));
	if(isset($autentication_data->status)):
		if($autentication_data->status=="success"):
			$post_data['token']=array(
				"token"=>$autentication_data->results->token,
				"token_timeout"=>$autentication_data->results->token_timeout,
				"token_generation_time"=>$autentication_data->results->token_generation_time
			);
			//print_r($_SESSION['SESSION_DATA_HOTEL']);exit;
			if(isset($_POST['btn_submit'])):
				//print_r($_POST);exit;
				if(tools::verify_token($white_list_array, $_POST, $verify_token)):
					$uploaded_file_json_data='';
					$_POST['account_type']="H";
					$_POST['account_name']=$_SESSION['SESSION_DATA_HOTEL']['hotel_name'];
					$_POST['account_email']=$_SESSION['SESSION_DATA_HOTEL']['email_address'];
					$_POST['account_phone']=$_SESSION['SESSION_DATA_HOTEL']['phone_number'];
					$_POST['ticket_id']="TM-".tools::create_password(8);
					$_POST['uploaded_files']=array();
					if(isset($_FILES["attachments"])){
						foreach($_FILES["attachments"]['name'] as $file_key=>$file_val):
							$extension = pathinfo($file_val, PATHINFO_EXTENSION);
							//$splited_name=explode(".", $file_val);
							//$extension = end($splited_name);
							$validation_array = array('exc', 'dmf', '.zip', 'tar.gz', 'rar');
							if(!in_array(strtolower($extension), $validation_array)) {
								$data = file_get_contents($_FILES["attachments"]['tmp_name'][$file_key]);
								$base64 = 'data:image/' . $extension . ';base64,' . base64_encode($data);
								array_push($_POST['uploaded_files'], curl_file_create($base64, $_FILES["attachments"]['type'][$file_key], $_FILES["attachments"]['name'][$file_key]));
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
					curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.DMC_API_PATH."support_tickets/create.php");
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					$return_data = curl_exec($ch);
					curl_close($ch);
					//print_r($return_data);exit;
					$return_data_arr=json_decode($return_data, true);
					//print_r($return_data_arr);
					if($return_data_arr['status']=="success")
					{
						$_SESSION['SET_TYPE'] = 'success';
						$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
						header("location:support_tickets");
						exit;
					}
					else
					{
						$_SESSION['SET_TYPE'] = 'error';
						$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
					}
				endif;
			endif;
		endif;
	endif;
?>
<!DOCTYPE html>
<html>
  <head>
    <title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER_HOTEL);?>CREATE NEW SUPPORT TICKET</title>
	<?php require_once(HOTEL_CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	$(function(){
		$("#form_new_support_ticket").validationEngine();
	});
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
				<h1>Create New Support Ticket</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo(DOMAIN_NAME_PATH_HOTEL);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
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
		<?php require_once(HOTEL_CONTROL_CENTER_COMMON_FILE_PATH.'footer.php');?>
		<!-- FOOTER -->

	</div>
</body>
</html>