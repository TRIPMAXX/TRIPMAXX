<?php
	require_once('loader.inc');
	if(isset($_GET['booking_id']) && $_GET['booking_id']!=""):
		$autentication_data_booking=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.BOOKING_API_PATH."authorized.php"));
		if(isset($autentication_data_booking->status)):
			if($autentication_data_booking->status=="success"):
				$post_data_booking['token']=array(
					"token"=>$autentication_data_booking->results->token,
					"token_timeout"=>$autentication_data_booking->results->token_timeout,
					"token_generation_time"=>$autentication_data_booking->results->token_generation_time
				);
				$post_data_booking['data']['booking_id']=base64_decode($_GET['booking_id']);
				$post_data_str_booking=json_encode($post_data_booking);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.BOOKING_API_PATH."booking/read.php");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_booking);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				$return_data_booking = curl_exec($ch);
				curl_close($ch);
				$return_data_arr_booking=json_decode($return_data_booking, true);
				if(!isset($return_data_arr_booking['status'])):
					$data['status'] = 'error';
					$data['msg']="Some error has been occure during execution.";
				elseif($return_data_arr_booking['status']=="success"):
					$booking_details_list=$return_data_arr_booking['results'][0];
				else:
					$data['status'] = 'error';
					$data['msg'] = $return_data_arr_booking['msg'];
				endif;
			endif;
		else:
			$data['status'] = 'error';
			$data['msg'] = $autentication_data->msg;
		endif;
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
					$post_data['data']=$_GET;
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
	else:
		$_SESSION['SET_TYPE'] = 'error';
		$_SESSION['SET_FLASH'] = 'Some data missing.';
		header("location:bookings");
		exit;
	endif;
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>EDIT BOOKING</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
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
		.city_tab_button_div, .tour_city_tab_button_div, .transfer_city_tab_button_div{margin-bottom: 20px;}
		.cls_each_city_hotel_tab_div, .cls_each_city_tour_tab_div, .cls_each_city_transfer_tab_div{
			padding: 5px;
			text-align: center;
			border:1px solid rgba(255, 0, 0, 0.32);
			background: #868484;
			color: white;
			font-size: 18px;
			cursor:pointer;
		}
		.cls_each_city_tab_div_active{
			background: #5bc0de;
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
								fetch_step3_rcd(page, type);
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
			});
			$(".save_step3_data").click(function(){
				var tour_offer_arr=[];
				$('input[class="selected_offer"]:checked').each(function(){
					tour_offer_arr.push($(this).val());
				});
				$.ajax({
					url:'<?= DOMAIN_NAME_PATH_ADMIN."ajax_booking_step3_execute";?>',
					type:"post",
					data:{
						tour_offer_arr:tour_offer_arr
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
							fetch_step4_rcd(page, type);
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
			});
			$(".save_step4_data").click(function(){
				var transfer_offer_arr=[];
				$('input[class="selected_transfer"]:checked').each(function(){
					transfer_offer_arr.push($(this).val());
				});
				$.ajax({
					url:'<?= DOMAIN_NAME_PATH_ADMIN."ajax_booking_step4_execute";?>',
					type:"post",
					data:{
						transfer_offer_arr:transfer_offer_arr
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
							var $active = $('.wizard .nav-tabs li.active');
							$active.next().removeClass('disabled');
							$active.next().find('a[data-toggle="tab"]').click();
							fetch_step5_data();
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
			}
			else {
				$(".all_child_age_div").html(child_number_html);
				$('.all_child_age_div').hide();
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
			if(cur.attr('previousValue') == 'true')
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
			}
		}
		function change_transfer_radio(cur)
		{
			if(cur.attr('previousValue') == 'true')
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
			}
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
			var search_val=$("#tour_keyword_search"+city_id).val();
			var country_id=cur.attr("data-country_id");
			var sort_order=cur.parents("#tour_city"+city_id).find("input[name='tour_sort']:checked").val();
			var page=1;
			var type=3;
			fetch_step3_rcd(page, type, sort_order, city_id, country_id, search_val);
		}
		function filter_transfer_search(cur, city_id)
		{
			var search_val=$("#transfer_keyword_search"+city_id).val();
			var country_id=cur.attr("data-country_id");
			var sort_order=cur.parents("#transfer_city"+city_id).find("input[name='transfer_sort']:checked").val();
			var page=1;
			var type=3;
			fetch_step4_rcd(page, type, sort_order, city_id, country_id, search_val);
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
		function fetch_step3_rcd(page, type, sort_order='', city_id='', country_id='', search_val='')
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
				}
			});
		}
		function fetch_step4_rcd(page, type, sort_order='', city_id='', country_id='', search_val='')
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

			$(document).ready(function(){
				$(".add-row").click(function(){
					var new_row_key=$(this).attr("data-attr_key");
					$(this).attr("data-attr_key", eval(new_row_key)+1);
					var markup = '';
					markup+='<div class="form-group col-md-12 appended_row">';
						markup+='<div class="form-group col-md-3">';
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
						markup+='<div class="form-group col-md-3">';
							markup+='<label for="inputName" class="control-label">City<font color="#FF0000">*</font></label>';
							markup+='<select class="form-control validate[required]" name="city['+new_row_key+']" id="city'+new_row_key+'">';
								markup+='<option value="">Select City</option>';
							markup+='</select>';
						markup+='</div>';
						markup+='<div class="form-group col-md-3"><label for="inputName" class="control-label">For No Of Nights<font color="#FF0000">*</font></label><input type="text" class="form-control number_of_night validate[required]"  value="" name="number_of_night['+new_row_key+']" id="number_of_night'+new_row_key+'" placeholder="Number Of Nights" tabindex = "4" onblur="check_night()"/></div>';
						markup+='<div class="form-group col-md-3"><label for="inputName" class="control-label">Hotel Ratings<font color="#FF0000">*</font></label><br/><input type="checkbox" value="1" name="hotel_ratings['+new_row_key+'][]" class="validate[minCheckbox[1]]"/>&nbsp;1&nbsp;&nbsp;<input type="checkbox" value="2" name="hotel_ratings['+new_row_key+'][]" class="validate[minCheckbox[1]]"/>&nbsp;2&nbsp;&nbsp;<input type="checkbox"  value="3" name="hotel_ratings['+new_row_key+'][]" class="validate[minCheckbox[1]]"/>&nbsp;3&nbsp;&nbsp;<input type="checkbox" value="4" name="hotel_ratings['+new_row_key+'][]" class="validate[minCheckbox[1]]"/>&nbsp;4&nbsp;&nbsp;<input type="checkbox" value="5" name="hotel_ratings['+new_row_key+'][]" class="validate[minCheckbox[1]]"/>&nbsp;5</div>';
					markup+='</div>';
					markup+='<div class="clearfix"></div>';
					$("#sample").append(markup);
					match_night();
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
	//-->
	</script>
	<!-- JAVASCRIPT CODE -->
</head>
<body class="skin-purple">
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
               <h1>Edit Booking</h1>
               <ol class="breadcrumb">
                  <li><a href="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                  <li class="active">Edit Booking</li>
               </ol>
            </section>
			<section class="content">
				<div class="row">
					<div class="col-md-12">
						<div id="notify_msg_div"></div>
						<div class="box box-primary">
							<section class="content">
								<div class="wizard">
									<div class="wizard-inner">
										<div class="connecting-line"></div>
										<ul class="nav nav-tabs" role="tablist">
											<li role="presentation" class="active">
												<a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" title="General">
												<span class="round-tab">
													<i class="fa fa-bars fa-1x" ></i>
												</span>
												</a>
											</li>

											<li role="presentation">
												<a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" title="Select Hotel">
												<span class="round-tab">
													<i class="fa fa-bed fa-1x" ></i>
												</span>
												</a>
											</li>

											<li role="presentation">
												<a href="#step3" data-toggle="tab" aria-controls="step3" role="tab" title="Select Transfer">
												<span class="round-tab">
													<i class="fa fa-car fa-1x" ></i>
												</span>
												</a>
											</li>

											<li role="presentation">
												<a href="#step4" data-toggle="tab" aria-controls="step4" role="tab" title="Select Tour">
												<span class="round-tab">
													<i class="fa fa-road fa-1x" ></i>
												</span>
												</a>
											</li>

											<li role="presentation">
												<a href="#complete" data-toggle="tab" aria-controls="complete" role="tab" title="Complete">
												<span class="round-tab">
													<i class="fa fa-shopping-cart fa-1x" ></i>
												</span>
												</a>
											</li>
										</ul>
									</div>

									<form role="form">
										<div class="tab-content">
											<div class="tab-pane active" role="tabpanel" id="step1">
												<h3>Select Criteria For New Booking</h3>
												<form name="form_first_step" id="form_first_step" method="POST" enctype="mulitipart/form-data">
													<div class="col-md-12 row">
														<div class="box-body" style="border:1px solid gray;">
															<div class="form-group col-md-6">
																<label for="inputName" class="control-label">Select Booking Type<font color="#FF0000">*</font></label>
																<select name = "booking_type" id = "booking_type" class="form-control validate[optional]"  tabindex = "1" onchange = "manage_booking_type(this.value);">
																	<option value = "personal" <?php echo(isset($_POST['booking_type']) && $_POST['booking_type']=="personal" ? "selected='selected'" : (isset($booking_details_list['booking_type']) && $booking_details_list['booking_type']=="personal" ? "selected='selected'" : ""));?>>Personal Booking</option>
																	<option value = "agent" <?php echo(isset($_POST['booking_type']) && $_POST['booking_type']=="agent" ? "selected='selected'" : (isset($agent_data) &&  !empty($agent_data) ? "selected='selected'" : (isset($booking_details_list['booking_type']) && $booking_details_list['booking_type']=="agent" ? "selected='selected'" : "")));?>>Agent Booking</option>
																</select>
															</div>
															<div class="form-group col-md-6">
																<label for="inputName" class="control-label">Select Agent<font color="#FF0000">*</font></label>
																<select name = "agent_name" id = "agent_name" class="form-control validate[optional]"  tabindex = "2" <?php echo(isset($_POST['agent_name']) && $_POST['agent_name']!="" ? '' : 'disabled');?>>
																	<option value = "">Select Agent</option>
																<?php
																if(isset($agent_list) && !empty($agent_list)):
																	foreach($agent_list as $agent_key=>$agent_val):
																?>
																	<option  value = "<?php echo $agent_val['id'];?>" <?php echo(isset($_POST['agent_name']) && $_POST['agent_name']==$agent_val['id'] ? 'selected="selected"' : (isset($agent_data) && !empty($agent_data) && $agent_data['id']==$agent_val['id'] ? 'selected="selected"' : (isset($booking_details_list['agent_id']) && $booking_details_list['agent_id']==$agent_val['id'] ? "selected='selected'" : "")));?>><?php echo $agent_val['first_name'].($agent_val['middle_name']!="" ? " ".$agent_val['middle_name'] : "")." ".$agent_val['last_name']." - ".$agent_val['code'];?></option>
																<?php
																	endforeach;
																endif;
																?>
																</select>
															</div>
															<div class="form-group col-md-6">
																<label for="inputName" class="control-label">Check In<font color="#FF0000">*</font></label>
																<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['checkin']) && $_POST['checkin']!='' ? $_POST['checkin'] : (isset($booking_details_list['checkin_date']) && $booking_details_list['checkin_date']!="" ? date("d/m/Y", strtotime($booking_details_list['checkin_date'])) : ""));?>" name="checkin" id="checkin" placeholder="Check In" tabindex = "3"  />
															</div>
															<div class="form-group col-md-6">
																<label for="inputName" class="control-label">Check Out<font color="#FF0000">*</font></label>
																<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['checkout']) && $_POST['checkout']!='' ? $_POST['checkout'] : (isset($booking_details_list['checkout_date']) && $booking_details_list['checkout_date']!="" ? date("d/m/Y", strtotime($booking_details_list['checkout_date'])) : ""));?>" name="checkout" id="checkout" placeholder="Check Out" tabindex = "4" />
															</div>
															<div class="clearfix"></div>
														</div>
														<div class="box-body"></div>
														<div class="box-body" id = "sample" style="border:1px solid gray;">
															<?php
															if(isset($_POST['country']) && !empty($_POST['country']))
															{
																foreach($_POST['country'] as $post_country_key=>$post_country_val)
																{
															?>
															<div class="form-group col-md-12 <?php echo($post_country_key > 0 ? "appended_row" : "");?>">
																<div class="form-group col-md-3">
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
																<div class="form-group col-md-3">
																	<label for="inputName" class="control-label">City<font color="#FF0000">*</font></label>
																	<select class="form-control validate[required]" name="city[<?php echo $post_country_key;?>]" id="city<?php echo $post_country_key;?>">
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
																<div class="form-group col-md-3">
																	<label for="inputName" class="control-label">For No Of Nights<font color="#FF0000">*</font></label>
																	<input type="text" class="form-control number_of_night validate[required]"  value="<?php echo(isset($_POST['number_of_night'][$post_country_key]) && $_POST['number_of_night'][$post_country_key]!='' ? $_POST['number_of_night'][$post_country_key] : "");?>" name="number_of_night[<?php echo $post_country_key;?>]" id="number_of_night<?php echo $post_country_key;?>" placeholder="Number Of Nights" tabindex = "4" onblur="check_night()"/>
																</div>
																<div class="form-group col-md-3">
																	<label for="inputName" class="control-label">Hotel Ratings<font color="#FF0000">*</font></label>
																	<br/>
																	<input type="checkbox" value="1" name="hotel_ratings[<?php echo $post_country_key;?>][]" <?php echo(isset($_POST['hotel_ratings']) && !empty($_POST['hotel_ratings']) && isset($_POST['hotel_ratings'][$post_country_key]) && in_array(1, $_POST['hotel_ratings'][$post_country_key]) ? 'checked="checked"' : "");?> class="validate[minCheckbox[1]]"/>&nbsp;1&nbsp;&nbsp;<input type="checkbox" value="2" name="hotel_ratings[<?php echo $post_country_key;?>][]" <?php echo(isset($_POST['hotel_ratings']) && !empty($_POST['hotel_ratings']) && isset($_POST['hotel_ratings'][$post_country_key]) && in_array(2, $_POST['hotel_ratings'][$post_country_key]) ? 'checked="checked"' : "");?> class="validate[minCheckbox[1]]"/>&nbsp;2&nbsp;&nbsp;<input type="checkbox"  value="3" name="hotel_ratings[<?php echo $post_country_key;?>][]" <?php echo(isset($_POST['hotel_ratings']) && !empty($_POST['hotel_ratings']) && isset($_POST['hotel_ratings'][$post_country_key]) && in_array(3, $_POST['hotel_ratings'][$post_country_key]) ? 'checked="checked"' : "");?> class="validate[minCheckbox[1]]"/>&nbsp;3&nbsp;&nbsp;<input type="checkbox" value="4" name="hotel_ratings[<?php echo $post_country_key;?>][]" <?php echo(isset($_POST['hotel_ratings']) && !empty($_POST['hotel_ratings']) && isset($_POST['hotel_ratings'][$post_country_key]) && in_array(4, $_POST['hotel_ratings'][$post_country_key]) ? 'checked="checked"' : "");?> class="validate[minCheckbox[1]]"/>&nbsp;4&nbsp;&nbsp;<input type="checkbox" value="5" name="hotel_ratings[<?php echo $post_country_key;?>][]" <?php echo(isset($_POST['hotel_ratings']) && !empty($_POST['hotel_ratings']) && isset($_POST['hotel_ratings'][$post_country_key]) && in_array(5, $_POST['hotel_ratings'][$post_country_key]) ? 'checked="checked"' : "");?> class="validate[minCheckbox[1]]"/>&nbsp;5
																</div>
															</div>
															<?php
																}
																$next_index=$post_country_key+1;
															}
															elseif(isset($booking_details_list['booking_destination_list']) && !empty($booking_details_list['booking_destination_list']))
															{
																foreach($booking_details_list['booking_destination_list'] as $destination_key=>$destination_val):
															?>
															<div class="form-group col-md-12 <?php echo($destination_key > 0 ? "appended_row" : "");?>">
																<div class="form-group col-md-3">
																	<?php
																	if($destination_key > 0)
																	{
																	?>
																	<input type="checkbox" name="record"/>&nbsp;&nbsp;
																	<?php
																	}
																	?>
																	<label for="inputName" class="control-label">Country<font color="#FF0000">*</font></label>
																	<select name="country[<?php echo $destination_key;?>]" class="form-control validate[required]" id="country0" onchange="fetch_city($(this).val(), <?php echo $destination_key;?>);">
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
																<div class="form-group col-md-3">
																	<label for="inputName" class="control-label">City<font color="#FF0000">*</font></label>
																	<select class="form-control validate[required]" name="city[<?php echo $post_country_key;?>]" id="city<?php echo $post_country_key;?>">
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
																<div class="form-group col-md-3">
																	<label for="inputName" class="control-label">For No Of Nights<font color="#FF0000">*</font></label>
																	<input type="text" class="form-control number_of_night validate[required]"  value="<?php echo(isset($_POST['number_of_night'][$post_country_key]) && $_POST['number_of_night'][$post_country_key]!='' ? $_POST['number_of_night'][$post_country_key] : "");?>" name="number_of_night[<?php echo $post_country_key;?>]" id="number_of_night<?php echo $post_country_key;?>" placeholder="Number Of Nights" tabindex = "4" onblur="check_night()"/>
																</div>
																<div class="form-group col-md-3">
																	<label for="inputName" class="control-label">Hotel Ratings<font color="#FF0000">*</font></label>
																	<br/>
																	<input type="checkbox" value="1" name="hotel_ratings[<?php echo $post_country_key;?>][]" <?php echo(isset($_POST['hotel_ratings']) && !empty($_POST['hotel_ratings']) && isset($_POST['hotel_ratings'][$post_country_key]) && in_array(1, $_POST['hotel_ratings'][$post_country_key]) ? 'checked="checked"' : "");?> class="validate[minCheckbox[1]]"/>&nbsp;1&nbsp;&nbsp;<input type="checkbox" value="2" name="hotel_ratings[<?php echo $post_country_key;?>][]" <?php echo(isset($_POST['hotel_ratings']) && !empty($_POST['hotel_ratings']) && isset($_POST['hotel_ratings'][$post_country_key]) && in_array(2, $_POST['hotel_ratings'][$post_country_key]) ? 'checked="checked"' : "");?> class="validate[minCheckbox[1]]"/>&nbsp;2&nbsp;&nbsp;<input type="checkbox"  value="3" name="hotel_ratings[<?php echo $post_country_key;?>][]" <?php echo(isset($_POST['hotel_ratings']) && !empty($_POST['hotel_ratings']) && isset($_POST['hotel_ratings'][$post_country_key]) && in_array(3, $_POST['hotel_ratings'][$post_country_key]) ? 'checked="checked"' : "");?> class="validate[minCheckbox[1]]"/>&nbsp;3&nbsp;&nbsp;<input type="checkbox" value="4" name="hotel_ratings[<?php echo $post_country_key;?>][]" <?php echo(isset($_POST['hotel_ratings']) && !empty($_POST['hotel_ratings']) && isset($_POST['hotel_ratings'][$post_country_key]) && in_array(4, $_POST['hotel_ratings'][$post_country_key]) ? 'checked="checked"' : "");?> class="validate[minCheckbox[1]]"/>&nbsp;4&nbsp;&nbsp;<input type="checkbox" value="5" name="hotel_ratings[<?php echo $post_country_key;?>][]" <?php echo(isset($_POST['hotel_ratings']) && !empty($_POST['hotel_ratings']) && isset($_POST['hotel_ratings'][$post_country_key]) && in_array(5, $_POST['hotel_ratings'][$post_country_key]) ? 'checked="checked"' : "");?> class="validate[minCheckbox[1]]"/>&nbsp;5
																</div>
															</div>
															<?php
																foreach;
															}
															else
															{
																$next_index=1;
															?>
															<div class="form-group col-md-12">
																<div class="form-group col-md-3">
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
																<div class="form-group col-md-3">
																	<label for="inputName" class="control-label">City<font color="#FF0000">*</font></label>
																	<select class="form-control validate[required]" name="city[0]" id="city0">
																		<option value="">Select City</option>
																	</select>
																</div>
																<div class="form-group col-md-3">
																	<label for="inputName" class="control-label">For No Of Nights<font color="#FF0000">*</font></label>
																	<input type="text" class="form-control number_of_night validate[required]"  value="" name="number_of_night[0]" id="number_of_night0" placeholder="Number Of Nights" tabindex = "4" onblur="check_night()"/>
																</div>
																<div class="form-group col-md-3">
																	<label for="inputName" class="control-label">Hotel Ratings<font color="#FF0000">*</font></label>
																	<br/>
																	<input type="checkbox" value="1" name="hotel_ratings[0][]" class="validate[minCheckbox[1]]"/>&nbsp;1&nbsp;&nbsp;<input type="checkbox" value="2" name="hotel_ratings[0][]" class="validate[minCheckbox[1]]"/>&nbsp;2&nbsp;&nbsp;<input type="checkbox"  value="3" name="hotel_ratings[0][]" class="validate[minCheckbox[1]]"/>&nbsp;3&nbsp;&nbsp;<input type="checkbox" value="4" name="hotel_ratings[0][]" class="validate[minCheckbox[1]]"/>&nbsp;4&nbsp;&nbsp;<input type="checkbox" class="validate[minCheckbox[1]]"value="5" name="hotel_ratings[0][]" />&nbsp;5
																</div>
															</div>
															<?php
															}
															?>
															<div class="clearfix"></div>
														</div>
														<div class="box-body">
															<div class="form-group col-md-12">
																<a href = "javascript:void(0);" class="add-row"><img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>plus-icon.png" border = "0" alt = "" /></a>&nbsp;&nbsp;<b>ADD ANOTHER DESTINATION</b>&nbsp;&nbsp;<a href = "javascript:void(0);" class="delete-row"><img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>minus-icon.png" border = "0" alt = "" /></a>
															</div>
														</div>
														<div class="box-body">
															<div class="form-group col-md-4">
																<label for="inputName" class="control-label">Nationality<font color="#FF0000">*</font></label>
																<select class="form-control validate[optional]" name="sel_nationality" id="sel_nationality"> 
																	<option value="">  - Select -  </option>
																	<option label="Afghanistani" value="AF">Afghanistani</option>
																	<option label="Algerian" value="123">Algerian</option>
																	<option label="American" value="69">American</option>
																	<option label="Andorran" value="108">Andorran</option>
																	<option label="Anguillan" value="166">Anguillan</option>
																	<option label="Armenian" value="147">Armenian</option>
																	<option label="Aruban" value="100">Aruban</option>
																	<option label="Australian" value="1">Australian</option>
																	<option label="Austrian" value="2">Austrian</option>
																	<option label="Azerbaijani" value="142">Azerbaijani</option>
																	<option label="Bahamian" value="101">Bahamian</option>
																	<option label="Bahraini" value="106">Bahraini</option>
																	<option label="Bangladeshi" value="3">Bangladeshi</option>
																	<option label="Barbadian" value="102">Barbadian</option>
																	<option label="Batswana" value="190">Batswana</option>
																	<option label="Belarusian" value="141">Belarusian</option>
																	<option label="Belgian" value="4">Belgian</option>
																	<option label="Belizean" value="158">Belizean</option>
																	<option label="Bolivian" value="169">Bolivian</option>
																	<option label="Brazilian" value="5">Brazilian</option>
																	<option label="British" value="68">British</option>
																	<option label="Bruneian" value="89">Bruneian</option>
																	<option label="Bulgarian" value="6">Bulgarian</option>
																	<option label="Burkinabe" value="178">Burkinabe</option>
																	<option label="Burundian" value="180">Burundian</option>
																	<option label="Cambodian" value="90">Cambodian</option>
																	<option label="Cameroonian" value="160">Cameroonian</option>
																	<option label="Canadian" value="7">Canadian</option>
																	<option label="Caymanian" value="105">Caymanian</option>
																	<option label="Chadian" value="181">Chadian</option>
																	<option label="Chilean" value="9">Chilean</option>
																	<option label="Chinese" value="10">Chinese</option>
																	<option label="Colombian" value="87">Colombian</option>
																	<option label="Comorian" value="777">Comorian</option>
																	<option label="Congo" value="203">Congo</option>
																	<option label="Cook Islander" value="194">Cook Islander</option>
																	<option label="Costa Rican" value="150">Costa Rican</option>
																	<option label="Cote D Ivoire" value="177">Cote D Ivoire</option>
																	<option label="Croatian" value="11">Croatian</option>
																	<option label="Cuba" value="88">Cuba</option>
																	<option label="Cypriot" value="12">Cypriot</option>
																	<option label="Czech" value="13">Czech</option>
																	<option label="Danish" value="14">Danish</option>
																	<option label="Dominican (Dominican Republic)" value="91">Dominican (Dominican Republic)</option>
																	<option label="Dutch" value="24">Dutch</option>
																	<option label="Egyptian" value="EGY">Egyptian</option>
																	<option label="Emirati" value="67">Emirati</option>
																	<option label="Eritrean" value="201">Eritrean</option>
																	<option label="Estonian" value="17">Estonian</option>
																	<option label="Ethiopian" value="84">Ethiopian</option>
																	<option label="Faroese" value="76">Faroese</option>
																	<option label="Finnish" value="18">Finnish</option>
																	<option label="French" value="19">French</option>
																	<option label="French Polynesian" value="62">French Polynesian</option>
																	<option label="Gambian" value="164">Gambian</option>
																	<option label="Georgian" value="118">Georgian</option>
																	<option label="German" value="21">German</option>
																	<option label="Ghanaian" value="124">Ghanaian</option>
																	<option label="Gibraltarian" value="77">Gibraltarian</option>
																	<option label="Greek" value="22">Greek</option>
																	<option label="Greenlandic" value="191">Greenlandic</option>
																	<option label="Grenadian" value="115">Grenadian</option>
																	<option label="Guamanian" value="110">Guamanian</option>
																	<option label="Guatemalan" value="119">Guatemalan</option>
																	<option label="Guinean" value="183">Guinean</option>
																	<option label="Haitian" value="86">Haitian</option>
																	<option label="Hongkonger" value="HKG">Hongkonger</option>
																	<option label="Hungarian" value="26">Hungarian</option>
																	<option label="Icelander" value="107">Icelander</option>
																	<option label="Indian" value="27" selected="selected">Indian</option>
																	<option label="Indonesian" value="28">Indonesian</option>
																	<option label="Iranian" value="78">Iranian</option>
																	<option label="Iraqi" value="79">Iraqi</option>
																	<option label="Irish" value="29">Irish</option>
																	<option label="Isreali" value="30">Isreali</option>
																	<option label="Italian" value="31">Italian</option>
																	<option label="Jamaican" value="32">Jamaican</option>
																	<option label="Japanese" value="33">Japanese</option>
																	<option label="Jordanian" value="34">Jordanian</option>
																	<option label="Kazakh" value="173">Kazakh</option>
																	<option label="Kenyan" value="80">Kenyan</option>
																	<option label="Korean" value="57">Korean</option>
																	<option label="Kuwaiti" value="KW">Kuwaiti</option>
																	<option label="Kyrgyzstani" value="175">Kyrgyzstani</option>
																	<option label="Lao" value="159">Lao</option>
																	<option label="Latvian" value="36">Latvian</option>
																	<option label="Lebanese" value="37">Lebanese</option>
																	<option label="Liberian" value="192">Liberian</option>
																	<option label="Libyan" value="172">Libyan</option>
																	<option label="Liechtenstein" value="154">Liechtenstein</option>
																	<option label="Lithuanian" value="120">Lithuanian</option>
																	<option label="Luxembourger" value="38">Luxembourger</option>
																	<option label="Malaysian" value="39">Malaysian</option>
																	<option label="Maldivian" value="40">Maldivian</option>
																	<option label="Maltese" value="41">Maltese</option>
																	<option label="Martiniquais" value="153">Martiniquais</option>
																	<option label="Mauritanian" value="185">Mauritanian</option>
																	<option label="Mexican" value="43">Mexican</option>
																	<option label="Micronesian" value="195">Micronesian</option>
																	<option label="Moldovan" value="113">Moldovan</option>
																	<option label="Monacan" value="85">Monacan</option>
																	<option label="Mongolia" value="193">Mongolia</option>
																	<option label="Montenegrin" value="200">Montenegrin</option>
																	<option label="Moroccan" value="44">Moroccan</option>
																	<option label="Mozambican" value="204">Mozambican</option>
																	<option label="Myanmari" value="148">Myanmari</option>
																	<option label="Namibian" value="161">Namibian</option>
																	<option label="Nepalese" value="45">Nepalese</option>
																	<option label="New Caledonian" value="122">New Caledonian</option>
																	<option label="New Zealander" value="46">New Zealander</option>
																	<option label="Nigerian" value="152">Nigerian</option>
																	<option label="Norwegian" value="47">Norwegian</option>
																	<option label="Omani" value="146">Omani</option>
																	<option label="Pakistani" value="97">Pakistani</option>
																	<option label="Palestinian" value="198">Palestinian</option>
																	<option label="Panamanian" value="162">Panamanian</option>
																	<option label="Paraguayan" value="149">Paraguayan</option>
																	<option label="Peruvian" value="98">Peruvian</option>
																	<option label="Philippine" value="48">Philippine</option>
																	<option label="Polish" value="49">Polish</option>
																	<option label="Portuguese" value="50">Portuguese</option>
																	<option label="Puerto Rican" value="51">Puerto Rican</option>
																	<option label="Qatari" value="168">Qatari</option>
																	<option label="Romanian" value="52">Romanian</option>
																	<option label="Russian" value="53">Russian</option>
																	<option label="Rwandan" value="186">Rwandan</option>
																	<option label="Salvadoran" value="157">Salvadoran</option>
																	<option label="San Marino" value="54">San Marino</option>
																	<option label="Saudi" value="95">Saudi</option>
																	<option label="Senegalese" value="130">Senegalese</option>
																	<option label="Serbian" value="109">Serbian</option>
																	<option label="Seychellois" value="81">Seychellois</option>
																	<option label="Singaporean" value="55">Singaporean</option>
																	<option label="Slovakian" value="94">Slovakian</option>
																	<option label="Slovenian" value="99">Slovenian</option>
																	<option label="South African" value="56">South African</option>
																	<option label="Spanish" value="58">Spanish</option>
																	<option label="Sri Lankan" value="59">Sri Lankan</option>
																	<option label="Sudanese" value="151">Sudanese</option>
																	<option label="Surinamese" value="188">Surinamese</option>
																	<option label="Swazi" value="199">Swazi</option>
																	<option label="Swedish" value="60">Swedish</option>
																	<option label="Swiss" value="61">Swiss</option>
																	<option label="Syrian Arab Republic" value="74">Syrian Arab Republic</option>
																	<option label="Taiwanese" value="63">Taiwanese</option>
																	<option label="Tajikistani" value="170">Tajikistani</option>
																	<option label="Tanzanian" value="112">Tanzanian</option>
																	<option label="Thai" value="64">Thai</option>
																	<option label="Tunisian" value="75">Tunisian</option>
																	<option label="Turkish" value="65">Turkish</option>
																	<option label="Ugandan" value="171">Ugandan</option>
																	<option label="Ukrainian" value="66">Ukrainian</option>
																	<option label="Uruguayan" value="70">Uruguayan</option>
																	<option label="Us Citizens" value="184">Us Citizens</option>
																	<option label="Uzbekistani" value="117">Uzbekistani</option>
																	<option label="Venezuelan" value="71">Venezuelan</option>
																	<option label="Verdean" value="139">Verdean</option>
																	<option label="Vietnamese" value="72">Vietnamese</option>
																	<option label="Yemeni" value="83">Yemeni</option>
																	<option label="Zambian" value="189">Zambian</option>
																	<option label="Zimbabwe" value="121">Zimbabwe</option>
																</select>
															</div>
															<div class="form-group col-md-4">
																<label for="inputName" class="control-label">Country Of Residence<font color="#FF0000">*</font></label>
																<select class="form-control validate[optional]" name="sel_nationality" id="sel_nationality"> 
																	<option value="">  - Select -  </option>
																	<option label="Afghanistani" value="AF">Afghanistani</option>
																	<option label="Algerian" value="123">Algerian</option>
																	<option label="American" value="69">American</option>
																	<option label="Andorran" value="108">Andorran</option>
																	<option label="Anguillan" value="166">Anguillan</option>
																	<option label="Armenian" value="147">Armenian</option>
																	<option label="Aruban" value="100">Aruban</option>
																	<option label="Australian" value="1">Australian</option>
																	<option label="Austrian" value="2">Austrian</option>
																	<option label="Azerbaijani" value="142">Azerbaijani</option>
																	<option label="Bahamian" value="101">Bahamian</option>
																	<option label="Bahraini" value="106">Bahraini</option>
																	<option label="Bangladeshi" value="3">Bangladeshi</option>
																	<option label="Barbadian" value="102">Barbadian</option>
																	<option label="Batswana" value="190">Batswana</option>
																	<option label="Belarusian" value="141">Belarusian</option>
																	<option label="Belgian" value="4">Belgian</option>
																	<option label="Belizean" value="158">Belizean</option>
																	<option label="Bolivian" value="169">Bolivian</option>
																	<option label="Brazilian" value="5">Brazilian</option>
																	<option label="British" value="68">British</option>
																	<option label="Bruneian" value="89">Bruneian</option>
																	<option label="Bulgarian" value="6">Bulgarian</option>
																	<option label="Burkinabe" value="178">Burkinabe</option>
																	<option label="Burundian" value="180">Burundian</option>
																	<option label="Cambodian" value="90">Cambodian</option>
																	<option label="Cameroonian" value="160">Cameroonian</option>
																	<option label="Canadian" value="7">Canadian</option>
																	<option label="Caymanian" value="105">Caymanian</option>
																	<option label="Chadian" value="181">Chadian</option>
																	<option label="Chilean" value="9">Chilean</option>
																	<option label="Chinese" value="10">Chinese</option>
																	<option label="Colombian" value="87">Colombian</option>
																	<option label="Comorian" value="777">Comorian</option>
																	<option label="Congo" value="203">Congo</option>
																	<option label="Cook Islander" value="194">Cook Islander</option>
																	<option label="Costa Rican" value="150">Costa Rican</option>
																	<option label="Cote D Ivoire" value="177">Cote D Ivoire</option>
																	<option label="Croatian" value="11">Croatian</option>
																	<option label="Cuba" value="88">Cuba</option>
																	<option label="Cypriot" value="12">Cypriot</option>
																	<option label="Czech" value="13">Czech</option>
																	<option label="Danish" value="14">Danish</option>
																	<option label="Dominican (Dominican Republic)" value="91">Dominican (Dominican Republic)</option>
																	<option label="Dutch" value="24">Dutch</option>
																	<option label="Egyptian" value="EGY">Egyptian</option>
																	<option label="Emirati" value="67">Emirati</option>
																	<option label="Eritrean" value="201">Eritrean</option>
																	<option label="Estonian" value="17">Estonian</option>
																	<option label="Ethiopian" value="84">Ethiopian</option>
																	<option label="Faroese" value="76">Faroese</option>
																	<option label="Finnish" value="18">Finnish</option>
																	<option label="French" value="19">French</option>
																	<option label="French Polynesian" value="62">French Polynesian</option>
																	<option label="Gambian" value="164">Gambian</option>
																	<option label="Georgian" value="118">Georgian</option>
																	<option label="German" value="21">German</option>
																	<option label="Ghanaian" value="124">Ghanaian</option>
																	<option label="Gibraltarian" value="77">Gibraltarian</option>
																	<option label="Greek" value="22">Greek</option>
																	<option label="Greenlandic" value="191">Greenlandic</option>
																	<option label="Grenadian" value="115">Grenadian</option>
																	<option label="Guamanian" value="110">Guamanian</option>
																	<option label="Guatemalan" value="119">Guatemalan</option>
																	<option label="Guinean" value="183">Guinean</option>
																	<option label="Haitian" value="86">Haitian</option>
																	<option label="Hongkonger" value="HKG">Hongkonger</option>
																	<option label="Hungarian" value="26">Hungarian</option>
																	<option label="Icelander" value="107">Icelander</option>
																	<option label="Indian" value="27" selected="selected">Indian</option>
																	<option label="Indonesian" value="28">Indonesian</option>
																	<option label="Iranian" value="78">Iranian</option>
																	<option label="Iraqi" value="79">Iraqi</option>
																	<option label="Irish" value="29">Irish</option>
																	<option label="Isreali" value="30">Isreali</option>
																	<option label="Italian" value="31">Italian</option>
																	<option label="Jamaican" value="32">Jamaican</option>
																	<option label="Japanese" value="33">Japanese</option>
																	<option label="Jordanian" value="34">Jordanian</option>
																	<option label="Kazakh" value="173">Kazakh</option>
																	<option label="Kenyan" value="80">Kenyan</option>
																	<option label="Korean" value="57">Korean</option>
																	<option label="Kuwaiti" value="KW">Kuwaiti</option>
																	<option label="Kyrgyzstani" value="175">Kyrgyzstani</option>
																	<option label="Lao" value="159">Lao</option>
																	<option label="Latvian" value="36">Latvian</option>
																	<option label="Lebanese" value="37">Lebanese</option>
																	<option label="Liberian" value="192">Liberian</option>
																	<option label="Libyan" value="172">Libyan</option>
																	<option label="Liechtenstein" value="154">Liechtenstein</option>
																	<option label="Lithuanian" value="120">Lithuanian</option>
																	<option label="Luxembourger" value="38">Luxembourger</option>
																	<option label="Malaysian" value="39">Malaysian</option>
																	<option label="Maldivian" value="40">Maldivian</option>
																	<option label="Maltese" value="41">Maltese</option>
																	<option label="Martiniquais" value="153">Martiniquais</option>
																	<option label="Mauritanian" value="185">Mauritanian</option>
																	<option label="Mexican" value="43">Mexican</option>
																	<option label="Micronesian" value="195">Micronesian</option>
																	<option label="Moldovan" value="113">Moldovan</option>
																	<option label="Monacan" value="85">Monacan</option>
																	<option label="Mongolia" value="193">Mongolia</option>
																	<option label="Montenegrin" value="200">Montenegrin</option>
																	<option label="Moroccan" value="44">Moroccan</option>
																	<option label="Mozambican" value="204">Mozambican</option>
																	<option label="Myanmari" value="148">Myanmari</option>
																	<option label="Namibian" value="161">Namibian</option>
																	<option label="Nepalese" value="45">Nepalese</option>
																	<option label="New Caledonian" value="122">New Caledonian</option>
																	<option label="New Zealander" value="46">New Zealander</option>
																	<option label="Nigerian" value="152">Nigerian</option>
																	<option label="Norwegian" value="47">Norwegian</option>
																	<option label="Omani" value="146">Omani</option>
																	<option label="Pakistani" value="97">Pakistani</option>
																	<option label="Palestinian" value="198">Palestinian</option>
																	<option label="Panamanian" value="162">Panamanian</option>
																	<option label="Paraguayan" value="149">Paraguayan</option>
																	<option label="Peruvian" value="98">Peruvian</option>
																	<option label="Philippine" value="48">Philippine</option>
																	<option label="Polish" value="49">Polish</option>
																	<option label="Portuguese" value="50">Portuguese</option>
																	<option label="Puerto Rican" value="51">Puerto Rican</option>
																	<option label="Qatari" value="168">Qatari</option>
																	<option label="Romanian" value="52">Romanian</option>
																	<option label="Russian" value="53">Russian</option>
																	<option label="Rwandan" value="186">Rwandan</option>
																	<option label="Salvadoran" value="157">Salvadoran</option>
																	<option label="San Marino" value="54">San Marino</option>
																	<option label="Saudi" value="95">Saudi</option>
																	<option label="Senegalese" value="130">Senegalese</option>
																	<option label="Serbian" value="109">Serbian</option>
																	<option label="Seychellois" value="81">Seychellois</option>
																	<option label="Singaporean" value="55">Singaporean</option>
																	<option label="Slovakian" value="94">Slovakian</option>
																	<option label="Slovenian" value="99">Slovenian</option>
																	<option label="South African" value="56">South African</option>
																	<option label="Spanish" value="58">Spanish</option>
																	<option label="Sri Lankan" value="59">Sri Lankan</option>
																	<option label="Sudanese" value="151">Sudanese</option>
																	<option label="Surinamese" value="188">Surinamese</option>
																	<option label="Swazi" value="199">Swazi</option>
																	<option label="Swedish" value="60">Swedish</option>
																	<option label="Swiss" value="61">Swiss</option>
																	<option label="Syrian Arab Republic" value="74">Syrian Arab Republic</option>
																	<option label="Taiwanese" value="63">Taiwanese</option>
																	<option label="Tajikistani" value="170">Tajikistani</option>
																	<option label="Tanzanian" value="112">Tanzanian</option>
																	<option label="Thai" value="64">Thai</option>
																	<option label="Tunisian" value="75">Tunisian</option>
																	<option label="Turkish" value="65">Turkish</option>
																	<option label="Ugandan" value="171">Ugandan</option>
																	<option label="Ukrainian" value="66">Ukrainian</option>
																	<option label="Uruguayan" value="70">Uruguayan</option>
																	<option label="Us Citizens" value="184">Us Citizens</option>
																	<option label="Uzbekistani" value="117">Uzbekistani</option>
																	<option label="Venezuelan" value="71">Venezuelan</option>
																	<option label="Verdean" value="139">Verdean</option>
																	<option label="Vietnamese" value="72">Vietnamese</option>
																	<option label="Yemeni" value="83">Yemeni</option>
																	<option label="Zambian" value="189">Zambian</option>
																	<option label="Zimbabwe" value="121">Zimbabwe</option>
																</select>
															</div>
															<div class="form-group col-md-4">
																<label for="inputName" class="control-label">Invoice Currency<font color="#FF0000">*</font></label>
																<select class="form-control validate[optional]" name="sel_currency" id="selected_currency">
																	<option value="">-Select-</option>
																	<option label="AED" value="AED">AED</option>
																	<option label="AUD" value="AUD">AUD</option>
																	<option label="EUR" value="EUR">EUR</option>
																	<option label="GBP" value="GBP">GBP</option>
																	<option label="IDR" value="IDR">IDR</option>
																	<option label="INR" value="INR" selected="selected">INR</option>
																	<option label="MYR" value="MYR">MYR</option>
																	<option label="PHP" value="PHP">PHP</option>
																	<option label="SGD" value="SGD">SGD</option>
																	<option label="THB" value="THB">THB</option>
																	<option label="USD" value="USD">USD</option>
																</select>
															</div>
														</div>
														<div class="box-body">
															<div class="form-group col-md-2">
																<label for="inputName" class="control-label">Number Of Rooms<font color="#FF0000">*</font></label>
																<select class="form-control validate[optional]" name="rooms" id="rooms"> 
																	<option value="">  - Select -  </option>
																	<option label="1" value="1">1</option>
																	<option label="2" value="2">2</option>
																	<option label="3" value="3">3</option>
																	<option label="4" value="4">4</option>
																	<option label="5" value="5">5</option>
																</select>
															</div>
															<div class="form-group col-md-2">
																<label for="inputName" class="control-label">Adult<font color="#FF0000">*</font></label>
																<select class="form-control validate[optional]" name="adult" id="adult"> 
																	<option value="">  - Select -  </option>
																	<option label="1" value="1">1</option>
																	<option label="2" value="2">2</option>
																	<option label="3" value="3">3</option>
																	<option label="4" value="4">4</option>
																	<option label="5" value="5">5</option>
																	<option label="6" value="6">6</option>
																	<option label="7" value="7">7</option>
																	<option label="8" value="8">8</option>
																	<option label="9" value="9">9</option>
																	<option label="10" value="10">10</option>
																</select>
															</div>
															<div class="form-group col-md-2">
																<label for="inputName" class="control-label">Child<font color="#FF0000">*</font></label>
																<select class="form-control validate[optional]" name="child" id="child" onchange = "child_attribute(this.value);"> 
																	<option value="">  - Select -  </option>
																	<option label="1" value="1">1</option>
																	<option label="2" value="2">2</option>
																	<option label="3" value="3">3</option>
																</select>
															</div>
															<div class="form-group col-md-2" style = "display:none;" id = "child_age_div">
																<label for="inputName" class="control-label">Age</label>
																<select class="form-control validate[optional]" name="child_age" id="child_age">
																	<option label="1" value="1">1</option>
																	<option label="2" value="2">2</option>
																	<option label="3" value="3">3</option>
																	<option label="4" value="4">4</option>
																	<option label="5" value="5">5</option>
																</select>
															</div>
															<div class="form-group col-md-4" style = "display:none;" id = "bed_required_div">
																<label for="inputName" class="control-label">Additional Bed Required</label>
																<select class="form-control validate[optional]" name="bed_required" id="bed_required">
																	<option label="Yes" value="Yes">Yes</option>
																	<option label="No" value="No">No</option>
																</select>
															</div>
														</div>
													</div>
												</form>
												<ul class="list-inline pull-right">
													<li><button type="button" class="btn btn-primary next-step">Search</button></li>
												</ul>
											</div>
											<div class="tab-pane" role="tabpanel" id="step2">
												<h3>Search Hotels</h3>
												<div class="col-md-6">
													<p>Your search for <font color = "red"><b>Thailand</b></font>, <font color = "red"><b>Bangkok</b></font> for <font color = "red"><b>3 Night(s)</b></font> fetched <font color = "red"><b>782 Hotels</b></font></p>
												</div>
												<div class="col-md-6">
													<p><b>SORT BY:</b>&nbsp;&nbsp;&nbsp;<input type = "radio" name = "sort" / >&nbsp;Price&nbsp;&nbsp;<input type = "radio" name = "sort" / >&nbsp;Hotel Name&nbsp;&nbsp;<input type = "radio" name = "sort" / >&nbsp;Rating</p>
												</div>
												<form name="profile" name="form_create_slider" id="form_create_slider" method="POST" enctype="mulimedeia/form-data">
													<div class="form-group col-md-3">
														<label for="inputName" class="control-label">Filter By Hotel Name<font color="#FF0000">*</font></label>
														<select class="form-control validate[optional]" name="sel_avlbl_hotel">
															<option value="">- All Hotels -</option>
															<option label="137 pillars suites and residences bangkok" value="331864">137 pillars suites and residences bangkok</option>
															<option label="137 pillars suites" value="96Y_BKK">137 pillars suites</option>
															<option label="1sabai hostel" value="5a95046e7e962fe9f28b4604">1sabai hostel</option>
															<option label="212 serviced apartment" value="5a95046e7e962fe9f28b4674">212 serviced apartment</option>
															<option label="48 ville donmuang airport" value="5a95046e7e962fe9f28b459e">48 ville donmuang airport</option>
															<option label="@hua lamphong hostel" value="5a95046e7e962fe9f28b45a4">@hua lamphong hostel</option>
															<option label="A-one bangkok boutique hotel" value="737981">A-one bangkok boutique hotel</option>
															<option label="A-one bangkok" value="AON_BKK">A-one bangkok</option>
															<option label="A-one boutique hotel" value="AON2_BKK">A-one boutique hotel</option>
															<option label="Abloom exclusive serviced apartment" value="5a95046f7e962fc2f28b45a2">Abloom exclusive serviced apartment</option>
														</select>
													</div>
													<div class="form-group col-md-3">
														<label for="inputName" class="control-label">Filter By Location<font color="#FF0000">*</font></label>
														<select class="form-control validate[optional]" name="sel_avlbl_hotel">
															<option value="">- Filter by Location -</option>
															<option label="All Locations" value="all_locations">All Locations</option>
															<option label=" " value=" "> </option>
															<option label="Airport" value="Airport">Airport</option>
															<option label="Airport - Suvarnabhumi Airport " value="Airport - Suvarnabhumi Airport ">Airport - Suvarnabhumi Airport </option>
															<option label="Ayuthaya Road" value="Ayuthaya Road">Ayuthaya Road</option>
															<option label="BANGKOK " value="BANGKOK ">BANGKOK </option>
															<option label="BANGKOK Sukhumvit" value="BANGKOK Sukhumvit">BANGKOK Sukhumvit</option>
															<option label="BANGKOK Terminal 21" value="BANGKOK Terminal 21">BANGKOK Terminal 21</option>
															<option label="Bang Rak" value="Bang Rak">Bang Rak</option>
															<option label="Bangkok" value="Bangkok">Bangkok</option>
															<option label="Bangkok Noi" value="Bangkok Noi">Bangkok Noi</option>
															<option label="Central" value="Central">Central</option>
														</select>
													</div>
													<div class="form-group col-md-6">
														<label for="inputName" class="control-label">Select Page<font color="#FF0000">*</font></label>
														<select class="form-control validate[optional]" name="sel_avlbl_hotel">
															<option value="1">- 1 -</option>
															<option value="2">- 2 -</option>
															<option value="3">- 3 -</option>
															<option value="4">- 4 -</option>
															<option value="5">- 5 -</option>
															<option value="6">- 6 -</option>
															<option value="7">- 7 -</option>
															<option value="8">- 8 -</option>
															<option value="9">- 9 -</option>
															<option value="10">- 10 -</option>
														</select>
													</div>
													<div class="form-group col-md-12">
														<div style = "border:1px solid red;background-color:red;">
															<div class="col-md-3" style = "font-weight:bold;color:#fff;">Hotel Name</div>
															<div class="col-md-2" style = "font-weight:bold;color:#fff;">Rating</div>
															<div class="col-md-2" style = "font-weight:bold;color:#fff;">Location</div>
															<div class="col-md-3" style = "font-weight:bold;color:#fff;text-align:center;">Availability</div>
															<div class="col-md-2" style = "font-weight:bold;color:#fff;text-align:center;">Rate</div>
															<div class="clearfix"></div>
														</div>
														<div style = "padding:20px 0 0 0;border:1px solid red;">
															<div class="col-md-3" style = "font-weight:bold;">Hotel Seagul</div>
															<div class="col-md-2" style = "font-weight:bold;"><img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>star.png" border = "0" alt = "" height = "20"/></div>
															<div class="col-md-2" style = "font-weight:bold;">Bangkok</div>
															<div class="col-md-3" style = "font-weight:bold;text-align:center;"><button type="button" class="btn btn-success next-step">AVAILABLE</button></div>
															<div class="col-md-2" style = "font-weight:bold;text-align:center;">$3803.67</div>
															<div class="clearfix"></div>
															<div class="col-md-3"><img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>hotel_images/img1.jpg" border = "0" alt = "" width = "250" height = "150" /></div>
															<div class="col-md-9">Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium</div>
															<div class="clearfix"></div>
															<div class="col-md-12">
																<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>view_hotel_details" target = "_blank"><b>MORE INFO</b></a> | <a href = "javascript:void(0);" onclick = "show_rooms('hotel1');"><b>VIEW AVAILABLE ROOMS</b></a>
															</div>
															<div class="clearfix"></div>
															<div id = "hotel1" style = "display:none;">
																<div style = "border:1px solid gray;background-color:gray;margin-top:10px;">
																	<div class="col-md-1" style = "font-weight:bold;color:#fff;">#</div>
																	<div class="col-md-3" style = "font-weight:bold;color:#fff;">Room Type</div>
																	<div class="col-md-2" style = "font-weight:bold;color:#fff;">Rooms</div>
																	<div class="col-md-4" style = "font-weight:bold;color:#fff;">Room Breakup</div>
																	<div class="col-md-2" style = "font-weight:bold;color:#fff;text-align:center;">Total Amount</div>
																	<div class="clearfix"></div>
																</div>
																<div style = "padding:10px 0 10px 0;border:1px solid gray;">
																	<div class="col-md-1" style = "font-weight:bold;">
																		<img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>a_icon.png" border = "0" alt = "" />
																		<br/>
																		<input type = "radio" name = "select" />
																	</div>
																	<div class="col-md-3" style = "font-weight:bold;">
																		Single Room Standard Room Only
																		<br/>
																		<font color = "red">Bedding : Twin/Double.</font>
																	</div>
																	<div class="col-md-2">1</div>
																	<div class="col-md-4">
																		<table aria-describedby="example1_info" class="table table-bordered table-striped dataTable">
																			<thead>
																				<tr role="row">
																					<th valign="middle" style="background-color:gray;" align="center">#</th>
																					<th valign="middle" style="background-color:gray;" align="center">Thu</th>
																					<th valign="middle" style="background-color:gray;" align="center">Fri</th>
																					<th valign="middle" style="background-color:gray;" align="center">Sat</th>
																				</tr>
																			</thead>
																			<tbody aria-relevant="all" aria-live="polite" role="alert">
																				<tr>
																					<td valign="middle"> Wk1 </td>
																					<td valign="middle">
																						1265.59 
																						&nbsp;
																					</td>
																					<td valign="middle">
																						1265.59 
																						&nbsp;
																					</td>
																					<td valign="middle">
																						1265.59 
																						&nbsp;
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</div>
																	<div class="col-md-2" style = "font-weight:bold;color:red;text-align:center;">$3796.77</div>
																	<div class="clearfix"></div>
																</div>
																<div style = "padding:10px 0 10px 0;border:1px solid gray;">
																	<div class="col-md-1" style = "font-weight:bold;">
																		<img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>r_icon.png" border = "0" alt = "" />
																		<br/>
																		<input type = "radio" name = "select" />
																	</div>
																	<div class="col-md-3" style = "font-weight:bold;">
																		Single Room Superior Room Only
																		<br/>
																		<font color = "red">Bedding : Twin/Double.</font>
																	</div>
																	<div class="col-md-2">1</div>
																	<div class="col-md-4">
																		<table aria-describedby="example1_info" class="table table-bordered table-striped dataTable">
																			<thead>
																				<tr role="row">
																					<th valign="middle" style="background-color:gray;" align="center">#</th>
																					<th valign="middle" style="background-color:gray;" align="center">Thu</th>
																					<th valign="middle" style="background-color:gray;" align="center">Fri</th>
																					<th valign="middle" style="background-color:gray;" align="center">Sat</th>
																				</tr>
																			</thead>
																			<tbody aria-relevant="all" aria-live="polite" role="alert">
																				<tr>
																					<td valign="middle"> Wk1 </td>
																					<td valign="middle">
																						1265.59 
																						&nbsp;
																					</td>
																					<td valign="middle">
																						1265.59 
																						&nbsp;
																					</td>
																					<td valign="middle">
																						1265.59 
																						&nbsp;
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</div>
																	<div class="col-md-2" style = "font-weight:bold;color:red;text-align:center;">$3796.77</div>
																	<div class="clearfix"></div>
																</div>
															</div>
														</div>
													</div>

													<div class="form-group col-md-12">
														<div style = "border:1px solid red;background-color:red;">
															<div class="col-md-3" style = "font-weight:bold;color:#fff;">Hotel Name</div>
															<div class="col-md-2" style = "font-weight:bold;color:#fff;">Rating</div>
															<div class="col-md-2" style = "font-weight:bold;color:#fff;">Location</div>
															<div class="col-md-3" style = "font-weight:bold;color:#fff;text-align:center;">Availability</div>
															<div class="col-md-2" style = "font-weight:bold;color:#fff;text-align:center;">Rate</div>
															<div class="clearfix"></div>
														</div>
														<div style = "padding:20px 0 0 0;border:1px solid red;">
															<div class="col-md-3" style = "font-weight:bold;">Hotel Seagul</div>
															<div class="col-md-2" style = "font-weight:bold;"><img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>star.png" border = "0" alt = "" height = "20"/></div>
															<div class="col-md-2" style = "font-weight:bold;">Bangkok</div>
															<div class="col-md-3" style = "font-weight:bold;text-align:center;"><button type="button" class="btn btn-success next-step">AVAILABLE</button></div>
															<div class="col-md-2" style = "font-weight:bold;text-align:center;">$3803.67</div>
															<div class="clearfix"></div>
															<div class="col-md-3"><img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>hotel_images/img1.jpg" border = "0" alt = "" width = "250" height = "150" /></div>
															<div class="col-md-9">Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium</div>
															<div class="clearfix"></div>
															<div class="col-md-12">
																<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>view_hotel_details" target = "_blank"><b>MORE INFO</b></a> | <a href = "javascript:void(0);"  onclick = "show_rooms('hotel2');"><b>VIEW AVAILABLE ROOMS</b></a>
															</div>
															<div class="clearfix"></div>
															<div id = "hotel2" style = "display:none;">
																<div style = "border:1px solid gray;background-color:gray;margin-top:10px;">
																	<div class="col-md-1" style = "font-weight:bold;color:#fff;">#</div>
																	<div class="col-md-3" style = "font-weight:bold;color:#fff;">Room Type</div>
																	<div class="col-md-2" style = "font-weight:bold;color:#fff;">Rooms</div>
																	<div class="col-md-4" style = "font-weight:bold;color:#fff;">Room Breakup</div>
																	<div class="col-md-2" style = "font-weight:bold;color:#fff;text-align:center;">Total Amount</div>
																	<div class="clearfix"></div>
																</div>
																<div style = "padding:10px 0 10px 0;border:1px solid gray;">
																	<div class="col-md-1" style = "font-weight:bold;">
																		<img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>a_icon.png" border = "0" alt = "" />
																		<br/>
																		<input type = "radio" name = "select" />
																	</div>
																	<div class="col-md-3" style = "font-weight:bold;">
																		Single Room Standard Room Only
																		<br/>
																		<font color = "red">Bedding : Twin/Double.</font>
																	</div>
																	<div class="col-md-2">1</div>
																	<div class="col-md-4">
																		<table aria-describedby="example1_info" class="table table-bordered table-striped dataTable">
																			<thead>
																				<tr role="row">
																					<th valign="middle" style="background-color:gray;" align="center">#</th>
																					<th valign="middle" style="background-color:gray;" align="center">Thu</th>
																					<th valign="middle" style="background-color:gray;" align="center">Fri</th>
																					<th valign="middle" style="background-color:gray;" align="center">Sat</th>
																				</tr>
																			</thead>
																			<tbody aria-relevant="all" aria-live="polite" role="alert">
																				<tr>
																					<td valign="middle"> Wk1 </td>
																					<td valign="middle">
																						1265.59 
																						&nbsp;
																					</td>
																					<td valign="middle">
																						1265.59 
																						&nbsp;
																					</td>
																					<td valign="middle">
																						1265.59 
																						&nbsp;
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</div>
																	<div class="col-md-2" style = "font-weight:bold;color:red;text-align:center;">$3796.77</div>
																	<div class="clearfix"></div>
																</div>
																<div style = "padding:10px 0 10px 0;border:1px solid gray;">
																	<div class="col-md-1" style = "font-weight:bold;">
																		<img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>r_icon.png" border = "0" alt = "" />
																		<br/>
																		<input type = "radio" name = "select" />
																	</div>
																	<div class="col-md-3" style = "font-weight:bold;">
																		Single Room Superior Room Only
																		<br/>
																		<font color = "red">Bedding : Twin/Double.</font>
																	</div>
																	<div class="col-md-2">1</div>
																	<div class="col-md-4">
																		<table aria-describedby="example1_info" class="table table-bordered table-striped dataTable">
																			<thead>
																				<tr role="row">
																					<th valign="middle" style="background-color:gray;" align="center">#</th>
																					<th valign="middle" style="background-color:gray;" align="center">Thu</th>
																					<th valign="middle" style="background-color:gray;" align="center">Fri</th>
																					<th valign="middle" style="background-color:gray;" align="center">Sat</th>
																				</tr>
																			</thead>
																			<tbody aria-relevant="all" aria-live="polite" role="alert">
																				<tr>
																					<td valign="middle"> Wk1 </td>
																					<td valign="middle">
																						1265.59 
																						&nbsp;
																					</td>
																					<td valign="middle">
																						1265.59 
																						&nbsp;
																					</td>
																					<td valign="middle">
																						1265.59 
																						&nbsp;
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</div>
																	<div class="col-md-2" style = "font-weight:bold;color:red;text-align:center;">$3796.77</div>
																	<div class="clearfix"></div>
																</div>
															</div>
														</div>
													</div>
												</form>
												<ul class="list-inline pull-right">
													<li><button type="button" class="btn btn-warning prev-step">Modify Search</button></li>
													<li><button type="button" class="btn btn-primary next-step">Save and continue</button></li>
												</ul>
											</div>
											<div class="tab-pane" role="tabpanel" id="step3">
												<h3>Search Transfer</h3>
												<div class="col-md-6">
													<p>Your search for <font color = "red"><b>Thailand</b></font>, <font color = "red"><b>Bangkok</b></font> for <font color = "red"><b>01/03/2018</b></font> for <font color = "red"><b>1 Adult(s)</b></font> gave <font color = "red"><b>2 transfers</b></font></p>
												</div>
												<div class="col-md-6">
													<p><b>SORT BY:</b>&nbsp;&nbsp;&nbsp;<input type = "radio" name = "sort" / >&nbsp;Price&nbsp;&nbsp;<input type = "radio" name = "sort" / >&nbsp;Name
												</div>
												<form name="profile" name="form_create_slider" id="form_create_slider" method="POST" enctype="mulimedeia/form-data">
													<div class="form-group col-md-3">
														<label for="inputName" class="control-label">Filter By Service Type<font color="#FF0000">*</font></label>
														<select class="form-control validate[optional]" name="sel_avlbl_hotel">
															<option value="">- All Type -</option>
															<option label="Private" value="Private">Private</option>
															<option label="Shared" value="Shared">Shared</option>
														</select>
													</div>
													<div class="form-group col-md-3">
														<label for="inputName" class="control-label">Filter By Transfer Type<font color="#FF0000">*</font></label>
														<select class="form-control validate[optional]" name="sel_avlbl_hotel">
															<option value="">- All Type -</option>
															<option label="Airport" value="Airport">Airport</option>
															<option label="Accomodation" value="Accomodation">Accomodation</option>
															<option label="Port" value="Port">Port</option>
															<option label="Station" value="Station">Station</option>
															<option label="Other" value="Other">Other</option>
														</select>
													</div>
													<div class="form-group col-md-6">
														<label for="inputName" class="control-label">Select Page<font color="#FF0000">*</font></label>
														<select class="form-control validate[optional]" name="sel_avlbl_hotel">
															<option value="1">- 1 -</option>
															<option value="2">- 2 -</option>
															<option value="3">- 3 -</option>
															<option value="4">- 4 -</option>
															<option value="5">- 5 -</option>
															<option value="6">- 6 -</option>
															<option value="7">- 7 -</option>
															<option value="8">- 8 -</option>
															<option value="9">- 9 -</option>
															<option value="10">- 10 -</option>
														</select>
													</div>
													<div class="form-group col-md-12">
														<div style = "border:1px solid red;background-color:red;">
															<div class="col-md-3" style = "font-weight:bold;color:#fff;">Transfer Name</div>
															<div class="col-md-2" style = "font-weight:bold;color:#fff;">Duration</div>
															<div class="col-md-3" style = "font-weight:bold;color:#fff;text-align:center;">Availability</div>
															<div class="col-md-2" style = "font-weight:bold;color:#fff;text-align:center;">Rate</div>
															<div class="clearfix"></div>
														</div>
														<div style = "padding:20px 0 0 0;border:1px solid red;">
															<div class="col-md-3" style = "font-weight:bold;">Airport to Hotel Drop-off</div>
															<div class="col-md-2" style = "font-weight:bold;">0 hr 45 mins</div>
															<div class="col-md-3" style = "font-weight:bold;text-align:center;"><button type="button" class="btn btn-success next-step">AVAILABLE</button></div>
															<div class="col-md-2" style = "font-weight:bold;text-align:center;color:red;">$50.67</div>
															<div class="clearfix"></div>
															<div class="col-md-3"><img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>transfer/img1.jpg" border = "0" alt = "" width = "250" height = "150" /></div>
															<div class="col-md-9">Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium</div>
															<div class="clearfix"></div>
															<div class="col-md-12">
																<a href = "javascript:void(0);" target = "_blank"><b>MORE INFO</b></a> | <a href = "javascript:void(0);" onclick = "show_transfers('transfer1');"><b>VIEW AVAILABLE OFFERS</b></a>
															</div>
															<div class="clearfix"></div>
															<div id = "transfer1" style = "display:none;">
																<div style = "border:1px solid gray;background-color:gray;margin-top:10px;">
																	<div class="col-md-1" style = "font-weight:bold;color:#fff;">#</div>
																	<div class="col-md-3" style = "font-weight:bold;color:#fff;">Vehicle</div>
																	<div class="col-md-3" style = "font-weight:bold;color:#fff;">Pick Up</div>
																	<div class="col-md-3" style = "font-weight:bold;color:#fff;">Drop Off</div>
																	<div class="col-md-2" style = "font-weight:bold;color:#fff;text-align:center;">Total Amount</div>
																	<div class="clearfix"></div>
																</div>
																<div style = "padding:10px 0 10px 0;border:1px solid gray;">
																	<div class="col-md-1" style = "font-weight:bold;">
																		<img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>a_icon.png" border = "0" alt = "" />
																		<br/>
																		<input type = "radio" name = "select" />
																	</div>
																	<div class="col-md-3">
																		Private Transfer Van/Car (2 pax)
																	</div>
																	<div class="col-md-3">Airport - Suvarnabhumi International Airport</div>
																	<div class="col-md-3">City Drop-off</div>
																	<div class="col-md-2" style = "font-weight:bold;color:red;text-align:center;">$37.77</div>
																	<div class="clearfix"></div>
																</div>
																<div style = "padding:10px 0 10px 0;border:1px solid gray;">
																	<div class="col-md-1" style = "font-weight:bold;">
																		<img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>r_icon.png" border = "0" alt = "" />
																		<br/>
																		<input type = "radio" name = "select" />
																	</div>
																	<div class="col-md-3">
																		Private Transfer Van/Car (2 pax)
																	</div>
																	<div class="col-md-3">Airport - Suvarnabhumi International Airport</div>
																	<div class="col-md-3">Accomodation Drop-off</div>
																	<div class="col-md-2" style = "font-weight:bold;color:red;text-align:center;">$47.77</div>
																	<div class="clearfix"></div>
																</div>
															</div>
														</div>
													</div>

													<div class="form-group col-md-12">
														<div style = "border:1px solid red;background-color:red;">
															<div class="col-md-3" style = "font-weight:bold;color:#fff;">Transfer Name</div>
															<div class="col-md-2" style = "font-weight:bold;color:#fff;">Duration</div>
															<div class="col-md-3" style = "font-weight:bold;color:#fff;text-align:center;">Availability</div>
															<div class="col-md-2" style = "font-weight:bold;color:#fff;text-align:center;">Rate</div>
															<div class="clearfix"></div>
														</div>
														<div style = "padding:20px 0 0 0;border:1px solid red;">
															<div class="col-md-3" style = "font-weight:bold;">Airport to Airport Drop-off</div>
															<div class="col-md-2" style = "font-weight:bold;">0 hr 45 mins</div>
															<div class="col-md-3" style = "font-weight:bold;text-align:center;"><button type="button" class="btn btn-success next-step">AVAILABLE</button></div>
															<div class="col-md-2" style = "font-weight:bold;text-align:center;color:red;">$50.67</div>
															<div class="clearfix"></div>
															<div class="col-md-3"><img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>transfer/img2.jpg" border = "0" alt = "" width = "250" height = "150" /></div>
															<div class="col-md-9">Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium</div>
															<div class="clearfix"></div>
															<div class="col-md-12">
																<a href = "javascript:void(0);" target = "_blank"><b>MORE INFO</b></a> | <a href = "javascript:void(0);" onclick = "show_transfers('transfer2');"><b>VIEW AVAILABLE OFFERS</b></a>
															</div>
															<div class="clearfix"></div>
															<div id = "transfer2" style = "display:none;">
																<div style = "border:1px solid gray;background-color:gray;margin-top:10px;">
																	<div class="col-md-1" style = "font-weight:bold;color:#fff;">#</div>
																	<div class="col-md-3" style = "font-weight:bold;color:#fff;">Vehicle</div>
																	<div class="col-md-3" style = "font-weight:bold;color:#fff;">Pick Up</div>
																	<div class="col-md-3" style = "font-weight:bold;color:#fff;">Drop Off</div>
																	<div class="col-md-2" style = "font-weight:bold;color:#fff;text-align:center;">Total Amount</div>
																	<div class="clearfix"></div>
																</div>
																<div style = "padding:10px 0 10px 0;border:1px solid gray;">
																	<div class="col-md-1" style = "font-weight:bold;">
																		<img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>a_icon.png" border = "0" alt = "" />
																		<br/>
																		<input type = "radio" name = "select" />
																	</div>
																	<div class="col-md-3">
																		Private Transfer Van/Car (2 pax)
																	</div>
																	<div class="col-md-3">Airport - Suvarnabhumi International Airport</div>
																	<div class="col-md-3">Airport - Don Muang International Airport</div>
																	<div class="col-md-2" style = "font-weight:bold;color:red;text-align:center;">$37.77</div>
																	<div class="clearfix"></div>
																</div>
																<div style = "padding:10px 0 10px 0;border:1px solid gray;">
																	<div class="col-md-1" style = "font-weight:bold;">
																		<img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>r_icon.png" border = "0" alt = "" />
																		<br/>
																		<input type = "radio" name = "select" />
																	</div>
																	<div class="col-md-3">
																		Private Transfer Van/Car (2 pax)
																	</div>
																	<div class="col-md-3">Airport - Suvarnabhumi International Airport</div>
																	<div class="col-md-3">Airport - Don Muang International Airport</div>
																	<div class="col-md-2" style = "font-weight:bold;color:red;text-align:center;">$47.77</div>
																	<div class="clearfix"></div>
																</div>
															</div>
														</div>
													</div>
												</form>
												<ul class="list-inline pull-right">
													<li><button type="button" class="btn btn-warning prev-step">Back To Hotel List</button></li>
													<li><button type="button" class="btn btn-default next-step">Skip</button></li>
													<li><button type="button" class="btn btn-primary btn-info-full next-step">Save and continue</button></li>
												</ul>
											</div>
											<div class="tab-pane" role="tabpanel" id="step4">
												<h3>Search Tours</h3>
												<div class="col-md-6">
													<p>Your search for <font color = "red"><b>Thailand</b></font>, <font color = "red"><b>Bangkok</b></font> for <font color = "red"><b>01-03-2018</b></font> for <font color = "red"><b>1 Passenger(s)</b></font> gave <font color = "red"><b>2 tours</b></font></p>
												</div>
												<div class="col-md-6">
													<p><b>SORT BY:</b>&nbsp;&nbsp;&nbsp;<input type = "radio" name = "sort" / >&nbsp;Price&nbsp;&nbsp;<input type = "radio" name = "sort" / >&nbsp;Name
												</div>
												<form name="profile" name="form_create_slider" id="form_create_slider" method="POST" enctype="mulimedeia/form-data">
													<div class="form-group col-md-3">
														<label for="inputName" class="control-label">Filter By Service Type<font color="#FF0000">*</font></label>
														<select class="form-control validate[optional]" name="sel_avlbl_hotel">
															<option value="">- All Type -</option>
															<option label="Private" value="Private">Private</option>
															<option label="Shared" value="Shared">Shared</option>
														</select>
													</div>
													<div class="form-group col-md-3">
														<label for="inputName" class="control-label">Filter By Tour Type<font color="#FF0000">*</font></label>
														<select class="form-control validate[optional]" name="sel_avlbl_hotel">
															<option value="">- All Type -</option>
															<option label="Ticket Only" value="Ticket Only">Ticket Only</option>
															<option label="Full Tour including Lunch" value="Full Tour including Lunch">Full Tour including Lunch</option>
															<option label="Full Tour including Dinner" value="Port">Full Tour including Dinner</option>
															<option label="Breakfast" value="Breakfast">Breakfast</option>
															<option label="Lunch" value="Lunch">Lunch</option>
															<option label="Dinner" value="Dinner">Dinner</option>
														</select>
													</div>
													<div class="form-group col-md-6">
														<label for="inputName" class="control-label">Select Page<font color="#FF0000">*</font></label>
														<select class="form-control validate[optional]" name="sel_avlbl_hotel">
															<option value="1">- 1 -</option>
															<option value="2">- 2 -</option>
															<option value="3">- 3 -</option>
															<option value="4">- 4 -</option>
															<option value="5">- 5 -</option>
															<option value="6">- 6 -</option>
															<option value="7">- 7 -</option>
															<option value="8">- 8 -</option>
															<option value="9">- 9 -</option>
															<option value="10">- 10 -</option>
														</select>
													</div>
													<div class="form-group col-md-12">
														<div style = "border:1px solid red;background-color:red;">
															<div class="col-md-3" style = "font-weight:bold;color:#fff;">Tour Name</div>
															<div class="col-md-2" style = "font-weight:bold;color:#fff;">Transfer Type</div>
															<div class="col-md-3" style = "font-weight:bold;color:#fff;text-align:center;">Availability</div>
															<div class="col-md-2" style = "font-weight:bold;color:#fff;text-align:center;">Rate</div>
															<div class="clearfix"></div>
														</div>
														<div style = "padding:20px 0 0 0;border:1px solid red;">
															<div class="col-md-3" style = "font-weight:bold;">Art In Paradise Bangkok - Ticket Only</div>
															<div class="col-md-2" style = "font-weight:bold;">Private</div>
															<div class="col-md-3" style = "font-weight:bold;text-align:center;"><button type="button" class="btn btn-success next-step">AVAILABLE</button></div>
															<div class="col-md-2" style = "font-weight:bold;text-align:center;color:red;">$50.67</div>
															<div class="clearfix"></div>
															<div class="col-md-3"><img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>tour/img1.jpg" border = "0" alt = "" width = "250" height = "150" /></div>
															<div class="col-md-9">Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium</div>
															<div class="clearfix"></div>
															<div class="col-md-12">
																<a href = "javascript:void(0);" target = "_blank"><b>MORE INFO</b></a> | <a href = "javascript:void(0);" onclick = "show_tours('tour1');"><b>VIEW AVAILABLE OFFERS</b></a>
															</div>
															<div class="clearfix"></div>
															<div id = "tour1" style = "display:none;">
																<div style = "border:1px solid gray;background-color:gray;margin-top:10px;">
																	<div class="col-md-1" style = "font-weight:bold;color:#fff;">#</div>
																	<div class="col-md-3" style = "font-weight:bold;color:#fff;">Offer Title</div>
																	<div class="col-md-3" style = "font-weight:bold;color:#fff;">Service Type</div>
																	<div class="col-md-3" style = "font-weight:bold;color:#fff;">Capacity</div>
																	<div class="col-md-2" style = "font-weight:bold;color:#fff;text-align:center;">Total Amount</div>
																	<div class="clearfix"></div>
																</div>
																<div style = "padding:10px 0 10px 0;border:1px solid gray;">
																	<div class="col-md-1" style = "font-weight:bold;">
																		<img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>a_icon.png" border = "0" alt = "" />
																		<br/>
																		<input type = "radio" name = "select" />
																	</div>
																	<div class="col-md-3">
																		Per Person (1-15 Pax)
																	</div>
																	<div class="col-md-3">Share</div>
																	<div class="col-md-3">1-15</div>
																	<div class="col-md-2" style = "font-weight:bold;color:red;text-align:center;">$37.77</div>
																	<div class="clearfix"></div>
																</div>
																<div style = "padding:10px 0 10px 0;border:1px solid gray;">
																	<div class="col-md-1" style = "font-weight:bold;">
																		<img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>r_icon.png" border = "0" alt = "" />
																		<br/>
																		<input type = "radio" name = "select" />
																	</div>
																	<div class="col-md-3">
																		Per Person (1-5 Pax)
																	</div>
																	<div class="col-md-3">Private</div>
																	<div class="col-md-3">1-5</div>
																	<div class="col-md-2" style = "font-weight:bold;color:red;text-align:center;">$47.77</div>
																	<div class="clearfix"></div>
																</div>
															</div>
														</div>
													</div>

													<div class="form-group col-md-12">
														<div style = "border:1px solid red;background-color:red;">
															<div class="col-md-3" style = "font-weight:bold;color:#fff;">Tour Name</div>
															<div class="col-md-2" style = "font-weight:bold;color:#fff;">Transfer Type</div>
															<div class="col-md-3" style = "font-weight:bold;color:#fff;text-align:center;">Availability</div>
															<div class="col-md-2" style = "font-weight:bold;color:#fff;text-align:center;">Rate</div>
															<div class="clearfix"></div>
														</div>
														<div style = "padding:20px 0 0 0;border:1px solid red;">
															<div class="col-md-3" style = "font-weight:bold;">Bangkok City Tour with Indian Lunch</div>
															<div class="col-md-2" style = "font-weight:bold;">Private</div>
															<div class="col-md-3" style = "font-weight:bold;text-align:center;"><button type="button" class="btn btn-success next-step">AVAILABLE</button></div>
															<div class="col-md-2" style = "font-weight:bold;text-align:center;color:red;">$50.67</div>
															<div class="clearfix"></div>
															<div class="col-md-3"><img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>tour/img2.jpg" border = "0" alt = "" width = "250" height = "150" /></div>
															<div class="col-md-9">Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium Lorem Ipsium</div>
															<div class="clearfix"></div>
															<div class="col-md-12">
																<a href = "javascript:void(0);" target = "_blank"><b>MORE INFO</b></a> | <a href = "javascript:void(0);" onclick = "show_tours('tour2');"><b>VIEW AVAILABLE OFFERS</b></a>
															</div>
															<div class="clearfix"></div>
															<div id = "tour2" style = "display:none;">
																<div style = "border:1px solid gray;background-color:gray;margin-top:10px;">
																	<div class="col-md-1" style = "font-weight:bold;color:#fff;">#</div>
																	<div class="col-md-3" style = "font-weight:bold;color:#fff;">Offer Title</div>
																	<div class="col-md-3" style = "font-weight:bold;color:#fff;">Service Type</div>
																	<div class="col-md-3" style = "font-weight:bold;color:#fff;">Capacity</div>
																	<div class="col-md-2" style = "font-weight:bold;color:#fff;text-align:center;">Total Amount</div>
																	<div class="clearfix"></div>
																</div>
																<div style = "padding:10px 0 10px 0;border:1px solid gray;">
																	<div class="col-md-1" style = "font-weight:bold;">
																		<img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>a_icon.png" border = "0" alt = "" />
																		<br/>
																		<input type = "radio" name = "select" />
																	</div>
																	<div class="col-md-3">
																		Per Person (1-15 Pax)
																	</div>
																	<div class="col-md-3">Share</div>
																	<div class="col-md-3">1-15</div>
																	<div class="col-md-2" style = "font-weight:bold;color:red;text-align:center;">$37.77</div>
																	<div class="clearfix"></div>
																</div>
																<div style = "padding:10px 0 10px 0;border:1px solid gray;">
																	<div class="col-md-1" style = "font-weight:bold;">
																		<img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>r_icon.png" border = "0" alt = "" />
																		<br/>
																		<input type = "radio" name = "select" />
																	</div>
																	<div class="col-md-3">
																		Per Person (1-5 Pax)
																	</div>
																	<div class="col-md-3">Private</div>
																	<div class="col-md-3">1-5</div>
																	<div class="col-md-2" style = "font-weight:bold;color:red;text-align:center;">$47.77</div>
																	<div class="clearfix"></div>
																</div>
															</div>
														</div>
													</div>
												</form>
												<ul class="list-inline pull-right">
													<li><button type="button" class="btn btn-warning prev-step">Manage Transfer</button></li>
													<li><button type="button" class="btn btn-default next-step">Skip</button></li>
													<li><button type="button" class="btn btn-primary btn-info-full next-step">Save and continue</button></li>
												</ul>
											</div>
											<div class="tab-pane" role="tabpanel" id="complete">
												<h3>Complete Your Booking</h3>
												<form name="profile" name="form_create_slider" id="form_create_slider" method="POST" enctype="mulimedeia/form-data">
													<div class="col-md-12 row">
														<div class="box-body">
															<div class="form-group col-md-6">
																<label for="inputName" class="control-label">Quotation Name<font color="#FF0000">*</font></label>
																<br/>
																<div style = "float:left;">
																	<input type="text" class="form-control validate[required]"  value="" name="quotation_name" id="quotation_name" placeholder="Quotation Name" tabindex = "1"  />
																</div>
																<div style = "float:left;">
																	&nbsp;<button type="button" class="btn btn-primary">Save</button>
																</div>
																<div style = "clear:both;"></div>
															</div>
															<div class="form-group col-md-6">
																<label for="inputName" class="control-label">Choose Payment Method<font color="#FF0000">*</font></label>
																<br/>
																<div style = "float:left;">
																	<select class="form-control validate[optional]" name="sel_avlbl_hotel">
																		<option value="">Offine</option>
																		<option label="PayUMoney" value="PayUMoney">PayUMoney</option>
																		<option label="PayPal" value="PayPal">PayPal</option>
																	</select>
																</div>
																<div style = "float:left;">
																	&nbsp;<button type="button" class="btn btn-primary">Continue</button>
																</div>
																<div style = "clear:both;"></div>
															</div>
														</div>
														<div class="box-body">
															<table aria-describedby="example1_info" id="example" class="table table-bordered table-striped dataTable">
																<thead>
																	<tr role="row">
																		<th style = "text-align:center;">Pax</th>
																		<th style = "text-align:center;">Quote Date</th>
																		<th style = "text-align:center;">Destination</th>
																		<th style = "text-align:center;">Service Date</th>
																	</tr>
																</thead>
																<tbody aria-relevant="all" aria-live="polite" role="alert">
																	<tr class="odd">
																		<td style = "text-align:center;">1</td>
																		<td style = "text-align:center;">28 Feb, 2018</td>
																		<td style = "text-align:center;">Bangkok</td>
																		<td style = "text-align:center;">01 Mar, 2018</td>
																	</tr>
																</tbody>
															</table>
														</div>
														<div class="box-body">
															<table aria-describedby="example1_info" id="example" class="table table-bordered table-striped dataTable">
																<thead>
																	<tr role="row">
																		<th style = "text-align:left;">Hotel</th>
																		<th style = "text-align:center;">Room Type</th>
																		<th style = "text-align:center;">Check In</th>
																		<th style = "text-align:center;">Check Out</th>
																		<th style = "text-align:center;">Rooms</th>
																		<th style = "text-align:center;">Nights</th>
																	</tr>
																</thead>
																<tbody aria-relevant="all" aria-live="polite" role="alert">
																	<tr class="odd">
																		<td style = "text-align:left;">Hotel Seagul</td>
																		<td style = "text-align:center;">
																			Single Room Standard Room Only
																			<br/>
																			<font color = "red">*No Check Out Allowed on 31st Dec.</font>
																		</td>
																		<td style = "text-align:center;">01 Mar, 2018</td>
																		<td style = "text-align:center;">04 Mar, 2018</td>
																		<td style = "text-align:center;">1</td>
																		<td style = "text-align:center;">3</td>
																	</tr>
																</tbody>
															</table>
														</div>
														<div class="box-body">
															<table aria-describedby="example1_info" id="example" class="table table-bordered table-striped dataTable">
																<thead>
																	<tr role="row">
																		<th style = "text-align:left;">Tour Sites</th>
																	</tr>
																</thead>
																<tbody aria-relevant="all" aria-live="polite" role="alert">
																	<tr class="odd">
																		<td style = "text-align:left;">Art In Paradise Bangkok - Ticket Only - - Private-Tour Van ( Units of vehicle: 1 )</td>
																	</tr>
																</tbody>
															</table>
														</div>
														<div class="box-body">
															<table aria-describedby="example1_info" id="example" class="table table-bordered table-striped dataTable">
																<thead>
																	<tr role="row">
																		<th style = "text-align:left;">Transfers</th>
																	</tr>
																</thead>
																<tbody aria-relevant="all" aria-live="polite" role="alert">
																	<tr class="odd">
																		<td style = "text-align:left;">
																		Bangkok - BANGKOK SUVARNABHUMI AIRPORT - BANGKOK DON MUANG AIRPORT - - Private Transfer Van/Car (2 pax) ( Units of vehicle: 1 )
																		<br/>
																		Pick Up: Airport - Suvarnabhumi International Airport
																		<br/>
																		Drop off: Airport - Don Muang International Airport
																		</td>
																	</tr>
																</tbody>
															</table>
														</div>
														<div class="box-body">
															<table aria-describedby="example1_info" id="example" class="table table-bordered table-striped dataTable">
																<thead>
																	<tr role="row">
																		<th style = "text-align:center;" colspan = "3">Quotation ($)</th>
																	</tr>
																</thead>
																<tbody aria-relevant="all" aria-live="polite" role="alert">
																	<tr class="odd">
																		<td style = "text-align:left;font-weight:bold;">Total Cost for Hotel Accommodation</td>
																		<td style = "text-align:center;font-weight:bold;" colspan = "2">$3796.77</td>
																	</tr>
																	<tr class="odd">
																		<td style = "text-align:left;font-weight:bold;">
																			Add-on : Cost for other components Tours, Transfer & Meals
																		</td>
																		<td style = "text-align:center;font-weight:bold;">
																			PER ADULT
																			<br/>
																			$4678.53
																		</td>
																		<td style = "text-align:center;font-weight:bold;">
																			PER CHILD
																			<br/>
																			$0.00
																		</td>
																	</tr>
																	<tr class="odd">
																		<td style = "text-align:left;font-weight:bold;">
																			No of Guests
																		</td>
																		<td style = "text-align:center;font-weight:bold;">
																			1
																		</td>
																		<td style = "text-align:center;font-weight:bold;">
																			0
																		</td>
																	</tr>
																	<tr class="odd">
																		<td style = "text-align:left;font-weight:bold;">Total Quantity</td>
																		<td style = "text-align:center;font-weight:bold;" colspan = "2">$8475.00</td>
																	</tr>
																</tbody>
															</table>
														</div>
														
													</div>
												</form>
											</div>
											<div class="clearfix"></div>
										</div>
									</form>
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