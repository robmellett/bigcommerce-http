<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Orders;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get the Refunds for a single Order.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/order-refunds#get-order-refunds
 */
class GetOrderRefundsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $orderId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/orders/{$this->orderId}/payment_actions/refunds";
    }
}
