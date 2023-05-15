<?php
if (!defined('CUSTOM_CHECK_GLB')) {
        header("Location: upgrade");
        die();
}
date_default_timezone_set("Asia/Ho_Chi_Minh");

// define common config
DEFINE('DOMAIN_NAME', $_SERVER['SERVER_NAME']); // define domain name
// NOTE: remove all empty line
DEFINE('LOGIN_MOD', 'login'); // define login controller name
DEFINE('LOGOUT_MOD', 'logout'); // define logout controller name

// include custom info for templete
include_once('cfsite.php');

define('PUBLIC_UPLOAD_PATH', 'uploads/images/');

//define upload folder
DEFINE('UPLOAD_FOLDER_PATH', 'uploads/');// folder nay can config write permission tren server

// PhpSpreadsheet library path
define('SPREADSHEET_LIB_PATH', 'app/appv3.1.4/libraries/PhpSpreadsheet/vendor/autoload.php');

// define folder for new skin
define('PC_TEMPLATE_FOLDER', 'v2023/');


/* SMS config */
define('SENDSMS', TRUE);
/* end SMS config */

// define private key for EncryptData lib. 16 character
// neu edit thi edit ca trong adpanel/plugins/filemanager/config/config.php
define('ENCRYPT_DATA_PRIVATE_KEY', 'hcYybzaXjpDW42me');

// for config cookie
define('COOKIE_CONFIG_PREFIX', '');
//define('COOKIE_CONFIG_DOMAIN', (stripos(DOMAIN_NAME, 'localhost') !== FALSE ? '' : DOMAIN_NAME));
define('COOKIE_CONFIG_DOMAIN', '');
define('COOKIE_CONFIG_PATH', '/');
define('COOKIE_CONFIG_SECURE', (HTTP_PROTOCOL == 'https' ? TRUE : FALSE));
define('COOKIE_CONFIG_HTTP_ONLY', TRUE);
define('COOKIE_CONFIG_LIFE_TIME', 0);

// encryption key
define('CONFIG_ENCRYPTION_KEY', 'sQ8hY7wqECMQrcKm');

// config for send email TODO: xóa
define('EMAIL_SENDER', '');
define('EMAIL_SENDER_NAME', '');

// for config session
define('SESSION_CONFIG_DRIVER', 'files'); // files|redis
define('SESSION_CONFIG_COOKIE_NAME', 'sal'); // dung cho session driver = files
define('SESSION_CONFIG_EXPIRATION', 28800); // neu dung session file thi = 0, neu dung redis session thi bang time_expire. redis set = 28800
define('SESSION_CONFIG_SAVE_PATH', session_save_path()); // dung cho session driver = files
define('SESSION_CONFIG_MATCH_IP', FALSE);
define('SESSION_CONFIG_TIME_TO_UPDATE', 300);
define('SESSION_CONFIG_REGENERATE_DESTROY', TRUE);
define('SESSION_CONFIG_TIME_EXPIRE', 28800);


// ROLE
define('ADMIN', '1');
define('SALE', '2');
define('USER', '3');