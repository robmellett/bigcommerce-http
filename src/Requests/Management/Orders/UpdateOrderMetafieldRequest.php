<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Orders;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update a single Order Metafield.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/order-metafields#update-an-order-metafield
 */
class UpdateOrderMetafieldRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(
        protected readonly int $orderId,
        protected readonly int $metafieldId,
        protected readonly ?string $value = null,
        protected readonly ?string $permissionSet = null,
        protected readonly ?string $description = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/orders/{$this->orderId}/metafields/{$this->metafieldId}";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return array_filter([
            'value' => $this->value,
            'permission_set' => $this->permissionSet,
            'description' => $this->description,
        ], static fn (mixed $value): bool => $value !== null);
    }
}
