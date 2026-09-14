<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Channels;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete the Site for a specific Channel.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/channels/channel-site#delete-a-channel-site
 */
class DeleteChannelSiteRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly int $channelId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/channels/{$this->channelId}/site";
    }
}
