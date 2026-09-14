<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Customers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get Customer settings for a specific Channel.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/customers-settings#get-customers-settings-for-a-channel
 */
class GetCustomersSettingsChannelRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $channelId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/customers/settings/channels/{$this->channelId}";
    }
}
