<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Storefront\Checkouts;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update an existing billing address on a Checkout.
 *
 * @see https://developer.bigcommerce.com/docs/rest-storefront/checkouts/checkout-billing-address#update-checkout-billing-address
 */
class UpdateCheckoutBillingAddressRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param  array<string, mixed>  $payload  e.g. ['firstName' => 'Jane', 'lastName' => 'Doe', 'countryCode' => 'US', ...]
     */
    public function __construct(
        protected readonly string $checkoutId,
        protected readonly string $addressId,
        protected readonly array $payload,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/checkouts/{$this->checkoutId}/billing-address/{$this->addressId}";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->payload;
    }
}
