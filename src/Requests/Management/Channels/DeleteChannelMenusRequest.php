<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Channels;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete the control panel side navigation menus for a Channel.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/channels/channel-menus#delete-channel-menus
 */
class DeleteChannelMenusRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly int $channelId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/channels/{$this->channelId}/channel-menus";
    }
}
