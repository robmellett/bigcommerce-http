<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create a Bulk Pricing Rule (quantity discount tier) for a Product.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/product-bulk-pricing-rules#create-bulk-pricing-rule
 */
class CreateBulkPricingRuleRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $payload  e.g. ['quantity_min' => 10, 'quantity_max' => 50, 'type' => 'price', 'amount' => 10]
     */
    public function __construct(
        protected readonly int $productId,
        protected readonly array $payload,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/catalog/products/{$this->productId}/bulk-pricing-rules";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->payload;
    }
}
