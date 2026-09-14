<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update Products in batch. Batches are limited to 10 products; each item
 * must include its `id`.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/products#update-products
 */
class UpdateProductsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param  array<int, array<string, mixed>>  $data  e.g. [['id' => 123, 'name' => 'Smith Journal 13', 'price' => 19.95]]
     * @param  array<string, mixed>|null  $queryParams  e.g. ['include_fields' => 'name,sku']
     */
    public function __construct(
        protected readonly array $data,
        protected readonly ?array $queryParams = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/catalog/products';
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }

    protected function defaultQuery(): array
    {
        return $this->queryParams ?? [];
    }
}
