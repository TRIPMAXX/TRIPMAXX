<?php
	require_once('loader.inc'); 
	tools::module_validation_check(@$_SESSION['SESSION_DATA_HOTEL']['id'], DOMAIN_NAME_PATH_HOTEL.'login');	
	if(isset($_SESSION['SESSION_DATA_HOTEL']['id']) && $_SESSION['SESSION_DATA_HOTEL']['id']!=""):
		$default_currency=tools::find("first", TM_SETTINGS." as s, ".TM_CURRENCIES." as c", 'c.*', "WHERE c.id=s.default_currency ", array());
		$currency_data=array();
		if(isset($default_currency) && !empty($default_currency)):
			$currency_data = $default_currency;
		endif;
		$return_data_arr = tools::find("all", TM_ROOMS, '*', "WHERE hotel_id=:hotel_id ", array(":hotel_id"=>$_SESSION['SESSION_DATA_HOTEL']['id']));		
		$room_data=array();
		if(isset($return_data_arr) && empty($return_data_arr)):
			$_SESSION['SET_TYPE'] = 'error';
			$_SESSION['SET_FLASH']="Some error has been occure during execution.";
		elseif(isset($return_data_arr) && !empty($return_data_arr)):
			$room_data=$return_data_arr;
		else:
			$_SESSION['SET_TYPE'] = 'error';
			$_SESSION['SET_FLASH'] = 'Something wrong';
		endif;
		if(isset($_GET['room_id']) && $_GET['room_id']!=''):		
			$find_room = tools::find("first", TM_ROOMS, '*', "WHERE id=:id", array(":id"=>base64_decode($_GET['room_id'])));
			//print_r($find_room); exit;
			if(!empty($find_room)):
				if(isset($find_room['room_images']) && $find_room['room_images']!=""):
					$image_arr=explode(",", $find_room['room_images']);
					foreach($image_arr as $img_key=>$img_val):
						if($img_val!="" && file_exists(ROOM_IMAGES.$img_val)):
							unlink(ROOM_IMAGES.$img_val);
						endif;
					endforeach;
				endif;
				tools::delete(TM_ROOM_PRICES, "WHERE room_id=:room_id", array(":room_id"=>$find_room['id']));
				//tools::delete(TM_ROOM_AGENT_MARKUP, "WHERE room_id=:room_id", array(":room_id"=>$find_room['id']));
				if(tools::delete(TM_ROOMS, "WHERE id=:id", array(":id"=>$find_room['id']))):
					$_SESSION['SET_TYPE'] = 'success';
					$_SESSION['SET_FLASH'] = 'Room has been deleted successfully.';		
					header("location:rooms");
					exit;
				else:
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH'] = 'We are having some probem. Please try again later.';
				endif;
			else:
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH'] = 'Invalid room id.';
			endif;
		endif;
	else:
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = 'Some data missing.';
		header("location:rooms");
		exit;
	endif;
	
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER_HOTEL);?>LIST(S) OF HOTEL ROOMS</title>
	<?php require_once(HOTEL_CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	$(document).ready(function() {
		$('#example').DataTable();
	} );
	function change_status(room_id, cur)
	{ 
		$.ajax({
			url:"<?= DOMAIN_NAME_PATH_HOTEL."ajax_room_status_update";?>",
			type:"post",
			data:{
				room_id:room_id
			},
			beforeSend:function(){
				
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
		<?php require_once(HOTEL_CONTROL_CENTER_COMMON_FILE_PATH.'header.php');?>		
		<!-- TOP HEADER -->

		<!-- LEFT MENU -->
		<?php require_once(HOTEL_CONTROL_CENTER_COMMON_FILE_PATH.'menu.php');?>
		<!-- LEFT MENU -->
		
		<!-- BODY -->
		<div class="content-wrapper">
			<section class="content-header">
				<h1>Lists Of Hotel Rooms For "<?php echo(isset($_SESSION['SESSION_DATA_HOTEL']['hotel_name']) && $_SESSION['SESSION_DATA_HOTEL']['hotel_name']!='' ? $_SESSION['SESSION_DATA_HOTEL']['hotel_name'] : "N/A");?>"</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo(DOMAIN_NAME_PATH_HOTEL);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
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
														<a href = "<?php echo(DOMAIN_NAME_PATH_HOTEL);?>edit_room?room_id=<?php echo base64_encode($room_val['id']);?>" title = "Edit Room"><i class="fa fa-pencil-square-o fa-1x" ></i></a>&nbsp;&nbsp;
														<a href = "<?php echo(DOMAIN_NAME_PATH_HOTEL);?>rooms?room_id=<?php echo base64_encode($room_val['id']);?>"  title = "Delete Room" onclick = "confirm('Are you sure you want to delete this item?') ? '' : event.preventDefault()"><i class="fa fa fa-trash-o fa-1x"></i></a>
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
		<?php require_once(HOTEL_CONTROL_CENTER_COMMON_FILE_PATH.'footer.php');?>
		<!-- FOOTER -->
	</div>
</body>
</html>