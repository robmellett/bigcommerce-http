<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Channels;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get a single Channel by ID.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/channels#get-a-channel
 */
class GetChannelRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly int $channelId,
        protected readonly ?string $include = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/channels/{$this->channelId}";
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'include' => $this->include,
        ]);
    }
}
