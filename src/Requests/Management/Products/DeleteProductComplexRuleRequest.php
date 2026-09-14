<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete a Product Complex Rule.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/product-complex-rules#delete-a-product-complex-rule
 */
class DeleteProductComplexRuleRequest extends Request
{
    protected Method $method = Method::DELETE;

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
