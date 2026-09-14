<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Storefront\Carts;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create a Cart.
 *
 * @see https://developer.bigcommerce.com/docs/rest-storefront/carts#create-a-cart
 */
class CreateCartRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $payload  e.g. ['lineItems' => [['quantity' => 2, 'productId' => 230]], 'locale' => 'en']
     */
    public function __construct(
        protected readonly array $payload,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/carts';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->payload;
    }
}
