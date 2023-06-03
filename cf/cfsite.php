<?php
if (!defined('CUSTOM_CHECK_GLB')){header("Location: upgrade");die();}
// file define config nhung thong tin chung cho template website o header, footer...: phone support, email support, link face, link zalo,...

define('PRODUCT_NAME', 'Virtual Staging | Stage Of Visualization | Hanoi');

define('VERSION', 'v2023');

define('LINK_OVERVIEW', 'overview');
define('LINK_HIW', 'how-it-works');
define('LINK_PRICINGS', 'pricing');
define('LINK_LIBRARY', 'library');
define('LINK_ORDER', 'order');

define('LINK_ABOUT', 'about');
define('LINK_CAREERS', 'careers');
define('LINK_CONTACT', 'contact');

define('LINK_POLICY', 'pricacy-policy');
define('LINK_TERMS', 'terms-of-use');
define('LINK_REFUND', 'refund-policy');

define('LINK_USER_ORDER', 'user/order');
define('LINK_USER_NOTICES', 'user/notices');
define('LINK_USER_PROFILES', 'user/profiles');
define('LINK_USER_SETTINGS', 'user/settings');
define('LINK_USER_TRANSACTIONS', 'user/transactions');
define('LINK_USER_LOGOUT', 'user/logout');
define('LINK_USER_SIGN_IN', 'user/sign_in');
define('LINK_USER_SIGN_OUT', 'user/logout');
define('LINK_USER_LOGIN', 'login');
define('LINK_USER_REGISTER', 'dang-ky');

define('PAY_DANG_CHO', 0); // chưa thanh toán
define('PAY_HOAN_THANH', 1); // đa thanh toán


define('STATUS_CHUA_LAM', 0); // đơn chưa giao mới tạo
define('STATUS_DA_GIAO', 1); // đơn đã giao cho designer
define('STATUS_DANG_LAM', 2); // designer đang làm
define('STATUS_SUA_LAI', 3); // đơn phải sửa lại
define('STATUS_HOAN_THANH', 4); // đơn đã xong


define('LOGO_FOLDER', 'logo'); // uploads/images/logo
define('HOME_FOLDER', 'home'); // uploads/images/home
define('SLIDE_FOLDER', 'home/slide'); // uploads/images/home/slide
define('PARTNER_FOLDER', 'home/partner');  // uploads/images/home/partner