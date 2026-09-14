<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Channels;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get a single Channel Metafield.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/channels/channel-metafields#get-a-channel-metafield
 */
class GetChannelMetafieldRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $channelId,
        protected readonly int $metafieldId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/channels/{$this->channelId}/metafields/{$this->metafieldId}";
    }
}
