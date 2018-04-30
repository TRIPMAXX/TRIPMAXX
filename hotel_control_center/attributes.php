<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA_HOTEL']['id'], DOMAIN_NAME_PATH_HOTEL.'login');
	$attribute_data = tools::find("all", TM_ATTRIBUTES, '*', "WHERE :all ", array(":all"=>1));
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER_HOTEL);?>LISTS OF HOTEL ATTRIBUTES</title>
	<?php require_once(HOTEL_CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	$(document).ready(function() {
		$('#example').DataTable();
	} );
	function change_status(attribute_id, cur)
	{
		$.ajax({
			url:"<?= DOMAIN_NAME_PATH_HOTEL."ajax_attribute_status_update";?>",
			type:"post",
			data:{
				attribute_id:attribute_id
			},
			beforeSend:function(){
				//cur.removeClass("btn-success").removeClass("btn-danger");
				//cur.text("");
				cur.hide();
			},
			dataType:"json",
			success:function(response){
				console.log(response);
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
	//-->
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
		
		<div class="content-wrapper">
			<section class="content-header">
			<h1>Lists Of Hotel Attributes</h1>
			<ol class="breadcrumb">
				<li><a href="<?php echo(DOMAIN_NAME_PATH_HOTEL);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">Lists Of Hotel Attributes </li>
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
												<th>Attribute Title</th>
												<th>Type</th>
												<th>Status</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody aria-relevant="all" aria-live="polite" role="alert">
										<?php
										if(!empty($attribute_data)):
											foreach($attribute_data as $attribute_key=>$attribute_val):
										?>
											<tr class="odd">
												<td class="  sorting_1"><?= $attribute_key+1;?></td>
												<td class=" "><?= $attribute_val['attribute_name'];?></td>
												<td class=" "><?= $attribute_val['type'];?></td>
												<td class=" ">
													<a style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" data-id="" class="status_checks <?= $attribute_val['status']==1 ? "btn-success" : "btn-danger";?>" onclick="change_status(<?= $attribute_val['id'];?>, $(this))"><?= $attribute_val['status']==1 ? "Active" : "Inactive";?></a>
												</td>
												<td class=" " data-title="Action">
													<a href = "<?php echo(DOMAIN_NAME_PATH_HOTEL);?>edit_attribute?attribute_id=<?php echo base64_encode($attribute_val['id']);?>" title = "Edit Attribute"><i class="fa fa-pencil-square-o fa-1x" ></i></a>&nbsp;&nbsp;
													<a href = "<?php echo(DOMAIN_NAME_PATH_HOTEL);?>attributes?attribute_id=<?php echo base64_encode($attribute_val['id']);?>"  title = "Delete Attribute" onclick = "confirm('Are you sure you want to delete this item?') ? '' : event.preventDefault()"><i class="fa fa fa-trash-o fa-1x"></i></a>
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
		<!-- BODY -->

	</div>
    
	<!-- FOOTER -->
	<?php require_once(HOTEL_CONTROL_CENTER_COMMON_FILE_PATH.'footer.php');?>
	<!-- FOOTER -->

</div>
</body>
</html>