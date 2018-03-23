<?php
require_once('loader.inc');
tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
if(isset($_GET['offer_id']) && $_GET['offer_id']!=""):
	$promotional_offer = tools::find("first", TM_PROMOTIONAL_OFFERS, '*', "WHERE id=:id ", array(":id"=>base64_decode($_GET['offer_id'])));
	if(!empty($promotional_offer)):
		if($promotional_offer['offer_document']!="" && file_exists(PROMO_DOC.$promotional_offer['offer_document'])):
			unlink(PROMO_DOC.$promotional_offer['offer_document']);
		endif;
		if(tools::delete(TM_PROMOTIONAL_OFFERS, "WHERE id=:id", array(":id"=>$promotional_offer['id']))):
			$_SESSION['SET_TYPE'] = 'success';
			$_SESSION['SET_FLASH'] = 'Promotional offer has been deleted successfully.';
		else:
			$_SESSION['SET_TYPE'] = 'error';
			$_SESSION['SET_FLASH'] = 'We are having some probem. Please try again later.';
		endif;
	else:
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = 'Invalid promotional offer id.';
	endif;
	header("location:offers");
	exit;
endif;
$promotional_offers = tools::find("all", TM_PROMOTIONAL_OFFERS, '*', "WHERE :all ", array(":all"=>1));
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>LIST(S) OF PROMOTIONAL OFFERS</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	$(document).ready(function() {
		$('#example').DataTable();
	} );
	function change_status(offer_id, cur)
	{
		$.ajax({
			url:"<?= DOMAIN_NAME_PATH_ADMIN."ajax_offer_status_update";?>",
			type:"post",
			data:{
				offer_id:offer_id
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
				<h1>Lists Of Promotional Offers</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Lists Of Promotional Offers </li>
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
												<a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>create_new_offer"><button class="status_checks btn btn-success btn-md" type="submit" style="float:right; margin-bottom:10px;" value="" onclick="" >CREATE NEW PROMOTIONAL OFFER</button></a>
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
													<th>Offer Document</th>
													<th>Offer Title</th>
													<th>Valid Through</th>
													<th>Status</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody aria-relevant="all" aria-live="polite" role="alert">
											<?php
											if(!empty($promotional_offers)):
												foreach($promotional_offers as $offer_key=>$offer_val):
											?>
												<tr class="odd">
													<td class="sorting_1"><?= $offer_key+1;?></td>
													<td class=" ">
														<?php
														if($offer_val['offer_document']!="" && file_exists(PROMO_DOC.$offer_val['offer_document'])):
														?>
															<a href="<?php echo DOMAIN_NAME_PATH_ADMIN.PROMO_DOC.$offer_val['offer_document'];?>" target="_blank">View File</a>
														<?php
														else:
															echo "N/A";
														endif;
														?>
													</td>
													<td class=" "><?= $offer_val['offer_title'];?></td>
													<td class=" ">
														<?php
														if($offer_val['offer_start_date']!="0000-00-00" || $offer_val['offer_end_date']!="0000-00-00"):
															echo "From ".tools::module_date_format($offer_val['offer_start_date'], "Y-m-d")." To ".tools::module_date_format($offer_val['offer_end_date'], "Y-m-d");
														else:
															echo "N/A";
														endif;
														?>
													</td>
													<td class=" ">
														<a style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" data-id="" class="status_checks <?= $offer_val['status']==1 ? "btn-success" : "btn-danger";?>" onclick="change_status(<?= $offer_val['id'];?>, $(this))"><?= $offer_val['status']==1 ? "Active" : "Inactive";?></a>
													</td>
													<td class=" " data-title="Action">
														<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>edit_offer?offer_id=<?php echo base64_encode($offer_val['id']);?>" title = "Edit Offer"><i class="fa fa-pencil-square-o fa-1x" ></i></a>&nbsp;&nbsp;
														<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>offers?offer_id=<?php echo base64_encode($offer_val['id']);?>"  title = "Delete Offer" onclick = "confirm('Are you sure you want to delete this item?') ? '' : event.preventDefault()"><i class="fa fa fa-trash-o fa-1x"></i></a>
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