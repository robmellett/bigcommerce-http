<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete a Product Video.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/product-videos#delete-a-product-video
 */
class DeleteProductVideoRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly int $productId,
        protected readonly int $videoId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/catalog/products/{$this->productId}/videos/{$this->videoId}";
    }
}
