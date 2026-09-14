<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get all Product Metafields across the whole catalog (batch metafields).
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/batch-product-metafields#get-all-product-metafields
 */
class GetProductsMetafieldsRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  array<string, mixed>|null  $queryParams  e.g. ['namespace' => 'Warehouse Locations', 'limit' => 50]
     */
    public function __construct(
        protected readonly ?array $queryParams = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/catalog/products/metafields';
    }

    protected function defaultQuery(): array
    {
        return $this->queryParams ?? [];
    }
}
