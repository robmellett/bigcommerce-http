<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Channels;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * List all Channel Listings for a specific Channel. Recommended for non-storefront channels
 * like marketplaces, marketing channels, and point of sale (POS) channels.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/channels/channel-listings#get-channel-listings
 */
class GetChannelListingsRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  array<string, mixed>|null  $queryParams  e.g. ['limit' => 50, 'product_id:in' => '80,100']
     */
    public function __construct(
        protected readonly int $channelId,
        protected readonly ?array $queryParams = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/channels/{$this->channelId}/listings";
    }

    protected function defaultQuery(): array
    {
        return $this->queryParams ?? [];
    }
}
