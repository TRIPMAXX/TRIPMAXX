<?php
	$find_agent_data = tools::find("first", TM_AGENT." as a, ".TM_COUNTRIES." as co, ".TM_STATES." as s, ".TM_CITIES." as ci, ".TM_CURRENCIES." as cu", 'a.*, co.name as co_name, s.name as s_name, ci.name as ci_name, cu.currency_code as currency_code, cu.currency_name as currency_name', "WHERE a.country=co.id AND a.state=s.id AND a.city=ci.id AND a.preferred_currency=cu.id AND a.id=:id ", array(":id"=>$_SESSION['AGENT_SESSION_DATA']['id']));
?>
				<div id="" class="container">
					<div class="mp-offesr">
						<div class="row rows">
							<div class="col-md-12 text-right">
								<div>
									<a href="<?php echo DOMAIN_NAME_PATH;?>dashboard.php">Dashboard</a>
									&nbsp;&nbsp;|&nbsp;&nbsp;
									<a href="<?php echo DOMAIN_NAME_PATH;?>logout.php">Logout</a>
								</div>
								<br/>
								<div>
									<b>Credit Balance - </b>
									<?php echo $find_agent_data['currency_code']." ".$find_agent_data['credit_balance'];?>
								</div>
							</div>
						</div>
						<div class="wrapper-padding-a">
							<div class="offer-slider">
								<div class="tabs_area">
									<ul>
										<a href="create_new_booking.html"><li class="first_tab tab_wrapper">
											<div class="tab_name_area">
											<h1>CREATE NEW BOOKING</h1>
											</div>
											
										</li></a>
										<a href="<?php echo DOMAIN_NAME_PATH;?>booking.php"><li class="second_tab tab_wrapper">
											<div class="tab_name_area">
											<h1>BOOKING HISTORY</h1>
											</div>
										</li></a>
										<a href="<?php echo DOMAIN_NAME_PATH;?>accounting.php"><li class="third_tab tab_wrapper">
											<div class="tab_name_area">
											<h1>ACCOUNTING</h1>
											</div>
										</li></a>
										<a href="<?php echo DOMAIN_NAME_PATH;?>agent_profile.php"><li class="fourth_tab tab_wrapper">
											<div class="tab_name_area">
											<h1>MANAGE PROFILE</h1>
											</div>
										</li></a>
										<?php
										if($find_agent_data['type']=="G"):
										?>
										<a href="<?php echo DOMAIN_NAME_PATH;?>sub_agent.php"><li class="fifth_tab tab_wrapper pad0">
											<div class="tab_name_area">
											<h1>MANAGE SUBAGENTS</h1></div>
										</li></a>
										<?php
										endif;
										?>
										<div class="clearfix"></div>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>