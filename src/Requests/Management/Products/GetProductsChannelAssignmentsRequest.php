<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get Product-Channel assignments (for MSF-enabled stores).
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/product-channel-assignments#get-products-channel-assignments
 */
class GetProductsChannelAssignmentsRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  array<string, mixed>|null  $queryParams  e.g. ['product_id:in' => '123,124', 'channel_id' => 1]
     */
    public function __construct(
        protected readonly ?array $queryParams = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/catalog/products/channel-assignments';
    }

    protected function defaultQuery(): array
    {
        return $this->queryParams ?? [];
    }
}
