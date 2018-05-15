<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
	if(isset($_GET['agent_id']) && $_GET['agent_id']!=""):
		$autentication_data=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."authorized.php"));
		if(isset($autentication_data->status)):
			if($autentication_data->status=="success"):
				$post_data['token']=array(
					"token"=>$autentication_data->results->token,
					"token_timeout"=>$autentication_data->results->token_timeout,
					"token_generation_time"=>$autentication_data->results->token_generation_time
				);
				$post_data['data']=$_GET;
				$post_data_str=json_encode($post_data);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."agent/read.php");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				$return_data = curl_exec($ch);
				curl_close($ch);
				$return_data_arr=json_decode($return_data, true);
				$agent_data=array();
				if(!isset($return_data_arr['status'])):
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH']="Some error has been occure during execution.";
					header("location:agents");
					exit;
				elseif($return_data_arr['status']=="success"):
					if(isset($_GET['accounting_id']) && $_GET['accounting_id']!=""):
						$post_data['data']=$_GET;
						$post_data_str=json_encode($post_data);
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
						curl_setopt($ch, CURLOPT_HEADER, false);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
						curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
						curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."accounting/delete.php");
						curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
						$return_data = curl_exec($ch);
						curl_close($ch);
						$return_data_arr=json_decode($return_data, true);
						if(!isset($return_data_arr['status'])):
							$_SESSION['SET_TYPE'] = 'error';
							$_SESSION['SET_FLASH']="Some error has been occure during execution.";
						elseif($return_data_arr['status']=="success"):
							$_SESSION['SET_TYPE'] = 'success';
							$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
						else:
							$_SESSION['SET_TYPE'] = 'error';
							$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
						endif;
						header("location:accounting?agent_id=".$_GET['agent_id']."");
						exit;
					endif;
					$agent_data=$return_data_arr['results'];
					$post_data['data']=$_GET;
					$post_data_str=json_encode($post_data);
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_HEADER, false);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
					curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."accounting/read.php");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					$return_data = curl_exec($ch);
					curl_close($ch);
					$return_data_arr=json_decode($return_data, true);
					//print_r($return_data_arr);
					$accounting_data=array();
					if(!isset($return_data_arr['status'])):
						$_SESSION['SET_TYPE'] = 'error';
						$_SESSION['SET_FLASH']="Some error has been occure during execution.";
					elseif($return_data_arr['status']=="success"):
						$accounting_data=$return_data_arr['results'];
					else:
						$_SESSION['SET_TYPE'] = 'error';
						$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
					endif;
				else:
					$_SESSION['SET_TYPE'] = 'error';
					$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
					header("location:agents");
					exit;
				endif;
			else:
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH'] = $autentication_data->msg;
			endif;
		else:
			$_SESSION['SET_TYPE'] = 'error';
			$_SESSION['SET_FLASH'] = "We are having some problem to authorize api.";
		endif;
	else:
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = 'Some data missing.';
		header("location:agents");
		exit;
	endif;
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>LISTS OF ACCOUNTING</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	$(function() {
		$('#example').DataTable();
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
		<section class="content-header">
			<h1>Lists Of Accounting</h1>
			<ol class="breadcrumb">
				<li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">Lists Of Accounting </li>
			</ol>
		</section>
		<section class="content">
			<div class="row">
				<section class="col-lg-12 connectedSortable">
					<div id="notify_msg_div"></div>
					<div class="box">
						<div class="box-body">
							<div id="" class="row">
								<div id="" class="col-md-8">
									<h4><strong>Current Credit Balance</strong> : <?php echo number_format($agent_data['credit_balance'], 2, ".", ",");?></h4>
								</div>
								<div id="" class="col-md-4">
									<a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>create_new_accounting?agent_id=<?php echo base64_encode($agent_data['id']);?>"><button class="status_checks btn btn-success btn-md" type="submit" style="float:right; margin-bottom:10px;" value="">CREATE NEW ACCOUNTING</button></a>
								</div>
							</div>
							<div id="example1_wrapper" class="dataTables_wrapper form-inline" role="grid">
								<div id="no-more-tables">
									<table aria-describedby="example1_info" id="example" class="table table-bordered table-striped dataTable">
										<thead>
											<tr role="row">
												<th>#</th>
												<th>Date</th>
												<th>Debit/Credit</th>
												<th>Transaction Id</th>
												<th>Amount</th>
												<th>Note</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody aria-relevant="all" aria-live="polite" role="alert">
										<?php
										if(!empty($accounting_data)):
											foreach($accounting_data as $accounting_key=>$accounting_val):
										?>
											<tr class="odd">
												<td class="  sorting_1"><?= $accounting_key+1;?></td>
												<td class=" "><?= tools::module_date_format($accounting_val['creation_date'], "Y-m-d H:i:s");?></td>
												<td class=" ">
												<?php
												if($accounting_val['debit_or_credit']=="Debit"):
												?>
													<a style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" class="btn-danger"><?= $accounting_val['debit_or_credit'];?></a>
												<?php
												else:
												?>
													<a style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" class="btn-success"><?= $accounting_val['debit_or_credit'];?></a>
												<?php
												endif;
												?>
												</td>
												<td class=" "><?= $accounting_val['transaction_id'];?></td>
												<td class=" "><?= $accounting_val['amount'];?></td>
												<td class=" "><?= nl2br($accounting_val['note']);?></td>
												<td class=" " data-title="Action">
													<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>edit_accounting?accounting_id=<?php echo base64_encode($accounting_val['id']);?>&agent_id=<?php echo base64_encode($agent_data['id']);?>" title = "Edit Accounting"><i class="fa fa-pencil-square-o fa-1x" ></i></a>&nbsp;&nbsp;
													<!-- <a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>accounting?accounting_id=<?php echo base64_encode($accounting_val['id']);?>&agent_id=<?php echo base64_encode($agent_data['id']);?>"  title = "Delete Accounting" onclick = "confirm('Are you sure you want to delete this item?') ? '' : event.preventDefault()"><i class="fa fa fa-trash-o fa-1x"></i></a> -->
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