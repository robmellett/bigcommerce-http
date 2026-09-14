<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Customers;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create Metafields across multiple Customers in a single batch call.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/customers-metafields/batch#create-customers-metafields
 */
class CreateCustomersMetafieldsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<int, array<string, mixed>>  $data  e.g. [['resource_id' => 11, 'key' => 'vip', 'value' => 'true', 'namespace' => 'Sales Department', 'permission_set' => 'read']]
     */
    public function __construct(
        protected readonly array $data,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/customers/metafields';
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }
}
