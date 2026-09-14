<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update a Product Metafield. `namespace`, `key`, `permission_set`, and
 * `value` can only be modified by the API account that created the field.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/product-metafields#update-a-product-metafield
 */
class UpdateProductMetafieldRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(
        protected readonly int $productId,
        protected readonly int $metafieldId,
        protected readonly ?string $key = null,
        protected readonly ?string $value = null,
        protected readonly ?string $namespace = null,
        protected readonly ?string $permissionSet = null,
        protected readonly ?string $description = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/catalog/products/{$this->productId}/metafields/{$this->metafieldId}";
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
