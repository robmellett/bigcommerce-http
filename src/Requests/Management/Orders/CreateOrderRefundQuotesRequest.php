<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Orders;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Get a quote for refunding line items on an Order, without actually refunding them.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/order-refunds#create-order-refund-quotes
 */
class CreateOrderRefundQuotesRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $payload  e.g. ['lineItems' => [['id' => 1, 'quantity' => 1]]]
     */
    public function __construct(
        protected readonly int $orderId,
        protected readonly array $payload,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/orders/{$this->orderId}/payment_actions/refund_quotes";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->payload;
    }
}
