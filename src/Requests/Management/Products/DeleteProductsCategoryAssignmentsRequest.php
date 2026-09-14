<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete Product-Category assignments (for MSF-enabled stores). At least
 * one filter must be supplied.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/product-category-assignments#delete-products-category-assignments
 */
class DeleteProductsCategoryAssignmentsRequest extends Request
{
    protected Method $method = Method::DELETE;

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
