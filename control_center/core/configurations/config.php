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
define("DATABASE_NAME", "tripmaxx_dmc");

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
//URL FOR SUPPLIER CONTROL CENTER
define("DOMAIN_NAME_PATH_SUPPLIER", $website_url.$project_folder."supplier_control_center/");

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
define("AUTO_LOGIN_SECURITY_KEY", "hjre658@37#99!");
define("PROMO_DOC", "assets/upload/promotional_offer/");
define("CMS_BANNER", "assets/upload/cms_banner/");
define("GENERAL_IMAGES", "assets/upload/general/");
define("SUPPORT_TICKET_IMAGE", "assets/upload/support_ticket_image/");
define("SUPPORT_TICKET_REPLY_IMAGE", "assets/upload/support_ticket_reply_image/");
define("API_USERNAME", "a");
define("API_PASSWORD", "1");
define("REST_API_PATH", "Rest/Api/");
define("SUPPLIER_API_PATH", "supplier/api/");
define("HOTEL_API_PATH", "hotel/api/");
define("AGENT_API_PATH", "agent/api/");
define("TOUR_API_PATH", "tour/api/");
define("PACKAGE_API_PATH", "package/api/");
define("TRANSFER_API_PATH", "transfer/api/");
define("BOOKING_API_PATH", "booking/api/");
define("DMC_API_PATH", "dmc/api/");

define("MAX_ROOM_NO", 10);
define("MAX_ADULT_NO", 10);
define("MAX_CHILD_NO", 10);
define("MAX_CHILD_AGE", 11);
define("RECORD_PER_PAGE", 10);

define("FROM_EMAIL", "noreply@neocoderztechnologies.com");


define("HOTEL_IMAGE_PATH", DOMAIN_NAME_PATH."hotel_control_center/assets/upload/hotel/thumb/");
define("ROOM_IMAGE_PATH", DOMAIN_NAME_PATH."hotel_control_center/assets/upload/room/thumb/");
define("AGENT_IMAGE_PATH", DOMAIN_NAME_PATH."agent_control_center/assets/upload/agent/");
define("TOUR_IMAGE_PATH", DOMAIN_NAME_PATH."tour_control_center/assets/upload/tour/thumb/");
define("TRANSFER_IMAGE_PATH", DOMAIN_NAME_PATH."transfer_control_center/assets/upload/transfer/thumb/");
define("PACKAGE_IMAGE_PATH", DOMAIN_NAME_PATH."package_control_center/assets/upload/package/");
?>