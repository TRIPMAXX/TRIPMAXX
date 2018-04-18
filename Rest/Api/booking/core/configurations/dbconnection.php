<?php
/*
=========================================================================================================================
COPYRIGHT: NEOCODERZ TECHNOLOGIES
PRODUCT NAME: TRAVELMAXX
PAGE FUNCTIONALITY: CONSISTS OF FUNCTION TO CONNECT WITH DATABASE SERVER AND DATABASE.
=========================================================================================================================
*/

class dbconnection { 

	public function db_connect() {
		global $db;
		if(DATABASE_NAME=='') {
			return '';
		} else {
			try {
				$db = new PDO("mysql:dbname=".DATABASE_NAME."; host=".DATABASE_SERVER."", DATABASE_USERNAME, DATABASE_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
				return $db;
			} catch(PDOException $exception){
				echo "DATABASE CONNECTION ERROR: " . $exception->getMessage();
			}
		}
	}
}
?>