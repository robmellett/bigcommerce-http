<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete Products matching the given filters (e.g. `id:in`, `sku`, `brand_id`).
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/products#delete-products
 */
class DeleteProductsRequest extends Request
{
    protected Method $method = Method::DELETE;

    /**
     * @param  array<string, mixed>|null  $queryParams  e.g. ['id:in' => '123,124,125']
     */
    public function __construct(
        protected readonly ?array $queryParams = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/catalog/products';
    }

    protected function defaultQuery(): array
    {
        return $this->queryParams ?? [];
    }
}
