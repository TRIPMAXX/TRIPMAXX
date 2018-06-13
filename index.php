<?php
require_once('loader.inc');
$white_list_array = array('username', 'password', 'code', 'token', 'btn_login');
$verify_token = "front_login";
if(isset($_SESSION['AGENT_SESSION_DATA']) && !empty($_SESSION['AGENT_SESSION_DATA']))
{
	$_SESSION['SET_TYPE'] = 'error';
	$_SESSION['SET_FLASH'] = 'You are already logged in.';
	header("location:dashboard.php");
	exit;
}
if(isset($_POST['btn_login']))
{
	$object_control_center = new front_control();
	if($object_control_center->front_control_login()) {
		if(tools::verify_token($white_list_array, $_POST, $verify_token)) {
				//print_r($_SESSION['AGENT_SESSION_DATA']);exit;
			if($_SESSION['AGENT_SESSION_DATA']['status'] == 1) {
				header("location:dashboard.php");
			} else {
				unset($_SESSION['AGENT_SESSION_DATA']);
				$_SESSION['SET_TYPE'] = 'error';
				$_SESSION['SET_FLASH'] = 'Your account is inactive. Please contact DMC.';
			}
		} else {
			$_SESSION['SET_TYPE'] = 'error';
			$_SESSION['SET_FLASH'] = 'Access token mismatch. Please reload the page & try again.';
		}
	} else {
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = 'Invalid Username or Password.';
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo DEFAULT_PAGE_TITLE ;?> Destination Management Company</title>	
<?php require_once('meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	$(function(){
		$("#agent_login").validationEngine();
	});
	</script>
	<!-- JAVASCRIPT CODE -->
</head>
<body class="index-page">
	<!-- TOP HEADER -->
	<?php require_once('header.php');?>		
	<!-- TOP HEADER -->
	<div class="main-cont">
		<div class="body-padding">
			<?php require_once('home_slider.php');?>	
			<!-- <div class="mp-offesr">
				<div class="wrapper-padding-a" >
					<div class="offer-slider">
						<header class="fly-in page-lbl">
							<div class="offer-slider-lbl">Our Exciting Offers On Hotel Bookings</div>
							<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
						</header>
						<div class="fly-in offer-slider-c">
							<div id="offers" class="owl-slider"> 
								<div class="offer-slider-i">
									<a class="offer-slider-img" href="#"><img alt="" src="img/slide-01.jpg" /> <span class="offer-slider-overlay"></span> </a>
									<div class="offer-slider-txt">
										<div class="offer-slider-link"><a href="#">Andrassy Thai Hotel</a></div>
										<div class="offer-slider-l">
											<div class="offer-slider-location">Location: Thailand </div>
											<nav class="stars">
												<ul>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-a.png" /></a></li>
												</ul>
												<div class="clear"></div>
											</nav>
										</div>
										<div class="offer-slider-r"> <b>756$</b> <span>avg/night</span> </div>
										<div class="offer-slider-devider"></div>
										<div class="clear"></div>
									</div>
								</div>
								<div class="offer-slider-i">
									<a class="offer-slider-img" href="#"> <img alt="" src="img/slide-02.jpg" /> <span class="offer-slider-overlay"></span> </a>
									<div class="offer-slider-txt">
										<div class="offer-slider-link"><a href="#">Campanile Cracovie</a></div>
										<div class="offer-slider-l">
											<div class="offer-slider-location">location: poland</div>
											<nav class="stars">
												<ul>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-a.png" /></a></li>
												</ul>
												<div class="clear"></div>
											</nav>
										</div>
										<div class="offer-slider-r"> <b>900$</b> <span>avg/night</span> </div>
										<div class="offer-slider-devider"></div>
										<div class="clear"></div>
									</div>
								</div>
								<div class="offer-slider-i">
									<a class="offer-slider-img" href="#"> <img alt="" src="img/slide-03.jpg" /> <span class="offer-slider-overlay"></span> </a>
									<div class="offer-slider-txt">
										<div class="offer-slider-link"><a href="#">Park Plaza Westminster</a></div>
										<div class="offer-slider-l">
											<div class="offer-slider-location">Location: Thailand </div>
											<nav class="stars">
												<ul>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
												</ul>
												<div class="clear"></div>
											</nav>
										</div>
										<div class="offer-slider-r"> <b>850$</b> <span>avg/night</span> </div>
										<div class="offer-slider-devider"></div>
										<div class="clear"></div>
									</div>
								</div>
								<div class="offer-slider-i">
									<a class="offer-slider-img" href="#"> <img alt="" src="img/slide-04.jpg" /> <span class="offer-slider-overlay"></span> </a>
									<div class="offer-slider-txt">
										<div class="offer-slider-link"><a href="#">Ermin's Hotel</a></div>
										<div class="offer-slider-l">
											<div class="offer-slider-location">location: england</div>
											<nav class="stars">
												<ul>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-a.png" /></a></li>
												</ul>
												<div class="clear"></div>
											</nav>
										</div>
										<div class="offer-slider-r"> <b>630$</b> <span>avg/night</span> </div>
										<div class="offer-slider-devider"></div>
										<div class="clear"></div>
									</div>
								</div>
								<div class="offer-slider-i">
									<a class="offer-slider-img" href="#"> <img alt="" src="img/slide-01.jpg" /> <span class="offer-slider-overlay"></span> </a>
									<div class="offer-slider-txt">
										<div class="offer-slider-link"><a href="#">Andrassy Thai Hotel</a></div>
										<div class="offer-slider-l">
											<div class="offer-slider-location">Location: Thailand </div>
											<nav class="stars">
												<ul>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-a.png" /></a></li>
												</ul>
												<div class="clear"></div>
											</nav>
										</div>
										<div class="offer-slider-r"> <b>756$</b> <span>avg/night</span> </div>
										<div class="offer-slider-devider"></div>
										<div class="clear"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="mp-offesr">
				<div class="wrapper-padding-a" >
					<div class="offer-slider">
						<header class="fly-in page-lbl">
							<div class="offer-slider-lbl">OUR FEATURE TOURS</div>
							<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
						</header>
						<div class="fly-in offer-slider-c">
							<div id="offers-a" class="owl-slider"> 
								<div class="offer-slider-i">
									<a class="offer-slider-img" href="#"> <img alt="" src="img/slide-05.jpg" /> <span class="offer-slider-overlay"></span> </a>
									<div class="offer-slider-txt">
										<div class="offer-slider-link"><a href="#">Paris, france</a></div>
										<div class="offer-slider-l">
											<div class="offer-slider-location">11 NOV 2014 - 22 NOV 2014</div>
											<nav class="stars">
												<ul>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-a.png" /></a></li>
												</ul>
												<div class="clear"></div>
											</nav>
										</div>
										<div class="offer-slider-r align-right"> <b>1200$</b> <span>price</span> </div>
										<div class="offer-slider-devider"></div>
										<div class="clear"></div>
									</div>
								</div>
								<div class="offer-slider-i">
									<a class="offer-slider-img" href="#"> <img alt="" src="img/slide-06.jpg" /> <span class="offer-slider-overlay"></span> </a>
									<div class="offer-slider-txt">
										<div class="offer-slider-link"><a href="#">pattaya, thailand</a></div>
										<div class="offer-slider-l">
											<div class="offer-slider-location">11 NOV 2014 - 22 NOV 2014</div>
											<nav class="stars">
												<ul>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-a.png" /></a></li>
												</ul>
												<div class="clear"></div>
											</nav>
										</div>
										<div class="offer-slider-r"> <b>2200$</b> <span>price</span> </div>
										<div class="offer-slider-devider"></div>
										<div class="clear"></div>
									</div>
								</div>
								<div class="offer-slider-i">
									<a class="offer-slider-img" href="#"> <img alt="" src="img/slide-07.jpg" /> <span class="offer-slider-overlay"></span> </a>
									<div class="offer-slider-txt">
										<div class="offer-slider-link"><a href="#">london, england</a></div>
										<div class="offer-slider-l">
											<div class="offer-slider-location">11 NOV 2014 - 22 NOV 2014</div>
											<nav class="stars">
												<ul>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
												</ul>
												<div class="clear"></div>
											</nav>
										</div>
										<div class="offer-slider-r"> <b>1900$</b> <span>price</span> </div>
										<div class="offer-slider-devider"></div>
										<div class="clear"></div>
									</div>
								</div>
								<div class="offer-slider-i">
									<a class="offer-slider-img" href="#"> <img alt="" src="img/slide-08.jpg" /> <span class="offer-slider-overlay"></span> </a>
									<div class="offer-slider-txt">
										<div class="offer-slider-link"><a href="#">san francisco, usa</a></div>
										<div class="offer-slider-l">
											<div class="offer-slider-location">11 NOV 2014 - 22 NOV 2014</div>
											<nav class="stars">
												<ul>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-b.png" /></a></li>
													<li><a href="#"><img alt="" src="img/star-a.png" /></a></li>
												</ul>
												<div class="clear"></div>
											</nav>
										</div>
										<div class="offer-slider-r"> <b>3500$</b> <span>price</span> </div>
										<div class="offer-slider-devider"></div>
										<div class="clear"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div> -->
		</div>
	</div>
	<!-- FOOTER -->
	<?php require_once('footer.php');?>
	<!-- FOOTER -->
</body>
</html>