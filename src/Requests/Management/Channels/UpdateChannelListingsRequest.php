<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Channels;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update one or more Channel Listings for a specific Channel. Recommended for non-storefront
 * channels like marketplaces, marketing channels, and point of sale (POS) channels.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/channels/channel-listings#update-channel-listings
 */
class UpdateChannelListingsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param  array<int, array<string, mixed>>  $data  e.g. [['listing_id' => 882998595, 'product_id' => 80, 'state' => 'active', 'variants' => [['product_id' => 80, 'variant_id' => 64, 'state' => 'active']]]]
     */
    public function __construct(
        protected readonly int $channelId,
        protected readonly array $data,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/channels/{$this->channelId}/listings";
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function defaultBody(): array
    {
        return $this->data;
    }
}
