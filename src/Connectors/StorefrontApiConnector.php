<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Connectors;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;

/**
 * Connects to a store's Storefront REST API (/api/storefront/...).
 *
 * This API authenticates via same-origin CORS / the visitor's storefront
 * session cookie rather than an API account token, so no BigCommerce
 * credentials are required. When calling it from a Laravel backend (rather
 * than the browser), forward the shopper's storefront session cookie so
 * BigCommerce can resolve their cart/checkout/customer session. See
 * https://developer.bigcommerce.com/docs/start/authentication/rest-storefront
 */
class StorefrontApiConnector extends Connector
{
    use AcceptsJson;
    use AlwaysThrowOnErrors;

    public function __construct(
        protected readonly string $storeDomain,
        protected readonly ?string $cookie = null,
    ) {
    }

    public function resolveBaseUrl(): string
    {
        return "https://{$this->storeDomain}/api/storefront";
    }

    protected function defaultHeaders(): array
    {
        return array_filter([
            'Content-Type' => 'application/json',
            'Cookie' => $this->cookie,
        ]);
    }
}
