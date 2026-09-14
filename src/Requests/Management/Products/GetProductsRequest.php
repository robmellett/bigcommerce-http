<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get all Products. Accepts many optional filter, sort, pagination, and
 * include query parameters.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/products#get-all-products
 */
class GetProductsRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  array<string, mixed>|null  $queryParams  e.g. ['include' => 'images,variants', 'limit' => 50, 'page' => 1]
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
