<?php
require_once('../loader.inc');
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>MANAGE PROFILE</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
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

		<div class="content-wrapper">
			<section class="content-header">
				<h1>Edit Profile</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo(DOMAIN_NAME_PATH_HOTEL);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Edit Profile </li>
				</ol>
			</section>
			<section class="content">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
						<form role="form" name="profile" id="profile"method="post" enctype="mulimedeia/form-data">
							<div class="col-md-12 row">
								<div class="box-body">
									<div class="form-group col-md-6">
										<label for="email"><font color="#FF0000">*</font>Email</label>
										<input type="text" class="form-control validate[required] custom[email]"  value="" name="email_address" id="email_address" placeholder="Enter Email" data-errormessage-value-missing="Email is required!" tabindex = "1" />
									</div>
									<div class="form-group col-md-6">
										<label for="email"><font color="#FF0000">*</font>Username</label>
										<input type="text" class="form-control validate[required]"  value="" name="username" id="username" placeholder="Enter Username" data-errormessage-value-missing="Username is required!" tabindex = "2" />
									</div>
									<div class="form-group col-md-6">
										<label for="inputName" class="control-label">Password</label>
										<div class="input-icon right">
											<input type="password" class="form-control validate[optional]"  placeholder = "Password" name="password" id="password" tabindex = "3" />
										</div>
										<div style = "margin-bottom:10px;color:#ff0000">Note: Leave blank if you do not wish to change existing password.</div>
									</div>
									<div class="form-group col-md-6">
										<label for="inputName" class="control-label">Confirm Password</label>
										<div class="input-icon right">
											<input type="password" class="form-control validate[optional, equals[password]]"  placeholder = "Confirm Password" name="confirm_password" id="confirm_password" tabindex = "3" />
										</div>
										<div style = "margin-bottom:10px;color:#ff0000">Note: Leave blank if you do not wish to change existing password.</div>
									</div>
								</div>
							</div>
							<div class="col-md-12 row">
								<div class="box-footer">
									<input type="hidden" name="token" value="" />
									<input type = "hidden" name = "id" id = "id" value = "" />
									<button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary" tabindex = "4">Edit</button>
								</div>
							</div>
						</form>
						<div class="clearfix"></div>
					</div>
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