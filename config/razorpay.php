<?php
return [
    'key_id' => env('RAZORPAY_KEY_ID', ''),
    'key_secret' => env('RAZORPAY_KEY_SECRET', ''),
    'currency' => env('RAZORPAY_CURRENCY', 'INR'),
    'webhook_secret' => env('RAZORPAY_WEBHOOK_SECRET', ''),
];