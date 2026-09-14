<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create a Custom Field on a Product, e.g. a book's ISBN or a DVD's release
 * date, shown on the product page, orders, etc.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/product-custom-fields#create-a-custom-field
 */
class CreateProductCustomFieldRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected readonly int $productId,
        protected readonly string $name,
        protected readonly string $value,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/catalog/products/{$this->productId}/custom-fields";
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
