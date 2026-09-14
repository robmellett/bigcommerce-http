<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Storefront\Carts;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update a Cart line item's quantity or options.
 *
 * @see https://developer.bigcommerce.com/docs/rest-storefront/carts/cart-items#update-cart-line-item
 */
class UpdateCartLineItemRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param  array<string, mixed>  $payload  e.g. ['lineItem' => ['productId' => 229, 'variantId' => 191, 'quantity' => 10], 'version' => 1]
     * @param  array<int, string>|null  $include
     */
    public function __construct(
        protected readonly string $cartId,
        protected readonly string $itemId,
        protected readonly array $payload,
        protected readonly ?array $include = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/carts/{$this->cartId}/items/{$this->itemId}";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->payload;
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'include' => $this->include !== null ? implode(',', $this->include) : null,
        ]);
    }
}
