<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Storefront\Checkouts;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update a single Checkout line item's quantity.
 *
 * @see https://developer.bigcommerce.com/docs/rest-storefront/checkouts/checkout-cart-items#update-a-line-item
 */
class UpdateCheckoutLineItemRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param  array<string, mixed>  $payload  e.g. ['lineItem' => ['productId' => 229, 'variantId' => 191, 'quantity' => 10]]
     */
    public function __construct(
        protected readonly string $checkoutId,
        protected readonly string $cartId,
        protected readonly string $itemId,
        protected readonly array $payload,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/checkouts/{$this->checkoutId}/carts/{$this->cartId}/items/{$this->itemId}";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->payload;
    }
}
