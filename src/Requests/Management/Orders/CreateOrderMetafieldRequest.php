<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Orders;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create a Metafield on an Order.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/order-metafields#create-an-order-metafield
 */
class CreateOrderMetafieldRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected readonly int $orderId,
        protected readonly string $key,
        protected readonly string $value,
        protected readonly string $namespace,
        protected readonly string $permissionSet = 'read',
        protected readonly ?string $description = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/orders/{$this->orderId}/metafields";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return array_filter([
            'key' => $this->key,
            'value' => $this->value,
            'namespace' => $this->namespace,
            'permission_set' => $this->permissionSet,
            'description' => $this->description,
        ], static fn (mixed $value): bool => $value !== null);
    }
}
