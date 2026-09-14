<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update a Product Complex Rule.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/product-complex-rules#update-a-product-complex-rule
 */
class UpdateProductComplexRuleRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param  array<string, mixed>  $payload  e.g. ['enabled' => false]
     */
    public function __construct(
        protected readonly int $productId,
        protected readonly int $complexRuleId,
        protected readonly array $payload,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/catalog/products/{$this->productId}/complex-rules/{$this->complexRuleId}";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->payload;
    }
}
