<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get a single Bulk Pricing Rule.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/product-bulk-pricing-rules#get-a-bulk-pricing-rule
 */
class GetBulkPricingRuleRequest extends Request
{
    protected Method $method = Method::GET;

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
