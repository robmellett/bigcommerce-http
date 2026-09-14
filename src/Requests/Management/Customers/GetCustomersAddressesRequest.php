<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Customers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get all Customer Addresses. Accepts filter, sort, and pagination query parameters.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/customers-addresses#get-all-addresses
 */
class GetCustomersAddressesRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  array<string, mixed>|null  $queryParams  e.g. ['customer_id:in' => '11,12', 'limit' => 50]
     */
    public function __construct(
        protected readonly ?array $queryParams = null,
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
        return $this->queryParams ?? [];
    }
}
