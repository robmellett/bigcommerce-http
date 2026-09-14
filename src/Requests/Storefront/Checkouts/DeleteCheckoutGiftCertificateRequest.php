<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Storefront\Checkouts;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete a Gift Certificate payment method from a Checkout.
 *
 * @see https://developer.bigcommerce.com/docs/rest-storefront/checkouts/checkout-gift-certificates#delete-gift-certificate
 */
class DeleteCheckoutGiftCertificateRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly string $checkoutId,
        protected readonly string $giftCertificateCode,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/checkouts/{$this->checkoutId}/gift-certificates/{$this->giftCertificateCode}";
    }
}
