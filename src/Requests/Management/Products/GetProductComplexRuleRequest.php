<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get a single Product Complex Rule.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/product-complex-rules#get-a-product-complex-rule
 */
class GetProductComplexRuleRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $productId,
        protected readonly int $complexRuleId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/catalog/products/{$this->productId}/complex-rules/{$this->complexRuleId}";
    }
}
