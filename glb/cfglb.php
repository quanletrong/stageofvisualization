<?php
if (!defined('CUSTOM_CHECK_GLB')){header("Location: upgrade");die();}
else
{
	// define http protocol
	define('HTTP_PROTOCOL', 'https');

	//safelist db
    define('DB_MASTER_HOST', 'localhost');
    define('DB_MASTER_USER', 'u966959669_virtualstage');
    define('DB_MASTER_PASS', 'Ay$8&98[gG7');
    define('DB_MASTER_DBNAME', 'u966959669_virtualstage');

    define('DB_SLAVE_HOST', 'localhost');
    define('DB_SLAVE_USER', 'u966959669_virtualstage');
    define('DB_SLAVE_PASS', 'Ay$8&98[gG7');
    define('DB_SLAVE_DBNAME', 'u966959669_virtualstage');

	// email
	define('EMAIL_SMTP_HOST', 'ssl://smtp.hostinger.com');
	define('EMAIL_SMTP_USER', 'no-reply@quancoder.online');
	define('EMAIL_SMTP_PASS', 'Stageofvisualization@2023');
	define('EMAIL_SMTP_PORT', '465');

	// sms config
	define('SMS_URL', '');
	define('SMS_ACCOUNT', '');
	define('SMS_PASS', '');
	define('SMS_BRAND_NAME', '');
}