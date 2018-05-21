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
	$find_agent_data = tools::find("first", TM_AGENT." as a, ".TM_COUNTRIES." as co, ".TM_STATES." as s, ".TM_CITIES." as ci, ".TM_CURRENCIES." as cu", 'a.*, co.name as co_name, s.name as s_name, ci.name as ci_name, cu.currency_code as currency_code, cu.currency_name as currency_name', "WHERE a.country=co.id AND a.state=s.id AND a.city=ci.id AND a.preferred_currency=cu.id AND a.id=:id ", array(":id"=>$post_agent_id));
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo DEFAULT_PAGE_TITLE ;?> List of Accounting Print</title>	
		<link rel="stylesheet" href="http://localhost/tripmaxx/css/bootstrap.css">
		<link rel="stylesheet" href="http://localhost/tripmaxx/css/mycss.css" />
		

		<style type="text/css">
			.main-header{background:#FFF;height:auto;max-height:none;padding: 15px 0px;}
			.main-header .logo{float:none;height:70px;padding:0px;}
		</style>
	</head>
	<body class="index-page" onload="window.print();">
		<div class="main-cont">
			<div class="body-padding">
				<div id="" class="container">
					<header class="main-header">
						<img  class="logo"src="<?php echo(DOMAIN_NAME_PATH);?>img/logo.png" border="0" alt="">
					</header>
					<section class="content-header">
						<h1>Lists Of Accounting</h1>
						<div>
							<b>Credit Balance - </b>
							<?php echo $find_agent_data['currency_code']." ".$find_agent_data['credit_balance'];?>
						</div>
					</section>
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
																	<th>Transaction Id</th>
																	<th>Debit</th>
																	<th>Credit</th>
																	<th>Closing Balance</th>
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
																	<td class=" "><?= $accounting_val['transaction_id'];?></td>
																	<td class=" ">
																	<?php
																	if($accounting_val['debit_or_credit']=="Debit"):
																	?>
																		<a style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" class="btn-danger"><?= $find_agent_data['currency_code']." ".$accounting_val['amount'];?></a>
																	<?php
																	else:
																		echo "--";
																	endif;
																	?>
																	</td>
																	<td class=" ">
																	<?php
																	if($accounting_val['debit_or_credit']=="Credit"):
																	?>
																		<a style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" class="btn-success"><?= $find_agent_data['currency_code']." ".$accounting_val['amount'];?></a>
																	<?php
																	else:
																		echo "--";
																	endif;
																	?>
																	</td>
																	<td class=" "><?= ($accounting_val['closing_balance']!="" ? $find_agent_data['currency_code']." ".$accounting_val['closing_balance'] : "--");?></td>
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
		<footer style="color:#fff;background:#000;padding:56px 0px 31px 0px;border-top:1px solid #000;">
			<div style="width:100%;margin: 0px auto;">
				<div style="padding:15px;">
					<div style="float:left;width:100%;">
						<div style="float:left;width:46%;padding:2%;">
							<div style="font-size: 20px;padding-bottom: 18px;color:#ffffff;">Get In Touch</div>
							<div style="font-size:13px;line-height:25px;color:#f7f7f7;">
								2, Ganesh Chandra Avenue, Commerce House, 1st floor, Kolkata 700013. India.
							</div>
						</div>
						<div style="float:left;width:46%;padding:2%;">
							<div style="font-size: 20px;padding-bottom: 18px;color:#ffffff;">CONTACT</div>
							<div style="font-size:13px;color:#f7f7f7;line-height:25px;">Telephones: +91 33 4032 8888</div>
							<div style="font-size:13px;color:#f7f7f7;line-height:25px;">E-mail: travel@tripmaxx.in</div>
						</div>
						<div style="clear:both"></div>
					</div>
					<div style="clear:both"></div>
				</div>
			</div>
			<div class="clear"></div>
		</footer>
		<footer class="main-footer text-center" style="padding-top:10px;">
			<div class="pull-right hidden-xs"></div>
			<strong>Copyright &copy; 2018 <a href="#">TRIPMAXX</a>. All rights reserved</strong>
		</footer>
	</body>
</html>