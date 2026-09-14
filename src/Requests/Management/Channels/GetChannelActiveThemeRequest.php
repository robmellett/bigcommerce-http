<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Channels;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get the active theme configuration for a Channel's storefront.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/channels#get-a-channel-s-active-theme
 */
class GetChannelActiveThemeRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $channelId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/channels/{$this->channelId}/active-theme";
    }
}
