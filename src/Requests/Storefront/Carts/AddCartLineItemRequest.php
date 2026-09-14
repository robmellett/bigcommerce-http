<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Storefront\Carts;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Add line items to a Cart.
 *
 * @see https://developer.bigcommerce.com/docs/rest-storefront/carts/cart-items#add-cart-line-items
 */
class AddCartLineItemRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $payload  e.g. ['lineItems' => [['quantity' => 2, 'productId' => 230, 'variantId' => 124]], 'version' => 1]
     * @param  array<int, string>|null  $include
     */
    public function __construct(
        protected readonly string $cartId,
        protected readonly array $payload,
        protected readonly ?array $include = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/carts/{$this->cartId}/items";
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
