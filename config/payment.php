<?php
return [
    'idfc' => [
        'merchant_id' => env('IDFC_MERCHANT_ID'),
        'api_key' => env('IDFC_API_KEY'),
        'payment_url' => env('IDFC_PAYMENT_URL'),
        'callback_url' => env('IDFC_CALLBACK_URL'),
        'key_secret' => env('IDFC_KEY_SECRET'),
    ],
];
