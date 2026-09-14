<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get all Complex Rules for a Product.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/product-complex-rules#get-product-complex-rules
 */
class GetProductComplexRulesRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  array<string, mixed>|null  $queryParams
     */
    public function __construct(
        protected readonly int $productId,
        protected readonly ?array $queryParams = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/catalog/products/{$this->productId}/complex-rules";
    }

    protected function defaultQuery(): array
    {
        return $this->queryParams ?? [];
    }
}
