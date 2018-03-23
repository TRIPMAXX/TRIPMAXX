<?php
require_once('loader.inc');
tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
if(isset($_GET['emp_id']) && $_GET['emp_id']!=""):
	$find_emp = tools::find("first", TM_DMC, '*', "WHERE id=:id AND account_type=:account_type", array(":id"=>base64_decode($_GET['emp_id']), ":account_type"=>"E"));
	if(!empty($find_emp)):
		if(tools::delete(TM_DMC, "WHERE id=:id", array(":id"=>$find_emp['id']))):
			$_SESSION['SET_TYPE'] = 'success';
			$_SESSION['SET_FLASH'] = 'Employee has been deleted successfully.';
		else:
			$_SESSION['SET_TYPE'] = 'error';
			$_SESSION['SET_FLASH'] = 'We are having some probem. Please try again later.';
		endif;
	else:
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = 'Invalid employee id.';
	endif;
	header("location:employees");
	exit;
endif;
$emp_list = tools::find("all", TM_DMC, '*', "WHERE account_type=:account_type ", array(":account_type"=>"E"));
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>LISTS OF EMPLOYEES</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	$(document).ready(function() {
		$('#example').DataTable();
	} );
	function change_status(emp_id, cur)
	{
		$.ajax({
			url:"<?= DOMAIN_NAME_PATH_ADMIN."ajax_employee_status_update";?>",
			type:"post",
			data:{
				emp_id:emp_id
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
			<h1>Lists Of Employees</h1>
			<ol class="breadcrumb">
				<li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">Lists Of Employees </li>
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
												<th>First Name</th>
												<th>Last Name</th>
												<th>Email</th>
												<th>Phone Number</th>
												<th>Status</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody aria-relevant="all" aria-live="polite" role="alert">
										<?php
										if(!empty($emp_list)):
											foreach($emp_list as $emp_key=>$emp_val):
										?>
											<tr class="odd">
												<td class="  sorting_1"><?= $emp_key+1;?></td>
												<td class=" "><?= $emp_val['first_name'];?></td>
												<td class=" "><?= $emp_val['last_name'];?></td>
												<td class=" "><?= $emp_val['email_address'];?></td>
												<td class=" "><?= $emp_val['phone_number'];?></td>
												<td class=" ">
													<a style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" data-id="" class="status_checks <?= $emp_val['status']==1 ? "btn-success" : "btn-danger";?>" onclick="change_status(<?= $emp_val['id'];?>, $(this))"><?= $emp_val['status']==1 ? "Active" : "Inactive";?></a>
												</td>
												<td class=" " data-title="Action">
													<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>edit_employee?emp_id=<?php echo base64_encode($emp_val['id']);?>" title = "Edit Employee"><i class="fa fa-pencil-square-o fa-1x" ></i></a>&nbsp;&nbsp;
													<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>employees?emp_id=<?php echo base64_encode($emp_val['id']);?>"  title = "Delete Employee" onclick = "confirm('Are you sure you want to delete this item?') ? '' : event.preventDefault()"><i class="fa fa fa-trash-o fa-1x"></i></a>
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