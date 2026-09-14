<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get a single Product Review.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/product-reviews#get-a-product-review
 */
class GetProductReviewRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $productId,
        protected readonly int $reviewId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/catalog/products/{$this->productId}/reviews/{$this->reviewId}";
    }
}
