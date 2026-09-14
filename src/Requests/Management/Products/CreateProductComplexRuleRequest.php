<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create a Complex Rule that adjusts a Product's price, weight, image, or
 * availability based on selected option/modifier values.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/product-complex-rules#create-product-complex-rule
 */
class CreateProductComplexRuleRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $payload  e.g. ['enabled' => true, 'conditions' => [], 'price_adjuster' => ['adjuster' => 'relative', 'adjuster_value' => 10]]
     */
    public function __construct(
        protected readonly int $productId,
        protected readonly array $payload,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/catalog/products/{$this->productId}/complex-rules";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->payload;
    }
}
