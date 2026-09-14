<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Orders;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get a single Refund by ID.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/order-refunds#get-a-refund
 */
class GetOrderRefundRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $refundId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/orders/payment_actions/refunds/{$this->refundId}";
    }
}
