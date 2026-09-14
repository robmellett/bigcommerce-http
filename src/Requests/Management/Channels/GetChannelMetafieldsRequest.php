<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Channels;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * List metafields on a Channel. Optional filter parameters can be passed in.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/channels/channel-metafields#get-channel-metafields
 */
class GetChannelMetafieldsRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  array<string, mixed>|null  $queryParams  e.g. ['namespace' => 'Sales Department', 'limit' => 50]
     */
    public function __construct(
        protected readonly int $channelId,
        protected readonly ?array $queryParams = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/channels/{$this->channelId}/metafields";
    }

    protected function defaultQuery(): array
    {
        return $this->queryParams ?? [];
    }
}
