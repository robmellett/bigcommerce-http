<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Orders;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Delete multiple Order Metafields by ID in a single batch call.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/order-metafields/batch#delete-orders-metafields
 */
class DeleteOrdersMetafieldsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::DELETE;

    /**
     * @param  array<int, int>  $ids  e.g. [7, 8]
     */
    public function __construct(
        protected readonly array $ids,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/orders/metafields';
    }

    /**
     * @return array<int, int>
     */
    protected function defaultBody(): array
    {
        return $this->ids;
    }
}
