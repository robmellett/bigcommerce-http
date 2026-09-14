<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Customers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get a single Customer Metafield.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/customers-metafields#get-a-customer-metafield
 */
class GetMetafieldsCustomerIdRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $customerId,
        protected readonly int $metafieldId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/customers/{$this->customerId}/metafields/{$this->metafieldId}";
    }
}
