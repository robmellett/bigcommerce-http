<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Storefront\Checkouts;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Remove store credit from a Checkout.
 *
 * @see https://developer.bigcommerce.com/docs/rest-storefront/checkouts/checkout-store-credit#remove-store-credit
 */
class RemoveCheckoutStoreCreditRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly string $checkoutId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/checkouts/{$this->checkoutId}/store-credit";
    }
}
