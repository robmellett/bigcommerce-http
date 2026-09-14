<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Customers;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update Customer Form Field Values in a single batch call.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/customers-form-field-values#update-customer-form-field-values
 */
class UpdateCustomerFormFieldValuesRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param  array<int, array<string, mixed>>  $data  e.g. [['customer_id' => 11, 'name' => 'How did you hear about us?', 'value' => 'Google']]
     */
    public function __construct(
        protected readonly array $data,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/customers/form-field-values';
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }
}
