<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Storefront\Checkouts;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Add a Gift Certificate code to a Checkout as a payment method.
 *
 * @see https://developer.bigcommerce.com/docs/rest-storefront/checkouts/checkout-gift-certificates#add-gift-certificate-to-checkout
 */
class AddCheckoutGiftCertificateRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected readonly string $checkoutId,
        protected readonly string $giftCertificateCode,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/checkouts/{$this->checkoutId}/gift-certificates";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return [
            'giftCertificateCode' => $this->giftCertificateCode,
        ];
    }
}
