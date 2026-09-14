<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Orders;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get Metafields across all Orders.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/order-metafields/batch#get-all-orders-metafields
 */
class GetOrdersMetafieldsRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  array<string, mixed>|null  $queryParams  e.g. ['resource_id:in' => '100,101', 'namespace' => 'Sales Department']
     */
    public function __construct(
        protected readonly ?array $queryParams = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/orders/metafields';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryParams ?? [];
    }
}
