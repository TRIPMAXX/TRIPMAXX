<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../../init.php');
	$return_data['status']="error";
	$return_data['msg']="Token is not verified.";
	$server_data=json_decode(file_get_contents("php://input"), true);
	if(isset($server_data['token']) && isset($server_data['token']['token']) && isset($server_data['token']['token_timeout']) && isset($server_data['token']['token_generation_time']) && tools::jwtTokenDecode($server_data['token']['token']) && ($server_data['token']['token_generation_time']+$server_data['token']['token_timeout']) > time()):
		$agent_id=$server_data['data']['id'];
		$find_agent = tools::find("first", TM_AGENT, '*', "WHERE id=:id ", array(":id"=>$agent_id));
		if(!empty($find_agent)):
			$_POST=$server_data['data'];
			$_POST['id']=$find_agent['id'];
			if(isset($_POST['payment_type']) && $_POST['payment_type']=="cash"):
				$_POST['credit_balance']="";
			endif;
			$check_flag=true;
			if(isset($server_data['data']['update_type']) && $server_data['data']['update_type']=="status"):
				if($find_agent['status']==1):
					$_POST['status']=0;
				else:
					$_POST['status']=1;
				endif;
			else:
				if(tools::module_data_exists_check("company_name = '".tools::stripcleantohtml($_POST['company_name'])."' AND id <> ".$find_agent['id']."", '', TM_AGENT)) {
					$check_flag=false;
					$return_data['status']="error";
					$return_data['msg'] = 'This company name already exists.';		
				}elseif(tools::module_data_exists_check("email_address = '".tools::stripcleantohtml($_POST['email_address'])."' AND id <> ".$find_agent['id']."", '', TM_AGENT)) {
					$check_flag=false;
					$return_data['status']="error";
					$return_data['msg'] = 'This email address already exists.';		
				}elseif(tools::module_data_exists_check("code = '".tools::stripcleantohtml($_POST['code'])."' AND id <> ".$find_agent['id']."", '', TM_AGENT)) {
					$check_flag=false;
					$return_data['status']="error";
					$return_data['msg'] = 'This code already exists.';		
				}elseif(tools::module_data_exists_check("username = '".tools::stripcleantohtml($_POST['username'])."' AND id <> ".$find_agent['id']."", '', TM_AGENT)) {
					$check_flag=false;
					$return_data['status']="error";
					$return_data['msg'] = 'This username already exists.';		
				}
			endif;
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
				if($check_flag==true):
					$_POST['image']=$find_agent['image'];
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
						if($find_agent['image']!="" && file_exists(AGENT_IMAGES.$find_agent['image']))
							unlink(AGENT_IMAGES.$find_agent['image']);
						$_POST['image']=$file_name;
					}
					if($save_agent_data = tools::module_form_submission("", TM_AGENT)):
						$find_updated_agent = tools::find("first", TM_AGENT, '*', "WHERE id=:id", array(":id"=>$find_agent['id']));
						$return_data['status'] = 'success';
						$return_data['msg'] = 'Agent has been updated successfully.';
						if($find_updated_agent['type']=="G")
							$return_data['msg'] = 'GSA has been updated successfully.';
						$return_data['results'] = $find_updated_agent;
					else:
						$return_data['status'] = 'error';
						$return_data['msg'] = 'We are having some probem. Please try again later.';
					endif;
				endif;
			else:
				$return_data['status']="error";
				$return_data['msg'] = 'Invalid gsa id.';
			endif;
		else:
			$return_data['status'] = 'error';
			$return_data['msg'] = 'Invalid agent id.';
		endif;
	endif;
	echo json_encode($return_data);	
?>