<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Channels;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get the Site for a specific Channel. Alias of `GET /sites?channel_id=channel_id`.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/channels/channel-site#get-a-channel-site
 */
class GetChannelSiteRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $channelId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/channels/{$this->channelId}/site";
    }
}
