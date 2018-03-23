<?php
require_once('loader.inc');
tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
if(isset($_GET['banner_id']) && $_GET['banner_id']!=""):
	$find_banner = tools::find("first", TM_HOME_SLIDER, '*', "WHERE id=:id ", array(":id"=>base64_decode($_GET['banner_id'])));
	if(!empty($find_banner)):
		if($find_banner['slider_image']!="" && file_exists(GENERAL_IMAGES.$find_banner['slider_image'])):
			unlink(GENERAL_IMAGES.$find_banner['slider_image']);
		endif;
		if(tools::delete(TM_HOME_SLIDER, "WHERE id=:id", array(":id"=>$find_banner['id']))):
			$_SESSION['SET_TYPE'] = 'success';
			$_SESSION['SET_FLASH'] = 'Home slider has been deleted successfully.';
		else:
			$_SESSION['SET_TYPE'] = 'error';
			$_SESSION['SET_FLASH'] = 'We are having some probem. Please try again later.';
		endif;
	else:
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = 'Invalid banner id.';
	endif;
	header("location:home_sliders");
	exit;
endif;
$home_sliders = tools::find("all", TM_HOME_SLIDER, '*', "WHERE :all ", array(":all"=>1));
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>LIST(S) OF HOME SLIDERS</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	$(document).ready(function() {
		$('#example').DataTable();
	} );
	function change_status(banner_id, cur)
	{
		$.ajax({
			url:"<?= DOMAIN_NAME_PATH_ADMIN."ajax_home_slider_status_update";?>",
			type:"post",
			data:{
				banner_id:banner_id
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
				if(response.msg=="success")
				{
					showSuccess(response.success);
					cur.removeClass("btn-success").removeClass("btn-danger");
					if(response.status==1)
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
				<h1>Lists Of Home Slider</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Lists Of Home Slider</li>
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
												<a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>create_new_slider"><button class="status_checks btn btn-success btn-md" type="submit" style="float:right; margin-bottom:10px;" value="" onclick="" >CREATE NEW HOME SLIDER</button></a>
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
													<th>Image</th>
													<th>Status</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody aria-relevant="all" aria-live="polite" role="alert">
											<?php
											if(!empty($home_sliders)):
												foreach($home_sliders as $slider_key=>$slider_val):
											?>
												<tr class="odd">
													<td class="  sorting_1"><?= $slider_key+1;?></td>
													<td class=" ">
														<?php
														if($slider_val['slider_image']!="" && file_exists(GENERAL_IMAGES.$slider_val['slider_image'])):
														?>
														<img src="<?php echo DOMAIN_NAME_PATH_ADMIN.GENERAL_IMAGES.$slider_val['slider_image'];?>" style="width:200px;"/>
														<?php
														endif;
														?>
													</td>
													<td class=" ">
														<a style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" data-id="" class="status_checks <?= $slider_val['status']==1 ? "btn-success" : "btn-danger";?>" onclick="change_status(<?= $slider_val['id'];?>, $(this))"><?= $slider_val['status']==1 ? "Active" : "Inactive";?></a>
													</td>
													<td class=" " data-title="Action">
														<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>edit_home_slider?banner_id=<?php echo base64_encode($slider_val['id']);?>" title = "Edit Home Slider"><i class="fa fa-pencil-square-o fa-1x" ></i></a>&nbsp;&nbsp;
														<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>home_sliders?banner_id=<?php echo base64_encode($slider_val['id']);?>"  title = "Delete Home Slider" onclick = "confirm('Are you sure you want to delete this item?') ? '' : event.preventDefault()"><i class="fa fa fa-trash-o fa-1x"></i></a>
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