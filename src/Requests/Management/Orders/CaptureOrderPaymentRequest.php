<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Orders;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Capture a payment for an Order.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/order-payment-actions#capture-payment
 */
class CaptureOrderPaymentRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $payload  e.g. ['amount' => '19.95']
     */
    public function __construct(
        protected readonly int $orderId,
        protected readonly array $payload,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/orders/{$this->orderId}/payment_actions/capture";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->payload;
    }
}
