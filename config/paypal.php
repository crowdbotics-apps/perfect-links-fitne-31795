<?php

/**

 * PayPal Setting & API Credentials

 * Created by Raza Mehdi .

 */

     

return [

    'mode'    => env('PAYPAL_MODE', 'live'),

    'sandbox' => [

        'username'    => env('PAYPAL_SANDBOX_API_USERNAME', 'sb-43cnmh792450_api1.business.example.com'),

        'password'    => env('PAYPAL_SANDBOX_API_PASSWORD', 'JD5J67VN84QJQQTL'),

        'secret'      => env('PAYPAL_SANDBOX_API_SECRET', 'EMPJ0I-78LKzG4XzK4vtSyi8mRS1Fy9o4r43QhRFdnL7RKVi6OfPJfyNPqdLRcg6Tce494mOxS7UlEuc'),

        'certificate' => env('PAYPAL_SANDBOX_API_CERTIFICATE', 'AiDrK6.VwYYaEX2733DTiG2uFJiHAuwy4rS1VUKIqdsy-txUwQDpzXpw'),

        'app_id'      => 'APP-80W284485P519543T',

    ],

    'live' => [

        'username'    => env('PAYPAL_LIVE_API_USERNAME', 'plinksfitness_api1.gmail.com'),

        'password'    => env('PAYPAL_LIVE_API_PASSWORD', 'MJK6Y7XPMJGTBQ7R'),

        'secret'      => env('PAYPAL_LIVE_API_SECRET', 'ENo6kTUm8nWU5dN7AgZpW5k1plZZnoBHLxEuM4dRuBN-zypPgbllvrSmoGdDW8vCkh9Z9BhLAD5QDuAf'),

        'certificate' => env('PAYPAL_LIVE_API_CERTIFICATE', 'AcfOVolzUaAmJ5KmW7TnbCUDiboC4WPTvW9yIG5qCIjgyvNbPH_eO4tWpm-zYjp9V0G6TOK81tegdK1R'),

        'app_id'      => 'AGmi3g09shb7e1ffAfXPJB9kz4rZAe8mkY2DT-QTaCCzMyby.YD7ZqRz', 

    ],

    'payment_action' => 'Sale',

    'currency'       => env('PAYPAL_CURRENCY', 'USD'),

    'billing_type'   => 'MerchantInitiatedBilling',

    'notify_url'     => '',

    'locale'         => '',

    'validate_ssl'   => false,

];

