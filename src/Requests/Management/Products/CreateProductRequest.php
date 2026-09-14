<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create a Product.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/products#create-a-product
 */
class CreateProductRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $payload  e.g. ['name' => 'Smith Journal 13', 'type' => 'physical', 'sku' => 'SM-13', 'weight' => 0.5, 'price' => 19.95, 'categories' => [24]]
     * @param  array<string, mixed>|null  $queryParams  e.g. ['include_fields' => 'name,sku']
     */
    public function __construct(
        protected readonly array $payload,
        protected readonly ?array $queryParams = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/catalog/products';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->payload;
    }

    protected function defaultQuery(): array
    {
        return $this->queryParams ?? [];
    }
}
