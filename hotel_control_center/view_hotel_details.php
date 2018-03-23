<?php
require_once('../loader.inc');
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>VIEW HOTEL DETAILS</title>
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
            
            <section class="content">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
							<div class="col-md-12 row">
								<div class="box-header">
								   <h3 class="box-title">Hotel Details</h3>
								</div>
								<div class="box-body">
									<div class="form-group col-md-6">
										<label for="inputName" class="control-label">Hotel Name<font color="#FF0000">*</font></label>
										<br/>
										Hotel Seagul
									</div>
									<div class="form-group col-md-4">
										<label for="inputName" class="control-label">Email Address<font color="#FF0000">*</font></label>
										<br/>
										seagul@gmail.com
									</div>
									<div class="form-group col-md-12">
										<div class = "col-md-3">
											<img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>hotel_images/img1.jpg" border = "0" alt = "" width = "250" height = "150" />
										</div>
										<div class = "col-md-3">
											<img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>hotel_images/img2.jpg" border = "0" alt = "" width = "250" height = "150" />
										</div>
										<div class = "col-md-3">
											<img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>transfer/img1.jpg" border = "0" alt = "" width = "250" height = "150" />
										</div>
										<div class = "col-md-3">
											<img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>transfer/img2.jpg" border = "0" alt = "" width = "250" height = "150" />
										</div>
									</div>
									<div class="form-group col-md-4">
										<label for="inputName" class="control-label">Hotel Address<font color="#FF0000">*</font></label>
										<br/>
										123 Street Address, Country, State, City, 12356. 
									</div>
									<div class="form-group col-md-4">
										<label for="inputName" class="control-label">Country<font color="#FF0000">*</font></label>
										<br/>
										Country Name
									</div>
									<div class="form-group col-md-4">
										<label for="inputName" class="control-label">State / Region<font color="#FF0000">*</font></label>
										<br/>
										State Name
									</div>
									<div class="form-group col-md-3">
										<label for="inputName" class="control-label">City<font color="#FF0000">*</font></label>
										<br/>
										City Name
									</div>
									<div class="form-group col-md-3">
										<label for="inputName" class="control-label">Postal Code<font color="#FF0000">*</font></label>
										<br/>
										12345
									</div>
									<div class="form-group col-md-3">
										<label for="inputName" class="control-label">Phone Number<font color="#FF0000">*</font></label>
										<br/>
										11-123-1234
									</div>
									<div class="form-group col-md-3">
										<label for="inputName" class="control-label">Altername Phone Number</label>
										<br/>
										11-123-1234
									</div>
									<div class="form-group col-md-12">
										<label for="inputName" class="control-label">Short Description</label>
										<br/>
										Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium.
									</div>
									<div class="form-group col-md-12">
										<label for="inputName" class="control-label">Long Description</label>
										<br/>
										Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium.
										<br/>
										Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium.
									</div>
									<div class="form-group col-md-4">
										<label for="inputName" class="control-label">Checkin Time<font color="#FF0000">*</font></label>
										<br/>
										10:00 AM
									</div>
									<div class="form-group col-md-4">
										<label for="inputName" class="control-label">Checkout Time<font color="#FF0000">*</font></label>
										<br/>
										09:00 AM
									</div>
									<div class="form-group col-md-4">
										<label for="inputName" class="control-label">Star Rating<font color="#FF0000">*</font></label>
										<br/>
										<img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>star.png" border = "0" alt = "" height = "20"/>&nbsp;(3 STAR)
									</div>
									<div class="form-group col-md-4">
										<label for="inputName" class="control-label">Is Cancellation Policy Applied?<font color="#FF0000">*</font></label>
										<br/>
										NO
									</div>
									<div class="form-group col-md-4">
										<label for="inputName" class="control-label">Cancellation Charge</label>
										<br/>
										N/A
									</div>
									<div class="form-group col-md-4">
										<label for="inputName" class="control-label">Cancellation Allowed Days</label>
										<br/>
										N/A
									</div>
									<div class="form-group col-md-12">
										<label for="inputName" class="control-label">Other Policies</label>
										<br/>
										Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium.
									</div>
									<div class="form-group col-md-12">
										<label for="inputName" class="control-label">Available Amenities<font color="#FF0000">*</font></label>
										<br/>
										Swimming Pool&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;WiFi&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Restaurant&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bar&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Gym&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Gaming&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Spa&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Spa&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Spa
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
			</section>

			<section class="content">
				<div class="row">
					<section class="col-lg-12 connectedSortable">
						<div id="notify_msg_div"></div>
						<div class="box">
							<div class="box-header">
							   <h3 class="box-title">Lists Of Rooms</h3>
							</div>
							<div class="box-body">
								<div id="example1_wrapper" class="dataTables_wrapper form-inline" role="grid">
									<div id="no-more-tables">
										<table aria-describedby="example1_info" id="example" class="table table-bordered table-striped dataTable">
											<thead>
												<tr role="row">
													<th>#</th>
													<th>Room Image</th>
													<th>Room Type</th>
													<th>Number Of Rooms</th>
													<th>Price / Night ($)</th>
													<th>Facilities</th>
												</tr>
											</thead>
											<tbody aria-relevant="all" aria-live="polite" role="alert">
												<tr class="odd">
													<td class="  sorting_1">1</td>
													<td class=" "><img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>room_images/img1.jpg" border = "0" alt = "" width = "250" height = "150" /></td>
													<td class=" ">Single Room</td>
													<td class=" ">20</td>
													<td class=" ">$100.00</td>
													<td class=" ">
														Television, Attached Bathroom, Carpet On Floor.
													</td>
												</tr>
												<tr class="odd">
													<td class="  sorting_1">2</td>
													<td class=" "><img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>room_images/img2.jpg" border = "0" alt = "" width = "250" height = "150" /></td>
													<td class=" ">Delux Room</td>
													<td class=" ">20</td>
													<td class=" ">$300.00</td>
													<td class=" ">
														Television, Attached Bathroom, Carpet On Floor.
													</td>
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