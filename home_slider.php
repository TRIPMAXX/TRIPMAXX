<?php
$home_sliders=array();
$autentication_data_home_slider=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.DMC_API_PATH."authorized.php"));
if(isset($autentication_data_home_slider->status)):
	if($autentication_data_home_slider->status=="success"):
		$post_data_home_slider['token']=array(
			"token"=>$autentication_data_home_slider->results->token,
			"token_timeout"=>$autentication_data_home_slider->results->token_timeout,
			"token_generation_time"=>$autentication_data_home_slider->results->token_generation_time
		);
		$post_home_slider_data_str=json_encode($post_data_home_slider);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.DMC_API_PATH."home_sliders/read.php");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_home_slider_data_str);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$return_data_home_slider = curl_exec($ch);
		curl_close($ch);
		$return_home_slider_data_arr=json_decode($return_data_home_slider, true);
		if(!isset($return_home_slider_data_arr['status'])):
			//$data['status'] = 'error';
			//$data['msg']="Some error has been occure during execution.";
		elseif($return_home_slider_data_arr['status']=="success"):
			//$data['status'] = 'success';
			//$data['msg']="Data received successfully";
			$home_sliders=$return_home_slider_data_arr['results'];
		else:
			//$data['status'] = 'error';
			//$data['msg'] = $return_home_slider_data_arr['msg'];
		endif;
	endif;
else:
	//$data['status'] = 'error';
	//$data['msg'] = $autentication_data_home_slider->msg;
endif;
?>
			<div class="mp-slider">
				<section class="home_page_slider">
					<div class="slider_txt">
						<div class="slider_txt_area">
							<div class="container">
								<div class="row rows">
									<div class="col-md-8 col-sm-6 ">
										<div class="banner_right_txt">
											<h2>HANDPICKED CHOICE OF PRODUCTS</h2>
											<div class="agent_btn">
												<button class="btn_styl_2 btn3" onclick="window.location.href='<?php echo(DOMAIN_NAME_PATH);?>agent_sign_up.php'"><span>AGENT SIGN UP</span> </button>
												<button class="btn_styl_2"><span>SUPPLIER SIGN UP</span></button>
											</div>
										</div>
									</div>
									<div class="col-md-4 col-sm-6">
										<div class="select_area">
											<h3 class="select_area_heading">Agent Partner Login</h3>
											<form name="agent_login" id="agent_login" method="POST">
												<div id="notify_msg_div"></div>
												<div class="select_box">
													<input type="text" class="styl1 validate[required]" id="code" name="code" placeholder="AGENT CODE" value="<?php echo(isset($_POST['code']) && $_POST['code']!='' ? $_POST['code'] : "");?>" tabindex="1">
													<input type="text" class="styl1 validate[required]" id="username" name="username" placeholder="YOUR USERNAME" value="<?php echo(isset($_POST['username']) && $_POST['username']!='' ? $_POST['username'] : "");?>" tabindex="2">
													<input type="password" class="styl1 validate[required]" id="password" name="password" placeholder="YOUR PASSWORD" value="" tabindex="3">
												</div>
												<p><a href="<?php echo DOMAIN_NAME_PATH."agent_forgot_password.php";?>" tabindex="4">Forget Password?</a></p>
												<div class="select_box_btn">
													<input type="hidden" name="token" value="<?php echo(tools::generateFormToken($verify_token)); ?>" />
													<button type="submit" class="btn_styl_3 select_area_btn" name="btn_login" tabindex="5">LOGIN</button>
												</div>
											</form> 
										</div>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="swiper-container" id="swiper-container1">
						<div class="swiper-wrapper">
						<?php 
							if(!empty($home_sliders)):
								foreach($home_sliders as $home_key => $home_slider):
									if($home_slider['slider_image']!="" && file_exists(GENERAL_IMAGES.$home_slider['slider_image'])):
						?>
							<div class="swiper-slide slide_height" style="background:url(<?php echo DOMAIN_NAME_PATH.GENERAL_IMAGES.$home_slider['slider_image'];?>)no-repeat center center / cover"> </div>
						<?php 
									endif;
								endforeach;
							endif;
						?>
						</div>
						<script>
							var swiper = new Swiper('#swiper-container1', {
								loop: true,
								paginationClickable: false,
								centeredSlides: true,
								autoplay: true,
								autoplay: 5000,
								autoplayDisableOnInteraction: false,
								slidesPerView: 1,
								spaceBetween:0,
								breakpoints: {
								}
							});
						</script> 
					</div>
				</section>
			</div>