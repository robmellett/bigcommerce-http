<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Storefront\Checkouts;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update a Consignment's address/line items, or select a shipping or pickup
 * option via `shippingOptionId`/`pickupOptionId`.
 *
 * @see https://developer.bigcommerce.com/docs/rest-storefront/checkouts/checkout-consignments#update-a-consignment
 */
class UpdateCheckoutConsignmentRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param  array<string, mixed>  $payload  e.g. ['shippingOptionId' => '...', 'lineItems' => [...]]
     * @param  array<int, string>|null  $include
     */
    public function __construct(
        protected readonly string $checkoutId,
        protected readonly string $consignmentId,
        protected readonly array $payload,
        protected readonly ?array $include = null,
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
        return $this->payload;
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'include' => $this->include !== null ? implode(',', $this->include) : null,
        ]);
    }
}
