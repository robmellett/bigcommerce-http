<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Storefront\Carts;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get a Cart.
 *
 * @see https://developer.bigcommerce.com/docs/rest-storefront/carts#get-a-cart
 */
class GetCartRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  array<int, string>|null  $include  e.g. ['lineItems.physicalItems.options']
     */
    public function __construct(
        protected readonly ?array $include = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/carts';
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'include' => $this->include !== null ? implode(',', $this->include) : null,
        ]);
    }
}
