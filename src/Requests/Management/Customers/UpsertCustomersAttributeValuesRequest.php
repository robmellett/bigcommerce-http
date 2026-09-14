<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Customers;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create or update Customer Attribute Values in a single batch call.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/customers-attributes/attribute-values#upsert-customer-attribute-values
 */
class UpsertCustomersAttributeValuesRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param  array<int, array<string, mixed>>  $data  e.g. [['attribute_id' => 3, 'customer_id' => 11, 'attribute_value' => '1990-05-04']]
     */
    public function __construct(
        protected readonly array $data,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/customers/attribute-values';
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }
}
