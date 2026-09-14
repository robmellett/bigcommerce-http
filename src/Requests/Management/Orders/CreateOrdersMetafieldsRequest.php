<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Orders;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create Metafields across multiple Orders in a single batch call.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/order-metafields/batch#create-orders-metafields
 */
class CreateOrdersMetafieldsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<int, array<string, mixed>>  $data  e.g. [['resource_id' => 100, 'key' => 'gift_message', 'value' => 'Happy Birthday', 'namespace' => 'Sales Department', 'permission_set' => 'read']]
     */
    public function __construct(
        protected readonly array $data,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/orders/metafields';
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }
}
