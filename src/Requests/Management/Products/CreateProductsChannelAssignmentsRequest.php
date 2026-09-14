<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Products;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Assign Products to Channels (for MSF-enabled stores). Note this operation
 * uses HTTP PUT per BigCommerce's API design.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/catalog/product-channel-assignments#create-products-channel-assignments
 */
class CreateProductsChannelAssignmentsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param  array<int, array<string, mixed>>  $data  e.g. [['product_id' => 123, 'channel_id' => 1]]
     */
    public function __construct(
        protected readonly array $data,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/catalog/products/channel-assignments';
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }
}
