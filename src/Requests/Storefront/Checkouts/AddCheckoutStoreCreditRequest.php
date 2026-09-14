<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Storefront\Checkouts;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Apply all available store credit to a Checkout (up to the order value).
 *
 * @see https://developer.bigcommerce.com/docs/rest-storefront/checkouts/checkout-store-credit#add-store-credit
 */
class AddCheckoutStoreCreditRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(
        protected readonly string $checkoutId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/checkouts/{$this->checkoutId}/store-credit";
    }
}
