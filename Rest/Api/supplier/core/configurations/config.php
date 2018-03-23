<?php
/*
=========================================================================================================================
COPYRIGHT: NEOCODERZ TECHNOLOGIES
PRODUCT NAME: TRAVELMAXX
PAGE FUNCTIONALITY: CONSISTS OF WEBSITE GENERAL AND OVERALL CONFIGURATION SETTINGS AND DEFINATION OF CONSTANTS AND GLOBAL VARIABLES USED THROUGHOUT THE WEBSITE.
=========================================================================================================================
*/

/*******************************************************************************
DATABASE CONFIGURATION
********************************************************************************/

//ENTER THE NAME OF THE DATABASE SERVER YOU ARE CONNECTING TO. NORMALLY SET TO "localhost"
define("DATABASE_SERVER", "localhost");

//ENTER THE NAME OF YOUR DATABASE
define("DATABASE_NAME", "tripmaxx_supplier");

//ENTER THE USERNAME THAT CONNECTS TO YOUR DATABASE
define("DATABASE_USERNAME", "root");

//ENTER THE PASSWORD FOR YOUR DATABASE USER
define("DATABASE_PASSWORD", "");

//BASE PATH
$website_url = ((isset($_SERVER['HTTPS']) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].'/').'tripmaxx/';
$project_folder = '';

/*******************************************************************************
DEFINE PATH FOR FRONT END
********************************************************************************/

//ENTER THE DOMAIN NAME FOR YOUR APPLICATION
define("DOMAIN_NAME_PATH", $website_url);

/*******************************************************************************
DEFINE PATH FOR CONTROL CENTER
********************************************************************************/

//URL FOR CONTROL CENTER
define("DOMAIN_NAME_PATH_ADMIN", $website_url.$project_folder."control_center/");

//URL FOR HOTEL CONTROL CENTER
define("DOMAIN_NAME_PATH_HOTEL", $website_url.$project_folder."hotel_control_center/");

//URL FOR CONTROL CENTER CSS
define("CONTROL_CENTER_CSS_PATH", $website_url.$project_folder."control_center/assets/css/");

//URL FOR CONTROL CENTER IMAGE
define("CONTROL_CENTER_IMAGE_PATH", $website_url.$project_folder."control_center/assets/img/");

//URL FOR CONTROL CENTER JS
define("CONTROL_CENTER_JS_PATH", $website_url.$project_folder."control_center/assets/js/");

//PATH FOR CONTROL CENTER CKEDITOR
define("CONTROL_CENTER_CKEDITOR_PATH", $website_url.$project_folder."control_center/assets/ckeditor/");

//PATH FOR CONTROL CENTER COMMON FILES
define("CONTROL_CENTER_COMMON_FILE_PATH", "assets/common/");

//DAFAULT PAGE TITLE
define("DEFAULT_PAGE_TITLE", "TRIPMAXX - ");

//DAFAULT PAGE TITLE CONTROL CENTER
define("DEFAULT_PAGE_TITLE_CONTROL_CENTER", "CONTROL CENTER - TRIPMAXX - ");

//DEFINE PRODUCT NAME 
define("PRODUCT_NAME", "TRIPMAXX");

//DEFINE PASSWORD SECURITY SALT
define("SECURITY_SALT", "neo@008790-09878900678905456@coderz%4844q0pdda!4545");
define("PROMO_DOC", "../../../../../control_center/assets/upload/promotional_offer/");
define("CMS_BANNER", "../../../../../control_center/assets/upload/cms_banner/");
define("GENERAL_IMAGES", "../../../../../control_center/assets/upload/general/");

define("API_USERNAME", "a");
define("API_PASSWORD", "1");
define("TOKEN", "b");
define("TOKEN_TIMEOUT", "10");
?>