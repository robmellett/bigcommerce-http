<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get all Reviews for a Product.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/product-reviews#get-product-reviews
 */
class GetProductReviewsRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  array<string, mixed>|null  $queryParams  e.g. ['status' => 1, 'limit' => 50]
     */
    public function __construct(
        protected readonly int $productId,
        protected readonly ?array $queryParams = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/catalog/products/{$this->productId}/reviews";
    }

    protected function defaultQuery(): array
    {
        return $this->queryParams ?? [];
    }
}
