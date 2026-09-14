<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Customers;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update one or more Customer Attributes in a single batch call. Each item must include the attribute `id`.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/customers-attributes#update-customer-attributes
 */
class UpdateCustomersAttributesRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param  array<int, array<string, mixed>>  $data  e.g. [['id' => 3, 'name' => 'Anniversary']]
     */
    public function __construct(
        protected readonly array $data,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/customers/attributes';
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }
}
