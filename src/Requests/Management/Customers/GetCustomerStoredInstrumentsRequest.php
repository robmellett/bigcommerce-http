<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Customers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get a Customer's stored payment instruments.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/customers-stored-instruments#get-customer-stored-instruments
 */
class GetCustomerStoredInstrumentsRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  array<string, mixed>|null  $queryParams  e.g. ['platform' => 'bigpay']
     */
    public function __construct(
        protected readonly int $customerId,
        protected readonly ?array $queryParams = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/customers/{$this->customerId}/stored-instruments";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryParams ?? [];
    }
}
