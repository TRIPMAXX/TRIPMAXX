<?php
require_once('../loader.inc');
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>CREATE NEW TRANSFER OFFER</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	jQuery(document).ready(function(){
		jQuery("#form_create_slider").validationEngine();
	});
	//-->
	</script>
	<script type="text/javascript">
	CKEDITOR.config.autoParagraph = false;
	CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
	CKEDITOR.config.shiftEnterMode = CKEDITOR.ENTER_BR;
	CKEDITOR.config.protectedSource.push(/<i[^>]*><\/i>/g);
	CKEDITOR.config.allowedContent = true;
	</script>
	<script>
	jQuery(document).ready(function(){
		jQuery("#profile").validationEngine();
	});

	$( function() {
		$( "#start_date1" ).datepicker();
		$( "#end_date1" ).datepicker();
		$( "#start_date2" ).datepicker();
		$( "#end_date2" ).datepicker();
		$( "#start_date3" ).datepicker();
		$( "#end_date3" ).datepicker();
		$( "#start_date4" ).datepicker();
		$( "#end_date4" ).datepicker();
		$( "#start_date5" ).datepicker();
		$( "#end_date5" ).datepicker();
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
               <h1>Create New Transfer Offer For "Hotel Name"</h1>
               <ol class="breadcrumb">
                  <li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                  <li class="active">Create New Transfer Offer</li>
               </ol>
            </section>
            <section class="content">
				<form name="profile" name="form_create_slider" id="form_create_slider" method="POST" enctype="mulimedeia/form-data">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
							<div class="col-md-12 row">
								<div class="box-body">
									<div class="form-group col-md-4">
										<label for="inputName" class="control-label">Offer Title<font color="#FF0000">*</font></label>
										<input type="text" class="form-control validate[required]"  value="" name="hotel_name" id="hotel_name" placeholder="Offer Title" tabindex = "1" />
									</div>
									<div class="form-group col-md-4">
										<label for="inputName" class="control-label">Capacity<font color="#FF0000">*</font></label>
										<input type="text" class="form-control validate[required]"  value="" name="hotel_name" id="hotel_name" placeholder="Capacity" tabindex = "1" />
									</div>
									<div class="form-group col-md-4">
										<label for="inputName" class="control-label">Service Type<font color="#FF0000">*</font></label>
										<select name = "country" class="form-control form_input1 select_bg">
											<option value = "">Select Service Type</option>
											<option value = "India">Private</option>
											<option value = "India">Shared</option>
										</select>
									</div>
									<div class="form-group col-md-6">
										<label for="inputName" class="control-label">Default Price Per Person<font color="#FF0000">*</font></label>
										<input type="text" class="form-control validate[required]"  value="" name="hotel_name" id="hotel_name" placeholder="Default Price Per Person" tabindex = "1" />
									</div>
									<div class="form-group col-md-6">
										<label for="inputName" class="control-label">Status</label>
										<select class="form-control validate[optional]"  tabindex = "5">
											<option value = "agent">Active</option>
											<option value = "agent">Inactive</option>
										</select>
									</div>
								</div>
								<div class="col-md-12 row">
									<div class="box-footer">
										<input type="hidden" name="token" value="" />
										<input type = "hidden" name = "id" id = "id" value = "" />
										<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex = "4">CREATE</button>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
							<div class="col-md-12 row">
								<div class="box-header">
								   <h3 class="box-title">Offer Price Range</h3>
								</div>
								<div class="box-body">
									<div class="dataTables_wrapper form-inline" role="grid">
										<table class="table table-bordered table-striped dataTable">
											<thead>
												<tr role="row">
													<th>#</th>
													<th>Start Date</th>
													<th>End Date</th>
													<th>Price / Person $</th>
												</tr>
											</thead>
											<tbody aria-relevant="all" aria-live="polite" role="alert">
												<tr class="odd">
													<td class="  sorting_1">1</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="start_date1" id="start_date1" placeholder="Start Date" tabindex = "1" readonly />
													</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="end_date1" id="end_date1" placeholder="End Date" tabindex = "1" readonly />
													</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="hotel_name" id="hotel_name" placeholder="Price  / Person" tabindex = "1" />
													</td>
												</tr>
												<tr class="odd">
													<td class="  sorting_1">2</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="start_date2" id="start_date2" placeholder="Start Date" tabindex = "1" readonly />
													</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="end_date2" id="end_date2" placeholder="End Date" tabindex = "1" readonly />
													</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="hotel_name" id="hotel_name" placeholder="Price  / Person" tabindex = "1" />
													</td>
												</tr>
												<tr class="odd">
													<td class="  sorting_1">3</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="start_date3" id="start_date3" placeholder="Start Date" tabindex = "1" readonly />
													</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="end_date3" id="end_date3" placeholder="End Date" tabindex = "1" readonly />
													</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="hotel_name" id="hotel_name" placeholder="Price  / Person" tabindex = "1" />
													</td>
												</tr>
												<tr class="odd">
													<td class="  sorting_1">4</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="start_date4" id="start_date4" placeholder="Start Date" tabindex = "1" readonly />
													</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="end_date4" id="end_date4" placeholder="End Date" tabindex = "1" readonly />
													</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="hotel_name" id="hotel_name" placeholder="Price  / Person" tabindex = "1" />
													</td>
												</tr>
												<tr class="odd">
													<td class="  sorting_1">5</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="start_date5" id="start_date5" placeholder="Start Date" tabindex = "1" readonly />
													</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="end_date5" id="end_date5" placeholder="End Date" tabindex = "1" readonly />
													</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="hotel_name" id="hotel_name" placeholder="Price  / Person" tabindex = "1" />
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="col-md-12 row">
										<div class="box-footer">
											<input type="hidden" name="token" value="" />
											<input type = "hidden" name = "id" id = "id" value = "" />
											<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex = "4">UPDATE</button>
										</div>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
							<div class="col-md-12 row">
								<div class="box-header">
								   <h3 class="box-title">Agent Markup</h3>
								</div>
								<div class="box-body">
									<div class="dataTables_wrapper form-inline" role="grid">
										<table class="table table-bordered table-striped dataTable">
											<thead>
												<tr role="row">
													<th>#</th>
													<th>Agent Name</th>
													<th>Agent Code</th>
													<th>Agent Markup (%)</th>
												</tr>
											</thead>
											<tbody aria-relevant="all" aria-live="polite" role="alert">
												<tr class="odd">
													<td class="  sorting_1">1</td>
													<td class=" ">
														Sandeep Sing
													</td>
													<td class=" ">
														012365
													</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="hotel_name" id="hotel_name" placeholder="Markup in %" tabindex = "1" />
													</td>
												</tr>
												<tr class="odd">
													<td class="  sorting_1">1</td>
													<td class=" ">
														Ajay Dey
													</td>
													<td class=" ">
														123698
													</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="hotel_name" id="hotel_name" placeholder="Markup in %" tabindex = "1" />
													</td>
												</tr>
												<tr class="odd">
													<td class="  sorting_1">1</td>
													<td class=" ">
														Pradipta Maitra
													</td>
													<td class=" ">
														236589
													</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="hotel_name" id="hotel_name" placeholder="Markup in %" tabindex = "1" />
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="col-md-12 row">
										<div class="box-footer">
											<input type="hidden" name="token" value="" />
											<input type = "hidden" name = "id" id = "id" value = "" />
											<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex = "4">UPDATE</button>
										</div>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
							<div class="col-md-12 row">
								<div class="box-header">
								   <h3 class="box-title">Nationality Wise Addon Price</h3>
								</div>
								<div class="box-body">
									<div class="dataTables_wrapper form-inline" role="grid">
										<table class="table table-bordered table-striped dataTable">
											<thead>
												<tr role="row">
													<th>#</th>
													<th>Nationality</th>
													<th>Addon Price $</th>
												</tr>
											</thead>
											<tbody aria-relevant="all" aria-live="polite" role="alert">
												<tr class="odd">
													<td class="  sorting_1">1</td>
													<td class=" ">
														Indian
													</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="hotel_name" id="hotel_name" placeholder="Addon Price" tabindex = "1" />
													</td>
												</tr>
												<tr class="odd">
													<td class="  sorting_1">1</td>
													<td class=" ">
														Thialand
													</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="hotel_name" id="hotel_name" placeholder="Addon Price" tabindex = "1" />
													</td>
												</tr>
												<tr class="odd">
													<td class="  sorting_1">1</td>
													<td class=" ">
														Singapore
													</td>
													<td class=" ">
														<input type="text" class="form-control validate[required]"  value="" name="hotel_name" id="hotel_name" placeholder="Addon Price" tabindex = "1" />
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="col-md-12 row">
										<div class="box-footer">
											<input type="hidden" name="token" value="" />
											<input type = "hidden" name = "id" id = "id" value = "" />
											<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex = "4">UPDATE</button>
										</div>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
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