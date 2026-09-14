<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Storefront\Checkouts;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update a Checkout's customer message (2000 character limit).
 *
 * @see https://developer.bigcommerce.com/docs/rest-storefront/checkouts#update-customer-messages
 */
class UpdateCheckoutRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(
        protected readonly string $checkoutId,
        protected readonly string $customerMessage,
        protected readonly ?int $version = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/checkouts/{$this->checkoutId}";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return array_filter([
            'customerMessage' => $this->customerMessage,
            'version' => $this->version,
        ], static fn (mixed $value): bool => $value !== null);
    }
}
