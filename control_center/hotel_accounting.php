<?php
require_once('../loader.inc');
tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>ACCOUNTING DETAILS FOR AGENTS</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script>
	$( function() {
		$( "#date_from" ).datepicker();
		$( "#date_to" ).datepicker();
	} );
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
			<h1>ACCOUNTING DETAILS FOR HOTEL(S)</h1>
			<ol class="breadcrumb">
				<li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">Hotel(s) Accounting </li>
			</ol>
		</section>
		<section class="content">
			<div class="row">
				<section class="col-lg-12 connectedSortable">
					<div id="notify_msg_div"></div>
					<div class="box">
						<div class="box-body">
							<form name = "agent_accounting" id = "agent_accounting" method = "POST" action = "">
								<div class="col-md-12 row">
									<div class="box-body">
										<div class="form-group col-md-6">
											<label for="email"><font color="#FF0000">*</font>Select Hotel</label>
											<select class="form-control" name = "hotels" id = "hotels" tabindex = "1">
												<option value = "">All</option>
												<option value = "">Hotel Name</option>
											</select>
										</div>
										<div class="form-group col-md-6">
											<label for="email"><font color="#FF0000">*</font>Booking Status</label>
											<select class="form-control" name = "booking_status" id = "booking_status" tabindex = "2">
												<option value = "A">All</option>
												<option value = "R">Confirmed</option>
												<option value = "C">Cancelled</option>
											</select>
										</div>
										<div class="form-group col-md-6">
											<label for="email">Date From</label>
											<input type="text" class="form-control"  value="" name="date_from" id="date_from" placeholder="Date From" tabindex = "3" />
										</div>
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Date To</label>
											<div class="input-icon right">
												<input type="text" class="form-control"  placeholder = "Date To" name="date_to" id="date_to" tabindex = "4" />
											</div>
										</div>
										<div class="form-group col-md-12">
											<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex = "5">SEARCH</button>
										</div>
									</div>
								</div>
							</form>
							<div id="example1_wrapper" class="dataTables_wrapper form-inline" role="grid">
								<div id="no-more-tables">
									<table class="table table-bordered table-striped dataTable">
										<tbody aria-relevant="all" aria-live="polite" role="alert">
											<tr>
												<td style = "text-align:center;font-weight:bold;">Please use the above form to generate your preferred report!</td>
											</tr>
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