<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Customers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete Customer Addresses matching the given filters (e.g. by ID or customer ID).
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/customers-addresses#delete-addresses
 */
class DeleteCustomersAddressesRequest extends Request
{
    protected Method $method = Method::DELETE;

    /**
     * @param  array<string, mixed>  $queryParams  e.g. ['id:in' => '5,6,7']
     */
    public function __construct(
        protected readonly array $queryParams,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/customers/addresses';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryParams;
    }
}
