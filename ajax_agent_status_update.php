<?php
	require_once('loader.inc');
	tools::module_validation_check(@$_SESSION['AGENT_SESSION_DATA']['id'], DOMAIN_NAME_PATH.'');
	$data['status']="error";
	$data['msg']="Some data missing.";
	if(isset($_POST['agent_id']) && $_POST['agent_id']!=""):
		$sub_agent_data = tools::find("first", TM_AGENT, "*", "WHERE id=:id AND parent_id=:parent_id", array(':id'=>$_POST['agent_id'], ':parent_id'=>$_SESSION['AGENT_SESSION_DATA']['id']));
		if(!empty($sub_agent_data)):
			$_POST['id']=$sub_agent_data['id'];
			if($sub_agent_data['status']==1):
				$_POST['status']=0;
			else:
				$_POST['status']=1;
			endif;
			if($save_agent_data = tools::module_form_submission("", TM_AGENT)):
				$data['status'] = 'success';
				$data['msg'] = 'Agent has been updated successfully.';
				$data['results']['status'] = $_POST['status'];
			else:
				$data['status'] = 'error';
				$data['msg'] = 'We are having some probem. Please try again later.';
			endif;
		else:
			$data['status'] = 'error';
			$data['msg']="Invalid sub agent.";
		endif;
	endif;
	echo json_encode($data);
?>