<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Connectors;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;

/**
 * Connects to the BigCommerce Management API (v3).
 *
 * Requires a store hash and an API account access token. See
 * https://developer.bigcommerce.com/docs/start/authentication/rest-api
 * for how to generate an access token.
 */
class ManagementApiConnector extends Connector
{
    use AcceptsJson;
    use AlwaysThrowOnErrors;

    public function __construct(
        protected readonly string $storeHash,
        protected readonly string $accessToken,
        protected readonly ?string $clientId = null,
    ) {
    }

    public function resolveBaseUrl(): string
    {
        return "https://api.bigcommerce.com/stores/{$this->storeHash}/v3";
    }

    protected function defaultHeaders(): array
    {
        return array_filter([
            'X-Auth-Token' => $this->accessToken,
            'X-Auth-Client' => $this->clientId,
            'Content-Type' => 'application/json',
        ]);
    }
}
