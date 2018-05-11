<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['AGENT_SESSION_DATA']['id'], DOMAIN_NAME_PATH.'');
	if(isset($_GET['sub_agent_id']) && $_GET['sub_agent_id']!=""):
		$sub_agent_data = tools::find("first", TM_AGENT, "*", "WHERE id=:id AND parent_id=:parent_id", array(':id'=>base64_decode($_GET['sub_agent_id']), ':parent_id'=>$_SESSION['AGENT_SESSION_DATA']['id']));
		if(!empty($sub_agent_data)):
			$post_agent_id=base64_decode($_GET['sub_agent_id']);
		else:
			$_SESSION['SET_TYPE'] = 'error';
			$_SESSION['SET_FLASH']="Invalid sub agent.";
			header("location:".DOMAIN_NAME_PATH."accounting.php");
			exit;
		endif;
	else:
		$post_agent_id=$_SESSION['AGENT_SESSION_DATA']['id'];
	endif;
	$accounting_data=tools::find("all", TM_AGENT_ACCOUNTING, "*", "WHERE agent_id = :agent_id ORDER BY id DESC", array(":agent_id"=>$post_agent_id));
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo DEFAULT_PAGE_TITLE ;?> Destination Management Company</title>	
		<?php require_once('meta.php');?>
		<link rel="stylesheet" href="<?php echo(DOMAIN_NAME_PATH);?>css/jquery.dataTables.min.css" />
		<script src="<?php echo(DOMAIN_NAME_PATH);?>js/jquery.dataTables.min.js"></script>
		<script type="text/javascript">
		<!--
			$(function() {
				$('#example').DataTable();
			});
		//-->
		</script>
	</head>
	<body class="index-page">
		<!-- TOP HEADER -->
		<?php require_once('header.php');?>
		<!-- TOP HEADER -->
		<div class="main-cont">
			<div class="body-padding">
				<div class="banner_slider" style="background:url(img/banner4.jpg)no-repeat center center/cover;">
					<div class="banner_slider_text">
						Accounting
					</div>
				</div>
				<?php require_once('login_menu.php');?>
				<div id="" class="container">
					<div class="mp-offesr">
						<div class="wrapper-padding-a">
							<div class="offer-slider">
								<div class="row rows">
									<div class="col-md-12">
										<div class="box box-info">
											<div class="box-body">
												<div class="box-body no-padding">
													<div id="no-more-tables">
														<table aria-describedby="example1_info" id="example" class="table table-bordered table-striped dataTable">
															<thead>
																<tr role="row">
																	<th>#</th>
																	<th>Date</th>
																	<th>Debit/Credit</th>
																	<th>Amount</th>
																	<th>Note</th>
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
																	<td class=" "><?= $find_agent_data['currency_code']." ".$accounting_val['amount'];?></td>
																	<td class=" "><?= nl2br($accounting_val['note']);?></td>
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
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- FOOTER -->
		<?php require_once('footer.php');?>
		<!-- FOOTER -->
	</body>
</html>