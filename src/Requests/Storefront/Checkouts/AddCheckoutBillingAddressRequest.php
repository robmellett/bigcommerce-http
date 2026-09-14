<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Storefront\Checkouts;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Add a billing address to an existing Checkout. Requires `countryCode`.
 *
 * @see https://developer.bigcommerce.com/docs/rest-storefront/checkouts/checkout-billing-address#add-checkout-billing-address
 */
class AddCheckoutBillingAddressRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $payload  e.g. ['firstName' => 'Jane', 'lastName' => 'Doe', 'countryCode' => 'US', ...]
     */
    public function __construct(
        protected readonly string $checkoutId,
        protected readonly array $payload,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/checkouts/{$this->checkoutId}/billing-address";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->payload;
    }
}
