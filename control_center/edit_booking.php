<?php
require_once('../loader.inc');
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo(DEFAULT_PAGE_TITLE_CONTROL_CENTER);?>EDIT BOOKING</title>
	<?php require_once(CONTROL_CENTER_COMMON_FILE_PATH.'meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	jQuery(document).ready(function(){
		jQuery("#form_create_slider").validationEngine();
	});
	//-->
	</script>
	<script type="text/javascript">
	CKEDITOR.config.autoParagraph = false;
	CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
	CKEDITOR.config.shiftEnterMode = CKEDITOR.ENTER_BR;
	CKEDITOR.config.protectedSource.push(/<i[^>]*><\/i>/g);
	CKEDITOR.config.allowedContent = true;
	</script>
	<script>
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
		}
	}

	function child_attribute(val)
	{
		if(val!='') {
			document.getElementById('child_age_div').style.display = 'block';
			document.getElementById('bed_required_div').style.display = 'block';
		}
		else {
			document.getElementById('child_age_div').style.display = 'none';
			document.getElementById('bed_required_div').style.display = 'none';
		}
	}

	function show_rooms(id) {
		if(id == "hotel1")
		{
			if(document.getElementById('hotel1').style.display == 'none')
			{
				document.getElementById('hotel1').style.display = 'block';
			}
			else
			{
				document.getElementById('hotel1').style.display = 'none';
			}
		}
		if(id == "hotel2")
		{
			if(document.getElementById('hotel2').style.display == 'none')
			{
				document.getElementById('hotel2').style.display = 'block';
			}
			else
			{
				document.getElementById('hotel2').style.display = 'none';
			}
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

	$( function() {
		$( "#checkin" ).datepicker();
		$( "#checkout" ).datepicker();
	} );
	</script>
	<script type="text/javascript">
		$(document).ready(function(){
			$(".add-row").click(function(){
				var markup = '<span><div class="form-group col-md-12"><div class="form-group col-md-3"><input type="checkbox" name="record"/>&nbsp;&nbsp;<label for="inputName" class="control-label">Country<font color="#FF0000">*</font></label><select name="country" class="form-control validate[required]" id="country"><option value="">- Select-</option><option label="AUSTRALIA" value="1">AUSTRALIA</option><option label="GREECE - ISLANDS" value="22">GREECE - ISLANDS</option><option label="GREECE - MAINLAND" value="23">GREECE - MAINLAND</option><option label="HONG KONG" value="HKG">HONG KONG</option><option label="INDONESIA" value="28">INDONESIA</option><option label="MACAU" value="MO">MACAU</option><option label="MALAYSIA" value="39">MALAYSIA</option><option label="MALDIVES" value="40">MALDIVES</option><option label="MYANMAR" value="148">MYANMAR</option><option label="SINGAPORE" value="55">SINGAPORE</option><option label="SRI LANKA" value="59">SRI LANKA</option><option label="THAILAND" value="64">THAILAND</option><option label="UNITED ARAB EMIRATES" value="67">UNITED ARAB EMIRATES</option></select></div><div class="form-group col-md-3"><label for="inputName" class="control-label">City<font color="#FF0000">*</font></label><select class="form-control validate[required]" name="city" id="city"><option value="">- Select-</option><option value="Ayutthaya">Ayutthaya</option><option value="Bangkok">Bangkok</option><option value="Cha am">Cha Am</option><option value="Chiang mai">Chiang Mai</option><option value="Chiang rai">Chiang Rai</option><option value="Hat yai">Hat Yai</option><option value="Hua hin">Hua Hin</option><option value="Kanchanaburi">Kanchanaburi</option><option value="Khao lak">Khao Lak</option><option value="Khao yai">Khao Yai</option><option value="Koh chang">Koh Chang</option><option value="Koh lanta">Koh Lanta</option><option value="Koh phangan">Koh Phangan</option><option value="Koh samui">Koh Samui</option><option value="Krabi">Krabi</option><option value="Pattaya">Pattaya</option><option value="Phang nga">Phang Nga</option><option value="Phi phi island">Phi Phi Island</option><option value="Phuket">Phuket</option><option value="Pranburi">Pranburi</option><option value="Rayong">Rayong</option><option value="Sa kaeo">Sa Kaeo</option><option value="Samui island">Samui Island</option><option value="Trat">Trat</option></select></div><div class="form-group col-md-3"><label for="inputName" class="control-label">For No Of Nights<font color="#FF0000">*</font></label><input type="text" class="form-control validate[required]"  value="" name="number_of_night" id="number_of_night" placeholder="Number Of Nights" tabindex = "4" /></div><div class="form-group col-md-3"><label for="inputName" class="control-label">Hotel Ratings<font color="#FF0000">*</font></label><br/><input type="checkbox" value="" name="hotel_ratings" id="hotel_ratings" />&nbsp;1&nbsp;&nbsp;<input type="checkbox" value="" name="hotel_ratings" id="hotel_ratings" />&nbsp;2&nbsp;&nbsp;<input type="checkbox"  value="" name="hotel_ratings" id="hotel_ratings" />&nbsp;3&nbsp;&nbsp;<input type="checkbox" value="" name="hotel_ratings" id="hotel_ratings" />&nbsp;4&nbsp;&nbsp;<input type="checkbox" value="" name="hotel_ratings" id="hotel_ratings" />&nbsp;5</div></div></span>';
				$("#sample").append(markup);
			});
			
			// Find and remove selected table rows
			$(".delete-row").click(function(){
				$("#sample").find('input[name="record"]').each(function(){
					if($(this).is(":checked")){
						$(this).parents("span").remove();
					}
				});
			});
		});    
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

											<li role="presentation" class="disabled">
												<a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" title="Select Hotel">
												<span class="round-tab">
													<i class="fa fa-bed fa-1x" ></i>
												</span>
												</a>
											</li>

											<li role="presentation" class="disabled">
												<a href="#step3" data-toggle="tab" aria-controls="step3" role="tab" title="Select Transfer">
												<span class="round-tab">
													<i class="fa fa-car fa-1x" ></i>
												</span>
												</a>
											</li>

											<li role="presentation" class="disabled">
												<a href="#step4" data-toggle="tab" aria-controls="step4" role="tab" title="Select Tour">
												<span class="round-tab">
													<i class="fa fa-road fa-1x" ></i>
												</span>
												</a>
											</li>

											<li role="presentation" class="disabled">
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
												<form name="profile" name="form_create_slider" id="form_create_slider" method="POST" enctype="mulimedeia/form-data">
													<div class="col-md-12 row">
														<div class="box-body">
															<div class="form-group col-md-6">
																<label for="inputName" class="control-label">Select Booking Type<font color="#FF0000">*</font></label>
																<select name = "booking_type" id = "booking_type" class="form-control validate[optional]"  tabindex = "1" onchange = "manage_booking_type(this.value);">
																	<option value = "personal">Personal Booking</option>
																	<option value = "agent">Agent Booking</option>
																</select>
															</div>
															<div class="form-group col-md-6">
																<label for="inputName" class="control-label">Select Agent<font color="#FF0000">*</font></label>
																<select name = "agent_name" id = "agent_name" class="form-control validate[optional]"  tabindex = "2" disabled>
																	<option value = "Sandy Smith - 023569">Sandy Smith - 023569</option>
																	<option value = "John Smith - 369856">John Smith - 369856</option>
																</select>
															</div>
															<div class="form-group col-md-6">
																<label for="inputName" class="control-label">Check In<font color="#FF0000">*</font></label>
																<input type="text" class="form-control validate[required]"  value="" name="checkin" id="checkin" placeholder="Check In" tabindex = "3"  />
															</div>
															<div class="form-group col-md-6">
																<label for="inputName" class="control-label">Check Out<font color="#FF0000">*</font></label>
																<input type="text" class="form-control validate[required]"  value="" name="checkout" id="checkout" placeholder="Check Out" tabindex = "4" />
															</div>
														</div>
														<div class="box-body" id = "sample">
															<div class="form-group col-md-12">
																<div class="form-group col-md-3">
																	<label for="inputName" class="control-label">Country<font color="#FF0000">*</font></label>
																	<select name="country" class="form-control validate[required]" id="country">
																		<option value="">- Select-</option>
																		<option label="AUSTRALIA" value="1">AUSTRALIA</option>
																		<option label="GREECE - ISLANDS" value="22">GREECE - ISLANDS</option>
																		<option label="GREECE - MAINLAND" value="23">GREECE - MAINLAND</option>
																		<option label="HONG KONG" value="HKG">HONG KONG</option>
																		<option label="INDONESIA" value="28">INDONESIA</option>
																		<option label="MACAU" value="MO">MACAU</option>
																		<option label="MALAYSIA" value="39">MALAYSIA</option>
																		<option label="MALDIVES" value="40">MALDIVES</option>
																		<option label="MYANMAR" value="148">MYANMAR</option>
																		<option label="SINGAPORE" value="55">SINGAPORE</option>
																		<option label="SRI LANKA" value="59">SRI LANKA</option>
																		<option label="THAILAND" value="64">THAILAND</option>
																		<option label="UNITED ARAB EMIRATES" value="67">UNITED ARAB EMIRATES</option>
																	</select>
																</div>
																<div class="form-group col-md-3">
																	<label for="inputName" class="control-label">City<font color="#FF0000">*</font></label>
																	<select class="form-control validate[required]" name="city" id="city">
																		<option value="">- Select-</option>
																		<option value="Ayutthaya">Ayutthaya</option>
																		<option value="Bangkok">Bangkok</option>
																		<option value="Cha am">Cha Am</option>
																		<option value="Chiang mai">Chiang Mai</option>
																		<option value="Chiang rai">Chiang Rai</option>
																		<option value="Hat yai">Hat Yai</option>
																		<option value="Hua hin">Hua Hin</option>
																		<option value="Kanchanaburi">Kanchanaburi</option>
																		<option value="Khao lak">Khao Lak</option>
																		<option value="Khao yai">Khao Yai</option>
																		<option value="Koh chang">Koh Chang</option>
																		<option value="Koh lanta">Koh Lanta</option>
																		<option value="Koh phangan">Koh Phangan</option>
																		<option value="Koh samui">Koh Samui</option>
																		<option value="Krabi">Krabi</option>
																		<option value="Pattaya">Pattaya</option>
																		<option value="Phang nga">Phang Nga</option>
																		<option value="Phi phi island">Phi Phi Island</option>
																		<option value="Phuket">Phuket</option>
																		<option value="Pranburi">Pranburi</option>
																		<option value="Rayong">Rayong</option>
																		<option value="Sa kaeo">Sa Kaeo</option>
																		<option value="Samui island">Samui Island</option>
																		<option value="Trat">Trat</option>
																	</select>
																</div>
																<div class="form-group col-md-3">
																	<label for="inputName" class="control-label">For No Of Nights<font color="#FF0000">*</font></label>
																	<input type="text" class="form-control validate[required]"  value="" name="number_of_night" id="number_of_night" placeholder="Number Of Nights" tabindex = "4" />
																</div>
																<div class="form-group col-md-3">
																	<label for="inputName" class="control-label">Hotel Ratings<font color="#FF0000">*</font></label>
																	<br/>
																	<input type="checkbox" value="" name="hotel_ratings" id="hotel_ratings" />&nbsp;1&nbsp;&nbsp;<input type="checkbox" value="" name="hotel_ratings" id="hotel_ratings" />&nbsp;2&nbsp;&nbsp;<input type="checkbox"  value="" name="hotel_ratings" id="hotel_ratings" />&nbsp;3&nbsp;&nbsp;<input type="checkbox" value="" name="hotel_ratings" id="hotel_ratings" />&nbsp;4&nbsp;&nbsp;<input type="checkbox" value="" name="hotel_ratings" id="hotel_ratings" />&nbsp;5
																</div>
															</div>
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