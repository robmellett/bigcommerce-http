<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Orders;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update the store's global Order settings.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/order-settings#update-global-order-settings
 */
class UpdateGlobalOrderSettingsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param  array<string, mixed>  $payload  e.g. ['is_multiple_notifications_enabled' => true]
     */
    public function __construct(
        protected readonly array $payload,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/orders/settings';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->payload;
    }
}
