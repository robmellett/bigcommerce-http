<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Customers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get a Customer's consent preferences (e.g. marketing, cookies).
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/customers-consent#get-customer-consent
 */
class GetCustomerConsentRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $customerId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/customers/{$this->customerId}/consent";
    }
}
