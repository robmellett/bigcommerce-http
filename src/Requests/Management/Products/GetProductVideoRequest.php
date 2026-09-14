<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get a single Product Video.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/product-videos#get-a-product-video
 */
class GetProductVideoRequest extends Request
{
    protected Method $method = Method::GET;

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
