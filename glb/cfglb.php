<?php
if (!defined('CUSTOM_CHECK_GLB')){header("Location: upgrade");die();}
else
{
	// define http protocol
	define('HTTP_PROTOCOL', 'https');

	//safelist db
    define('DB_MASTER_HOST', '82.180.152.103');
    define('DB_MASTER_USER', 'u966959669_virtualstage');
    define('DB_MASTER_PASS', 'Ay$8&98[gG7');
    define('DB_MASTER_DBNAME', 'u966959669_virtualstage');

    define('DB_SLAVE_HOST', '82.180.152.103');
    define('DB_SLAVE_USER', 'u966959669_virtualstage');
    define('DB_SLAVE_PASS', 'Ay$8&98[gG7');
    define('DB_SLAVE_DBNAME', 'u966959669_virtualstage');

	// email
	define('EMAIL_SMTP_HOST', '');
	define('EMAIL_SMTP_USER', '');
	define('EMAIL_SMTP_PASS', '');
	define('EMAIL_SMTP_PORT', '');

	// sms config
	define('SMS_URL', '');
	define('SMS_ACCOUNT', '');
	define('SMS_PASS', '');
	define('SMS_BRAND_NAME', '');
}