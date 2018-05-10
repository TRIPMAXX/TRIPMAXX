<footer class="footer-a">
		<div class="wrapper-padding">
			<div class="container">
				<div class="row rows">
					<div class="col-md-6">
						<div class="row rows">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="section">
									<div class="footer-lbl">Get In Touch</div>
									<div class="footer-adress">
										2, Ganesh Chandra Avenue, Commerce House, 1st floor,<br>Kolkata 700013. India.
									</div>
								</div>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="section">
									<div class="footer-lbl">CONTACT</div>
									<div class="footer-phones">Telephones: +91 33 4032 8888</div>
									<div class="footer-email">E-mail: travel@tripmaxx.in</div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
					<div class="col-md-6">
						<section class="map_sec">
							<div id="map_canvas" class="google_map" style="position: relative; overflow: hidden;"></div>
							<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCqHRNCLFjL_2XipyaSdSswQUdoWPYU7Rs&amp;callback=initialize" async defer></script> 
							<script type="text/javascript">
							<!--
							/*google map*/
							var locations = [
							  ['<img src="image/user.png"  height="auto"  alt="" style="width:100% !important">', 'Acropolis Mall', 'USA', 'Gautam Talukdar', 'Go to office page in order to see all team members', '#', 'images/map1.png'],

							  
							];
							var geocoder;
							var map;
							//var bounds = new google.maps.LatLngBounds();

							function initialize() {
							  map = new google.maps.Map(
								document.getElementById("map_canvas"), {
								  center: new google.maps.LatLng(22.5726, 88.3639),
								  zoom: 10,
								  mapTypeId: google.maps.MapTypeId.ROADMAP
								});
							  geocoder = new google.maps.Geocoder();

							  for (i = 0; i < locations.length; i++) {
								geocodeAddress(locations, i);
							  }

							}

							//google.maps.event.addDomListener(window, "load", initialize);
							function geocodeAddress(locations, i) {
							  var img = locations[i][0];
							  var address = locations[i][2];
							  var name = locations[i][3];
							  var des = locations[i][4];
							  var url = locations[i][5];
							  var icon = locations[i][6];
							  geocoder.geocode({
								  'address': locations[i][1]
								},
								function(results, status) {
								  if (status == google.maps.GeocoderStatus.OK) {
									var marker = new google.maps.Marker({
									  icon: icon,
									  map: map,
									  position: results[0].geometry.location,
									  img: img,
									  animation: google.maps.Animation.DROP,
									  address: address,
									  url: url,
									  name:name,
									  des:des
									})
									infoWindow(marker, map, img, address, url, name, des);
									//bounds.extend(marker.getPosition());
									//map.fitBounds(bounds);
								  } else {
									alert("geocode of " + address + " failed:" + status);
								  }
								});

							}
							function infoWindow(marker, map, img, address, url, name, des) {
							  google.maps.event.addListener(marker, 'click', function() {
								var html = "<div style='width:290px;'><div style='border:1px solid #ccc; float:left; width:75px'>" + img + "</div><div style='border:0px solid red; float:right; width:205px'><p style='font-size:14px;color:red'>" + address + "</p><p style='font-weight:bold;'>" + name + "</p><p>" + des + "</p></div></div>";
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