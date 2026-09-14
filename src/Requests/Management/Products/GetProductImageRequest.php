<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get a single Product Image.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/product-images#get-a-product-image
 */
class GetProductImageRequest extends Request
{
    protected Method $method = Method::GET;

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
