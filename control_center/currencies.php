<?php
require_once('loader.inc');
tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
if(isset($_GET['currency_id']) && $_GET['currency_id']!=""):
	$find_currency = tools::find("first", TM_CURRENCIES, '*', "WHERE id=:id ", array(":id"=>base64_decode($_GET['currency_id'])));
	if(!empty($find_currency)):
		if(tools::delete(TM_CURRENCIES, "WHERE id=:id", array(":id"=>$find_currency['id']))):
			$_SESSION['SET_TYPE'] = 'success';
			$_SESSION['SET_FLASH'] = 'Currency has been deleted successfully.';
		else:
			$_SESSION['SET_TYPE'] = 'error';
			$_SESSION['SET_FLASH'] = 'We are having some probem. Please try again later.';
		endif;
	else:
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = 'Invalid currency id.';
	endif;
	header("location:currencies");
	exit;
endif;
$currency_details = tools::find("all", TM_CURRENCIES, '*', "WHERE :all ", array(":all"=>1));
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>LISTS OF CURRENCIES</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	$(document).ready(function() {
		$('#example').DataTable();
	} );
	function change_status(currency_id, cur)
	{
		$.ajax({
			url:"<?= DOMAIN_NAME_PATH_ADMIN."ajax_currency_status_update";?>",
			type:"post",
			data:{
				currency_id:currency_id
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
	//-->
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
		
		<div class="content-wrapper">
			<section class="content-header">
			<h1>Lists Of Currencies</h1>
			<ol class="breadcrumb">
				<li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">Lists Of Currencies </li>
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
											<a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>create_new_currency"><button class="status_checks btn btn-success btn-md" type="submit" style="float:right; margin-bottom:10px;" value="" onclick="" >CREATE NEW CURRENCY</button></a>
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
												<th>Title</th>
												<th>Short Code</th>
												<th>Status</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody aria-relevant="all" aria-live="polite" role="alert">
										<?php
										if(!empty($currency_details)):
											foreach($currency_details as $currency_key=>$currency_val):
										?>
											<tr class="odd">
												<td class="  sorting_1"><?= $currency_key+1;?></td>
												<td class=" "><?= $currency_val['currency_name'];?></td>
												<td class=" "><?= $currency_val['currency_code'];?></td>
												<td class=" ">
													<a style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" data-id="" class="status_checks <?= $currency_val['status']==1 ? "btn-success" : "btn-danger";?>" onclick="change_status(<?= $currency_val['id'];?>, $(this))"><?= $currency_val['status']==1 ? "Active" : "Inactive";?></a>
												</td>
												<td class=" " data-title="Action">
													<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>edit_currencies?currency_id=<?php echo base64_encode($currency_val['id']);?>" title = "Edit Currency"><i class="fa fa-pencil-square-o fa-1x" ></i></a>&nbsp;&nbsp;
													<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>currencies?currency_id=<?php echo base64_encode($currency_val['id']);?>"  title = "Delete Currency" onclick = "confirm('Are you sure you want to delete this item?') ? '' : event.preventDefault()"><i class="fa fa fa-trash-o fa-1x"></i></a>
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
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'footer.php');?>
	<!-- FOOTER -->

</div>
</body>
</html>