<?php
require_once('../loader.inc');
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>EDIT ROOM</title>
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
               <h1>Edit Room For "Hotel Name"</h1>
               <ol class="breadcrumb">
                  <li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                  <li class="active">Edit Room</li>
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
									<div class="form-group col-md-6">
										<label for="inputName" class="control-label">Room Type<font color="#FF0000">*</font></label>
										<input type="text" class="form-control validate[required]"  value="" name="hotel_name" id="hotel_name" placeholder="Room Type" tabindex = "1" />
									</div>
									<div class="form-group col-md-6">
										<label for="inputName" class="control-label">Room Images</label>
										<input type="file" class="form-control validate[optional]"  value="" name="hotel_images[]" placeholder="Hotel Image" tabindex = "2" multiple/>
										<br/>
										<font color = "red">SELECT MULTIPLE BY HOLDING CONTROL BUTTON.</font>
									</div>
									<div class="form-group col-md-12">
										<div class = "col-md-3">
											<img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>room_images/img1.jpg" border = "0" alt = "" width = "250" height = "150" />
										</div>
										<div class = "col-md-3">
											<img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>room_images/img2.jpg" border = "0" alt = "" width = "250" height = "150" />
										</div>
										<div class = "col-md-3">
											<img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>room_images/img1.jpg" border = "0" alt = "" width = "250" height = "150" />
										</div>
										<div class = "col-md-3">
											<img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>room_images/img2.jpg" border = "0" alt = "" width = "250" height = "150" />
										</div>
									</div>
									<div class="form-group col-md-12">
										<label for="inputName" class="control-label">Room Description<font color="#FF0000">*</font></label>
										<textarea class="form-control ckeditor validate[required]"  value="" name="hotel_address" id="hotel_address" placeholder="Room Description" tabindex = "3"></textarea>
									</div>
									<div class="form-group col-md-12">
										<label for="inputName" class="control-label">Available Facilities<font color="#FF0000">*</font></label>
										<br/>
										<input type = "checkbox" name = "">&nbsp;Television&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Attached Bathroom&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Carpet On Floor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Television&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Attached Bathroom&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Carpet On Floor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Television&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Attached Bathroom&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Carpet On Floor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Television&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Attached Bathroom&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Carpet On Floor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Television&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Attached Bathroom&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Carpet On Floor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Television&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Attached Bathroom&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Carpet On Floor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Television&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Attached Bathroom&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Carpet On Floor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Television&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Attached Bathroom&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "checkbox" name = "">&nbsp;Carpet On Floor
									</div>
									<div class="form-group col-md-6">
										<label for="inputName" class="control-label">Default Price<font color="#FF0000">*</font></label>
										<input type="text" class="form-control validate[required]"  value="" name="hotel_name" id="hotel_name" placeholder="Default Price" tabindex = "1" />
									</div>
									<div class="form-group col-md-6">
										<label for="inputName" class="control-label">Default Number Of Rooms<font color="#FF0000">*</font></label>
										<input type="text" class="form-control validate[required]"  value="" name="hotel_name" id="hotel_name" placeholder="Default Number Of Rooms" tabindex = "1" />
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
										<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex = "4">UPDATE</button>
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
								   <h3 class="box-title">Room Price Range</h3>
								</div>
								<div class="box-body">
									<div class="dataTables_wrapper form-inline" role="grid">
										<table class="table table-bordered table-striped dataTable">
											<thead>
												<tr role="row">
													<th>#</th>
													<th>Start Date</th>
													<th>End Date</th>
													<th>Room Price / Night $</th>
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
														<input type="text" class="form-control validate[required]"  value="" name="hotel_name" id="hotel_name" placeholder="Room Price  / Night" tabindex = "1" />
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
														<input type="text" class="form-control validate[required]"  value="" name="hotel_name" id="hotel_name" placeholder="Room Price  / Night" tabindex = "1" />
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
														<input type="text" class="form-control validate[required]"  value="" name="hotel_name" id="hotel_name" placeholder="Room Price  / Night" tabindex = "1" />
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
														<input type="text" class="form-control validate[required]"  value="" name="hotel_name" id="hotel_name" placeholder="Room Price  / Night" tabindex = "1" />
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
														<input type="text" class="form-control validate[required]"  value="" name="hotel_name" id="hotel_name" placeholder="Room Price  / Night" tabindex = "1" />
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