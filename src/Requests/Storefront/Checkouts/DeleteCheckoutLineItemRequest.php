<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Storefront\Checkouts;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete a Checkout line item.
 *
 * @see https://developer.bigcommerce.com/docs/rest-storefront/checkouts/checkout-cart-items#delete-a-line-item
 */
class DeleteCheckoutLineItemRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly string $checkoutId,
        protected readonly string $cartId,
        protected readonly string $itemId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/checkouts/{$this->checkoutId}/carts/{$this->cartId}/items/{$this->itemId}";
    }
}
