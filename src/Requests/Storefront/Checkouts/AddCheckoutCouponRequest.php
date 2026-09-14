<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Storefront\Checkouts;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Add a Coupon code to a Checkout.
 *
 * @see https://developer.bigcommerce.com/docs/rest-storefront/checkouts/checkout-coupons#add-coupon-to-checkout
 */
class AddCheckoutCouponRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected readonly string $checkoutId,
        protected readonly string $couponCode,
        protected readonly ?int $version = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/checkouts/{$this->checkoutId}/coupons";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return array_filter([
            'couponCode' => $this->couponCode,
            'version' => $this->version,
        ], static fn (mixed $value): bool => $value !== null);
    }
}
