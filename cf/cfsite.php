<?php
if (!defined('CUSTOM_CHECK_GLB')){header("Location: upgrade");die();}
// file define config nhung thong tin chung cho template website o header, footer...: phone support, email support, link face, link zalo,...

define('PRODUCT_NAME', 'Virtual Staging | Stage Of Visualization | Hanoi');

define('VERSION', 'v2023');

define('SOCKET_SERVICES', 'https://quancoder.online:5001/');

define('LINK_OVERVIEW', 'overview');
define('LINK_HIW', 'how-it-works');
define('LINK_PRICINGS', 'pricing');
define('LINK_LIBRARY', 'library');
define('LINK_ORDER', 'order');
define('LINK_ORDER_AJAX_ADD_REWORK', 'order/ajax-add-rework');

define('LINK_ABOUT', 'about');
define('LINK_CAREERS', 'careers');
define('LINK_CONTACT', 'contact');

define('LINK_POLICY', 'pricacy-policy');
define('LINK_TERMS', 'terms-of-use');
define('LINK_REFUND', 'refund-policy');

define('LINK_USER_ORDER', 'user/order');
define('LINK_USER_ORDER_DETAIL', 'user/orderdetail');
define('LINK_USER_NOTICES', 'user/notices');
define('LINK_USER_PROFILES', 'user/profiles');
define('LINK_USER_SETTINGS', 'user/settings');
define('LINK_USER_TRANSACTIONS', 'user/transactions');
define('LINK_USER_SIGN_IN', 'user/sign_in');
define('LINK_USER_SIGN_OUT', 'user/logout');
define('LINK_USER_LOGIN', 'login');
define('LINK_USER_LOGOUT', 'logout');
define('LINK_USER_REGISTER', 'register');

define('PAY_DANG_CHO', 0); // chưa thanh toán
define('PAY_HOAN_THANH', 1); // đa thanh toán
define('PAY_HUY', 2); // hủy thanh toán hoặc có lỗi


define('LOGO_FOLDER', 'logo'); // uploads/images/logo
define('HOME_FOLDER', 'home'); // uploads/images/home
define('SLIDE_FOLDER', 'home/slide'); // uploads/images/home/slide
define('PARTNER_FOLDER', 'home/partner');  // uploads/images/home/partner

define('PUBLIC_UPLOAD_PATH', 'uploads/');
define('TMP_UPLOAD_PATH', 'uploads/tmp/');
define('FOLDER_AVATAR', 'uploads/avatar/');
define('FOLDER_PARTNER', 'uploads/partner/');
define('FOLDER_SLIDE', 'uploads/slide/');
define('FOLDER_LOGO', 'uploads/logo/');
define('FOLDER_SERVICES', 'uploads/services/');
define('FOLDER_ROOM', 'uploads/room/');
define('FOLDER_STYLE', 'uploads/style/');
define('FOLDER_LIBRARY', 'uploads/library/');
define('FOLDER_LIBRARY_THUMB', 'uploads/library/thumb/');
define('FOLDER_ORDER', 'uploads/order/');
define('FOLDER_CHAT_TONG', 'uploads/chat_tong/');
define('FOLDER_HIW', 'uploads/hiw/');

define('FILE_MAIN', 1);
define('FILE_REF', 2);
define('FILE_COMPLETE', 3);
define('FILE_REWORK', 4);
define('FILE_DISCUSS', 5);