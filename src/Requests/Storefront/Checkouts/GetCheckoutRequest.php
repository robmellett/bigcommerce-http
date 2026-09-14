<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Storefront\Checkouts;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get a Checkout. The cart ID and checkout ID are the same.
 *
 * @see https://developer.bigcommerce.com/docs/rest-storefront/checkouts#get-a-checkout
 */
class GetCheckoutRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  array<int, string>|null  $include  e.g. ['consignments.availableShippingOptions']
     */
    public function __construct(
        protected readonly string $checkoutId,
        protected readonly ?array $include = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/checkouts/{$this->checkoutId}";
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'include' => $this->include !== null ? implode(',', $this->include) : null,
        ]);
    }
}
