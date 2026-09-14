<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Channels;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get all Channel Metafields across all Channels.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/channels/channel-batch-metafields#get-all-channel-metafields
 */
class GetChannelsMetafieldsRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  array<string, mixed>|null  $queryParams  e.g. ['namespace' => 'Sales Department', 'limit' => 50]
     */
    public function __construct(
        protected readonly ?array $queryParams = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/channels/metafields';
    }

    protected function defaultQuery(): array
    {
        return $this->queryParams ?? [];
    }
}
