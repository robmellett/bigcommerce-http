<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Orders;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Refund line items on an Order.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/order-refunds#create-an-order-refund
 */
class CreateOrderRefundRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $payload  e.g. ['lineItems' => [['id' => 1, 'quantity' => 1]], 'reason' => 'Customer request']
     */
    public function __construct(
        protected readonly int $orderId,
        protected readonly array $payload,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/orders/{$this->orderId}/payment_actions/refunds";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->payload;
    }
}
