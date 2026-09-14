<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Orders;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get Order settings for a specific Channel.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/order-settings#get-channel-order-settings
 */
class GetChannelOrderSettingsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $channelId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/orders/settings/channels/{$this->channelId}";
    }
}
