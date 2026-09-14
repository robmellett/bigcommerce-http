<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Channels;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * List Channels, optionally filtered by type, platform, status, or availability.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/channels#get-all-channels
 */
class GetChannelsRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  array<string, mixed>|null  $queryParams  e.g. ['type' => 'storefront', 'available' => true, 'limit' => 50]
     */
    public function __construct(
        protected readonly ?array $queryParams = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/channels';
    }

    protected function defaultQuery(): array
    {
        return $this->queryParams ?? [];
    }
}
