<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
	unset($_SESSION['step_1']);
	unset($_SESSION['step_2']);
	unset($_SESSION['step_3']);
	unset($_SESSION['step_4']);
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
								<div class="wizard">
									<div class="wizard-inner">
										<div class="connecting-line"></div>
										<ul class="nav nav-tabs" role="tablist">
											<li role="presentation" class="active">
												<a href="#step1" data-toggle="tab" aria-controls="step1" role="tab">
												<span class="round-tab">
													<i class="fa fa-bars fa-1x" ></i>
												</span>
												</a>
											</li>

											<li role="presentation" class="disabled">
												<a href="#step2" data-toggle="tab" aria-controls="step2" role="tab">
												<span class="round-tab">
													<i class="fa fa-bed fa-1x" ></i>
												</span>
												</a>
											</li>

											<li role="presentation" class="disabled">
												<a href="#step3" data-toggle="tab" aria-controls="step3" role="tab">
												<span class="round-tab">
													<i class="fa fa-road fa-1x" ></i>
												</span>
												</a>
											</li>

											<li role="presentation" class="disabled">
												<a href="#step4" data-toggle="tab" aria-controls="step4" role="tab">
												<span class="round-tab">										
													<i class="fa fa-car fa-1x" ></i>
												</span>
												</a>
											</li>

											<li role="presentation" class="disabled">
												<a href="#complete" data-toggle="tab" aria-controls="complete" role="tab">
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
														<div class="box-body" style = "border:1px solid gray;">
															<div class="form-group col-md-6">
																<label for="inputName" class="control-label">Select Booking Type<font color="#FF0000">*</font></label>
																<select name = "booking_type" id = "booking_type" class="form-control validate[required]"  tabindex = "1" onchange = "manage_booking_type(this.value);">
																	<option value = "personal" <?php echo(isset($_POST['booking_type']) && $_POST['booking_type']=="personal" ? "selected='selected'" : "");?>>Personal Booking</option>
																	<option value = "agent" <?php echo(isset($_POST['booking_type']) && $_POST['booking_type']=="agent" ? "selected='selected'" : (isset($agent_data) &&  !empty($agent_data) ? "selected='selected'" : ""));?>>Agent Booking</option>
																</select>
															</div>
															<div class="form-group col-md-6">
																<label for="inputName" class="control-label">Select Agent<font color="#FF0000">*</font></label>
																<select name = "agent_name" id = "agent_name" class="form-control validate[required]"  tabindex = "2" <?php echo(isset($_POST['agent_name']) && $_POST['agent_name']!="" ? '' : 'disabled');?>>
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
															<div class="form-group col-md-6">
																<label for="inputName" class="control-label">Check In<font color="#FF0000">*</font></label>
																<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['checkin']) && $_POST['checkin']!='' ? $_POST['checkin'] : "");?>" name="checkin" id="checkin" placeholder="Check In" tabindex = "3"  />
															</div>
															<div class="form-group col-md-6">
																<label for="inputName" class="control-label">Check Out<font color="#FF0000">*</font></label>
																<input type="text" class="form-control validate[required]"  value="<?php echo(isset($_POST['checkout']) && $_POST['checkout']!='' ? $_POST['checkout'] : "");?>" name="checkout" id="checkout" placeholder="Check Out" tabindex = "4" />
															</div>
															<div class="clearfix"></div>
														</div>
														<div class="box-body">
														</div>
														<div class="box-body" id = "sample"  style = "border:1px solid gray;">
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
																<a href = "javascript:void(0);" class="add-row" data-attr_key="<?php echo $next_index;?>"><img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>plus-icon.png" border = "0" alt = "" /></a>&nbsp;&nbsp;<b>ADD ANOTHER DESTINATION</b>&nbsp;&nbsp;<a href = "javascript:void(0);" class="delete-row"><img src = "<?php echo(CONTROL_CENTER_IMAGE_PATH);?>minus-icon.png" border = "0" alt = "" /></a>
															</div>
														</div>
														<div class="box-body">
														</div>
														<div class="box-body" style = "border:1px solid gray;">
															<div class="form-group col-md-4">
																<label for="inputName" class="control-label">Nationality<font color="#FF0000">*</font></label>
																<select class="form-control validate[required]" name="sel_nationality" id="sel_nationality"> 
																	<option value = "">Select Nationality</option>
																	<?php
																	if(!empty($contry_list)):
																		foreach($contry_list as $country_key=>$country_val):
																	?>
																		<option value = "<?php echo $country_val['id'];?>" <?php echo(isset($_POST['sel_nationality']) && $_POST['sel_nationality']==$country_val['id'] ? 'selected="selected"' : "");?>><?php echo $country_val['name'];?></option>
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
																	<option value = "<?php echo $country_val['id'];?>" <?php echo(isset($_POST['country_residance']) && $_POST['country_residance']==$country_val['id'] ? 'selected="selected"' : "");?>><?php echo $country_val['name'];?></option>
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
											<div class="tab-pane" role="tabpanel" id="step3">
												<h3>Search Tour</h3>
												<div class="tour_city_tab_button_div">
													<!-- Tour City Tab -->
												</div>
												<div class="main_tab_content_outer tour_tab_all_data_div">
													<!-- All tour tab content -->
												</div>
												<ul class="list-inline pull-right">
													<li><button type="button" class="btn btn-warning prev-step">Back To Hotel List</button></li>
													<li><button type="button" class="btn btn-default save_step3_data">Skip</button></li>
													<li><button type="button" class="btn btn-primary btn-info-full save_step3_data">Save and continue</button></li>
												</ul>
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
													<li><button type="button" class="btn btn-warning prev-step">Manage Tour</button></li>
													<li><button type="button" class="btn btn-default save_step4_data">Skip</button></li>
													<li><button type="button" class="btn btn-primary btn-info-full save_step4_data">Save and continue</button></li>
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
																	<input type="text" class="form-control validate[required]"  value="" name="quotation_name" id="quotation_name" placeholder="Quotation Name" tabindex = "1"  />
																</div>
																<div style = "float:left;">
																	&nbsp;<button type="submit" class="btn btn-primary quotation_name_save_btn">Save</button>
																</div>
																<div class = "clearfix"></div>
															</form>
														</div>
														<div class="form-group col-md-6">
															<form method="post" action="" name="payment_method_form" id="payment_method_form">
																<label for="inputName" class="control-label">Choose Payment Method<font color="#FF0000">*</font></label>
																<br/>
																<div style = "float:left;">
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