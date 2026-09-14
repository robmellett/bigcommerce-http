<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Customers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete Customer Attribute Values matching the given filters.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/customers-attributes/attribute-values#delete-customer-attribute-values
 */
class DeleteCustomersAttributeValuesRequest extends Request
{
    protected Method $method = Method::DELETE;

    /**
     * @param  array<string, mixed>  $queryParams  e.g. ['customer_id:in' => '11,12']
     */
    public function __construct(
        protected readonly array $queryParams,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/customers/attribute-values';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryParams;
    }
}
