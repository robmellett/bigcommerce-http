<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Orders;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Void a captured payment for an Order.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/order-payment-actions#void-payment
 */
class VoidOrderPaymentRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(
        protected readonly int $orderId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/orders/{$this->orderId}/payment_actions/void";
    }
}
