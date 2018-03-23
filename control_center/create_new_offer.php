<?php
require_once('loader.inc');
tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
$white_list_array = array('offer_title', 'offer_description', 'offer_start_date', 'offer_end_date', 'offer_document', 'account_type', 'allowed_account', 'status', 'start_date', 'end_date', 'token', 'id', 'btn_submit');
$verify_token = "create_offer";
if(isset($_POST['btn_submit'])) {
	$_POST['status']=1;
	if($_POST['start_date']!="")
	{
		$start_date=date_create_from_format("m/d/Y", $_POST['start_date']);
		$_POST['offer_start_date']=date_format($start_date,"Y-m-d");
	}
	else
	{
		$_POST['offer_start_date']="0000-00-00";
	}
	if($_POST['end_date']!="")
	{
		$end_date=date_create_from_format("m/d/Y", $_POST['end_date']);
		$_POST['offer_end_date']=date_format($end_date,"Y-m-d");
	}
	else
	{
		$_POST['offer_end_date']="0000-00-00";
	}
	if(tools::verify_token($white_list_array, $_POST, $verify_token)) {
		$uploaded_file_json_data="";
		if(isset($_FILES) && !empty($_FILES) && $_FILES['offer_document']['name']!="")
		{
			$file_arr['form_field_name']="offer_document";
			$file_arr['form_field_name_hidden']="";
			$file_arr['file_path']=PROMO_DOC;
			$file_arr['width']="";
			$file_arr['height']="";
			$file_arr['file_type']="all";
			$uploaded_file_data['uploaded_file_data']=array($file_arr);
			$uploaded_file_json_data=json_encode($uploaded_file_data);
		}
		if(tools::module_data_exists_check("offer_title = '".tools::stripcleantohtml($_POST['offer_title'])."'", '', TM_PROMOTIONAL_OFFERS)) {
			$_SESSION['SET_TYPE'] = 'error';
			$_SESSION['SET_FLASH'] = 'This offer title already exists.';
		} else {
			if($save_promotional_offers = tools::module_form_submission($uploaded_file_json_data, TM_PROMOTIONAL_OFFERS)) {
				$_SESSION['SET_TYPE'] = 'success';
				$_SESSION['SET_FLASH'] = 'Promotional offer has been created successfully.';
				header("location:offers");
				exit;
			} else {
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH'] = 'We are having some probem. Please try again later.';
			}
		}
	} else {
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = 'Access token mismatch. Please reload the page & try again.';
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>CREATE NEW PROMOTIONAL OFFERS</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script>
	jQuery(document).ready(function(){
		jQuery("#form_create_offer").validationEngine();
	});
	$( function() {
		$("#start_date").datepicker({
			minDate:0,
			onSelect:function(selectedDate){
				$("#end_date").datepicker( "option", "minDate", selectedDate);
			}		
		});
		$("#end_date").datepicker({
			minDate:0,
			onSelect:function(selectedDate){
				$("#start_date").datepicker( "option", "maxDate", selectedDate);
			}		
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
               <h1>Create New Promotional Offers</h1>
               <ol class="breadcrumb">
                  <li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                  <li class="active">Create New Promotional Offers</li>
               </ol>
            </section>
            <section class="content">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
							<div class="box box-primary">
								<form name="form_create_offer" id="form_create_offer" method="POST" enctype="multipart/form-data">
									<div class="col-md-12 row">
										<div class="box-body">
											<div class="form-group col-md-12">
												<label for="offer_title">Offer Title <font color="#FF0000">*</font></label>
												<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['offer_title']) && $_POST['offer_title']!='' ? $_POST['offer_title'] : "");?>" name="offer_title" id="offer_title" placeholder="Offer Title" tabindex = "1" />
											</div>
											<div class="form-group col-md-12">
												<label for="offer_description">Offer Description <font color="#FF0000">*</font></label>
												<textarea class="form-control validate[required]" name="offer_description" id="offer_description" placeholder="Offer Description" tabindex = "2"><?php echo(isset($_POST['offer_description']) && $_POST['offer_description']!='' ? $_POST['offer_description'] : "");?></textarea>
											</div>
											<div class="form-group col-md-6">
												<label for="start_date" class="control-label">Start Date</label>
												<input type="text" class="form-control validate[optional]"  placeholder = "Start Date" name="start_date" id="start_date" tabindex = "3" value="<?php echo(isset($_POST['start_date']) && $_POST['start_date']!='' ? $_POST['start_date'] : "");?>"/>
											</div>
											<div class="form-group col-md-6">
												<label for="end_date" class="control-label">End Date</label>
												<input type="text" class="form-control validate[optional]"  placeholder = "End Date" name="end_date" id="end_date" tabindex = "4" value="<?php echo(isset($_POST['end_date']) && $_POST['end_date']!='' ? $_POST['end_date'] : "");?>"/>
											</div>
											<div class="form-group col-md-6">
												<label for="inputName" class="control-label">Choose Account Type</label>
												<select class="form-control validate[optional]" tabindex = "5" name="account_type" id="account_type" <?php echo(isset($_POST['account_type']) && $_POST['account_type']=='All' ? 'selected="selected"' : "");?>>
													<option value = "All">All</option>
												</select>
											</div>
											<div class="form-group col-md-6">
												<label for="inputName" class="control-label">Choose Specific User</label>
												<select class="form-control validate[optional]" tabindex = "6" name="allowed_account" id="allowed_account" <?php echo(isset($_POST['allowed_account']) && $_POST['allowed_account']=='All' ? 'selected="selected"' : "");?>>
												</select>
											</div>
											<div class="form-group col-md-12">
												<label for="offer_document" class="control-label">Upload Offer Document</label>
												<input type = "file" name = "offer_document" />
												<br/>
												<font color = "red">NOTE: Please Upload Image Or PDF OR Word Document</font>
											</div>
										</div>
									</div>
									<div class="col-md-12 row">
										<div class="box-footer">
											<input type="hidden" name="token" value="<?php echo(tools::generateFormToken($verify_token)); ?>" />
											<input type = "hidden" name = "id" id = "id" value = "" />
											<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex = "4">CREATE</button>
										</div>
									</div>
								</form>
								<div class="clearfix"></div>
							</div>
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