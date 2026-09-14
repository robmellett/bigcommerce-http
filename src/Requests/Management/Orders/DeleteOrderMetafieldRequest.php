<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Orders;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete a single Order Metafield.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/order-metafields#delete-an-order-metafield
 */
class DeleteOrderMetafieldRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly int $orderId,
        protected readonly int $metafieldId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/orders/{$this->orderId}/metafields/{$this->metafieldId}";
    }
}
