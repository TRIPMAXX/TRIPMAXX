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
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>LISTS OF ACCOUNTING PRINT</title>
	<link href="<?php echo(CONTROL_CENTER_CSS_PATH);?>bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo(CONTROL_CENTER_CSS_PATH);?>select2.css" rel="stylesheet" type="text/css" />
	<style type="text/css">
	@media screen, print
		.main-footer{margin-left: 0px;text-align:center;}
		.main-header{background:#FFF;height:auto;max-height:none;padding: 15px 0px;}
		.main-header .logo{float:none;height:auto;padding:0px;}
		.footer-a {
			color:#fff !important;
			background:#141d1e !important;
			padding:56px 0px 31px 0px;
		}
		.footer-a .section {
			width:281px;
			float:left;
			display:block;
			color:#fff !important;
		}
		.footer-a .section:last-child {
			float:right;
			width:254px;
			color:#fff !important;
		}
		.footer-lbl {
			font-size:14px;
			color:#ffffff;
			color:#ffffff;
			font-weight:normal;
			background:url(assets/img/foot-lbl.gif) left bottom no-repeat;
			padding:0px 0px 18px 0px;
			text-transform:uppercase;	
			color:#fff !important;
		}
		.footer-adress {
			font-size:13px;
			line-height:33px;
			color:#f7f7f7;
			background:url(assets/img/footer-icon-01.png) left top no-repeat;
			padding:0px 0px 0px 22px;
			background-position:left 9px;
			margin:0px 0px 9px 0px;
			color:#fff !important;
		}
		.footer-phones {
			font-size:13px;
			color:#f7f7f7;
			background:url(assets/img/footer-icon-02.png) left top no-repeat;
			padding:0px 0px 0px 22px;
			margin:0px 0px 17px 0px;
			background-position:left 1px;
			color:#fff !important;
		}
		.footer-phones a[href^=tel]{color:#fff; text-decoration:none;} 
		.footer-email {
			font-size:13px;
			color:#f7f7f7;
			margin:0px 0px 17px 0px;
			background:url(assets/img/footer-icon-03.png) left top no-repeat;
			padding:0px 0px 0px 22px;
			background-position:left 2px;
			color:#fff !important;
		}
		.footer-skype {
			font-size:13px;
			color:#f7f7f7;
			background:url(assets/img/footer-icon-04.png) left top no-repeat;
			padding:0px 0px 0px 22px;
			background-position:left 2px;
			color:#fff !important;
		}
	}
	</style>
</head>
<body class="skin-purple" onload="window.print();">
<div class="wrapper">
	<header class="main-header">
		<img  class="logo"src="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>assets/img/logo.png" border="0" alt="">
	</header>
	<!-- BODY --> 
	<div class="content-wrapper" style="margin-left: 0px;padding: 15px;">
		<section class="content-header">
			<h1>Lists Of Accounting</h1>
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
							</div>
							<div id="example1_wrapper" class="dataTables_wrapper form-inline" role="grid">
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
													<a style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" class="btn-danger"><?= $accounting_val['amount'];?></a>
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
													<a style="padding: 3px;border-radius: 2px;cursor:pointer;text-decoration:none" class="btn-success"><?= $accounting_val['amount'];?></a>
												<?php
												else:
													echo "--";
												endif;
												?>
												</td>
												<td class=" "><?= ($accounting_val['closing_balance']!="" ? $accounting_val['closing_balance'] : "--");?></td>
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
				</section>
			</div>
		</section>
	</div>
	<!-- BODY --> 
	<!-- FOOTER -->
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
	<!-- FOOTER -->
      </div>
   </body>
</html>