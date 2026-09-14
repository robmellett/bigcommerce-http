<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Customers;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update a single Customer Metafield.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/customers-metafields#update-a-customer-metafield
 */
class UpdateCustomerMetafieldRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(
        protected readonly int $customerId,
        protected readonly int $metafieldId,
        protected readonly ?string $value = null,
        protected readonly ?string $permissionSet = null,
        protected readonly ?string $description = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/customers/{$this->customerId}/metafields/{$this->metafieldId}";
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
