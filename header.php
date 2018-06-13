
	<header id="top">
		<div class="header-a">
			<div class="wrapper-padding">
				<div class="header-phone"><span><?php echo(isset($general_setting['contact_phone_number']) && $general_setting['contact_phone_number']!='' ? $general_setting['contact_phone_number']:"");?></span></div>
				<div class="header-account">
					<a href="#"><?php echo(isset($general_setting['contact_email_address']) && $general_setting['contact_email_address']!='' ?$general_setting['contact_email_address']:"");?></a>
				</div>
				<!-- <div class="header-social">
					<a href="#" class="social-twitter"></a>
					<a href="#" class="social-facebook"></a>
					<a href="#" class="social-vimeo"></a>
					<a href="#" class="social-pinterest"></a>
					<a href="#" class="social-instagram"></a>
				</div> -->
			</div>
			<div class="clear"></div>
		</div>
		<div class="header-b">
			<div class="mobile-menu">
				<nav>
					<ul>
						<li><a class="has-child" href="<?=DOMAIN_NAME_PATH?>">HOME</a></li>
						<li><a class="has-child" href="<?=DOMAIN_NAME_PATH."who-we-are"?>">WHO WE ARE</a></li>
						<li><a class="has-child" href="<?=DOMAIN_NAME_PATH."experience-destination"?>">EXPERIENCE DESTINATION</a></li>
						<li><a class="has-child" href="<?=DOMAIN_NAME_PATH."know-the-team"?>">KNOW THE TEAM</a></li>
						<li><a class="has-child" href="<?=DOMAIN_NAME_PATH."career"?>">CAREER</a></li>
						<li><a class="has-child" href="<?=DOMAIN_NAME_PATH."contact"?>">CONTACT</a></li>
					</ul>
				</nav>
			</div>
			<div class="wrapper-padding">
				<div class="header-logo">
				<?php
				if($general_setting['website_logo']!="" && file_exists(GENERAL_IMAGES.$general_setting['website_logo'])):
				?>
					<a href="<?=DOMAIN_NAME_PATH?>"><img alt="" src="<?=DOMAIN_NAME_PATH.GENERAL_IMAGES.$general_setting['website_logo']?>" /></a>
				<?php
				endif;
				?>
				</div>
				<div class="header-right">
					<a href="#" class="menu-btn"></a>
					<nav class="header-nav">
						<ul>
							<li><a href="<?=DOMAIN_NAME_PATH?>">HOME</a></li>
							<li><a href="<?=DOMAIN_NAME_PATH."who-we-are"?>">WHO WE ARE</a></li>
							<li><a href="<?=DOMAIN_NAME_PATH."experience-destination"?>">EXPERIENCE DESTINATION</a></li>
							<li><a href="<?=DOMAIN_NAME_PATH."know-the-team"?>">KNOW THE TEAM</a></li>
							<li><a href="<?=DOMAIN_NAME_PATH."career"?>">CAREER</a></li>
							<li><a href="<?=DOMAIN_NAME_PATH."contact"?>">CONTACT</a></li>
						</ul>
					</nav>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</header>