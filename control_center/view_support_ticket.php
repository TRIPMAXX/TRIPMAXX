<?php
require_once('loader.inc');
tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
if(isset($_GET['ticket_id']) && $_GET['ticket_id']!='') {
	$support_ticket_val = tools::find("first", TM_SUPPORT_TICKETS, '*', 'WHERE id=:id', array(':id'=>base64_decode($_GET['ticket_id'])));
	$support_ticket_replies = tools::find("all", TM_SUPPORT_TICKET_REPLIES, '*', 'WHERE support_ticket_id=:id', array(':id'=>base64_decode($_GET['ticket_id'])));
}
else
{
	$_SESSION['SET_TYPE'] = 'error';
	$_SESSION['SET_FLASH'] = 'Some data missing.';
	header("location:support_tickets");
	exit;
}
$white_list_array = array('massage', 'status', 'attachments', 'token', 'btn_submit');
$verify_token = "create_new_support_ticket_replies";
if(isset($_POST['btn_submit'])):
//print_r($_POST);exit;
	if(tools::verify_token($white_list_array, $_POST, $verify_token)) {
		$uploaded_file_json_data='';
		$_POST['attachments']="";
		$_POST['support_ticket_id']=$support_ticket_val['id'];
		$_POST['reply_from']="Tripmaxx";
		$_POST['reply_to']=($support_ticket_val['account_type']=="A"?"Agent":($support_ticket_val['account_type']=="H"?"Hotel":($support_ticket_val['account_type']=="S"?"Supplier":"")))." - ".$support_ticket_val['account_name']." - ".$support_ticket_val['account_email']." - ".$support_ticket_val['account_phone'];
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
		if($save_reply = tools::module_form_submission($uploaded_file_json_data, TM_SUPPORT_TICKET_REPLIES)) {
			if(isset($_POST['status']) && $_POST['status']=="C"):
				unset($_POST);
				$_POST['id']=$support_ticket_val['id'];
				$_POST['status']="C";
				$update_ticket = tools::module_form_submission($uploaded_file_json_data, TM_SUPPORT_TICKETS);
			endif;
			$_SESSION['SET_TYPE']="success";
			$_SESSION['SET_FLASH'] = 'Support ticket response has been created successfully.';
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
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>SUPPORT TICKET DETAILS</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
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
		<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'header.php');?>		
		<!-- TOP HEADER -->

		<!-- LEFT MENU -->
		<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'menu.php');?>
		<!-- LEFT MENU -->
		
		<!-- BODY -->   
		<div class="content-wrapper">
			<section class="content-header">
				<h1>Support Ticket Details</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Support Ticket Details</li>
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
			<?php if(isset($support_ticket_val['status']) && $support_ticket_val['status']!="C"):?>
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
											<label for="Attachments" class="control-label">Attachments :</label>
											<input type="file" class=""  value="" name="attachments[]" id="attachments" placeholder="Attachments" tabindex = "6" multiple/>
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
			<?php endif;?>
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
													<td class=" "><?=substr(nl2br($ticket_repy_val['massage']),0,50).(strlen($ticket_repy_val['massage'])>50?"...":"")?></td>
													<td class=" " data-title="Action">
													  <!-- Trigger the modal with a button -->
													  <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal_149<?php echo($ticket_repy_val['id']);?>" title = "View Massage"><i class="fa fa-eye fa-1x" ></i></button>

													  <!-- Modal -->
													  <div class="modal fade" id="myModal_149<?php echo($ticket_repy_val['id']);?>" role="dialog">
														<div class="modal-dialog modal-lg">
														  <div class="modal-content" style="border-radius:5px;">
															<div class="modal-header">
															  <button type="button" class="close" data-dismiss="modal">&times;</button>
															  <h4 class="modal-title">Support Ticket Response for :- <?=$support_ticket_val['ticket_id']?></h4>
															</div>
															<div class="modal-body">
																<div id="" class="row rows">
																	<div class="col-md-4">
																		<label class="form-label1">Date : &nbsp;&nbsp;&nbsp;</label>
																		<?= tools::module_date_format($ticket_repy_val['response_date'],"Y-m-d H:i:s");?>
																	</div>
																	<div class="col-md-4">
																		<label class="form-label1">To : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
																		<?=$ticket_repy_val['reply_to']?>
																	</div>
																	<div class="col-md-4">
																		<label class="form-label1">From : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
																		<?=$ticket_repy_val['reply_from']?>
																	</div>
																	<div class="clearfix"></div>
																	<div class="col-md-12">
																		<label class="form-label1">Response : </label>
																		<?=nl2br($ticket_repy_val['massage'])?>
																	</div>
																	<div class="clearfix"></div><br>
																	<div class="form-group col-md-12">
																		<label for="Attachments" class="control-label">Attachments :</label><br>
																	
																		<?php
																		if(isset($ticket_repy_val['attachments']) && $ticket_repy_val['attachments']!=""):
																			$image_arr=explode(",", $ticket_repy_val['attachments']);
																			foreach($image_arr as $img_key=>$img_val):
																				if($img_val!=""):
																		?>
																			<div style="display:inline-block;position:relative;">
																				<img src = "<?php echo(SUPPORT_TICKET_REPLY_IMAGE.$img_val);?>" border = "0" alt = "" style="width:150px;height:100px;margin:1px;background: #efefef;" onerror="this.remove;"/>
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
														</div>
													  </div>
													  <!-- Modal -->
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
		<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'footer.php');?>
		<!-- FOOTER -->

	</div>
</body>
</html>