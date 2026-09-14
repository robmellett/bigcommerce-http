<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Storefront\Carts;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update the currency of a Cart. Promotions and gift certificates that don't
 * apply to the new currency are removed from the cart.
 *
 * @see https://developer.bigcommerce.com/docs/rest-storefront/carts/cart-currency#update-cart-currency
 */
class UpdateCartCurrencyRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<int, string>|null  $include
     */
    public function __construct(
        protected readonly string $cartId,
        protected readonly string $currencyCode,
        protected readonly ?array $include = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/carts/{$this->cartId}/currency";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return [
            'currencyCode' => $this->currencyCode,
        ];
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'include' => $this->include !== null ? implode(',', $this->include) : null,
        ]);
    }
}
