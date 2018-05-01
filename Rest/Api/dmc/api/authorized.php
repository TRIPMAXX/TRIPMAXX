<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	include_once('../init.php');	
	use \Firebase\JWT\JWT;
	
	$return_data['status']="error";
	$return_data['msg']="Autentication failed.";
	if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']) && $_SERVER['PHP_AUTH_USER']==API_USERNAME && $_SERVER['PHP_AUTH_PW']==API_PASSWORD)
	{
		$tokenId    = base64_encode(mcrypt_create_iv(32));
		$issuedAt   = time();
		$notBefore  = $issuedAt;             //Adding 0 seconds
		$expire     = $notBefore + TOKEN_TIMEOUT;            // Adding 60 seconds
		$serverName = DOMAIN_NAME_PATH.REST_API_PATH.DMC_API_PATH;
		$file_name=$issuedAt."_".rand().".txt";
		$data = [
			'iat'  => $issuedAt,         // Issued at: time when the token was generated
			'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
			'iss'  => $serverName,       // Issuer
			'nbf'  => $notBefore,        // Not before
			'exp'  => $expire,
			'data'=>[
				'file_name'=>$file_name
			]
		];
		$secretKey = base64_decode(TOKEN_SECRET_KEY);
		$jwt = JWT::encode(
			$data,      //Data to be encoded in the JWT
			$secretKey, // The signing key
			'HS512'     // Algorithm used to sign the token, 
		);
		file_put_contents($file_name, $tokenId);
		$data_array['token'] = $jwt;
		$data_array['token_timeout'] = TOKEN_TIMEOUT;
		$data_array['token_generation_time'] = time();
		$return_data['status']="success";
		$return_data['msg']="Data received successfully.";
		$return_data['results']=$data_array;
	}
	echo json_encode($return_data);	
?>