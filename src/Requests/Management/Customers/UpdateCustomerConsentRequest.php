<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Customers;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update a Customer's consent preferences.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/customers-consent#update-customer-consent
 */
class UpdateCustomerConsentRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param  array<string, mixed>  $payload  e.g. ['channel_id' => 1, 'consent' => ['tracking' => true, 'performance_cookie' => true]]
     */
    public function __construct(
        protected readonly int $customerId,
        protected readonly array $payload,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/customers/{$this->customerId}/consent";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->payload;
    }
}
