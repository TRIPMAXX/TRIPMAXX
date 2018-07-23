<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['SESSION_DATA']['id'], DOMAIN_NAME_PATH_ADMIN.'login');	
	$data['status']="error";
	$data['msg']="Some data missing.";
	if(isset($_SESSION) && !empty($_SESSION) && isset($_SESSION['step_1']) && !empty($_SESSION['step_1'])):
		//print_r($_SESSION);
		$number_of_person=$number_of_adult=$number_of_child=0;
		foreach($_SESSION['step_1']['adult'] as $adult_key=>$adult_val):
			if($adult_val!="")
				$number_of_adult=$number_of_adult+$adult_val;
		endforeach;
		foreach($_SESSION['step_1']['child'] as $child_key=>$child_val):
			if($child_val!="")
				$number_of_child=$number_of_child+$child_val;
		endforeach;
		$number_of_person=$number_of_adult+$number_of_child;
		$country_id_str=implode(",", $_SESSION['step_1']['country']);
		$city_id_str=implode(",", $_SESSION['step_1']['city']);
		$city_list = tools::find("first", TM_CITIES, "GROUP_CONCAT(DISTINCT(name) SEPARATOR ', ') as destination_name", "WHERE id IN (".$city_id_str.") ORDER BY name ASC", array());
		ob_start();
?>
		<div class="box-body">
			<table aria-describedby="example1_info" id="example" class="table table-bordered table-striped dataTable">
				<thead>
					<tr role="row">
						<th style = "text-align:center;">Pax</th>
						<th style = "text-align:center;">Quote Date</th>
						<th style = "text-align:center;">Destination</th>
						<th style = "text-align:center;">Booking Date</th>
					</tr>
				</thead>
				<tbody aria-relevant="all" aria-live="polite" role="alert">
					<tr class="odd">
						<td style = "text-align:center;"><?php echo $number_of_person;?></td>
						<td style = "text-align:center;"><?php echo tools::module_date_format(date("Y-m-d"));?></td>
						<td style = "text-align:center;"><?php echo $city_list['destination_name'];?></td>
						<td style = "text-align:center;"><?php echo tools::module_date_format($_SESSION['step_1']['checkin'], "d/m/Y");?></td>
					</tr>
				</tbody>
			</table>
		</div>
<?php
		$first_section_html=ob_get_clean();
		$secend_section_html='';
		$hotel_price=0.00;
		$hotel_currency="INR";
		if(isset($_SESSION['step_2']) && !empty($_SESSION['step_2'])):
			$autentication_data=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."authorized.php"));
			if(isset($autentication_data->status)):
				if($autentication_data->status=="success"):
					$post_data['token']=array(
						"token"=>$autentication_data->results->token,
						"token_timeout"=>$autentication_data->results->token_timeout,
						"token_generation_time"=>$autentication_data->results->token_generation_time
					);
					$post_data['data']['step_1']=$_SESSION['step_1'];
					$post_data['data']['step_2']=$_SESSION['step_2'];
					$post_data_str=json_encode($post_data);
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_HEADER, false);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
					curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.HOTEL_API_PATH."hotel/selected-hotel-details.php");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					$return_data = curl_exec($ch);
					curl_close($ch);
					$return_data_arr=json_decode($return_data, true);
					$hotel_data=array();
					if(!isset($return_data_arr['status'])):
						//$data['status'] = 'error';
						//$data['msg']="Some error has been occure during execution.";
					elseif($return_data_arr['status']=="success"):
						//$data['status'] = 'success';
						//$data['msg']="Data received successfully";
						$secend_section_html=$return_data_arr['room_details'];
						$hotel_price=$return_data_arr['hotel_price'];
						$hotel_currency=$return_data_arr['default_currency'];
						//$data['city_tab_html']=$return_data_arr['city_tab_html'];
						//$data['heading_count_rcd']=$return_data_arr['heading_count_rcd'];
					else:
						//$data['status'] = 'error';
						//$data['msg'] = $return_data_arr['msg'];
					endif;
				endif;
			else:
				//$data['status'] = 'error';
				//$data['msg'] = $autentication_data->msg;
			endif;
		endif;
		$third_section_html='';
		$tour_price=0.00;
		$tour_currency="INR";
		if(isset($_SESSION['step_3']) && !empty($_SESSION['step_3'])):
			$autentication_data_tour=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.TOUR_API_PATH."authorized.php"));
			if(isset($autentication_data_tour->status)):
				if($autentication_data_tour->status=="success"):
					$post_data_tour['token']=array(
						"token"=>$autentication_data_tour->results->token,
						"token_timeout"=>$autentication_data_tour->results->token_timeout,
						"token_generation_time"=>$autentication_data_tour->results->token_generation_time
					);
					$post_data_tour['data']['step_1']=$_SESSION['step_1'];
					$post_data_tour['data']['step_3']=$_SESSION['step_3'];
					$post_data_tour['data']['step_3_all']=$_SESSION['step_3_all'];
					$post_data_str_tour=json_encode($post_data_tour);
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_HEADER, false);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
					curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.TOUR_API_PATH."tour/selected-tour-details.php");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_tour);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					$return_data_tour = curl_exec($ch);
					curl_close($ch);
					$return_data_arr_tour=json_decode($return_data_tour, true);
					//print_r($return_data_tour);
					$tour_data=array();
					if(!isset($return_data_arr_tour['status'])):
						//$data['status'] = 'error';
						//$data['msg']="Some error has been occure during execution.";
					elseif($return_data_arr_tour['status']=="success"):
						//$data['status'] = 'success';
						//$data['msg']="Data received successfully";
						$third_section_html=$return_data_arr_tour['tour_details'];
						$tour_price=$return_data_arr_tour['tour_price'];
						$tour_currency=$return_data_arr_tour['default_currency'];
					else:
						//$data['status'] = 'error';
						//$data['msg'] = $return_data_arr_tour['msg'];
					endif;
				endif;
			else:
				//$data['status'] = 'error';
				//$data['msg'] = $autentication_data->msg;
			endif;
		endif;
		$fourth_section_html='';
		$transfer_price=0.00;
		$transfer_currency="INR";
		if(isset($_SESSION['step_4']) && !empty($_SESSION['step_4'])):
			$autentication_data_transfer=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.TRANSFER_API_PATH."authorized.php"));
			if(isset($autentication_data_transfer->status)):
				if($autentication_data_transfer->status=="success"):
					$post_data_transfer['token']=array(
						"token"=>$autentication_data_transfer->results->token,
						"token_timeout"=>$autentication_data_transfer->results->token_timeout,
						"token_generation_time"=>$autentication_data_transfer->results->token_generation_time
					);
					$post_data_transfer['data']['step_1']=$_SESSION['step_1'];
					$post_data_transfer['data']['step_4']=$_SESSION['step_4'];
					$post_data_transfer['data']['step_4_all']=$_SESSION['step_4_all'];
					$post_data_str_transfer=json_encode($post_data_transfer);
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_HEADER, false);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
					curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.TRANSFER_API_PATH."transfer/selected-transfer-details.php");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_str_transfer);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					$return_data_transfer = curl_exec($ch);
					curl_close($ch);
					$return_data_arr_transfer=json_decode($return_data_transfer, true);
					$transfer_data=array();
					if(!isset($return_data_arr_transfer['status'])):
						//$data['status'] = 'error';
						//$data['msg']="Some error has been occure during execution.";
					elseif($return_data_arr_transfer['status']=="success"):
						//$data['status'] = 'success';
						//$data['msg']="Data received successfully";
						$fourth_section_html=$return_data_arr_transfer['transfer_details'];
						$transfer_price=$return_data_arr_transfer['transfer_price'];
						$transfer_currency=$return_data_arr_transfer['default_currency'];
					else:
						//$data['status'] = 'error';
						//$data['msg'] = $return_data_arr_transfer['msg'];
					endif;
				else:
					//$data['status'] = 'error';
					//$data['msg'] = $autentication_data->msg;
				endif;
			else:
				//$data['status'] = 'error';
				//$data['msg'] = $autentication_data->msg;
			endif;
		endif;
		$show_pay_dropdown=true;
		$show_pay_days=0;
		if(isset($_SESSION['step_1']['booking_type']) && $_SESSION['step_1']['booking_type']=="agent" && isset($_SESSION['step_1']['agent_name']) && $_SESSION['step_1']['agent_name']!=""):
			$autentication_agent_data=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."authorized.php"));
			if(isset($autentication_agent_data->status)):
				if($autentication_agent_data->status=="success"):
					$post_agent_data['token']=array(
						"token"=>$autentication_agent_data->results->token,
						"token_timeout"=>$autentication_agent_data->results->token_timeout,
						"token_generation_time"=>$autentication_agent_data->results->token_generation_time
					);
					$post_agent_data['data']['agent_id']=$_SESSION['step_1']['agent_name'];
					$post_agent_data_str=json_encode($post_agent_data);
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_HEADER, false);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
					curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.AGENT_API_PATH."agent/booking-agent.php");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_agent_data_str);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					$return_agent_data = curl_exec($ch);
					curl_close($ch);
					$return_agent_data_arr=json_decode($return_agent_data, true);
					//print_r($return_agent_data);
					$tour_data=array();
					if(!isset($return_agent_data_arr['status'])):
						//$data['status'] = 'error';
						//$data['msg']="Some error has been occure during execution.";
					elseif($return_agent_data_arr['status']=="success"):
						//$data['status'] = 'success';
						//$data['msg']="Data received successfully";
						if($return_agent_data_arr['results']['payment_type']=="cash"):
							$show_pay_dropdown=false;
							$show_pay_days=$return_agent_data_arr['results']['pay_within_days'];
						else:
							//$data['status'] = 'error';
							//$data['msg']="You do not have enough credit balance";
						endif;
					else:
						//$data['status'] = 'error';
						//$data['msg'] = $return_data_arr['msg'];
					endif;
				endif;
			else:
				//$data['status'] = 'error';
				//$data['msg'] = $autentication_data->msg;
			endif;
		endif;
		$fifth_section_html='';
		$fifth_section_html.='<div class="box-body">';
			$fifth_section_html.='<table aria-describedby="example1_info" id="example" class="table table-bordered table-striped dataTable">';
				$fifth_section_html.='<thead>';
					$fifth_section_html.='<tr role="row">';
						$fifth_section_html.='<th style = "text-align:center;" colspan = "3">Quotation</th>';
					$fifth_section_html.='</tr>';
				$fifth_section_html.='</thead>';
				$fifth_section_html.='<tbody aria-relevant="all" aria-live="polite" role="alert">';
					$fifth_section_html.='<tr class="odd">';
						$fifth_section_html.='<td style = "text-align:left;font-weight:bold;">Total Cost for Hotel Accommodation</td>';
						$fifth_section_html.='<td style = "text-align:center;font-weight:bold;" colspan = "2">'.$hotel_currency.number_format($hotel_price, 2,".",",").'</td>';
					$fifth_section_html.='</tr>';
					if($tour_price!=0.00 || $transfer_price!=0.00):
						$fifth_section_html.='<tr class="odd">';
							$fifth_section_html.='<td style = "text-align:left;font-weight:bold;">';
								$fifth_section_html.='Add-on : Cost for other components Tours & Transfer';
							$fifth_section_html.='</td>';
							$fifth_section_html.='<td style = "text-align:center;font-weight:bold;">';
								//$fifth_section_html.='PER ADULT';
								$fifth_section_html.='<br/>';
								$fifth_section_html.=''.$tour_currency.number_format($tour_price+$transfer_price, 2,".",",").'';
							$fifth_section_html.='</td>';
							$fifth_section_html.='<td style = "text-align:center;font-weight:bold;">';
								//$fifth_section_html.='PER CHILD';
								$fifth_section_html.='<br/>';
								//$fifth_section_html.=''.$tour_currency.number_format($tour_price+$transfer_price, 2,".",",").'';
								$fifth_section_html.=''.$tour_currency.number_format(0, 2,".",",").'';
							$fifth_section_html.='</td>';
						$fifth_section_html.='</tr>';
					endif;
					$fifth_section_html.='<tr class="odd">';
						$fifth_section_html.='<td style = "text-align:left;font-weight:bold;">';
							$fifth_section_html.='No of Guests';
						$fifth_section_html.='</td>';
						$fifth_section_html.='<td style = "text-align:center;font-weight:bold;">';
							$fifth_section_html.='ADULT';
							$fifth_section_html.='<br/>';
							$fifth_section_html.=''.$number_of_adult;
						$fifth_section_html.='</td>';
						$fifth_section_html.='<td style = "text-align:center;font-weight:bold;">';
							$fifth_section_html.='CHILD';
							$fifth_section_html.='<br/>';
							$fifth_section_html.=''.$number_of_child;
						$fifth_section_html.='</td>';
					$fifth_section_html.='</tr>';
					$fifth_section_html.='<tr class="odd">';
						$fifth_section_html.='<td style = "text-align:left;font-weight:bold;">Total Quantity</td>';
						$fifth_section_html.='<td style = "text-align:center;font-weight:bold;color:red;" colspan = "2">'.$hotel_currency.number_format($hotel_price+(($tour_price+$transfer_price)), 2,".",",").'</td>';
					$fifth_section_html.='</tr>';
				$fifth_section_html.='</tbody>';
			$fifth_section_html.='</table>';
		$fifth_section_html.='</div>';
		$data['booking_html']=$first_section_html.$secend_section_html.$fourth_section_html.$third_section_html.$fifth_section_html;
		$data['total_price']=$hotel_price+(($tour_price+$transfer_price));
		$data['show_pay_dropdown']=$show_pay_dropdown;
		$data['show_pay_days']=$show_pay_days;
		$data['status']="success";
		$data['msg']="Saved to session.";
	endif;
	echo json_encode($data);
?>