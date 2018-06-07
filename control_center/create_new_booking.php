<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
	unset($_SESSION['step_1']);
	unset($_SESSION['step_2']);
	unset($_SESSION['step_3']);
	unset($_SESSION['step_3_all']);
	unset($_SESSION['step_4']);
	unset($_SESSION['step_4_all']);
	unset($_SESSION['step_5']);
	$autentication_data=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."authorized.php"));
	if(isset($autentication_data->status)):
		if($autentication_data->status=="success"):
			$post_data['token']=array(
				"token"=>$autentication_data->results->token,
				"token_timeout"=>$autentication_data->results->token_timeout,
				"token_generation_time"=>$autentication_data->results->token_generation_time
			);
			$post_data['data']['status']=1;
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
			$agent_list=array();
			if($return_data_arr['status']=="success"):
				$agent_list=$return_data_arr['results'];
			//else:
			//	$_SESSION['SET_TYPE'] = 'error';
			//	$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
			endif;
			if(isset($_GET['agent_id']) && $_GET['agent_id']!=""):
				$post_data['data']['agent_id']=base64_decode($_GET['agent_id']);
				$post_data_str=json_encode($post_data);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."agent/booking-agent.php");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				$return_data = curl_exec($ch);
				curl_close($ch);
				$return_data_arr=json_decode($return_data, true);
				$agent_data=array();
				if(!isset($return_data_arr['status'])):
					//$_SESSION['SET_TYPE'] = 'error';
					//$_SESSION['SET_FLASH']="Some error has been occure during execution.";
				elseif($return_data_arr['status']=="success"):
					$agent_data=$return_data_arr['results'];
				else:
					//$_SESSION['SET_TYPE'] = 'error';
					//$_SESSION['SET_FLASH'] = $return_data_arr['msg'];
				endif;
			endif;
		else:
			$_SESSION['SET_TYPE'] = 'error';
			$_SESSION['SET_FLASH'] = $autentication_data->msg;
		endif;
	else:
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = "We are having some problem to authorize api.";
	endif;
	$contry_list = tools::find("all", TM_COUNTRIES, '*', "WHERE :all ORDER BY name ASC", array(":all"=>1));
	$currency_list = tools::find("all", TM_CURRENCIES, '*', "WHERE status=:status ORDER BY serial_number ASC", array(":status"=>1));
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>CREATE NEW BOOKING</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<script src="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>assets/raty/jquery.raty.js" type="text/javascript"></script>
	<script src="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>assets/js/bootstrap-toggle.min.js" type="text/javascript"></script>
    <link href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>assets/css/clocks.css" type="text/css" rel="stylesheet">
    <link href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>assets/css/bootstrap-toggle.css" type="text/css" rel="stylesheet">
	<style type="text/css">
		.hide_age_div, .each_hotel_tab_content, .each_tour_tab_content, .each_transfer_tab_content{display:none;}
		.active_each_tab_content{display:block;}
		.loader_inner{
			position: fixed;
			z-index: 999999;
			width: 100%;
			height: 100%;
			text-align: center;
			display:none;
		}
		.loader_inner img{
			margin-top:40vh;
		}
		.city_tab_button_div, .tour_city_tab_button_div, .transfer_city_tab_button_div{margin-bottom: 5px;}
		.cls_each_city_hotel_tab_div, .cls_each_city_tour_tab_div, .cls_each_city_transfer_tab_div{
			padding: 5px;
			text-align: center;
			border-style:solid;
			border-color:rgba(255, 0, 0, 0.32);
			border-width:1px;
			/*background: #868484;*/
			color: #3c8dbc;
			font-size: 18px;
			cursor:pointer;
			border-top-width:0px;
		}
		.cls_each_city_tab_div_active{
			/*background: #5bc0de;*/
			border-top-width:1px;
			color: #000;
			border-bottom-width:0px;
		}
		.form-group {
			margin-bottom: 4px;
			padding: 0px 6px;
		}
		.wizard .tab-pane {
			padding-top: 10px;
		}
		.wizard h3{
			font-size: 18px;
		}
	</style>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	$(function(){
		//$("#form_first_step").validationEngine();
		$('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
			var $target = $(e.target);
			if ($target.parent().hasClass('disabled')) {
				return false;
			}
		});
		$("#form_first_step").submit(function(){
			//alert($(this).find("input[name='hotel_ratings[0][]']").length);
			if($("#form_first_step").validationEngine("validate"))
			{
				$("#agent_name").attr("disabled", false);
				$.ajax({
					url:'<?= DOMAIN_NAME_PATH_ADMIN."ajax_booking_step1_execute";?>',
					type:"post",
					data:$("#form_first_step").serialize(),
					beforeSend:function(){
						$(".loader_inner").fadeIn();
					},
					dataType:"json",
					success:function(response){
						//console.log(response);
						if($("#booking_type").val()=="personal" || '<?php echo(isset($_GET['agent_id']) && $_GET['agent_id']!="" ? $_GET['agent_id'] : "");?>'!="")
							$("#agent_name").attr("disabled", true);
						if(response.status=="success")
						{
							$(".nav-tabs li:eq(0)").addClass("completed_booking_step");
							$(".nav-tabs li:gt(0)").removeClass("completed_booking_step");
							var page=1;
							var type=1;
							fetch_step2_rcd(page, type);
							var $active = $('.wizard .nav-tabs li.active');
							$active.next().removeClass('disabled');
							$active.next().find('a[data-toggle="tab"]').click();
						}
						else
						{
							showError(response.msg);
						}
						$(".loader_inner").fadeOut();
					},
					error:function(){
						//showError("We are having some problem. Please try later.");
					}
				});
			}
			return false;
		});
		$("#checkin").datepicker({
			dateFormat: 'dd/mm/yy',
			minDate:0,
			onSelect:function(selectedDate){
				$("#checkout").datepicker( "option", "minDate", selectedDate);
				if($("#checkin").val()!="" && $("#checkout").val()!="")
				{
					//$("#number_of_night0").val(datediff($("#checkin").val(), $("#checkout").val())+1);
					match_night();
				}
			}
		});
		$("#checkout").datepicker({
			dateFormat: 'dd/mm/yy',
			minDate:0,
			onSelect:function(selectedDate){
				$("#checkin").datepicker( "option", "maxDate", selectedDate);
				if($("#checkin").val()!="" && $("#checkout").val()!="")
				{
					//$("#number_of_night0").val(datediff($("#checkin").val(), $("#checkout").val())+1);
					match_night();
				}
			}
		});
		$("#rooms").change(function(){
			var adult_child_html='';
			for(var i=0;i<$("#rooms").val();i++)
			{
				adult_child_html+='<div class="row each_adult_child_div">';
					adult_child_html+='<div class="form-group col-md-3">';
						adult_child_html+='<label for="inputName" class="control-label">Adult<font color="#FF0000">*</font></label>';
						adult_child_html+='<select class="form-control validate[required]" name="adult['+i+']" id="adult'+i+'">';
						<?php
						for($adult_no=1;$adult_no<=MAX_ADULT_NO;$adult_no++)
						{
						?>
							adult_child_html+='<option label="<?php echo $adult_no;?>" value="<?php echo $adult_no;?>"><?php echo $adult_no;?></option>';
						<?php
						}
						?>
						adult_child_html+='</select>';
					adult_child_html+='</div>';
					adult_child_html+='<div class="form-group col-md-3">';
						adult_child_html+='<label for="inputName" class="control-label">Child</label>';
						adult_child_html+='<select class="form-control validate[optional]" name="child['+i+']" id="child'+i+'" onchange = "child_attribute($(this),'+i+');"> ';
							adult_child_html+='<option value="">Select</option>';
						<?php
						for($child_no=1;$child_no<=MAX_CHILD_NO;$child_no++)
						{
						?>
							adult_child_html+='<option label="<?php echo $child_no;?>" value="<?php echo $child_no;?>"><?php echo $child_no;?></option>';
						<?php
						}
						?>
						adult_child_html+='</select>';
					adult_child_html+='</div>';
					adult_child_html+='<div class="col-md-6 hide_age_div all_child_age_div'+i+'">';
					adult_child_html+='</div>';
					adult_child_html+='<div class="clearfix"></div>';
				adult_child_html+='</div>';
			}
			$(".all_adult_child_div").html(adult_child_html);
			$('select').select2();
		});
		$(".save_step2_data").click(function(){
			if($('.each_hotel_tab_content').length != $('input[class="selected_room"]:checked').length)
			{
				showError("Please select hotel for all the cities.");
			}
			else
			{
				var hotel_room_arr=[];
				$('input[class="selected_room"]:checked').each(function(){
					hotel_room_arr.push($(this).val());
				});
				$.ajax({
					url:'<?= DOMAIN_NAME_PATH_ADMIN."ajax_booking_step2_execute";?>',
					type:"post",
					data:{
						hotel_room_arr:hotel_room_arr
					},
					beforeSend:function(){
						$(".loader_inner").fadeIn();
					},
					dataType:"json",
					success:function(response){
						//console.log(response);
						//console.log(JSON.stringify(response, null, 4));
						if(response.status=="success")
						{
							var page=1;
							var type=1;
							//fetch_step4_rcd(page, type);
							fetch_new_step4_rcd(page, type);
							var $active = $('.wizard .nav-tabs li.active');
							$active.next().removeClass('disabled');
							$active.next().find('a[data-toggle="tab"]').click();
							$(".nav-tabs li:eq(1)").addClass("completed_booking_step");
							$(".nav-tabs li:gt(1)").removeClass("completed_booking_step").removeClass("uncompleted_booking_step");
						}
						else
						{
							showError(response.msg);
						}
						$(".loader_inner").fadeOut();
					},
					error:function(){
						//showError("We are having some problem. Please try later.");
					}
				});
			}
		});
		$(".save_step3_data").click(function(){
			var tour_offer_arr=[];
			var tour_offer_city_arr=[];
			var tour_type_arr=[];
			var tour_pickuptime_arr=[];
			var tour_dropofftime_arr=[];
			var tour_booking_tour_date_arr=[];
			var tour_service_type_arr=[];
			$('input[class="selected_tour"]:checked').each(function(){
				if($(this).val()!="" && $(this).parents(".each_tour_row_outer").find(".selected_booking_tour_date").val()!="" && $(this).parents(".each_tour_row_outer").find(".tour_type").val()!="" && $(this).parents(".each_tour_row_outer").find(".selected_service_type").val()!="" && $(this).parents(".each_tour_row_outer").find(".pickuptime").val()!="" && $(this).parents(".each_tour_row_outer").find(".dropofftime").val()!="")
				{
					tour_offer_arr.push($(this).val());
					tour_type_arr.push($(this).parents(".each_tour_row_outer").find(".tour_type").val());
					tour_booking_tour_date_arr.push($(this).parents(".each_tour_row_outer").find(".selected_booking_tour_date").val());
					tour_service_type_arr.push($(this).parents(".each_tour_row_outer").find(".selected_service_type").val());
					tour_pickuptime_arr.push($(this).parents(".each_tour_row_outer").find(".pickuptime").val());
					tour_dropofftime_arr.push($(this).parents(".each_tour_row_outer").find(".dropofftime").val());
					var city_id=$(this).val().split("-");
					if($.inArray(city_id[0], tour_offer_city_arr) < 0)
						tour_offer_city_arr.push(city_id[0]);
				}
			});
			$.ajax({
				url:'<?= DOMAIN_NAME_PATH_ADMIN."ajax_booking_step3_execute";?>',
				type:"post",
				data:{
					tour_offer_arr:tour_offer_arr,
					tour_type_arr:tour_type_arr,
					tour_pickuptime_arr:tour_pickuptime_arr,
					tour_dropofftime_arr:tour_dropofftime_arr,
					tour_booking_tour_date_arr:tour_booking_tour_date_arr,
					tour_service_type_arr:tour_service_type_arr
				},
				beforeSend:function(){
					$(".loader_inner").fadeIn();
				},
				dataType:"json",
				success:function(response){
					//console.log(response);
					//console.log(JSON.stringify(response, null, 4));
					if(response.status=="success")
					{
						var page=1;
						var type=1;
						fetch_step5_data();
						var $active = $('.wizard .nav-tabs li.active');
						$active.next().removeClass('disabled');
						$active.next().find('a[data-toggle="tab"]').click();
						if($('.each_tour_tab_content').length == tour_offer_city_arr.length)
						{
							$(".nav-tabs li:eq(3)").addClass("completed_booking_step").removeClass("uncompleted_booking_step");
						}
						else
						{
							$(".nav-tabs li:eq(3)").addClass("uncompleted_booking_step").removeClass("completed_booking_step");
						}
					}
					else
					{
						showError(response.msg);
					}
					$(".loader_inner").fadeOut();
				},
				error:function(){
					//showError("We are having some problem. Please try later.");
				}
			});
		});
		$(".save_step4_data").click(function(){
			var transfer_offer_arr=[];
			var transfer_offer_city_arr=[];
			var transfer_pickuptime_arr=[];
			var transfer_dropofftime_arr=[];
			var transfer_booking_transfer_date_arr=[];
			var transfer_pickup_dropoff_type_arr=[];
			var transfer_airport_arr=[];
			var transfer_service_type_arr=[];
			$('input[class="selected_transfer"]:checked').each(function(){
				if($(this).val()!="" && $(this).parents(".each_transfer_row_outer").find(".selected_booking_transfer_date").val()!="" && $(this).parents(".each_transfer_row_outer").find(".selected_service_type").val()!="" && $(this).parents(".each_transfer_row_outer").find(".pickup_dropoff_type").val()!="" && $(this).parents(".each_transfer_row_outer").find(".pickuptime").val()!="" && $(this).parents(".each_transfer_row_outer").find(".dropofftime").val()!="")
				{
					transfer_offer_arr.push($(this).val());
					transfer_booking_transfer_date_arr.push($(this).parents(".each_transfer_row_outer").find(".selected_booking_transfer_date").val());
					transfer_service_type_arr.push($(this).parents(".each_transfer_row_outer").find(".selected_service_type").val());
					transfer_pickup_dropoff_type_arr.push($(this).parents(".each_transfer_row_outer").find(".pickup_dropoff_type").val());
					transfer_airport_arr.push($(this).parents(".each_transfer_row_outer").find(".selected_airport").val());
					transfer_pickuptime_arr.push($(this).parents(".each_transfer_row_outer").find(".pickuptime").val());
					transfer_dropofftime_arr.push($(this).parents(".each_transfer_row_outer").find(".dropofftime").val());
					var city_id=$(this).val().split("-");
					if($.inArray(city_id[0], transfer_offer_city_arr) < 0)
						transfer_offer_city_arr.push(city_id[0]);
				}
			});
			$.ajax({
				url:'<?= DOMAIN_NAME_PATH_ADMIN."ajax_booking_step4_execute";?>',
				type:"post",
				data:{
					transfer_offer_arr:transfer_offer_arr,
					transfer_pickuptime_arr:transfer_pickuptime_arr,
					transfer_dropofftime_arr:transfer_dropofftime_arr,
					transfer_booking_transfer_date_arr:transfer_booking_transfer_date_arr,
					transfer_pickup_dropoff_type_arr:transfer_pickup_dropoff_type_arr,
					transfer_airport_arr:transfer_airport_arr,
					transfer_service_type_arr:transfer_service_type_arr
				},
				beforeSend:function(){
					$(".loader_inner").fadeIn();
				},
				dataType:"json",
				success:function(response){
					//console.log(response);
					//console.log(JSON.stringify(response, null, 4));
					if(response.status=="success")
					{
						var page=1;
						var type=1;
						var $active = $('.wizard .nav-tabs li.active');
						$active.next().removeClass('disabled');
						$active.next().find('a[data-toggle="tab"]').click();
						//fetch_step3_rcd(page, type);
						fetch_new_step3_rcd(page, type);
						$(".nav-tabs li:gt(2)").removeClass("completed_booking_step").removeClass("uncompleted_booking_step");
						if($('.each_transfer_tab_content').length == transfer_offer_city_arr.length)
						{
							$(".nav-tabs li:eq(2)").addClass("completed_booking_step").removeClass("uncompleted_booking_step");
						}
						else
						{
							$(".nav-tabs li:eq(2)").addClass("uncompleted_booking_step").removeClass("completed_booking_step");
						}
					}
					else
					{
						showError(response.msg);
					}
					$(".loader_inner").fadeOut();
				},
				error:function(){
					//showError("We are having some problem. Please try later.");
				}
			});
		});
		var iScrollPos = 0;
		$(window).scroll(function(){
			var iCurScrollPos = $(this).scrollTop();
			if (iCurScrollPos > iScrollPos) {
				if($(window).scrollTop() == $("body")[0].scrollHeight-$(window).outerHeight())
				{
					if($(".tab-content .active").attr("id")=="step2")
					{
						var cur=$(".tab-content .active ").find(".active_each_tab_content");
						if(cur.find(".hotel_list_tab_no_more_record_status").val()==0)
						{
							var page=eval(cur.find(".hotel_list_tab_current_page").val())+eval(1);
							var type=2;
							var sort_order=cur.find("input[name='sort']:checked").val();
							var city_id=cur.attr("data-city_id");
							var country_id=cur.attr("data-country_id");
							var search_val=$("#keyword_search"+city_id).val();
							fetch_step2_rcd(page, type, sort_order, city_id, country_id, search_val)
						}
					}
					else if($(".tab-content .active").attr("id")=="step3")
					{
						var cur=$(".tab-content .active ").find(".active_each_tab_content");
						if(cur.find(".tour_list_tab_no_more_record_status").val()==0)
						{
							var page=eval(cur.find(".tour_list_tab_current_page").val())+eval(1);
							var type=2;
							var sort_order=cur.find("input[name='tour_sort']:checked").val();
							var city_id=cur.attr("data-city_id");
							var country_id=cur.attr("data-country_id");
							var search_val=$("#tour_keyword_search"+city_id).val();
							fetch_step3_rcd(page, type, sort_order, city_id, country_id, search_val)
						}
					}
					else if($(".tab-content .active").attr("id")=="step4")
					{
						var cur=$(".tab-content .active ").find(".active_each_tab_content");
						if(cur.find(".transfer_list_tab_no_more_record_status").val()==0)
						{
							var page=eval(cur.find(".transfer_list_tab_current_page").val())+eval(1);
							var type=2;
							var sort_order=cur.find("input[name='tour_sort']:checked").val();
							var city_id=cur.attr("data-city_id");
							var country_id=cur.attr("data-country_id");
							var search_val=$("#transfer_keyword_search"+city_id).val();
							fetch_step4_rcd(page, type, sort_order, city_id, country_id, search_val)
						}
					}
				}
			} else {
			   //Scrolling Up
			}
			iScrollPos = iCurScrollPos;
		});
		$("#quotation_name_form").submit(function(){
			if($("#quotation_name_form").validationEngine("validate"))
			{
				$.ajax({
					url:'<?= DOMAIN_NAME_PATH_ADMIN."ajax_update_quotation_name";?>',
					type:"post",
					data:$("#quotation_name_form").serialize(),
					beforeSend:function(){
						$(".loader_inner").fadeIn();
					},
					dataType:"json",
					success:function(response){
						//console.log(response);
						//console.log(JSON.stringify(response, null, 4));
						if(response.status=="success")
						{
							showSuccess(response.msg);
						}
						else
						{
							showError(response.msg);
						}
						$(".loader_inner").fadeOut();
					},
					error:function(){
						//showError("We are having some problem. Please try later.");
					}
				});
			}
			return false;
		});
		$("#payment_method_form").submit(function(){
			if($("#quotation_name").val()=="")
			{
				showError("Please first save the quotation name");
			}
			else
			{
				$.ajax({
					url:'<?= DOMAIN_NAME_PATH_ADMIN."ajax_booking_step5_execute";?>',
					type:"post",
					data:$("#payment_method_form").serialize(),
					beforeSend:function(){
						$(".loader_inner").fadeIn();
					},
					dataType:"json",
					success:function(response){
						//console.log(response);
						//console.log(JSON.stringify(response, null, 4));
						if(response.status=="success")
						{
							//showSuccess(response.msg);
							window.location.href="bookings?msg=b_success";
						}
						else
						{
							showError(response.msg);
						}
						$(".loader_inner").fadeOut();
					},
					error:function(){
						showError("We are having some problem. Please try later.");
					}
				});
			}
			return false;
		});
	});	  
	function check_all_rating(cur)
	{
		if(cur.is(":checked")==true)
		{
			cur.parent("div").find('input[type="checkbox"]').prop("checked", true);
		}
		else
		{
			cur.parent("div").find('input[type="checkbox"]').prop("checked", false);
		}
	}
	function manage_booking_type(val) {
		if(val == "agent") {
			document.getElementById('agent_name').disabled = false;
		}
		else
		{
			document.getElementById('agent_name').disabled = true;
			$("#agent_name").val("");
		}
	}

	function child_attribute(cur, index)
	{
		var val=cur.val();
		var child_number_html='';
		if(val!='') 
		{
			for(var i=0;i<val;i++)
			{

				child_number_html+='<div class="row each_child_age_div">';
					child_number_html+='<div class="col-md-4">';
						child_number_html+='<label for="inputName" class="control-label">Age</label>';
						child_number_html+='<div class="form-group">';
							child_number_html+='<select class="form-control validate[optional]" name="child_age['+index+']['+i+']" id="child_age'+index+''+i+'">';
							<?php
							for($child_loop=1;$child_loop<=MAX_CHILD_AGE;$child_loop++)
							{
							?>
								child_number_html+='<option label="<?php echo $child_loop;?>" value="<?php echo $child_loop;?>"><?php echo $child_loop;?></option>';
							<?php
							}
							?>
							child_number_html+='</select>';
						child_number_html+='</div>';
					child_number_html+='</div>';
					child_number_html+='<div class="col-md-8" id = "bed_required_div">';
						child_number_html+='<label for="inputName" class="control-label">Additional Bed Required</label>';
						child_number_html+='<select class="form-control validate[optional]" name="bed_required['+index+']['+i+']" id="bed_required'+index+''+i+'">';
							child_number_html+='<option value="Yes">Yes</option>';
							child_number_html+='<option value="No">No</option>';
						child_number_html+='</select>';
					child_number_html+='</div>';
				child_number_html+='</div>';
				child_number_html+='<div class="clearfix"></div>';
			}
			$(".all_child_age_div"+index).html(child_number_html);
			$('.all_child_age_div'+index).show();
			$('select').select2();
		}
		else {
			$(".all_child_age_div"+index).html(child_number_html);
			$('.all_child_age_div'+index).hide();
		}
	}

	function show_rooms(id)
	{
		if($("#"+id).is(":visible"))
		{
			$("#"+id).hide();
		}
		else
		{
			$("#"+id).show();
		}
	}
	function show_offers(id) 
	{
		if($("#"+id).is(":visible"))
		{
			$("#"+id).hide();
		}
		else
		{
			$("#"+id).show();
		}
	}

	function show_transfers(id)
	{
		if($("#"+id).is(":visible"))
		{
			$("#"+id).hide();
		}
		else
		{
			$("#"+id).show();
		}
	}
	
	function datediff(first, second) {
		var first_arr = first.split('/');
		var proper_first_date= new Date(first_arr[2], first_arr[1]-1, first_arr[0]);
		var second_arr = second.split('/');
		var proper_second_date= new Date(second_arr[2], second_arr[1]-1, second_arr[0]);
		return Math.round((proper_second_date-proper_first_date)/(1000*60*60*24));
	}
	function fetch_city(country_id, key)
	{
		$.ajax({
			url:"<?= DOMAIN_NAME_PATH_ADMIN."ajax_booking_city_fetch";?>",
			type:"post",
			data:{
				country_id:country_id
			},
			beforeSend:function(){
				$("#city"+key).html('<option value = "">Select City</option>');
			},
			dataType:"json",
			success:function(response){
				//console.log(response);
				if(response.status=="success")
				{
					if(response.results.length > 0)
					{
						$.each(response.results, function(city_key, city_val){
							$("#city"+key).append('<option value = "'+city_val['id']+'">'+city_val['name']+'</option>');
						});
					}
				}
				else
				{
					//showError(response.msg);
				}
			},
			error:function(){
				//showError("We are having some problem. Please try later.");
			}
		});
	}
	function match_night()
	{
		var total_days_selected=datediff($("#checkin").val(), $("#checkout").val());
		var total_night_text_field=$(".number_of_night").length;
		var minus_day=0;
		var minus_field=0;
		$(".number_of_night").each(function(){
			var remaining_days=total_days_selected - minus_day;
			var remaining_fields=total_night_text_field - minus_field;
			var display_day=Math.ceil(remaining_days/remaining_fields);
			if(display_day>0)
			{
				minus_day=eval(minus_day)+eval(display_day);
				minus_field++;
				$(this).val(display_day);
			}
			else
			{
				showError("You can not add more destination. Please increase checkout date to add more destination.");
				$(this).parents("div.appended_row").remove();
			}
		});
	}
	function check_night()
	{
		var total_neight=0;
		$(".number_of_night").each(function(){
			if($(this).val()!=""){
				total_neight=eval(total_neight)+eval($(this).val());
			}
		});
		var total_days_selected=datediff($("#checkin").val(), $("#checkout").val())+1;
		if(total_neight!=total_days_selected)
		{
			showError("Number of night is not same as the difference of checkin and checkout");
		}
	}
	function change_city_hotel(cur)
	{
		if(cur.hasClass("cls_each_city_tab_div_active")==false)
		{
			$(".each_hotel_tab_content").removeClass("active_each_tab_content");
			$(".cls_each_city_hotel_tab_div").removeClass("cls_each_city_tab_div_active");
			cur.addClass("cls_each_city_tab_div_active");
			$("#"+cur.attr("data-tab_id")).addClass("active_each_tab_content");
		}
	}
	function change_city_tour(cur)
	{
		if(cur.hasClass("cls_each_city_tab_div_active")==false)
		{
			$(".each_tour_tab_content").removeClass("active_each_tab_content");
			$(".cls_each_city_tour_tab_div").removeClass("cls_each_city_tab_div_active");
			cur.addClass("cls_each_city_tab_div_active");
			$("#"+cur.attr("data-tab_id")).addClass("active_each_tab_content");
		}
	}
	function change_city_transfer(cur)
	{
		if(cur.hasClass("cls_each_city_tab_div_active")==false)
		{
			$(".each_transfer_tab_content").removeClass("active_each_tab_content");
			$(".cls_each_city_transfer_tab_div").removeClass("cls_each_city_tab_div_active");
			cur.addClass("cls_each_city_tab_div_active");
			$("#"+cur.attr("data-tab_id")).addClass("active_each_tab_content");
		}
	}
	function change_room_radio(cur)
	{
		if(cur.attr('previousValue') == 'true')
		{
            cur.prop('checked', false);
			cur.attr('previousValue', false);
			cur.parents(".form-group").find(".default_price_div").html(cur.parents(".form-group").find(".default_price_div").attr("data-default_price"));
        } 
		else
		{
			$('input[name="selected_room[]"]').attr('previousValue', false);
            cur.attr('previousValue', true);			
			cur.parents(".form-group").find(".default_price_div").html(cur.attr('data-price'));
        }
	}
	function change_offer_radio(cur)
	{
		/*if(cur.attr('previousValue') == 'true')
		{
            cur.prop('checked', false);
			cur.attr('previousValue', false);
			cur.parents(".form-group").find(".default_price_div").html(cur.parents(".form-group").find(".default_price_div").attr("data-default_price"));
        } 
		else
		{
			$('input[name="selected_offer[]"]').attr('previousValue', false);
            cur.attr('previousValue', true);			
			cur.parents(".form-group").find(".default_price_div").html(cur.attr('data-price'));
        }*/
	}
	function change_transfer_radio(cur)
	{
		/*if(cur.attr('previousValue') == 'true')
		{
            cur.prop('checked', false);
			cur.attr('previousValue', false);
			cur.parents(".form-group").find(".default_price_div").html(cur.parents(".form-group").find(".default_price_div").attr("data-default_price"));
        } 
		else
		{
			$('input[name="selected_transfer[]"]').attr('previousValue', false);
            cur.attr('previousValue', true);			
			cur.parents(".form-group").find(".default_price_div").html(cur.attr('data-price'));
        }*/
	}
	function change_order(cur)
	{
		var sort_order=cur.val();
		var city_id=cur.attr("data-city_id");
		var country_id=cur.attr("data-country_id");
		var search_val=$("#keyword_search"+city_id).val();
		var page=1;
		var type=1;
		fetch_step2_rcd(page, type, sort_order, city_id, country_id, search_val);
	}
	function change_tour_order(cur)
	{
		var sort_order=cur.val();
		var city_id=cur.attr("data-city_id");
		var country_id=cur.attr("data-country_id");
		var search_val=$("#tour_keyword_search"+city_id).val();
		var page=1;
		var type=1;
		fetch_step3_rcd(page, type, sort_order, city_id, country_id, search_val);
	}
	function change_transfer_order(cur)
	{
		var sort_order=cur.val();
		var city_id=cur.attr("data-city_id");
		var country_id=cur.attr("data-country_id");
		var search_val=$("#transfer_keyword_search"+city_id).val();
		var page=1;
		var type=1;
		fetch_step4_rcd(page, type, sort_order, city_id, country_id, search_val);
	}
	function filter_search(cur, city_id)
	{
		var search_val=$("#keyword_search"+city_id).val();
		var country_id=cur.attr("data-country_id");
		var sort_order=cur.parents("#city"+city_id).find("input[name='sort']:checked").val();
		var page=1;
		var type=3;
		fetch_step2_rcd(page, type, sort_order, city_id, country_id, search_val);
	}
	function filter_tour_search(cur, city_id)
	{
		if(cur.validationEngine("validate")==true)
		{
			var booking_tour_date=$("#booking_tour_date"+city_id).val();
			var tour_type=$("#tour_type"+city_id).val();
			var pick_time=$("#pick_time"+city_id).val();
			var selected_service_type=$("#selected_tour_service_type"+city_id).val();
			var country_id=cur.attr("data-country_id");
			var search_counter=cur.find(".search_counter").val();
			var search_val='';
			var sort_order='';
			var page=1;
			var type=3;
			fetch_step3_rcd(page, type, sort_order, city_id, country_id, search_val, booking_tour_date, tour_type, pick_time, selected_service_type, search_counter);
			cur.find(".search_counter").val(eval(search_counter)+eval(1));
		}
		return false;
	}
	function filter_transfer_search(cur, city_id)
	{
		if(cur.validationEngine("validate")==true)
		{
			var booking_transfer_date=$("#booking_transfer_date"+city_id).val();
			var pickup_dropoff_type=$("#pickup_dropoff_type"+city_id).val();
			var selected_airport=$("#selected_airport"+city_id).val();
			var arr_dept_flight_number=$("#arr_dept_flight_number"+city_id).val();
			var arr_dept_time=$("#arr_dept_time"+city_id).val();
			var selected_service_type=$("#selected_service_type"+city_id).val();
			var country_id=cur.attr("data-country_id");
			var search_counter=cur.find(".search_counter").val();
			var sort_order='';
			var page=1;
			var type=3;
			var search_val='';
			fetch_step4_rcd(page, type, sort_order, city_id, country_id, search_val, booking_transfer_date, pickup_dropoff_type, selected_airport, arr_dept_flight_number, arr_dept_time, selected_service_type, search_counter);
			cur.find(".search_counter").val(eval(search_counter)+eval(1));
		}
		return false;
	}
	function fetch_step2_rcd(page, type, sort_order='', city_id='', country_id='', search_val='')
	{
		$.ajax({
			url:'<?= DOMAIN_NAME_PATH_ADMIN."ajax_find_booking_step2_data";?>',
			type:"post",
			data:{
				page:page,
				type:type,
				sort_order:sort_order,
				city_id:city_id,
				country_id:country_id,
				search_val:search_val
			},
			beforeSend:function(){
				$(".loader_inner").fadeIn();
			},
			dataType:"json",
			success:function(response){
				//console.log(response);
				//console.log(JSON.stringify(response, null, 4));
				if(response.status=="success")
				{
					if(type==2)
					{
						$("#city"+city_id+" .all_rcd_row").append(response.hotel_data);
						$(".tab-content .active .active_each_tab_content").find(".hotel_list_tab_current_page").val(page);
						var prev_count=$("#city"+city_id+" .total_hotel_number").html();
						var new_count=eval(prev_count)+eval(response.heading_count_rcd);
						$("#city"+city_id+" .total_hotel_number").html(new_count);
						if(response.hotel_data.indexOf("No more record found") > -1)
							$(".tab-content .active .active_each_tab_content").find(".hotel_list_tab_no_more_record_status").val(1);
					}
					else if(sort_order!="" || type==3)
					{
						if(type==3)
						{
							$(".tab-content .active .active_each_tab_content").find(".hotel_list_tab_current_page").val(1);
							$(".tab-content .active .active_each_tab_content").find(".hotel_list_tab_no_more_record_status").val(0);
						}
						$("#step2 #city"+city_id+" .all_rcd_row").html(response.hotel_data);
						$("#step2 #city"+city_id+" .total_hotel_number").text(response.heading_count_rcd);
					}
					else
					{
						$(".hotel_tab_all_data_div").html(response.hotel_data);
						$(".city_tab_button_div").html(response.city_tab_html);
					}

				}
				else
				{
					showError(response.msg);
				}
				$(".loader_inner").fadeOut();
			},
			error:function(){
				showError("We are having some problem. Please try later.");
				$(".loader_inner").fadeOut();
			}
		}).done(function() {
			$(".rate_content_div").each(function(){
				$(this).raty({
					readOnly: true,
					path: '<?php echo(DOMAIN_NAME_PATH_ADMIN);?>assets/raty/images',
					score:$(this).attr("data-rate")
				});
			});
			$('#min_rating_div').raty('set', { score: window.localStorage.getItem("set_star") });				 
		});
	}
	function fetch_step3_rcd(page, type, sort_order='', city_id='', country_id='', search_val='', booking_tour_date='', tour_type='', pick_time='', selected_service_type='', search_counter='')
	{
		$.ajax({
			url:'<?= DOMAIN_NAME_PATH_ADMIN."ajax_find_booking_step3_data";?>',
			type:"post",
			data:{
				page:page,
				type:type,
				sort_order:sort_order,
				city_id:city_id,
				country_id:country_id,
				search_val:search_val,
				booking_tour_date:booking_tour_date,
				tour_type:tour_type,
				pick_time:pick_time,
				selected_service_type:selected_service_type,
				search_counter:search_counter
			},
			beforeSend:function(){
				$(".loader_inner").fadeIn();
			},
			dataType:"json",
			success:function(response){
				//console.log(response);
				//console.log(JSON.stringify(response, null, 4));
				if(response.status=="success")
				{
					if(type==2)
					{
						/*$("#tour_city"+city_id+" .all_rcd_row").append(response.tour_data);
						$(".tab-content .active .active_each_tab_content").find(".tour_list_tab_current_page").val(page);
						var prev_count=$("#tour_city"+city_id+" .total_tour_number").html();
						var new_count=eval(prev_count)+eval(response.heading_count_rcd);
						$("#tour_city"+city_id+" .total_tour_number").html(new_count);
						if(response.tour_data.indexOf("No more record found") > -1)
							$(".tab-content .active .active_each_tab_content").find(".tour_list_tab_no_more_record_status").val(1);*/
					}
					else if(sort_order!="" || type==3)
					{
						//if(type==3)
						//{
							$(".tab-content .active .active_each_tab_content").find(".tour_list_tab_current_page").val(1);
							$(".tab-content .active .active_each_tab_content").find(".tour_list_tab_no_more_record_status").val(0);
						//}
						//$("#step3 #tour_city"+city_id+" .all_rcd_row").html(response.tour_data);
						//$("#step3 #tour_city"+city_id+" .total_tour_number").text(response.heading_count_rcd);
						if($("#step3 #tour_city"+city_id+" .all_rcd_row .each_tour_date_div_"+response['post_data']['country_city_rcd_date']).length)
						{								
							$("#step3 #tour_city"+city_id+" .all_rcd_row .each_tour_date_div .no_rcd").remove();
							$("#step3 #tour_city"+city_id+" .all_rcd_row .each_tour_date_div_"+response['post_data']['country_city_rcd_date']).append(response.transfer_data);
						}
						else
						{
							/*var exists_svg_path="";
							$(".each_tour_date_div").each(function(){
								if($(this).attr("data-date_time")==response['post_data']['country_city_rcd_date_time'] && $(this).find(".radio_button_row_background").length>0)
								{
									exists_svg_path+=$(this).find("svg").html();
								}
							});
							$(".each_date_div").each(function(){
								if($(this).attr("data-date_time")==response['post_data']['country_city_rcd_date_time'] && $(this).find(".radio_button_row_background").length>0)
								{
									exists_svg_path+=$(this).find("svg").html();
								}
							});*/
							var exists_am_svg_path="";
							var exists_pm_svg_path="";
							$(".each_tour_date_div").each(function(){
								if($(this).attr("data-date_time")==response['post_data']['country_city_rcd_date_time'] && $(this).find(".radio_button_row_background").length>0)
								{
									exists_am_svg_path+=($(this).find(".clock_am_div svg").html() ? $(this).find(".clock_am_div svg").html() : "");
									exists_pm_svg_path+=($(this).find(".clock_pm_div svg").html() ? $(this).find(".clock_pm_div svg").html() : "");
								}
							});
							$(".each_date_div").each(function(){
								if($(this).attr("data-date_time")==response['post_data']['country_city_rcd_date_time'] && $(this).find(".radio_button_row_background").length>0)
								{
									exists_am_svg_path+=($(this).find(".clock_am_div svg").html() ? $(this).find(".clock_am_div svg").html() : "");
									exists_pm_svg_path+=($(this).find(".clock_pm_div svg").html() ? $(this).find(".clock_pm_div svg").html() : "");
								}
							});
							var add_html='';
							add_html+='<div class="each_tour_date_div_'+response['post_data']['country_city_rcd_date']+' each_tour_date_div" data-date_time="'+response['post_data']['country_city_rcd_date_time']+'">';
								add_html+='<div class="col-md-12 date_heading_div" onclick="hide_show_tour_details($(this))">';
									add_html+='<h4>Date: '+response['post_data']['country_city_rcd_formated_date']+'</h4>';
									add_html+='<div class="clock_img_div">';
										add_html+='<div class="change_clock_am_div">';
											add_html+='<input type="checkbox" checked="" data-toggle="toggle" data-on="AM" data-off="PM" data-onstyle="primary" data-offstyle="danger" class="toggle-demo" onchange="change_clock($(this))">';
										add_html+='</div>';
										add_html+='<div class="clock clock_am_div">';
											add_html+='<svg>';
												add_html+=exists_am_svg_path;
											add_html+='</svg>';
										add_html+='</div>';
										add_html+='<div class="clock clock_pm_div">';
											add_html+='<svg>';
												add_html+=exists_pm_svg_path;
											add_html+='</svg>';
										add_html+='</div>';
									add_html+='</div>';
									/*add_html+='<div class="clock_img_div">';
										add_html+='<div class="clock">';
											add_html+='<svg>';
											  add_html+=exists_svg_path;
											add_html+='</svg>';
										add_html+='</div>';
										//add_html+='<img src="assets/img/final_rular.png" border="0" alt="">';
									add_html+='</div>';*/
								add_html+='</div>';
								add_html+=response.tour_data;
							add_html+='</div>';								
							$("#step3 #tour_city"+city_id+" .all_rcd_row .each_tour_date_div .no_rcd").remove();
							var will_prepend=false;
							$("#step3 #tour_city"+city_id+" .all_rcd_row .each_tour_date_div").each(function(){
								if(response['post_data']['country_city_rcd_date_time']<=$(this).attr("data-date_time"))
								{
									will_prepend=true;
									$(this).before(add_html);
									return false;
								}
							});
							if(will_prepend==true)
							{
								//$("#step3 #tour_city"+city_id+" .all_rcd_row").prepend(add_html);
							}
							else
							{
								$("#step3 #tour_city"+city_id+" .all_rcd_row").append(add_html);
							}
						}
						$(".each_tour_date_div").each(function(){
							if(response['post_data']['country_city_rcd_date_time']==$(this).attr("data-date_time"))
							{
								$(this).find(".each_tour_row_outer").show();
							}
							else
							{
								$(this).find(".each_tour_row_outer").hide();
							}
						});
					}
					else
					{
						$(".tour_tab_all_data_div").html(response.tour_data);
						$(".tour_city_tab_button_div").html(response.city_tab_html);
					}
				}
				else
				{
					showError(response.msg);
				}
				$(".loader_inner").fadeOut();
			},
			error:function(){
				showError("We are having some problem. Please try later.");
				$(".loader_inner").fadeOut();
			},
			complete:function(){
				$("select").select2();
				$('.toggle-demo').bootstrapToggle();
			}
		});
	}
	function fetch_new_step3_rcd(page, type, sort_order='', city_id='', country_id='', search_val='')
	{
		$.ajax({
			url:'<?= DOMAIN_NAME_PATH_ADMIN."ajax_find_new_booking_step3_data";?>',
			type:"post",
			data:{
				page:page,
				type:type,
				sort_order:sort_order,
				city_id:city_id,
				country_id:country_id,
				search_val:search_val
			},
			beforeSend:function(){
				$(".loader_inner").fadeIn();
			},
			dataType:"json",
			success:function(response){
				//console.log(response);
				//console.log(JSON.stringify(response, null, 4));
				if(response.status=="success")
				{
					if(type==2)
					{
						$("#tour_city"+city_id+" .all_rcd_row").append(response.tour_data);
						$(".tab-content .active .active_each_tab_content").find(".tour_list_tab_current_page").val(page);
						var prev_count=$("#tour_city"+city_id+" .total_tour_number").html();
						var new_count=eval(prev_count)+eval(response.heading_count_rcd);
						$("#tour_city"+city_id+" .total_tour_number").html(new_count);
						if(response.tour_data.indexOf("No more record found") > -1)
							$(".tab-content .active .active_each_tab_content").find(".tour_list_tab_no_more_record_status").val(1);
					}
					else if(sort_order!="" || type==3)
					{
						if(type==3)
						{
							$(".tab-content .active .active_each_tab_content").find(".tour_list_tab_current_page").val(1);
							$(".tab-content .active .active_each_tab_content").find(".tour_list_tab_no_more_record_status").val(0);
						}
						$("#step3 #tour_city"+city_id+" .all_rcd_row").html(response.tour_data);
						$("#step3 #tour_city"+city_id+" .total_tour_number").text(response.heading_count_rcd);
					}
					else
					{
						$(".tour_tab_all_data_div").html(response.tour_data);
						$(".tour_city_tab_button_div").html(response.city_tab_html);
					}
				}
				else
				{
					showError(response.msg);
				}
				$(".loader_inner").fadeOut();
			},
			error:function(){
				showError("We are having some problem. Please try later.");
				$(".loader_inner").fadeOut();
			},
			complete:function(){
				$("select").select2();
				$('.toggle-demo').bootstrapToggle();
			}
		});
	}
	function fetch_step4_rcd(page, type, sort_order='', city_id='', country_id='', search_val='', booking_transfer_date='', pickup_dropoff_type='', selected_airport='', arr_dept_flight_number='', arr_dept_time='', selected_service_type='', search_counter='')
	{
		$.ajax({
			url:'<?= DOMAIN_NAME_PATH_ADMIN."ajax_find_booking_step4_data";?>',
			type:"post",
			data:{
				page:page,
				type:type,
				sort_order:sort_order,
				city_id:city_id,
				country_id:country_id,
				search_val:search_val,
				booking_transfer_date:booking_transfer_date,
				pickup_dropoff_type:pickup_dropoff_type,
				selected_airport:selected_airport,
				arr_dept_flight_number:arr_dept_flight_number,
				arr_dept_time:arr_dept_time,
				selected_service_type:selected_service_type,
				search_counter:search_counter,
			},
			beforeSend:function(){
				$(".loader_inner").fadeIn();
			},
			dataType:"json",
			success:function(response){
				//console.log(response);
				//console.log(JSON.stringify(response, null, 4));
				if(response.status=="success")
				{
					if(type==2)
					{
						/*$("#transfer_city"+city_id+" .all_rcd_row").append(response.transfer_data);
						$(".tab-content .active .active_each_tab_content").find(".transfer_list_tab_current_page").val(page);
						var prev_count=$("#transfer_city"+city_id+" .total_transfer_number").html();
						var new_count=eval(prev_count)+eval(response.heading_count_rcd);
						$("#transfer_city"+city_id+" .total_transfer_number").html(new_count);
						if(response.transfer_data.indexOf("No more record found") > -1)
							$(".tab-content .active .active_each_tab_content").find(".transfer_list_tab_no_more_record_status").val(1);*/
					}
					else if(sort_order!="" || type==3)
					{
						//if(type==3)
						//{
							$(".tab-content .active .active_each_tab_content").find(".transfer_list_tab_current_page").val(1);
							$(".tab-content .active .active_each_tab_content").find(".transfer_list_tab_no_more_record_status").val(0);
						//}
						//$("#step4 #transfer_city"+city_id+" .all_rcd_row").html(response.transfer_data);
						//$("#step4 #transfer_city"+city_id+" .total_transfer_number").text(response.heading_count_rcd);
						if($("#step4 #transfer_city"+city_id+" .all_rcd_row .each_date_div_"+response['post_data']['country_city_rcd_date']).length)
						{								
							$("#step4 #transfer_city"+city_id+" .all_rcd_row .each_date_div .no_rcd").remove();
							$("#step4 #transfer_city"+city_id+" .all_rcd_row .each_date_div_"+response['post_data']['country_city_rcd_date']).append(response.transfer_data);
						}
						else
						{
							var exists_am_svg_path="";
							var exists_pm_svg_path="";
							$(".each_date_div").each(function(){
								if($(this).attr("data-date_time")==response['post_data']['country_city_rcd_date_time'] && $(this).find(".radio_button_row_background").length>0)
								{
									exists_am_svg_path+=($(this).find(".clock_am_div svg").html() ? $(this).find(".clock_am_div svg").html() : "");
									exists_pm_svg_path+=($(this).find(".clock_pm_div svg").html() ? $(this).find(".clock_pm_div svg").html() : "");
								}
							});
							var add_html='';
							add_html+='<div class="each_date_div_'+response['post_data']['country_city_rcd_date']+' each_date_div" data-date_time="'+response['post_data']['country_city_rcd_date_time']+'">';
								add_html+='<div class="col-md-12 date_heading_div" onclick="hide_show_transfer_details($(this))">';
									add_html+='<h4>Date: '+response['post_data']['country_city_rcd_formated_date']+'</h4>';
									add_html+='<div class="clock_img_div">';
										add_html+='<div class="change_clock_am_div">';
											add_html+='<input type="checkbox" checked="" data-toggle="toggle" data-on="AM" data-off="PM" data-onstyle="primary" data-offstyle="danger" class="toggle-demo" onchange="change_clock($(this))">';
										add_html+='</div>';
										add_html+='<div class="clock clock_am_div">';
											add_html+='<svg>';
												add_html+=exists_am_svg_path;
											add_html+='</svg>';
										add_html+='</div>';
										add_html+='<div class="clock clock_pm_div">';
											add_html+='<svg>';
												add_html+=exists_pm_svg_path;
											add_html+='</svg>';
										add_html+='</div>';
									add_html+='</div>';
									/*add_html+='<div class="clock_img_div">';
										add_html+='<div class="clock">';
											add_html+='<svg>';
											  add_html+=exists_svg_path;
											add_html+='</svg>';
										add_html+='</div>';
										//add_html+='<img src="assets/img/final_rular.png" border="0" alt="">';
									add_html+='</div>';*/
								add_html+='</div>';
								add_html+=response.transfer_data;
							add_html+='</div>';								
							$("#step4 #transfer_city"+city_id+" .all_rcd_row .each_date_div .no_rcd").remove();
							var will_prepend=false;
							$("#step4 #transfer_city"+city_id+" .all_rcd_row .each_date_div").each(function(){
								if(response['post_data']['country_city_rcd_date_time']<=$(this).attr("data-date_time"))
								{
									will_prepend=true;
									$(this).before(add_html);
									return false;
								}
							});
							if(will_prepend==true)
							{
								//$("#step4 #transfer_city"+city_id+" .all_rcd_row").prepend(add_html);
							}
							else
							{
								$("#step4 #transfer_city"+city_id+" .all_rcd_row").append(add_html);
							}
						}
						$(".each_date_div").each(function(){
							if(response['post_data']['country_city_rcd_date_time']==$(this).attr("data-date_time"))
							{
								$(this).find(".each_transfer_row_outer").show();
							}
							else
							{
								$(this).find(".each_transfer_row_outer").hide();
							}
						});
					}
					else
					{
						$(".transfer_tab_all_data_div").html(response.transfer_data);
						$(".transfer_city_tab_button_div").html(response.city_tab_html);
					}
				}
				else
				{
					showError(response.msg);
				}
				$(".loader_inner").fadeOut();
			},
			error:function(){
				showError("We are having some problem. Please try later.");
				$(".loader_inner").fadeOut();
			},
			complete:function(){
				$("select").select2();
				$('.toggle-demo').bootstrapToggle();
			}
		});
	}
	function fetch_new_step4_rcd(page, type, sort_order='', city_id='', country_id='', search_val='')
	{
		$.ajax({
			url:'<?= DOMAIN_NAME_PATH_ADMIN."ajax_find_new_booking_step4_data";?>',
			type:"post",
			data:{
				page:page,
				type:type,
				sort_order:sort_order,
				city_id:city_id,
				country_id:country_id,
				search_val:search_val,
			},
			beforeSend:function(){
				$(".loader_inner").fadeIn();
			},
			dataType:"json",
			success:function(response){
				//console.log(response);
				//console.log(JSON.stringify(response, null, 4));
				if(response.status=="success")
				{
					if(type==2)
					{
						$("#transfer_city"+city_id+" .all_rcd_row").append(response.transfer_data);
						$(".tab-content .active .active_each_tab_content").find(".transfer_list_tab_current_page").val(page);
						var prev_count=$("#transfer_city"+city_id+" .total_transfer_number").html();
						var new_count=eval(prev_count)+eval(response.heading_count_rcd);
						$("#transfer_city"+city_id+" .total_transfer_number").html(new_count);
						if(response.transfer_data.indexOf("No more record found") > -1)
							$(".tab-content .active .active_each_tab_content").find(".transfer_list_tab_no_more_record_status").val(1);
					}
					else if(sort_order!="" || type==3)
					{
						if(type==3)
						{
							$(".tab-content .active .active_each_tab_content").find(".transfer_list_tab_current_page").val(1);
							$(".tab-content .active .active_each_tab_content").find(".transfer_list_tab_no_more_record_status").val(0);
						}
						$("#step4 #transfer_city"+city_id+" .all_rcd_row").html(response.transfer_data);
						$("#step4 #transfer_city"+city_id+" .total_transfer_number").text(response.heading_count_rcd);
					}
					else
					{
						$(".transfer_tab_all_data_div").html(response.transfer_data);
						$(".transfer_city_tab_button_div").html(response.city_tab_html);
					}
				}
				else
				{
					showError(response.msg);
				}
				$(".loader_inner").fadeOut();
			},
			error:function(){
				showError("We are having some problem. Please try later.");
				$(".loader_inner").fadeOut();
			},
			complete:function(){
				$("select").select2();
				$('.toggle-demo').bootstrapToggle();
			}
		});
	}
	function fetch_step5_data()
	{
		$.ajax({
			url:'<?= DOMAIN_NAME_PATH_ADMIN."ajax_find_booking_step5_data";?>',
			type:"post",
			beforeSend:function(){
				$(".loader_inner").fadeIn();
			},
			dataType:"json",
			success:function(response){
				//console.log(response);
				//console.log(JSON.stringify(response, null, 4));
				if(response.status=="success")
				{
					$("#final_step_html").html(response.booking_html);
					$("#total_price").val(response.total_price);
					if(response.show_pay_dropdown==false)
					{
						$(".pay_dropdown_div").hide();
						$(".pay_dropdown_div_1").text("Cash Payment with in "+response.show_pay_days+" days").show();
					}
				}
				else
				{
					showError(response.msg);
				}
				$(".loader_inner").fadeOut();
			},
			error:function(){
				showError("We are having some problem. Please try later.");
				$(".loader_inner").fadeOut();
			}
		});
	}
	function find_hotel_name(cur, type='')
	{
		if(type!="" && cur.parents(".each_city_row").find(".form-group:eq(1) select").val()=="")
		{
			showError("Please select city first");
		}
		else if(cur.val()!="" || type!="")
		{
			var hotel_type="";
			var city_id="";
			if(type!="")
			{
				hotel_type=cur.val();
				city_id=cur.parents(".each_city_row").find(".form-group:eq(1) select").val();
			}
			else
			{
				city_id=cur.val();
				hotel_type=cur.parents(".each_city_row").find(".form-group:eq(3) select").val();
			}
			$.ajax({
				url:'<?= DOMAIN_NAME_PATH_ADMIN."ajax_find_city_hotel_data";?>',
				type:"post",
				data:{
					city_id:city_id,
					hotel_type:hotel_type
				},
				beforeSend:function(){
					$(".loader_inner").fadeIn();
				},
				dataType:"json",
				success:function(response){
					//console.log(response);
					//console.log(JSON.stringify(response, null, 4));
					if(response.status=="success")
					{
						if(response.results.length > 0)
						{
							var city_hotel_html='<option value="">Select Hotel</option>';
							$.each(response.results, function(key, val){
								city_hotel_html+='<option value="'+val['id']+'" data-hotel_rating="'+val['rating']+'">'+val['hotel_name']+'</option>';
							});
							//alert(cur.parents(".each_city_row").find(".first_page_hotel").html())
							//alert(city_hotel_html);
							cur.parents(".each_city_row").find(".first_page_hotel").html(city_hotel_html);
							//alert(cur.parents(".each_city_row").find(".first_page_hotel").html())
						}
						else
						{
							cur.parents(".each_city_row").find(".first_page_hotel").html('<option value="">Select Hotel</option>');
						}
					}
					else
					{
						//showError(response.msg);
						cur.parents(".each_city_row").find(".first_page_hotel").html('<option value="">Select Hotel</option>');
					}
					$(".loader_inner").fadeOut();
				},
				error:function(){
					//showError("We are having some problem. Please try later.");
					cur.parents(".each_city_row").find(".first_page_hotel").html('<option value="">Select Hotel</option>');
					$(".loader_inner").fadeOut();
				}
			});
		}
		else
		{
			cur.parents(".each_city_row").find(".first_page_hotel").html('<option value="">Select Hotel</option>');
		}
	}
		$(document).ready(function(){
			$(".add-row").click(function(){
				var new_row_key=$(this).attr("data-attr_key");
				$(this).attr("data-attr_key", eval(new_row_key)+1);
				var markup = '';
				markup+='<div class="form-group appended_row each_city_row">';
					markup+='<div class="form-group col-md-2">';
						markup+='<input type="checkbox" name="record"/>&nbsp;&nbsp;<label for="inputName" class="control-label">Country<font color="#FF0000">*</font></label>';
						markup+='<select name="country['+new_row_key+']" class="form-control validate[required]" id="country'+new_row_key+'" onchange="fetch_city($(this).val(), '+new_row_key+');">';
							markup+='<option value="">Select Country</option>';
							<?php
							if(!empty($contry_list)):
								foreach($contry_list as $country_key=>$country_val):
							?>
									markup+='<option value = "<?php echo $country_val['id'];?>" <?php echo(isset($_POST['country']) && $_POST['country']==$country_val['id'] ? 'selected="selected"' : "");?>><?php echo addslashes($country_val['name']);?></option>';
							<?php
								endforeach;
							endif;
							?>
						markup+='</select>';
					markup+='</div>';
					markup+='<div class="form-group col-md-2">';
						markup+='<label for="inputName" class="control-label">City<font color="#FF0000">*</font></label>';
						markup+='<select class="form-control validate[required]" name="city['+new_row_key+']" id="city'+new_row_key+'" onchange="find_hotel_name($(this))">';
							markup+='<option value="">Select City</option>';
						markup+='</select>';
					markup+='</div>';
					markup+='<div class="form-group col-md-2"><label for="inputName" class="control-label">For No Of Nights<font color="#FF0000">*</font></label><input type="text" class="form-control number_of_night validate[required, custom[integer]]"  value="" name="number_of_night['+new_row_key+']" id="number_of_night'+new_row_key+'" placeholder="Number Of Nights" tabindex = "4" onblur="check_night()"/></div>';					
					markup+='<div class="form-group col-md-2" style="">';
						markup+='<label for="inputName" class="control-label">Hotel Type</label>';
						markup+='<select class="form-control hotel_type" name="hotel_type['+new_row_key+']" id="first_hotel_type'+new_row_key+'" onchange="find_hotel_name($(this), \'type\')">';
							markup+='<option value="">All</option>';
					<?php
					if(isset($global_hotel_type_arr) && !empty($global_hotel_type_arr)):
						foreach($global_hotel_type_arr as $hotel_type_key=>$hotel_type_val):
					?>
							markup+='<option value="<?php echo $hotel_type_key;?>" ><?php echo $hotel_type_val;?></option>';
					<?php
						endforeach;
					endif;
					?>
						markup+='</select>';
					markup+='</div>';
					markup+='<div class="form-group col-md-2"><label for="inputName" class="control-label">Hotel Ratings<font color="#FF0000">*</font></label><br/><input type="checkbox" name="all_checkbox" class="all_checkbox" onclick="check_all_rating($(this))">&nbsp;All&nbsp;&nbsp;<input type="checkbox" value="1" name="hotel_ratings['+new_row_key+'][]" class="validate[minCheckbox[1]]"/>&nbsp;1&nbsp;&nbsp;<input type="checkbox" value="2" name="hotel_ratings['+new_row_key+'][]" class="validate[minCheckbox[1]]"/>&nbsp;2&nbsp;&nbsp;<input type="checkbox"  value="3" name="hotel_ratings['+new_row_key+'][]" class="validate[minCheckbox[1]]"/>&nbsp;3&nbsp;&nbsp;<input type="checkbox" value="4" name="hotel_ratings['+new_row_key+'][]" class="validate[minCheckbox[1]]"/>&nbsp;4&nbsp;&nbsp;<input type="checkbox" value="5" name="hotel_ratings['+new_row_key+'][]" class="validate[minCheckbox[1]]"/>&nbsp;5</div>';
					markup+='<div class="form-group col-md-2" style="">';
						markup+='<label for="inputName" class="control-label">Hotels</label>';
						markup+='<select class="form-control first_page_hotel" name="first_page_hotel['+new_row_key+']" id="first_page_hotel'+new_row_key+'" onchange="checked_hotel_rating($(this))">';
							markup+='<option value="">Select Hotel</option>';
						markup+='</select>';
					markup+='</div>';
					markup+='<div class="clearfix"></div>';
				markup+='</div>';
				markup+='<div class="clearfix"></div>';
				$("#sample").append(markup);
				match_night();
				$('select').select2();
			});
			
			// Find and remove selected table rows
			$(".delete-row").click(function(){
				if($("#sample").find('input[name="record"]:checked').length > 0)
				{
					$("#sample").find('input[name="record"]:checked').each(function(){
						$(this).parents("div.appended_row").remove();
					});
				}
				else
				{
					showError("Please select checkbox to delete row.");
				}
				match_night();
			});
		});
		function checked_hotel_rating(cur)
		{
			if(cur.val()!="" && cur.find("option:selected").attr("data-hotel_rating")!="")
			{
				cur.parents(".each_city_row").find('input[type="checkbox"][value='+cur.find("option:selected").attr("data-hotel_rating")+']').prop('checked', true);
			}
			else
			{
				cur.parents(".each_city_row").find('input[type="checkbox"]').prop('checked', false);
			}
		} 
		function select_radio_row(cur)
		{
			if(cur.hasClass("radio_button_row_background"))
			{
				cur.removeClass("radio_button_row_background");
				cur.find('input[type="radio"]').prop("checked", false);
				showSuccess("Room deselected successfully.");
			}
			else
			{
				cur.parents(".each_hotel_tab_content").find(".radio_button_row").removeClass("radio_button_row_background");
				cur.parents(".each_hotel_tab_content").find(".radio_button_row").find('input[type="radio"]').removeAttr("checked");
				cur.addClass("radio_button_row_background");
				cur.find('input[type="radio"]').prop("checked", true);
				showSuccess("Room selected successfully.");
			}
		}
		function select_transfer_radio_row(cur)
		{
			if(cur.parents(".each_transfer_row_outer").find(".pickuptime").val()!="" && cur.parents(".each_transfer_row_outer").find(".dropofftime").val()!="")
			{
				if(cur.hasClass("radio_button_row_background"))
				{
					cur.removeClass("radio_button_row_background");
					cur.find('input[type="radio"]').prop("checked", false);
					cur.parents(".form-group").find(".default_price_div").html(cur.parents(".form-group").find(".default_price_div").attr("data-default_price"));
					showSuccess("Transfer deselected successfully.");
				}
				else
				{
					cur.parent(".transfer_offer_cls").find(".radio_button_row").removeClass("radio_button_row_background");
					cur.parent(".transfer_offer_cls").find(".radio_button_row").find('input[type="radio"]').removeAttr("checked");
					cur.addClass("radio_button_row_background");
					cur.find('input[type="radio"]').prop("checked", true);
					cur.parents(".form-group").find(".default_price_div").html(cur.find('input[type="radio"]').attr('data-price'));
					showSuccess("Transfer selected successfully.");
				}
			}
			else
			{
				showError("Please select pickup and dropoff time.");
			}
		}
		function select_tour_radio_row(cur)
		{
			if(cur.parents(".each_tour_row_outer").find(".pickuptime").val()!="" && cur.parents(".each_tour_row_outer").find(".dropofftime").val()!="")
			{
				if(cur.hasClass("radio_button_row_background"))
				{
					cur.removeClass("radio_button_row_background");
					cur.find('input[type="radio"]').prop("checked", false);
					cur.parents(".form-group").find(".default_price_div").html(cur.parents(".form-group").find(".default_price_div").attr("data-default_price"));
					showSuccess("Tour deselected successfully.");
				}
				else
				{
					cur.parent(".tour_offer_cls").find(".radio_button_row").removeClass("radio_button_row_background");
					cur.parent(".tour_offer_cls").find(".radio_button_row").find('input[type="radio"]').removeAttr("checked");
					cur.addClass("radio_button_row_background");
					cur.find('input[type="radio"]').prop("checked", true);
					cur.parents(".form-group").find(".default_price_div").html(cur.find('input[type="radio"]').attr('data-price'));
					showSuccess("Tour selected successfully.");
				}
			}
			else
			{
				showError("Please select pickup and dropoff time.");
			}
		}
		function calculate_time(cur, type)
		{
			if(type=="p")
			{
				var valuestart = cur.val();
				var valuestop=cur.parents(".calculate_time").find(".dropofftime").val();
			}
			else
			{
				var valuestart = cur.parents(".calculate_time").find(".pickuptime").val();
				var valuestop=cur.val();
			}
			//console.log(valuestart+"/"+valuestop);
			if(valuestart!="" && valuestop!="")
			{
				var selected_date=cur.parents(".calculate_time").find(".selected_booking_transfer_date").val();
				var timeStart = new Date(selected_date+" "+valuestart+":00");
				var timeEnd = new Date(selected_date+" "+valuestop+":00");
				if(timeEnd > timeStart)
				{
					var exists_msg="";
					//console.log($(".each_transfer_row_outer").not(cur.parents(".each_transfer_row_outer")).length);
					$(".each_transfer_row_outer").not(cur.parents(".each_transfer_row_outer")).each(function(){
						var other_valuestart = $(this).find(".calculate_time .pickuptime").val();
						var other_valuestop=$(this).find(".calculate_time .dropofftime").val();
						if(other_valuestart!="" && other_valuestop!="")
						{
							var other_selected_date=$(this).find(".calculate_time .selected_booking_transfer_date").val();
							var other_timeStart = new Date(other_selected_date+" "+other_valuestart+":00");
							var other_timeEnd = new Date(other_selected_date+" "+other_valuestop+":00");	
							/*console.log(other_timeStart);
							console.log(timeStart);
							console.log(other_timeEnd);
							console.log(timeEnd);
							console.log("aaa");*/
							if((other_timeStart>=timeStart && other_timeEnd<timeStart) || (other_timeStart<timeEnd && other_timeEnd>=timeEnd) || (other_timeStart>=timeStart && other_timeEnd<=timeEnd) || (other_timeStart<=timeStart && other_timeEnd<=timeEnd && other_timeEnd>=timeStart) || (other_timeStart>=timeStart && other_timeEnd>=timeEnd && other_timeStart<=timeStart))
							{
								exists_msg="You have transfer in this time.";
							}
						}
					});
					$(".each_tour_row_outer").each(function(){
						var other_valuestart = $(this).find(".calculate_tour_time .pickuptime").val();
						var other_valuestop=$(this).find(".calculate_tour_time .dropofftime").val();
						if(other_valuestart!="" && other_valuestop!="")
						{
							var other_selected_date=$(this).find(".calculate_tour_time .selected_booking_tour_date").val();
							var other_timeStart = new Date(other_selected_date+" "+other_valuestart+":00");
							var other_timeEnd = new Date(other_selected_date+" "+other_valuestop+":00");
							if((other_timeStart>=timeStart && other_timeEnd<timeStart) || (other_timeStart<timeEnd && other_timeEnd>=timeEnd) || (other_timeStart<=timeStart && other_timeEnd<=timeEnd) || (other_timeStart<=timeStart && other_timeEnd<=timeEnd && other_timeEnd>=timeStart) || (other_timeStart>=timeStart && other_timeEnd>=timeEnd && other_timeStart<=timeStart))
							{
								if(exists_msg=="")
									exists_msg="You have tour in this time.";
								else
									exists_msg="You have transfer and tour in this time.";
							}
						}
					});
					if(exists_msg=="" || confirm(exists_msg+" Are you sure you want to proceed?"))
					{
						var difference = timeEnd - timeStart;            
						//var diff_result = new Date(difference);
						var minuteDiff = difference/ (60*1000);
						var hourDiff = Math.floor(minuteDiff / 60) ;
						var minuteDiff = minuteDiff % 60 ;
						cur.parents(".calculate_time").find(".calculated_time_diff").text((hourDiff > 0 ? hourDiff+(hourDiff > 1 ? " hours " : " hour ") : "")+(minuteDiff > 0 ? minuteDiff+(minuteDiff > 1 ? " minutes" : " minute") : ""));
						var start_angle=(timeStart.getHours()*60+timeStart.getMinutes())*.5;
						//console.log(timeStart.getHours());
						//console.log(timeStart.getMinutes());
						//console.log((timeStart.getHours()*60+timeStart.getMinutes()));
						var start_point=(((timeStart.getHours()*60+timeStart.getMinutes())*644)/1440);
						//console.log(start_point);
						var end_angle=(timeEnd.getHours()*60+timeEnd.getMinutes())*.5;
						var end_point=(((timeEnd.getHours()*60+timeEnd.getMinutes())*644)/1440);
						//console.log(end_point);
						//var arc = describeArc(50, 28, 28, start_angle, end_angle);
						//alert(cur.parents(".calculate_time").find(".svg_path_id_input").length);
						if(start_angle < 360 && end_angle < 360)
						{
							var arc_am=describeArc(30, 17.8, 19, start_angle, end_angle);
							var arc_pm='';
						}
						else if(start_angle < 360 && end_angle > 359)
						{
							var arc_am=describeArc(30, 17.8, 19, start_angle, 359);
							var arc_pm=describeArc(30, 17.8, 19, 360, end_angle);
						}
						else if(start_angle > 359 && end_angle < 720)
						{
							var arc_am='';
							var arc_pm=describeArc(30, 17.8, 19, start_angle, end_angle);
						}
						if(cur.parents(".calculate_time").find(".svg_path_id_input").length)
						{
							$(".am_"+cur.parents(".calculate_time").find(".svg_path_id_input").val()).attr("d", arc_am);
							$(".pm_"+cur.parents(".calculate_time").find(".svg_path_id_input").val()).attr("d", arc_pm);
							//$("."+cur.parents(".calculate_time").find(".svg_path_id_input").val()).attr("x1", start_point).attr("x2", end_point);
							$(".each_date_div").each(function(){
								if($(this).attr("data-date_time")==cur.parents(".each_date_div").attr("data-date_time"))
								{
									$(this).find(".clock_am_div svg").html(cur.parents(".each_date_div").find(".date_heading_div .clock_am_div svg").html());
									$(this).find(".clock_pm_div svg").html(cur.parents(".each_date_div").find(".date_heading_div .clock_pm_div svg").html());
								}
							});
							$(".each_tour_date_div").each(function(){
								if($(this).attr("data-date_time")==cur.parents(".each_date_div").attr("data-date_time"))
								{
									$(this).find(".clock_am_div svg").html(cur.parents(".each_date_div").find(".date_heading_div .clock_am_div svg").html());
									$(this).find(".clock_pm_div svg").html(cur.parents(".each_date_div").find(".date_heading_div .clock_pm_div svg").html());
								}
							});
						}
						else
						{
							var svg_path_id=Math.floor((Math.random() * 1000) + 1);
							cur.parents(".calculate_time").append('<input type="hidden" name="svg_path_id_input_hidden" class="svg_path_id_input" value="'+svg_path_id+'">');
							//var prev_html=cur.parents(".each_date_div").find(".date_heading_div .clock svg").html();
							var prev_am_html=cur.parents(".each_date_div").find(".date_heading_div .clock_am_div svg").html();
							var prev_pm_html=cur.parents(".each_date_div").find(".date_heading_div .clock_pm_div svg").html();
							cur.parents(".each_date_div").find(".date_heading_div .clock_am_div svg").html(prev_am_html+'<path class="am_'+svg_path_id+'" fill="green" d="'+arc_am+'"/>');
							cur.parents(".each_date_div").find(".date_heading_div .clock_pm_div svg").html(prev_pm_html+'<path class="pm_'+svg_path_id+'" fill="green" d="'+arc_pm+'"/>');
							//cur.parents(".each_date_div").find(".date_heading_div .clock svg").html(prev_html+' <line x1="'+start_point+'" y1="0" x2="'+end_point+'" y2="0" class="'+svg_path_id+'"/>');
							$(".each_date_div").each(function(){
								if($(this).attr("data-date_time")==cur.parents(".each_date_div").attr("data-date_time"))
								{
									$(this).find(".clock_am_div svg").html(cur.parents(".each_date_div").find(".date_heading_div .clock_am_div svg").html());
									$(this).find(".clock_pm_div svg").html(cur.parents(".each_date_div").find(".date_heading_div .clock_pm_div svg").html());
								}
							});
							$(".each_tour_date_div").each(function(){
								if($(this).attr("data-date_time")==cur.parents(".each_date_div").attr("data-date_time"))
								{
									$(this).find(".clock_am_div svg").html(cur.parents(".each_date_div").find(".date_heading_div .clock_am_div svg").html());
									$(this).find(".clock_pm_div svg").html(cur.parents(".each_date_div").find(".date_heading_div .clock_pm_div svg").html());
								}
							});
						}
					}
					else
					{
						cur.val("");
						cur.parents(".calculate_time").find(".calculated_time_diff").text("--");
						if(cur.parents(".calculate_time").find(".svg_path_id_input").length)
						{
							$(".am_"+cur.parents(".calculate_time").find(".svg_path_id_input").val()).attr("d", "");
							$(".pm_"+cur.parents(".calculate_time").find(".svg_path_id_input").val()).attr("d", "");
							$(".each_date_div").each(function(){
								if($(this).attr("data-date_time")==cur.parents(".each_date_div").attr("data-date_time"))
								{
									$(this).find(".clock_am_div svg").html(cur.parents(".each_date_div").find(".date_heading_div .clock_am_div svg").html());
									$(this).find(".clock_pm_div svg").html(cur.parents(".each_date_div").find(".date_heading_div .clock_pm_div svg").html());
								}
							});
							$(".each_tour_date_div").each(function(){
								if($(this).attr("data-date_time")==cur.parents(".each_date_div").attr("data-date_time"))
								{
									$(this).find(".clock_am_div svg").html(cur.parents(".each_date_div").find(".date_heading_div .clock_am_div svg").html());
									$(this).find(".clock_pm_div svg").html(cur.parents(".each_date_div").find(".date_heading_div .clock_pm_div svg").html());
								}
							});
						}
					}
				}
				else
				{
					showError("Drop off time must be greater than pick up time");
					cur.parents(".calculate_time").find(".calculated_time_diff").text("--");
				}
			}
		}
		function calculate_tour_time(cur, type)
		{
			if(type=="p")
			{
				var valuestart = cur.val();
				var valuestop=cur.parent("div").find(".dropofftime").val();
			}
			else
			{
				var valuestart = cur.parent("div").find(".pickuptime").val();
				var valuestop=cur.val();
			}
			//console.log(valuestart+"/"+valuestop);
			if(valuestart!="" && valuestop!="")
			{
				var selected_date=cur.parent("div").find(".selected_booking_tour_date").val();
				var timeStart = new Date(selected_date+" "+valuestart+":00");
				var timeEnd = new Date(selected_date+" "+valuestop+":00");
				if(timeEnd > timeStart)
				{
					var exists_msg="";
					//console.log($(".each_transfer_row_outer").not(cur.parents(".each_transfer_row_outer")).length);
					$(".each_transfer_row_outer").each(function(){
						var other_valuestart = $(this).find(".calculate_time .pickuptime").val();
						var other_valuestop=$(this).find(".calculate_time .dropofftime").val();
						if(other_valuestart!="" && other_valuestop!="")
						{
							var other_selected_date=$(this).find(".calculate_time .selected_booking_transfer_date").val();
							var other_timeStart = new Date(other_selected_date+" "+other_valuestart+":00");
							var other_timeEnd = new Date(other_selected_date+" "+other_valuestop+":00");
							if((other_timeStart>=timeStart && other_timeEnd<timeStart) || (other_timeStart<timeEnd && other_timeEnd>=timeEnd) || (other_timeStart>=timeStart && other_timeEnd<=timeEnd) || (other_timeStart<=timeStart && other_timeEnd<=timeEnd && other_timeEnd>=timeStart) || (other_timeStart>=timeStart && other_timeEnd>=timeEnd && other_timeStart<=timeStart))
							{
								exists_msg="You have transfer in this time.";
							}
						}
					});
					$(".each_tour_row_outer").not(cur.parents(".each_tour_row_outer")).each(function(){
						var other_valuestart = $(this).find(".calculate_tour_time .pickuptime").val();
						var other_valuestop=$(this).find(".calculate_tour_time .dropofftime").val();
						if(other_valuestart!="" && other_valuestop!="")
						{
							var other_selected_date=$(this).find(".calculate_tour_time .selected_booking_tour_date").val();
							var other_timeStart = new Date(other_selected_date+" "+other_valuestart+":00");
							var other_timeEnd = new Date(other_selected_date+" "+other_valuestop+":00");
							if((other_timeStart>=timeStart && other_timeEnd<timeStart) || (other_timeStart<timeEnd && other_timeEnd>=timeEnd) || (other_timeStart<=timeStart && other_timeEnd<=timeEnd) || (other_timeStart<=timeStart && other_timeEnd<=timeEnd && other_timeEnd>=timeStart) || (other_timeStart>=timeStart && other_timeEnd>=timeEnd && other_timeStart<=timeStart))
							{
								if(exists_msg=="")
									exists_msg="You have tour in this time.";
								else
									exists_msg="You have transfer and tour in this time.";
							}
						}
					});
					if(exists_msg=="" || confirm(exists_msg+" Are you sure you want to proceed?"))
					{
						var difference = timeEnd - timeStart;            
						//var diff_result = new Date(difference);
						var minuteDiff = difference/ (60*1000);
						var hourDiff = Math.floor(minuteDiff / 60) ;
						var minuteDiff = minuteDiff % 60 ;
						cur.parent("div").find(".calculated_time_diff").text((hourDiff > 0 ? hourDiff+(hourDiff > 1 ? " hours " : " hour ") : "")+(minuteDiff > 0 ? minuteDiff+(minuteDiff > 1 ? " minutes" : " minute") : ""));
						var start_angle=(timeStart.getHours()*60+timeStart.getMinutes())*.5;
						var start_point=(((timeStart.getHours()*60+timeStart.getMinutes())*644)/1440);
						var end_angle=(timeEnd.getHours()*60+timeEnd.getMinutes())*.5;
						var end_point=(((timeEnd.getHours()*60+timeEnd.getMinutes())*644)/1440);
						//var arc = describeArc(50, 28, 28, start_angle, end_angle);
						//alert(cur.parent("div").find(".svg_path_id_input").length)
						if(start_angle < 360 && end_angle < 360)
						{
							var arc_am=describeArc(30, 17.8, 19, start_angle, end_angle);
							var arc_pm='';
						}
						else if(start_angle < 360 && end_angle > 359)
						{
							var arc_am=describeArc(30, 17.8, 19, start_angle, 359);
							var arc_pm=describeArc(30, 17.8, 19, 360, end_angle);
						}
						else if(start_angle > 359 && end_angle < 720)
						{
							var arc_am='';
							var arc_pm=describeArc(30, 17.8, 19, start_angle, end_angle);
						}
						if(cur.parent("div").find(".svg_path_id_input").length)
						{
							$(".am_"+cur.parent("div").find(".svg_path_id_input").val()).attr("d", arc_am);
							$(".pm_"+cur.parent("div").find(".svg_path_id_input").val()).attr("d", arc_pm);
							//$("."+cur.parent("div").find(".svg_path_id_input").val()).attr("x1", start_point).attr("x2", end_point);
							$(".each_date_div").each(function(){
								if($(this).attr("data-date_time")==cur.parents(".each_date_div").attr("data-date_time"))
								{
									$(this).find(".clock_am_div svg").html(cur.parents(".each_tour_date_div").find(".date_heading_div .clock_am_div svg").html());
									$(this).find(".clock_pm_div svg").html(cur.parents(".each_tour_date_div").find(".date_heading_div .clock_pm_div svg").html());
								}
							});
							$(".each_tour_date_div").each(function(){
								if($(this).attr("data-date_time")==cur.parents(".each_tour_date_div").attr("data-date_time"))
								{
									$(this).find(".clock_am_div svg").html(cur.parents(".each_tour_date_div").find(".date_heading_div .clock_am_div svg").html());
									$(this).find(".clock_pm_div svg").html(cur.parents(".each_tour_date_div").find(".date_heading_div .clock_pm_div svg").html());
								}
							});
						}
						else
						{
							var svg_path_id=Math.floor((Math.random() * 1000) + 1);
							cur.parent("div").append('<input type="hidden" name="svg_path_id_input_hidden" class="svg_path_id_input" value="'+svg_path_id+'">');
							//var prev_html=cur.parents(".each_tour_date_div").find(".date_heading_div .clock svg").html();
							var prev_am_html=cur.parents(".each_tour_date_div").find(".date_heading_div .clock_am_div svg").html();
							var prev_pm_html=cur.parents(".each_tour_date_div").find(".date_heading_div .clock_pm_div svg").html();
							cur.parents(".each_tour_date_div").find(".date_heading_div .clock_am_div svg").html(prev_am_html+'<path class="am_'+svg_path_id+'" fill="green" d="'+arc_am+'"/>');
							cur.parents(".each_tour_date_div").find(".date_heading_div .clock_pm_div svg").html(prev_pm_html+'<path class="pm_'+svg_path_id+'" fill="green" d="'+arc_pm+'"/>');
							//cur.parents(".each_tour_date_div").find(".date_heading_div .clock svg").html(prev_html+' <line x1="'+start_point+'" y1="0" x2="'+end_point+'" y2="0" class="'+svg_path_id+'"/>');
							$(".each_date_div").each(function(){
								if($(this).attr("data-date_time")==cur.parents(".each_date_div").attr("data-date_time"))
								{
									$(this).find(".clock_am_div svg").html(cur.parents(".each_tour_date_div").find(".date_heading_div .clock_am_div svg").html());
									$(this).find(".clock_pm_div svg").html(cur.parents(".each_tour_date_div").find(".date_heading_div .clock_pm_div svg").html());
								}
							});
							$(".each_tour_date_div").each(function(){
								if($(this).attr("data-date_time")==cur.parents(".each_tour_date_div").attr("data-date_time"))
								{
									$(this).find(".clock_am_div svg").html(cur.parents(".each_tour_date_div").find(".date_heading_div .clock_am_div svg").html());
									$(this).find(".clock_pm_div svg").html(cur.parents(".each_tour_date_div").find(".date_heading_div .clock_pm_div svg").html());
								}
							});
						}
					}
					else
					{
						cur.val("");
						cur.parents(".calculate_tour_time").find(".calculated_time_diff").text("--");
						if(cur.parents(".calculate_tour_time").find(".svg_path_id_input").length)
						{
							$(".am_"+cur.parents(".calculate_tour_time").find(".svg_path_id_input").val()).attr("d", "");
							$(".pm_"+cur.parents(".calculate_tour_time").find(".svg_path_id_input").val()).attr("d", "");
							$(".each_date_div").each(function(){
								if($(this).attr("data-date_time")==cur.parents(".each_tour_date_div").attr("data-date_time"))
								{
									$(this).find(".clock_am_div svg").html(cur.parents(".each_tour_date_div").find(".date_heading_div .clock_am_div svg").html());
									$(this).find(".clock_pm_div svg").html(cur.parents(".each_tour_date_div").find(".date_heading_div .clock_pm_div svg").html());
								}
							});
							$(".each_tour_date_div").each(function(){
								if($(this).attr("data-date_time")==cur.parents(".each_tour_date_div").attr("data-date_time"))
								{
									$(this).find(".clock_am_div svg").html(cur.parents(".each_tour_date_div").find(".date_heading_div .clock_am_div svg").html());
									$(this).find(".clock_pm_div svg").html(cur.parents(".each_tour_date_div").find(".date_heading_div .clock_pm_div svg").html());
								}
							});
						}
					}
				}
				else
				{
					showError("Drop off time must be greater than pick up time");
					cur.parent("div").find(".calculated_time_diff").text("--");
				}
			}
		}
		function polarToCartesian(centerX, centerY, radius, angleInDegrees) {
			var angleInRadians = (angleInDegrees-90) * Math.PI / 180.0;
			return {
				x: centerX + (radius * Math.cos(angleInRadians)),
				y: centerY + (radius * Math.sin(angleInRadians))
			};
		}

		function describeArc(x, y, radius, startAngle, endAngle){
			var start = polarToCartesian(x, y, radius, endAngle);
			var end = polarToCartesian(x, y, radius, startAngle);
			var arcSweep = endAngle - startAngle <= 180 ? "0" : "1";
			var d = [
				"M", start.x, start.y, 
				"A", radius, radius, 0, arcSweep, 0, end.x, end.y,
				"L", x,y,
				"L", start.x, start.y
			].join(" ");
			//console.log(d);
			return d;       
		}
		function delete_transfer_row(cur)
		{
			if(confirm("Are you sure you want to remove this row?"))
			{
				if(cur.parents(".each_date_div").find(".each_transfer_row_outer").length==1)
				{
					$(".am_"+cur.parent("div").find(".svg_path_id_input").val()).remove();
					$(".pm_"+cur.parent("div").find(".svg_path_id_input").val()).remove();
					cur.parents(".each_date_div").remove();
				}
				else
				{
					$(".am_"+cur.parent("div").find(".svg_path_id_input").val()).remove();
					$(".pm_"+cur.parent("div").find(".svg_path_id_input").val()).remove();
					cur.parents(".each_transfer_row_outer").remove();
				}
			}
		}
		function delete_tour_row(cur)
		{
			if(confirm("Are you sure you want to remove this row?"))
			{
				if(cur.parents(".each_tour_date_div").find(".each_tour_row_outer").length==1)
				{
					$(".am_"+cur.parent("div").find(".svg_path_id_input").val()).remove();
					$(".pm_"+cur.parent("div").find(".svg_path_id_input").val()).remove();
					cur.parents(".each_tour_date_div").remove();
				}
				else
				{
					$(".am_"+cur.parent("div").find(".svg_path_id_input").val()).remove();
					$(".pm_"+cur.parent("div").find(".svg_path_id_input").val()).remove();
					cur.parents(".each_tour_row_outer").remove();
				}
			}
		}
		function change_clock(cur)
		{
			if(cur.is(":checked"))
			{
				cur.parents(".clock_img_div").find(".clock_am_div").show();
				cur.parents(".clock_img_div").find(".clock_pm_div").hide();
			}
			else
			{
				cur.parents(".clock_img_div").find(".clock_am_div").hide();
				cur.parents(".clock_img_div").find(".clock_pm_div").show();
			}
			cur.parents(".date_heading_div").siblings().show();
		}
		function enable_disable_airport(cur)
		{
			if(cur.val()==1 || cur.val()==4)
			{
				cur.parents("form").find(".airport_all_div").show();
				cur.parents("form").find(".arr_dept_time_label").html("Arrival/Departure Time");
			}
			else
			{
				cur.parents("form").find(".airport_all_div").hide();
				cur.parents("form").find(".arr_dept_time_label").html("Pickup Time");
			}
		}
		function hide_show_transfer_details(cur)
		{
			cur.parent("div").find(".each_transfer_row_outer").toggle();
		}
		function hide_show_tour_details(cur)
		{
			cur.parent("div").find(".each_tour_row_outer").toggle();
		}
	//-->
	</script>
	<!-- JAVASCRIPT CODE -->
</head>
<body class="skin-purple">
	<div class="loader_inner"><img src="assets/img/spinner.gif" border="0" alt="Loading..."></div>
	<div class="wrapper">
      
		<!-- TOP HEADER -->
		<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'header.php');?>		
		<!-- TOP HEADER -->

		<!-- LEFT MENU -->
		<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'menu.php');?>
		<!-- LEFT MENU -->  
		
		<!-- BODY -->
		<div class="content-wrapper">
            <section class="content-header">
               <h1>Create New Booking</h1>
               <ol class="breadcrumb">
                  <li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                  <li class="active">Create New Booking</li>
               </ol>
            </section>
			<section class="content">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
							<section class="content">
								<div class="wizard" style="margin: 0px auto;">
									<div class="wizard-inner">
										<div class="connecting-line"></div>
										<ul class="nav nav-tabs" role="tablist" style="margin: 0px auto;">
											<li role="presentation" class="active">
												<a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" style="margin: 0px auto 9px;">
												<span class="round-tab">
													<i class="fa fa-bars fa-1x" ></i>
												</span>
												</a>
											</li>

											<li role="presentation" class="disabled">
												<a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" style="margin: 0px auto 9px;">
												<span class="round-tab">
													<i class="fa fa-bed fa-1x" ></i>
												</span>
												</a>
											</li>

											<li role="presentation" class="disabled">
												<a href="#step4" data-toggle="tab" aria-controls="step4" role="tab" style="margin: 0px auto 9px;">
												<span class="round-tab">										
													<i class="fa fa-car fa-1x" ></i>
												</span>
												</a>
											</li>

											<li role="presentation" class="disabled">
												<a href="#step3" data-toggle="tab" aria-controls="step3" role="tab" style="margin: 0px auto 9px;">
												<span class="round-tab">
													<i class="fa fa-road fa-1x" ></i>
												</span>
												</a>
											</li>

											<li role="presentation" class="disabled">
												<a href="#complete" data-toggle="tab" aria-controls="complete" role="tab" style="margin: 0px auto 9px;">
												<span class="round-tab">
													<i class="fa fa-shopping-cart fa-1x" ></i>
												</span>
												</a>
											</li>
										</ul>
									</div>

									<!-- <form role="form"> -->
										<div class="tab-content">
											<div class="tab-pane active" role="tabpanel" id="step1">
												<h3>Select Criteria For New Booking</h3>
												<form name="form_first_step" id="form_first_step" method="post" enctype="mulitipart/form-data">
													<div class="col-md-12 row">
														<div class="box-body" style = "border:1px solid gray;padding: 3px;">
															<div class="form-group col-md-3">
																<label for="inputName" class="control-label">Select Booking Type<font color="#FF0000">*</font></label>
																<select name = "booking_type" id = "booking_type" class="form-control validate[required]"  tabindex = "1" onchange = "manage_booking_type(this.value);">
																	<option value = "personal" <?php echo(isset($_POST['booking_type']) && $_POST['booking_type']=="personal" ? "selected='selected'" : "");?>>Personal Booking</option>
																	<option value = "agent" <?php echo(isset($_POST['booking_type']) && $_POST['booking_type']=="agent" ? "selected='selected'" : (isset($agent_data) &&  !empty($agent_data) ? "selected='selected'" : ""));?>>Agent Booking</option>
																</select>
															</div>
															<div class="form-group col-md-3">
																<label for="inputName" class="control-label">Select Agent<font color="#FF0000">*</font></label>
																<select name = "agent_name" id = "agent_name" class="form-control js-example-basic-single validate[required]"  tabindex = "2" <?php echo(isset($_POST['agent_name']) && $_POST['agent_name']!="" ? '' : 'disabled');?>>
																	<option value = "">Select Agent</option>
																<?php
																if(isset($agent_list) && !empty($agent_list)):
																	foreach($agent_list as $agent_key=>$agent_val):
																?>
																	<option  value = "<?php echo $agent_val['id'];?>" <?php echo(isset($_POST['agent_name']) && $_POST['agent_name']==$agent_val['id'] ? 'selected="selected"' : (isset($agent_data) && !empty($agent_data) && $agent_data['id']==$agent_val['id'] ? 'selected="selected"' : ""));?>><?php echo $agent_val['first_name'].($agent_val['middle_name']!="" ? " ".$agent_val['middle_name'] : "")." ".$agent_val['last_name']." - ".$agent_val['code'];?></option>
																<?php
																	endforeach;
																endif;
																?>
																</select>
															</div>
															<div class="form-group col-md-3">
																<label for="inputName" class="control-label">Check In<font color="#FF0000">*</font></label>
																<input type="text" class="form-control datepicker validate[required]"  value="<?php echo(isset($_POST['checkin']) && $_POST['checkin']!='' ? $_POST['checkin'] : "");?>" name="checkin" id="checkin" placeholder="Check In" tabindex = "3" autocomplete="off"/>
															</div>
															<div class="form-group col-md-3">
																<label for="inputName" class="control-label">Check Out<font color="#FF0000">*</font></label>
																<input type="text" class="form-control datepicker validate[required]"  value="<?php echo(isset($_POST['checkout']) && $_POST['checkout']!='' ? $_POST['checkout'] : "");?>" name="checkout" id="checkout" placeholder="Check Out" tabindex = "4" autocomplete="off"/>
															</div>
															<div class="clearfix"></div>
														</div>
														<div class="box-body" style="padding: 3px;"></div>
														<div class="box-body" id = "sample"  style = "border:1px solid gray;padding: 3px;">
															<?php
															if(isset($_POST['country']) && !empty($_POST['country']))
															{
																foreach($_POST['country'] as $post_country_key=>$post_country_val)
																{
															?>
															<div class="form-group <?php echo($post_country_key > 0 ? "appended_row" : "");?> each_city_row">
																<div class="form-group col-md-2">
																	<?php
																	if($post_country_key > 0)
																	{
																	?>
																	<input type="checkbox" name="record"/>&nbsp;&nbsp;
																	<?php
																	}
																	?>
																	<label for="inputName" class="control-label">Country<font color="#FF0000">*</font></label>
																	<select name="country[<?php echo $post_country_key;?>]" class="form-control validate[required]" id="country0" onchange="fetch_city($(this).val(), <?php echo $post_country_key;?>);">
																		<option value = "">Select Country</option>
																	<?php
																	if(!empty($contry_list)):
																		foreach($contry_list as $country_key=>$country_val):
																	?>
																		<option value = "<?php echo $country_val['id'];?>" <?php echo($post_country_val==$country_val['id'] ? 'selected="selected"' : "");?>><?php echo $country_val['name'];?></option>
																	<?php
																		endforeach;
																	endif;
																	?>
																	</select>
																</div>
																<div class="form-group col-md-2">
																	<label for="inputName" class="control-label">City<font color="#FF0000">*</font></label>
																	<select class="form-control validate[required]" name="city[<?php echo $post_country_key;?>]" id="city<?php echo $post_country_key;?>" onchange="find_hotel_name($(this))">
																		<option value="">Select City</option>
																	<?php
																	$state_list = tools::find("first", TM_STATES, 'GROUP_CONCAT(id) as state_ids', "WHERE country_id=:country_id ORDER BY name ASC ", array(":country_id"=>$post_country_val));
																	$city_list=array();
																	if(!empty($state_list))
																	{
																		$city_list = tools::find("all", TM_CITIES, '*', "WHERE state_id IN (".$state_list['state_ids'].") ORDER BY name ASC ", array());
																	}
																	foreach($city_list as $city_key=>$city_val)
																	{
																	?>
																	<option value = "<?php echo $city_val['id'];?>" <?php echo(isset($_POST['city']) && !empty($_POST['city']) && $_POST['city'][$post_country_key]==$city_val['id'] ? 'selected="selected"' : "");?>><?php echo $city_val['name'];?></option>
																	<?php
																	}
																	?>
																	</select>
																</div>
																<div class="form-group col-md-2">
																	<label for="inputName" class="control-label">For No Of Nights<font color="#FF0000">*</font></label>
																	<input type="text" class="form-control number_of_night validate[required, custom[integer]]"  value="<?php echo(isset($_POST['number_of_night'][$post_country_key]) && $_POST['number_of_night'][$post_country_key]!='' ? $_POST['number_of_night'][$post_country_key] : "");?>" name="number_of_night[<?php echo $post_country_key;?>]" id="number_of_night<?php echo $post_country_key;?>" placeholder="Number Of Nights" tabindex = "4" onblur="check_night()"/>
																</div>
																<div class="form-group col-md-2" style="">
																	<label for="inputName" class="control-label">Hotel Type</label>
																	<select class="form-control hotel_type" name="hotel_type[<?php echo $post_country_key;?>]" id="first_hotel_type<?php echo $post_country_key;?>" onchange="find_hotel_name($(this))">
																		<option value="">All</option>
																<?php
																if(isset($global_hotel_type_arr) && !empty($global_hotel_type_arr)):
																	foreach($global_hotel_type_arr as $hotel_type_key=>$hotel_type_val):
																?>
																		<option value="<?php echo $hotel_type_key;?>" <?php echo(isset($_POST['hotel_type']) && !empty($_POST['hotel_type']) && $_POST['hotel_type'][$post_country_key]==$hotel_type_key ? 'selected="selected"' : "");?>><?php echo $hotel_type_val;?></option>
																<?php
																	endforeach;
																endif;
																?>
																	</select>
																</div>
																<div class="form-group col-md-2">
																	<label for="inputName" class="control-label">Hotel Ratings<font color="#FF0000">*</font></label>
																	<br/>
																	<input type="checkbox" name="all_checkbox" class="all_checkbox" onclick="check_all_rating($(this))">&nbsp;All&nbsp;&nbsp;
																	<input type="checkbox" value="1" name="hotel_ratings[<?php echo $post_country_key;?>][]" <?php echo(isset($_POST['hotel_ratings']) && !empty($_POST['hotel_ratings']) && isset($_POST['hotel_ratings'][$post_country_key]) && in_array(1, $_POST['hotel_ratings'][$post_country_key]) ? 'checked="checked"' : "");?> class="validate[minCheckbox[1]]"/>&nbsp;1&nbsp;&nbsp;<input type="checkbox" value="2" name="hotel_ratings[<?php echo $post_country_key;?>][]" <?php echo(isset($_POST['hotel_ratings']) && !empty($_POST['hotel_ratings']) && isset($_POST['hotel_ratings'][$post_country_key]) && in_array(2, $_POST['hotel_ratings'][$post_country_key]) ? 'checked="checked"' : "");?> class="validate[minCheckbox[1]]"/>&nbsp;2&nbsp;&nbsp;<input type="checkbox"  value="3" name="hotel_ratings[<?php echo $post_country_key;?>][]" <?php echo(isset($_POST['hotel_ratings']) && !empty($_POST['hotel_ratings']) && isset($_POST['hotel_ratings'][$post_country_key]) && in_array(3, $_POST['hotel_ratings'][$post_country_key]) ? 'checked="checked"' : "");?> class="validate[minCheckbox[1]]"/>&nbsp;3&nbsp;&nbsp;<input type="checkbox" value="4" name="hotel_ratings[<?php echo $post_country_key;?>][]" <?php echo(isset($_POST['hotel_ratings']) && !empty($_POST['hotel_ratings']) && isset($_POST['hotel_ratings'][$post_country_key]) && in_array(4, $_POST['hotel_ratings'][$post_country_key]) ? 'checked="checked"' : "");?> class="validate[minCheckbox[1]]"/>&nbsp;4&nbsp;&nbsp;<input type="checkbox" value="5" name="hotel_ratings[<?php echo $post_country_key;?>][]" <?php echo(isset($_POST['hotel_ratings']) && !empty($_POST['hotel_ratings']) && isset($_POST['hotel_ratings'][$post_country_key]) && in_array(5, $_POST['hotel_ratings'][$post_country_key]) ? 'checked="checked"' : "");?> class="validate[minCheckbox[1]]"/>&nbsp;5
																</div>
																<div class="form-group col-md-2" style="">
																	<label for="inputName" class="control-label">Hotels</label>
																	<select class="form-control first_page_hotel" name="first_page_hotel[<?php echo $post_country_key;?>]" id="first_page_hotel<?php echo $post_country_key;?>" onchange="checked_hotel_rating($(this))">
																		<option value="">Select Hotel</option>
																	</select>
																</div>
																<div class="clearfix"></div>
															</div>
															<?php
																}
																$next_index=$post_country_key+1;
															}
															else
															{
																$next_index=1;
															?>
															<div class="form-group each_city_row">
																<div class="form-group col-md-2">
																	<label for="inputName" class="control-label">Country<font color="#FF0000">*</font></label>
																	<select name="country[0]" class="form-control validate[required]" id="country0" onchange="fetch_city($(this).val(), 0);">
																		<option value = "">Select Country</option>
																	<?php
																	if(!empty($contry_list)):
																		foreach($contry_list as $country_key=>$country_val):
																	?>
																		<option value = "<?php echo $country_val['id'];?>"><?php echo $country_val['name'];?></option>
																	<?php
																		endforeach;
																	endif;
																	?>
																	</select>
																</div>
																<div class="form-group col-md-2">
																	<label for="inputName" class="control-label">City<font color="#FF0000">*</font></label>
																	<select class="form-control validate[required]" name="city[0]" id="city0" onchange="find_hotel_name($(this))">
																		<option value="">Select City</option>
																	</select>
																</div>
																<div class="form-group col-md-2">
																	<label for="inputName" class="control-label">For No Of Nights<font color="#FF0000">*</font></label>
																	<input type="text" class="form-control number_of_night validate[required]"  value="" name="number_of_night[0]" id="number_of_night0" placeholder="Number Of Nights" tabindex = "4" onblur="check_night()"/>
																</div>
																<div class="form-group col-md-2" style="">
																	<label for="inputName" class="control-label">Hotel Type</label>
																	<select class="form-control hotel_type" name="hotel_type[0]" id="first_hotel_type0" onchange="find_hotel_name($(this), 'type')">
																		<option value="">All</option>
																<?php
																if(isset($global_hotel_type_arr) && !empty($global_hotel_type_arr)):
																	foreach($global_hotel_type_arr as $hotel_type_key=>$hotel_type_val):
																?>
																		<option value="<?php echo $hotel_type_key;?>" ><?php echo $hotel_type_val;?></option>
																<?php
																	endforeach;
																endif;
																?>
																	</select>
																</div>
																<div class="form-group col-md-2">
																	<label for="inputName" class="control-label">Hotel Ratings<font color="#FF0000">*</font></label>
																	<br/>
																	<input type="checkbox" name="all_checkbox" class="all_checkbox" onclick="check_all_rating($(this))">&nbsp;All&nbsp;&nbsp;
																	<input type="checkbox" value="1" name="hotel_ratings[0][]" class="validate[minCheckbox[1]]"/>&nbsp;1&nbsp;&nbsp;<input type="checkbox" value="2" name="hotel_ratings[0][]" class="validate[minCheckbox[1]]"/>&nbsp;2&nbsp;&nbsp;<input type="checkbox"  value="3" name="hotel_ratings[0][]" class="validate[minCheckbox[1]]"/>&nbsp;3&nbsp;&nbsp;<input type="checkbox" value="4" name="hotel_ratings[0][]" class="validate[minCheckbox[1]]"/>&nbsp;4&nbsp;&nbsp;<input type="checkbox" class="validate[minCheckbox[1]]"value="5" name="hotel_ratings[0][]" />&nbsp;5
																</div>
																<div class="form-group col-md-2" style="">
																	<label for="inputName" class="control-label">Hotels</label>
																	<select class="form-control first_page_hotel" name="first_page_hotel[0]" id="first_page_hotel0" onchange="checked_hotel_rating($(this))">
																		<option value="">Select Hotel</option>
																	</select>
																</div>
																<div class="clearfix"></div>
															</div>
															<?php
															}
															?>
															<div class="clearfix"></div>
														</div>
														<div class="box-body" style="padding: 3px;">
															<div class="form-group">
																<a href = "javascript:void(0);" class="add-row" data-attr_key="<?php echo $next_index;?>"><img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>plus-icon.png" border = "0" alt = "" /></a>&nbsp;&nbsp;<b>ADD ANOTHER DESTINATION</b>&nbsp;&nbsp;<a href = "javascript:void(0);" class="delete-row"><img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>minus-icon.png" border = "0" alt = "" /></a>
															</div>
														</div>
														<div class="box-body" style="padding: 3px;"></div>
														<div class="box-body" style = "border:1px solid gray;padding: 3px;">
															<div class="form-group col-md-4">
																<label for="inputName" class="control-label">Nationality<font color="#FF0000">*</font></label>
																<select class="form-control validate[required]" name="sel_nationality" id="sel_nationality"> 
																	<option value = "">Select Nationality</option>
																	<?php
																	if(!empty($contry_list)):
																		foreach($contry_list as $country_key=>$country_val):
																	?>
																		<option value = "<?php echo $country_val['id'];?>" <?php echo(isset($_POST['sel_nationality']) && $_POST['sel_nationality']==$country_val['id'] ? 'selected="selected"' : (!isset($_POST['sel_nationality']) && $country_val['id']==101 ? 'selected="selected"' : ""));?>><?php echo $country_val['name'];?></option>
																	<?php
																		endforeach;
																	endif;
																	?>
																</select>
															</div>
															<div class="form-group col-md-4">
																<label for="inputName" class="control-label">Country Of Residence<font color="#FF0000">*</font></label>
																<select class="form-control validate[required]" name="country_residance" id="country_residance"> 
																	<option value = "">Select Country Of Residence</option>
																<?php
																if(!empty($contry_list)):
																	foreach($contry_list as $country_key=>$country_val):
																?>
																	<option value = "<?php echo $country_val['id'];?>" <?php echo(isset($_POST['country_residance']) && $_POST['country_residance']==$country_val['id'] ? 'selected="selected"' : (!isset($_POST['country_residance']) && $country_val['id']==101 ? 'selected="selected"' : ""));?>><?php echo $country_val['name'];?></option>
																<?php
																	endforeach;
																endif;
																?>
																</select>
															</div>
															<div class="form-group col-md-4">
																<label for="inputName" class="control-label">Invoice Currency<font color="#FF0000">*</font></label>
																<select class="form-control validate[required]" name="sel_currency" id="selected_currency">
																	<option value="">Select</option>
																<?php
																if(!empty($currency_list)):
																	foreach($currency_list as $currency_key=>$currency_val):
																?>
																	<option value = "<?php echo $currency_val['id'];?>" <?php echo(isset($_POST['sel_currency']) && $_POST['sel_currency']==$currency_val['id'] ? 'selected="selected"' : "");?>><?php echo $currency_val['currency_name']." (".$currency_val['currency_code'].")";?></option>
																<?php
																	endforeach;
																endif;
																?>
																</select>
															</div>
															<div class="clearfix"></div>
															<div class="form-group col-md-2">
																<label for="inputName" class="control-label">Number Of Rooms<font color="#FF0000">*</font></label>
																<select class="form-control validate[required]" name="rooms" id="rooms"> 
																<?php
																for($rooom_no=1;$rooom_no<=MAX_ROOM_NO;$rooom_no++)
																{
																?>
																	<option label="<?php echo $rooom_no;?>" value="<?php echo $rooom_no;?>" <?php echo(isset($_POST['rooms']) && $_POST['rooms']==$rooom_no ? 'selected="selected"' : "");?>><?php echo $rooom_no;?></option>
																<?php
																}
																?>
																</select>
															</div>
															<div class="col-md-10 all_adult_child_div">
															<?php
															if(isset($_POST['rooms']) && $_POST['rooms']!="")
															{
																for($i=0;$i<$_POST['rooms'];$i++)
																{
															?>
																<div class="row each_adult_child_div">
																	<div class="form-group col-md-3">
																		<label for="inputName" class="control-label">Adult<font color="#FF0000">*</font></label>
																		<select class="form-control validate[required]" name="adult[<?php echo $i;?>]" id="adult<?php echo $i;?>"> 
																		<?php
																		for($adult_no=1;$adult_no<=MAX_ADULT_NO;$adult_no++)
																		{
																		?>
																			<option label="<?php echo $adult_no;?>" value="<?php echo $adult_no;?>" <?php echo(isset($_POST['adult'][$i]) && $_POST['adult'][$i]==$adult_no ? 'selected="selected"' : "");?>><?php echo $adult_no;?></option>
																		<?php
																		}
																		?>
																		</select>
																	</div>
																	<div class="form-group col-md-3">
																		<label for="inputName" class="control-label">Child</label>
																		<select class="form-control validate[optional]" name="child[<?php echo $i;?>]" id="child<?php echo $i;?>" onchange = "child_attribute($(this), <?php echo $i;?>);"> 
																			<option value="">Select</option>
																		<?php
																		for($child_no=1;$child_no<=MAX_CHILD_NO;$child_no++)
																		{
																		?>
																			<option label="<?php echo $child_no;?>" value="<?php echo $child_no;?>" <?php echo(isset($_POST['child'][$i]) && $_POST['child'][$i]==$child_no ? 'selected="selected"' : "");?>><?php echo $child_no;?></option>
																		<?php
																		}
																		?>
																		</select>
																	</div>
																	<?php
																	if(isset($_POST['child_age'][$i]) && !empty($_POST['child_age'][$i]))
																	{
																	?>
																	<div class="col-md-6 all_child_age_div<?php echo $i;?>">
																	<?php
																		foreach($_POST['child_age'][$i] as $child_age_key=>$child_age_val)
																		{
																	?>
																		<div class="row each_child_age_div">
																			<div class="form-group col-md-4">
																				<label for="inputName" class="control-label">Age</label>
																				<div class="form-group">
																					<select class="form-control validate[optional]" name="child_age[<?php echo $i;?>][<?php echo $child_age_key;?>]" id="child_age<?php echo $i;?><?php echo $child_age_key;?>">
																					<?php
																					for($child_loop=1;$child_loop<=MAX_CHILD_AGE;$child_loop++)
																					{
																					?>
																						<option label="<?php echo $child_loop;?>" value="<?php echo $child_loop;?>" <?php echo($child_age_val==$child_loop ? 'selected="selected"' : "");?>><?php echo $child_loop;?></option>
																					<?php
																					}
																					?>
																					</select>
																				</div>
																			</div>
																			<div class="form-group col-md-8">
																				<label for="inputName" class="control-label">Additional Bed Required</label>
																				<select class="form-control validate[optional]" name="bed_required[<?php echo $i;?>][<?php echo $child_age_key;?>]" id="bed_required<?php echo $i;?><?php echo $child_age_key;?>">
																					<option value="Yes" <?php echo(isset($_POST['bed_required'][$i][$child_age_key]) && isset($_POST['bed_required'][$i][$child_age_key])&& $_POST['bed_required'][$i][$child_age_key]=="Yes" ? 'selected="selected"' : "");?>>Yes</option>
																					<option value="No" <?php echo(isset($_POST['bed_required'][$i][$child_age_key]) && isset($_POST['bed_required'][$i][$child_age_key])&& $_POST['bed_required'][$i][$child_age_key]=="No" ? 'selected="selected"' : "");?>>No</option>
																				</select>
																			</div>
																		</div>
																		<div class="clearfix"></div>
																	<?php
																		}
																	?>
																	</div>
																	<?php
																	}
																	?>
																	<div class="clearfix"></div>
																</div>
															<?php
																}
															}
															else
															{
															?>
																<div class="row each_adult_child_div">
																	<div class="form-group col-md-3">
																		<label for="inputName" class="control-label">Adult<font color="#FF0000">*</font></label>
																		<select class="form-control validate[required]" name="adult[0]" id="adult0"> 
																		<?php
																		for($adult_no=1;$adult_no<=MAX_ADULT_NO;$adult_no++)
																		{
																		?>
																			<option label="<?php echo $adult_no;?>" value="<?php echo $adult_no;?>"><?php echo $adult_no;?></option>
																		<?php
																		}
																		?>
																		</select>
																	</div>
																	<div class="form-group col-md-3">
																		<label for="inputName" class="control-label">Child</label>
																		<select class="form-control validate[optional]" name="child[0]" id="child0" onchange = "child_attribute($(this), 0);"> 
																			<option value="">Select</option>
																		<?php
																		for($child_no=1;$child_no<=MAX_CHILD_NO;$child_no++)
																		{
																		?>
																			<option label="<?php echo $child_no;?>" value="<?php echo $child_no;?>"><?php echo $child_no;?></option>
																		<?php
																		}
																		?>
																		</select>
																	</div>
																	<div class="col-md-6 hide_age_div all_child_age_div0">
																		
																	</div>
																	<div class="clearfix"></div>
																</div>
															<?php
															}
															?>
															</div>
															<div class="clearfix"></div>
														</div>
														<div class="clearfix"></div>
													</div>
													<div class="clearfix"></div>
													<ul class="list-inline pull-right" style = "margin-top:25px;">
														<li><button type="submit" class="btn btn-primary ">Search</button></li>
													</ul>
												</form>
											</div>
											<div class="tab-pane" role="tabpanel" id="step2">
												<h3>Search Hotels</h3>
												<!-- <form name="form_secend_step" id="form_secend_step" method="POST" enctype="multipart/form-data"> -->
													<div class="city_tab_button_div">
														<!-- City Tab -->
													</div>
													<div class="main_tab_content_outer hotel_tab_all_data_div">
														<!-- All tab content -->
													</div>
													<ul class="list-inline pull-right">
														<li><button type="button" class="btn btn-warning prev-step">Modify Search</button></li>
														<li><button type="button" class="btn btn-primary save_step2_data">Save and continue</button></li>
													</ul>
												<!-- </form> -->
											</div>
											<div class="tab-pane" role="tabpanel" id="step4">
												<h3>Search Transfers</h3>
												<div class="transfer_city_tab_button_div">
													<!-- Transfer City Tab -->
												</div>
												<div class="main_tab_content_outer transfer_tab_all_data_div">
													<!-- All Transfer tab content -->
												</div>
												<ul class="list-inline pull-right">
													<li><button type="button" class="btn btn-warning prev-step">Back To Hotel List</button></li>
													<li><button type="button" class="btn btn-default save_step4_data">Skip</button></li>
													<li><button type="button" class="btn btn-primary btn-info-full save_step4_data">Save and continue</button></li>
												</ul>
											</div>
											<div class="tab-pane" role="tabpanel" id="step3">
												<h3>Search Tour</h3>
												<div class="tour_city_tab_button_div">
													<!-- Tour City Tab -->
												</div>
												<div class="main_tab_content_outer tour_tab_all_data_div">
													<!-- All tour tab content -->
												</div>
												<ul class="list-inline pull-right">
													<li><button type="button" class="btn btn-warning prev-step">Manage Transfer</button></li>
													<li><button type="button" class="btn btn-default save_step3_data">Skip</button></li>
													<li><button type="button" class="btn btn-primary btn-info-full save_step3_data">Save and continue</button></li>
												</ul>
											</div>
											<div class="tab-pane" role="tabpanel" id="complete">
												<h3>Complete Your Booking</h3>
												<div class="col-md-12 row">
													<div id="final_step_html">
														<!-- Final step display content -->
													</div>
													<div class="box-body">
														<div class="form-group col-md-6">
															<form method="post" action="" name="quotation_name_form" id="quotation_name_form">
																<label for="inputName" class="control-label">Quotation Name<font color="#FF0000">*</font></label>
																<br/>
																<div style = "float:left;">
																	<input type="text" class="form-control validate[required, custom[onlyLetterNumber]]"  value="" name="quotation_name" id="quotation_name" placeholder="Quotation Name" tabindex = "1"  />
																</div>
																<div style = "float:left;">
																	&nbsp;<button type="submit" class="btn btn-primary quotation_name_save_btn">Save</button>
																</div>
																<div class = "clearfix"></div>
															</form>
														</div>
														<div class="form-group col-md-6">
															<form method="post" action="" name="payment_method_form" id="payment_method_form">
																<label for="inputName" class="control-label pay_dropdown_div">Choose Payment Method<font color="#FF0000">*</font></label>
																<label for="inputName" class="control-label pay_dropdown_div_1" style="display:none;">--</label>
																<br/>
																<div style = "float:left;" class="pay_dropdown_div">
																	<select class="form-control validate[optional]" name="sel_avlbl_hotel">
																		<option value="Pay">Pay</option>
																	</select>
																	<input type="hidden" name="total_price" id="total_price" value="">
																</div>
																<div style = "float:left;">
																	&nbsp;<button type="submit" class="btn btn-primary payment_method_save_btn">Continue</button>
																</div>
																<div class = "clearfix"></div>
															</form>
														</div>
													</div>
												</div>
											</div>
											<div class="clearfix"></div>
										</div>
									<!-- </form> -->
								</div>
							</section>
							
						</div>
					</div>
				</div>
			</section>
		</div>
        <!-- BODY -->

        <!-- FOOTER -->
		<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'footer.php');?>
		<!-- FOOTER -->

	</div>
</body>
</html>