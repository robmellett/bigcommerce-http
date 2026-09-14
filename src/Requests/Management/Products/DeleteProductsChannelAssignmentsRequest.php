<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete Product-Channel assignments (for MSF-enabled stores). At least
 * one filter must be supplied.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/product-channel-assignments#delete-products-channel-assignments
 */
class DeleteProductsChannelAssignmentsRequest extends Request
{
    protected Method $method = Method::DELETE;

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
