<?php
require_once('loader.inc');
$cms_pages=array();
$autentication_data_dmc=json_decode(tools::apiauthentication(DOMAIN_NAME_PATH.REST_API_PATH.DMC_API_PATH."authorized.php"));
if(isset($autentication_data_dmc->status)):
	if($autentication_data_dmc->status=="success"):
		$post_data_dmc['token']=array(
			"token"=>$autentication_data_dmc->results->token,
			"token_timeout"=>$autentication_data_dmc->results->token_timeout,
			"token_generation_time"=>$autentication_data_dmc->results->token_generation_time
		);
		$post_data_dmc['data']['page_slug']="career";
		$post_dmc_data_str=json_encode($post_data_dmc);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json"));
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_URL, DOMAIN_NAME_PATH.REST_API_PATH.DMC_API_PATH."cms/read.php");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_dmc_data_str);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$return_data_dmc = curl_exec($ch);
		curl_close($ch);
		$return_dmc_data_arr=json_decode($return_data_dmc, true);
		if(!isset($return_dmc_data_arr['status'])):
			//$data['status'] = 'error';
			//$data['msg']="Some error has been occure during execution.";
		elseif($return_dmc_data_arr['status']=="success"):
			//$data['status'] = 'success';
			//$data['msg']="Data received successfully";
			$cms_pages=$return_dmc_data_arr['results'];
		else:
			//$data['status'] = 'error';
			//$data['msg'] = $return_dmc_data_arr['msg'];
		endif;
	endif;
else:
	//$data['status'] = 'error';
	//$data['msg'] = $autentication_data_dmc->msg;
endif;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="Keywords" content="<?php echo $cms_pages['page_meta_keyword'] ;?>">
  <meta name="Description" content="<?php echo $cms_pages['page_meta_description'] ;?>">
  <title><?php echo DEFAULT_PAGE_TITLE." ".$cms_pages['page_meta_title'] ;?></title>	
<?php require_once('meta.php');?>
	<!-- JAVASCRIPT CODE -->
	<script type="text/javascript">
	<!--
	$(function(){
		$("#agent_login").validationEngine();
	});
	</script>
	<!-- JAVASCRIPT CODE -->
</head>
<body class="index-page">
	<!-- TOP HEADER -->
	<?php require_once('header.php');?>		
	<!-- TOP HEADER -->
	<?php require_once('cms_pages.php');?>	
	<!-- FOOTER -->
	<?php require_once('footer.php');?>
	<!-- FOOTER -->
</body>
</html>