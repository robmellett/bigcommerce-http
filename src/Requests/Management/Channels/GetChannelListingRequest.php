<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Channels;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get a single Channel Listing for a specific Channel. Recommended for non-storefront channels
 * like marketplaces, marketing channels, and point of sale (POS) channels.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/channels/channel-listings#get-a-channel-listing
 */
class GetChannelListingRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $channelId,
        protected readonly int $listingId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/channels/{$this->channelId}/listings/{$this->listingId}";
    }
}
