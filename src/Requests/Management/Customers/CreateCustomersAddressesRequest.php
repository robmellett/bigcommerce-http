<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Customers;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create one or more Customer Addresses in a single batch call.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/customers-addresses#create-addresses
 */
class CreateCustomersAddressesRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<int, array<string, mixed>>  $data  e.g. [['customer_id' => 11, 'first_name' => 'Jane', 'last_name' => 'Doe', 'address1' => '123 Main St', 'city' => 'Austin', 'country_code' => 'US', 'postal_code' => '78751']]
     */
    public function __construct(
        protected readonly array $data,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/customers/addresses';
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }
}
