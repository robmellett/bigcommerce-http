<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Customers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get all Customer Form Field Values.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/customers-form-field-values#get-all-customer-form-field-values
 */
class GetCustomersFormFieldValuesRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  array<string, mixed>|null  $queryParams  e.g. ['customer_id:in' => '11,12']
     */
    public function __construct(
        protected readonly ?array $queryParams = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/customers/form-field-values';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryParams ?? [];
    }
}
