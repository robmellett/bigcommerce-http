<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Storefront\Checkouts;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Verify a Checkout was created by a human using a reCAPTCHA token.
 *
 * @see https://developer.bigcommerce.com/docs/rest-storefront/checkouts/checkout-spam-protection#checkout-spam-protection
 */
class CheckoutSpamProtectionRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected readonly string $checkoutId,
        protected readonly string $token,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/checkouts/{$this->checkoutId}/spam-protection";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return [
            'token' => $this->token,
        ];
    }
}
