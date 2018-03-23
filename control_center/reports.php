<?php
require_once('../loader.inc');
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>LIST(S) OF REPORTS</title>
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
			<h1>REPORTS</h1>
			<ol class="breadcrumb">
				<li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">Lists Of Reports </li>
			</ol>
		</section>
		<section class="content">
			<div class="row">
				<section class="col-lg-12 connectedSortable">
					<div id="notify_msg_div"></div>
					<div class="box">
						<div class="box-body">
							<form role="form" name="profile" id="profile"method="post">
								<div class="col-md-12 row">
									<div class="box-body">
										<div class="form-group col-md-6">
											<label for="email"><font color="#FF0000">*</font>Report Type</label>
											<select class="form-control" tabindex = "1">
												<option value = "booking">Lists Of Booking</option>
												<option value = "Agents">Lists Of Agents</option>
												<option value = "Suppliers">Lists Of Suppliers</option>
												<option value = "Suppliers">Lists Of GSA</option>
												<option value = "Hotels">Lists Of Hotels</option>
												<option value = "Employee">Lists Of Employees</option>
												<option value = "Earnings">Earning History</option>
												<option value = "Payment_Status">Payment Status</option>
											</select>
										</div>
										<div class="form-group col-md-6">
											<label for="email"><font color="#FF0000">*</font>Report Format</label>
											<select class="form-control" tabindex = "2">
												<option value = "booking">PDF</option>
												<option value = "Agents">Excel</option>
											</select>
										</div>
										<div class="form-group col-md-6">
											<label for="email">Date From</label>
											<input type="text" class="form-control"  value="" name="date_from" id="date_from" placeholder="Date From" tabindex = "2" />
										</div>
										<div class="form-group col-md-6">
											<label for="inputName" class="control-label">Date To</label>
											<div class="input-icon right">
												<input type="text" class="form-control"  placeholder = "Date To" name="date_to" id="date_to" tabindex = "3" />
											</div>
										</div>
									</div>
									<div class="box-body">
										<div class="form-group col-md-12"><h3>Filters</h3></div>
									</div>
									<div class="box-body">
										<div class="form-group col-md-4">
											<select class="form-control" tabindex = "1">
												<option value = "booking">All Agents</option>
												<option value = "Agents">Rakesh Agarwal (12356)</option>
												<option value = "Suppliers">Sandeep Tiwary (12696)</option>
												<option value = "Suppliers">Suraj Agarwal (12698)</option>
											</select>
										</div>
										<div class="form-group col-md-4">
											<select class="form-control" tabindex = "1">
												<option value = "booking">All GSA</option>
												<option value = "Agents">Rakesh Agarwal (12356)</option>
												<option value = "Suppliers">Sandeep Tiwary (12696)</option>
												<option value = "Suppliers">Suraj Agarwal (12698)</option>
											</select>
										</div>
										<div class="form-group col-md-4">
											<select class="form-control" tabindex = "1">
												<option value = "booking">All Status</option>
												<option value = "Agents">Complete</option>
												<option value = "Suppliers">Pending</option>
												<option value = "Suppliers">Cancelled</option>
											</select>
										</div>
										<div class="form-group col-md-12">
											<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex = "4">SEARCH</button>
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