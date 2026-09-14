<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Storefront\Checkouts;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Remove a Consignment from a Checkout.
 *
 * @see https://developer.bigcommerce.com/docs/rest-storefront/checkouts/checkout-consignments#delete-a-consignment
 */
class DeleteCheckoutConsignmentRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly string $checkoutId,
        protected readonly string $consignmentId,
        protected readonly ?int $version = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/checkouts/{$this->checkoutId}/consignments/{$this->consignmentId}";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return array_filter(
            ['version' => $this->version],
            static fn (mixed $value): bool => $value !== null,
        );
    }
}
