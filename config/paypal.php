<?php
/**
 * PayPal Setting & API Credentials
 * Created by Raza Mehdi <srmk@outlook.com>.
 */

return [
    'mode'    => env('PAYPAL_MODE', 'sandbox'), // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
    'sandbox' => [
        'username'    => env('PAYPAL_SANDBOX_API_USERNAME', 'sb-b1jwc21523121_api1.business.example.com'),
        'password'    => env('PAYPAL_SANDBOX_API_PASSWORD', 'UEBT27E8MBYCXGB2'),
        'secret'      => env('PAYPAL_SANDBOX_API_SECRET', 'EOzB1X1d1b-zJiCeYDgSQSMw6E5EcmtM9mrH6oqbkqzBW5lpkhVBlPXkVpUhbKtjaUE0P_DCP9ya0rDf'),
        'certificate' => env('PAYPAL_SANDBOX_API_CERTIFICATE', 'AzZ8AXF9MVOsS4k2r1mqU-XIq2JiA-DWdVGSXP0Ci-Lg3DVhgyK8gPqz'),
        'app_id'      => 'APP-80W284485P519543T', // Used for testing Adaptive Payments API in sandbox mode
    ],
    'live' => [
        'username'    => env('PAYPAL_LIVE_API_USERNAME', ''),
        'password'    => env('PAYPAL_LIVE_API_PASSWORD', ''),
        'secret'      => env('PAYPAL_LIVE_API_SECRET', ''),
        'certificate' => env('PAYPAL_LIVE_API_CERTIFICATE', ''),
        'app_id'      => '', // Used for Adaptive Payments API
    ],

    'payment_action' => 'Sale', // Can only be 'Sale', 'Authorization' or 'Order'
    'currency'       => env('PAYPAL_CURRENCY', 'USD'),
    'billing_type'   => 'MerchantInitiatedBilling',
    'notify_url'     => '', // Change this accordingly for your application.
    'locale'         => '', // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
    'validate_ssl'   => true, // Validate SSL when creating api client.
];
