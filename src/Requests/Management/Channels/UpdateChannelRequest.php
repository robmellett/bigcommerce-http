<?php

declare(strict_types=1);

namespace RobMellett\BigcommerceHttp\Requests\Management\Channels;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update a Channel.
 *
 * @see https://developer.bigcommerce.com/docs/rest-management/channels#update-a-channel
 */
class UpdateChannelRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param  array<string, mixed>  $payload  e.g. ['name' => 'My Marketplace', 'status' => 'active']
     */
    public function __construct(
        protected readonly int $channelId,
        protected readonly array $payload,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/channels/{$this->channelId}";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->payload;
    }
}
