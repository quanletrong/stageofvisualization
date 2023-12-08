<?php

require_once ("app/appv3.1.4/libraries/paypal/autoload.php");

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;


// For test payments we want to enable the sandbox mode. If you want to put live
// payments through then this setting needs changing to `false`.
$enableSandbox = true;

// PayPal settings. Change these to your account details and the relevant URLs
// for your site.
$paypalConfig = [
    'client_id' => 'ASCWm_V76KLw3VAezYv8JJNyrJipJROjRpzzqLligNYw0PXD_gafFIU4bST7354x7bqbANmVZcIURloL',
    'client_secret' => 'EAtILCsdldsvTMUsvB6hHvslGJZjFBQIIRnjkJs7UPGPut1_BfnVoBtciSn7O_6KbvWG1LJChOxkOdJF',
    'return_url' => site_url('checkout/success'),
    'cancel_url' => site_url('checkout/cancle'),
];

// Database settings. Change these for your database configuration.
$dbConfig = [
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'name' => 'codeat21'
];

$apiContext = getApiContext($paypalConfig['client_id'], $paypalConfig['client_secret'], $enableSandbox);

/**
 * Set up a connection to the API
 *
 * @param string $clientId
 * @param string $clientSecret
 * @param bool   $enableSandbox Sandbox mode toggle, true for test payments
 * @return \PayPal\Rest\ApiContext
 */
function getApiContext($clientId, $clientSecret, $enableSandbox = false)
{
    $apiContext = new ApiContext(
        new OAuthTokenCredential($clientId, $clientSecret)
    );

    $apiContext->setConfig([
        'mode' => $enableSandbox ? 'sandbox' : 'live'
    ]);

    return $apiContext;
}
