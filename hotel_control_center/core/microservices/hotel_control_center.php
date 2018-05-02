<?php
/*
=========================================================================================================================
COPYRIGHT: NEO CODERZ TECHNOLOGIES
PRODUCT NAME: TRAVELMAXX
PAGE FUNCTIONALITY: THIS IS THE MICROSERVICE FOR DMC FEATURE.
=========================================================================================================================
*/

class hotel_control_center extends tools { 
	
	/*
	function control_center_login()

	* Used for dmc / employee login.
	* @return: true if username and password is valid Or false if invalid.
	* Used for normal process.
	*/
	public function hotel_control_center_login() { 
		$password = tools::hash_password($_POST['password']);
		$execute_array[':email_address']=tools::stripcleantohtml($_POST['email_address']);
		$execute_array[':password']=tools::stripcleantohtml($password);
		if($_POST['status']!='') {
			$execute_array[':status']=$_POST['status'];
			$where_clause = "WHERE (email_address = :email_address) AND password = :password AND status = :status";
		} else{
			$where_clause = "WHERE (email_address = :email_address) AND password = :password";
		}
		if($login_check = tools::find("first", TM_HOTELS, $value='id, hotel_name, hotel_images, email_address, phone_number, creation_date, last_updated, status', $where_clause, $execute_array)) {
			$_SESSION['SESSION_DATA_HOTEL'] = $login_check;
			return true;
		} else {
			return false;
		}
	}

	/*
	function control_center_set_permission($id)

	* Used for setting employee permission.
	* @param integer id: Unique id of Employee.
	* @return: true if success, false otehrwise.
	* Used for normal process.
	*/
	public function hotel_control_center_set_permission($id) {
	}
	
	/*
	function control_center_update_permission($id)

	* Used for updating employee permission.
	* @param integer id: Unique id of Employee.
	* @return: true if success, false otehrwise.
	* Used for normal process.
	*/
	public function hotel_control_center_update_permission() {
	}

	/*****************************************************************************************************
	**									USED FOR REST API CALL											**
	**																									**
	******************************************************************************************************/

	/*
	function auto_login()

	* Used for dmc / employee auto login.
	* @return: Json with success flag.
	* Used for Webservice.
	*/
	public static function hotel_auto_login($hotel_id) {
		if($login_check = tools::find("first", TM_HOTELS, $value='*', "WHERE id=:id", array(":id"=>$hotel_id))) {
			$_SESSION['SESSION_DATA_HOTEL'] = $login_check;
			return true;
		} else {
			return false;
		}
	}
}
?>