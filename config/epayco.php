<?php

return [
    'public_key' => env('EPAYCO_PUBLIC_KEY'),
    'private_key' => env('EPAYCO_PRIVATE_KEY'),
    'test' => filter_var(env('EPAYCO_TEST', true), FILTER_VALIDATE_BOOL),
    'lang' => env('EPAYCO_LANG', 'ES'),
    'currency' => env('EPAYCO_CURRENCY', 'COP'),
    'country' => env('EPAYCO_COUNTRY', 'CO'),
    'response_url' => env('EPAYCO_RESPONSE_URL'),
    'confirmation_url' => env('EPAYCO_CONFIRMATION_URL'),
    'validation_url' => env('EPAYCO_VALIDATION_URL', 'https://secure.epayco.co/validation/v1/reference/'),
    'min_amount' => env('EPAYCO_MIN_AMOUNT'),
    'max_amount' => env('EPAYCO_MAX_AMOUNT'),
    'sales_fee' => [
        'aggregator_percentage_rate' => env('EPAYCO_AGGREGATOR_PERCENTAGE_RATE', 2.68),
        'aggregator_fixed_fee' => env('EPAYCO_AGGREGATOR_FIXED_FEE', 900),
    ],
];
