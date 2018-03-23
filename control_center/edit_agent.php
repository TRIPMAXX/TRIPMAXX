<?php
require_once('../loader.inc');
?>
<!DOCTYPE html>
<html>
  <head>
    <title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>EDIT AGENT</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	jQuery(document).ready(function(){
		jQuery("#form_currency_add").validationEngine();
	});
	//-->
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
				<h1>Edit Agent</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Edit Agent</li>
				</ol>
			</section>
            <section class="content">
				<form  name="form_currency_add" id="form_currency_add" method="POST">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
							<div class="col-md-12 row">
								<div class="box-header">
								   <h3 class="box-title">Company Details</h3>
								</div>
								<div class="box-body">
									<div id="" class="row rows">
										<div class="form-group col-md-6">
											<label for="pwd" class="form-label1">Company Name <span class=""> *</span> :</label>
											<input type="email" class="form-control form_input1" id="email" placeholder="">
										</div>
										<div class="form-group col-md-6">
											<label for="pwd" class="form-label1">Company Accounting Name<span class=""> *</span> :</label>
											<input type="email" class="form-control form_input1" id="email" placeholder="">
										</div>
										
										<div class="form-group col-md-4">
											<label for="pwd" class="form-label1">First Name<span class=""> *</span> :</label>
											<input type="email" class="form-control form_input1" id="email" placeholder="First Name">
										</div>
										<div class="form-group col-md-4">
											<label for="pwd" class="form-label1">Middle Name<span class=""> *</span> :</label>
											<input type="email" class="form-control form_input1" id="email" placeholder="Middle Name">
										</div>
												
										<div class="form-group col-md-4">
											<label for="pwd" class="form-label1">Last Name<span class=""> *</span> :</label>
											<input type="email" class="form-control form_input1" id="email" placeholder="Last Name">
										</div>
										<div class="form-group col-md-6">
											<label for="pwd" class="form-label1">Email<span class=""> *</span> :</label>
											<input type="email" class="form-control form_input1" id="email" placeholder="">
										</div>
										<div class="form-group col-md-6">
											<label for="pwd" class="form-label1">Designation :</label>
											<input type="email" class="form-control form_input1" id="email" placeholder="">
										</div>
										<div class="form-group col-md-6 radio_pad">
											<label for="pwd" class="form-label1">IATA Status<span class=""> *</span> :</label>
											<select name="cars" class="form-control form_input1 select_bg">
												<option value="Approve" class="form-control form_input1">Approve</option>
												<option value="Not Approve" class="form-control form_input1">Not Approve</option>
											</select>
										</div>
										<div class="form-group col-md-6">
											<label for="pwd" class="form-label1">Nature of Business:</label>
											<select name="cars" class="form-control form_input1 select_bg">
												<option value="volvo" class="form-control form_input1">- Select -</option>
												<option value="saab" class="form-control form_input1">Activity Supplier</option>
												<option value="fiat" class="form-control form_input1">Hotel</option>
												<option value="audi" class="form-control form_input1">Hotel Chain</option>
												<option value="saab" class="form-control form_input1">Resturent</option>
											</select>
										</div>
										<div class="form-group col-md-6">
											<label for="pwd" class="form-label1">Preferred Currency <span class="">*</span> :</label>
											<select name="cars" class="form-control form_input1 select_bg">
												<option value="volvo" class="form-control form_input1">- Select -</option>
												<option value="saab" class="form-control form_input1">Activity Supplier</option>
												<option value="fiat" class="form-control form_input1">Hotel</option>
												<option value="audi" class="form-control form_input1">Hotel Chain</option>
												<option value="saab" class="form-control form_input1">Resturent</option>
											</select>
										</div>
										<div class="form-group col-md-6">
											<label for="pwd" class="form-label1">Country<span class="">*</span> :</label>
											<select name="cars" class="form-control form_input1 select_bg" style="background:rgba(255,255,255) url('img/dropdown_arrow.png') no-repeat 98% center !important; background-size:30px !important">
												<option value="volvo" class="form-control form_input1">- Select -</option>
												<option value="saab" class="form-control form_input1">Activity Supplier</option>
												<option value="fiat" class="form-control form_input1">Hotel</option>
												<option value="audi" class="form-control form_input1">Hotel Chain</option>
												<option value="saab" class="form-control form_input1">Resturent</option>
											</select>
										</div>
										<div class="form-group col-md-6">
											<label for="pwd" class="form-label1">City <span class="">*</span> :</label>
											<select name="cars" class="form-control form_input1 select_bg">
												<option value="volvo" class="form-control form_input1">Please Select</option>
												<option value="saab" class="form-control form_input1">Activity Supplier</option>
												<option value="fiat" class="form-control form_input1">Hotel</option>
												<option value="audi" class="form-control form_input1">Hotel Chain</option>
												<option value="saab" class="form-control form_input1">Resturent</option>
											</select>
										</div>
										<div class="form-group col-md-6">
											<label for="pwd" class="form-label1">Pincode/Zipcode/Postcode<span class="">*</span> :</label>
											<input type="email" class="form-control form_input1" id="email" placeholder="">
										</div>
										<div class="form-group col-md-12">
											<label for="pwd" class="form-label1">Address<span class="">*</span> :</label>
											<textarea class="form-control form_input1" rows="5" id="comment"></textarea>
										</div>
										<div class="form-group col-md-6">
											<label for="pwd" class="form-label1">Time Zone<span class="">*</span> :</label>
											<select name="cars" class="form-control form_input1 select_bg" style="background:rgba(255,255,255) url('img/dropdown_arrow.png') no-repeat 98% center !important; background-size:30px !important">
												<option value="volvo" class="form-control form_input1">- Select -</option>
												<option value="saab" class="form-control form_input1">Activity Supplier</option>
												<option value="fiat" class="form-control form_input1">Hotel</option>
												<option value="audi" class="form-control form_input1">Hotel Chain</option>
												<option value="saab" class="form-control form_input1">Resturent</option>
											</select>
										</div>
										<div class="form-group col-md-6">
											<label for="pwd" class="form-label1">Telephone* :</label>
											<input type="email" class="form-control form_input1" id="email" placeholder="">
										</div>
										<div class="form-group col-md-6">
											<label for="pwd" class="form-label1">Mobile Number<span class="">*</span> :</label>
											<input type="email" class="form-control form_input1" id="email" placeholder="">
										</div>
										<div class="form-group col-md-6">
											<label for="pwd" class="form-label1">Website:</label>
											<input type="email" class="form-control form_input1" id="email" placeholder="">
										</div>
										<div class="form-group col-md-6">
											<label for="pwd" class="form-label1">Your Logo:</label>
											<input type="file"  id="email" placeholder="">
										</div>
										<div class="form-group col-md-6">
											<label for="pwd" class="code_text1 form-label1">Type The Code Shown<span class="">*</span> :</label>
											<input type="text" class="form-control form_input1" id="text" placeholder="">
										</div>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>

						<div class="box box-primary">
							<div class="col-md-12 row">
								<div class="box-header">
								   <h3 class="box-title">Login Details</h3>
								</div>
								<div class="box-body">
									<div id="" class="row rows">
										<div class="form-group col-md-12">
											<label for="pwd" class="form-label1">Username <span class="">*</span> :</label>
											<input type="text" class="form-control form_input1" id="text" placeholder="">
										</div>
										<div class="form-group col-md-6">
											<label for="pwd" class="form-label1">Password <span class="">*</span> :</label>
											<input type="text" class="form-control form_input1" id="text" placeholder="">
										</div>
										<div class="form-group col-md-6">
											<label for="pwd" class="form-label1">Confirm Password <span class="">*</span> :</label>
											<input type="text" class="form-control form_input1" id="text" placeholder="">
										</div>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>

						<div class="box box-primary">
							<div class="col-md-12 row">
								<div class="box-header">
								   <h3 class="box-title">Access Details</h3>
								</div>
								<div class="box-body">
									<div id="" class="row rows">
										<div id="" class="col-md-3">
											<div class="form-group fancy-form">
												<label for="pwd" class="form-label1">Account Department :</label>
											</div>
										</div>
										<div id="" class="col-md-9">
											<div class="row rows">
												<div id="" class="col-md-4">
													<div class="form-group fancy-form">
														<input type="text" class="form-control form_input1" id="text" placeholder="Name">
													</div>
												</div>
												<div id="" class="col-md-4">
													<div class="form-group fancy-form">
														<input type="text" class="form-control form_input1" id="text" placeholder="Email">
													</div>
												</div>
												<div id="" class="col-md-4">
													<div class="form-group fancy-form">
														<input type="text" class="form-control form_input1" id="text" placeholder="Contact Number">
													</div>
												</div>
											</div>
										</div>

										<div id="" class="col-md-3">
											<div class="form-group fancy-form">
												<label for="pwd" class="form-label1">Reservations/Operations Department:</label>
											</div>
										</div>
										<div id="" class="col-md-9">
											<div class="row rows">
												<div id="" class="col-md-4">
													<div class="form-group fancy-form">
														<input type="text" class="form-control form_input1" id="text" placeholder="Name">
													</div>
												</div>
												<div id="" class="col-md-4">
													<div class="form-group fancy-form">
														<input type="text" class="form-control form_input1" id="text" placeholder="Email">
													</div>
												</div>
												<div id="" class="col-md-4">
													<div class="form-group fancy-form">
														<input type="text" class="form-control form_input1" id="text" placeholder="Contact Number">
													</div>
												</div>
											</div>
										</div>
										<div id="" class="col-md-12">
											<div id="" class="row rows">
												<div id="" class="col-md-3">
													<div class="form-group fancy-form">
														<label for="pwd" class="form-label1">Management Department:</label>
													</div>
												</div>
												<div id="" class="col-md-9">
													<div class="row rows">
														<div id="" class="col-md-4">
															<div class="form-group fancy-form">
																<input type="text" class="form-control form_input1" id="text" placeholder="Name">
															</div>
														</div>
														<div id="" class="col-md-4">
															<div class="form-group fancy-form">
																<input type="text" class="form-control form_input1" id="text" placeholder="Email">
															</div>
														</div>
														<div id="" class="col-md-4">
															<div class="form-group fancy-form">
																<input type="text" class="form-control form_input1" id="text" placeholder="Contact Number">
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>

						<div class="box box-primary">
							<div class="col-md-12 row">
								<div class="box-header">
								   <h3 class="box-title">Price Markup (%)</h3>
								</div>
								<div class="box-body">
									<div id="" class="row rows">
										<div class="form-group col-md-3">
											<label for="pwd" class="form-label1">Hotel :</label>
											<input type="text" class="form-control form_input1" id="text" placeholder="Hotel">
										</div>
										<div class="form-group col-md-3">
											<label for="pwd" class="form-label1">Tour :</label>
											<input type="text" class="form-control form_input1" id="text" placeholder="Tour">
										</div>
										<div class="form-group col-md-3">
											<label for="pwd" class="form-label1">Transfer :</label>
											<input type="text" class="form-control form_input1" id="text" placeholder="Transfer">
										</div>
										<div class="form-group col-md-3">
											<label for="pwd" class="form-label1">Package :</label>
											<input type="text" class="form-control form_input1" id="text" placeholder="Package">
										</div>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>

						<div class="box-footer">
							<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex = "4">UPDATE</button>
						</div>
					</div>
				</div>
				</form>
			</section>
		</div>
		<!-- BODY --> 

		<!-- FOOTER -->
		<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'footer.php');?>
		<!-- FOOTER -->

	</div>
</body>
</html>