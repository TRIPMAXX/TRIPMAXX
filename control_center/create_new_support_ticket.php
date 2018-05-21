<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
	$white_list_array = array('token', 'btn_submit');
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
			$agent_data=array();
			if(!isset($return_data_arr['status'])):
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH']="Some error has been occure during execution.";
			elseif($return_data_arr['status']=="success"):
				$agent_data=$return_data_arr['results'];
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
		$("#form_new_agent").validationEngine();
		// HIDE & SHOW
		$("#credit").change(function(){
			$("#pay_within_days").hide();
		});
		$("#cash").change(function(){
			$("#pay_within_days").show();
		});
		// HIDE & SHOW
	});
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
				<h1>Create New Support Ticket</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Create New Support Ticket</li>
				</ol>
			</section>
            <section class="content">
				<form name="form_new_agent" id="form_new_agent" method="POST" enctype="multipart/form-data">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
							<div class="col-md-12 row">
								<div class="box-body">
									<div id="" class="row rows">
										<div class="form-group col-md-6 radio_pad">
											<label for="account_type" class="form-label1">Account Type<font color="#FF0000">*</font> :</label>
											<select name="account_type" id="account_type" class="form-control form_input1 select_bg" tabindex="1">
												<option value="A" <?php echo(isset($_POST['account_type']) && $_POST['account_type']=="A" ? 'selected="selected"' : "");?>>Agent</option>
												<option value="S" <?php echo(isset($_POST['account_type']) && $_POST['account_type']=="H" ? 'selected="selected"' : "");?>>Hotel</option>
												<option value="S" <?php echo(isset($_POST['account_type']) && $_POST['account_type']=="S" ? 'selected="selected"' : "");?>>Supplier</option>
											</select>
										</div>
										<div class="form-group col-md-6">
											<label for="choose_account" class="form-label1">Choose Account<font color="#FF0000">*</font> :</label>
											<select name="choose_account" id="choose_account" class="form-control form_input1 select_bg" tabindex="2">
												<option value="">- Select -</option>
												<option value="Activity Supplier" <?php echo(isset($_POST['nature_of_business']) && $_POST['nature_of_business']=="Activity Supplier" ? 'selected="selected"' : "");?>>Activity Supplier</option>
												<option value="Hotel" <?php echo(isset($_POST['nature_of_business']) && $_POST['nature_of_business']=="Hotel" ? 'selected="selected"' : "");?>>Hotel</option>
												<option value="Hotel Chain" <?php echo(isset($_POST['nature_of_business']) && $_POST['nature_of_business']=="Hotel Chain" ? 'selected="selected"' : "");?>>Hotel Chain</option>
												<option value="Resturent" <?php echo(isset($_POST['nature_of_business']) && $_POST['nature_of_business']=="Resturent" ? 'selected="selected"' : "");?>>Resturent</option>
											</select>
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-12">
											<label for="Heading" class="form-label1">Heading<font color="#FF0000">*</font> :</label>
											<input type="text" class = "form-control ckeditor validate[required]"  value = "<?php echo(isset($_POST['heading']) && $_POST['heading']!="" ? $_POST['heading'] : "");?>" name = "heading" id = "heading" placeholder = "Heading" tabindex = "3" >
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