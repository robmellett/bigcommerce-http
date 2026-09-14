<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get a single Product.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/products#get-a-product
 */
class GetProductRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  array<string, mixed>|null  $queryParams  e.g. ['include' => 'images,variants,custom_fields']
     */
    public function __construct(
        protected readonly int $productId,
        protected readonly ?array $queryParams = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/catalog/products/{$this->productId}";
    }

    protected function defaultQuery(): array
    {
        return $this->queryParams ?? [];
    }
}
