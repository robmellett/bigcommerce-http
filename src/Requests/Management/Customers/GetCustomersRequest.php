<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Customers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get all Customers. Accepts many optional filter, sort, pagination, and include query parameters.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/customers#get-all-customers
 */
class GetCustomersRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  array<string, mixed>|null  $queryParams  e.g. ['email:in' => 'jane@example.com', 'limit' => 50]
     */
    public function __construct(
        protected readonly ?array $queryParams = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/customers';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryParams ?? [];
    }
}
