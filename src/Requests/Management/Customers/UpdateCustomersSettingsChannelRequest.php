<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Customers;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update Customer settings for a specific Channel.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/customers-settings#update-customers-settings-for-a-channel
 */
class UpdateCustomersSettingsChannelRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param  array<string, mixed>  $payload  e.g. ['registration_enabled' => true]
     */
    public function __construct(
        protected readonly int $channelId,
        protected readonly array $payload,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/customers/settings/channels/{$this->channelId}";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->payload;
    }
}
