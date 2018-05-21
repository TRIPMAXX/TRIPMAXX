<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		$_POST=$server_data['data'];
		if(isset($_POST['payment_type']) && $_POST['payment_type']=="cash"):
			$_POST['credit_balance']="";
		endif;
		$uploaded_file_json_data="";
		if(tools::module_data_exists_check("company_name = '".tools::stripcleantohtml($_POST['company_name'])."'", '', TM_AGENT)) {
			$return_data['status']="error";
			$return_data['msg'] = 'This company name already exists.';		
		}elseif(tools::module_data_exists_check("email_address = '".tools::stripcleantohtml($_POST['email_address'])."'", '', TM_AGENT)) {
			$return_data['status']="error";
			$return_data['msg'] = 'This email address already exists.';		
		}elseif(tools::module_data_exists_check("code = '".tools::stripcleantohtml($_POST['code'])."'", '', TM_AGENT)) {
			$return_data['status']="error";
			$return_data['msg'] = 'This code already exists.';		
		}elseif(tools::module_data_exists_check("username = '".tools::stripcleantohtml($_POST['username'])."'", '', TM_AGENT)) {
			$return_data['status']="error";
			$return_data['msg'] = 'This username already exists.';		
		} else {
			$gsa_check_flag=true;
			if(isset($_POST['parent_id']) && $_POST['parent_id']!=""):
				$find_gse = tools::find("first", TM_AGENT, '*', "WHERE id=:id AND type=:type", array(":id"=>$_POST['parent_id'], ":type"=>"G"));
				if(!empty($find_gse)):
					//do nothing
					$gsa_check_flag=true;
				else:
					$gsa_check_flag=false;
				endif;
			endif;
			if($gsa_check_flag==true):
				$_POST['image']="";
				if(isset($_POST['uploaded_files']) && !empty($_POST['uploaded_files']))
				{
					$file_val=$_POST['uploaded_files'];
					$random_number = tools::create_password(5);
					$extension = pathinfo($file_val['postname'], PATHINFO_EXTENSION);
					$file_name = str_replace(" ", '' , $random_number."_".$file_val['postname']);
					$img = str_replace('data:image/'.$extension.';base64,', '', $file_val['name']);
					$img = str_replace(' ', '+', $img);
					$data_img_str = base64_decode($img);
					file_put_contents(AGENT_IMAGES.$file_name, $data_img_str);
					$_POST['image']=$file_name;
				}
				if($save_agent = tools::module_form_submission($uploaded_file_json_data, TM_AGENT)) {
					$credit_balance=$_POST['credit_balance'];
					unset($_POST);
					$_POST['agent_id']=$save_agent;
					$_POST['amount']=$credit_balance;
					$_POST['note']="Default Credit";
					$save_agent_accounting = tools::module_form_submission("", TM_AGENT_ACCOUNTING);
					unset($_POST);
					$_POST['transaction_id']=tools::generate_transaction_id("TM-".$save_agent_accounting);
					$_POST['id']=$save_agent_accounting;
					$save_agent_accounting = tools::module_form_submission("", TM_AGENT_ACCOUNTING);
					$return_data['status']="success";
					$return_data['msg'] = 'Agent has been created successfully.';
					if(isset($_POST['type']) && $_POST['type']=="G")
						$return_data['msg'] = 'GSA has been created successfully.';
					$return_data['results'] = $save_agent;
				} else {
					$return_data['status']="error";
					$return_data['msg'] = 'We are having some probem. Please try again later.';
				};
			else:
				$return_data['status']="error";
				$return_data['msg'] = 'Invalid gsa id.';
			endif;
		};
	endif;
	echo json_encode($return_data);	
?>