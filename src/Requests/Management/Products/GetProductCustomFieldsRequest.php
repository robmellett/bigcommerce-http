<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get all Custom Fields for a Product.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/product-custom-fields#get-all-custom-fields
 */
class GetProductCustomFieldsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $productId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/catalog/products/{$this->productId}/custom-fields";
    }
}
