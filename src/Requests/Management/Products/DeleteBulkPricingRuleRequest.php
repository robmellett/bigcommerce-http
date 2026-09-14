<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete a Bulk Pricing Rule.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/product-bulk-pricing-rules#delete-a-bulk-pricing-rule
 */
class DeleteBulkPricingRuleRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly int $productId,
        protected readonly int $bulkPricingRuleId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/catalog/products/{$this->productId}/bulk-pricing-rules/{$this->bulkPricingRuleId}";
    }
}
