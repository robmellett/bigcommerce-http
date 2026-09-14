<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Orders;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get the payment Transactions for an Order.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/order-transactions#get-order-transactions
 */
class GetOrderTransactionsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $orderId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/orders/{$this->orderId}/transactions";
    }
}
