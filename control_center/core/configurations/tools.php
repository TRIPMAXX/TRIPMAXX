<?php
/*
=========================================================================================================================
COPYRIGHT: NEO CODERZ TECHNOLOGIES
PRODUCT NAME: TRAVELMAXX
PAGE FUNCTIONALITY: THIS IS THE MICROSERVICE FOR DMC FEATURE.
=========================================================================================================================
*/

class tools { 
	/*
	function find()

	* fetch record from database
	* @param string type: Can be all / first
	* @param string table: Name of the table
	* @param string value: Table fields values that needs to be fetched (filed1, field2)
	* @param string where_clause: Where conditions based on which data needs to be fetched (WHERE field1=:defined_value1 AND field2=:defined_value2 OR field3=:defined_value3)
	* @param array execute: Consists of actual values of defined variables (array(':defined_value1'=>$_POST['POST_DATA1'], ':defined_value2'=>$_POST['POST_DATA2'],))
	* @returns an array of fetched records.
	* @example1: find('first', 'table_name', 'field1, field2', 'WHERE field1:=defined_value1 AND field2:=defined_value2', array(':defined_value1'=>$_POST['DATA1'], ':defined_value2'=>$_POST['DATA2'])); 
	* @example2: find('all', 'table_name', 'field1, field2', 'WHERE field1:=defined_value1 AND field2:=defined_value2', array(':defined_value1'=>$_POST['DATA1'], ':defined_value2'=>$_POST['DATA2']));
	*/
	public static function find($type, $table, $value='*', $where_clause, $execute) {
		global $db;
		if($where_clause) {
			$sql = "SELECT ".$value." FROM ".$table." ".$where_clause."";
		} else {
			$sql = "SELECT ".$value." FROM ".$table;
		}
		$prepare_sql = $db->prepare($sql);
		foreach($execute As $key=>$value) {
			$execute[$key] = stripslashes($value);
		}
		$prepare_sql->execute($execute);
		if($prepare_sql->errorCode() == 0) {
			if($type == 'first') {
				//fetch single record from database
				$result = $prepare_sql->fetch(PDO::FETCH_ASSOC);
			} else if($type == 'all') {
				//fetch multiple record from database
				$result = $prepare_sql->fetchAll(PDO::FETCH_ASSOC);
			}
			return $result;
		} else {
			$errors = $prepare_sql->errorInfo();
			echo '<pre>';
			print_r($errors[2]);
			echo '</pre>';
			die();
		}
	}

	/*
	function find_custom()

	* fetch record from database
	* @param string string: The entire Sql Statement
	*/
	public static function find_custom($string) {
		global $db;
		$sql = $string;
		$prepare_sql = $db->prepare($sql);
		$execute = array();
		foreach($execute As $key=>$value) {
			$execute[$key] = stripslashes($value);
		}
		$prepare_sql->execute($execute);
		if($prepare_sql->errorCode() == 0) {
			$result = $prepare_sql->fetchAll();
			return $result;
		} else {
			$errors = $prepare_sql->errorInfo();
			echo '<pre>';
			print_r($errors[2]);
			echo '</pre>';
			die();
		}
	}

	/*
	function save()

	* insert record into database
	* @param string table: Name of the table
	* @param string fields: Lists of fields name of the corresponding database table within which data needs to be added (field1, field2)
	* @param string values: Lists of defined values variables that will be used in ececute array reflect corresponding table fields (:defined_value1, :defined_value2)
	* @param array execute: Lists of defined values variables along with the actual values that needs to be added within the database tables (array(':defined_value1'=>$_POST['POST_DATA1'], ':defined_value2'=>$_POST['POST_DATA2']))
	* @ returns last inserted id.
	* @ example: save('table_name', 'fields1, fields2', ':defined_value1, :defined_value2', array(':defined_value1'=>$_POST['POST_DATA1'], ':defined_value2'=>$_POST['POST_DATA2']))
	*/
	public static function save($table, $fields, $values, $execute) {
		global $db;
		$result = false;
		$sql = "INSERT INTO ".$table." (".$fields.") VALUES (".$values.")";
		$prepare_sql = $db->prepare($sql);
		foreach($execute As $key=>$value) {
			$execute[$key] = stripslashes($value);
		}
		$prepare_sql->execute($execute);
		$result = $db->lastInsertId();
		return $result;
	}

	/*
	function update()

	* update record into database
	* @param string table: Name of the table
	* @param string set fields: Database tables fields names that needs to be updated ('fields1=:defined_value1, fields2=:defined_value2')
	* @param string where_clause: Where condition based on which the database table will be updated ('WHERE fields1=:defined_value1 AND WHERE fields2=:defined_value2')
	* @param array execute:  Lists of defined values variables along with the actual values that needs to be updated within the database tables (array(':defined_value1'=>$_POST['POST_DATA1'], ':defined_value2'=>$_POST['POST_DATA2']))
	* @return true or false
	* @ example: update('table_name', 'fields1=:defined_value1, fields2=:defined_value2', 'WHERE fields1=:defined_value1 AND fields2=:defined_value2', array(':defined_value1'=>$_POST['POST_DATA1'], ':defined_value2'=>$_POST['POST_DATA2']))
	*/
	public static function update($table, $set_value, $where_clause, $execute) {
		global $db;
		$sql = "UPDATE ".$table." SET ".$set_value." ".$where_clause."";
		$prepare_sql = $db->prepare($sql);
		foreach($execute As $key=>$value) {
			$execute[$key] = stripslashes($value);
		}
		$prepare_sql->execute($execute);
		if($prepare_sql->errorCode() == 0) {
			return true;
		} else {
			$errors = $prepare_sql->errorInfo();
			echo '<pre>';
			print_r($errors[2]);
			echo '</pre>';
			die();
			return false;
		}
	}

	/*
	function delete()

	* delete record from database
	* @param string table: Name of the table
	* @param string where_clause: Where condition based on which the database table will be updated ('WHERE fields1=:defined_value1 AND WHERE fields2=:defined_value2')
	* @param array execute:  Lists of defined values variables along with the actual values that needs to be updated within the database tables (array(':defined_value1'=>$_POST['POST_DATA1'], ':defined_value2'=>$_POST['POST_DATA2']))
	* @return true or false
	* @ example: delete('table_name', 'WHERE fields1=:defined_value1', array(':defined_value1'=>$_POST['POST_DATA1']))
	*/
	public static function delete($table, $where_clause, $execute) {
		global $db;
		$sql = "DELETE FROM ".$table." ".$where_clause."";
		$prepare_sql = $db->prepare($sql);
		$prepare_sql->execute($execute);
		if($prepare_sql->errorCode() == 0) {
			return true;
		} else {
			$errors = $prepare_sql->errorInfo();
			echo '<pre>';
			print_r($errors[2]);
			echo '</pre>';
			die();
		}
	}

	/*
	function Send_HTML_Mail()

	* send HTML or text email without SMTP validation.
	* @param string mail_To: Email address which which email needs to be sent.
	* @param string mail_From: Email address from which email needs to be sent.
	* @param string mail_CC: Enter email address that you wish to send a cc copy (optional).
	* @param string mail_subject: Email subject line.
	* @param string mail_Body: Email content either in HTML format or simple text format.
	* @returns true or false.
	*/
	public static function Send_HTML_Mail($mail_To, $mail_From, $mail_CC, $mail_subject, $mail_Body) {
		$mail_From = "TRIPMAXX <noreply@neocoderztechnologies.com>";
		$mail_Headers  = "MIME-Version: 1.0\r\n";
		$mail_Headers .= "Content-type: text/html; charset=utf-8\r\n";		
		$mail_Headers .= "From: ${mail_From}\r\n";

		if($mail_CC != '') {
			$mail_Headers .= "Cc: ${mail_CC}\r\n";
		}

		if(mail($mail_To, $mail_subject, $mail_Body, $mail_Headers)) {			
			return true;
		}else{        	
			return false;
		}
	}

	/*
	function Send_SMTP_Mail()

	* send HTML or text email without SMTP validation.
	* @param string mail_To: Email address which which email needs to be sent.
	* @param string mail_From: Email address from which email needs to be sent.
	* @param string mail_CC: Enter email address that you wish to send a cc copy (optional).
	* @param string mail_subject: Email subject line.
	* @param string mail_Body: Email content either in HTML format or simple text format.
	* @returns true or false.
	*/
	public static function Send_SMTP_Mail($mail_To, $mail_From, $mail_CC, $mail_subject, $mail_Body) {
		include_once ("core/configurations/class.phpmailer.php");
		include_once ("core/configurations/class.smtp.php");
		$mail = new PHPMailer();
		$mail->IsSMTP(); 
		$mail->Host = "localhost";
		$mail->SMTPAuth = true;
		$mail->Username = "noreply@jenapburger.com";
		$mail->Password = "123456";
		$mail->From = 'noreply@jenapburger.com';
		$mail->FromName = "Jenap Burger";
		$mail->AddAddress($mail_To, ""); 
		$mail->IsHTML(true);
		$mail->Subject = $mail_subject;
		$mail->Body = $mail_Body;
		$mail->Send();
	}

	/*
	function create_password()

	* create random number with maximum length of 10.
	* @param length: Can be any interger value starting from 1 to 10.
	* @returns randon generated string with specified length.
	*/
	public static function create_password($length=10) {
	   $phrase = "";
	   $chars = array (
					  "1","2","3","4","5","6","7","8","9","0",
					  "a","A","b","B","c","C","d","D","e","E","f","F","g","G","h","H","i","I","j","J",
					  "k","K","l","L","m","M","n","N","o","O","p","P","q","Q","r","R","s","S","t","T",
					  "u","U","v","V","w","W","x","X","y","Y","z","Z"
					  );

	   $count = count ($chars) - 1;
	   srand ((double) microtime() * 1234567);
	   for ($i = 0; $i < $length; $i++)
		  $phrase .= $chars[rand (0, $count)];
	   return $phrase;
	}

	/*
	function cleantohtml()

	* Restores the added slashes (ie.: " I\'m John " for security in output, and escapes them in htmlentities(ie.:  &quot; etc.)
	  It preserves any <html> tags in that they are encoded aswell (like &lt;html&gt;)
	  As an extra security, if people would try to inject tags that would become tags after stripping away bad characters
	  we do still strip tags but only after htmlentities, so any genuine code examples will stay
	* @param form: form field value.
	* @Use: For input fields that may contain html, like a textarea
	* @returns formatted form filed value.
	*/
	public static function cleantohtml($s) {
		return strip_tags(htmlentities(trim(stripslashes($s))), ENT_NOQUOTES);
	}

	/*
	function stripcleantohtml()

	* Restores the added slashes (ie.: " I\'m John " for security in output, and escapes them in htmlentities(ie.:  &quot; etc.)
	  It preserves any <html> tags in that they are encoded aswell (like &lt;html&gt;)
	  As an extra security, if people would try to inject tags that would become tags after stripping away bad characters
	  we do still strip tags but only after htmlentities, so any genuine code examples will stay
	* @param form: form field value.
	* @Use: For input fields that may contain html, like a textarea
	* @returns formatted form filed value.
	*/
	public static function stripcleantohtml($s) {
		return htmlentities(trim(strip_tags(stripslashes($s))), ENT_NOQUOTES, "UTF-8");
	}

	/*
	function file_download()

	* provide option to download any files.
	* @param download_path: Path of the files that needs to be downloaded.
	* @returns downloaded file.
	*/
	public static function file_download($download_path) {
		$path = $download_path;
		$file = $path;
		$filename = basename($file);
		$file_extension = strtolower(substr(strrchr($filename,"."),1));
		$download_size = filesize($file);
		switch( $file_extension ) {
		  case "pdf": $ctype="application/pdf"; break;
		  case "csv": $ctype="application/csv"; break;
		  case "exe": $ctype="application/octet-stream"; break;
		  case "zip": $ctype="application/zip"; break;
		  case "doc": $ctype="application/msword"; break;
		  case "docx": $ctype="application/msword"; break;
		  case "xls": $ctype="application/vnd.ms-excel"; break;
		  case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
		  case "gif": $ctype="image/gif"; break;
		  case "png": $ctype="image/png"; break;
		  case "jpeg":
		  case "jpg": $ctype="image/jpg"; break;
		  default: $ctype="application/force-download";
		}
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);
		//Use the switch-generated Content-Type
		header("Content-Type: $ctype");
		header('Content-Transfer-Encoding: Binary');
		//Force the download
		header("Accept-Ranges: bytes");
		header("Content-Length: $download_size");
		header('Content-Disposition: attachment; filename="'.$filename.'";');
		readfile($file);
	}

	/*
	function read_all_files()

	* provide option to read all files and dirctory name available within a specified path.
	* @param root: Path of the root directory.
	* @returns array of entire directory structure.
	*/
	public static function read_all_files($root = '.') {
		$files  = array();
		$directories  = array();
		$last_letter  = $root[strlen($root)-1];
		$root  = ($last_letter == '\\' || $last_letter == '/') ? $root : $root.DIRECTORY_SEPARATOR;

		$directories[]  = $root;

		while (sizeof($directories)) {
			$dir  = array_pop($directories);
			if ($handle = opendir($dir)) {
				while (false !== ($file = readdir($handle))) {
					if ($file == '.' || $file == '..') {
						continue;
					}
					$file  = $dir.$file;
					if (is_dir($file)) {
						$directory_path = $file.DIRECTORY_SEPARATOR;
						array_push($directories, $directory_path);
						$files[]  = $directory_path;
					} elseif (is_file($file)) {
						$files[]  = $file;
					}
				}
				closedir($handle);
			}
		}
		return $files;
	}

	/*
	function xcopy()

	* Copy files and directories from source to destination
	* @param source: Path of the source directory / files.
	* @param dest: Path of the destination directory / files.
	* @param permissions: access permission of copied directory.
	* @returns array of entire directory structure.
	*/
	public static function xcopy($source, $dest, $permissions = 0777) {
		// Check for symlinks
		if (is_link($source)) {
			return symlink(readlink($source), $dest);
		}
		// Simple copy for a file
		if (is_file($source)) {
			return copy($source, $dest);
		}
		// Make destination directory
		if (!is_dir($dest)) {
			mkdir($dest, $permissions);
		}
		// Loop through the folder
		$dir = dir($source);
		while (false !== $entry = $dir->read()) {
			// Skip pointers
			if ($entry == '.' || $entry == '..') {
				continue;
			}
			// Deep copy directories
			xcopy("$source/$entry", "$dest/$entry", $permissions);
		}
		// Clean up
		$dir->close();
		return true;
	}

	/*
	function scan_dir()

	* Scan directory provided in $path and provide the lsits of files availabel within that directory
	* @param $path: Path of the source directory / files.
	* @returns array of total_files, file_size and file_names.
	*/
	public static function scan_dir($path) {
		$ite=new RecursiveDirectoryIterator($path);

		$bytestotal=0;
		$nbfiles=0;
		$files = array();
		foreach (new RecursiveIteratorIterator($ite) as $filename=>$cur) {
			if($filename == '.' || $filename == '..' || is_dir($filename)) {
				//DO NOTHING
			} else {
				$filesize=$cur->getSize();
				$bytestotal+=$filesize;
				$nbfiles++;
				$files[] = $filename;
			}
		}
		$bytestotal=number_format($bytestotal);
		return array('total_files'=>$nbfiles,'total_size'=>$bytestotal,'files'=>$files);
	}

	/*
	function weeks_in_month()

	* This function used to get the number of weeks in a month for a specific year and month
	* @param $year: current year
	* @param $month: current month
	* @start_day_of_week: set it to 1
	* @returns count number of weeks.
	*/
	public static function weeks_in_month($year, $month, $start_day_of_week) {
		// Total number of days in the given month.
		$num_of_days = date("t", mktime(0,0,0,$month,1,$year));
		// Count the number of times it hits $start_day_of_week.
		$num_of_weeks = 0;
		for($i=1; $i<=$num_of_days; $i++) {
			$day_of_week = date('w', mktime(0,0,0,$month,$i,$year));
			if($day_of_week==$start_day_of_week)
			$num_of_weeks++;
		}
		return $num_of_weeks;
	}

	/*
	function createDateRangeArray($strDateFrom, $strDateTo)

	* This function used to get the entire date range for a specified start date and end date
	* @param $strDateFrom: Start date from
	* @param $strDateTo: End date to
	* @returns count lists of days in an array format.
	*/
	public static function createDateRangeArray($strDateFrom, $strDateTo) {
		$aryRange=array();

		$iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
		$iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

		if ($iDateTo>=$iDateFrom) {
			array_push($aryRange,date('Y-m-d-D',$iDateFrom)); // first entry
			while ($iDateFrom<$iDateTo){
				$iDateFrom+=86400; // add 24 hours
				array_push($aryRange,date('Y-m-d-D',$iDateFrom));
			}
		}
		return $aryRange;
	}

	/*
	function current_page()

	* This function used to get the name of the current page or last seo page name that you are current viewing
	* @returns name of the page or seo name.
	*/
	public static function current_page() {
		$trim_data = rtrim($_SERVER['PHP_SELF'], '/');
		$split_page_path = explode('/', $trim_data);
		$page_name = end($split_page_path);
		return $page_name;
	}

	/*
	function generateFormToken()

	* generate a token from an unique value
	* @param form: name of the form.
	* @returns random generated security token
	*/
	public static function generateFormToken($form) {
		$token = md5(uniqid(microtime(), true));
		$_SESSION[$form.'_token'] = $token; 
		return $token;
	}

	/*
	function verifyFormToken()

	* check with the submitted token through form submission
	* @param form: name of the form.
	* @returns true if match found or false in not found.
	*/
	public static function verifyFormToken($form) {
		// check if a session is started and a token is transmitted, if not return an error
		if(!isset($_SESSION[$form.'_token'])) { 
			return false;
		}
		// check if the form is sent with token in it
		if(!isset($_POST['token'])) {
			return false;
		}
		
		// compare the tokens against each other if they are still the same
		if ($_SESSION[$form.'_token'] !== $_POST['token']) {
			return false;
		}
		return true;
	}

	/*
	function shorten_string($string, $wordsreturned)

	* this function cut the scring to certain length provided.
	* @param string: raw string data provided.
	* @param wordsreturned: number of work needs to be rerurned.
	* @returns cut string.
	*/
	public static function shorten_string($string, $wordsreturned) {
	  $retval = $string;
	  $string = preg_replace('/(?<=\S,)(?=\S)/', ' ', $string);
	  $string = str_replace("\n", " ", $string);
	  $array = explode(" ", $string);
	  if (count($array)<=$wordsreturned) {
		$retval = $string;
	  } else {
		array_splice($array, $wordsreturned);
		$retval = implode(" ", $array)." ...";
	  }
	  return $retval;
	}

	/*
	function isValidLength($text , $length)

	* this function check the provided string length with provided length
	* @param text: raw string data provided.
	* @param length: length needs to be checked.
	* @returns if match found retun true otehrwise return false.
	*/
	public static function isValidLength($text , $length) {
	   $text  = explode(" " , $text );
	   if(count($text) > $length)
			  return false;
	   else
			  return true;
	}

	/*
	function hash_password($password)

	* this function generate the cript() version of the provided password. 
	* @param password: raw password entered by the user.
	* @returns if match found retun true otehrwise return false.
	*/
	public static function hash_password($password) {
	   $hashed_password = sha1($password.SECURITY_SALT);
	   return $hashed_password;
	}

	/*
	function post_form_status($from_data, $data = null, $type)

	* this function will check the posted form data or database driven data and will show in value fields accordingly. 
	* @param from_data: posted data or database driven data.
	* @param data: data to which the posted form data to database driven data needs to be checked. It can be null as well
	* @param type: it signifies what type of form field it is.
	* @returns data if found or corresponding tag for checkbox and drop-down otherwise return false.
	*/
	public static function post_form_status($from_data, $data = null, $type) {
		if($type == 'text' || $type == 'text-area') {
			if(isset($from_data) && $from_data!='') {
				return $from_data;
			}
		} else if($type == 'dropdown') {
			if(isset($from_data) && $from_data == $data) {
				return "selected=selected";
			}
		} else if($type == 'checkbox' || $type == 'radio') {
			if(isset($from_data) && $from_data == $data) {
				return "checked";
			}
		} else {
			return false;
		}
	}

	/*
	function unlink_files($file_name, $file_path)

	* this function will delete the file within the specified path. 
	* @param file_name: name of the file that needs to be deleted.
	* @param file_path: path of teh file that needs to be deleted.
	* @returns true if deleted otehrwise false.
	*/
	public static function unlink_files($file_name, $file_path) {
		if($file_name!='' && file_exists($file_path.$file_name)) {
			if(unlink($file_path.$file_name)) {
				return true;
			} else {
				return false;
			}
		}
	}

	/*
	function verify_token()

	* validate whether only valid for fields are posted. This function will prevent forcefully added form hidden fields to be posted.
	* $white_list_array: array_of from fieldname.
	* $post_date: date posted through from using POST method.
	* return true if success or false is invalid.
	* $verify_token: Fixed defined token for each from.
	* returnds true if matching found, otherwise returnds false.
	*/
	public static function verify_token($white_list_array, $post_date, $verify_token) {
		$security_flag = true;
		foreach($post_date AS $key=>$value) {
			if(!in_array($key, $white_list_array)) {
				$security_flag = false;
				break;
			}
		}
		if(tools::verifyFormToken($verify_token) && $security_flag) {
			return true;
		} else {
			return false;
		}
	}

	/*
	function module_logout($redirect_path)

	* logout from application
	* @param string destination path
	* @return: NONE. Redirect user to the provided destination path
	*/
	public static function module_logout($destination_path) {
		if(count($_SESSION)) {
			foreach($_SESSION AS $key=>$value) {
				session_unset($_SESSION[$key]);
			}
			session_destroy();
		}
		header("location:".$destination_path);
	}

	/*
	function module_redirect($redirect_type, $redirect_path)

	* logout from application
	* @param string redirect_type = 'E/I' i.e: External for external redirect path, Internal for internal redirect path 
	* @return: NONE. Redirect user to the provided destination path
	*/
	public static function module_redirect($redirect_path) {
		header("location:".$redirect_path);
		exit;
	}

	/*
	function module_validation_check($checkingVariable, $destinationPat)

	* check whether the page is accessable without login or not.
	* @param string checkingVariable: Consists of the variable value based on whcih validation needs to be done.
	* @return: If true redirects user to the destination path, otherwise return true.
	*/
	public static function module_validation_check($checkingVariable, $destination_path) {
		if($checkingVariable =='') {
		 	tools::module_logout($destination_path);
		}
	}

	/*
	function recurring_replace($find_array, $replace_array, $actual_string)

	* This function relace the array of data with provided array of data within a provided string. 
	* @param string find_array: Lists of array data that needs to be find within the provided string.
	* @param string replace_array: Lists of array data that needs to be relanced within the provided string based on find_array.
	* @param string actual_string: The string for which data needs to be replaced.
	* @return: If found return replaced string otherwise return false.
	*/
	public static function recurring_replace($find_array, $replace_array, $actual_string) {
		$final_string = $actual_string;
		if((is_array($find_array) && count($find_array) > 0) && (is_array($replace_array) && count($replace_array) > 0) && (count($find_array) == count($replace_array))) {
			for($i = 0; $i<count($find_array); $i++) {
				$final_string = str_ireplace($find_array[$i], $replace_array[$i], $final_string);
			}
			return $final_string;
		} else {
			return false;
		}
	}

	/*
	function module_counter($where_clause, $table)

	* this function will count or sum the data for you and return count value.
	* $param1: Username or email address of the account
	* $param2: Password of the account
	* $param3: optional, mostly used as status valiable
	* $table_name: name of the table to which you need to validate
	* return true if success or false is invalid.
	*/
	public static function module_counter($type, $field_to_count, $where_clause, $table) {
		$where_clause = "WHERE ".$where_clause;

		if($type == "COUNT") {
			if($count_data = tools::find("first", $table, $value = 'count('.$field_to_count.') AS COUNT_VAL', $where_clause, array()))
			{
				return $count_data['COUNT_VAL'];
			} else {
				return false;
			}
		} if($type == "SUM") {
			if($count_data = tools::find("first", $table, $value = 'SUM('.$field_to_count.') AS COUNT_VAL', $where_clause, array())) {
				return $count_data['COUNT_VAL'];
			} else {
				return false;
			}
		} if($type == "AVG") {
			if($count_data = tools::find("first", $table, $value = 'AVG('.$field_to_count.') AS COUNT_VAL', $where_clause, array())) {
				return $count_data['COUNT_VAL'];
			} else {
			 	return false;
			}
		}
	}

	/*
	function module_currency($type, $currency_id = 'null', $table)

	* this function find and rerurns either all available currencies or specific currency search for.
	* $type: it can be either 'FETCH' or 'FIND' If 'FETCH' will return all active currencies. If 'FIND' will rerurn specific currency details.
	* $currency_id: specific currency id you are searching for. It can be null.
	* $table: name of the database table.
	* return array of data if found otherwise false.
	*/

	public static function module_currency($type, $currency_id = 'null', $table) {
		if($type == 'FETCH') {
			$where_clause = "WHERE status = 1 ORDER BY serial_number";
			if($currency_data = tools::find("all", $table, $value = 'id, currency_name, currency_code', $where_clause, array())) {
				return $currency_data;
			} else {
				return false;
			}
		} if($type == 'FIND') {
			$where_clause = "WHERE id = ".$currency_id." AND status = 1";
			if($currency_data = tools::find("first", $table, $value = 'id, currency_name, currency_code', $where_clause, array())) {
				return $currency_data;
			} else {
				return false;
			}
		}
	}

	/*
	function module_country($type, $currency_id = 'null', $table)

	* this function find and rerurns either all available countries or specific countries search for.
	* $type: it can be either 'FETCH' or 'FIND' If 'FETCH' will return all active countries. If 'FIND' will rerurn specific countries details.
	* $country_id: specific countries id you are searching for. It can be null.
	* $table: name of the database table.
	* return array of data if found otherwise false.
	*/

	public static function module_country($type, $country_id = 'null', $table) {
		if($type == 'FETCH') {
			$where_clause = "WHERE 1";
			if($country_data = tools::find("all", $table, $value = 'id, sortname, name', $where_clause, array())) {
				return $country_data;
			} else {
				return false;
			}
		} if($type == 'FIND') {
			$where_clause = "WHERE id = ".$country_id."";

			if($country_data = tools::find("first", $table, $value = 'id, sortname, name', $where_clause, array())) {
				return $country_data;
			} else {
				return false;
			}
		}
	}

	/*
	function module_state($type, $state_id = 'null', $country_id = 'null', $table)

	* this function find and rerurns either all available states or specific states search for or state lists for a specific country.
	* $type: it can be either 'FETCH' or 'FIND' If 'FETCH' will return all active states. If 'FIND' will rerurn specific states details.
	* $state_id: specific states id you are searching for. It can be null.
	* $country_id: specific country id you are searching all the states of taht country. It can be null.
	* $table: name of the database table.
	* return array of data if found otherwise false.
	*/

	public static function module_state($type, $state_id = 'null', $country_id = 'null', $table) {
		if($type == 'FETCH') {
			if($country_id!='') {
				$where_clause = "WHERE country_id = ".$country_id."";
				if($state_data = tools::find("all", $table, $value = 'id, name, country_id', $where_clause, array())) {
					return $state_data;
				} else {
					return false;
				}
			} else {
				$where_clause = "WHERE 1";
				if($state_data = tools::find("all", $table, $value = 'id, name, country_id', $where_clause, array())) {
					return $state_data;
				} else {
					return false;
				}
			}
		} if($type == 'FIND') {
			$where_clause = "WHERE id = ".$state_id."";
			if($state_data = tools::find("first", $table, $value = 'id, name, country_id', $where_clause, array())) {
				return $state_data;
			} else {
				return false;
			}
		}
	}

	/*
	function module_city($type, $city_id = 'null', $state_id = 'null', $table)

	* this function find and rerurns either all available cities or specific city search for or cities lists for a specific state.
	* $type: it can be either 'FETCH' or 'FIND' If 'FETCH' will return all active cities. If 'FIND' will rerurn specific cities details.
	* $city_id: specific cities id you are searching for. It can be null.
	* $state_id: specific state id for which you need city listing
	* $table: name of the database table.
	* return array of data if found otherwise false.
	*/
	public static function module_city($type, $city_id = 'null', $state_id = 'null', $table) {
		if($type == 'FETCH') {
			if($state_id!='') {
				$where_clause = "WHERE state_id = ".$state_id."";
				if($city_data = tools::find("all", $table, $value = 'id, name, state_id', $where_clause, array())) {
					return $city_data;
				} else {
				 	return false;
				}
			} else {
				$where_clause = "WHERE 1";
				if($city_data = tools::find("all", $table, $value = 'id, name, state_id', $where_clause, array())) {
					return $city_data;
				} else {
					return false;
				}
			}
		}
		if($type == 'FIND') {
			$where_clause = "WHERE id = ".$city_id."";
			if($city_data = tools::find("first", $table, $value = 'id, name, state_id', $where_clause, array())) {
				return $city_data;
			} else {
				return false;
			}
		}
	}

	/*
	function module_form_submission($file_json, $table)

	* this function used to insert / update form data into database.
	* $file_json: this is a json data that will consists of lists of uploaded file name and path.
	* $table: name of the database table.
	* return last inserted id if successfully data is been inserted, return false otherwise.
	* file_json format:
	* IMAGE: {"uploaded_file_data":[{"form_field_name":"restaurant_logo","form_field_name_hidden":"restaurant_logo_hidden","file_path":"../assets/vendor_logo","width":"","height":"","file_type":"image"}]}
	* FILE: {"uploaded_file_data":[{"form_field_name":"restaurant_logo","form_field_name_hidden":"restaurant_logo_hidden","file_path":"../assets/vendor_logo","width":"","height":"","file_type":"file"}]}
	* ALL: {"uploaded_file_data":[{"form_field_name":"restaurant_logo","form_field_name_hidden":"restaurant_logo_hidden","file_path":"../assets/vendor_logo","width":"","height":"","file_type":"all"}]}
	*/
	public static function module_form_submission($file_json, $table)
	{
		$flag_check = "VALID";
		$db_field_array = array();
		$field_data = "";
		$values_data_insert = "";
		$values_data_update = "";
		$execute_data = array();

		if($file_json!= '') {
			$decoded_file_data = json_decode($file_json, true);
			foreach($decoded_file_data['uploaded_file_data'] AS $uploaded_file_info) {
				if(isset($_POST[''.$uploaded_file_info['form_field_name_hidden'].'']) && $_POST[''.$uploaded_file_info['form_field_name_hidden'].'']!='') {
					if($_FILES[''.$uploaded_file_info['form_field_name'].'']['name']!='') {
						$position_of_dot = strrpos($_FILES[''.$uploaded_file_info['form_field_name'].'']['name'],'.');
						$extension = substr($_FILES[''.$uploaded_file_info['form_field_name'].'']['name'], $position_of_dot+1);
						if($uploaded_file_info['file_type'] == 'image') {
							$validation_array = array('jpg', 'jpeg', 'png', 'gif', 'bmp');
							if(in_array(strtolower($extension), $validation_array)) {
								$flag_check = "VALID";
							} else {
								$flag_check = "INVALID";
								return $flag_check;
							}
						} if($uploaded_file_info['file_type'] == 'file') {
							$validation_array = array('exc', 'dmf', '.zip', 'tar.gz', 'rar', 'jpg', 'jpeg', 'png', 'gif', 'bmp');
							if(!in_array(strtolower($extension), $validation_array)) {
								$flag_check = "VALID";
							} else {
								$flag_check = "INVALID";
								return $flag_check;
							}
						}
						if($uploaded_file_info['file_type'] == 'all') {
							$validation_array = array('exc', 'dmf', '.zip', 'tar.gz', 'rar');
							if(!in_array($extension, $validation_array)) {
								$flag_check = "VALID";
							} else {
								$flag_check = "INVALID";
								return $flag_check;
							}
						}

						if($flag_check == "VALID") {
							if($_POST[''.$uploaded_file_info['form_field_name_hidden'].'']!='' && file_exists($uploaded_file_info['file_path'].$_POST[''.$uploaded_file_info['form_field_name_hidden'].''])) {
								@unlink($uploaded_file_info['file_path'].$_POST[''.$uploaded_file_info['form_field_name_hidden'].'']);
							}
							$random_number = tools::create_password(5);
							$file_name = str_replace(" ",'',$random_number."_".$_FILES[''.$uploaded_file_info['form_field_name'].'']['name']);
							move_uploaded_file($_FILES[''.$uploaded_file_info['form_field_name'].'']['tmp_name'], $uploaded_file_info['file_path'].$file_name);
						}
						$field_data.= $uploaded_file_info['form_field_name'].", ";
						$values_data_insert.= ":".$uploaded_file_info['form_field_name'].", ";
						$values_data_update.= $uploaded_file_info['form_field_name']."=:".$uploaded_file_info['form_field_name'].", ";
						$execute_data[''.$uploaded_file_info['form_field_name'].''] = $file_name;
					}
				}
				else
				{
					if($_FILES[''.$uploaded_file_info['form_field_name'].'']['name']!='') {
						$position_of_dot = strrpos($_FILES[''.$uploaded_file_info['form_field_name'].'']['name'],'.');
						$extension = substr($_FILES[''.$uploaded_file_info['form_field_name'].'']['name'], $position_of_dot+1);
						if($uploaded_file_info['file_type'] == 'image') {
							$validation_array = array('jpg', 'jpeg', 'png', 'gif', 'bmp');
							if(in_array(strtolower($extension), $validation_array)) {
								$flag_check = "VALID";
							} else {
								$flag_check = "INVALID";
								return $flag_check;
							}
						} if($uploaded_file_info['file_type'] == 'file') {
							$validation_array = array('exc', 'dmf', '.zip', 'tar.gz', 'rar', 'jpg', 'jpeg', 'png', 'gif', 'bmp');
							if(!in_array(strtolower($extension), $validation_array)){
								$flag_check = "VALID";
							} else {
								$flag_check = "INVALID";
								return $flag_check;
							}
						} if($uploaded_file_info['file_type'] == 'all') {
							$validation_array = array('exc', 'dmf', '.zip', 'tar.gz', 'rar');
							if(!in_array($extension, $validation_array)) {
								$flag_check = "VALID";
							} else {
								$flag_check = "INVALID";
								return $flag_check;
							}
						}

						if($flag_check == "VALID") {
							$random_number = tools::create_password(5);
							$file_name = str_replace(" ",'',$random_number."_".$_FILES[''.$uploaded_file_info['form_field_name'].'']['name']);
							move_uploaded_file($_FILES[''.$uploaded_file_info['form_field_name'].'']['tmp_name'], $uploaded_file_info['file_path'].$file_name);
						}
						$field_data.= $uploaded_file_info['form_field_name'].", ";
						$values_data_insert.= ":".$uploaded_file_info['form_field_name'].", ";
						$values_data_update.= $uploaded_file_info['form_field_name']."=:".$uploaded_file_info['form_field_name'].", ";
						$execute_data[''.$uploaded_file_info['form_field_name'].''] = $file_name;
					}
				}
			}
		}
		$data = tools::find_custom("SHOW COLUMNS FROM ".$table."");
		foreach($data AS $values) {
			array_push($db_field_array, $values['Field']);
		}
		foreach($_POST AS $key=>$value) {
			if(in_array($key, $db_field_array)) {
				if($key == 'id' || $key == 'id_custom') {
					//DO NOTHING;
				} else {
					if($key == "password") {
						if($value!='') {
							$field_data.= $key.", ";
							$values_data_insert.= ":".$key.", ";
							$values_data_update.= $key."=:".$key.", ";
							$execute_data[''.$key.''] = tools::hash_password(stripslashes(trim($value)));
						}
					} else {
						$field_data.= $key.", ";
						$values_data_insert.= ":".$key.", ";
						$values_data_update.= $key."=:".$key.", ";
						if(is_array($value) && count($value) > 0) {
							$value = implode(',',$value);
						}
						$execute_data[''.$key.''] = stripslashes(trim($value));
					}
				}
			}
		}
		$field_data = rtrim($field_data, ', ');
		$values_data_insert = rtrim($values_data_insert, ', ');
		$values_data_update = rtrim($values_data_update, ', ');
		if(isset($_POST['id']) && $_POST['id']!='') {
			if(isset($_POST['id_custom']) && $_POST['id_custom']!='') {
				$where_clause = 'WHERE '.$_POST['id'].'='.$_POST['id_custom'].'';
			} else {
				$where_clause = 'WHERE id='.$_POST['id'].'';
			}
			if(tools::update($table, $values_data_update, $where_clause, $execute_data)) {
				return true;
			} else {
				return false;
			}
		} else {
			if($last_inserted_id = tools::save($table, $field_data, $values_data_insert, $execute_data)) {
				return $last_inserted_id;
			} else {
				return false;
			}
		}
		/*print $field_data."<br/>";
		print $values_data_insert."<br/>";
		print $values_data_update."<br/>";
		print_r($execute_data)."<br/>";
		exit;*/
	}

	/*
	function module_data_exists_check($condition, $table)

	* check if the current data already exists in database or not.
	* $condition: the where condition that needs to be checked.
	* $table: name of the table to which you need to validate
	* return true if success or false is invalid.
	*/

	public static function module_data_exists_check($condition, $value = null, $table) {
		$return_value = "";
		$where_clause = "WHERE ".$condition;
		if($value!='') {
			$return_value = $value;
		} else {
			$return_value = 'id';
		}

		if($check = tools::find("first", $table, $return_value, $where_clause, array())) {
			return true;
		} else {
		 	return false;
		}
	}

	public static function module_date_format($date, $current_format="Y-m-d") {
		if($date!="" && $date!="0000-00-00"):
			$date_obj=date_create_from_format($current_format, $date);
			$return_format=date_format($date_obj,"d/m/Y");
			return $return_format;
		else:
			return "N/A";
		endif;
	}
	public static function slugify($text){ 
		// replace non letter or digits by -
		$text = preg_replace('~[^\\pL\d]+~u', '-', $text);
		
		// trim
		$text = trim($text, '-');
		
		// transliterate
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
		// lowercase
		$text = strtolower($text);		
		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);
		
		if (empty($text)){
			return 'n-a';
		}	
		return $text;
	}
	public static function apiauthentication($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json, Content-Type: application/json","Authorization: Basic ".base64_encode(API_USERNAME.":".API_PASSWORD)));
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		$return_data = curl_exec($ch);
		$return_data_arr=json_decode($return_data, true);
		if(!isset($return_data_arr['status']))
		{
			$return_data_arr['status']="error";
			$return_data_arr['msg']="Some error has been occure during authentication.";
		}
		if(curl_error($ch))
		{
			$return_data_arr['status']="error";
			$return_data_arr['msg']=curl_error($ch);
			//$return_data_arr['error_data']=curl_error($ch);
		}
		curl_close($ch);
		$return_data=json_encode($return_data_arr);
		return $return_data;
	}
}
?>