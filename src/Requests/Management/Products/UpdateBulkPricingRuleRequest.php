<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update a Bulk Pricing Rule.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/product-bulk-pricing-rules#update-a-bulk-pricing-rule
 */
class UpdateBulkPricingRuleRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param  array<string, mixed>  $payload  e.g. ['quantity_max' => 100, 'amount' => 15]
     */
    public function __construct(
        protected readonly int $productId,
        protected readonly int $bulkPricingRuleId,
        protected readonly array $payload,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/catalog/products/{$this->productId}/bulk-pricing-rules/{$this->bulkPricingRuleId}";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->payload;
    }
}
