<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create multiple Product Metafields in a single batch call, each item must
 * include its `resource_id` (the product ID it belongs to).
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/batch-product-metafields#create-multiple-metafields
 */
class CreateProductsMetafieldsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<int, array<string, mixed>>  $data  e.g. [['resource_id' => 123, 'key' => 'location_id', 'value' => '4HG', 'namespace' => 'Warehouse Locations', 'permission_set' => 'read']]
     */
    public function __construct(
        protected readonly array $data,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/catalog/products/metafields';
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }
}
