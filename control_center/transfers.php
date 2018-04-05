<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
	$autentication_data=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.TRANSFER_API_PATH."authorized.php"));
	if(isset($autentication_data->status)):
		if($autentication_data->status=="success"):
			$post_data['token']=array(
				"token"=>$autentication_data->results->token,
				"token_timeout"=>$autentication_data->results->token_timeout,
				"token_generation_time"=>$autentication_data->results->token_generation_time
			);
			if(isset($_GET['transfer_id']) && $_GET['transfer_id']!=""):
				$post_data['data']=$_GET;
				$post_data_str=json_encode($post_data);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.TRANSFER_API_PATH."transfer/delete.php");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				$return_data = curl_exec($ch);
				curl_close($ch);
				$return_data_arr=json_decode($return_data, true);
				if(!isset($return_data_arr['status'])):
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH']="Some error has been occure during execution.";
				elseif($return_data_arr['status']=="success"):
					$_SESSION['SET_TYPE'] = 'success';
					$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
				else:
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
				endif;
				header("location:transfers");
				exit;
			endif;
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
			elseif($return_data_arr['status']=="success"):
				$transfer_data=$return_data_arr['results'];
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
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>LIST(S) OF TRANSFERS</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	$(document).ready(function() {
		$('#example').DataTable();
	} );
	function change_status(transfer_id, cur)
	{
		$.ajax({
			url:"<?= DOMAIN_NAME_PATH_ADMIN."ajax_transfer_status_update";?>",
			type:"post",
			data:{
				transfer_id:transfer_id
			},
			beforeSend:function(){
				//cur.removeClass("btn-success").removeClass("btn-danger");
				//cur.text("");
				cur.hide();
			},
			dataType:"json",
			success:function(response){
				//console.log(response);
				cur.show();
				if(response.status=="success")
				{
					showSuccess(response.msg);
					cur.removeClass("btn-success").removeClass("btn-danger");
					if(response.results.status==1)
					{
						cur.addClass("btn-success");
						cur.text("Active");
					}
					else
					{
						cur.addClass("btn-danger");
						cur.text("Inactive");
					}
				}
				else
				{
					showError(response.msg);
				}
			},
			error:function(){
				cur.show();
				showError("We are having some problem. Please try later.");
			}
		});
	}
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
				<h1>Lists Of Transfers</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Lists Of Transfers</li>
				</ol>
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
													<th>Image</th>
													<th>Country</th>
													<th>City</th>
													<th>Transfer Title</th>
													<th>Service Type</th>
													<th>Status</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody aria-relevant="all" aria-live="polite" role="alert">
											<?php
											if(!empty($transfer_data)):
												foreach($transfer_data as $transfer_key=>$transfer_val):
											?>
												<tr class="odd">
													<td class="  sorting_1"><?= $transfer_key+1;?></td>
													<td class=" ">
													<?php
													if($transfer_val['transfer_images']!=""):
														$image_arr=explode(",", $transfer_val['transfer_images']);
														//if($image_arr[0]!="" && file_exists(TRANSFER_IMAGE_PATH.$image_arr[0])):
													?>
														<img src = "<?php echo(TRANSFER_IMAGE_PATH.$image_arr[0]);?>" border = "0" alt = "" width = "250" height = "150" onerror="this.remove;"/>
													<?php
														/*else:
															echo "N/A";
														endif;*/
													else:
														echo "N/A";
													endif;
													?>
													</td>
													<td class=" "><?= $transfer_val['co_name'];?></td>
													<td class=" "><?= $transfer_val['ci_name'];?></td>
													<td class=" "><?= $transfer_val['transfer_title'];?></td>
													<td class=" "><?= $transfer_val['transfer_service'];?></td>
													<td class=" ">
														<a style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" data-id="" class="status_checks <?= $transfer_val['status']==1 ? "btn-success" : "btn-danger";?>" onclick="change_status(<?= $transfer_val['id'];?>, $(this))"><?= $transfer_val['status']==1 ? "Active" : "Inactive";?></a>
													</td>
													<td class=" " data-title="Action">
														<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>transfer_offers?transfer_id=<?php echo base64_encode($transfer_val['id']);?>" title = "Manage Tour Offers"><i class="fa fa-home fa-1x" ></i></a>&nbsp;&nbsp;
														<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>edit_transfer?transfer_id=<?php echo base64_encode($transfer_val['id']);?>" title = "Edit Tour"><i class="fa fa-pencil-square-o fa-1x" ></i></a>&nbsp;&nbsp;
														<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>transfers?transfer_id=<?php echo base64_encode($transfer_val['id']);?>"  title = "Delete Tour" onclick = "confirm('Are you sure you want to delete this item?') ? '' : event.preventDefault()"><i class="fa fa fa-trash-o fa-1x"></i></a>
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