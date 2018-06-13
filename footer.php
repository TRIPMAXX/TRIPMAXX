<?php
$logo_img='<img src="'.DOMAIN_NAME_PATH.GENERAL_IMAGES.$general_setting['website_logo'].'" style="width:70px;"/>';
?>
	<footer class="footer-a">
		<div class="wrapper-padding">
			<div class="container">
				<div class="row rows">
					<div class="col-md-6">
						<div class="row rows">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="section">
									<div class="footer-lbl">Get In Touch</div>
									<div class="footer-adress"><?=nl2br($general_setting['contact_address'])?></div>
								</div>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="section">
									<div class="footer-lbl">CONTACT</div>
									<div class="footer-phones"><?php echo(isset($general_setting['contact_phone_number']) && $general_setting['contact_phone_number']!='' ? "Telephones: ".$general_setting['contact_phone_number']:"");?></div>
									<div class="footer-email"><?php echo(isset($general_setting['contact_email_address']) && $general_setting['contact_email_address']!='' ? "E-mail: ".$general_setting['contact_email_address']:"");?></div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
					<div class="col-md-6">
						<section class="map_sec">
							<div id="map_canvas" class="google_map" style="position: relative; overflow: hidden;"></div>
							<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCqHRNCLFjL_2XipyaSdSswQUdoWPYU7Rs&callback=initialize"
							async defer></script> 
							<script type="text/javascript">
								<!--
								/*$(window).load(function(){
									initialize();
								});*/
								var loc_add='<?php echo preg_replace("/[\r\n]*/","",  str_replace("<br />"," ",$general_setting["contact_address"]));?>';
								var locations = [
									['<?php echo $logo_img;?>', loc_add, '', '', '', '#', 'assets/img/map.png']
								];
								var geocoder;
								var map;
								//var bounds = new google.maps.LatLngBounds();

								function initialize() {
									geocoder = new google.maps.Geocoder();

									for (i = 0; i < locations.length; i++) {
										geocodeAddress(locations, i);
									}
								}
								//google.maps.event.addDomListener(window, "load", initialize);
								function geocodeAddress(locations, i) {
									var img = locations[i][0];
									var address = locations[i][1];
									var name = locations[i][3];
									var des = locations[i][4];
									var url = locations[i][5];
									var icon = locations[i][6];
									geocoder.geocode({
											'address': locations[i][1]
										},
										function(results, status) {
											if (status == google.maps.GeocoderStatus.OK) {
												map = new google.maps.Map(
													document.getElementById("map_canvas"), {
														center: results[0].geometry.location,
														zoom: 12,
														scrollwheel:  false,
														mapTypeId: google.maps.MapTypeId.ROADMAP,
														/*styles: [
														  {
															"elementType": "geometry",
															"stylers": [
															  {
																"color": "#e5ecf0"
															  }
															]
														  },
														  {
															"elementType": "labels.icon",
															"stylers": [
															  {
																"visibility": "off"
															  }
															]
														  },
														  {
															"elementType": "labels.text.fill",
															"stylers": [
															  {
																"color": "#616161"
															  }
															]
														  },
														  {
															"elementType": "labels.text.stroke",
															"stylers": [
															  {
																"color": "#eae6f0"
															  }
															]
														  },
														  {
															"featureType": "administrative.land_parcel",
															"elementType": "labels.text.fill",
															"stylers": [
															  {
																"color": "#bdbdbd"
															  }
															]
														  },
														  {
															"featureType": "poi",
															"elementType": "geometry",
															"stylers": [
															  {
																"color": "#c8dae4"
															  }
															]
														  },
														  {
															"featureType": "poi",
															"elementType": "labels.text.fill",
															"stylers": [
															  {
																"color": "#757575"
															  }
															]
														  },
														  {
															"featureType": "poi.park",
															"elementType": "geometry",
															"stylers": [
															  {
																"color": "#c8dae4"
															  }
															]
														  },
														  {
															"featureType": "poi.park",
															"elementType": "labels.text.fill",
															"stylers": [
															  {
																"color": "#9e9e9e"
															  }
															]
														  },
														  {
															"featureType": "road",
															"elementType": "geometry",
															"stylers": [
															  {
																"color": "#ffffff"
															  }
															]
														  },
														  {
															"featureType": "road.arterial",
															"elementType": "labels.text.fill",
															"stylers": [
															  {
																"color": "#757575"
															  }
															]
														  },
														  {
															"featureType": "road.highway",
															"elementType": "geometry",
															"stylers": [
															  {
																"color": "#ffffff"
															  }
															]
														  },
														  {
															"featureType": "road.highway",
															"elementType": "labels.text.fill",
															"stylers": [
															  {
																"color": "#cac2d8"
															  }
															]
														  },
														  {
															"featureType": "road.local",
															"elementType": "labels.text.fill",
															"stylers": [
															  {
																"color": "#fbfbfb"
															  }
															]
														  },
														  {
															"featureType": "transit.line",
															"elementType": "geometry",
															"stylers": [
															  {
																"color": "#ffffff"
															  }
															]
														  },
														  {
															"featureType": "transit.station",
															"elementType": "geometry",
															"stylers": [
															  {
																"color": "#c8dae4"
															  }
															]
														  },
														  {
															"featureType": "water",
															"elementType": "geometry",
															"stylers": [
															  {
																"color": "#a3ddff"
															  }
															]
														  },
														  {
															"featureType": "water",
															"elementType": "labels.text.fill",
															"stylers": [
															  {
																"color": "#c9c6e4"
															  }
															]
														  }
														]*/
												});
												var marker = new google.maps.Marker({
													icon: icon,
													map: map,
													position: results[0].geometry.location,
													img: img,
													animation: google.maps.Animation.DROP,
													address: address,
													url: url,
													name: name,
													des: des
												})
												infoWindow(marker, map, img, address, url, name, des);
												//bounds.extend(marker.getPosition());
												//map.fitBounds(bounds);
											} else {
												//alert("geocode of " + address + " failed:" + status);
											}
										});
								}

								function infoWindow(marker, map, img, address, url, name, des) {
									google.maps.event.addListener(marker, 'click', function() {
										var html = "<div style='width:290px;background: #000;'><div style='border:1px solid #ccc; float:left; width:75px;padding: 2px'>" + img + "</div><div style='border:0px solid red; float:right; width:205px'><p style='font-size:12px;color:red'>" + address + "</p></div></div>";
										iw = new google.maps.InfoWindow({
											content: html,
											maxWidth: 350
										});
										iw.open(map, marker);
									});
								}
							//-->
							</script>
						</section>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</footer>
	<footer class="footer-b">
		<div class="wrapper-padding">
			<div class="footer-left" style = "text-align:center;">&copy; Copyright 2017 TripMaxx. All rights reserved.</div>
		</div>
	</footer> 
	<script>
	$(document).ready(function(){
		$('.date-inpt').datepicker();
		$('.custom-select').customSelect();
		$(function() {
			$(document.body).on('appear', '.fly-in', function(e, $affected) {
				$(this).addClass("appeared");
			});
			$('.fly-in').appear({force_process: true});
		});

		$(".owl-slider").owlCarousel({
			loop:true,
			margin:28,
			responsiveClass:true,
			responsive:{
		0:{
			items:1,
			nav:true
		},
		620:{
			items:2,
			nav:true
		},
		900:{
			items:3,
			nav:false
		},
		1120:{
			items:4,
			nav:true,
			loop:false
		}
	}
		});
		$slideHover();
	});
	</script>
<?php
if(isset($_SESSION['SET_FLASH']))
{
	if($_SESSION['SET_TYPE']=='error')
	{
		echo "<script type='text/javascript'>showError('".$_SESSION['SET_FLASH']."');</script>";
	}
	if($_SESSION['SET_TYPE']=='success')
	{
		echo "<script type='text/javascript'>showSuccess('".$_SESSION['SET_FLASH']."');</script>";
	}
}
unset($_SESSION['SET_FLASH']);
unset($_SESSION['SET_TYPE']);
$db=NULL;
?>