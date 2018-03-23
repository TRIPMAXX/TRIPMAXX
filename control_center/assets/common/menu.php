<aside class="main-sidebar">
	<section class="sidebar">
		<ul class="sidebar-menu">
			<li class="<?php echo($current_page_name == 'dashboard.php' ? 'active' : '');?>">
				<a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard">
				<span>DASHBOARD</span>
				</a>
			</li>
			<li class="<?php echo(($current_page_name == 'create_new_booking.php' || $current_page_name == 'bookings.php' || $current_page_name == 'edit_booking.php') ? 'active' : '');?>">
				<a href="javascript:void(0);">
				<span>MANAGE BOOKINGS</span>
				<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
				 <li class=""><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>create_new_booking"><i class="fa fa-circle-o"></i> CREATE NEW</a></li> 
				<li class=""><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>bookings"><i class="fa fa-circle-o"></i> LISTS OF BOOKINGS</a></li>
				</ul>
			</li>
			<li class="">
				<a href="javascript:void(0);">
				<span>MANAGE PACKAGES</span>
				<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li class=""><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>create_new_package"><i class="fa fa-circle-o"></i> CREATE NEW</a></li>
					<li class=""><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>packages"><i class="fa fa-circle-o"></i> LISTS OF PACKAGES</a></li>
					<li class=""><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>create_new_combo_package"><i class="fa fa-circle-o"></i> CREATE NEW COMBO</a></li>
					<li class=""><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>combo_packages"><i class="fa fa-circle-o"></i> LISTS OF COMBO</a></li>
				</ul>
			</li>
			<li class="<?php echo(($current_page_name == 'create_new_tour_attribute.php' || $current_page_name == 'tour_attributes.php' || $current_page_name == 'edit_tour_attribute.php' || $current_page_name == 'create_new_tour.php' || $current_page_name == 'tours.php' || $current_page_name == 'edit_tour.php' || $current_page_name == 'tour_offers.php' || $current_page_name == 'create_new_tour_offer.php' || $current_page_name == 'edit_tour_offer.php') ? 'active' : '');?>">
				<a href="javascript:void(0);">
				<span>MANAGE TOURS</span>
				<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li class=""><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>create_new_tour_attribute"><i class="fa fa-circle-o"></i> CREATE ATTRIBUTES</a></li>
					<li class=""><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>tour_attributes"><i class="fa fa-circle-o"></i> LISTS OF ATTRIBUTES</a></li>
					<li class=""><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>create_new_tour"><i class="fa fa-circle-o"></i> CREATE NEW TOUR</a></li>
					<li class=""><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>tours"><i class="fa fa-circle-o"></i> LISTS OF TOURS</a></li>
				</ul>
			</li>
			<li class="<?php echo(($current_page_name == 'create_new_transfer_attribute.php' || $current_page_name == 'transfer_attributes.php' || $current_page_name == 'edit_transfer_attribute.php' || $current_page_name == 'create_new_transfer.php' || $current_page_name == 'transfers.php' || $current_page_name == 'edit_transfer.php' || $current_page_name == 'transfer_offers.php' || $current_page_name == 'create_new_transfer_offer.php' || $current_page_name == 'edit_transfer_offer.php') ? 'active' : '');?>">
				<a href="javascript:void(0);">
				<span>MANAGE TRANSFERS</span>
				<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li class=""><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>create_new_transfer_attribute"><i class="fa fa-circle-o"></i> CREATE ATTRIBUTES</a></li>
					<li class=""><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>transfer_attributes"><i class="fa fa-circle-o"></i> LISTS OF ATTRIBUTES</a></li>
					<li class=""><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>create_new_transfer"><i class="fa fa-circle-o"></i> CREATE NEW TRANSFER</a></li>
					<li class=""><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>transfers"><i class="fa fa-circle-o"></i> LISTS OF TRANSFERS</a></li>
				</ul>
			</li>
			<li class="<?php echo(($current_page_name == 'create_new_attribute.php' || $current_page_name == 'attributes.php' || $current_page_name == 'edit_attribute.php' || $current_page_name == 'create_new_hotel.php' || $current_page_name == 'hotels.php' || $current_page_name == 'edit_hotel.php' || $current_page_name == 'rooms.php' || $current_page_name == 'create_new_room.php' || $current_page_name == 'edit_room.php' || $current_page_name == 'view_hotel_details.php') ? 'active' : '');?>">
				<a href="javascript:void(0);">
				<span>MANAGE HOTELS</span>
				<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li class=""><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>create_new_attribute"><i class="fa fa-circle-o"></i> CREATE ATTRIBUTES</a></li>
					<li class=""><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>attributes"><i class="fa fa-circle-o"></i> LISTS OF ATTRIBUTES</a></li>
					<li class=""><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>create_new_hotel"><i class="fa fa-circle-o"></i> CREATE NEW HOTELS</a></li>
					<li class=""><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>hotels"><i class="fa fa-circle-o"></i> LISTS OF HOTELS</a></li>
				</ul>
			</li>
			<li class="<?php echo(($current_page_name == 'create_new_employee.php' || $current_page_name == 'employees.php' || $current_page_name == 'edit_employee.php') ? 'active' : '');?>">
				<a href="javascript:void(0);">
				<span>MANAGE EMPLOYEE </span>
				<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li class=""><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>create_new_employee"><i class="fa fa-circle-o"></i> CREATE NEW</a></li>
					<li class=""><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>employees"><i class="fa fa-circle-o"></i> LISTS OF EMPLOYEE</a></li>
				</ul>
			</li>
			<li class="<?php echo(($current_page_name == 'create_new_gsa.php' || $current_page_name == 'gsas.php' || $current_page_name == 'edit_gsa.php' || $current_page_name == 'sub_agents.php') ? 'active' : '');?>">
				<a href="javascript:void(0);">
				<span>MANAGE GSA</span>
				<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li class=""><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>create_new_gsa"><i class="fa fa-circle-o"></i> CREATE NEW</a></li>
					<li class=""><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>gsas"><i class="fa fa-circle-o"></i> LISTS OF GSA</a></li>
				</ul>
			</li>
			<li class="<?php echo(($current_page_name == 'create_new_agent.php' || $current_page_name == 'agents.php' || $current_page_name == 'edit_agent.php') ? 'active' : '');?>">
				<a href="javascript:void(0);">
				<span>MANAGE AGENT</span>
				<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li class=""><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>create_new_agent"><i class="fa fa-circle-o"></i> CREATE NEW</a></li>
					<li class=""><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>agents"><i class="fa fa-circle-o"></i> LISTS OF AGENT</a></li>
				</ul>
			</li>
			<li class="<?php echo(($current_page_name == 'create_new_supplier.php' || $current_page_name == 'supplier.php' || $current_page_name == 'edit_supplier.php') ? 'active' : '');?>">
				<a href="javascript:void(0);">
				<span>MANAGE SUPPLIER</span>
				<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li class=""><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>create_new_supplier"><i class="fa fa-circle-o"></i> CREATE NEW</a></li>
					<li class=""><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>supplier"><i class="fa fa-circle-o"></i> LISTS OF SUPPLIER</a></li>
				</ul>
			</li>
			<li class="<?php echo(($current_page_name == 'general_settings.php' || $current_page_name == 'currencies.php' || $current_page_name == 'create_new_currency.php' || $current_page_name == 'edit_currencies.php' || $current_page_name == 'home_sliders.php' || $current_page_name == 'create_new_slider.php' || $current_page_name == 'edit_home_slider.php') ? 'active' : '');?>">
				<a href="javascript:void(0);">
				<span>MANAGE SETTINGS</span>
				<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li class=""><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>general_settings"><i class="fa fa-circle-o"></i> GENERAL SETTINGS</a></li>
					<li class=""><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>currencies"><i class="fa fa-circle-o"></i> MANAGE CURRENCIES</a></li>
					<li class=""><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>home_sliders"><i class="fa fa-circle-o"></i> HOME SLIDER</a></li>
				</ul>
			</li>
			<li class="<?php echo(($current_page_name == 'cms.php' || $current_page_name == 'edit_cms.php') ? 'active' : '');?>">
				<a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>cms">
				<span>MANAGE CMS</span>
				</a>
			</li>
			<li class="<?php echo(($current_page_name == 'email_templates.php' || $current_page_name == 'edit_email_template.php') ? 'active' : '');?>">
				<a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>email_templates">
				<span>EMAIL TEMPLATES</span>
				</a>
			</li>
			<li class="<?php echo(($current_page_name == 'reports.php') ? 'active' : '');?>">
				<a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>reports">
				<span>REPORT</span>
				</a>
			</li>
			<li class="<?php echo(($current_page_name == 'create_new_offer.php' || $current_page_name == 'offers.php' || $current_page_name == 'edit_offer.php') ? 'active' : '');?>">
				<a href="javascript:void(0);">
				<span>PROMOTIONAL OFFERS</span>
				<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li class=""><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>create_new_offer"><i class="fa fa-circle-o"></i> CREATE NEW</a></li>
					<li class=""><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>offers"><i class="fa fa-circle-o"></i> LISTS OF OFFERS</a></li>
				</ul>
			</li>
		</ul>
	</section>
</aside>