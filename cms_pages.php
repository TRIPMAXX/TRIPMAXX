
	<div class="main-cont">
		<div class="body-padding">
			<?php
			if($cms_pages['page_banner_image']!="" && file_exists(CMS_BANNER.$cms_pages['page_banner_image'])):
			?>
			<div class="banner_slider" style="background:url(<?php echo DOMAIN_NAME_PATH.CMS_BANNER.$cms_pages['page_banner_image'];?>)no-repeat center center/cover;">
				<div class="banner_slider_text" style="text-transform: uppercase;">
					<?=$cms_pages['page_heading']?>
				</div>
			</div>
			<?php
			endif;
			?>
			<div id="" class="container">
				<div class="mp-offesr">
					<div class="wrapper-padding-a">
						<div class="offer-slider">
							<header class="fly-in res_padding">
								<div class="offer-slider-lbl"><?=$cms_pages['page_title']?></div>
								<?=$cms_pages['page_description']?>
							</header>
							<div class="fly-in offer-slider-c">
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>