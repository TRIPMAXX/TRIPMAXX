<aside class="main-sidebar">
	<section class="sidebar">
		<ul class="sidebar-menu">
			<li class="<?php echo($current_page_name == 'dashboard.php' ? 'active' : '');?>">
				<a href="<?php echo(DOMAIN_NAME_PATH_HOTEL);?>dashboard">
				<span>DASHBOARD</span>
				</a>
			</li>
			<li class="<?php echo(($current_page_name == 'create_new_booking.php' || $current_page_name == 'bookings.php' || $current_page_name == 'edit_booking.php') ? 'active' : '');?>">
				<a href="<?php echo(DOMAIN_NAME_PATH_HOTEL);?>bookings">
				<span>LISTS OF BOOKINGS</span>
				<i class="fa fa-angle-left pull-right"></i>
				</a>
			</li>
			<li class="<?php echo(($current_page_name == 'create_new_attribute.php' || $current_page_name == 'attributes.php' || $current_page_name == 'edit_attribute.php' || $current_page_name == 'create_new_hotel.php' || $current_page_name == 'hotels.php' || $current_page_name == 'edit_hotel.php' || $current_page_name == 'rooms.php' || $current_page_name == 'create_new_room.php' || $current_page_name == 'edit_room.php' || $current_page_name == 'view_hotel_details.php') ? 'active' : '');?>">
				<a href="javascript:void(0);">
				<span>MANAGE HOTELS</span>
				<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li class=""><a href="<?php echo(DOMAIN_NAME_PATH_HOTEL);?>edit_hotel"><i class="fa fa-circle-o"></i> EDIT HOTEL</a></li>
					<li class=""><a href="<?php echo(DOMAIN_NAME_PATH_HOTEL);?>create_new_room"><i class="fa fa-circle-o"></i> CREATE NEW ROOM</a></li>
					<li class=""><a href="<?php echo(DOMAIN_NAME_PATH_HOTEL);?>rooms"><i class="fa fa-circle-o"></i> LISTS OF ROOMS</a></li>
				</ul>
			</li>
			<li class="<?php echo($current_page_name == 'reports.php' ? 'active' : '');?>">
				<a href="<?php echo(DOMAIN_NAME_PATH_HOTEL);?>reports">
				<span>REPORT</span>
				</a>
			</li>
		</ul>
	</section>
</aside>