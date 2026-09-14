<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Orders;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get all Metafields belonging to a single Order.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/order-metafields#get-all-order-metafields
 */
class GetOrderMetafieldsRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  array<string, mixed>|null  $queryParams  e.g. ['namespace' => 'Sales Department']
     */
    public function __construct(
        protected readonly int $orderId,
        protected readonly ?array $queryParams = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/orders/{$this->orderId}/metafields";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryParams ?? [];
    }
}
