<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Storefront\Checkouts;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Add one or more Consignments to a Checkout, assigning line items to a
 * shipping address (requires `address` and `lineItems`) or a pickup method
 * (requires `pickupOption` and `lineItems`). Pass `include` with
 * `consignments.availableShippingOptions` to get shipping quotes back.
 *
 * @see https://developer.bigcommerce.com/docs/rest-storefront/checkouts/checkout-consignments#create-a-consignment
 */
class CreateCheckoutConsignmentRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<int, array<string, mixed>>  $consignments  e.g. [['address' => [...], 'lineItems' => [...]]]
     * @param  array<int, string>|null  $include
     */
    public function __construct(
        protected readonly string $checkoutId,
        protected readonly array $consignments,
        protected readonly ?array $include = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/checkouts/{$this->checkoutId}/consignments";
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function defaultBody(): array
    {
        return $this->consignments;
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'include' => $this->include !== null ? implode(',', $this->include) : null,
        ]);
    }
}
