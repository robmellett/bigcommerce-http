<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Customers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete a single Customer Metafield.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/customers-metafields#delete-a-customer-metafield
 */
class DeleteCustomerMetafieldsIdRequest extends Request
{
    protected Method $method = Method::DELETE;

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
