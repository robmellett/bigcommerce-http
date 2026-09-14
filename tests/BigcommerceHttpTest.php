<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Tests;

use RobMellett\BigcommerceHttp\BigcommerceHttp;
use RobMellett\BigcommerceHttp\Connectors\ManagementApiConnector;
use RobMellett\BigcommerceHttp\Connectors\StorefrontApiConnector;

class BigcommerceHttpTest extends TestCase
{
    public function test_it_builds_a_management_connector_from_config(): void
    {
        config()->set('bigcommerce-http.store_hash', 'abc123');
        config()->set('bigcommerce-http.access_token', 'token');
        config()->set('bigcommerce-http.client_id', 'client-id');

        $connector = (new BigcommerceHttp)->management();

        $this->assertInstanceOf(ManagementApiConnector::class, $connector);
        $this->assertSame('https://api.bigcommerce.com/stores/abc123/v3', $connector->resolveBaseUrl());

        $headers = $connector->headers()->all();

        $this->assertSame('token', $headers['X-Auth-Token']);
        $this->assertSame('client-id', $headers['X-Auth-Client']);
    }

    public function test_it_builds_a_storefront_connector_from_config_with_an_optional_cookie(): void
    {
        config()->set('bigcommerce-http.store_domain', 'store.example.com');

        $connector = (new BigcommerceHttp)->storefront('session=abc');

        $this->assertInstanceOf(StorefrontApiConnector::class, $connector);
        $this->assertSame('https://store.example.com/api/storefront', $connector->resolveBaseUrl());
        $this->assertSame('session=abc', $connector->headers()->all()['Cookie']);
    }

    public function test_it_builds_a_storefront_connector_without_a_cookie(): void
    {
        config()->set('bigcommerce-http.store_domain', 'store.example.com');

        $connector = (new BigcommerceHttp)->storefront();

        $this->assertArrayNotHasKey('Cookie', $connector->headers()->all());
    }
}
