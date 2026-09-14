<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Storefront\Carts;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete a Cart. Once deleted it cannot be recovered.
 *
 * @see https://developer.bigcommerce.com/docs/rest-storefront/carts#delete-a-cart
 */
class DeleteCartRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly string $cartId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/carts/{$this->cartId}";
    }
}
