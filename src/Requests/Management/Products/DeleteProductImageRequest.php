<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete a Product Image.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/product-images#delete-a-product-image
 */
class DeleteProductImageRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly int $productId,
        protected readonly int $imageId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/catalog/products/{$this->productId}/images/{$this->imageId}";
    }
}
