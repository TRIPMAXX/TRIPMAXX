<?php
require_once('loader.inc');
tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
$support_tickets = tools::find("all", TM_SUPPORT_TICKETS, '*', "WHERE 1 ORDER BY status ASC", array());
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>LISTS OF SUPPORT TICKETS</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	$(document).ready(function() {
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
				<h1>Lists Of Support Tickets</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Lists Of Support Tickets </li>
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
													<th>Ticket No</th>
													<th>Account Type</th>
													<th>Name</th>
													<th>Priority</th>
													<th>Posting Date</th>
													<th>Status</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody aria-relevant="all" aria-live="polite" role="alert">
											<?php
											if(!empty($support_tickets)):
												foreach($support_tickets as $s_key => $support_ticket_val):
											?>
												<tr class="odd">
													<td class="  sorting_1"><?=$s_key+1?></td>
													<td class=" "><?=$support_ticket_val['ticket_id']?></td>
													<td class=" ">
													<?=($support_ticket_val['account_type']=="A"?"Agent":($support_ticket_val['account_type']=="H"?"Hotel":($support_ticket_val['account_type']=="S"?"Supplier":"")))?>
													</td>
													<td class=" "><?=$support_ticket_val['account_name']?></td>
													<td class=" ">
													<?=($support_ticket_val['priority']=="H"?"High":($support_ticket_val['priority']=="M"?"Medium":($support_ticket_val['priority']=="L"?"Low":"")))?>
													</td>
													<td class=" "><?= tools::module_date_format($support_ticket_val['creation_date'],"Y-m-d H:i:s");?></td>
													<td class=" ">
														<a style="padding: 3px;border-radius: 2px;text-decoration:none" data-id="" class="status_checks <?=($support_ticket_val['status']=="P"?"btn-warning":"btn-success")?>"><?=($support_ticket_val['status']=="P"?"Pending":"Completed")?></a>
													</td>
													<td class=" " data-title="Action">
														&nbsp;&nbsp;<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>view_support_ticket?ticket_id=<?=base64_encode($support_ticket_val['id'])?>" title = "Edit Email Template"><i class="fa fa-eye fa-1x" ></i></a>
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