<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Tests\Connectors;

use RobMellett\BigcommerceHttp\Connectors\ManagementApiConnector;
use RobMellett\BigcommerceHttp\Tests\TestCase;

class ManagementApiConnectorTest extends TestCase
{
    public function test_it_resolves_the_base_url_using_the_store_hash(): void
    {
        $connector = new ManagementApiConnector(storeHash: 'abc123', accessToken: 'token');

        $this->assertSame('https://api.bigcommerce.com/stores/abc123/v3', $connector->resolveBaseUrl());
    }

    public function test_it_sends_the_access_token_and_client_id_as_headers(): void
    {
        $connector = new ManagementApiConnector(storeHash: 'abc123', accessToken: 'token', clientId: 'client-id');

        $headers = $connector->headers()->all();

        $this->assertSame('token', $headers['X-Auth-Token']);
        $this->assertSame('client-id', $headers['X-Auth-Client']);
        $this->assertSame('application/json', $headers['Content-Type']);
    }

    public function test_it_omits_the_client_id_header_when_not_provided(): void
    {
        $connector = new ManagementApiConnector(storeHash: 'abc123', accessToken: 'token');

        $this->assertArrayNotHasKey('X-Auth-Client', $connector->headers()->all());
    }
}
