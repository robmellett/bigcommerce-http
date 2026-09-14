<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete a single Product.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/products#delete-a-product
 */
class DeleteProductRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly int $productId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/catalog/products/{$this->productId}";
    }
}
