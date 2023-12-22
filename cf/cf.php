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


//define upload folder
DEFINE('UPLOAD_FOLDER_PATH', 'uploads/'); // folder nay can config write permission tren server

// PhpSpreadsheet library path
define('SPREADSHEET_LIB_PATH', 'app/appv3.1.4/libraries/PhpSpreadsheet/vendor/autoload.php');

// define folder for new skin
define('TEMPLATE_FOLDER', 'v2023/');


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
define('QC', '3');
define('EDITOR', '4');
define('CUSTOMER', '5');

//STATUS ORDER
define('ORDER_PENDING', '1');    //hồng
define('ORDER_QC_CHECK', '2');   //vàng
define('ORDER_AVAIABLE', '3');   //vàng
define('ORDER_PROGRESS', '4');   //xanh ngọc
define('ORDER_DONE', '5');       //xanh lá
define('ORDER_DELIVERED', '6');  //xanh lá
define('ORDER_REWORK', '7');     //vàng
define('ORDER_CANCLE', '8');     //đỏ
define('ORDER_COMPLETE', '9');   //xanh lá
define('ORDER_FIX', '10');      //đỏ
define('ORDER_PAY_WAITING', '11');      //đỏ

// SERVICE
define('SERVICE_RUN', 1);
define('SERVICE_STOP', 0);

// WORKING TYPE
define('WORKING_SALE', 1);
define('WORKING_QC_IN', 2);
define('WORKING_QC_OUT', 5);
define('WORKING_EDITOR', 3);
define('WORKING_CUSTOM', 4);

// 
define('SERVICES_CUSTOM', 'CUSTOM');

// 
define('DON_KHACH_TAO', 1); // đơn do khách tự tạo
define('DON_NOI_BO', 2); // đơn nội bộ do sale, admin tạo, đơn này không cần thanh toán
define('DON_TAO_HO', 3); // đơn do sale, admin tạo hộ khách

//editor type
define('ED_NOI_BO', 1); // editor nội bộ
define('ED_CTV', 2); // editor cộng tác viên

// chat type
define('CHAT_NOI_BO', 1); // chat nội bộ
define('CHAT_KHACH', 2); // chat khách

// thoi_gian_toi_thieu
define('MIN_TIME_WORKING', 0);

// HỒNG ĐƠN MỚI (PENDING)
// ĐỎ   ĐƠN CHẬM (DELIVERED >  hạn chót)
// CAM  URGENT
// VÀNG REWORK (đơn làm lại)
// GREN DELIVERED <= hạn chót (đơn đúng hạn)
// BLUE QCCHECK+AVAIABLE+PROGRESS+DONE => Đang xử lý


//login google
define("gg_ClientId", "654277741157-g3eecjl5a6nq1d3n55jdubasik12221u.apps.googleusercontent.com");
define("gg_ClientSecret", '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');

// limit size upload image
define('LIMIT_SIZE_IMAGE', 52428800); // 50Mb

define('AVATAR_DEFAULT', 'user-default.png');


define('LOG_TIME_CUSTOM', 1);
define('LOG_STATUS', 2);
define('LOG_CUSTOMER_CODE', 3);
define('LOG_ORDER_CODE', 4);
define('LOG_QC_IN_ADD', 5);
define('LOG_QC_IN_REMOVE', 6);
define('LOG_QC_OUT_ADD', 7);
define('LOG_QC_OUT_REMOVE', 8);
define('LOG_ED_ADD', 9);
define('LOG_ED_REMOVE', 10);
define('LOG_CUSTOM_ADD', 11);
define('LOG_CUSTOM_REMOVE', 12);
define('LOG_CUSTOM_TOTAL_PRICE_EDIT', 13);
define('LOG_CUSTOM_USER_PRICE_EDIT', 14);
define('LOG_FILE_MAIN_EDIT', 15);
define('LOG_REF_ADD', 16);
define('LOG_REF_EDIT', 17);
define('LOG_REF_REMOVE', 18);
define('LOG_NOTE_EDIT', 19);
define('LOG_COMPLETE_ADD', 20);
define('LOG_COMPLETE_EDIT', 21);
define('LOG_COMPLETE_REMOVE', 22);
define('LOG_RW_ADD', 23);
define('LOG_RW_FILE_COMPLETE_ADD', 24);
define('LOG_RW_FILE_COMPLETE_EDIT', 25);
define('LOG_RW_FILE_COMPLETE_REMOVE', 26);
define('LOG_RW_REF_ADD', 27);
define('LOG_RW_REF_EDIT', 28);
define('LOG_RW_REF_REMOVE', 29);
define('LOG_RW_NOTE_EDIT', 30);
define('LOG_CREATE_ORDER', 31);
define('LOG_PAYED_ORDER', 32);
const LOG = [
        LOG_TIME_CUSTOM             => 'Sửa thời gian làm đơn hàng',
        LOG_STATUS                  => 'Sửa trạng thái đơn hàng',
        LOG_CUSTOMER_CODE           => 'Sửa mã khách hàng',
        LOG_ORDER_CODE              => 'Sửa mã đơn hàng',
        LOG_QC_IN_ADD               => 'Sửa Working QC IN',
        LOG_QC_IN_REMOVE            => 'Xóa Working QC IN',
        LOG_QC_OUT_ADD              => 'Sửa Working QC OUT',
        LOG_QC_OUT_REMOVE           => 'Xóa Working QC OUT',
        LOG_ED_ADD                  => 'Sửa Working Editor',
        LOG_ED_REMOVE               => 'Xóa Working Editor',
        LOG_CUSTOM_ADD              => 'Thêm Working custom',
        LOG_CUSTOM_REMOVE           => 'Xóa Working custom',
        LOG_CUSTOM_TOTAL_PRICE_EDIT => 'Sửa tổng giá custom của đơn hàng',
        LOG_CUSTOM_USER_PRICE_EDIT  => 'Sửa giá custom của tài khoản',
        LOG_FILE_MAIN_EDIT          => 'Sửa file chính',
        LOG_REF_ADD                 => 'Thêm file đính kèm',
        LOG_REF_EDIT                => 'Sửa ảnh đính kèm',
        LOG_REF_REMOVE              => 'Xóa ảnh đính kèm',
        LOG_NOTE_EDIT               => 'Sửa yêu cầu',
        LOG_COMPLETE_ADD            => 'Thêm file hoàn thành',
        LOG_COMPLETE_EDIT           => 'Sửa file hoàn thành',
        LOG_COMPLETE_REMOVE         => 'Xóa file hoàn thành',
        LOG_RW_ADD                  => 'Thêm mới <b>Rework</b>',
        LOG_RW_FILE_COMPLETE_ADD    => 'Thêm file hoàn thành cho <b>Rework</b> ',
        LOG_RW_FILE_COMPLETE_EDIT   => 'Sửa file hoàn thành cho <b>Rework</b>',
        LOG_RW_FILE_COMPLETE_REMOVE => 'Xóa file hoàn thành cho <b>Rework</b>',
        LOG_RW_REF_ADD              => 'Thêm file đính kèm cho <b>Rework</b>',
        LOG_RW_REF_EDIT             => 'Sửa file đính kèm cho <b>Rework</b>',
        LOG_RW_REF_REMOVE           => 'Xóa file đính kèm cho <b>Rework</b>',
        LOG_RW_NOTE_EDIT            => 'Sửa mổ tả <b>Rework</b>',
        LOG_CREATE_ORDER            => 'Tạo thành công đơn hàng',
        LOG_PAYED_ORDER             => 'Đã thanh toán thành công đơn hàng'
];


// Phương thức thanh toán
define('PAYPAL', 1);