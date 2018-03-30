<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
	if(isset($_GET['hotel_id']) && $_GET['hotel_id']!=""):
		$autentication_data=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."authorized.php"));
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
			curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."setting/currency.php");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$return_data = curl_exec($ch);
			curl_close($ch);
			$return_data_arr=json_decode($return_data, true);
			$currency_data=array();
			if(isset($return_data_arr['status']) && $return_data_arr['status']=="success"):
				$currency_data=$return_data_arr['results'];
			endif;
			$post_data['data']=$_GET;
			$post_data_str=json_encode($post_data);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."hotel/read.php");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$return_data = curl_exec($ch);
			curl_close($ch);
			$return_data_arr=json_decode($return_data, true);
			$hotel_data=array();
			if(!isset($return_data_arr['status'])):
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH']="Some error has been occure during execution.";
				header("location:hotels");
				exit;
			elseif($return_data_arr['status']=="success"):
				$hotel_data=$return_data_arr['results'];
				if(isset($_GET['room_id']) && $_GET['room_id']!=""):
					$post_data['data']=$_GET;
					$post_data_str=json_encode($post_data);
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_HEADER, false);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
					curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."room/delete.php");
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
					header("location:rooms?hotel_id=".base64_encode($hotel_data['id']));
					exit;
				endif;
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."room/read.php");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				$return_data = curl_exec($ch);
				curl_close($ch);
				$return_data_arr=json_decode($return_data, true);
				$room_data=array();
				if(!isset($return_data_arr['status'])):
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH']="Some error has been occure during execution.";
				elseif($return_data_arr['status']=="success"):
					$room_data=$return_data_arr['results'];
				else:
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
				endif;
			else:
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
				header("location:hotels");
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
	header("location:hotels");
	exit;
endif;
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>LIST(S) OF HOTEL ROOMS</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	$(document).ready(function() {
		$('#example').DataTable();
	} );
	function change_status(room_id, cur)
	{
		$.ajax({
			url:"<?= DOMAIN_NAME_PATH_ADMIN."ajax_room_status_update";?>",
			type:"post",
			data:{
				room_id:room_id,
				hotel_id:<?php echo base64_decode($_GET['hotel_id']);?>
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
				<h1>Lists Of Hotel Rooms For "<?php echo(isset($hotel_data['hotel_name']) && $hotel_data['hotel_name']!='' ? $hotel_data['hotel_name'] : "N/A");?>"</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Lists Of Hotel Rooms</li>
				</ol>
			</section>
			<section class="content">
				<div class="row">
					<section class="col-lg-12 connectedSortable">
						<div id="notify_msg_div"></div>
						<div class="box">
							<div class="box-body">
								<div id="" class="col-md-12">
									<div id="" class="row">
										<div id="" class="col-md-8"></div>
										<div id="" class="col-md-4">
											<div id="" class="row">
												<a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>create_new_room?hotel_id=<?php echo $_GET['hotel_id'];?>"><button class="status_checks btn btn-success btn-md" type="button" style="float:right; margin-bottom:10px;" value="" onclick="" >CREATE NEW ROOM</button></a>
											</div>
										</div>
									</div>
								</div>
								<div id="example1_wrapper" class="dataTables_wrapper form-inline" role="grid">
									<div id="no-more-tables">
										<table aria-describedby="example1_info" id="example" class="table table-bordered table-striped dataTable">
											<thead>
												<tr role="row">
													<th>#</th>
													<th>Room Image</th>
													<th>Room Type</th>
													<th>Default Number Of Rooms</th>
													<th>Default Price / Room</th>
													<th>Status</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody aria-relevant="all" aria-live="polite" role="alert">
											<?php
											if(!empty($room_data)):
												foreach($room_data as $room_key=>$room_val):
											?>
												<tr class="odd">
													<td class="  sorting_1"><?= $room_key+1;?></td>
													<td class=" ">
													<?php
													if($room_val['room_images']!=""):
														$image_arr=explode(",", $room_val['room_images']);
														//if($image_arr[0]!="" && file_exists(HOTEL_IMAGE_PATH.$image_arr[0])):
													?>
														<img src = "<?php echo(ROOM_IMAGE_PATH.$image_arr[0]);?>" border = "0" alt = "" width = "250" height = "150" onerror="this.remove;"/>
													<?php
														/*else:
															echo "N/A";
														endif;*/
													else:
														echo "N/A";
													endif;
													?>
													</td>
													<td class=" "><?= $room_val['room_type'];?></td>
													<td class=" "><?= $room_val['number_of_rooms'];?></td>
													<td class=" "><?= $currency_data['currency_code'];?>&nbsp;<?= $room_val['price'];?></td>
													<td class=" ">
														<a style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" data-id="" class="status_checks <?= $room_val['status']==1 ? "btn-success" : "btn-danger";?>" onclick="change_status(<?= $room_val['id'];?>, $(this))"><?= $room_val['status']==1 ? "Active" : "Inactive";?></a>
													</td>
													<td class=" " data-title="Action">
														<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>edit_room?hotel_id=<?php echo base64_encode($hotel_data['id']);?>&room_id=<?php echo base64_encode($room_val['id']);?>" title = "Edit Room"><i class="fa fa-pencil-square-o fa-1x" ></i></a>&nbsp;&nbsp;
														<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>rooms?hotel_id=<?php echo base64_encode($hotel_data['id']);?>&room_id=<?php echo base64_encode($room_val['id']);?>"  title = "Delete Room" onclick = "confirm('Are you sure you want to delete this item?') ? '' : event.preventDefault()"><i class="fa fa fa-trash-o fa-1x"></i></a>
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