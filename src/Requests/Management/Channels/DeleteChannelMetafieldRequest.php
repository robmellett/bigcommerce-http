<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Channels;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete a single Channel Metafield.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/channels/channel-metafields#delete-a-channel-metafield
 */
class DeleteChannelMetafieldRequest extends Request
{
    protected Method $method = Method::DELETE;

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
