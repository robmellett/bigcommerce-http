<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Tests\Connectors;

use RobMellett\BigcommerceHttp\Connectors\StorefrontApiConnector;
use RobMellett\BigcommerceHttp\Tests\TestCase;

class StorefrontApiConnectorTest extends TestCase
{
    public function test_it_resolves_the_base_url_using_the_store_domain(): void
    {
        $connector = new StorefrontApiConnector(storeDomain: 'store.example.com');

        $this->assertSame('https://store.example.com/api/storefront', $connector->resolveBaseUrl());
    }

    public function test_it_sends_the_shopper_cookie_as_a_header_when_provided(): void
    {
        $connector = new StorefrontApiConnector(storeDomain: 'store.example.com', cookie: 'session=abc');

        $headers = $connector->headers()->all();

        $this->assertSame('session=abc', $headers['Cookie']);
        $this->assertSame('application/json', $headers['Content-Type']);
    }

    public function test_it_omits_the_cookie_header_when_not_provided(): void
    {
        $connector = new StorefrontApiConnector(storeDomain: 'store.example.com');

        $this->assertArrayNotHasKey('Cookie', $connector->headers()->all());
    }
}
