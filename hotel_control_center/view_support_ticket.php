<?php
require_once('loader.inc');
tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_HOTEL.'login');

if(isset($_GET['ticket_id']) && $_GET['ticket_id']!='') {
	$ticket_id = base64_decode($_GET['ticket_id']);
}
else
{
	$_SESSION['SET_TYPE'] = 'error';
	$_SESSION['SET_FLASH'] = 'Some data missing.';
	header("location:support_tickets");
	exit;
}
$autentication_data=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.DMC_API_PATH."authorized.php"));
	if(isset($autentication_data->status)):
		if($autentication_data->status=="success"):
			$post_data['token']=array(
				"token"=>$autentication_data->results->token,
				"token_timeout"=>$autentication_data->results->token_timeout,
				"token_generation_time"=>$autentication_data->results->token_generation_time
			);
			$post_data['data']['email_address']=$_SESSION['SESSION_DATA_HOTEL']['email_address'];
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
			curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.DMC_API_PATH."support_tickets/read.php");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$return_data = curl_exec($ch);
			curl_close($ch);
			//print_r($return_data);exit;
			$return_data_arr=json_decode($return_data, true);
			//print_r($return_data_arr);
			$support_tickets=array();
			if(!isset($return_data_arr['status'])):
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH']="Some error has been occure during execution.";
			elseif($return_data_arr['status']=="success"):
				$support_tickets=$return_data_arr['results'];
			else:
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
			endif;
		endif;
	endif;
$white_list_array = array('massage', 'status', 'attachments', 'token', 'btn_submit');
$verify_token = "create_new_support_ticket_replies";
if(isset($_POST['btn_submit'])):
//print_r($_POST);exit;
	if(tools::verify_token($white_list_array, $_POST, $verify_token)) {
		$uploaded_file_json_data='';
		$_POST['attachments']="";
		$_POST['support_ticket_id']=$support_ticket_val['id'];
		$_POST['reply_from']="";
		$_POST['reply_to']="";
		if(is_array($_FILES["attachments"]['name'])){
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
						move_uploaded_file($_FILES["attachments"]['tmp_name'][$file_key], SUPPORT_TICKET_REPLY_IMAGE.$file_name);
						$_POST['attachments'].=($_POST['attachments']!="" ? "," : "").$file_name;
					}
				}
			endforeach;
		}
		//print_r($_POST);exit;
		if($save_hotel = tools::module_form_submission($uploaded_file_json_data, TM_SUPPORT_TICKET_REPLIES)) {
			$_SESSION['SET_TYPE']="success";
			$_SESSION['SET_FLASH'] = 'Support ticket has been created successfully.';
			header("location:view_support_ticket?ticket_id=".base64_encode($support_ticket_val['id']));
			exit;
		} else {
			$_SESSION['SET_TYPE']="error";
			$_SESSION['SET_FLASH'] = 'We are having some probem. Please try again later.';
		};
	};
endif;

?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>VIEW SUPPORT TICKETS</title>
	<?php require_once(HOTEL_CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	$(document).ready(function() {
		$("#form_new_support_ticket_reply").validationEngine();
		$('#example').DataTable();
	} );
	</script>
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
				<h1>View Support Ticket</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo(DOMAIN_NAME_PATH_HOTEL);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">View Support Ticket</li>
				</ol>
			</section>
            <section class="content">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
							<div class="col-md-12 row">
								<div class="box-body">
									<div id="" class="row rows">
										<div class="col-md-3">
											<label for="account_type" class="form-label1">Ticket No :</label>
										</div>
										<div class="col-md-3">
											<?=$support_ticket_val['ticket_id']?>
										</div>
										<div class="col-md-3">
											<label for="account_type" class="form-label1">Priority :</label>
										</div>
										<div class="col-md-3">
											<?=($support_ticket_val['priority']=="H"?"High":($support_ticket_val['priority']=="M"?"Medium":($support_ticket_val['priority']=="L"?"Low":"")))?>
										</div>
										<div class="clearfix"></div>
										<div class="col-md-3">
											<label for="account_type" class="form-label1">Post Date :</label>
										</div>
										<div class="col-md-3">
											<?= tools::module_date_format($support_ticket_val['creation_date'],"Y-m-d H:i:s");?>
										</div>
										<div class="col-md-3">
											<label for="account_type" class="form-label1">Account Type :</label>
										</div>
										<div class="col-md-3">
											<?=($support_ticket_val['account_type']=="A"?"Agent":($support_ticket_val['account_type']=="H"?"Hotel":($support_ticket_val['account_type']=="S"?"Supplier":"")))?>
										</div>
										<div class="clearfix"></div>
										<div class="col-md-3">
											<label for="account_type" class="form-label1">Account Name :</label>
										</div>
										<div class="col-md-3">
											<?=$support_ticket_val['account_name']?>
										</div>
										<div class="col-md-3">
											<label for="account_type" class="form-label1">Heading :</label>
										</div>
										<div class="col-md-3">
											<?=$support_ticket_val['heading']?>
										</div>
										<div class="clearfix"></div>
										<div class="col-md-3">
											<label for="account_type" class="form-label1">Description :</label>
										</div>
										<div class="col-md-9">
											<?=$support_ticket_val['description']?>
										</div>
										<div class="clearfix"></div><br>
										<div class="form-group col-md-3">
											<label for="Attachments" class="control-label">Attachments :</label>
										</div>
										<div class="col-md-9">
										
											<?php
											if(isset($support_ticket_val['attachments']) && $support_ticket_val['attachments']!=""):
												$image_arr=explode(",", $support_ticket_val['attachments']);
												foreach($image_arr as $img_key=>$img_val):
													if($img_val!=""):
											?>
												<div style="display:inline-block;position:relative;">
													<img src = "<?php echo(SUPPORT_TICKET_IMAGE.$img_val);?>" border = "0" alt = "" style="width:150px;height:100px;margin:1px;" onerror="this.remove;"/>
												</div>
											<?php
													endif;
												endforeach;
											endif;
											?>
										</div>
										<div class="clearfix"></div>
									</div>
								</div>

							</div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
			</section>
            <section class="content">
				<form name="form_new_support_ticket_reply" id="form_new_support_ticket_reply" method="POST" enctype="multipart/form-data">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
							<div class="col-md-12 row">
								<div class="box-body">
									<div id="" class="row rows">
										<div class="form-group col-md-12">
											<label for="Reply" class="form-label1">Reply<font color="#FF0000">*</font> :</label>
											<textarea class = "form-control validate[required]" name = "massage" id = "reply" placeholder = "Reply" tabindex = "4"><?php echo(isset($_POST['massage']) && $_POST['massage']!="" ? $_POST['massage'] : "");?></textarea>
										</div>
										<div class="form-group col-md-6">
											<label for="Attachments" class="control-label">Attachments <font color="#FF0000">*</font> :</label>
											<input type="file" class="validate[required]"  value="" name="attachments[]" id="attachments" placeholder="Attachments" tabindex = "6" multiple/>
											<br/>
											<font color = "red">SELECT MULTIPLE BY HOLDING CONTROL BUTTON.</font>
										</div>
										<div class="form-group col-md-6 radio_pad">
											<label for="status" class="form-label1">Status<font color="#FF0000">*</font> :</label>
											<select name="status" id="status" class="form-control form_input1 select_bg" tabindex="5">
												<option value="P" <?php echo(isset($_POST['status']) && $_POST['status']=="P" ? 'selected="selected"' : "");?>>Pending</option>
												<option value="C" <?php echo(isset($_POST['status']) && $_POST['status']=="C" ? 'selected="selected"' : "");?>>Complete</option>
											</select>
										</div>
										<div class="clearfix"></div>
									</div>
								</div>
								<div class="box-footer">
									<input type="hidden" name="token" value="<?php echo(tools::generateFormToken($verify_token)); ?>" />
									<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex="7">CREATE</button>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
				</form>
			</section>
			<section class="content">
				<div class="row">
					<section class="col-lg-12 connectedSortable">
						<div id="notify_msg_div"></div>
						<div class="box">
							<div class="box-body">
								<div id="example1_wrapper" class="dataTables_wrapper form-inline" role="grid">
									<div id="no-more-tables">
										<table aria-describedby="example1_info" id="example" class="table table-bordered table-striped dataTable">
											<thead>
												<tr role="row">
													<th>#</th>
													<th>Date</th>
													<th>From</th>
													<th>To</th>
													<th>Response</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody aria-relevant="all" aria-live="polite" role="alert">
											<?php
											if(!empty($support_ticket_replies)):
												foreach($support_ticket_replies as $r_key =>$ticket_repy_val):
											?>
												<tr class="odd">
													<td class="  sorting_1"><?=$r_key=1?></td>
													<td class=" "><?= tools::module_date_format($ticket_repy_val['response_date'],"Y-m-d H:i:s");?></td>
													<td class=" "><?=$ticket_repy_val['reply_from']?></td>
													<td class=" "><?=$ticket_repy_val['reply_to']?></td>
													<td class=" "><?=$ticket_repy_val['massage']?></td>
													<td class=" " data-title="Action">
														<a href = "<?php echo(DOMAIN_NAME_PATH_HOTEL);?>view_support_ticket?id=" title = "Edit Email Template"><i class="fa fa-eye fa-1x" ></i></a>&nbsp;&nbsp;
														<!-- <a href = "#"  title = "Delete Email Templates" onclick = "delete_email_template('<?php echo(base64_encode($email_template['id']));?>');"><i class="fa fa fa-trash-o fa-1x"></i></a> -->
													</td>
												</tr>
											<?php
												endforeach;
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
		<?php require_once(HOTEL_CONTROL_CENTER_COMMON_FILE_PATH.'footer.php');?>
		<!-- FOOTER -->

	</div>
</body>
</html>