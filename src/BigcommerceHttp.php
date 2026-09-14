<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp;

use RobMellett\BigcommerceHttp\Connectors\ManagementApiConnector;
use RobMellett\BigcommerceHttp\Connectors\StorefrontApiConnector;

class BigcommerceHttp
{
    /**
     * Build a connector for the BigCommerce Management API (v3), authenticated
     * using the store hash and access token from the package config.
     */
    public function management(): ManagementApiConnector
    {
        return new ManagementApiConnector(
            storeHash: (string) config('bigcommerce-http.store_hash'),
            accessToken: (string) config('bigcommerce-http.access_token'),
            clientId: config('bigcommerce-http.client_id'),
        );
    }

    /**
     * Build a connector for a store's Storefront REST API, using the store
     * domain from the package config. Pass the shopper's storefront session
     * cookie when calling this API from a server-side context.
     */
    public function storefront(?string $cookie = null): StorefrontApiConnector
    {
        return new StorefrontApiConnector(
            storeDomain: (string) config('bigcommerce-http.store_domain'),
            cookie: $cookie,
        );
    }
}
