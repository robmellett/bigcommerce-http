<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Customers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get Metafields across all Customers.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/customers-metafields/batch#get-all-customers-metafields
 */
class GetAllCustomersMetafieldsRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  array<string, mixed>|null  $queryParams  e.g. ['resource_id:in' => '11,12', 'namespace' => 'Sales Department']
     */
    public function __construct(
        protected readonly ?array $queryParams = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/customers/metafields';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryParams ?? [];
    }
}
