<?php

declare(strict_types=1);

// config for RobMellett/BigcommerceHttp
return [

    /*
    |--------------------------------------------------------------------------
    | Management API credentials
    |--------------------------------------------------------------------------
    |
    | Used by RobMellett\BigcommerceHttp\Connectors\ManagementApiConnector to
    | authenticate requests against the BigCommerce Management API (v3).
    | Generate an API account under Settings > API Accounts in your store's
    | control panel to obtain these values.
    |
    */

    'store_hash' => env('BIGCOMMERCE_STORE_HASH'),

    'access_token' => env('BIGCOMMERCE_ACCESS_TOKEN'),

    'client_id' => env('BIGCOMMERCE_CLIENT_ID'),

    /*
    |--------------------------------------------------------------------------
    | Storefront API domain
    |--------------------------------------------------------------------------
    |
    | Used by RobMellett\BigcommerceHttp\Connectors\StorefrontApiConnector to
    | build requests against a store's Storefront REST API, e.g.
    | "yourstore.example.com" or a custom domain attached to the storefront.
    |
    */

    'store_domain' => env('BIGCOMMERCE_STORE_DOMAIN'),

];
