<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Customers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get all Metafields belonging to a single Customer.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/customers-metafields#get-all-customer-metafields
 */
class GetCustomersMetafieldsRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  array<string, mixed>|null  $queryParams  e.g. ['namespace' => 'Sales Department']
     */
    public function __construct(
        protected readonly int $customerId,
        protected readonly ?array $queryParams = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/customers/{$this->customerId}/metafields";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryParams ?? [];
    }
}
