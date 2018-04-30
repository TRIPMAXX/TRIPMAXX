<?php
/*
=========================================================================================================================
COPYRIGHT: NEO CODERZ TECHNOLOGIES
PRODUCT NAME: TRAVELMAXX
PAGE FUNCTIONALITY: THIS IS THE CLASS FOR EMAIL TEMPLATE FEATURE.
=========================================================================================================================
*/

class email_template extends tools { 
	
	/*****************************************************************************************************
	**									USED FOR REST API CALL											**
	**																									**
	******************************************************************************************************/

	/*
	function email_template_find($id)

	* Used for finding Email Template data.
	* @param integer id: Unique id of DMC / Employee. Its an optional value, if provided return that specific data otherwise all data is been returned.
	* @return: specific / full email template data array.
	* Used for normal process.
	*/
	public static function template($id = null) {
		if($id!='') {
			$template = tools::find("first", TM_EMAIL_TEMPLATES, $value='id, template_title, template_subject, template_body, status', "WHERE status = 1 AND id = ".$id."", array());
			return json_encode($template);
		} else {
			return false;
		}
	}
}
?>