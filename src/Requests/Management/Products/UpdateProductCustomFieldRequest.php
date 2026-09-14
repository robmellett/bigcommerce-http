<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update a Product Custom Field.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/product-custom-fields#update-a-custom-field
 */
class UpdateProductCustomFieldRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(
        protected readonly int $productId,
        protected readonly int $customFieldId,
        protected readonly string $name,
        protected readonly string $value,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/catalog/products/{$this->productId}/custom-fields/{$this->customFieldId}";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return [
            'name' => $this->name,
            'value' => $this->value,
        ];
    }
}
