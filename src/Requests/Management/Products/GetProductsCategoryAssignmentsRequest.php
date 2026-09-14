<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get Product-Category assignments (for MSF-enabled stores).
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/product-category-assignments#get-products-category-assignments
 */
class GetProductsCategoryAssignmentsRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  array<string, mixed>|null  $queryParams  e.g. ['product_id:in' => '123,124', 'category_id' => 24]
     */
    public function __construct(
        protected readonly ?array $queryParams = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/catalog/products/category-assignments';
    }

    protected function defaultQuery(): array
    {
        return $this->queryParams ?? [];
    }
}
