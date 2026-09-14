<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Orders;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get a single Order Metafield.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/order-metafields#get-an-order-metafield
 */
class GetOrderMetafieldRequest extends Request
{
    protected Method $method = Method::GET;

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
