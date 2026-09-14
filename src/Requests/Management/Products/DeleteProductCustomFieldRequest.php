<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete a Product Custom Field.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/product-custom-fields#delete-a-custom-field
 */
class DeleteProductCustomFieldRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly int $productId,
        protected readonly int $customFieldId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/catalog/products/{$this->productId}/custom-fields/{$this->customFieldId}";
    }
}
