<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Channels;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * List the control panel side navigation menus for a Channel.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/channels/channel-menus#get-channel-menus
 */
class GetChannelMenusRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $channelId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/channels/{$this->channelId}/channel-menus";
    }
}
