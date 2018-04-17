<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');
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
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>CREATE NEW BOOKING</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<script src="<?php echo(DOMAIN_NAME_PATH_ADMIN);?>assets/raty/jquery.raty.js" type="text/javascript"></script>
	<style type="text/css">
		.hide_age_div, .each_tab_content{display:none;}
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
		.city_tab_button_div{margin-bottom: 20px;}
		.cls_each_city_tab_div{
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
							fetch_secend_step2_rcd(page, type);
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
	});
	CKEDITOR.config.autoParagraph = false;
	CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
	CKEDITOR.config.shiftEnterMode = CKEDITOR.ENTER_BR;
	CKEDITOR.config.protectedSource.push(/<i[^>]*><\/i>/g);
	CKEDITOR.config.allowedContent = true;
	jQuery(document).ready(function(){
		jQuery("#profile").validationEngine();
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

	function show_transfers(id) {
		if(id == "transfer1")
		{
			if(document.getElementById('transfer1').style.display == 'none')
			{
				document.getElementById('transfer1').style.display = 'block';
			}
			else
			{
				document.getElementById('transfer1').style.display = 'none';
			}
		}
		if(id == "transfer2")
		{
			if(document.getElementById('transfer2').style.display == 'none')
			{
				document.getElementById('transfer2').style.display = 'block';
			}
			else
			{
				document.getElementById('transfer2').style.display = 'none';
			}
		}
	}

	function show_tours(id) {
		if(id == "tour1")
		{
			if(document.getElementById('tour1').style.display == 'none')
			{
				document.getElementById('tour1').style.display = 'block';
			}
			else
			{
				document.getElementById('tour1').style.display = 'none';
			}
		}
		if(id == "tour2")
		{
			if(document.getElementById('tour2').style.display == 'none')
			{
				document.getElementById('tour2').style.display = 'block';
			}
			else
			{
				document.getElementById('tour2').style.display = 'none';
			}
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
		var total_days_selected=datediff($("#checkin").val(), $("#checkout").val())+1;
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
			$(".each_tab_content").removeClass("active_each_tab_content");
			$(".cls_each_city_tab_div").removeClass("cls_each_city_tab_div_active");
			cur.addClass("cls_each_city_tab_div_active");
			$("#"+cur.attr("data-tab_id")).addClass("active_each_tab_content");
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
		fetch_secend_step2_rcd(page, type, sort_order, city_id, country_id, search_val);
	}
	function filter_search(cur, city_id)
	{
		var search_val=$("#keyword_search"+city_id).val();
		var country_id=cur.attr("data-country_id");
		var sort_order=cur.parents("#city"+city_id).find("input[name='sort']:checked").val();
		var page=1;
		var type=1;
		fetch_secend_step2_rcd(page, type, sort_order, city_id, country_id, search_val);
	}
	function fetch_secend_step2_rcd(page, type, sort_order='', city_id='', country_id='', search_val='')
	{
		if(type==2)
		{
		}
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
				console.log(JSON.stringify(response, null, 4));
				if(response.status=="success")
				{
					if(sort_order!="" || search_val!="")
					{
						$("#city"+city_id).html(response.hotel_data);
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
												<a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" >
												<span class="round-tab">
													<i class="fa fa-bars fa-1x" ></i>
												</span>
												</a>
											</li>

											<li role="presentation" class="disabled">
												<a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" >
												<span class="round-tab">
													<i class="fa fa-bed fa-1x" ></i>
												</span>
												</a>
											</li>

											<li role="presentation" class="disabled">
												<a href="#step3" data-toggle="tab" aria-controls="step3" role="tab" >
												<span class="round-tab">
													<i class="fa fa-car fa-1x" ></i>
												</span>
												</a>
											</li>

											<li role="presentation" class="disabled">
												<a href="#step4" data-toggle="tab" aria-controls="step4" role="tab" >
												<span class="round-tab">
													<i class="fa fa-road fa-1x" ></i>
												</span>
												</a>
											</li>

											<li role="presentation" class="disabled">
												<a href="#complete" data-toggle="tab" aria-controls="complete" role="tab" >
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
												<form name="form_secend_step" id="form_secend_step" method="POST" enctype="multipart/form-data">
													<div class="city_tab_button_div">
														<!-- City Tab -->
													</div>
													<div class="main_tab_content_outer hotel_tab_all_data_div">
														<!-- <div class="each_tab_content active_each_tab_content" id="city1">
															<div class="col-md-6">
																<p>Your search for <font color = "red"><b>Thailand</b></font>, <font color = "red"><b>Bangkok</b></font> for <font color = "red"><b>3 Night(s)</b></font> fetched <font color = "red"><b>782 Hotels</b></font></p>
															</div>
															<div class="col-md-6">
																<p><b>SORT BY:</b>&nbsp;&nbsp;&nbsp;<input type = "radio" name = "sort" / >&nbsp;Price&nbsp;&nbsp;<input type = "radio" name = "sort" / >&nbsp;Hotel Name&nbsp;&nbsp;<input type = "radio" name = "sort" / >&nbsp;Rating</p>
															</div>												
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
															<div class="clearfix"></div>
															<div class="all_rcd_row">
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
																			<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>view_hotel_details" target = "_blank" style = "font-size:16px;"><b>MORE INFO</b></a> | <a href = "javascript:void(0);" onclick = "show_rooms('hotel1');" style = "font-size:16px;"><b>VIEW AVAILABLE ROOMS</b></a>
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
																			<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>view_hotel_details" target = "_blank" style = "font-size:16px;"><b>MORE INFO</b></a> | <a href = "javascript:void(0);"  onclick = "show_rooms('hotel2');" style = "font-size:16px;"><b>VIEW AVAILABLE ROOMS</b></a>
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
																<div class="clearfix"></div>
															</div>
														</div>
														<div class="each_tab_content" id="city2">
															<div class="col-md-6">
																<p>Your search for <font color = "red"><b>Thailand sfs</b></font>, <font color = "red"><b>Bangkok</b></font> for <font color = "red"><b>3 Night(s)</b></font> fetched <font color = "red"><b>782 Hotels</b></font></p>
															</div>
															<div class="col-md-6">
																<p><b>SORT BY:</b>&nbsp;&nbsp;&nbsp;<input type = "radio" name = "sort" / >&nbsp;Price&nbsp;&nbsp;<input type = "radio" name = "sort" / >&nbsp;Hotel Name&nbsp;&nbsp;<input type = "radio" name = "sort" / >&nbsp;Rating</p>
															</div>												
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
															<div class="clearfix"></div>
															<div class="all_rcd_row">
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
																			<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>view_hotel_details" target = "_blank" style = "font-size:16px;"><b>MORE INFO</b></a> | <a href = "javascript:void(0);" onclick = "show_rooms('hotel1');" style = "font-size:16px;"><b>VIEW AVAILABLE ROOMS</b></a>
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
																			<a href = "<?php echo(DOMAIN_NAME_PATH_ADMIN);?>view_hotel_details" target = "_blank" style = "font-size:16px;"><b>MORE INFO</b></a> | <a href = "javascript:void(0);"  onclick = "show_rooms('hotel2');" style = "font-size:16px;"><b>VIEW AVAILABLE ROOMS</b></a>
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
																<div class="clearfix"></div>
															</div>
														</div> -->
													</div>
													<ul class="list-inline pull-right">
														<li><button type="button" class="btn btn-warning prev-step">Modify Search</button></li>
														<li><button type="button" class="btn btn-primary next-step">Save and continue</button></li>
													</ul>
												</form>
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
																<a href = "javascript:void(0);" target = "_blank" style = "font-size:16px;"><b>MORE INFO</b></a> | <a href = "javascript:void(0);" onclick = "show_transfers('transfer1');" style = "font-size:16px;"><b>VIEW AVAILABLE OFFERS</b></a>
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
																<a href = "javascript:void(0);" target = "_blank" style = "font-size:16px;"><b>MORE INFO</b></a> | <a href = "javascript:void(0);" onclick = "show_transfers('transfer2');" style = "font-size:16px;"><b>VIEW AVAILABLE OFFERS</b></a>
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
																<a href = "javascript:void(0);" target = "_blank" style = "font-size:16px;"><b>MORE INFO</b></a> | <a href = "javascript:void(0);" onclick = "show_tours('tour1');" style = "font-size:16px;"><b>VIEW AVAILABLE OFFERS</b></a>
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
																<a href = "javascript:void(0);" target = "_blank" style = "font-size:16px;"><b>MORE INFO</b></a> | <a href = "javascript:void(0);" onclick = "show_tours('tour2');" style = "font-size:16px;"><b>VIEW AVAILABLE OFFERS</b></a>
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
																		<td style = "text-align:center;font-weight:bold;color:red;" colspan = "2">$8475.00</td>
																	</tr>
																</tbody>
															</table>
														</div>
														
													</div>
												</form>
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