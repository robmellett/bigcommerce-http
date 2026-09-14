<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Storefront\Customers;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Create a Customer from the storefront (e.g. during guest checkout).
 *
 * @see https://developer.bigcommerce.com/docs/rest-storefront/customers#create-a-customer
 */
class CreateCustomerRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $payload  e.g. ['firstName' => 'Jane', 'lastName' => 'Doe', 'email' => 'jane@example.com', 'password' => 'SecurePassword123!', 'acceptsMarketingEmails' => true]
     */
    public function __construct(
        protected readonly array $payload,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/customers';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->payload;
    }
}
